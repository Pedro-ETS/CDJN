<?php        
    //Se importa la barra de navegacion desde el archivo barranav.php
    include_once($_SERVER['DOCUMENT_ROOT']."/SistemaEstacionamiento/barranav.php");    
    $tipoUsuario = "'".$_SESSION["tipoUsuario"]."'";
?>
<!DOCTYPE html>
<html >
    <head>
            <meta charset="utf-8">
            <title>Estacionamiento</title>    
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta http-equiv="x-ua-Compatible" content="ie=edge">
            <link rel="stylesheet" type="text/css" href="estilos.css">
    </head>
    <body>
        <center>
        <div id="seles" class="form-group form-inline">            
            <label for="cajon" style="padding-right:10px;">Número de cajon:</label>
            <select class="form-control" id="cajon" onchange="crearGrafica()">
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
                <option>6</option>
                <option>7</option>
                <option>8</option>
                <option>9</option>
                <option>10</option>
                <option>11</option>
                <option>12</option>
                <option>13</option>
                <option>14</option>
                <option>15</option>
                <option>16</option>
                <option>17</option>
                <option>18</option>
                <option>19</option>
                <option>20</option>
                <option>21</option>
                <option>22</option>
                <option>23</option>
                <option>24</option>
            </select>
            
            <label for="tipo" style="margin-left:40px; padding-right:10px;">Tipo:</label>
            <select class="form-control" id="tipo" onchange="crearGrafica()">
                <option>Cobros</option>
                <option>Resguardos</option>
            </select>
        </div>    
        </center>            
        <div id="miGrafica">
            <canvas id="grafica">
            </canvas>
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
        $resguardos = array();
        //echo "<script>alert('');</script>";
        $sql_sentence = "select id, sum(pago), count(id_cajon) from cajones inner join resguardos on id = id_cajon group by id";
        $query = $conn->query($sql_sentence);
        if($query){
            if($query->num_rows > 0){
                while($row = $query->fetch_assoc()){
                    array_push($resguardos, $row["id"]);
                    array_push($resguardos, $row["sum(pago)"]);
                    array_push($resguardos, $row["count(id_cajon)"]);
                }
            }
        }else{
            echo $conn->error;
        }
    }
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

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
    var arregloResguardosPorCajon = [<?php echo implode(",",$resguardos);?>];
    crearGrafica();

    function crearGrafica(){
        cajon = document.getElementById("cajon").value;
        tipo = document.getElementById("tipo").value;
        var total, cantidad;
        creado = false;
        for(i=0; i<arregloResguardosPorCajon.length; i+=3){
            if(cajon == arregloResguardosPorCajon[i]){  
                total = arregloResguardosPorCajon[i+1];
                cantidad = arregloResguardosPorCajon[i+2];
                creado = true;                                 
            }
        }       
        var conteos;
        if(creado){     
            if(tipo == "Cobros"){                
                conteos = [parseFloat(total)];
            }else{       
                conteos = [parseInt(cantidad)];
            }                      
        }else{
            conteos = [0];
        }
        nuevagrafica = document.createElement('canvas');
            nuevagrafica.setAttribute('id','grafica');
            grafica = document.getElementById('grafica');
            grafica.parentNode.removeChild(grafica);
            parent = document.getElementById('miGrafica');
            parent.appendChild(nuevagrafica);

            var espacioCanvas = document.getElementById('grafica').getContext('2d');
            var etiquetas = ["Cajón: "+cajon];
            var chart = new Chart(espacioCanvas, {
                    type: 'bar',
                    data: {
                        labels: etiquetas,
                        datasets: [{
                            label: tipo,
                            data: conteos,
                            order: 0,
                            backgroundColor: ['#757700'],
                        }]
                    },
                    options: {
                        title: {
                            fontSize: 15,
                            display: true,
                            text: tipo+" en el cajón "+cajon
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }],
                            xAxes: [{
                                barPercentage: 0.3
                            }]
                        },
                        animation:{
                            animateRotate: true
                        }
                    }
                });  
    }
</script>