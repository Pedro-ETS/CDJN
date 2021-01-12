<?php        
    //Se importa la barra de navegacion desde el archivo barranav.php
    include_once($_SERVER['DOCUMENT_ROOT']."/SistemaEstacionamiento/barranav.php");    
?>
<!DOCTYPE html>
<html>
    <head>        
        <meta charset="utf-8">
        <title>Estacionamiento</title>    
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="estilos.css">
    </head>
    <body>    
        <div class="container h-90">
        <!--Fechas de perioro para el corte de caja-->
        <center>
            <div class="row col-12 d-flex flex-row">
                <span><b>Fecha inicio</b></span>
                <input id="fechaDesde" type="date" class="ml-3 mr-4"/>
            </div>
            <div class="row col-12 d-flex flex-row" style="margin-top: 1%;">
                <span><b>Fecha fin</b></span>
                <input id="fechaHasta" type="date" class="ml-3 mr-4"/>
            </div>
            <div class="row col-12 d-flex flex-row" style="margin-top: 1%;">
                <button id="btnCorte" type="button" class="btn text-white" onclick="generarCorte()">Generar corte</button>
            </div>
        </center>
        <!--Tabla con el listado de los resguardos cobrados en el periodo seleccionado-->
            <div class="table-responsive"  style="margin-top: 3%;">
                <table id="tablaCorteCaja" class="table  mt-5">
                    <thead >
                        <tr>
                        <th scope="col">N°</th>
                        <th scope="col">Nombre del usuario</th>
                        <th scope="col">Vehiculo</th>
                        <th scope="col">N° de cajon</th>
                        <th scope="col">Concepto</th>
                        <th scope="col">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <th scope="row"></th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>No se encontraron resguardos pagados</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
        </div>

    </body>
</html>

<?php
$server = "localhost";
$user = "root";
$password = "";
$db = "estacionamiento";
$conn = new mysqli($server, $user, $password, $db);
if($conn->connect_error){
    die("Falló la conexión: ".$conn->connect_error);
}else{
    $usuarios = array();  
    $tipoUsuario = "'".$_SESSION["tipoUsuario"]."'";
    $sql_sentence = "SELECT * FROM usuarios WHERE tipo = 'conductor'";
    $query = $conn->query($sql_sentence);
    if($query){
        if($query->num_rows > 0){
            while($row = $query->fetch_assoc()){
                array_push($usuarios, "'".$row["id"]."'");
                array_push($usuarios, "'".$row["nombre"]."'");
                array_push($usuarios, "'".$row["apellidos"]."'");
            }
            $vehiculos = array();
            $sql_sentence = "SELECT * FROM vehiculos";
            $query = $conn->query($sql_sentence);
            if($query){
                if($query->num_rows > 0){
                    while($row = $query->fetch_assoc()){
                        array_push($vehiculos, "'".$row["id"]."'");
                        array_push($vehiculos, "'".$row["marca"]."'");
                        array_push($vehiculos, "'".$row["modelo"]."'");
                        array_push($vehiculos, "'".$row["cliente"]."'");
                    }
                    $resguardos = array();
                    $sql_sentence = "SELECT * FROM resguardos WHERE estatus = 'Pagado'";
                    $query = $conn->query($sql_sentence);
                    if($query){
                        if($query->num_rows > 0){
                            while($row = $query->fetch_assoc()){
                                array_push($resguardos, "'".$row["id_cajon"]."'");
                                array_push($resguardos, "'".$row["id_vehiculo"]."'");
                                array_push($resguardos, "'".$row["hora_llegada"]."'");
                                array_push($resguardos, "'".$row["hora_salida"]."'");
                                array_push($resguardos, "'".$row["hora_salida"]."'");
                                array_push($resguardos, "'".$row["pago"]."'");
                                array_push($resguardos, "'".$row["fecha"]."'");
                                array_push($resguardos, "'".$row["estatus"]."'");
                            }
                        }
                    }else{
                        echo $conn->error;
                    }
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

<script>    
    var tipoUsuario = [<?php echo $tipoUsuario;?>];

if(tipoUsuario == "admin"){
    document.getElementById("menu_inicio").style.display = "block";
    document.getElementById("menu_grafica").style.display = "block";
    document.getElementById("menu_usuarios").style.display = "block";
    document.getElementById("menu_vehiculos").style.display = "block";
    document.getElementById("menu_resguardos").style.display = "block";
    document.getElementById("menu_datosConductor").style.display = "none";
    document.getElementById("menu_reservaciones").style.display = "none";
}else if(tipoUsuario == "valet"){
    document.getElementById("menu_inicio").style.display = "block";
    document.getElementById("menu_grafica").style.display = "none";
    document.getElementById("menu_usuarios").style.display = "none";
    document.getElementById("menu_vehiculos").style.display = "none";
    document.getElementById("menu_resguardos").style.display = "none";
    document.getElementById("menu_datosConductor").style.display = "none";
    document.getElementById("menu_reservaciones").style.display = "none";
}else if(tipoUsuario == "cajero"){
    document.getElementById("menu_inicio").style.display = "block";
    document.getElementById("menu_grafica").style.display = "block";
    document.getElementById("menu_usuarios").style.display = "none";
    document.getElementById("menu_vehiculos").style.display = "none";
    document.getElementById("menu_resguardos").style.display = "block";
    document.getElementById("menu_datosConductor").style.display = "none";
    document.getElementById("menu_reservaciones").style.display = "block";
}
  var arregloClientesPHP = [<?php echo implode(",",$usuarios);?>];
  var arregloVehiculosPHP = [<?php echo implode(",",$vehiculos);?>];
  var arregloResguardosPHP = [<?php echo implode(",",$resguardos);?>];

    function generarCorte(){
        //Para generar el corte primero se limpia la tabla, en caso de que tenga filas ya creadas
        tabla = document.getElementById("tablaCorteCaja");
        while(tabla.rows.length > 1){
            tabla.deleteRow(tabla.rows.length-1);
        }
        sumaTotal = 0;
        fechaDesde = document.getElementById("fechaDesde").value;
        fechaHasta = document.getElementById("fechaHasta").value;
        //Luego se valida que la fecha de inicio sea menor a la fecha de fin del periodo a realizar el corte
        if(fechaDesde != "" && fechaHasta != ""){
            if(fechaDesde <= fechaHasta){
                //Ya que se valido la fecha se para a recorrer el arreglo de resguardos pagados
                for(i=0; i<arregloResguardosPHP.length; i+=8){
                    elementos = arregloResguardosPHP[i+6].split("/");
                    fechaResguardo = (elementos[2]+"-"+elementos[1]+"-"+elementos[0]);
                    //Si la fecha del resguardo esta en el periodo entonces se procede a agregar una nueva fila con las celdas de los datos
                    if(fechaResguardo >= fechaDesde && fechaResguardo <= fechaHasta){
                        fila = tabla.insertRow(tabla.rows.length);
                        numero = fila.insertCell(0);
                        cliente = fila.insertCell(1);
                        vehiculo = fila.insertCell(2);
                        cajon = fila.insertCell(3);
                        concepto = fila.insertCell(4);
                        subtotal = fila.insertCell(5);
                        //Se agrega el numero, el cajon
                        numero.innerHTML = tabla.rows.length; 
                        cajon.innerHTML =   arregloResguardosPHP[i];   
                        //Se recorre el arreglo de vehiculos y de clientes para recuperar sus placas, modelo, nombre y apellidos
                        for(m=0; m<arregloVehiculosPHP.length; m+=4){
                            if(arregloVehiculosPHP[m] == arregloResguardosPHP[m+1]){
                                vehiculo.innerHTML = arregloVehiculosPHP[m+1]+" "+arregloVehiculosPHP[m+2];

                                for(k=0; k<arregloClientesPHP.length; k+=3){
                                    if(arregloClientesPHP[k] == arregloVehiculosPHP[m+3]){
                                        cliente.innerHTML = arregloClientesPHP[k+1]+" "+arregloClientesPHP[k+2];
                                        break;
                                    }
                                } 

                                break;
                            }
                        }
                        //Se agrega el concepto y el total de cobro
                        concepto.innerHTML = "Estacionamiento de vehiculo";
                        subtotal.innerHTML = "$"+arregloResguardosPHP[i+5];
                        //Se van sumando cada pago para al final mostrar el total final
                        sumaTotal += parseFloat(arregloResguardosPHP[i+5]);
                    }
                }
                //Al final se agrega una ultima fila para mostrar el total final
                fila = tabla.insertRow(tabla.rows.length);
                numero = fila.insertCell(0);
                cliente = fila.insertCell(1);
                vehiculo = fila.insertCell(2);
                cajon = fila.insertCell(3);
                concepto = fila.insertCell(4);
                subtotal = fila.insertCell(5);
                
                numero.innerHTML = "Total final";
                subtotal.innerHTML = sumaTotal;
                if(tabla.rows.length>2){
                    subtotal.innerHTML = sumaTotal;
                }else{                    
                    concepto.innerHTML = "No se encontraron resguardos pagados";
                }
            }else{
                alert("La fecha de inicio debe ser menor a la fecha fin");
            }
        }else{
            alert("Elija la fecha del corte")
        }
    }

</script>