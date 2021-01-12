<?php
//Para cerrar sesion solo se limpian las variables de sesion y se redirige al index
    session_start();  
    $_SESSION["tipoUsuario"] = "";
    $_SESSION["idUsuario"] = "";
    echo "<script>
    location.href = 'index.php';</script>";
?>