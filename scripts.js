$(document).ready(function() {



    window.addEventListener('load', (event) => {

    });



}); 

document.addEventListener("DOMContentLoaded",function(event){
    var base64Location = "";
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
      } else {
        console.log("Geolokáció nem támogatott ebben a böngészőben.");
      }
  
      function showPosition(position) {
          console.log("Latitude: " + btoa(position.coords.latitude));
          console.log("Longitude: " + btoa(position.coords.longitude));

          base64Location = btoa(position.coords.latitude) + "|" + btoa(position.coords.longitude);
      }


    var meta = document.createElement('meta');
    meta.name = "customtarget";
    meta.content = base64Location;
    document.getElementsByTagName('head')[0].appendChild(meta);

});