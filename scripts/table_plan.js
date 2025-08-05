
    const modal = document.getElementById("modal");
    const btn = document.getElementById("openModalBtn");
    const span = document.getElementsByClassName("close")[0];

    btn.onclick = () => modal.style.display = "flex";

    function abrirModalEditar(id) {
    window.location.href = 'table_plan.php?edit_id=' + id;
    }


    // Cerrar con la X
    span.onclick = () => {
      modal.style.display = "none";
      window.location.href = "table_plan.php";
    }

    // Cerrar haciendo clic fuera del modal
    window.onclick = (e) => {
      if (e.target == modal) {
        modal.style.display = "none";
        window.location.href = "table_plan.php";
      }
    }
