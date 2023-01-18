<?php
function queryUsuario(PDO $pdo, $usercode, $passwordHash) {
    $sql = 'SELECT id, cod, nombre FROM `usuarios` WHERE cod=:usercode AND password=:password';
    $ret = false;
    $ret = false;
    try {
        $stmt = $pdo->prepare($sql);
        $data = ['usercode' => $usercode, 'password' => $passwordHash];
        if ($stmt->execute($data)) {
            $ret = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $ex) {
        $ret = -1;
    }
    return $ret;
}

function listaDePedidosPorCliente(PDO $pdo, $usercode)

{
    $sql = 'SELECT pedidos.id as idpedido, pedidos.fechapedido as fechapedido,'
        . ' pedidos.fechaentrega as fechaentrega, pedidos.idusuario as idusuario,'
        . ' usuarios.cod as codigousuario, usuarios.nombre as nombreusuario '
        . ' FROM pedidos left join usuarios on usuarios.id=pedidos.idusuario '
        . ' WHERE usuarios.cod=:codcliente';
    $ret = false;
    try {
        $stmt = $pdo->prepare($sql);
        $data = ['codcliente' => $usercode];
        if ($stmt->execute($data)) {
            $ret = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $ex) {
        $ret = -1;
    }
    return $ret;
}

function modificarPassword(PDO $pdo, $usercode, $oldpPassword, $newPassword) {
    $sql = 'UPDATE usuarios SET password=:newpassword WHERE cod=:codcliente AND password=:oldpassword';
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':codcliente', $usercode);
        $stmt->bindParam(':oldpassword', $oldpPassword);
        $stmt->bindParam(':newpassword', $newPassword);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $ex) {
        return $ex;
    }
}
?>