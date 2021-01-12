<?php
//Se recuperan id y tipo
    $idUsuario = $_POST["id"];
    $tipoUsuario = $_POST["tipo"];
//Se genera una nueva sesion con los datos correspondientes
    session_start(); 
    $_SESSION["tipoUsuario"] = $tipoUsuario; 
    $_SESSION["idUsuario"] = $idUsuario; 
    session_regenerate_id();
    //Se responde con el tipo de usuario
    echo $tipoUsuario;
?>