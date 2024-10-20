document.getElementById('nav-toggle').addEventListener('click', function() {
    var sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('hidden'); // Alterna la clase 'hidden' para desplazar el aside
});

// Función para llenar el select con los grados
async function cargarGrados() {
    try {
        const response = await fetch('https://as0o0gl0d5.execute-api.us-east-1.amazonaws.com/leerGrado');
        
        if (!response.ok) {
            throw new Error('Error en la solicitud');
        }
        
        const grados = await response.json();
        const selectGrado = document.getElementById('grade');
        
        // Limpiar cualquier opción previa
        selectGrado.innerHTML = '';

        // Iterar sobre los grados y crear opciones
        grados.forEach(grado => {
            const option = document.createElement('option');
            option.value = grado.idGrado; // Usar idGrado como el valor de la opción
            option.textContent = grado.nombreGrado; // Mostrar nombreGrado como el texto de la opción
            selectGrado.appendChild(option);
        });
    } catch (error) {
        console.error('Error al cargar los grados:', error);
    }
}

// Función para llenar el select con las secciones
async function cargarSecciones() {
    try {
        const response = await fetch('https://as0o0gl0d5.execute-api.us-east-1.amazonaws.com/leerSeccion');
        
        if (!response.ok) {
            throw new Error('Error en la solicitud');
        }
        
        const secciones = await response.json();
        const selectSeccion = document.getElementById('section');
        
        // Limpiar cualquier opción previa
        selectSeccion.innerHTML = '';

        // Iterar sobre las secciones y crear opciones
        secciones.forEach(seccion => {
            const option = document.createElement('option');
            option.value = seccion.idSeccion; // Usar idSeccion como el valor de la opción
            option.textContent = seccion.nombreSeccion; // Mostrar nombreSeccion como el texto de la opción
            selectSeccion.appendChild(option);
        });
    } catch (error) {
        console.error('Error al cargar las secciones:', error);
    }
}

// Usar addEventListener para que ambas funciones se ejecuten al cargar la página
window.addEventListener('load', function() {
    cargarGrados();
    cargarSecciones();
});

// Función para listar asistencia y realizar las solicitudes fetch
async function listarAsistencia() {
    const idGrado = document.getElementById('grade').value;
    const idSeccion = document.getElementById('section').value;
    const fecha = document.getElementById('date').value;

    try {
        // Primera solicitud fetch: verificar si existe registro de asistencia
        const urlAsistencia = `https://as0o0gl0d5.execute-api.us-east-1.amazonaws.com/leerAsistencia?idGrado=${idGrado}&idSeccion=${idSeccion}&fecha=${fecha}`;
        const responseAsistencia = await fetch(urlAsistencia);
        
        if (!responseAsistencia.ok) {
            throw new Error('Error en la solicitud de asistencia');
        }
        
        const dataAsistencia = await responseAsistencia.json();
        
        if (dataAsistencia.length === 0) {
            alert("No existe registro de asistencia para esta fecha.");
            return; // Detener la ejecución si no existe un registro
        }

        // Segunda solicitud fetch: leer tipos de asistencia
        const urlTipoAsistencia = `https://as0o0gl0d5.execute-api.us-east-1.amazonaws.com/leerTipoAsistencia`;
        const responseTipoAsistencia = await fetch(urlTipoAsistencia);
        
        if (!responseTipoAsistencia.ok) {
            throw new Error('Error en la solicitud de tipos de asistencia');
        }

        const tiposAsistencia = await responseTipoAsistencia.json();

        // Limpiar la tabla de asistencia antes de llenarla
        const tbody = document.querySelector('.attendance-table tbody');
        tbody.innerHTML = ''; // Limpiar cualquier fila previa

        // Iterar sobre los registros de asistencia y crear filas para la tabla
        dataAsistencia.forEach(asistencia => {
            const fila = document.createElement('tr');
            
            // Guardar idAsistencia como atributo oculto en la fila
            fila.setAttribute('data-id-asistencia', asistencia.idAsistencia);

            // Filtrar los nombres y apellidos que no sean null o vacíos
            const nombresCompletos = [
                asistencia.primerNombre,
                asistencia.segundoNombre,
                asistencia.tercerNombre,
                asistencia.primerApellido,
                asistencia.segundoApellido
            ].filter(Boolean).join(' '); // Filtrar valores nulos/vacíos y unir con espacio

            // Columna 1: Nombres completos
            const columnaNombre = document.createElement('td');
            columnaNombre.textContent = nombresCompletos;
            fila.appendChild(columnaNombre);
            
            // Columna 2: Clave del alumno
            const columnaClave = document.createElement('td');
            columnaClave.textContent = asistencia.claveAlumno;
            fila.appendChild(columnaClave);
            
            // Columna 3: Select para el tipo de asistencia
            const columnaAsistencia = document.createElement('td');
            const selectAsistencia = document.createElement('select');

            // Llenar el select con los tipos de asistencia
            tiposAsistencia.forEach(tipo => {
                const option = document.createElement('option');
                option.value = tipo.idtipoAsistencia;
                option.textContent = tipo.nombreAsistencia;
                
                // Comparar con el idtipoAsistencia del registro de asistencia y seleccionarlo
                if (tipo.idtipoAsistencia === asistencia.idtipoAsistencia) {
                    option.selected = true;
                }

                selectAsistencia.appendChild(option);
            });

            columnaAsistencia.appendChild(selectAsistencia);
            fila.appendChild(columnaAsistencia);
            
            // Añadir la fila a la tabla
            tbody.appendChild(fila);
        });
    } catch (error) {
        console.error('Error al listar la asistencia:', error);
    }
}


// Función para editar la asistencia
async function editarAsistencia() {
    const filas = document.querySelectorAll('.attendance-table tbody tr');
    const asistencias = [];

    filas.forEach(fila => {
        const idAsistencia = fila.getAttribute('data-id-asistencia'); // Tomar el idAsistencia oculto
        const selectAsistencia = fila.querySelector('select').value; // Tomar el valor seleccionado del select (idtipoAsistencia)
        
        asistencias.push({
            idAsistencia: idAsistencia,
            idtipoAsistencia: selectAsistencia
        });
    });

    const jsonData = {
        asistencias: asistencias
    };

    console.log('Datos enviados:', JSON.stringify(jsonData)); // Mostrar en la consola el JSON generado

    try {
        const response = await fetch('https://15eqv9i01e.execute-api.us-east-1.amazonaws.com/editarAsistencia', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(jsonData)
        });

        if (!response.ok) {
            throw new Error('Error en la solicitud');
        }

        const result = await response.json();
        alert('Asistencia actualizada exitosamente');
    } catch (error) {
        console.error('Error al guardar la asistencia:', error);
        alert('No se pudo actualizar la asistencia');
    }
}

// Asociar la función al botón de guardar
document.querySelector('.save-button').addEventListener('click', editarAsistencia);

// Asignar la función al botón de búsqueda
document.querySelector('.search-button').addEventListener('click', listarAsistencia);

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