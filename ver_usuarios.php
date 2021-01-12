<!--Se importa la barra de navegacion-->
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
        <!--Formulario que contiene los datos de los usuarios-->
        <div class="container" style="padding-top:20px; padding-bottom:100px; max-width: 800px;">
            <h4>Buscar cliente</h4>            
            <form autocomplete="off" id="areaRegAuto" onsubmit=false>   
                <div class="form-group autocomplete">
                    <label style="float: left; margin-right: 2%" for="registros_nombre">Nombre:</label>
                    <input style="width: 80%; float: left" type="text" class="form-control" id="registros_nombre" placeholder="Búscar por nombre..." name="registros_nombre">
                </div>
                <br><br><br>
                <div class="form-group">
                    <label style="float: left; margin-right: 2%" for="registros_ape">Apellidos:</label>
                    <input style="width: 80%; float: left" type="text" class="form-control" id="registros_ape" name="registros_ape" readonly>
                </div>
                <br><br>
                <div class="form-group">
                    <label style="float: left; margin-right: 2%" for="registros_telefono">Teléfono:</label>
                    <input style="width: 80%; float: left"  type="text" class="form-control" id="registros_telefono" name="registros_telefono" readonly>
                </div> 
                <br><br>
                <div class="form-group">
                    <label style="float: left; margin-right: 2%" for="registros_email">Correo:</label>
                    <input style="width: 80%; float: left" type="text" class="form-control" id="registros_email" name="registros_email" readonly>
                </div>  
                <br><br>   
            </form>                
                <div class="form-group inline-group">
                <form action="modificarCliente.php" method=post onsubmit="modificar()" style="float: left; padding-right: 20px;">
                    <input type="submit" class="btn " value="Guardar cambios" id="btnModificar" disabled>
                    <input id="modificarID" name="modificarID" type=hidden>             
                </form> 
                <form action="eliminarCliente.php" method=post onsubmit="eliminar()" >
                    <input id="eliminarID" name="eliminarID" type=hidden>   
                    <input type="submit" class="btn " value="Eliminar" id="btnEliminar" disabled>
                </form>
                </div>
        </div>            
    </body>        
</html>

<?php
//Variables de conexion
    $server = "localhost";
    $user = "root";
    $password = "";
    $db = "estacionamiento";
    $conn = new mysqli($server, $user, $password, $db);
    //Si falla la conexion se lanza el error
    if($conn->connect_error){
        die("Falló la conexión: ".$conn->connect_error);
    }else{//Si se conecta todo bien se procese
        $clientes = array();
        $vehiculos = array();
        //Se recupera el tipo de usuario logueado
        $tipoUsuario = "'".$_SESSION["tipoUsuario"]."'";
        $sql_sentence = "SELECT * FROM usuarios";
        $query = $conn->query($sql_sentence);
        if($query){
            if($query->num_rows > 0){
                //Se recorren los datos recuperados de la consulta y se almacenan en el arreglo $clientes
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


    <script>
    //Se manejan variables que tienen el control de que usuario se ha buscado
        var seleccionado = "0";
        var inputName = document.getElementById("registros_nombre");
        var arregloClientes = new Array();
        var arregloClientesPHP = [<?php echo implode(",",$clientes);?>];
        rellenaAutocompletar();    
        cargaAutocompletar();
        //Se rellena el arreglo arregloClientes que se ha de usar para mostrar el autocompletar, se rellena porque se quiere buscar por nombre
        //y apellido
        function rellenaAutocompletar(){
            for(i=0; i<arregloClientesPHP.length; i+=5){
                arregloClientes.push(arregloClientesPHP[i+1]+" "+arregloClientesPHP[i+2]);
            }
        }
//Esta funcion es la que agrega el autocompletar en el input de Nombre
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
            rellenaCampos(inputName.value);
            hacerEditables();
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
        rellenaCampos(inputName.value);
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
/*execute a function when someone clicks in the document:*/
document.addEventListener("click", function (e) {
    closeAllLists(e.target);
});
}
//Esta funcion habilita los campos una vez que ya se selecciono a un usuario
function hacerEditables(){
  $("#registros_ape").removeAttr("readonly", false);
  $("#registros_telefono").removeAttr("readonly", false);
  $("#registros_email").removeAttr("readonly", false);
  $("#btnEliminar").removeAttr("disabled", false);
  $("#btnModificar").removeAttr("disabled", false);
}
//Esta funcion coloca los datos del usuario en cada campo (input) del formulario cuando ya se ha seleccionado a un usuario
function rellenaCampos(entrada){    
    for(i=0; i<arregloClientesPHP.length; i+=5){
        if(entrada == arregloClientesPHP[i+1]+" "+arregloClientesPHP[i+2]){
            seleccionado = arregloClientesPHP[i];
            document.getElementById("registros_nombre").value =  arregloClientesPHP[i+1];
            document.getElementById("registros_ape").value = arregloClientesPHP[i+2];
            document.getElementById("registros_telefono").value = arregloClientesPHP[i+3];
            document.getElementById("registros_email").value = arregloClientesPHP[i+4];
            break;
        }
    }
}
//Se coloca el valor del ID del usuario a eliminar en el input eliminarID para que se envie con el formulario al archivo eliminarCliente.php
function eliminar(){       
        document.getElementById("eliminarID").value = seleccionado;
}
//Se recorre el arreglo con los datos de los usuarios, una vez que se encuentra al usuario seleccionado se rellenan los campos de los
//input de nombre, apellidos, etc. para que sean enviados junto con el formulario al archivo modificarCliente.php
function modificar(){
    for(i=0; i<arregloClientesPHP.length; i+=5){
        if(seleccionado == arregloClientesPHP[i]){
            arregloClientesPHP[i+1] = document.getElementById("registros_nombre").value;
            arregloClientesPHP[i+2] = document.getElementById("registros_ape").value;
            arregloClientesPHP[i+3] = document.getElementById("registros_telefono").value;
            arregloClientesPHP[i+4] = document.getElementById("registros_email").value;
            break;
        }
    }
    document.getElementById("modificarID").value = arregloClientesPHP.toString();
}
</script>
<script>
//Segun el tipo de usuario se habilitarán u ocultaran las opciones de la barra de navegacion
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
</script>