$(document).ready(function() {
  // Inicializa la tabla DataTable
  var miTabla = $('#dataTable').DataTable();

  // Aplica las configuraciones al cargar la página
  aplicarConfiguraciones();

  // Aplica las configuraciones cada vez que la tabla se redibuja
  miTabla.on('draw.dt', function() {
    aplicarConfiguraciones();
  }); 
    });
function aplicarConfiguraciones() {
    var tabla = document.getElementById("dataTable");
    var columnas = document.getElementsByClassName("columna");
    var numColumnas = tabla.rows[0].cells.length;
    var numFilas = tabla.rows.length;

    for (var fila = 0; fila < numFilas; fila++) {
      var celdas = tabla.rows[fila].cells;

      for (var columna = 0; columna < numColumnas; columna++) {
        var columnaVisible = columnas[columna].checked;
        var celda = celdas[columna];

        celda.classList.toggle("hidden", !columnaVisible);

        // Ocultar/Mostrar los títulos
        if (fila === 0) {
          var titulo = tabla.rows[fila].getElementsByTagName("th")[columna];
          titulo.classList.toggle("hidden", !columnaVisible);
        }
      }
      guardarConfiguraciones();
    }

  }
    
function marcarTodos() {
  var checkboxes = document.getElementsByClassName("columna");
  for (var i = 0; i < checkboxes.length; i++) {
    checkboxes[i].checked = true;
  }
}
