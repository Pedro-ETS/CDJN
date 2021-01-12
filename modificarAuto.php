<?php
    //Se reciben las variables a modificar
    $auto = $_POST["modificarAuto"];
    $lista = explode(',',$auto); 

    //Variables de conexion
    $server = "localhost";
    $user = "root";
    $password = "";
    $db = "estacionamiento";
    $conn = new mysqli($server, $user, $password, $db);
    if($conn->connect_error){
        die("Falló la conexión: ".$conn->connect_error);
    }else{
        //se actualizan los datos del vehiculo segun su ID
        $sql_sentence = "UPDATE vehiculos SET placas = '".$lista[1]."', marca = '".$lista[2]."', modelo = '".$lista[3]."', color = '".$lista[4]."', tamanio = '".$lista[5]."' WHERE id = ".$lista[0];
        $query = $conn->query($sql_sentence);
        if($query){
            echo "<script>alert('Información actualizada éxitosamente');
            location.href = 'ver_autos.php';
            location.href.reload();</script>";
        }else{
            echo $conn->error;
        }
        
    }
           
?>