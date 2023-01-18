<?php

include __DIR__ . '/etc/conf.php';

$mensaje = "";
$error = "";
// Si existe la cookie de favoritosSerializado, inicializaríamos la variable favoritoss con sus datos, mediante unserialize, si no existe la cookie, inicializariamos la variable vacía y crearíamos también la cookie.

if (isset($_COOKIE['favoritosSerializado']) && isset($_COOKIE['hashFavoritosSerializado'])) {

    // Si el hash de la cookie "hashFavoritosSerializado" coincide con el de la cookie de favoritos, continuamos el proceso
    if (hash('sha256', $_COOKIE['favoritosSerializado']) === $_COOKIE['hashFavoritosSerializado']) {
        // Mostramos el mensaje de que la cookie ha sido verificada.
        $mensaje = "Cookie Verificada";
        // recuperamos los favoritos de la cookie.
        $favoritos = unserialize($_COOKIE['favoritosSerializado']);

        // Si se hace click en favorito
        if (isset($_POST['op']) && $_POST['op'] === 'fav') {

            $producto = $_POST['producto'];

            // Comprobamos que el producto coincide con un producto en la lista de la base de datos
            if (isset($productos[$_POST['producto']])) {

                // Impedimos que el producto aparezca como favorito dos veces comprobando si está en el array.
                if (!in_array($producto, $favoritos)) {

                    // Si se hace click en fav, añadimo producto a una variable, y hacemos un push al array favoritos.
                    array_push($favoritos, $producto);
                    // Una vez añadamos el elemento al array, actualizamos la cookie con éste:
                    setcookie('favoritosSerializado', serialize($favoritos), time() + 600);
                    setcookie('hashFavoritosSerializado', hash('sha256', serialize($favoritos)), time() + 600);
                }
            // Si no existiera el producto en la lista, recibiremos el error.
            } else {
                $error = "El producto no coincide con ninguno en la lista de productos";
            }
        }

        // Si se hace click en unfav
        if (isset($_POST['op']) && $_POST['op'] === 'unfav') {

            // Si hacemos click en unfav, buscaremos el producto en el array y lo eliminamos con splice
            $key = array_search($_POST['producto'], $favoritos);
            array_splice($favoritos, $key, 1);


            // Actualizamos la cookie una vez se ha eliminado del array.
            setcookie('favoritosSerializado', serialize($favoritos), time() + 600);
            setcookie('hashFavoritosSerializado', hash('sha256', serialize($favoritos)), time() + 600);


        }

    } else {
        $error = "Fallo al verificar la cookie, se borrarán las cookies";
        $favoritos = [];
        setcookie('favoritosSerializado', serialize($favoritos), time() - 6000);
        setcookie('hashFavoritosSerializado', serialize($favoritos), time() - 6000);

    }

} else {
    // Si no existen las cookies las creamos e iniciamos el array de favoritos.
    $favoritos = [];
    $mensaje = "No había cookies, por lo que se envían por primera vez";
    //Creamos la cookie para los favoritos
    setcookie('favoritosSerializado', serialize($favoritos), time() + 600);
    //Creamos la cookie para la validacion
    setcookie('hashFavoritosSerializado', hash('sha256', serialize($favoritos)), time() + 600);

}



// echo "<pre>";
// var_dump($_COOKIE);
// echo "</pre>";
// if (hash('sha256',$_COOKIE['hashFavoritosSerializado']) === $_COOKIE['favoritosSerializado']) {
//     echo "HASH VÁLIDO";
// } else {
//     echo "HASH NO VÁLIDO";
// }

// echo "FAVORITOS";
// echo "<br>";
// echo "<pre>";
// var_dump($favoritos);
// echo "</pre>";


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio Cookies - David Martínez</title>
</head>
<link rel="stylesheet" href="css/styles.css">
<link href="assets/fontawesome/css/all.min.css" rel="stylesheet" type="text/css">

<body>
    <?php include __DIR__ . '/extra/mensajes.php'; ?>
    <table>
        <thead>
            <tr>
                <th>
                    Favoritos
                </th>
                <th>
                    Código producto
                </th>
                <th>
                    Descripción
                </th>
                <th>
                    Precio
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $cod => $datos): ?>
                <tr>
                    <td style="text-align:center">
                        <form action="" method="post">
                            <?php if (in_array($cod, $favoritos)): ?>
                                <button type="submit" class="flat"><i class="fa-solid fa-star fa-lg"></i></button>
                                <input type="hidden" name="op" value="unfav">

                            <?php else: ?>
                                <button type="submit" class="flat"><i class="fa-regular fa-star fa-lg"></i></button>
                                <input type="hidden" name="op" value="fav">
                            <?php endif; ?>
                            <input type="hidden" name="producto" value="<?= $cod ?>">
                        </form>
                    </td>
                    <td>
                        <?= $cod ?>
                    </td>
                    <td>
                        <?= $datos['descripcion'] ?>
                    </td>
                    <td>
                        <?= $datos['precio_unidad'] ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if ($mensaje): ?>
        <h1 class="mensaje"><?= $mensaje ?></h1>
    <?php endif ?>
    <?php if ($error): ?>
        <h1 class="error">
            <?= $error ?>
        </h1>
    <?php endif ?>
</body>

</html>