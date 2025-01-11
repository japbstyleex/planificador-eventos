// Equivalente a window.onload = x, pero no sobrescribe permitiendo así tener varios window.onload y que todos se ejecuten
window.addEventListener("load", () => {
  obtenerCarrito()
    .then((resultadoCarrito) => {
      if (resultadoCarrito.status == "success") {
        actualizarContadorCarrito(resultadoCarrito.carrito.length);
      } else {
        actualizarContadorCarrito(0);
      }
    })
    .catch((error) => {
      console.error(error);
    });

  addDomEventListeners();
  mostrarUsuario();
});

// Funciones CRUD de carrito (Create, Read, Update, Delete)
// Read
function obtenerCarrito() {
  // Devuelve Promise asíncrono, luego habrá que tratarlo con await o .then().catch()
  return new Promise((success, error) => {
    $.ajax({
      url: "php/carrito.php",
      type: "GET",
      dataType: "json",
      success, // then
      error, // catch
    });
  });
}
// Create
function agregarAlCarritoAjax(nombre, servicio, presupuesto) {
  var formData = new FormData();
  formData.append("articulo", nombre);
  formData.append("servicio", servicio);
  formData.append("presupuesto", presupuesto);

  return new Promise((success, error) => {
    $.ajax({
      url: "php/carrito.php",
      type: "POST",
      data: formData,
      dataType: "json",
      success,
      error,
      contentType: false,
      processData: false,
    });
  });
}
// Update
function editarArticuloAjax(idArticulo, nombre, servicio, presupuesto) {
  var formData = new FormData();
  formData.append("id_articulo", idArticulo);
  formData.append("articulo", nombre);
  formData.append("servicio", servicio);
  formData.append("presupuesto", presupuesto);

  return new Promise((success, error) => {
    $.ajax({
      url: "php/carrito.php",
      type: "PUT",
      contentType: "application/json",
      data: JSON.stringify({
        id_articulo: idArticulo,
        articulo: nombre,
        servicio,
        presupuesto,
      }),
      dataType: "json",
      success,
      error,
      contentType: false,
      processData: false,
    });
  });
}
// Delete
function eliminarArticuloAjax(idArticulo) {
  var formData = new FormData();
  formData.append("id_articulo", idArticulo);

  return new Promise((success, error) => {
    $.ajax({
      url: "php/carrito.php?id_articulo=" + idArticulo,
      type: "DELETE",
      dataType: "json",
      success,
      error,
      contentType: false,
      processData: false,
    });
  });
}

function editarArticulo(idArticulo, articulo, servicio) {
  let presupuesto = prompt("Introduce nuevo presupuesto");
  editarArticuloAjax(idArticulo, articulo, servicio, presupuesto)
    .then((resultado) => {
      if (resultado.status == "success") {
        mostrarCarrito();
      } else {
        alerta("Error", resultado.message);
      }
    })
    .catch((error) => {
      console.error(error);
      alerta("Error", error);
    });
}
function eliminarArticulo(idArticulo) {
  eliminarArticuloAjax(idArticulo)
    .then((resultado) => {
      if (resultado.status == "success") {
        mostrarCarrito();
      } else {
        alerta("Error", resultado.message);
      }
    })
    .catch((error) => {
      console.error(error);
      alerta("Error", error);
    });
}
function actualizarContadorCarrito(cantidadArticulos) {
  const contador = document.getElementById("carritoCount");
  contador.textContent = cantidadArticulos;
}
function incrementarContadorCarrito() {
  const contador = document.getElementById("carritoCount");
  contador.textContent = parseInt(contador.textContent) + 1;
}

function mostrarCarrito() {
  const listaCarrito = document.getElementById("listaCarrito");

  obtenerCarrito()
    .then((resultadoCarrito) => {
      if (resultadoCarrito.status == "error") {
        listaCarrito.innerHTML =
          '<li class="list-group-item">' + resultadoCarrito.message + "</li>";
      } else {
        let carrito = resultadoCarrito.carrito;
        actualizarContadorCarrito(carrito.length);
        listaCarrito.innerHTML = ""; // Limpiamos la lista

        if (carrito.length === 0) {
          listaCarrito.innerHTML =
            '<li class="list-group-item">Tu carrito está vacío.</li>';
        } else {
          carrito.forEach((item) => {
            const li = document.createElement("li");
            li.className = "list-group-item";
            li.innerHTML = `
              <strong>Servicio:</strong> ${item.articulo}<br>
              <strong>Presupuesto:</strong> ${item.presupuesto} €
              <button class="btn btn-warning btn-sm float-end" onclick="editarArticulo(${item.id}, '${item.articulo}', '${item.servicio}')">
              <img src="images/pen-to-square-regular.svg" alt="Editar" width="20" height="20"/>
              </button>
              <button class="btn btn-danger btn-sm float-end" onclick="eliminarArticulo(${item.id})">
              <img src="images/papelera.svg" alt="Eliminar" width="20" height="20"/>
              </button>
            `;
            listaCarrito.appendChild(li);
          });
        }
      }
    })
    .catch((error) => {
      listaCarrito.innerHTML = '<li class="list-group-item">' + error + "</li>";
    });
}

function addDomEventListeners() {
  document.getElementById("carritoLink").addEventListener("click", function () {
    mostrarCarrito();
  });

  // Función para redirigir a la página de pago si el carrito tiene artículos
  document
    .getElementById("finalizarCompra")
    .addEventListener("click", async function () {
      let resultadoCarrito = await obtenerCarrito();

      if (
        resultadoCarrito.status == "success" &&
        resultadoCarrito.carrito.length > 0
      ) {
        window.location.href = "pago.html"; // Redirige a la página de pago
      } else {
        if (resultadoCarrito.status != "success") {
          alerta("Error", resultadoCarrito.message);
        } else {
          alerta(
            "Carrito",
            "Tu carrito está vacío. No puedes finalizar la compra."
          );
        }
      }
    });
}

function obtenerUsuarioAjax() {
  return new Promise((success, error) => {
    $.ajax({
      url: "php/sesion.php",
      type: "GET",
      dataType: "json",
      success,
      error,
    });
  });
}
function mostrarUsuario() {
  obtenerUsuarioAjax().then((datos) => {
    if (!datos.username) return; // Si no ha iniciado sesión, terminar aquí la función y no hacer las demás líneas

    let navbar = document.querySelector("div#navbarNav > ul");
    // eliminar login y register
    navbar.removeChild(
      navbar.querySelector("a[href='#loginModal']").parentElement
    );
    navbar.removeChild(
      navbar.querySelector("a[href='#registerModal']").parentElement
    );

    let carrito = document.getElementById("carritoLink").parentElement;
    let liUser = document.createElement("li");
    let liLogout = document.createElement("li");

    liUser.innerHTML = `<span class="nav-link text-success">Bienvenido ${datos.username}</span>`;
    liLogout.innerHTML = `<a href="php/logout.php" class="nav-link">Cerrar sesión <img src="images/right-from-bracket-solid.svg" alt="Eliminar" width="20" height="20"/></a>`;

    navbar.insertBefore(liUser, carrito);
    navbar.insertBefore(liLogout, carrito);
  });
}
