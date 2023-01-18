<?php

include __DIR__ . '/etc/db.php';
include __DIR__ . '/etc/dbfuncs.php';
require_once __DIR__.'/vendor/autoload.php';

//Indicamos que vamos a usar la clase Logger del espacio de nombres Monolog
use \Monolog\Logger;
//Indicamos que vamos a usar la clase StreamHandler del espacio de nombres Monolog\Handler
use \Monolog\Handler\StreamHandler;

// Creamos un Logger con el nombre de canal "Eventos de usuario"
$log = new Logger('Eventos de login de usuario');
/* Indicamos que los logs se van a guardar en un archivo llamado eventos.log en el
   mismo directorio de la aplicación (lo normal es que esté en otro directorio, pero
   como estamos aprendiendo lo vamos a dejar aquí) */
   $log->pushHandler(new StreamHandler(__DIR__.'/eventosLogin.log', Logger::DEBUG));




$error = '';
$tiempoSesion = 10;
$login = false;

session_start();

echo "USUARIODB";
echo "<pre>";
var_dump($usuarioDb);
echo "</pre>";

    if(isset($_POST['submit']) && !isset($_SESSION['sessionStartTime']) && $login == false) {

        $usercode = filter_input(INPUT_POST,'usercode',FILTER_SANITIZE_STRING);
        $password = $_POST['password'];
        $passwordHash = hash("sha256", $password);

        unset($_POST);

        $usuarioDb = queryUsuario($pdo, $usercode, $passwordHash);





        if(isset($usuarioDb[0]['id']) && isset($usuarioDb[0]['cod']) && isset($usuarioDb[0]['nombre']) && $login == false) {


            $log->info('El usuario ' . $usuarioDb[0]['id'] . ' ha iniciado sesión');

            $_SESSION['usuarioId'] = $usuarioDb[0]['id'];
            $_SESSION['usuarioNombre'] = $usuarioDb[0]['nombre'];
            $_SESSION['usuarioCod'] = $usuarioDb[0]['cod'];




            if(!isset( $_SESSION['sessionStartTime']) && $login == false) {

                $login = true;
                unset($usuarioDb);
                unset($_POST);
                $_SESSION['sessionStartTime'] = time();
                $_SESSION['sessionEndTime'] = time() + $tiempoSesion;
            }

        } else {
            $login = false;
            unset($_POST);
            $error = 'Usuario o contraseña erroneos';
            $log->warning('Usuario ' . $usercode . ' y Contraseña '. $password . ' Incorrectos introducido');
            if(isset($_SESSION)) {
            unset($_SESSION);
                session_destroy();
            }
        }
    }

if(isset($_SESSION['sessionStartTime']) && isset($_SESSION['sessionEndTime']) && $_SESSION['sessionEndTime'] < time()) {

    $login = false;

    unset($_SESSION);
    session_destroy();
    $error = 'Sesión Expirada, por favor, inicie otra vez la sesión';
    return;

}


if($_POST) {
    unset($_POST);
}

echo "LOGIN:";
echo "<br>";
var_dump ($login);
echo "<br>";

echo "POST:";
echo "<br>";
var_dump ($_POST);
echo "<br>";

echo "SESSION:";
echo "<pre>";
var_dump ($_SESSION);
echo "</pre>";

echo "<br>";
echo "EXPIRES";
echo "<br>";
echo $_SESSION['sessionStartTime'] + $tiempoSesion;

echo "<br>";
echo "TIME";
echo "<br>";
echo time();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio Login - David Martínez</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="container">
        <?php if (!isset($_SESSION['sessionStartTime'])) { ?>
        <h1 class="titulo">Formulario de Login</h1>
        <form action="login.php" method="post">
            <div class="row">
                <label for="usercode">Usuario</label>
                <input type="text" value="usercode" id="usercode" name="usercode" placeholder="Su usuario...">
            </div>
            <div class="row">
                <label for="user">Password</label>
                <input type="password" value="password" id="password" name="password">
            </div>
            <div class="row">
                <input type="hidden" value="<?= $_SESSION ?>">
                <button class="btn" type="submit" name="submit" value="login">Login</button>
            </div>
        </form>
        <div class="error-wrap">
            <?php if ($error): ?>
            <h3 class="error"><?= $error ?></h3>
            <?php endif ?>
        </div>

        <?php } else {; ?>
            <h1 class="welcome">Bienvenido, <span><?=$_SESSION['usuarioNombre'] ?></span>.  Vaya a la  <a href="index.php">página principal</a> para ver sus pedidos</h1>
        <?php }; ?>
    </div>
</body>

</html>