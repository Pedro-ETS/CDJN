<?php        
    //Se importa la barra de navegacion desde el archivo barranav.php
    include_once($_SERVER['DOCUMENT_ROOT']."/SistemaEstacionamiento/barranav.php");    
?>
<!DOCTYPE html>
<html>
    <head>        
        <meta charset="utf-8">
        <title>Estacionamiento - Reservaciones</title>    
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="estilos.css">
    </head>
    <body>    
        <div class="container h-90">
            <h3 class="text-center">Reservaciones</h3>

            <div>
                <form style="width: 100vw;" method="post" action="reservarCajon.php">

                        <b><span style="font-size: 18px;">Cliente</span></b>
                        <div class="input-group">    
                            <input id="clienteID" name="clienteID" type=hidden>  
                            <input style="width: 50vw;" autocomplete="off" id="input__idBuscarConductor" type="text" name="cliente" placeholder="" aria-label="Username" aria-describedby="basic-addon1" disabled>
                        </div>

                        <b><span style="font-size: 18px;">Vehiculo</span></b>
                        <div class="input-group">    
                            <input id="autoID" name="autoID" type=hidden>  
                            <input style="width: 50vw;" autocomplete="off" id="input__idBuscarVehiculo" type="text" name="vehiculo" placeholder="" aria-label="Username" aria-describedby="basic-addon1" disabled>
                        </div>

                    
                        <div class="input-group-prepend">
                        <b><span style="font-size: 18px;">Cajones por reservar</span></b>
                        </div>    
                        <input id="cajonID" name="cajonID" type=hidden>  
                        <select style="width: 25vw" id="input__cajon" name="cajon" onchange="cajonSeleccionado()">
                        </select>
                        <br><br>
                    <div class="input-group mb-5">
                        <button id="btnGuardar" type="submit" class="btn" disabled>Reservar</button>
                    </div>
                </form>
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
    $users = array();
    $tipoUsuario = "'".$_SESSION["tipoUsuario"]."'";
    if($tipoUsuario == "conductor"){
        $sql_sentence = "SELECT * FROM usuarios where usuario = '".$_SESSION["UsuarioID"]."'";
    }else{
        $sql_sentence = "SELECT * FROM usuarios where tipo = 'conductor'";
    }
    $query = $conn->query($sql_sentence);
    if($query){
        if($query->num_rows > 0){
            while($row = $query->fetch_assoc()){
                array_push($users, "'".$row["id"]."'");
                array_push($users, "'".$row["nombre"]."'");
                array_push($users, "'".$row["apellidos"]."'");
            }
            $cars = array();
            $sql_sentence = "SELECT * FROM vehiculos";
            $query = $conn->query($sql_sentence);
            if($query){
                if($query->num_rows > 0){
                    while($row = $query->fetch_assoc()){
                        array_push($cars, "'".$row["id"]."'");
                        array_push($cars, "'".$row["marca"]."'");
                        array_push($cars, "'".$row["modelo"]."'");
                        array_push($cars, "'".$row["cliente"]."'");
                    }
                    $cajones = array();
                    $sql_sentence = "SELECT * FROM cajones WHERE situacion = 'Disponible'";
                    $query = $conn->query($sql_sentence);
                    if($query){
                        if($query->num_rows > 0){
                            while($row = $query->fetch_assoc()){
                                array_push($cajones, "'".$row["id"]."'");
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

<script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@7.2.0/dist/js/autoComplete.min.js"></script>
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

    document.getElementById("input__idBuscarConductor").disabled = false;
}else if(tipoUsuario == "conductor"){
    document.getElementById("menu_inicio").style.display = "none";
    document.getElementById("menu_grafica").style.display = "none";
    document.getElementById("menu_usuarios").style.display = "none";
    document.getElementById("menu_vehiculos").style.display = "none";
    document.getElementById("menu_resguardos").style.display = "none";
    document.getElementById("menu_datosConductor").style.display = "block";
    document.getElementById("menu_reservaciones").style.display = "block";
    document.getElementById("input__idBuscarVehiculo").disabled = false;

}
$("form").keypress(function(e) {
  //Enter key
  if (e.which == 13) {
    return false;
  }
});
var arregloCajonesPHP = [<?php echo implode(",",$cajones);?>];
for(i=0; i<arregloCajonesPHP.length; i+=1){
    document.getElementById("input__cajon").innerHTML += "<option>"+arregloCajonesPHP[i]+"</option>";
}
        var arregloAutosPHP = [<?php echo implode(",",$cars);?>];
        var arregloAutos = new Array();
        
    var arregloUsuariosPHP = [<?php echo implode(",",$users);?>];
        var arregloUsuarios = new Array();
        for(i=0; i<arregloUsuariosPHP.length; i+=3){
            arregloUsuarios.push(arregloUsuariosPHP[i+1]+" "+arregloUsuariosPHP[i+2]);
        }
    if(tipoUsuario == "conductor"){
        document.getElementById("clienteID").value = arregloUsuariosPHP[0];
        document.getElementById("input__idBuscarConductor").value = arregloUsuariosPHP[1]+" "+arregloUsuariosPHP[2];
        for(i=0; i<arregloAutosPHP.length; i+=4){
            if(document.getElementById("clienteID").value == arregloAutosPHP[i+3]){
                arregloAutos.push(arregloAutosPHP[i+1]+" "+arregloAutosPHP[i+2]);
            }
        }
        generarAutoCompletarVehiculos();
    }else{
        new autoComplete({
            data: {                              // Data src [Array, Function, Async] | (REQUIRED)
                src: arregloUsuarios,
                key: [""],
                cache: false
            },
            sort: (a, b) => {                    // Sort rendered results ascendingly | (Optional)
                if (a.match < b.match) return -1;
                if (a.match > b.match) return 1;
                return 0;
            },
            placeHolder: "",     // Place Holder text                 | (Optional)
            selector: "#input__idBuscarConductor",           // Input field selector              | (Optional)
            threshold: 0,                        // Min. Chars length to start Engine | (Optional)
            debounce: 0,                       // Post duration for engine to start | (Optional)
            searchEngine: "strict",              // Search Engine type/mode           | (Optional)
            resultsList: {                       // Rendered results list object      | (Optional)
                render: true,
                /* if set to false, add an eventListener to the selector for event type
                "autoComplete" to handle the result */
                container: source => {
                    source.setAttribute("id", "autoComplete_list");
                },
                destination: document.querySelector("#autoComplete"),
                position: "afterend",
                element: "ul"
            },
            maxResults: 3,                         // Max. number of rendered results | (Optional)
            highlight: true,                       // Highlight matching results      | (Optional)
            resultItem: {                          // Rendered result item            | (Optional)
                content: (data, source) => {
                    source.innerHTML = data.match;
                },
                element: "li"
            },
            noResults: () => {                     // Action script on noResults      | (Optional)
                const result = document.createElement("li");
                result.setAttribute("class", "no_result");
                result.setAttribute("tabindex", "1");
                result.innerHTML = "Sin coincidencias";
                document.querySelector("#autoComplete_list").appendChild(result);
                console.log("No results");
            },
            onSelection: feedback => {             // Action script onSelection event | (Optional)
                document.getElementById("input__idBuscarVehiculo").disabled = false;
                
                for(i=0; i<arregloUsuariosPHP.length; i+=3){
                    if(feedback.selection.value == arregloUsuariosPHP[i+1]+" "+arregloUsuariosPHP[i+2]){
                        document.getElementById("input__idBuscarConductor").value = feedback.selection.value;
                        document.getElementById("clienteID").value = arregloUsuariosPHP[i];
                        break;
                    }
                }   
                
                for(i=0; i<arregloAutosPHP.length; i+=4){
                    if(document.getElementById("clienteID").value == arregloAutosPHP[i+3]){
                        arregloAutos.push(arregloAutosPHP[i+1]+" "+arregloAutosPHP[i+2]);
                    }
                }
                generarAutoCompletarVehiculos();             
            }             
        });
    }

        
    function generarAutoCompletarVehiculos(){
        new autoComplete({
            data: {                              // Data src [Array, Function, Async] | (REQUIRED)
                src: arregloAutos,
                key: [""],
                cache: false
            },
            sort: (a, b) => {                    // Sort rendered results ascendingly | (Optional)
                if (a.match < b.match) return -1;
                if (a.match > b.match) return 1;
                return 0;
            },
            placeHolder: "",     // Place Holder text                 | (Optional)
            selector: "#input__idBuscarVehiculo",           // Input field selector              | (Optional)
            threshold: 0,                        // Min. Chars length to start Engine | (Optional)
            debounce: 0,                       // Post duration for engine to start | (Optional)
            searchEngine: "strict",              // Search Engine type/mode           | (Optional)
            resultsList: {                       // Rendered results list object      | (Optional)
                render: true,
                /* if set to false, add an eventListener to the selector for event type
                "autoComplete" to handle the result */
                container: source => {
                    source.setAttribute("id", "autoComplete_list1");
                },
                destination: document.querySelector("#autoComplete"),
                position: "afterend",
                element: "ul"
            },
            maxResults: 3,                         // Max. number of rendered results | (Optional)
            highlight: true,                       // Highlight matching results      | (Optional)
            resultItem: {                          // Rendered result item            | (Optional)
                content: (data, source) => {
                    source.innerHTML = data.match;
                },
                element: "li"
            },
            noResults: () => {                     // Action script on noResults      | (Optional)
                const result = document.createElement("li");
                result.setAttribute("class", "no_result");
                result.setAttribute("tabindex", "1");
                result.innerHTML = "Sin coincidencias";
                document.querySelector("#autoComplete_list1").appendChild(result);
                console.log("No results");
            },
            onSelection: feedback => {             // Action script onSelection event | (Optional)

                for(i=0; i<arregloAutosPHP.length; i+=4){
                    if(feedback.selection.value == arregloAutosPHP[i+1]+" "+arregloAutosPHP[i+2]){
                        document.getElementById("input__idBuscarVehiculo").value = feedback.selection.value;
                        document.getElementById("autoID").value = arregloAutosPHP[i];
                        break;
                    }
                }                
                document.getElementById("btnGuardar").disabled = false;
            }             
        });
    }
    function cajonSeleccionado(){
        document.getElementById("cajonID").value = document.getElementById("input__cajon").value;
    }

</script>