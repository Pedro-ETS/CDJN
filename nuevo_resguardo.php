<?php        
//Se importa la barra de navegacion
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
    <!--Formulario para el registro de un nuevo resguardo-->
        <form autocomplete="off" action="registroResguardos.php" method=post style="margin-top:40px; padding-bottom:30px; width: 40%; margin-left: 30%; margin-right: 30%; margin-bottom: 5%; ">                        
             
            <input id="fecha" name="fecha" type=hidden> 
            <input id="horaEntrada" name="horaEntrada" type=hidden> 
            <center>
                <h4><b>Comprobante de estacionamiento</b></h4>
                <p id="dd">Fecha: <br>
                   Hora: </p>
            </center>
            
            <label for="tipo" style="margin-left:10%; width: 20%; float: left">Tipo:</label>
            <select style="width: 60%" class="form-control" id="tipo" onchange="cambiarTipoRecibo()">
                <option value="Vehiculo registrado">Vehiculo registrado</option>
                <option value="Vehiculo nuevo">Vehiculo nuevo</option>
            </select>
            <br>

            <div class="form-group form-inline autocomplete">
                <label for="cliente" style="margin-left: 10%; width: 20%;">Cliente:</label>         
                <input id="clienteID" name="clienteID" type=hidden>  
                <input type="text" class="form-control" id="cliente" name="cliente" placeholder="Nombre del cliente..." style="width:60%">
            </div>
            <div id="camposAutoReg" class="form-group form-inline autocomplete">              
                <input id="autoID" name="autoID" type=hidden>   
                <label for="auto" style="margin-top: 10px; margin-left: 10%; width: 20%;">Vehiculo:</label>
                <input type="text" class="form-control" id="auto" name="auto" placeholder="Vehiculo del cliente..." style="width:60%" readonly>
            </div>
            
            <div id="camposAutoNuevo" style="display: none">
              <div class="form-inline form-group">
                  <label for="matricula" style="margin-left: 10%; width: 20%;">Placas:</label>
                  <input type="text" class="form-control" id="matricula" placeholder="Matricula..." name="matricula" style="width: 60%">                
              </div>
              
              <div class="form-inline form-group">              
                  <label for="marca" style="margin-left: 10%; width: 20%;">Marca:</label>
                  <input type="text" class="form-control" id="marca" placeholder="Marca..." name="marca" style="width: 60%">
              </div>
              <div class="form-inline form-group">
                  <label for="modelo" style="margin-left: 10%; width: 20%;">Modelo:</label>
                  <input type="text" class="form-control" id="modelo" placeholder="Modelo..." name="modelo" style="width: 60%">     
              </div>
              
              <div class="form-inline form-group">              
                  <label for="color" style="margin-left: 10%; width: 20%;">Color:</label>
                  <input type="text" class="form-control" id="color" placeholder="Color..." name="color" style="width: 60%">
              </div>
              <div class="form-inline form-group">              
                  <label for="tamanio" style="margin-left: 10%; width: 20%;">Tamaño:</label>
                  <select id="tamanio" name="tamanio" class="form-control" style="width: 60%">
                      <option value="Chico">Chico</option>
                      <option value="Grande">Grande</option>
                  </select>
              </div>
            </div>

            <center>
              <br>
            <input type="button" class="btn" value="Registrar" id="btnGuardar" onclick="registrarResguardo()" disabled>
            </center>
        </form>
    </body>


<?php
//Variables de conexion
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
          //Se recuperan datos de los usuarios tipo conductor
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
                  //Se recuperan datos de los vehiculos
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




    <!--Se importa la libreria para generar el PDF del boleto de entrada al estacionamiento-->
<script src="pdfLibrary/dist/jspdf.min.js"></script>
<script type="text/javascript">
//Se recupera el tipo de usuario que esta logueado en el sistema
//Segun el tipo de usuario se mostraran/ocultaran las opciones de la barra de navegacion
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
    //Se recupera la fecha y hora actual y se colocan los datos en los inputs de fecha y horaEntrada
        var d = new Date();
        document.getElementById("dd").innerHTML = "Fecha: "+('0'+d.getDate()).slice(-2)+" - "+('0'+(d.getMonth()+1)).slice(-2)+" - "+d.getFullYear()+
            "<br>Hora: "+('0'+d.getHours()).slice(-2)+":"+('0'+d.getMinutes()).slice(-2);
        document.getElementById("fecha").value = ('0'+d.getDate()).slice(-2)+"/"+('0'+(d.getMonth()+1)).slice(-2)+"/"+d.getFullYear();
        document.getElementById("horaEntrada").value = ('0'+d.getHours()).slice(-2)+":"+('0'+d.getMinutes()).slice(-2)+":"+('0'+d.getSeconds()).slice(-2);
        var inputName = document.getElementById("cliente");        
        var inputCar = document.getElementById("auto");
        var arregloClientes = new Array();
        var arregloClientesPHP = [<?php echo implode(",",$clientes);?>];
        var arregloAutos = new Array();
        var arregloAutosPHP = [<?php echo implode(",",$vehiculos);?>];
        rellenaAutocompletar();    
        cargaAutocompletar();
//Se rellena el arregloClientes para poder mostrar el autocompletar en el input de nombre del cliente
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
        $("#auto").removeAttr("readonly", false);
        for(i=0; i<arregloClientesPHP.length; i+=5){
                if(inputName.value == arregloClientesPHP[i+1]+" "+arregloClientesPHP[i+2]){
                  document.getElementById("clienteID").value = arregloClientesPHP[i];
                  break;
                }
            }
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
        $("#auto").removeAttr("readonly", false);
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
//Esta funcion carga el segundo autocompletar para listar el vehiculo segun su marca modelo placas
function listarAutos(cliente){
  var arregloAutos = new Array();
  $("#marca").removeAttr("readonly", false);
  var seleccionado = "";
    for(i=0; i<arregloClientesPHP.length; i+=5){
        if(cliente == arregloClientesPHP[i+1]+" "+arregloClientesPHP[i+2]){
            for(j=0; j<arregloAutosPHP.length; j+=7){
                if(arregloClientesPHP[i] == arregloAutosPHP[j+6]){
                    arregloAutos.push(arregloAutosPHP[j+2]+" "+arregloAutosPHP[j+3]+" "+arregloAutosPHP[j+1]);
                }
            }
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

//Cuando ya se ha seleccionado un usuario y un vehiculo se habilita el boton de guardar y se guarda el ID del auto en el input autoID
function hacerEditables(){
  entrada = document.getElementById("auto").value;
  for(i=0; i<arregloAutosPHP.length; i+=7){
        if(entrada == arregloAutosPHP[i+2]+" "+arregloAutosPHP[i+3]+" "+arregloAutosPHP[i+1]){
            document.getElementById("autoID").value = arregloAutosPHP[i];
            break;
        }
    }
  $("#btnGuardar").removeAttr("disabled", false);
}

//Cuando se cambia la opcion de resguardo de un vehiculo ya registrado por la opcion de resguardar un vehiculo nuevo entonces se
//habilitan los inputs para recibir los datos del nuevo vehiculo a registrar, aqui tambn se debe seleccionar a un usuario para que
//quede registrado el nuevo vehiculo a su nombre
function cambiarTipoRecibo(){
  if(document.getElementById("tipo").value != "Vehiculo registrado"){
    document.getElementById("camposAutoNuevo").style.display = "block";
    document.getElementById("camposAutoReg").style.display = "none";
    document.getElementById("auto").value = "";
    document.getElementById("autoID").value = "";
    document.getElementById("cliente").value = "";
    document.getElementById("clienteID").value = "";
    $("#auto").attr("readonly", true);
    $("#btnGuardar").removeAttr("disabled", false);
  }else{
    document.getElementById("camposAutoNuevo").style.display = "none";
    document.getElementById("camposAutoReg").style.display = "block";
    $("#btnGuardar").attr("disabled", true);
  }
}

//Esta funcion recupera todos los datos a registrar en el resguardo
  function registrarResguardo(){
    clienteID = document.getElementById("clienteID").value;
    autoID = document.getElementById("autoID").value;
    fecha = document.getElementById("fecha").value;
    horaEntrada = document.getElementById("horaEntrada").value;
    
    matricula = document.getElementById("matricula").value;
    marca = document.getElementById("marca").value;
    modelo = document.getElementById("modelo").value;
    color = document.getElementById("color").value;
    tamanio = document.getElementById("tamanio").value;
    //Recupera la fecha para ponerla en el boleto de entrada
    d = new Date();
    hraSalida = ('0'+d.getHours()).slice(-2)+":"+('0'+d.getMinutes()).slice(-2)+":"+('0'+d.getSeconds()).slice(-2);
    hoy = ('0'+d.getDate()).slice(-2)+"/"+('0'+(d.getMonth()+1)).slice(-2)+"/"+d.getFullYear();
        $.ajax({//Llama al archivo registroResguardos.php para realizar el registro en la BD
            data: {clienteID: clienteID, autoID: autoID, fecha: fecha, horaEntrada: horaEntrada, matricula:matricula,
              marca:marca, modelo:modelo, color:color, tamanio:tamanio},
            url: "registroResguardos.php",
            type: "POST",            
            success: function(response){           
              //Si se responde con que todo fue bien se procede a crear el pdf con la libreria      
                if(response.toString() == "crearPDF"){
                  //Se crea un nuevo jsPDF, es el objeto de la libreria
                    var doc = new jsPDF();     
                    //Se van insertando los textos en diferentes posiciones y se van colocando los datos basicos de entrada del vehiculo   
                    doc.setFontSize(18);
                    textWidth = doc.getStringUnitWidth("Estacionamiento") * doc.internal.getFontSize() / doc.internal.scaleFactor;
                    textOffset = (doc.internal.pageSize.width - textWidth) / 2;
                    doc.text(textOffset, 18, "Estacionamiento");

                    doc.setFontSize(14);
                    textWidth = doc.getStringUnitWidth("Boleto de entrada") * doc.internal.getFontSize() / doc.internal.scaleFactor;
                    textOffset = (doc.internal.pageSize.width - textWidth) / 2;
                    doc.text(textOffset, 25, "Boleto de entrada");


                    doc.setFontSize(12);
                    doc.text(30, 37, "Fecha: "+hoy);
                    doc.text(140, 37, "Hora: "+hraSalida);

                    doc.setFontSize(10);
                    doc.text(30, 50, "Nombre del usuario: "+document.getElementById("cliente").value);
                    doc.text(30, 55, "Vehiculo: "+document.getElementById("auto").value);
                    doc.text(30, 65, "****************Guarde su boleto de entrada***********************");
                    
                    //Ya creado todo se guarda en el equipo el pdf y se manda el msg de que ya se ha resguardado el auto
                    doc.save("boleto_entrada_"+hraSalida+".pdf");
                    alert('Gracias por su confianza, su auto ya esta en resguardo'); 
                    location.href='nuevo_resguardo.php';
                }//Si se retorna una respuesta diferente quiere decir que hubo un problema, segun la respuesta se muestra el msg de error
                else if(response.toString() == "noEspacio"){                
                  alert('Lo sentimos, no hay espacios disponibles');
                }else if(response.toString() == "autoRegistrado"){                
                  alert('El auto ya se encuentra en resguardo');
                }else if(response.toString() == "vacio"){                
                  alert('Error, uno o mas campos estan vacios');
                }
            }
        }).fail(function(e, test, error){
            alert(error.toString());
        });
  }

</script>
</html>