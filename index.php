<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/webp" href="./assets/img/imagenes/icon_white.png">
    <meta name="description" content="Sistema CRUD para la gestión de egresados. Inicie sesión para acceder al panel de administración y gestionar los datos de los egresados.">
    <meta property="og:title" content="Sistema de Gestión de Egresados">
    <meta property="og:description" content="Acceso al sistema de gestión de egresados para administrar información relevante de los exalumnos.">
    <meta property="og:image" content="./assets/img/imagenes/icon.png">
    <meta property="og:type" content="website">
    <title>Sistema de Gestión de Egresados</title>
    <link rel="stylesheet" href="./assets/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo" style="display: flex; flex-direction: column; align-items: center;">
            <img src="./assets/img/imagenes/icon.png" alt="Icono del Sistema de Gestión de Egresados" style="width: 100px; height: auto;">
            <a href="#"><b>Gestión</b> Egresados</a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Ingresa tus credenciales</p>
                <form id="login-form" method="post">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Correo electrónico" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="contrasena" id="contrasena" placeholder="Contraseña" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Iniciar sesión</button>
                        </div>
                    </div>
                </form>
                <div class="alert alert-danger" style="display: none;" id="error-alert"></div>
                <br>
                <p class="mb-1">
                    <a href="./vista/recuperar.php">Olvidé mi contraseña</a>
                </p>
                <p class="mb-0">
                    <a href="./vista/registrar.php" class="text-center">Quiero registrarme</a>
                </p>
            </div>
        </div>
    </div>
    <script src="./assets/js/adminlte.min.js"></script>
    <script src="./assets/plugins/jquery/jquery.min.js"></script>
    <script src="./assets/js/ingresar.js"></script>
</body>

</html>
