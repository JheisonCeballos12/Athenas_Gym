//---------------------Gráfica de barras – Ventas por mes-----------------------
const ctxMes = document.getElementById('ventasPorMes').getContext('2d');
const ventasPorMes = new Chart(ctxMes, {
    type: 'bar',
    data: {
        labels: window.labelsMeses,
        datasets: [{
            label: 'Ventas por Mes',
            data: window.ventasMesData,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
    }
});

//---------------------Gráfica de pastel – Inscripciones por plan-----------------------

const ctxPlan = document.getElementById('ventasPorPlan').getContext('2d');
const ventasPorPlan = new Chart(ctxPlan, {
    type: 'pie',
    data: {
        labels: window.duracionesLabels,
        datasets: [{
            label: 'Inscripciones por duración de plan',
            data: window.ventasPlanesData,
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
        }]
    },
    options: {
        responsive: true
    }
});

//----------------------Resumen dinámico debajo de la gráfica-----------------------------

// Diccionario de traducción
const mesesES = {
  "Jan": "Enero",
  "Feb": "Febrero",
  "Mar": "Marzo",
  "Apr": "Abril",
  "May": "Mayo",
  "Jun": "Junio",
  "Jul": "Julio",
  "Aug": "Agosto",
  "Sep": "Septiembre",
  "Oct": "Octubre",
  "Nov": "Noviembre",
  "Dec": "Diciembre"
};

// Reemplazar los labels en inglés por español
window.labelsMeses = window.labelsMeses.map(mes => mesesES[mes] || mes);

let resumen = "";
if (window.labelsMeses.length === 1) {
    // Si se filtró un solo mes
    resumen = `Total vendido en ${window.labelsMeses[0]}: $${window.ventasMesData[0].toLocaleString()}`;
} else {
    // Si son todos los meses
    resumen = "<h3>Totales por mes:</h3><ul>";
    window.labelsMeses.forEach((mes, i) => {
        resumen += `<li>${mes} <span>$${window.ventasMesData[i].toLocaleString()}</span></li>`;
    });
    resumen += "</ul>";
}

document.getElementById("resumenVentasMes").innerHTML = resumen;


//---------------------Confirmación moderna con SweetAlert2----------------------------------

document.addEventListener("DOMContentLoaded", function() {
  document.querySelectorAll('.cancelar-btn').forEach(boton => {
    boton.addEventListener('click', function () {
      let form = this.closest('form');
      Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción anulará la inscripción",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit(); // Envía el formulario si confirma
        }
      });
    });
  });
});

// --------------------- Inicializar DataTable ------------------------------
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#ventasTable')) {
        $('#ventasTable').DataTable().destroy(); // 👈 destruye la previa
    }

    $('#ventasTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'pdf', 'print'
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        }
    });
});








