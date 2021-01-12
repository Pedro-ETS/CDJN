<!--Aqui se importa la barra de navegacion, es el archivo barranav.php-->
<?php        
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
        <!--Se dibuja el estacionamiento con espacios para los cajones-->
        <div id="resguardos">
            <div class="fila">
                <div id="cajon1" class="cajon">
                    <img id="cajon1_img"/>
                    <p id="cajon1_situacion">(Vacío)</p>
                    <p>1</p>
                </div>
                <div id="cajon2" class="cajon">
                    <img id="cajon2_img"/>
                    <p id="cajon2_situacion">(Vacío)</p>
                    <p>2</p>
                </div>
                <div id="cajon3" class="cajon">
                    <img id="cajon3_img"/>
                    <p id="cajon3_situacion">(Vacío)</p>
                    <p>3</p>
                </div>
                <div id="cajon4" class="cajon">
                    <img id="cajon4_img"/>
                    <p id="cajon4_situacion">(Vacío)</p>
                    <p>4</p>
                </div>
                <div id="cajon5" class="cajon">
                    <img id="cajon5_img"/>
                    <p id="cajon5_situacion">(Vacío)</p>
                    <p>5</p>
                </div>
                <div id="cajon6" class="cajon">
                    <img id="cajon6_img"/>
                    <p id="cajon6_situacion">(Vacío)</p>
                    <p>6</p>
                </div>
            </div>
            <div class="fila">
                <div id="cajon7" class="cajon">
                    <img id="cajon7_img"/>
                    <p id="cajon7_situacion">(Vacío)</p>
                    <p>7</p>
                </div>
                <div id="cajon8" class="cajon">
                    <img id="cajon8_img"/>
                    <p id="cajon8_situacion">(Vacío)</p>
                    <p>8</p>
                </div>
                <div id="cajon9" class="cajon">
                    <img id="cajon9_img"/>
                    <p id="cajon9_situacion">(Vacío)</p>
                    <p>9</p>
                </div>
                <div id="cajon10" class="cajon">
                    <img id="cajon10_img"/>
                    <p id="cajon10_situacion">(Vacío)</p>
                    <p>10</p>
                </div>
                <div id="cajon11" class="cajon">
                    <img id="cajon11_img"/>
                    <p id="cajon11_situacion">(Vacío)</p>
                    <p>11</p>
                </div>
                <div id="cajon12" class="cajon">
                    <img id="cajon12_img"/>
                    <p id="cajon12_situacion">(Vacío)</p>
                    <p>12</p>
                </div>
            </div>
            <div class="fila">
                <div id="cajon13" class="cajon">
                    <img id="cajon13_img"/>
                    <p id="cajon13_situacion">(Vacío)</p>
                    <p>13</p>
                </div>
                <div id="cajon14" class="cajon">
                    <img id="cajon14_img"/>
                    <p id="cajon14_situacion">(Vacío)</p>
                    <p>14</p>
                </div>
                <div id="cajon15" class="cajon">
                    <img id="cajon15_img"/>
                    <p id="cajon15_situacion">(Vacío)</p>
                    <p>15</p>
                </div>
                <div id="cajon16" class="cajon">
                    <img id="cajon16_img"/>
                    <p id="cajon16_situacion">(Vacío)</p>
                    <p>16</p>
                </div>
                <div id="cajon17" class="cajon">
                    <img id="cajon17_img"/>
                    <p id="cajon17_situacion">(Vacío)</p>
                    <p>17</p>
                </div>
                <div id="cajon18" class="cajon">
                    <img id="cajon18_img"/>
                    <p id="cajon18_situacion">(Vacío)</p>
                    <p>18</p>
                </div>             
            </div>
            <div class="fila">
                <div id="cajon19" class="cajon">
                    <img id="cajon19_img"/>
                    <p id="cajon19_situacion">(Vacío)</p>
                    <p>19</p>
                </div>
                <div id="cajon20" class="cajon">
                    <img id="cajon20_img"/>
                    <p id="cajon20_situacion">(Vacío)</p>
                    <p>20</p>
                </div>
                <div id="cajon21" class="cajon">
                    <img id="cajon21_img"/>
                    <p id="cajon21_situacion">(Vacío)</p>
                    <p>21</p>
                </div>
                <div id="cajon22" class="cajon">
                    <img id="cajon22_img"/>
                    <p id="cajon22_situacion">(Vacío)</p>
                    <p>22</p>
                </div>
                <div id="cajon23" class="cajon">
                    <img id="cajon23_img"/>
                    <p id="cajon23_situacion">(Vacío)</p>
                    <p>23</p>
                </div>
                <div id="cajon24" class="cajon">
                    <img id="cajon24_img"/>
                    <p id="cajon24_situacion">(Vacío)</p>
                    <p>24</p>
                </div>           
            </div>
        </div>
        
    </body>
</html>

<?php
//Variables para la conexion a la BD estacionamiento
$server = "localhost";
$user = "root";
$password = "";
$db = "estacionamiento";
$conn = new mysqli($server, $user, $password, $db);
//Si la conexion falla se captura y muestra el error
if($conn->connect_error){
    die("Falló la conexión: ".$conn->connect_error);
}else{//Si la conexion es correcta se procese
    $resguardos = array();
    //Se recupera que tipo de usuario esta conectado
    $tipoUsuario = "'".$_SESSION["tipoUsuario"]."'";
    //Se recuperan todos los datos de los cajones que esten ocupados/reservados para cambiar su color e imagen segun su situacion
    $sql_sentence = "SELECT id, situacion FROM cajones WHERE situacion = 'Ocupado' OR situacion = 'Reservado'";
    $query = $conn->query($sql_sentence);
    if($query){
        if($query->num_rows > 0){
            //Se recorren los datos de la consulta y se agregan al arreglo $resguardos
            while($row = $query->fetch_assoc()){
                array_push($resguardos, "'".$row["id"]."'");
                array_push($resguardos, "'".$row["situacion"]."'");
            }
        }
    }else{
        echo $conn->error;
    }
}
?>

<script>    
    //Se recuperan los arreglos desde el PHP y se guardan en variables de JavaScript
    var arregloResguardos = [<?php echo implode(",",$resguardos);?>];
    var tipoUsuario = [<?php echo $tipoUsuario;?>];
    //Segun el tipo de usuario se van a mostrar/ocultar las opciones en la barra de navegacion
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
    //Aqui se muestra el conteo de resguardos ocupados/reservados
    document.getElementById("ocupacion").innerHTML = "Total de resguardos: "+arregloResguardos.length/2;
    for(i=0; i<arregloResguardos.length; i+=2){
        //Si esta ocupado se colorea de rojo, si esta reservado de amarillo, se usa la misma imagen para los dos
        if(arregloResguardos[i+1] == "Ocupado"){
            document.getElementById("cajon"+arregloResguardos[i]+"_situacion").innerHTML = "Ocupado";
            document.getElementById("cajon"+arregloResguardos[i]).style.backgroundColor = "red";
            document.getElementById("cajon"+arregloResguardos[i]+"_img").style.backgroundColor = "red";
            document.getElementById("cajon"+arregloResguardos[i]).style.color = "white";
        }else{
            document.getElementById("cajon"+arregloResguardos[i]+"_situacion").innerHTML = "Reservado";
            document.getElementById("cajon"+arregloResguardos[i]).style.backgroundColor = "yellow";
            document.getElementById("cajon"+arregloResguardos[i]+"_img").style.backgroundColor = "yellow";
            document.getElementById("cajon"+arregloResguardos[i]).style.color = "black";
        }
        document.getElementById("cajon"+arregloResguardos[i]+"_img").src = "icon-car.png";
    }
</script>