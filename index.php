<!doctype <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <title>Inicio de sesión</title>
</head>
<body>
   <header>
       <h1 class="text-center  alert alert-success">Proyecto de consumir apis google Elizabeth</h1>
   </header>
    <div class="text-center">
   <?php 
    if(!isset($_GET["code"]))
    { 
        if(isset($_GET["error"]))
        {   
        ?>
    <div class="alert alert-danger" role="alert">Ingreso al sistema incorrecto, favor de logearse nuevamente</div>
    <?php 
    }}
    ?>
            <button class="btn btn-default" onclick="enviarDatos();">Iniciar sesión</button>            
    </div>
    <div>
       <h4 class="text-center">Tecnologías emergentes</h4>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js" integrity="sha384-feJI7QwhOS+hwpX2zkaeJQjeiwlhOP+SdQDqhgvvo1DsjtiSQByFdThsxO669S2D" crossorigin="anonymous"></script>
    <script>
      var enviarDatos = function(){

        //========================INFORMACIÓN SACADA DEL LA DOCUMENTACIÓN AUTH 2.0 DE GOOGLE==========================
        var uriLogearApi = "https://accounts.google.com/o/oauth2/v2/auth?";        
        var clienteId = "client_id=883242617561-hic5mdsfklaqru4dq0b80t937jdoe9e5.apps.googleusercontent.com";
        var responseType = "&response_type=code";
        var scope = "&scope=https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.me https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/youtube https://www.googleapis.com/auth/youtube.force-ssl https://www.googleapis.com/auth/youtube.readonly https://www.googleapis.com/auth/youtube.upload https://www.googleapis.com/auth/youtubepartner https://www.googleapis.com/auth/youtubepartner-channel-audit";
        var redirectUrl = "&redirect_uri=http://localhost/proyecto/index.php";

        var uriCompleta =   uriLogearApi + clienteId + responseType + scope + redirectUrl; 

        //========================================================
        
        document.location.href = uriCompleta;

      };
    </script>
<?php
   if(isset($_GET["code"]))
   {
    $variable = $_GET["code"];
?>
    <script>
        //Canjear el código obtenido de la auntentificación por un access token 
        var codigo = "<?php echo $variable ?>";

        var uriToken = "https://www.googleapis.com/oauth2/v4/token?";
        var code = "code="+codigo;
        var clienteId = "&client_id=883242617561-hic5mdsfklaqru4dq0b80t937jdoe9e5.apps.googleusercontent.com";
        var clienteSecreto = "&client_secret=xgZBHB7EIuxcjOGC46DvUOet";
        var uriRedirect = "&redirect_uri=http://localhost/proyecto/index.php";
        var granType = "&grant_type=authorization_code";

        var uriCompleta = uriToken+code+clienteId+clienteSecreto+uriRedirect+granType;

        var xhr = new XMLHttpRequest();
        xhr.open("POST",uriCompleta);
        xhr.onreadystatechange = function(respuesta){
           if(xhr.readyState == 4){
             var respuesta = xhr.responseText;
             respuesta = JSON.parse(respuesta);
             if(respuesta.access_token != undefined && respuesta.token_type != undefined){
                var accessToken = respuesta.access_token;
                var tipoToken = respuesta.token_type;
                localStorage["accessToken"] = accessToken;
                localStorage["tipoToken"] = tipoToken;
                document.location.href = "/proyecto/principal.php";
             }
           }
        }
        xhr.send();
 </script>

<?php 
   }
?>
</body>
</html>