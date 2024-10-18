document.getElementById('nav-toggle').addEventListener('click', function () {
    var sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('hidden');
});

// Función para buscar usuarios
async function buscarUsuarios() {
    const cicloEscolar = new Date().getFullYear(); // Año en curso
    const url = `../../backend/buscarUsuario.php?cicloEscolar=${cicloEscolar}`;

    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error('Error al obtener los usuarios');
        }

        const usuarios = await response.json();
        const tbody = document.querySelector('.attendance-table tbody');
        tbody.innerHTML = '';

        usuarios.forEach(usuario => {
            const fila = document.createElement('tr');

            const inputIdUsuario = document.createElement('input');
            inputIdUsuario.type = 'hidden';
            inputIdUsuario.value = usuario.idUsuario;
            inputIdUsuario.className = 'usuario-id'; // Clase para identificar el idUsuario
            fila.appendChild(inputIdUsuario);

            const limpiarNombres = (nombre) => nombre !== null ? nombre : '';

            const columnaNombre = document.createElement('td');
            columnaNombre.textContent = `${limpiarNombres(usuario.primerNombre)} ${limpiarNombres(usuario.segundoNombre)} ${limpiarNombres(usuario.tercerNombre)} ${limpiarNombres(usuario.primerApellido)} ${limpiarNombres(usuario.segundoApellido)}`.trim();
            fila.appendChild(columnaNombre);

            const columnaNombreUsuario = document.createElement('td');
            columnaNombreUsuario.textContent = usuario.nombreUsuario || 'Sin nombre de usuario';
            fila.appendChild(columnaNombreUsuario);

            const columnaRol = document.createElement('td');
            columnaRol.textContent = usuario.nombreRol || 'Sin rol';
            fila.appendChild(columnaRol);

            const columnaEditar = document.createElement('td');
            const botonEditar = document.createElement('button');
            botonEditar.className = 'edit-button';
            botonEditar.textContent = 'Editar';
            botonEditar.addEventListener('click', function () {
                openEditModal(usuario);
            });
            columnaEditar.appendChild(botonEditar);
            fila.appendChild(columnaEditar);

            const columnaEliminar = document.createElement('td');
            const botonEliminar = document.createElement('button');
            botonEliminar.className = 'delete-button';
            botonEliminar.textContent = 'Dar de Baja';
            botonEliminar.addEventListener('click', function () {
                openDeleteModal(usuario);
            });
            columnaEliminar.appendChild(botonEliminar);
            fila.appendChild(columnaEliminar);

            tbody.appendChild(fila);
        });
    } catch (error) {
        console.error('Error al buscar los usuarios:', error);
        alert('Hubo un error al buscar los usuarios.');
    }
}

// Función para abrir el modal de edición
function openEditModal(usuario) {
    const modal = document.getElementById('editModal');
    const form = modal.querySelector('form');
    const saveButton = form.querySelector('.save-button');

    form.querySelector('#edit-primerNombre').value = usuario.primerNombre;
    form.querySelector('#edit-segundoNombre').value = usuario.segundoNombre || '';
    form.querySelector('#edit-tercerNombre').value = usuario.tercerNombre || '';
    form.querySelector('#edit-primerApellido').value = usuario.primerApellido;
    form.querySelector('#edit-segundoApellido').value = usuario.segundoApellido || '';
    form.querySelector('#edit-nombreUsuario').value = usuario.nombreUsuario;

    modal.classList.add('show');

    saveButton.replaceWith(saveButton.cloneNode(true));
    const newSaveButton = form.querySelector('.save-button');

    newSaveButton.addEventListener('click', function () {
        guardarCambios(usuario.idUsuario);
    });
}

// Función para limpiar los campos del modal de edición
function limpiarCamposEdicion() {
    const form = document.getElementById('edit-form');
    form.reset(); // Esto limpiará todos los campos del formulario
}

// Función para guardar los cambios del usuario editado
async function guardarCambios(idUsuario) {
    // Obtener los valores de los campos del formulario de edición
    const primerNombre = document.getElementById('edit-primerNombre').value.trim();
    const segundoNombre = document.getElementById('edit-segundoNombre').value.trim();
    const tercerNombre = document.getElementById('edit-tercerNombre').value.trim();
    const primerApellido = document.getElementById('edit-primerApellido').value.trim();
    const segundoApellido = document.getElementById('edit-segundoApellido').value.trim();
    const nombreUsuario = document.getElementById('edit-nombreUsuario').value.trim();
    const contrasena = document.getElementById('edit-contrasena').value.trim(); // Puede estar vacío

    // Expresión regular para validar nombres y apellidos (solo letras, tildes y espacios)
    const nombreRegex = /^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/;

    // Validar campos obligatorios
    if (!primerNombre || !primerApellido || !nombreUsuario) {
        alert("Por favor, llene todos los campos obligatorios.");
        return;
    }

    // Validar que los nombres y apellidos contengan solo caracteres permitidos
    if (!nombreRegex.test(primerNombre) || (segundoNombre && !nombreRegex.test(segundoNombre)) ||
        (tercerNombre && !nombreRegex.test(tercerNombre)) || !nombreRegex.test(primerApellido) || 
        (segundoApellido && !nombreRegex.test(segundoApellido))) {
        alert("Los nombres y apellidos solo deben contener letras del abecedario español.");
        return;
    }

    // Crear objeto JSON con los datos del usuario
    const data = {
        idUsuario,
        primerNombre,
        segundoNombre,
        tercerNombre,
        primerApellido,
        segundoApellido,
        nombreUsuario
    };

    // Incluir la contraseña solo si se ha ingresado una
    if (contrasena) {
        data.contrasena = contrasena;
    }

    try {
        // Enviar los datos al backend utilizando fetch
        const response = await fetch('../../backend/editarUsuario.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            alert("Usuario actualizado exitosamente.");
            closeModal(); // Cierra el modal
            limpiarCamposEdicion(); // Limpia los campos del formulario
            buscarUsuarios(); // Refresca la tabla
        } else {
            alert("Error al actualizar el usuario: " + result.message);
        }
    } catch (error) {
        console.error("Error al actualizar el usuario:", error);
        alert("Hubo un error al intentar actualizar el usuario.");
    }
}

// Función para abrir el modal de eliminación y mostrar la información del usuario
function openDeleteModal(usuario) {
    const modal = document.getElementById('deleteModal');
    
    // Asignar valores a los campos del modal de eliminación
    document.getElementById('delete-idUsuario').value = usuario.idUsuario; // Campo oculto agregado
    document.getElementById('delete-primerNombre').textContent = usuario.primerNombre || '';
    document.getElementById('delete-segundoNombre').textContent = usuario.segundoNombre || '';
    document.getElementById('delete-tercerNombre').textContent = usuario.tercerNombre || '';
    document.getElementById('delete-primerApellido').textContent = usuario.primerApellido || '';
    document.getElementById('delete-segundoApellido').textContent = usuario.segundoApellido || '';
    document.getElementById('delete-nombreUsuario').textContent = usuario.nombreUsuario || '';
    document.getElementById('delete-rolUsuario').textContent = usuario.nombreRol || '';

    // Mostrar el modal agregando la clase 'show'
    modal.classList.add('show');

    // Asignar idUsuario al campo oculto
    document.getElementById('delete-idUsuario').value = usuario.idUsuario;
}

async function eliminarUsuario() {
    const idUsuario = document.getElementById('delete-idUsuario').value; // Leer idUsuario desde el campo oculto
    const data = { idUsuario };

    try {
        const response = await fetch('../../backend/eliminarUsuario.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            alert("Usuario dado de baja exitosamente.");
            closeDeleteModal(); // Cerrar el modal de eliminación
            buscarUsuarios(); // Actualizar la tabla de usuarios
        } else {
            alert("Error al dar de baja al usuario: " + result.message);
        }
    } catch (error) {
        console.error("Error al dar de baja al usuario:", error);
        alert("Hubo un error al intentar dar de baja al usuario.");
    }
}


// Agregar el evento al botón "Eliminar" del modal de eliminación
document.querySelector('.delete-button-confirm').addEventListener('click', eliminarUsuario);

function closeModal() {
    const modal = document.getElementById('editModal');
    modal.classList.remove('show');
    limpiarCamposEdicion(); // Limpiar los campos al cerrar el modal
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('show');
}

document.querySelector('.cancel-button').addEventListener('click', closeModal);
document.querySelector('.cancel-button-delete').addEventListener('click', closeDeleteModal);

window.addEventListener('load', function () {
    buscarUsuarios();
});

document.addEventListener('DOMContentLoaded', function () {
    const userFooterBtn = document.getElementById('toggleDropdown');
    const userText = userFooterBtn.querySelector('.user-text'); // Seleccionamos solo el texto del botón
    const originalUsername = userFooterBtn.getAttribute('data-username'); // Guardamos el valor del nombre de usuario original

    // Función para hacer la transición suave del texto
    function transitionButton(newText) {
        userText.style.transition = 'opacity 0.3s'; // Transición suave para el texto
        userText.style.opacity = '0'; // Desaparecer el texto actual

        setTimeout(() => {
            userText.textContent = newText; // Cambiar el texto
            userText.style.opacity = '1'; // Mostrar el nuevo texto
        }, 300); // Tiempo de la transición de 0.3 segundos
    }

    // Mostrar "Cerrar sesión" en lugar del nombre de usuario
    userFooterBtn.addEventListener('click', function (event) {
        event.stopPropagation(); // Detener la propagación del evento para no activar el cierre inmediato
        if (userText.textContent === 'Cerrar sesión') {
            window.location.href = '../../backend/logout.php'; // Redirigir al logout
        } else {
            transitionButton('Cerrar sesión');
        }
    });

    // Si el usuario hace clic en cualquier parte de la página, restaurar el nombre del usuario
    document.addEventListener('click', function () {
        if (userText.textContent === 'Cerrar sesión') {
            transitionButton(originalUsername); // Restauramos el nombre de usuario original desde el atributo
        }
    });

    // Detener la propagación del evento cuando el usuario haga clic en el botón de usuario
    userFooterBtn.addEventListener('click', function (event) {
        event.stopPropagation();
    });
});