<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librería El Walter</title>
    <style>
        body {
            font-family: 'Arial';
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
            color: #000;
        }

        .header {
            background-color: #2c3e50;
            color: white;
            padding: 15px 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .nav-menu {
            display: flex;
            gap: 20px;
        }

        .nav-item {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .nav-item:hover {
            background-color: #3498db;
        }

        .main-content {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .welcome-title {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-top: 0;
        }

        .admin-profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .admin-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #3498db;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: bold;
        }

        .admin-label {
            font-size: 15px;
        }

        .admin-profile:hover {
            transform: translateY(-2px);
        }

        .admin-profile:hover .admin-icon {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <img src="W.png" alt="Logo Librería El Walter" class="logo-img"
                 style="width: 50px; height: 50px;">
            <div class="logo">Librería "El Walter"</div>
        </div>
        
        <div class="nav-menu">
            <a href="#" class="nav-item">Marcas</a>
            <a href="#" class="nav-item">Productos</a>
            <a href="#" class="nav-item">Categoría</a>
            <a href="#" class="nav-item">Compras</a>
            <a href="#" class="nav-item">Ventas</a>
            <a href="#" class="nav-item">Usuarios</a>
        </div>
        
        <div class="admin-profile">
        <img src="admin-avatar.png" alt="Foto perfil" class="profile-img" style="width: 30px; height: 30px;">
            <div class="admin-label">Administrador</div>
        </div>
    </div>

    <div class="main-content">
        <h1 class="welcome-title">Bienvenido a Librería "El Walter"</h1>
        <p>Cojutepeque, Cuscatlan Sur</p>
    </div>
</body>
</html>
