<!--Aqui se verifica si el tipo de usuario es admin, valet, cajero o conductor, si no, entonces no se ha iniciado sesión 
    y se retorna al index.php-->
<?php    
    session_start();    
    if($_SESSION["tipoUsuario"] != "admin" && $_SESSION["tipoUsuario"] != "valet" && $_SESSION["tipoUsuario"] != "cajero" && $_SESSION["tipoUsuario"] != "conductor"){ 
        echo "<script>location.href='index.php';</script>";
    }
?>
<!--Se usa JQuery y Bootstrap-->
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>

<!--Este es el HTML de la barra de navegacion, esta barra tiene todas las opciones del sistema, que se habilitan/ocultan segun el usuario,
eso se hace en cada archivo que importe esta barra-->
<nav class="navbar navbar-default navbar-doublerow navbar-trans navbar-fixed-top"> 		<!-- Master nav -->
            <nav class="navbar navbar-down">
                <div class="container">
                        <div class="flex-container">
                            <ul class="nav navbar-nav flex-item">
                                <li id="menu_inicio" class="item"><a  class="dropdown-toggle" href="vista_cajones.php"><b>INICIO</b></a></li>
                                <li id="menu_grafica" class="item"><a  class="dropdown-toggle" href="ver_grafica.php">GRAFICA</a></li>
                                
                                <li id="menu_usuarios" class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">USUARIOS  <span class="badge badge-light">🢃</span></a>
                                    <ul class="dropdown-menu">
                                            <li><a href="nuevo_usuario.php">Registrar</a></li>
                                            <li role="separator" class="divider"></li>
                                            <li><a href="ver_usuarios.php">Visualizar</a></li>
                                          </ul>
                                </li>
                                <li id="menu_vehiculos" class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">VEHICULOS  <span class="badge badge-light">🢃</span></a>
                                    <ul class="dropdown-menu">
                                            <li><a href="nuevo_auto.php">Registrar</a></li>
                                            <li role="separator" class="divider"></li>
                                            <li><a href="ver_autos.php">Visualizar</a></li>
                                    </ul>
                                </li>
                                <li id="menu_resguardos" class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">RESGUARDOS  <span class="badge badge-light">🢃</span></a>
                                    <ul class="dropdown-menu">
                                            <li><a href="nuevo_resguardo.php">Registrar</a></li>
                                            <li role="separator" class="divider"></li>
                                            <li><a href="ver_resguardos.php">Visualizar</a></li>
                                            <li role="separator" class="divider"></li>
                                            <li><a href="corte_de_caja.php">Corte de caja</a></li>
                                          </ul>
                                </li>
                                <li id="menu_datosConductor" class="item"><a  class="dropdown-toggle" href="datosConductor.php">CUENTA</a></li>
                                <li id="menu_reservaciones" class="item"><a  class="dropdown-toggle" href="reservaciones.php">RESERVACIONES</a></li>
                                <li class="item"><a  class="dropdown-toggle" href="cerrarSesion.php">CERRAR SESIÓN</a></li>
                            </ul>
                            <!--Aqui se muestra el conteo de ocupación de cajones-->
                            <ul id="menu_ocupacion" class="nav navbar-nav flex-item hidden-xs pull-right">
                                <li><a id="ocupacion" class=""></a></li>
                            </ul>
                        </div>
                    </div>
            </nav>
        </nav>