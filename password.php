<?php
include __DIR__ . '/etc/db.php';
include __DIR__ . '/etc/dbfuncs.php';

session_start();

$mensaje = '';
$error = '';
$logged = false;
$tiempoSesion = 120;


if (
    isset($_SESSION['usuarioNombre'])
    && isset($_SESSION['usuarioId'])
    && isset($_SESSION['sessionStartTime'])
    && isset($_SESSION['sessionEndTime'])
    && $_SESSION['sessionEndTime'] > time()
) {

    $logged = true;
    $passOk = '';
    $error = '';

    $usercode = $_SESSION['usuarioCod'];
    $usuario = $_SESSION['usuarioNombre'];
    $_SESSION['sessionEndTime'] = time() + $tiempoSesion;

    if (isset($_POST['password']) && $_POST['newpassword'] && $_POST['repeatnewpassword']) {
        if ($_POST['newpassword'] == $_POST['repeatnewpassword']) {

            $oldPassword = hash("sha256", $_POST['password']);
            $newPassword = hash("sha256", $_POST['newpassword']);

            echo "<br>";
            echo $oldPassword;
            echo "<br>";
            echo $newPassword;
            echo "<br>";
            $modificarPass = modificarPassword($pdo, $usercode, $oldPassword, $newPassword);
            if($modificarPass) {
                $passOk = 'El Password ha sido cambiado';
            } else {
                $error = 'El Password no es correcto';
            }

            echo "<pre>";
            var_dump ($modificarPass);
            echo "</pre>";
        } else {
            $error = "Fallo al confirmar el nuevo password";
        }
    } else {
        $error = "Debe rellenar todos los campos";
    }


} else {
    $logged = false;
}




echo "<br>";
echo "EXPIRES";
echo "<br>";
echo $_SESSION['sessionEndTime'];

echo "<br>";
echo "TIME";
echo "<br>";
echo time();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>WetWater S.L. - Cambiar Contraseña</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="assets/fontawesome/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/login.css">

<body>
    <div class="container container-password">
        <?php if ($logged == false) { ?>
            <div class="no-log">
                <H1>Portal del cliente de WetWater S.L.</H1>
                <p>No hay ningun usuario autentificado, por favor diríjase a la
                    <a href="login.php">página de autentificación</a>
                </p>
            </div>
        <?php } else { ?>
            <div class="password-title">
                <H1>Cambiar Contraseña</H1>
            </div>
            <div>
                <h2>Hola <span>
                        <?= $usuario ?>
                    </span></h2>
                <P> En ésta página puede cambiar su contraseña, vaya a la <a href="index.php">página principal</a> para ver
                    sus pedidos.</p>
                <?php if (!isset($mensaje)) { ?>
                    <h3>Mensaje:</h3>
                    <p class="mensaje">
                        <?= $mensaje ?>
                    </p>
                <?php } ?>
                <form class="form-password" action="password.php" method="post">
                    <div class="row">
                        <label for="user">Password Antiguo</label>
                        <input type="password" id="oldpassword" name="password">
                    </div>
                    <div class="row">
                        <label for="user">Password Nuevo</label>
                        <input type="password" id="newpassword" name="newpassword">
                    </div>
                    <div class="row">
                        <label for="user">Confirmar Nuevo Password</label>
                        <input type="password" id="repeatnewpassword" name="repeatnewpassword">
                    </div>
                    <button class="btn btn-password" type="submit" name="cambiarpass" value="cambiarpass">Cabiar
                        Contraseña</button>
                </form>
                <div class="error-wrap">
                    <?php if ($passOk): ?>
                        <h3 class="success"><?= $passOk ?></h3>
                    <?php endif ?>
                    <?php if ($error): ?>
                        <h3 class="error"><?= $error ?></h3>
                    <?php endif ?>
                </div>
            <?php } ?>

        </div>

    </div>
    </div>
</body>

</html>

<?php
echo "<pre>";
echo "POST";
var_dump($_POST);
echo "</pre>";
echo "<pre>";
echo "POST";
var_dump($_SESSION);
echo "</pre>";
?>