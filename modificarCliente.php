<?php
    //Se recupera el ID del usuario junto con la lista de los datos del usuario (nombre, apellidos, telefono, etc)
    //Esta lista es en realidad un arreglo donde se metieron todos los datos ya modificados
    $id = $_POST["modificarID"];
    $lista = explode(',',$id);  

    //Variables de conexion
    $server = "localhost";
    $user = "root";
    $password = "";
    $db = "estacionamiento";
    $conn = new mysqli($server, $user, $password, $db);
    if($conn->connect_error){
        die("Falló la conexión: ".$conn->connect_error);
    }else{
        //Como es un arreglo se usa un for para recorrerlo y asi poder obtener los valores del mismo
        for ($x=0;$x<count($lista); $x+=5){
            //Se actualizan los datos del usuario en cuestion
            $sql_sentence = "UPDATE usuarios SET nombre = '".$lista[$x+1]."', apellidos = '".$lista[$x+2]."', telefono = '".$lista[$x+3]."', correo = '".$lista[$x+4]."' WHERE id = '".$lista[$x]."'";
            $query = $conn->query($sql_sentence);
            if($query){
                echo "<script>alert('Información actualizada éxitosamente');
                location.href = 'ver_usuarios.php';
                location.href.reload();</script>";
            }else{
                echo $conn->error;
            }
        }
    }
    
           
?>