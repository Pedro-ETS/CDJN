<?php    
//Se reciben las variables para realizar el pago del resguardo
    $arreglo = $_POST["datosActualizar"];
    $totalPagado = $_POST["total"];
    $lista = explode(',',$arreglo);  
    $resp = "";

    //Si los datos recibidos son solo 1 quiere decir que hubo un problema, porque el resguardo contiene al menos 7 datos
    if(count($lista) == 1){
        echo $resp;
    }else{
        //Variables de conexion
        $server = "localhost";
        $user = "root";
        $password = "";
        $db = "estacionamiento";
        $conn = new mysqli($server, $user, $password, $db);
        if($conn->connect_error){
            die("Falló la conexión: ".$conn->connect_error);
        }else{
            //Se actualizan los datos del resguardo para marcarlo como 'Pagado'
            $sql_sentence = "UPDATE resguardos SET hora_salida = '".$lista[3]."', pago = '".$lista[4]."', estatus = 'Pagado' WHERE id_cajon = ".$lista[0]." AND id_vehiculo = ".$lista[1]." AND estatus = 'Activo'";
            $query = $conn->query($sql_sentence);
            if($query){
                //Tambien el cajon donde estaba el vehiculo quedara como disponible
                $sql_sentence = "UPDATE cajones SET situacion = 'Disponible' WHERE id = ".$lista[0];
                $query = $conn->query($sql_sentence);
                if($query){
                    //Se retorna la respuesta de que todo fue bien
                    $resp = "éxito";
                    echo $resp;
                }else{
                    echo $conn->error;
                }
            }else{
                echo $conn->error;
            }
        }
    }
?>