<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupController extends Controller
{
 public function index()
{
    $dir = storage_path('app/backups');
    $this->ensureDirectory($dir);

    $paths = glob($dir . DIRECTORY_SEPARATOR . '*.sql') ?: [];

    $files = collect($paths)->map(function ($full) {
        $basename = basename($full);
        $storageRelative = 'backups/'.$basename; // para descargar

        // Intentar leer metadata
        $metaFull = preg_replace('/\.sql$/', '.meta.json', $full);
        $createdAt = null;
        $byUser    = null;

        if (is_file($metaFull)) {
            $meta = json_decode(@file_get_contents($metaFull), true);
            $createdAt = $meta['created_at'] ?? null;
            $byUser    = $meta['by'] ?? null;
        }

        // Fallback a fecha por filemtime
        if (!$createdAt && is_file($full)) {
            $createdAt = \Carbon\Carbon::createFromTimestamp(@filemtime($full))->toDateTimeString();
        }

        // Fallback a usuario contenido en el nombre del archivo
        if (!$byUser) {
            // Ej: database_2025_10_27_01_36_10_Manfredy.sql → "Manfredy"
            if (preg_match('/^database_\d{4}_\d{2}_\d{2}_\d{2}_\d{2}_\d{2}_(.+)\.sql$/', $basename, $m)) {
                $byUser = $m[1];
            } else {
                $byUser = '—';
            }
        }

        return [
            'storage_path' => $storageRelative,
            'name'         => $basename,
            'size_kb'      => is_file($full) ? round(filesize($full)/1024, 1) : 0,
            'created_at'   => $createdAt,
            'by'           => $byUser,
        ];
    })
    ->sortByDesc(fn($i) => $i['created_at'] ?? '')
    ->values();

    return view('backup.backup', compact('files'));
}



    /**
     * Genera .sql
     * mode: 'save' (guarda) | 'download' (descarga)
     */
public function generar(Request $request)
{
    $storageDir = storage_path('app/backups');
    if (!is_dir($storageDir)) @mkdir($storageDir, 0775, true);

    // Toma el nombre del usuario logueado (según tu tabla 'usuario' usas 'nombre')
    $userName = auth()->check()
        ? (auth()->user()->nombres ?? auth()->user()->nombre ?? auth()->user()->name ?? auth()->user()->email ?? 'anon')
        : 'anon';

    // Evitar espacios y caracteres raros en el nombre del archivo
    $userSlug = preg_replace('/[^A-Za-z0-9_\-\.]+/', '_', $userName);

    $filename = 'database_' . now()->format('Y_m_d_H_i_s') . '_' . $userSlug . '.sql';
    $full     = $storageDir . DIRECTORY_SEPARATOR . $filename;

    [$ok, $msg] = $this->dumpDatabaseTo($full);

    if (!$ok) {
        @file_put_contents($full, "-- WARNING: {$msg}\n");
        return back()
            ->with('error', 'No se pudo generar la base de datos. Revisa la configuración.')
            ->with('dump_error', $msg);
    }

    // === NUEVO: guardar metadata con el usuario y la fecha ===
    $meta = [
        'created_at' => now()->format('Y-m-d H:i:s'),
        'by'         => $userName,
    ];
    $metaFull = preg_replace('/\.sql$/', '.meta.json', $full);
    @file_put_contents($metaFull, json_encode($meta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

    if ($request->input('mode') === 'download') {
        return response()->download($full)->deleteFileAfterSend(false);
    }

    return back()->with('success', 'Respaldo SQL generado correctamente.');
}



    // Eliminar 1 backup (y su .meta.json)
public function destroy(\Illuminate\Http\Request $request)
{
    $relative = $request->input('path', '');
    if (!is_string($relative) || $relative === '' || !str_starts_with($relative, 'backups/') || !str_ends_with($relative, '.sql')) {
        return redirect()->route('backups.index')->with('error', 'Ruta inválida.');
    }

    $full = storage_path('app/'.$relative);
    $meta = preg_replace('/\.sql$/', '.meta.json', $full);

    if (!is_file($full)) {
        return redirect()->route('backups.index')->with('error', 'El respaldo no existe o ya fue eliminado.');
    }

    try {
        @unlink($full);
        if (is_file($meta)) @unlink($meta);
        return redirect()->route('backups.index')->with('success', 'Respaldo eliminado correctamente.');
    } catch (\Exception $e) {
        return redirect()->route('backups.index')->with('error', 'Error al eliminar: '.$e->getMessage());
    }
}



// Eliminar backups viejos (>= X días)
public function purgeOld(Request $request)
{
    // toma del form o del .env
    $days = (int)($request->input('days') ?? env('BACKUP_RETENTION_DAYS', 15));
    if ($days < 1) $days = 1;

    $dir = storage_path('app/backups');
    $this->ensureDirectory($dir);

    $now = time();
    $deleted = 0;

    foreach (glob($dir.DIRECTORY_SEPARATOR.'*.sql') ?: [] as $full) {
        $ageDays = ($now - @filemtime($full)) / 86400;
        if ($ageDays >= $days) {
            $meta = preg_replace('/\.sql$/', '.meta.json', $full);
            if (is_file($full)) { @unlink($full); $deleted++; }
            if (is_file($meta)) { @unlink($meta); }
        }
    }

    return back()->with('success', "Depuración completa. Eliminados: {$deleted} (>= {$days} días).");
}


  public function descargar(Request $request)
{
    $token = $request->query('f', '');
    if ($token === '') abort(400, 'Falta parámetro');

    // base64url decode seguro
    $b64 = strtr($token, '-_', '+/');
    $relative = base64_decode($b64);

    // seguridad
    if (!is_string($relative) || !str_starts_with($relative, 'backups/') || !str_ends_with($relative, '.sql')) {
        abort(400, 'Ruta inválida.');
    }

    $full = storage_path('app/'.$relative);
    if (!is_file($full)) abort(404, 'Respaldo no encontrado');

    return response()->download($full);
}


    // ----------------- helpers -----------------
private function dumpDatabaseTo(string $destFullPath): array
{
    $bin = env('BACKUP_MYSQLDUMP_PATH', 'C:/xampp/mysql/bin/mysqldump.exe');
    if (!is_file($bin)) {
        return [false, "No se encontró mysqldump en: {$bin}"];
    }

    $host = env('DB_HOST', '127.0.0.1');
    $port = env('DB_PORT', '3306');
    $user = env('DB_USERNAME', 'root');
    $pass = env('DB_PASSWORD', '');
    $db   = env('DB_DATABASE');
    if (!$db) return [false, 'DB_DATABASE vacío en .env'];

    // Detectar si el binario/report es MySQL o MariaDB
    $verOut = [];
    $verCode = 0;
    exec('"'.$bin.'" --version 2>&1', $verOut, $verCode);
    $verStr = strtolower(implode(' ', $verOut));
    $isMaria = (strpos($verStr, 'mariadb') !== false);

    // Flags base compatibles
    $flags = [
        '--host=' . $host,
        '--port=' . $port,
        '--user=' . $user,
        '--password=' . $pass,     // importante para pass vacía (no usar -p)
        '--databases ' . $db,
        '--skip-comments',
        '--routines',
        '--events',
        '--single-transaction',
        '--quick',
        '--no-tablespaces',        // evita errores al importar
        '--triggers',
        '--add-drop-table',
        '--default-character-set=utf8mb4',
    ];

    // Solo para MySQL (NO MariaDB)
    if (!$isMaria) {
        $flags[] = '--set-gtid-purged=OFF';
        $flags[] = '--column-statistics=0';
        // $flags[] = '--skip-tz-utc'; // opcional si te mete temas de zona horaria
    }

    $cmd = '"'.$bin.'" '.implode(' ', $flags).' --result-file="'. $destFullPath .'" 2>&1';

    $output = [];
    $code   = 0;
    exec($cmd, $output, $code);

    if ($code !== 0 || !is_file($destFullPath) || filesize($destFullPath) < 10) {
        $msg = "mysqldump falló (code {$code}). CMD:\n{$cmd}\n----\n".implode("\n", $output);
        return [false, $msg];
    }

    return [true, 'OK'];
}




    protected function resolveMySqlDumpPath(): string
    {
        $fromEnv = env('BACKUP_MYSQLDUMP_PATH');
        if ($fromEnv) {
            $normalized = str_replace('/', DIRECTORY_SEPARATOR, $fromEnv);
            if (file_exists($normalized)) return $normalized;
        }
        $candidates = [
            'C:\xampp\mysql\bin\mysqldump.exe',
            'C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump.exe',
            'C:\Program Files (x86)\MySQL\MySQL Server 5.7\bin\mysqldump.exe',
        ];
        foreach ($candidates as $c) if (file_exists($c)) return $c;
        return 'mysqldump';
    }

    protected function ensureDirectory(string $dir): void
    {
        if (!is_dir($dir)) @mkdir($dir, 0755, true);
    }

    protected function runShell(string $cmd): void
    {
        if (str_starts_with(PHP_OS_FAMILY, 'Windows')) {
            $spec = [0=>['pipe','r'], 1=>['pipe','w'], 2=>['pipe','w']];
            $proc = @proc_open(['cmd', '/c', $cmd], $spec, $pipes);
            if (is_resource($proc)) {
                @stream_get_contents($pipes[1]);
                @stream_get_contents($pipes[2]);
                foreach ($pipes as $p) @fclose($p);
                @proc_close($proc);
            } else {
                @shell_exec($cmd);
            }
        } else {
            @shell_exec($cmd);
        }
    }
}
