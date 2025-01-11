function alerta(titulo, mensaje) {
  return new Promise((success) => {
    alertify.alert(titulo, mensaje, success);
  });
}
