<?php
    //Se recupera el ID del usuario a eliminar
    $id = $_POST["eliminarID"];
     
    //Variables de conexion
    $server = "localhost";
    $user = "root";
    $password = "";
    $db = "estacionamiento";
    $conn = new mysqli($server, $user, $password, $db);
    if($conn->connect_error){
        die("Falló la conexión: ".$conn->connect_error);
    }else{
        //Se comprueba si existen resguardos activos a nombre del usuario a eliminar       
        $sql_sentence = "select nombre from usuarios inner join vehiculos on usuarios.id = cliente inner join resguardos on id_vehiculo = vehiculos.id where resguardos.estatus = 'Activo' and usuarios.id = '".$id."'";
        $query = $conn->query($sql_sentence);
        if($query){
            //Si existe al menos un resguardo activo a su nombre no se puede eliminar
            if($query->num_rows > 0){
                echo "<script>alert('No se puede eliminar el cliente si existen resguardos activos');
                    location.href = 'ver_usuarios.php';
                    location.href.reload();</script>";
            }else{//Si no tiene resguardos a su nombre entonces si se procede a eliminar al usuario y a todos los vehiculos a su nombre
                $sql_sentence = "DELETE FROM usuarios WHERE id = '".$id."'";
                $query = $conn->query($sql_sentence);
                if($query){
                    $sql_sentence = "DELETE FROM vehiculos WHERE cliente = '".$id."'";
                    $query = $conn->query($sql_sentence);
                    if($query){
                        echo "<script>alert('Información actualizada éxitosamente');
                        location.href = 'ver_usuarios.php';
                        location.href.reload();</script>";
                    }else{
                        echo $conn->error;
                    }
                } else{
                    echo $conn->error;
                }
            }
        }else{
            echo $conn->error;
        }
    }

?>