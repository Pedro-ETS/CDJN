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
        <div class="container" style="padding-top:20px; padding-bottom:100px; max-width: 800px;">
            <form autocomplete="off" id="areaRegAuto" onsubmit=false>                
            <h4>Buscar vehiculo</h4>
                <div class="form-group autocomplete">
                    <input type="text" class="form-control" id="registros_nombre" placeholder="Buscar por cliente..." name="registros_nombre">
                </div>
            <div class="form-inline form-group autocomplete">
                <label for="marca" style="padding-right:20px;">Marca:</label>
                <input type="text" class="form-control" id="marca" placeholder="Busqueda por marca..." name="marca" readonly style="width: 90%" >                             
            </div>
            <div class="form-inline form-group autocomplete">
                <label for="matricula" style="padding-right:20px;">Placas:</label>
                <input type="text" class="form-control" id="matricula" name="matricula" style="width: 90%" readonly>                                
            </div>
            <div class="form-inline form-group">
                <label for="modelo" style="padding-right:20px">Modelo:</label>
                <input type="text" class="form-control" id="modelo" name="modelo" style="width: 85%" readonly>    
            </div>
            <div class="form-inline form-group">              
                <label for="color" style="padding-right:20px; ">Color:</label>
                <input type="text" class="form-control" id="color"  name="color" readonly style="width: 90%" >
            </div>
            <div class="form-inline form-group">              
                <label for="tamanio" style="padding-right:20px;">Tamaño:</label>
                <select id="tamanio" name="tamanio" class="form-control">
                    <option value="Chico">Chico</option>
                    <option value="Grande">Grande</option>
                </select>
            </div>
            </form>
                               
            <div class="form-group inline-group">
                <form action="modificarAuto.php" method=post onsubmit="modificar()" style="float: left; padding-right: 20px;">
                <input id="modificarAuto" name="modificarAuto" type=hidden>             
                <input type="submit" class="btn " value="Guardar cambios" id="btnModificar" disabled>
                </form> 
                <form action="eliminarAuto.php" method=post onsubmit="eliminar()" >
                <input id="eliminarAuto" name="eliminarAuto" type=hidden>             
                <input type="submit" class="btn " value="Eliminar" id="btnEliminar" disabled>
                </form>
            </div>
        </div>

            
    </body>


    <?php
    $server = "localhost";
    $user = "root";
    $password = "";
    $db = "estacionamiento";
    $conn = new mysqli($server, $user, $password, $db);
    if($conn->connect_error){
        die("Falló la conexión: ".$conn->connect_error);
    }else{
        $clientes = array();
        $vehiculos = array();
        $tipoUsuario = "'".$_SESSION["tipoUsuario"]."'";
        $sql_sentence = "SELECT * FROM usuarios where tipo = 'conductor'";
        $query = $conn->query($sql_sentence);
        if($query){
            if($query->num_rows > 0){
                while($row = $query->fetch_assoc()){
                    array_push($clientes, "'".$row["id"]."'");
                    array_push($clientes, "'".$row["nombre"]."'");
                    array_push($clientes, "'".$row["apellidos"]."'");
                    array_push($clientes, "'".$row["telefono"]."'");
                    array_push($clientes, "'".$row["correo"]."'");
                }            
            }
            
            $sql_sentence = "SELECT * FROM vehiculos";
            $query = $conn->query($sql_sentence);
            if($query){
                if($query->num_rows > 0){
                    while($row = $query->fetch_assoc()){
                        array_push($vehiculos, "'".$row["id"]."'");
                        array_push($vehiculos, "'".$row["placas"]."'");
                        array_push($vehiculos, "'".$row["marca"]."'");
                        array_push($vehiculos, "'".$row["modelo"]."'");
                        array_push($vehiculos, "'".$row["color"]."'");
                        array_push($vehiculos, "'".$row["tamanio"]."'");
                        array_push($vehiculos, "'".$row["cliente"]."'");
                    }
                }
            }
        }else{
            echo $conn->error;
        }
    }
?>




<script type="text/javascript">
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
        var inputName = document.getElementById("registros_nombre");        
        var inputCar = document.getElementById("marca");
        var arregloClientes = new Array();
        var arregloClientesPHP = [<?php echo implode(",",$clientes);?>];
        var arregloAutos = new Array();
        var arregloAutosPHP = [<?php echo implode(",",$vehiculos);?>];
        rellenaAutocompletar();    
        cargaAutocompletar();

        function rellenaAutocompletar(){
            for(i=0; i<arregloClientesPHP.length; i+=5){
                arregloClientes.push(arregloClientesPHP[i+1]+" "+arregloClientesPHP[i+2]);
            }
        }

function cargaAutocompletar(){
  inputName.addEventListener("input", function(e) {
    var a, b, i, val = this.value;
    /*close any already open lists of autocompleted values*/
    closeAllLists();
    if (!val) { return false;}
    currentFocus = -1;
    /*create a DIV element that will contain the items (values):*/
    a = document.createElement("DIV");
    a.setAttribute("id", this.id + "autocomplete-list");
    a.setAttribute("class", "autocomplete-items");
    /*append the DIV element as a child of the autocomplete container:*/
    this.parentNode.appendChild(a);
    /*for each item in the array...*/
    for (i = 0; i < arregloClientes.length; i++) {
      /*check if the item starts with the same letters as the text field value:*/
      if (arregloClientes[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
        /*create a DIV element for each matching element:*/
        b = document.createElement("DIV");
        /*make the matching letters bold:*/
        b.innerHTML = "<strong>" + arregloClientes[i].substr(0, val.length) + "</strong>";
        b.innerHTML += arregloClientes[i].substr(val.length);
        /*insert a input field that will hold the current array item's value:*/
        b.innerHTML += "<input type='hidden' value='" + arregloClientes[i] + "'>";
        /*execute a function when someone clicks on the item value (DIV element):*/
        b.addEventListener("click", function(e) {
            /*insert the value for the autocomplete text field:*/
            inputName.value = this.getElementsByTagName("input")[0].value;
            /*close the list of autocompleted values,
            (or any other open lists of autocompleted values:*/
            listarAutos(inputName.value);
            closeAllLists();
        });
        a.appendChild(b);
      }
    }    
});
/*execute a function presses a key on the keyboard:*/
inputName.addEventListener("keydown", function(e) {
    var x = document.getElementById(this.id + "autocomplete-list");
    if (x) x = x.getElementsByTagName("div");
    if (e.keyCode == 40) {
      /*If the arrow DOWN key is pressed,
      increase the currentFocus variable:*/
      currentFocus++;
      /*and and make the current item more visible:*/
      addActive(x);
    } else if (e.keyCode == 38) { //up
      /*If the arrow UP key is pressed,
      decrease the currentFocus variable:*/
      currentFocus--;
      /*and and make the current item more visible:*/
      addActive(x);
    } else if (e.keyCode == 13) {
      /*If the ENTER key is pressed, prevent the form from being submitted,*/
      e.preventDefault();
      if (currentFocus > -1) {
        /*and simulate a click on the "active" item:*/
        if (x) x[currentFocus].click();
        listarAutos(inputName.value);
      }
    }
});
function addActive(x) {
  /*a function to classify an item as "active":*/
  if (!x) return false;
  /*start by removing the "active" class on all items:*/
  removeActive(x);
  if (currentFocus >= x.length) currentFocus = 0;
  if (currentFocus < 0) currentFocus = (x.length - 1);
  /*add class "autocomplete-active":*/
  x[currentFocus].classList.add("autocomplete-active");
}
function removeActive(x) {
  /*a function to remove the "active" class from all autocomplete items:*/
  for (var i = 0; i < x.length; i++) {
    x[i].classList.remove("autocomplete-active");
  }
}
function closeAllLists(elmnt) {
  /*close all autocomplete lists in the document,
  except the one passed as an argument:*/
  var x = document.getElementsByClassName("autocomplete-items");
  for (var i = 0; i < x.length; i++) {
    if (elmnt != x[i] && elmnt != inputName) {
      x[i].parentNode.removeChild(x[i]);
    }
  }
}
/*execute a function when someone clicks in the document:*/
document.addEventListener("click", function (e) {
    closeAllLists(e.target);
});
}

function listarAutos(cliente){
  $("#marca").removeAttr("readonly", false);
  var seleccionado = "";
  arregloAutos = new Array();
    for(i=0; i<arregloClientesPHP.length; i+=5){
        if(cliente == arregloClientesPHP[i+1]+" "+arregloClientesPHP[i+2]){
            for(j=0; j<arregloAutosPHP.length; j+=7){
                if(arregloClientesPHP[i] == arregloAutosPHP[j+6]){
                    arregloAutos.push(arregloAutosPHP[j+2]+", Modelo: "+arregloAutosPHP[j+3]+", Placas: "+arregloAutosPHP[j+1]);
                }
            }
            break;
        }
    }

  inputCar.addEventListener("input", function(e) {
    var a, b, i, val = this.value;
    /*close any already open lists of autocompleted values*/
    closeAllLists();
    if (!val) { return false;}
    currentFocus = -1;
    /*create a DIV element that will contain the items (values):*/
    a = document.createElement("DIV");
    a.setAttribute("id", this.id + "autocomplete-list");
    a.setAttribute("class", "autocomplete-items");
    /*append the DIV element as a child of the autocomplete container:*/
    this.parentNode.appendChild(a);
    /*for each item in the array...*/
    for (i = 0; i < arregloAutos.length; i++) {
      /*check if the item starts with the same letters as the text field value:*/
      if (arregloAutos[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
        /*create a DIV element for each matching element:*/
        b = document.createElement("DIV");
        /*make the matching letters bold:*/
        b.innerHTML = "<strong>" + arregloAutos[i].substr(0, val.length) + "</strong>";
        b.innerHTML += arregloAutos[i].substr(val.length);
        /*insert a input field that will hold the current array item's value:*/
        b.innerHTML += "<input type='hidden' value='" + arregloAutos[i] + "'>";
        /*execute a function when someone clicks on the item value (DIV element):*/
        b.addEventListener("click", function(e) {
            /*insert the value for the autocomplete text field:*/
            inputCar.value = this.getElementsByTagName("input")[0].value;
            /*close the list of autocompleted values,
            (or any other open lists of autocompleted values:*/
            rellenarCampos(inputCar.value);
            hacerEditables();
            closeAllLists();
        });
        a.appendChild(b);
      }
    }    
});
/*execute a function presses a key on the keyboard:*/
inputCar.addEventListener("keydown", function(e) {
    var x = document.getElementById(this.id + "autocomplete-list");
    if (x) x = x.getElementsByTagName("div");
    if (e.keyCode == 40) {
      /*If the arrow DOWN key is pressed,
      increase the currentFocus variable:*/
      currentFocus++;
      /*and and make the current item more visible:*/
      addActive(x);
    } else if (e.keyCode == 38) { //up
      /*If the arrow UP key is pressed,
      decrease the currentFocus variable:*/
      currentFocus--;
      /*and and make the current item more visible:*/
      addActive(x);
    } else if (e.keyCode == 13) {
      /*If the ENTER key is pressed, prevent the form from being submitted,*/
      e.preventDefault();
      if (currentFocus > -1) {
        /*and simulate a click on the "active" item:*/
        if (x) x[currentFocus].click();
        rellenarCampos(inputCar.value);
        hacerEditables();
      }
    }
});
function addActive(x) {
  /*a function to classify an item as "active":*/
  if (!x) return false;
  /*start by removing the "active" class on all items:*/
  removeActive(x);
  if (currentFocus >= x.length) currentFocus = 0;
  if (currentFocus < 0) currentFocus = (x.length - 1);
  /*add class "autocomplete-active":*/
  x[currentFocus].classList.add("autocomplete-active");
}
function removeActive(x) {
  /*a function to remove the "active" class from all autocomplete items:*/
  for (var i = 0; i < x.length; i++) {
    x[i].classList.remove("autocomplete-active");
  }
}
function closeAllLists(elmnt) {
  /*close all autocomplete lists in the document,
  except the one passed as an argument:*/
  var x = document.getElementsByClassName("autocomplete-items");
  for (var i = 0; i < x.length; i++) {
    if (elmnt != x[i] && elmnt != inputName) {
      x[i].parentNode.removeChild(x[i]);
    }
  }
}
}


function hacerEditables(){
  $("#matricula").removeAttr("readonly", false);
  $("#color").removeAttr("readonly", false);
  $("#modelo").removeAttr("readonly", false);
  $("#btnEliminar").removeAttr("disabled", false);
  $("#btnModificar").removeAttr("disabled", false);
}

function rellenarCampos(datosAuto){
    for(i=0; i<arregloAutosPHP.length; i+=7){
        if(datosAuto == arregloAutosPHP[i+2]+", Modelo: "+arregloAutosPHP[i+3]+", Placas: "+arregloAutosPHP[i+1]){
            seleccionado = arregloAutosPHP[i+1];
            document.getElementById("matricula").value = arregloAutosPHP[i+1];
            document.getElementById("marca").value = arregloAutosPHP[i+2];
            document.getElementById("modelo").value = arregloAutosPHP[i+3];
            document.getElementById("color").value = arregloAutosPHP[i+4];
            document.getElementById("tamanio").value = arregloAutosPHP[i+5];
            break;
        }
    }
}

function eliminar(){
    for(i=0; i<arregloAutosPHP.length; i++){
        if(seleccionado == arregloAutosPHP[i+1]){
          document.getElementById("eliminarAuto").value = arregloAutosPHP[i];
        }else{
            i += 6;
        }
    }
}
function modificar(){
    arreglo = new Array();
    for(i=0; i<arregloAutosPHP.length; i+=7){
        if(seleccionado == arregloAutosPHP[i+1]){
            arreglo.push(arregloAutosPHP[i]);
            arreglo.push(document.getElementById("matricula").value);
            arreglo.push(document.getElementById("marca").value);
            arreglo.push(document.getElementById("modelo").value);
            arreglo.push(document.getElementById("color").value);
            arreglo.push(document.getElementById("tamanio").value);
            break;
        }
    }
    document.getElementById("modificarAuto").value = arreglo.toString();
}


    </script>
</html>