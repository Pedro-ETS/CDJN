<?php        
  //Se importa la barra de navegacion del archivo barranav.php
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

        <!--Formulario de registro del vehiculo-->
        <div class="container" style="padding-top:20px; padding-bottom:100px; max-width: 800px;">
            <form id="areaRegAuto" autocomplete="off"  method="post" action="registroAutos.php">                
            <h4 style="margin-left: -20px">Ingresa los siguientes datos:</h4>
            <input id="clienteID" name="clienteID" type=hidden>   
                <div class="form-group autocomplete">
                    <input type="text" class="form-control" id="registros_nombre" placeholder="Nombre del cliente..." name="registros_nombre">
                </div>
            <div class="form-inline form-group">
                <input type="text" class="form-control" id="matricula" placeholder="Matricula..." name="matricula" style="width: 45%">                
                <input type="text" class="form-control" id="marca" placeholder="Marca..." name="marca" style="width: 45%; margin-left: 9%">
            </div>
            <div class="form-inline form-group">
                <input type="text" class="form-control" id="modelo" placeholder="Modelo..." name="modelo" style="width: 45%">                
                <input type="text" class="form-control" id="color" placeholder="Color..." name="color" style="width: 45%; margin-left: 9%">
            </div>
            <div class="form-inline form-group">              
                <label for="tamanio" style="padding-right:20px;">Tamaño:</label>
                <select id="tamanio" name="tamanio" class="form-control">
                    <option value="Chico">Chico</option>
                    <option value="Grande">Grande</option>
                </select>
            </div>
            <div class="text-right" style="clear:left; padding-top:30px">
              <input type="submit" class="btn btn-md btn-success" value="Guardar"/>        
            </div>
            </form>
        </div>

            
    </body>


    <?php
    //Variables de conexion
    $server = "localhost";
    $user = "root";
    $password = "";
    $db = "estacionamiento";
    $conn = new mysqli($server, $user, $password, $db);
    //Si la conexion falla se captura el error
    if($conn->connect_error){
        die("Falló la conexión: ".$conn->connect_error);
    }else{//Si todo va bien se procede
        $clientes = array();
        //Se recupera el tipo de usuario conextado
        $tipoUsuario = "'".$_SESSION["tipoUsuario"]."'";
        //Se consultan los datos de los usuarios tipo conductor en el sistema
        $sql_sentence = "SELECT * FROM usuarios where tipo = 'conductor'";
        $query = $conn->query($sql_sentence);
        if($query){
            if($query->num_rows > 0){
              //Se recorren los datos de la consulta y se almacenan en el arrelo de $clientes
                while($row = $query->fetch_assoc()){
                    array_push($clientes, "'".$row["id"]."'");
                    array_push($clientes, "'".$row["nombre"]."'");
                    array_push($clientes, "'".$row["apellidos"]."'");
                    array_push($clientes, "'".$row["telefono"]."'");
                    array_push($clientes, "'".$row["correo"]."'");
                }            
            }
            
        }else{
            echo $conn->error;
        }
    }
?>




<script type="text/javascript">
//Segun el tipo de usuario se procede a mostrar/ocultar las opciones de la barra de navegacion
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
//Se recuperan los datos del arreglo en PHP a JavaScript
var inputName = document.getElementById("registros_nombre");
var arregloClientes = new Array();
var arregloClientesPHP = [<?php echo implode(",",$clientes);?>];
rellenaAutocompletar();    
cargaAutocompletar();
//Se rellena el arregloClientes para usarlo en el autocompletar
function rellenaAutocompletar(){
    for(i=0; i<arregloClientesPHP.length; i+=5){
        arregloClientes.push(arregloClientesPHP[i+1]+" "+arregloClientesPHP[i+2]);
    }
}
//Funcion que permite crear el autocompletar en el formulario para seleccionar al usuario conductor al que le pertenece el vehiculo a registrar
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
            marcarSeleccion(inputName.value);
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
        marcarSeleccion(inputName.value);
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

//Cuando se selecciona a un usuario del autocompletar entonces se guarda su id en el input ClienteID para que sea enviado al formulario de registro
function marcarSeleccion(entrada){    
    for(i=0; i<arregloClientesPHP.length; i+=5){
        if(entrada == arregloClientesPHP[i+1]+" "+arregloClientesPHP[i+2]){
            document.getElementById("clienteID").value = arregloClientesPHP[i];
            break;
        }
    }
}

//Esta funcion con JQuery es la que se invocará cuando se envie el formulario de registro, se validan los datos antes de enviarla con la funcion comprobar
$(document).ready(function () {
    // Listen to submit event on the <form> itself!
    $('#areaRegAuto').submit(function (e) {    
      matricula = document.getElementById("matricula").value;
      marca = document.getElementById("marca").value;
      mod = document.getElementById("modelo").value;
      color = document.getElementById("color").value;
      if(comprobar(matricula, marca, mod, color)){            
        //REGISTRO
        $.post("Estacionamiento/registroAutos.php", {         
          matri: matricula,
          modelo: mod,
          col: color
        }).complete(function() {
          limpiarCampos();     
        });                            
      }else{          
        e.preventDefault();
      }  
    });    
});


//funcion que valida los datos ingresados
function comprobar(matricula, marca, modelo, color){    
                if((matricula != "")&&((matricula.match(/[A-Z]{3}\-[0-9]{2}\-[0-9]{2}/) == matricula)||(matricula.match(/[A-Z]\-\d{3}\-[A-Z]{3}/)==matricula) ||(matricula.match(/\d{3}\-[A-Z]{3}/)==matricula)||(matricula.match(/[A-Z]\-\d{2}\-[A-Z]{3}/)==matricula))){
                    
                  if((marca!="")&&marca.match(/[(A-ZÁ-Úa-zá-ú0-9)+\s?+]+/)==marca){

                    if((modelo!="")&&modelo.match(/[(A-ZÁ-Úa-zá-ú0-9)+\s?+]+/)==modelo){

                      if((color!="")&&color.match(/[(A-ZÁ-Úa-zá-ú)+\s?+]+/)==color){
                        return true;
                      }else{ 
                        alert("Error en el color - Campo vacío o formato inválido (Sólo letras y espacios)");
                        return false;
                      }

                    }else{ 
                      alert("Error en el modelo - Campo vacío o formato inválido (Sólo letras, digitos y espacios)");
                      return false;
                    }
                  }else{ 
                    alert("Error en la marca - Campo vacío o formato inválido (Sólo letras, digitos y espacios)");
                    return false;                    
                  }
                }else{               
                    alert("Error en la matricula del auto - Campo vacío o formato inválido \nL = Letra, D = Digito \tFormatos válidos: \n(LLL-DD-DD, L-DDD-LLL, DDD-LLL o bien L-DD-LLL)");
                    return false;
                }
}

//Funcion que limpia los campos para que cuando se registre el vehiculo no se mantengan visibles los datos
function limpiarCampos(){    
    document.getElementById("matricula").value = "";
    document.getElementById("marca").value = "";
    document.getElementById("modelo").value = "";
    document.getElementById("color").value = "";
}


    </script>
</html>