function validarCurso() {
  let titulo = document.getElementById("titulo").value;
  let precio = document.getElementById("precio_actual").value;

  if (titulo.length < 5) {
    alert("El título debe tener al menos 5 caracteres");
    return false;
  }

  if (precio <= 0) {
    alert("El precio debe ser mayor a 0");
    return false;
  }

  return true;
}
