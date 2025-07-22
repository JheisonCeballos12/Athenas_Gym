<!-- Librería Toastify (solo se carga una vez) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<!-- Script para mostrar el toast si hay mensaje -->
<?php if (isset($_GET['toast'])): ?>
<script>
  Toastify({
    text: "<?= htmlspecialchars($_GET['toast']) ?>",
    duration: 3000,
    gravity: "top",
    position: "center",
    style: {
      background: "#851111ff"
    }
  }).showToast();
</script>
<?php endif; ?>
