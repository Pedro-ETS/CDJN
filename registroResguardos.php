<?php
//Se recuperan los datos para registrar el resguardo
    $cliente = $_POST["clienteID"];
    $auto = $_POST["autoID"];
    $fecha = $_POST["fecha"];
    $hraEntrada = $_POST["horaEntrada"];
//Tambn los del vehiculo en caso de que sea registro de un vehiculo nuevo
    $matricula = $_POST["matricula"];
    $marca = $_POST["marca"];
    $modelo = $_POST["modelo"];
    $color = $_POST["color"];
    $tamanio = $_POST["tamanio"];

//VAriables de conexion
    $server = "localhost";
    $user = "root";
    $password = "";
    $db = "estacionamiento";
    $conn = new mysqli($server, $user, $password, $db);
//Si la variable auto esta vacia significa que se estara registrando un resguardo con un vehiculo nuevo
    if($auto == ""){
        //Como es un vehiculo nuevo, se valida que ningun dato venga vacio
        if(($matricula == "") || ($marca == "") || ($modelo == "") || ($color == "")){
            echo "vacio";
        }else{
            if($conn->connect_error){
                die("Falló la conexión: ".$conn->connect_error);
            }else{
                //Con los datos validados, creamos el nuevo vehiculo antes de registrar el resguardo
                $sql_sentence = "INSERT INTO vehiculos (id, placas, marca, modelo, color, tamanio, cliente) VALUES (0, '".$matricula."', '".$marca."', '".$modelo."', '".$color."', '".$tamanio."', '".$cliente."')";
                if($conn->query($sql_sentence) === TRUE){
                    //Se consulta cual es el cajon mas proximo disponible
                    $sql_sentence_aux = "SELECT id FROM cajones WHERE situacion = 'Disponible'";
                    $query = $conn->query($sql_sentence_aux);
                    if($query->num_rows > 0){
                        $row = $query->fetch_assoc();
                        $cajon = $row["id"];
                        //Se consulta el id del vehiculo registrado previamente
                        $sql_sentence_aux = "SELECT max(id) FROM vehiculos";
                        $query = $conn->query($sql_sentence_aux);
                        if($query->num_rows > 0){
                            $row2 = $query->fetch_assoc();
                            $autoID = $row2["max(id)"];
                            //Con todos los datos obtenidos se procede a registrar el resguardo
                            $sql_sentence = "INSERT INTO resguardos (id_cajon, id_vehiculo, hora_llegada, hora_salida, pago, fecha, estatus) VALUES ('".$cajon."', '".$autoID."', '".$hraEntrada."', '', '', '".$fecha."', 'Activo')";
                            if($conn->query($sql_sentence) === TRUE){
                                //Tambien se procede a marcar al cajon como ocupado
                                $sql_sentence = "UPDATE cajones SET situacion = 'Ocupado' WHERE id = '".$cajon."'";
                                if($conn->query($sql_sentence) === TRUE){
                                    //Si todo va bien se responde con el msg de crear PDF
                                    echo "crearPDF";
                                }else{
                                    echo $conn->error;
                                }
                            }else{
                                echo $conn->error;
                            }
                        }
                    }else{
                        //Si no se encuentra un espacio disponible entonces se responde con el msg de error de que no hay espacio en el estacionamiento
                        echo "noEspacio";
                    }
                }else{
                    echo $conn->error;
                }
            }            
        }
    }else{//Si la variable de auto no esta vacia entonces quiere decir que se va a registrar un resguardo con un vehiculo ya registrado
        if($cliente == ""){//Se comprueba que tambn se haya especificado al cliente
            echo "vacio";
        }else{
            if($conn->connect_error){
                die("Falló la conexión: ".$conn->connect_error);
            }else{
                //Se procede a verificar si el auto no esta ya resguardado
                $sql_sentence_aux = "SELECT id_vehiculo FROM resguardos WHERE id_vehiculo = ".$auto." AND estatus = 'Activo'";
                $query = $conn->query($sql_sentence_aux);
                if($query->num_rows > 0){//Si resulta que ya esta resguardado se responde con ese msg de error
                    echo "autoRegistrado";
                }else{
                    //Si todo esta bien y el auto aun no esta resguardado se procede a obtener el id del proximo cajon disponible
                    $sql_sentence_aux = "SELECT id FROM cajones WHERE situacion = 'Disponible'";
                    $query = $conn->query($sql_sentence_aux);
                    if($query->num_rows > 0){
                        $row = $query->fetch_assoc();
                        $cajon = $row["id"];
                        //Se checa si el auto ya tenia una reservacion
                        $sql_sentence_aux = "SELECT id_cajon FROM cajones_reservados WHERE id_auto = '".$auto."'";
                        $query = $conn->query($sql_sentence_aux);
                        if($query->num_rows > 0){
                            $row = $query->fetch_assoc();
                            //Si es asi entonces se cambia el id del cajon por el id del cajon reservado previamente
                            $cajon = $row["id_cajon"];
                        }
                        //Se procede a registrar el resguardo
                        $sql_sentence = "INSERT INTO resguardos (id_cajon, id_vehiculo, hora_llegada, hora_salida, pago, fecha, estatus) VALUES ('".$cajon."', '".$auto."', '".$hraEntrada."', '', '', '".$fecha."', 'Activo')";
                        if($conn->query($sql_sentence) === TRUE){
                            $sql_sentence = "UPDATE cajones SET situacion = 'Ocupado' WHERE id = '".$cajon."'";
                                if($conn->query($sql_sentence) === TRUE){
                                    //Se responde con el msg de crear PDF si todo fue bien
                                    echo "crearPDF";
                                }else{
                                    echo $conn->error;
                                }
                        }else{
                            echo $conn->error;
                        }
                    }else{//Si no se encuentra un cajon disponible se responde con el msg de error de que no hay espacio
                        echo "noEspacio";
                    }
                }
            }
        }
    }
?>