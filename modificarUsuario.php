<?php
//Se recuperan los datos a modificar
    $id = $_POST["idModificar"];
    $tipo = $_POST["tipoModificar"];
    $clavedeacceso = $_POST["claveModificar"];
    $nombre = $_POST["nombreModificar"];
    $apellidos = $_POST["apellidosModificar"];
    $telefono = $_POST["telefonoModificar"];
    $correo = $_POST["correoModificar"];
    $oldid = $_POST["viejoIDModificar"];

    //Variables de conexion
    $server = "localhost";
    $user = "root";
    $password = "";
    $db = "estacionamiento";
    $conn = new mysqli($server, $user, $password, $db);
    if($conn->connect_error){
        die("Falló la conexión: ".$conn->connect_error);
    }else{
        //Se actualizan los datos
        $sql_sentence = "UPDATE usuarios SET id = '".$id."', tipo = '".$tipo."', contrasenia = '".$clavedeacceso."', nombre = '".$nombre."', apellidos = '".$apellidos."', telefono = '".$telefono."', correo = '".$correo."' WHERE id = '".$oldid."'";
        $query = $conn->query($sql_sentence);
        if($query){
            //Si se modifico el ID entonces tambien debe modificarse en los vehiculos registrados a nombre del cliente
            $sql_sentence = "UPDATE vehiculos SET cliente = '".$id."' WHERE cliente = '".$oldid."'";
            $query = $conn->query($sql_sentence);
            if($query){
                session_start(); 
                $_SESSION["idUsuario"] = $id;
                echo "éxito";
            }else{
                echo $conn->error;
            }
        }else{
            echo $conn->error;
        }
        
    }
           
?>