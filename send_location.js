function envoyerMaPosition(articleId) {
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'save_location.php';

            const inputLat = document.createElement('input');
            inputLat.type = 'hidden';
            inputLat.name = 'lat';
            inputLat.value = lat;

            const inputLng = document.createElement('input');
            inputLng.type = 'hidden';
            inputLng.name = 'lng';
            inputLng.value = lng;

            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = 'article_id';
            inputId.value = articleId;

            form.appendChild(inputLat);
            form.appendChild(inputLng);
            form.appendChild(inputId);
            document.body.appendChild(form);
            form.submit();
        }, function(error) {
            alert("Erreur de géolocalisation : " + error.message);
        });
    } else {
        alert("La géolocalisation n'est pas supportée par votre navigateur.");
    }
}