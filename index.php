<?php        
    //Se verifica si ya se ha iniciado sesion como admin, valen, etc. Si es asi, entonces se redirige a la vista del estacionamiento
    session_start();
    if($_SESSION["tipoUsuario"] == "admin" || $_SESSION["tipoUsuario"] == "valet" || $_SESSION["tipoUsuario"] == "cajero"){ 
        echo "<script>location.href='vista_cajones.php';</script>";
    }else if($_SESSION["tipoUsuario"] == "conductor"){ //Si se inició como conductor entonces se redirige a los datos del conductor
        echo "<script>location.href='datosConductor.php';</script>";
    }else{//Si no se ha iniciado sesion se limpian las variables de sesion
        $_SESSION["tipoUsuario"] = "";
        $_SESSION["idUsuario"] = ""; 
    }
?>
<!DOCTYPE html>
<html>
    <head>        
        <meta charset="utf-8">
        <title>Inicio de sesión</title>    
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-Compatible" content="ie=edge">
        <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="estilos.css">
    </head>
    <body>
        <center>
        <h2>Inicio de sesión</h2>
        <input id="nombreDeUsuario" name="nombreDeUsuario" type="text" placeholder="Usuario" style="width: 25%;"/>
        <br><br>
        <input id="contraseña" name="contraseña" type="text" placeholder="Contraseña" style="width: 25%;"/>
        <br><br>
        <button type="button" class="btn" onclick="checarDatosUsuario()">Entrar ahora</button>
        </center>
    </body>
</html>

<?php
//Variables de conexion
$server = "localhost";
$user = "root";
$password = "";
$db = "estacionamiento";
$conn = new mysqli($server, $user, $password, $db);
//Si falla la conexion se muestra el error
if($conn->connect_error){
    die("Falló la conexión: ".$conn->connect_error);
}else{//Si se conecta correctamente se prosigue
    //Se consultan los datos de usuarios
    $consulta = $conn->query("SELECT * FROM usuarios");
    if($consulta){
        if($consulta->num_rows > 0){
            $usuarios = array();
            //los datos se leen y pasan al arreglo $usuarios
            while($fila = $consulta->fetch_assoc()){
                array_push($usuarios, "'".$fila["id"]."'");
                array_push($usuarios, "'".$fila["contrasenia"]."'");
                array_push($usuarios, "'".$fila["tipo"]."'");
            }
        }
    }else{
        echo $conn->error;
    }
}
?>

<script>    
//Se recupera el arreglo de php y se pasa a JavaScript
    var usuarios = [<?php echo implode(",",$usuarios);?>];

    //Funcion para validar el inicio de sesion
    function checarDatosUsuario(){
        idUsuario = document.getElementById("nombreDeUsuario").value;
        contraseniaUsuario = document.getElementById("contraseña").value;
        tipoUsuario = "";
        usuarioValido = false;

        for(indice = 0; indice < usuarios.length; indice += 3){
            //Se recorre todo el arreglo y si coinciden el usuariuo y contraseña entonces se valida al usuario
            if(idUsuario == usuarios[indice] && contraseniaUsuario == usuarios[indice+1]){
                tipoUsuario = usuarios[indice+2];
                usuarioValido = true;
                break;
            }
        }

        //Si el usuario es valido se llama a iniciarSesion.php pasando como variables el id y el tipo de usuario
        if(usuarioValido){
            $.ajax({
                data: {id: idUsuario, tipo: tipoUsuario},
                url: "iniciarSesion.php",
                type: "POST",            
                success: function(response){    //Se captura la respuesta de iniciarSesion.php           
                    if(response.toString() == "conductor"){//Si responde con el tipo de usuario conductor entonces se redirige a sus datos
                        location.href = "datosConductor.php";
                    }else{//Si responde con cualquier otro tipo se redirige a los cajones
                        location.href = "vista_cajones.php";
                    }
                }
            }).fail(function(e, t, error){
                alert(error.toString());
            });
        }else{//Si el usuario no es valido se muestra un msg de alerta
            alert("El usuario o contraseña son incorrectos");
        }
    }
    
</script>