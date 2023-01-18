<?php

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
// echo "<pre>";
// var_dump(queryUsuarios($pdo));
// echo "</pre>";

session_start();

echo "<pre>";
var_dump($_SESSION);
echo "</pre>";

$logged = false;


if (    isset($_SESSION['usuarioId'])
        && isset($_SESSION['sessionStartTime'])
        && isset($_SESSION['sessionEndTime'])
        && $_SESSION['sessionEndTime'] > time()) {

    $logged = true;
    $nombre = $_SESSION['usuarioNombre'];
    $log->info('El usuario ' . $nombre . ' ha cerrado sesión');
    unset($_SESSION['usuarioId']);
    unset($_SESSION['sessionStartTime']);
    unset($_SESSION['sessionEndTime']);
    unset($_SESSION['usuarioNombre']);
    unset($_SESSION['usuarioCod']);
    session_destroy();


} else {
    $logged = false;
}
echo $logged;
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Logout</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="container">
        <?php if ($logged === true) { ?>
            <div class="logout welcome">
                <h1>Hasta Pronto, <span><?= $nombre ?></span>, la sesión se ha cerrado.</h1>
                <a href="login.php">Volver a la página de login de cliente</a>
            </div>
        <?php } else { ?>
            <div class="logout welcome">
                <h1>No se ha iniciado sesión</h1>
                <a href="login.php">Volver a la página de login de cliente</a>
            </div>
        <?php } ?>
    </div>
</body>

</html>
<?php
// echo "LOGIN:";
// echo "<br>";
// var_dump ($logged);
// echo "<br>";

// echo "<br>";
// echo "TIME";
// echo "<br>";
// echo time();

?>