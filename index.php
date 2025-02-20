<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <!-- Bootstrap CSS desde CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <!-- Bootstrap JS desde CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/app.css">

    <style>
        .user-section {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 1rem;
    padding: 10px 20px;
    border-radius: 8px;
    }

    .user-text {
        font-size: 14px;
        color:rgb(0, 0, 0);
        margin: 0;
        font-weight: 500;
    }

    .user-text strong {
        color: #74b9ff;
        font-weight: 600;
    }

    .logout-btn,
    .login-btn {
        background: #e74c3c;
        color: #ffffff;
        border: none;
        padding: 8px 15px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .logout-btn:hover,
    .login-btn:hover {
        background: #c0392b;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }

    .logout-btn:active,
    .login-btn:active {
        transform: scale(0.98);
        background: #a93226;
    }

    .logout-form {
        margin: 0;
    }

    </style>
</head>

<body>
    <div class="container-fluid" id="capaEncabezado">
        <div class="row">
            <div class="col-md-2 col-sm-9 d-none d-sm-block">
                <img src="img/logo.png" style="height:5rem;">
            </div>
            <div class="col-md-8 d-none d-md-block divTitulo">
                Miguel Morlanes Turón - DI 2024
            </div>
            <div class="col-md-2 col-sm-3 user-section d-flex align-items-center justify-content-end">
                <?php if (isset($_SESSION['login']) && $_SESSION['login']): ?>
                    <span class="user-text me-2">Usuario: <strong><?php echo htmlspecialchars($_SESSION['login']); ?></strong></span>
                    <form action="login.php" method="POST" class="logout-form">
                        <button type="submit" name="logout" class="logout-btn">Cerrar Sesión</button>
                    </form>
                <?php else: ?>
                    <a href="login.php" class="login-btn">Iniciar Sesión</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    

    <?php
    require_once 'controladores/C_Menu.php';
    $controladorMenu = new C_Menu();
    $controladorMenu->mostrarMenu();
    ?>

    <div class="container-fluid" id="capaContenido">
    </div>

    <script src="js/Opciones.js" defer></script>
    <script src="js/Usuarios.js" defer></script>
    <script src="app.js" defer></script>
</body>

</html>