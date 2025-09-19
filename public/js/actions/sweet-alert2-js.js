window.showAlert = function(icon, title, text, color) {
  Swal.fire({
    icon,
    title,
    text,
    confirmButtonColor: color,
    background: '#94a3b8',
    color: '#000000',
    customClass: {
      confirmButton: 'swal2-confirm-btn-text-color'
    }
  });
};
