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
            <h4 style="margin-left: -20px">Ingresa los siguientes datos:</h4>
            <form id="areaRegCliente" autocomplete="off" method="post" action="registroUsuarios.php">
                <div class="form-group">
                    <input type="text" class="form-control" id="id" placeholder="Id del usuario..." name="id">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="nombre" placeholder="Nombre del usuario..." name="nombre">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="apellidos" placeholder="Primer apellido..." name="apellidos">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="apeMaterno" placeholder="Segundo apellido..." name="apeMaterno">
                </div> 
                <div class="form-group">
                    <input type="text" class="form-control" id="telefono" placeholder="Teléfono..." name="telefono">
                </div> 
                <div class="form-group">
                    <input type="text" class="form-control" id="email" placeholder="Correo electrónico..." name="email">
                </div> 
                <div class="form-group">
                    <input type="password" class="form-control" id="contraseña" placeholder="Contraseña..." name="contraseña">
                </div>
                <div class="form-inline form-group">              
                    <label for="tamanio" style="padding-right:20px;">Tipo de usuario:</label>
                    <select id="tipo" name="tipo" class="form-control" onchange="cambioTipoUsuario()">
                        <option value="conductor">Conductor</option>
                        <option value="admin">Administrador</option>
                        <option value="cajero">Cajero</option>
                        <option value="valet">Valet</option>
                    </select>
                </div>
                
                <div id="datosAuto">
                <h4 style="margin-left: -20px; padding-top: 25px">Registra tambien un vehiculo:</h4>
                <div class="form-inline form-group">
                    <input type="text" class="form-control" id="matricula" placeholder="Placas..." name="matricula" style="width: 45%">                
                    <input type="text" class="form-control" id="modelo" placeholder="Modelo..." name="modelo" style="width: 45%; margin-left: 5%">
                </div>
                <div class="form-inline form-group">
                    <input type="text" class="form-control" id="marca" placeholder="Marca..." name="marca" style="width: 45%">                
                    <input type="text" class="form-control" id="color" placeholder="Color..." name="color" style="width: 45%; margin-left: 5%">
                </div>
                <div class="form-inline form-group">              
                    <label for="tamanio" style="padding-right:20px;">Tamaño del vehiculo:</label>
                    <select id="tamanio" name="tamanio" class="form-control">
                        <option value="Chico">Chico</option>
                        <option value="Grande">Grande</option>
                    </select>
                </div>
                </div>
                <div class="text-right" style="clear:left; padding-top:30px">
                <input type="submit" class="btn btn-md " value="Registrar"/>        
                </div>
            </form>
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
        $tipoUsuario = "'".$_SESSION["tipoUsuario"]."'";
    }
?>

    <script src="scriptClientes.js" type="text/javascript">
    </script>
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
    function cambioTipoUsuario(){
        if(document.getElementById("tipo").value == "conductor"){
            document.getElementById("datosAuto").style.display = "block";
        }else{
            document.getElementById("datosAuto").style.display = "none";}
    }
</script>
</html>