const ctxMes = document.getElementById('ventasPorMes').getContext('2d');
const ventasPorMes = new Chart(ctxMes, {
    type: 'bar',
    data: {
        labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
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



