$(document).ready(function () {
  var table = $('#dataTable').DataTable();
  var filterDate1 = $('#filterDate1').val();
  var filterDate2 = $('#filterDate2').val();

  // Función para actualizar la tabla con el filtro actual
  function updateTable() {
    $.ajax({
      type: 'POST',
      url: '/scantask/views/models/cargar_operaciones.php',
      data: {
        filterDate1: filterDate1,
        filterDate2: filterDate2
      },
      success: function (data) {
        // Limpia la tabla y destruye la instancia DataTables
        table.clear().destroy();
        // Agrega los resultados de la consulta a la tabla
        $('#dataTable tbody').html(data);
        // Vuelve a inicializar DataTables
        table = $('#dataTable').DataTable();
      }
    });
  }

  // Configura la fecha actual en el campo de entrada
  var today = new Date();
  var todayFormatted = today.toISOString().split('T')[0];
  $('#filterDate1').val(todayFormatted);
    $('#filterDate2').val(todayFormatted);

  // Realiza una carga inicial de la tabla con la fecha de hoy
  filterDate1 = todayFormatted;
    filterDate2 = todayFormatted;
  updateTable();

  $('#filterButton').click(function () {
    filterDate1 = $('#filterDate1').val();
    filterDate2 = $('#filterDate2').val();
    updateTable();
  });

  // Actualiza la tabla cada 10 minutos
  setInterval(updateTable, 600000); // 600,000 milliseconds = 10 minutes
});
