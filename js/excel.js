
document.getElementById('exportButton').addEventListener('click', function() {
  // Obtener la tabla y sus filas de datos
  var table = document.getElementById('dataTable');
  var rows = table.getElementsByTagName('tr');
  
  // Crear una matriz para almacenar los datos de las filas visibles
  var data = [];
  
  // Recorrer las filas de la tabla y obtener los datos de las filas visibles
  for (var i = 0; i < rows.length; i++) {
    var row = rows[i];
    
    // Verificar si la fila es visible (display: table-row)
    if (window.getComputedStyle(row).display === 'table-row') {
      var rowData = [];
      var cells = row.getElementsByTagName('td');
      
      // Obtener los datos de las celdas de la fila
      for (var j = 0; j < cells.length; j++) {
        rowData.push(cells[j].innerText);
      }
      
      // Agregar los datos de la fila a la matriz
      data.push(rowData);
    }
  }
  
  // Crear un objeto de libro de Excel
  var wb = XLSX.utils.book_new();
  var ws = XLSX.utils.aoa_to_sheet(data);
  
  // Obtener la fecha de hoy en el formato deseado (por ejemplo, 'yyyyMMdd')
  var today = new Date();
  var dateFormatted = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();

  // Agregar la palabra y la fecha al nombre del archivo
  var fileName = 'table_' + dateFormatted + '.xlsx';

  XLSX.utils.book_append_sheet(wb, ws, 'Hoja1');

  // Guardar el archivo Excel
  XLSX.writeFile(wb, fileName);
});