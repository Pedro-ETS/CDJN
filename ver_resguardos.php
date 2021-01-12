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
            <h4>Buscar resguardo</h4>        
            <div class="form-group form-inline">
                <p for="tipo" style="padding-right:20px;">Estado:</p>
                <select class="form-control" id="tipo" onchange="cargandoParkings()">
                    <option>--Seleccionar--</option>
                    <option>Activos</option>
                    <option>Pagados</option>
                </select>
            </div>
            <div class="form-group autocomplete">
                <p for="propietario">Cliente:</p>
                <input style="width: 80%" type="text" class="form-control" id="propietario" placeholder="Nombre del cliente..." name="propietario" readonly>
            </div>
            <table class="table table-hover" id="tablaRegistros" style="margin-top: 30px">
                <thead id="parkingsColumnas">
                  <tr>
                    <th>Cajon</th>
                    <th>Vehiculo</th>
                    <th>Cliente</th>
                    <th>Fecha</th>                
                    <th>Hora entrada</th>
                    <th>Hora salida</th>
                    <th>Estado</th>
                    <th>Seleccionar</th>
                  </tr>
                </thead>
                <tbody id="parkingsFilas">
                </tbody>
            </table>
            <div class="text-right">                     
                <input style="display: right" type="button" class="btn" value="Marcar como pagado" id="btnPagar" onclick="pagar()" disabled>
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
            $resguardos = array();
            $clientes = array();
            $tipoUsuario = "'".$_SESSION["tipoUsuario"]."'";
            $sql_sentence = "SELECT * FROM resguardos";
            $query = $conn->query($sql_sentence);
            if($query){
                if($query->num_rows > 0){
                    while($row = $query->fetch_assoc()){
                        array_push($resguardos, "'".$row["id_cajon"]."'");
                        array_push($resguardos, "'".$row["id_vehiculo"]."'");
                        array_push($resguardos, "'".$row["hora_llegada"]."'");
                        array_push($resguardos, "'".$row["hora_salida"]."'");
                        array_push($resguardos, "'".$row["pago"]."'");
                        array_push($resguardos, "'".$row["fecha"]."'");
                        array_push($resguardos, "'".$row["estatus"]."'");
                    }
                    $sql_sentence = "SELECT * FROM usuarios where tipo = 'conductor'";
                    $query = $conn->query($sql_sentence);
                    if($query){
                        if($query->num_rows > 0){
                            while($row = $query->fetch_assoc()){
                                array_push($clientes, "'".$row["id"]."'");
                                array_push($clientes, "'".$row["nombre"]."'");
                                array_push($clientes, "'".$row["apellidos"]."'");
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
                                        array_push($vehiculos, "'".$row["color"]."'");
                                        array_push($vehiculos, "'".$row["cliente"]."'");
                                        array_push($vehiculos, "'".$row["tamanio"]."'");
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

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="pdfLibrary/dist/jspdf.min.js"></script>

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
        var seleccionTipo = document.getElementById("tipo").value;
        var inputName = document.getElementById("propietario");            
        var arregloClientesPHP = [<?php echo implode(",",$clientes);?>];     
        var arregloClientes = new Array();
        var arregloVehiculos = [<?php echo implode(",",$vehiculos);?>];
        var arregloResguardosPHP = [<?php echo implode(",",$resguardos);?>];

        for(i=0; i<arregloClientesPHP.length; i+=3){
            arregloClientes.push(arregloClientesPHP[i+1]+" "+arregloClientesPHP[i+2]);
        }

function cargandoParkings(){
    seleccionTipo = document.getElementById("tipo").value;
    $("#propietario").removeAttr("readonly", false);
            
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
            for(i=0; i<arregloClientesPHP.length; i+=3){
                if(arregloClientesPHP[i+1]+" "+arregloClientesPHP[i+2] == inputName.value){
                    listarAutos(arregloClientesPHP[i], inputName.value);
                    closeAllLists();
                    break;
                }
            }
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
        for(i=0; i<arregloClientesPHP.length; i+=3){
            if(arregloClientesPHP[i+1]+" "+arregloClientesPHP[i+2] == inputName.value){
                listarAutos(arregloClientesPHP[i], inputName.value);
                closeAllLists();
                break;
            }
        }
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

function listarAutos(idcliente, cliente){ 
    tablaC = document.getElementById("parkingsColumnas"); 
    tablaH = document.getElementById("parkingsFilas");    
    tablaH.innerHTML = "";
    if(seleccionTipo == "Activos"){   
        tablaC.rows[0].cells[7].innerHTML = "Seleccionar";
        $("#btnPagar").removeAttr("disabled", false);

        for(i=0; i<arregloResguardosPHP.length; i+=7){
            if((arregloResguardosPHP[i+6] == "Activo")){

                for(j=0; j<arregloVehiculos.length; j+=6){
                    if((arregloResguardosPHP[i+1] == arregloVehiculos[j]) && (idcliente == arregloVehiculos[j+4])){
                        //Rellenar tabla
                        filaH = tablaH.insertRow(tablaH.rows.length);
                        colUnoH = filaH.insertCell(0);
                        colDosH = filaH.insertCell(1);
                        colTresH = filaH.insertCell(2);
                        colCuatroH = filaH.insertCell(3);
                        colCincoH = filaH.insertCell(4);
                        colSeisH = filaH.insertCell(5);
                        colSieteH = filaH.insertCell(6);
                        colOchoH = filaH.insertCell(7);
                        colUnoH.innerHTML = arregloResguardosPHP[i];
                        colDosH.innerHTML = arregloVehiculos[j+1]+", "+arregloVehiculos[j+2]+", "+arregloVehiculos[j+3];
                        colTresH.innerHTML = cliente;
                        colCuatroH.innerHTML = arregloResguardosPHP[i+5];
                        colCincoH.innerHTML = arregloResguardosPHP[i+2];
                        colSeisH.innerHTML = "No definida";
                        colSieteH.innerHTML = "En resguardo";
                        colOchoH.innerHTML = "<input class='form-check-input' type='checkbox'>";
                        break;
                    }
                }
            }
        }
    }else if(seleccionTipo == "Pagados"){   
        $("#btnPagar").attr("disabled", true);             
        tablaC.rows[0].cells[7].innerHTML = "Cobro";
        for(i=0; i<arregloResguardosPHP.length; i+=7){
            if((arregloResguardosPHP[i+6] != "Activo")){
                for(j=0; j<arregloVehiculos.length; j+=6){
                    if((arregloResguardosPHP[i+1] == arregloVehiculos[j]) && (idcliente == arregloVehiculos[j+4])){
                        //Rellenar tabla
                        filaH = tablaH.insertRow(tablaH.rows.length);
                        colUnoH = filaH.insertCell(0);
                        colDosH = filaH.insertCell(1);
                        colTresH = filaH.insertCell(2);
                        colCuatroH = filaH.insertCell(3);
                        colCincoH = filaH.insertCell(4);
                        colSeisH = filaH.insertCell(5);
                        colSieteH = filaH.insertCell(6);
                        colOchoH = filaH.insertCell(7);
                        colUnoH.innerHTML = arregloResguardosPHP[i];
                        colDosH.innerHTML = arregloVehiculos[j+1]+", "+arregloVehiculos[j+2]+", "+arregloVehiculos[j+3];
                        colTresH.innerHTML = cliente;
                        colCuatroH.innerHTML = arregloResguardosPHP[i+5];
                        colCincoH.innerHTML = arregloResguardosPHP[i+2];
                        colSeisH.innerHTML = arregloResguardosPHP[i+3];
                        colSieteH.innerHTML = "Pagado";
                        colOchoH.innerHTML = arregloResguardosPHP[i+4];
                        break;
                    }
                }
            }
        }                
    }
}

function pagar(){
    tablaH = document.getElementById("parkingsFilas");   
    chequeados = 0; costoHra = 12;
    carroQueSale = "";
    var nombreCliente;
    var nombreVehiculo;
    for(i=0; i<tablaH.rows.length; i++){
        if(tablaH.rows[i].cells[7].children[0].checked == true){
            for(j=0; j<arregloVehiculos.length; j+=6){
                if(arregloVehiculos[j+1]+", "+arregloVehiculos[j+2]+", "+arregloVehiculos[j+3] == (tablaH.rows[i].cells[1].innerHTML)){                    
                    carroQueSale = arregloVehiculos[j];
                    nombreVehiculo = arregloVehiculos[j+1]+", "+arregloVehiculos[j+2]+", "+arregloVehiculos[j+3];
                    if(arregloVehiculos[j+5] == "Grande"){
                        costoHra = 18;
                    }
                    for(m=0; m<arregloClientesPHP.length; m+=3){
                        if(arregloVehiculos[j+4] == arregloClientesPHP[m]){
                            nombreCliente = arregloClientesPHP[m+1]+" "+arregloClientesPHP[m+2];
                        }
                    }
                }
            }
            chequeados++;
        }
    }   

    if(chequeados == 1){
        for(i=0; i<arregloResguardosPHP.length; i+=7){
            if((arregloResguardosPHP[i+6] == "Activo")&&(arregloResguardosPHP[i+1]==carroQueSale)){    
                datos = new Array();                   
                var d = new Date();
                hraSalida = ('0'+d.getHours()).slice(-2)+":"+('0'+d.getMinutes()).slice(-2)+":"+('0'+d.getSeconds()).slice(-2);
                fechaEntrada = new Date("February 13, 2019 "+arregloResguardosPHP[i+2]);
                fechaSalida = new Date("February 13, 2019 "+hraSalida);
                var c;
                if(fechaSalida > fechaEntrada){
                    c = Math.abs((((fechaSalida-fechaEntrada)/1000)/60)/60);
                }else{
                    fechaAux1 = new Date("February 13, 2019 23:59:59");
                    fechaAux2 = new Date("February 13, 2019 00:00:00");
                    c = Math.abs(((((fechaSalida - fechaAux2)+(fechaAux1 - fechaEntrada))/1000)/60)/60);
                }
                hrs = Math.round(c*100) / 100;
                pagar = Math.round((hrs*costoHra)*100) / 100; 
                datos.push(arregloResguardosPHP[i]);
                datos.push(arregloResguardosPHP[i+1]);
                datos.push(arregloResguardosPHP[i+2]);
                datos.push(hraSalida);
                datos.push(pagar);
                datos.push(arregloResguardosPHP[i+5]);
                datos.push("Pagado");
                
                d = new Date();
                hoy = ('0'+d.getDate()).slice(-2)+"/"+('0'+(d.getMonth()+1)).slice(-2)+"/"+d.getFullYear();

                $.ajax({
                    data: {datosActualizar: datos.toString(), total: pagar},
                    url: "pagar_resguardo.php",
                    type: "POST",            
                    success: function(response){                  
                        if(response.toString() == "éxito"){
                            var doc = new jsPDF();        
                            doc.setFontSize(18);
                            textWidth = doc.getStringUnitWidth("Estacionamiento") * doc.internal.getFontSize() / doc.internal.scaleFactor;
                            textOffset = (doc.internal.pageSize.width - textWidth) / 2;
                            doc.text(textOffset, 18, "Estacionamiento");

                            doc.setFontSize(14);
                            textWidth = doc.getStringUnitWidth("Ticket de salida") * doc.internal.getFontSize() / doc.internal.scaleFactor;
                            textOffset = (doc.internal.pageSize.width - textWidth) / 2;
                            doc.text(textOffset, 25, "Ticket de salida");


                            doc.setFontSize(12);
                            doc.text(30, 37, "Fecha: "+hoy);
                            doc.text(140, 37, "Hora: "+hraSalida);

                            doc.setFontSize(10);
                            doc.text(30, 45, "Cajón: "+arregloResguardosPHP[i]);
                            doc.text(30, 50, "Nombre del usuario: "+nombreCliente);
                            doc.text(30, 55, "Vehiculo: "+nombreVehiculo);
                            doc.text(30, 60, "Total: "+pagar);
                            doc.text(30, 70, "***GRACIAS POR SU PREFERENCIA***");
                            

                            doc.save("boleto_salida_"+hraSalida+".pdf");
                            alert('Resguardo registrado como pagado');
                            location.href='ver_resguardos.php';
                            location.href.reload();
                        }
                        else {                
                            alert('Seleccione sólo un registro activo');
                        }
                    }
                }).fail(function(e, test, error){
                    alert(error.toString());
                });

                break;
            }
        }
    }else{        
        $.ajax({
            data: {datosActualizar: "", total: ""},
            url: "pagar_resguardo.php",
            type: "POST",            
            success: function(response){                        
                if(response.toString() == "éxito"){
                }else {                
                    alert('Seleccione sólo un registro activo');
                }
            }
        }).fail(function(e, test, error){
            alert(error.toString());
        });
    }
}
    </script>
