document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('calendar');
  const calendar = new FullCalendar.Calendar(calendarEl, {
    themeSystem: 'bootstrap4',
    headerToolbar: {
      left: 'prev,today,next',
      center: 'title',
      right: 'dayGridMonth,listDay,listWeek,listMonth',
    },
    buttonText: {
      today: 'Hoy',
      month: 'Mes',
      day: 'Día',
      week: 'Semana',
      listMonth: 'Agenda'
    },
    locale: 'es',
    viewDidMount: function (view) {
      // Modificar el estilo del título del mes
      const title = document.querySelector('.fc-toolbar-title');
      if (title) {
        title.classList.add('btn', 'btn-sm'); // Agregar clases de Bootstrap a título
        title.style.textTransform = 'uppercase';
      }

      // Modificar el estilo de los nombres de los días de la semana
      const dayHeaders = document.querySelectorAll('.fc-col-header-cell.fc-day');
      dayHeaders.forEach(dayHeader => {
        dayHeader.style.textTransform = 'capitalize';
      });
    },
    dayHeaderFormat: {
      weekday: 'long'
    }, // Mostrar nombres de días completos
    displayEventTime: false, // Ocultar la información de la hora
    events: {
      url: '/assets/dashboard/fecha_soporte.php', // Reemplaza con la URL correcta de tu API
      method: 'GET',
      extraParams: {
        // Puedes enviar parámetros adicionales si es necesario
      },
      failure: function () {
        console.error('Error al obtener los eventos');
      },
    },
    eventRender: function (info) {
      // Modificar el formato de la fecha del evento
      const eventDate = info.event.start.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
      info.el.querySelector('.fc-event-title').innerHTML = `${eventDate} - ${info.event.title}`;
    },
    eventClick: function (info) {
      // Obtener el ID del evento
      const eventId = info.event.id;

      // Redirigir a la página deseada, pasando el ID del evento como parámetro
      window.location.href = `ver_equipo${eventId}`;
    }
  });

  calendar.render();
});
