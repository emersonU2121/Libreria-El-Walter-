<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PasswordController extends Controller
{
    // Mostrar formulario de solicitud de correo
    public function showRequestForm()
    {
        return view('password_reset_request');
    }

    // Enviar correo con token
    public function sendResetLink(Request $request)
    {
        $request->validate(['correo' => 'required|email']);

        $user = Usuario::where('correo', $request->correo)->first();
        if (!$user) {
            return back()->withErrors(['correo' => 'Correo no encontrado']);
        }

        $token = Str::random(64);

        // Guardar token en tabla password_resets
        DB::table('password_resets')->updateOrInsert(
            ['correo' => $request->correo],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        // Enviar correo
        $link = url('/password/reset/' . $token . '?correo=' . $request->correo);

       Mail::send([], [], function($message) use ($request, $link) {
    $message->to($request->correo)
        ->subject('Recuperación de contraseña')
        ->html('<p>Haz clic en el siguiente enlace para cambiar tu contraseña:</p>
                <a href="' . $link . '">Cambiar contraseña</a>');
});
        return back()->with('status', 'Enlace de recuperación enviado a tu correo');
    }

    // Mostrar formulario para cambiar contraseña
    public function showResetForm($token, Request $request)
    {
        return view('password_reset_form', ['token' => $token, 'correo' => $request->correo]);
    }

    // Actualizar contraseña
    public function resetPassword(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'contraseña' => 'required|min:6',
            'token' => 'required'
        ]);

        $record = DB::table('password_resets')
            ->where('correo', $request->correo)
            ->where('token', $request->token)
            ->first();

        if (!$record) {
            return back()->withErrors(['correo' => 'Token inválido']);
        }

        $user = Usuario::where('correo', $request->correo)->first();
        $user->contraseña = bcrypt($request->contraseña);
        $user->save();

        // Borrar token
        DB::table('password_resets')->where('correo', $request->correo)->delete();

        return redirect('/login')->with('status', 'Contraseña cambiada correctamente');
    }
}