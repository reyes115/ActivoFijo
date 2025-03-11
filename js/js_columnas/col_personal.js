  // Función para guardar las configuraciones en el almacenamiento local
  function guardarConfiguraciones() {
    var columnas = document.getElementsByClassName("columna");
    var configuraciones = [];

    for (var i = 0; i < columnas.length; i++) {
      configuraciones.push(columnas[i].checked);
    }

    localStorage.setItem("TablaPersonal", JSON.stringify(configuraciones));
  }

  // Función para cargar las configuraciones desde el almacenamiento local
  function cargarConfiguraciones() {
    var columnas = document.getElementsByClassName("columna");
    var configuraciones = JSON.parse(localStorage.getItem("TablaPersonal"));

    if (configuraciones) {
      for (var i = 0; i < columnas.length; i++) {
        columnas[i].checked = configuraciones[i];
      }

      aplicarConfiguraciones();
    }
  }

  // Aplicar las configuraciones al cargar la página
  window.onload = function () {
    cargarConfiguraciones();
  };

