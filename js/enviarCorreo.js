function enviarCorreo(email, id_servicio) {
    if (email === "" || email === "administracion@ab-forti.com") {
        alert("Usuario no tiene email registrado, favor de comunicarle acerca de la fecha de verificación.");
    } else {
        // Realizar una solicitud AJAX para enviar el correo
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "autos_servicio_manual.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert(xhr.responseText);
            }
        };
        xhr.send("email=" + encodeURIComponent(email) + "&id_servicio=" + encodeURIComponent(id_servicio));
    }
}
// JavaScript Document