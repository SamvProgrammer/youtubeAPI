<!doctype <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Pagina principal</title>

    <style>
       #seccionMapas {
        height: 400px;
        width: 100%;
       }
    </style>
</head>
<body>
   <header>
       <h1 class="text-center alert alert-success">Pagina principal proyecto</h1>
   </header>
    <div class="container">
        <div class="row">
           <div class="col">
           <label for="txt1">Vídeo a buscar</label>
             <input id="txt1" type="text" class="form-control">
           </div>
           <div class="col">
           <label for="txt2">Cantidad a visualizar</label>
             <input id="txt2" type="number" class="form-control">
           </div>
           <div class="col">
              <button style="margin-top: 9%" class="btn btn-default" onclick="buscar();">
                Buscar vídeos..
              </button>
           </div>
        </div>
        <div class="row" >
          <div class="col" id="seccionVideos">
               
          </div>
        </div>
        <div class="row" >
        <div class="col">
        <label for="" >Seccion de mapa</label>
        <div id="seccionMapas"></div>
        </div>
        </div>
    </div>
    <footer>
       <h4 class="text-center alert alert-info">Tecnologías emergentes</h4>
    </footer>

    


<!-- Modal -->
<div class="modal fade" id="modalPrincipal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      
      <div class="modal-body">
        <div id="contenidoModal"></div>
      </div>
     
    </div>
  </div>
</div>
   

   <script>
   
    var buscar = function(){
       var txt1 = document.getElementById("txt1");
       var txt2 = document.getElementById("txt2");

       if(txt1.value == ""){
         alert("Ingresar texto de busqueda de vídeo..");
         return;
       }

       
       if(txt2.value == ""){
         alert("Poner cantidad de vídeos a visualizar..");
         return;
       }

       if(txt2.value <= 0){
        alert("La cantidad de vídeos no puede ser menor o igual a 0");
         return;
       }
       
       var url = "https://www.googleapis.com/youtube/v3/search?part=snippet";
       var q = "&q="+txt1.value;
       var limiteVideos = "&maxResults="+txt2.value;
       var apikey = "&key=AIzaSyAUqs9SGGJZC74LiQ44fgOZQR8VMdsFjf4";

       var urlFinal = url+q+limiteVideos+apikey; //Url armada para busaueda de videos...

       var token = localStorage["accessToken"];  //Obtension del token(usuario y contraseña encriptados) ya obtenido 
       var tipoToken = localStorage["tipoToken"];

       var xhr = new XMLHttpRequest();
       
       xhr.open("GET",urlFinal);
       xhr.setRequestHeader("Authorization",tipoToken+" "+token);
       xhr.send();

       xhr.onreadystatechange = function(response){
             if(xhr.readyState == 4){
               var respuesta = xhr.responseText;
               respuesta = JSON.parse(respuesta);
               var arregloVideo = respuesta.items;
               var seccionVideos = document.getElementById("seccionVideos");
               seccionVideos.innerHTML = "";
               for(var x = 0; x < arregloVideo.length; x++){
                   var urlImage = arregloVideo[x]["snippet"]["thumbnails"]["medium"]["url"];
                   var imagen = document.createElement("img");
                   imagen.src = urlImage;
                   imagen.hight = 200;
                   imagen.widht = 200;
                   imagen.addEventListener("click",visualizar(arregloVideo[x]["id"]["videoId"]),false);
                   seccionVideos.appendChild(imagen);                   
               }
               var seccionVideos = document.getElementById("seccionVideos");              
             }
       };

    };


    function initMap() {
        
        map = new google.maps.Map(document.getElementById('seccionMapas'), {
          center: {lat: 19.4284700, lng: -99.1276600},
          zoom: 4
        });
      }
      
      var visualizar = function(uriVideo){
         return function(){
            //Otra peticion para localizar en la región el vídeos...
            
            var token = localStorage["accessToken"];  //Obtension del token(usuario y contraseña encriptados) ya obtenido 
            var tipoToken = localStorage["tipoToken"];

            var urlVideo = "https://www.googleapis.com/youtube/v3/videos?part=recordingDetails&id="+uriVideo+"&key=AIzaSyAUqs9SGGJZC74LiQ44fgOZQR8VMdsFjf4";

            var xhr = new XMLHttpRequest();
            xhr.open("GET",urlVideo);
            xhr.setRequestHeader("Authorization",tipoToken+" "+token);
            xhr.send();
            
            xhr.onreadystatechange = function(response){
                if(xhr.readyState == 4){
                    var respuesta = xhr.responseText;
                    respuesta = JSON.parse(respuesta);
                    respuesta = respuesta.items;
                    respuesta = respuesta[0];
                    var videoaux = "'https://www.youtube.com/embed/"+uriVideo+"'";
                    var aux = "<iframe  width='200' height='150'  src="+videoaux+"></iframe>";
                    try {
                        var localizacion = respuesta.recordingDetails.location;   
                        console.log(localizacion);
                        iniciarMapa(aux,localizacion);
}
catch(err) {
    console.log(err);
    $('#modalPrincipal').modal('show')
    var temporal = document.getElementById("contenidoModal");
    temporal.innerHTML = aux;
}
                   
             }
            }
            
            var videoaux = "'https://www.youtube.com/embed/"+uriVideo+"'";
            var  aux = "<iframe  width='200' height='150'  src="+videoaux+"></iframe>";
            //iniciarMapa(aux);
         }
      }
      var iniciarMapa= function(objeto,localizacion){
        var uluru = {lat:localizacion.latitude,lng:localizacion.longitude}
        map = new google.maps.Map(document.getElementById('seccionMapas'), {
          center: uluru,
          zoom: 8
        });

      var marker = new google.maps.Marker({
        position: uluru,
        map: map,
        draggable: true,   
        animation: google.maps.Animation.DROP,
      });
      marker.addListener('click', toggleBounce);


    var contentString = objeto;
  
  var infowindow = new google.maps.InfoWindow({
  content: contentString
   });
   infowindow.open(map, marker);

     
       function toggleBounce() {
        

      if (marker.getAnimation() !== null) {
        marker.setAnimation(null);
      } else {
        marker.setAnimation(google.maps.Animation.BOUNCE);
      }
    }
      }
   </script>
   <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUqs9SGGJZC74LiQ44fgOZQR8VMdsFjf4&callback=initMap">
    </script>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>