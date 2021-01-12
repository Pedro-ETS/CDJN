<?php
//Se recuperan los datos de la reservacion
    $id = $_POST["clienteID"];
    $auto = $_POST["autoID"];
    $cajon = $_POST["cajonID"];

    
$server = "localhost";
$user = "root";
$password = "";
$db = "estacionamiento";
$conn = new mysqli($server, $user, $password, $db);
if($conn->connect_error){
    die("Falló la conexión: ".$conn->connect_error);
}else{
    //Se verifica si el auto no tiene ya una reservacion
    $sql_sentence = "SELECT * FROM cajones_reservados WHERE id_auto = '".$auto."'"; 
    $query = $conn->query($sql_sentence);
    if($query){
        if($query->num_rows > 0){
            echo "<script>alert('Este auto ya ha reservado un lugar en el estacionamiento');
            location.href = 'reservaciones.php';</script>";
        }else{
            //Si el auto no tiene una reservacion se procede a generar la reservacion solicitada
            $sql_sentence = "INSERT INTO cajones_reservados (id_cajon, id_auto, usuario) VALUES ('".$cajon."', '".$auto."', '".$id."')";
            if($conn->query($sql_sentence) === TRUE){
                //Tambn se marca el cajon como reservado
                $sql_sentence = "UPDATE cajones SET situacion = 'Reservado' WHERE id = '".$cajon."'";
                if($conn->query($sql_sentence) === TRUE){
                    echo "<script>alert('Reservación creada correctamente');
                                location.href = 'reservaciones.php';</script>";
                }else{
                    echo $conn->error;
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