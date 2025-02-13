<?php 
session_start();

$username = '';
$password = '';
$msj = '';

// Manejo de cierre de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    // Cerrar sesión
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}

// Manejo de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['logout'])) {
    extract($_POST);

    if ($username == '' || $password == '') {
        $msj = 'Debes completar los campos.';
    } else {
        // Verificar usuario y contraseña contra la BD
        require_once 'controladores/C_Usuarios.php';
        $objCont = new C_Usuarios();
        $id_Usuario = $objCont->validarUsuario(array('usuario' => $username, 'pass' => $password));

        if ($id_Usuario != '') {
            $_SESSION['login'] = $username;
            $_SESSION['id_Usuario'] = $id_Usuario;
            // Redirigir a index.php
            header('Location: index.php');
            exit();
        } else {
            unset($_SESSION['login']);
            unset($_SESSION['id_Usuario']);
            $msj = 'Usuario o contraseña incorrectos.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="container">
        <h2>Iniciar Sesión</h2>
        <form action="login.php" method="post">
            <label for="username">Usuario:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>">

            <span id="msj" class="msj"><?php echo $msj; ?></span>
            <div class="elementoFormCentrado">
                <button type="submit" id="aceptar">Aceptar</button>
            </div>
        </form>
    </div>
</body>
</html>
