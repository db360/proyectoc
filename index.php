<?php
include __DIR__ . '/etc/db.php';
include __DIR__ . '/etc/dbfuncs.php';

session_start();

$tiempoSesion = 120;


// echo "POST:";
// echo "<br>";
// var_dump ($_POST);
// echo "<br>";

echo "SESSION:";
echo "<pre>";
var_dump ($_SESSION);
echo "</pre>";



if (    isset($_SESSION['usuarioNombre'])
        && isset($_SESSION['usuarioId'])
        && isset($_SESSION['sessionStartTime'])
        && isset($_SESSION['sessionEndTime'])
        && $_SESSION['sessionEndTime'] > time()) {

    $logged = true;
    echo "session no expirada";

    $usercode = $_SESSION['usuarioCod'];
    $usuario = $_SESSION['usuarioNombre'];

    echo "usercode " . $usercode . "<br>";

    $listaPedidos = listaDePedidosPorCliente($pdo, $usercode);

    if (count($listaPedidos) == 0) {
        echo "no hay pedidos cliente <br>";
    }

} else {

    if (    isset($_SESSION['usuarioNombre'])
        && isset($_SESSION['usuarioId'])
        && isset($_SESSION['sessionStartTime'])
        && isset($_SESSION['sessionEndTime'])
        && $_SESSION['sessionEndTime'] < time()) {

            $_SESSION['sessionEndTime'] = time() + $tiempoSesion;
            echo "session extendida <br>";
            header("Location: index.php");
        } else {

            session_destroy();
            header("refresh");
            echo "session expirada <br>";
            $logged = false;

    }
}

echo "<br>SESSION:";
echo "<pre>";
var_dump($logged);
echo "</pre>";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Portal del cliente de WetWater S.L.</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="assets/fontawesome/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/login.css">

<body>
    <?php if ($logged == false) { ?>
        <div class="container">
            <div class="no-log">
                <H1>Portal del cliente de WetWater S.L.</H1>
                <a href="ej3.php">Consulte nuestra lista de productos</a>
                <p>Diríjase a la
                <a href="login.php">página de autentificación</a></p>
            </div>
        </div>
    <?php } else { ?>
        <div class="container">
            <div class="log">
                <H1>Portal del cliente de WetWater S.L.</H1>
                <a href="ej3.php">Consulte nuestra lista de productos</a>
            </div>
            <div>
                <H2>Bienvenido <span><?= $usuario ?></span> <a href="logout.php" alt="Cerrar Sesión"><i
                            class="fa-solid fa-arrow-right-from-bracket"></i></a>
                    <a href="password.php"><i class="fa-solid fa-user-pen"></i></a>.
                </H2>
                <P> Haga click en <i class="fa-solid fa-arrow-right-from-bracket"></i> para cerrar sesión.</p>
                <P> Haga click en <i class="fa-solid fa-user-pen"></i> para cambiar su contraseña.</P>
                <P> <B>¡Atención!</B> La sesión expirará en 120 segundos de inactividad.</P>
                <P> A continuación puede ver el listado de sus pedidos. </P>
                <table>
                    <thead>
                    <tr>
                        <th>Codigo de cliente</th>
                        <th>Nombre de cliente</th>
                        <th>ID de pedido</th>
                        <th>Fecha del pedido</th>
                        <th>Fecha de entrega</th>
                    </tr>
                    </thead>
                    <?php foreach($listaPedidos as $pedido): ?>
                    <tr>
                        <td><?=$pedido['codigousuario'];?></td>
                        <td><?=$pedido['nombreusuario'];?></td>
                        <td><?=$pedido['idpedido'];?></td>
                        <td><?=$pedido['fechapedido'];?></td>
                        <td><?=$pedido['fechaentrega'];?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    <?php } ?>
    </div>
</body>

</html>

<?php
echo "<br>";
echo "EXPIRES";
echo "<br>";
echo $_SESSION['sessionEndTime'];

echo "<br>";
echo "TIME";
echo "<br>";
echo time();

?>