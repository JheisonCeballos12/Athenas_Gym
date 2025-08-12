document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("modal");
  const modalVenta = document.getElementById("modalVenta");
  const clienteSelectVenta = document.getElementById("cliente_id_select");

  window.abrirModalEditar = function(clienteId) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '';
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'edit_id';
    input.value = clienteId;
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
  }

  window.abrirModalVenta = function(clienteId) {
    if (modalVenta) {
      modalVenta.style.display = "flex";
      if (clienteSelectVenta) {
        clienteSelectVenta.value = clienteId;
      }
    }
  }

  const openModalBtn = document.getElementById("openModalBtn");
  if (openModalBtn && modal) {
    openModalBtn.addEventListener("click", () => {
      modal.style.display = "flex";
    });
  }

  const openVentaBtn = document.getElementById("openVentaBtn");
  if (openVentaBtn && modalVenta) {
    openVentaBtn.addEventListener("click", () => {
      modalVenta.style.display = "flex";
    });
  }

  const closeBtn = document.querySelector(".close");
  if (closeBtn && modal) {
    closeBtn.addEventListener("click", () => {
      modal.style.display = "none";
      window.location.href = "table_clients.php";
    });
  }

  const closeVentaBtn = document.querySelector(".close-venta");
  if (closeVentaBtn && modalVenta) {
    closeVentaBtn.addEventListener("click", () => {
      modalVenta.style.display = "none";
    });
  }

  window.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.style.display = "none";
      window.location.href = "table_clients.php";
    }
    if (e.target === modalVenta) {
      modalVenta.style.display = "none";
    }
  });

  // TOAST DE CUMPLEAÑOS
  if (window.cumpleaneros && cumpleaneros.length > 0) {
    cumpleaneros.forEach(nombre => {
      mostrarToast(`🎉 ¡Hoy está de cumpleaños ${nombre}! 🎂`);
    });
  }

  function mostrarToast(mensaje) {
    Toastify({
      text: mensaje,
      duration: 5000,
      gravity: "top",
      position: "right",
      style: {
        background: "#1abc9c",
        color: "#fff",
        borderRadius: "8px",
        padding: "10px 20px",
        fontWeight: "bold",
      }
    }).showToast();
  }
});

// ------------------- NOTIFICACION PARA CONFIRMAR ACTIVAR O DESACTIVAR----------------------------------

document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".form-delete").forEach(function(form) {
        form.addEventListener("submit", function(event) {
            event.preventDefault(); // Evita el envío automático

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción cambiará el estado del cliente",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Envía el formulario si confirmó
                }
            });
        });
    });
});

