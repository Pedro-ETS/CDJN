<?php
//Se recuperan los datos del usuario a registrar
    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];
    $contraseña = $_POST["contraseña"];
    $tipo = $_POST["tipo"];
//Tambien los del vehiculo, en caso de que sea un usuario tipo conductor
    $matricula = $_POST["matricula"];
    $marca = $_POST["marca"];
    $modelo = $_POST["modelo"];
    $color = $_POST["color"];
    $tamanio = $_POST["tamanio"];
//Variables de conexion
$server = "localhost";
$user = "root";
$password = "";
$db = "estacionamiento";
$conn = new mysqli($server, $user, $password, $db);
if($conn->connect_error){
    die("Falló la conexión: ".$conn->connect_error);
}else{
    //Se consulta si ya existe un usuario con el id especificado
    $sql_sentence = "SELECT tipo FROM usuarios WHERE id = '".$id."'"; 
    $query = $conn->query($sql_sentence);
    if($query){
        //Si se recupera algun dato entonces este id ya se esta usando
        if($query->num_rows > 0){
            echo "<script>alert('El id de usuario ya esta en uso');</script>";
        }else{
            //Sino, se procede a registrar al usuario, porque los demas datos pueden ser repetidos
            $sql_sentence = "INSERT INTO usuarios (id, nombre, apellidos, telefono, correo, contrasenia, tipo) VALUES ('".$id."','".$nombre."','".$apellidos."','".$telefono."','".$email."','".$contraseña."','".$tipo."')";    
            if($conn->query($sql_sentence) === TRUE){
                //Si el usuario es de tipo conductor entonces tambien se debe registrar un vehiculo como minimo
                if($tipo == "conductor"){
                    $sql_sentence = "INSERT INTO vehiculos (id, placas, marca, modelo, color, tamanio, cliente) VALUES (0, '".$matricula."', '".$marca."', '".$modelo."', '".$color."', '".$tamanio."', '".$id."')";
                    if($conn->query($sql_sentence) === TRUE){
                        echo "<script>alert('Registro éxitoso');
                        location.href = 'nuevo_usuario.php';
                        </script>";
                    }else{
                        echo $conn->error;
                    }
                }else{
                    echo "<script>alert('Registro éxitoso');
                        location.href = 'nuevo_usuario.php';
                        </script>";
                }
            }else{
                echo $conn->error;
            }
            
        }
    }else{
        echo $conn->error;
    }
}
          
?>