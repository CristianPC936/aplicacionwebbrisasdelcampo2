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

function establecerFechaActual() {
    const hoy = new Date();
    const dia = String(hoy.getDate()).padStart(2, '0'); // Día en formato de dos dígitos
    const mes = String(hoy.getMonth() + 1).padStart(2, '0'); // Mes en formato de dos dígitos (los meses en JS van de 0 a 11)
    const anio = hoy.getFullYear(); // Año actual

    const fechaFormateada = `${anio}-${mes}-${dia}`; // Formato de fecha en YYYY-MM-DD

    // Establecer la fecha actual en el input
    document.getElementById('date').value = fechaFormateada;
}

// Usar addEventListener para que ambas funciones se ejecuten al cargar la página
window.addEventListener('load', function() {
    cargarGrados();
    cargarSecciones();
    establecerFechaActual();
});

// Función para listar asistencia y realizar las solicitudes fetch
async function listarAsistencia() {
    const button = document.querySelector('.search-button');
    button.disabled = true;
    button.style.opacity = "0.5";

    // Limpiar la tabla de asistencia antes de llenarla
    const tbody = document.querySelector('.attendance-table tbody');
    tbody.innerHTML = ''; // Limpiar cualquier fila previa

    const idGrado = document.getElementById('grade').value;
    const idSeccion = document.getElementById('section').value;
    const fecha = document.getElementById('date').value;
    const cicloEscolar = new Date().getFullYear(); // Obtener el año en curso

    try {
        // Primera solicitud fetch: verificar si existe registro de asistencia
        const urlAsistencia = `https://as0o0gl0d5.execute-api.us-east-1.amazonaws.com/leerAsistencia?idGrado=${idGrado}&idSeccion=${idSeccion}&fecha=${fecha}`;
        const responseAsistencia = await fetch(urlAsistencia);
        
        if (!responseAsistencia.ok) {
            throw new Error('Error en la solicitud de asistencia');
        }
        
        const dataAsistencia = await responseAsistencia.json();
        
        if (dataAsistencia.length > 0) {
            alert("Ya existe un registro de asistencia para esta fecha.");
            return; // Detener la ejecución si ya existe un registro
        }

        // Segunda solicitud fetch: leer alumnos del grado y sección seleccionados
        const urlAlumno = `https://as0o0gl0d5.execute-api.us-east-1.amazonaws.com/leerAlumno?idGrado=${idGrado}&idSeccion=${idSeccion}&cicloEscolar=${cicloEscolar}`;
        const responseAlumno = await fetch(urlAlumno);
        
        if (!responseAlumno.ok) {
            throw new Error('Error en la solicitud de alumnos');
        }
        
        const alumnos = await responseAlumno.json();

        // Tercera solicitud fetch: leer tipos de asistencia
        const urlTipoAsistencia = `https://as0o0gl0d5.execute-api.us-east-1.amazonaws.com/leerTipoAsistencia`;
        const responseTipoAsistencia = await fetch(urlTipoAsistencia);
        
        if (!responseTipoAsistencia.ok) {
            throw new Error('Error en la solicitud de tipos de asistencia');
        }

        const tiposAsistencia = await responseTipoAsistencia.json();

        // Iterar sobre los alumnos y crear filas para la tabla
        alumnos.forEach(alumno => {
            const fila = document.createElement('tr');
            
            // Guardar idAlumno como atributo oculto en la fila
            fila.setAttribute('data-id-alumno', alumno.idAlumno);

            // Filtrar nombres y apellidos que no sean null y crear la cadena
            const nombresCompletos = [
                alumno.primerNombre,
                alumno.segundoNombre,
                alumno.tercerNombre,
                alumno.primerApellido,
                alumno.segundoApellido
            ].filter(Boolean).join(' '); // Filtra los valores no nulos o vacíos y únelos con un espacio

            // Columna 1: Nombres completos
            const columnaNombre = document.createElement('td');
            columnaNombre.textContent = nombresCompletos;
            fila.appendChild(columnaNombre);
            
            // Columna 2: Clave del alumno
            const columnaClave = document.createElement('td');
            columnaClave.textContent = alumno.claveAlumno;
            fila.appendChild(columnaClave);
            
            // Columna 3: Select para el tipo de asistencia
            const columnaAsistencia = document.createElement('td');
            const selectAsistencia = document.createElement('select');

            // Llenar el select con los tipos de asistencia
            tiposAsistencia.forEach(tipo => {
                const option = document.createElement('option');
                option.value = tipo.idtipoAsistencia;
                option.textContent = tipo.nombreAsistencia;
                selectAsistencia.appendChild(option);
            });

            columnaAsistencia.appendChild(selectAsistencia);
            fila.appendChild(columnaAsistencia);
            
            // Añadir la fila a la tabla
            tbody.appendChild(fila);
        });
    } catch (error) {
        console.error('Error al listar la asistencia:', error);
    } finally {
        // Esto siempre se ejecutará, independientemente de lo que pase en el try
        button.disabled = false;
        button.style.opacity = "1";
    }
}

function guardarAsistencia() {
    const button = document.querySelector('.save-button');
    button.disabled = true;
    button.style.opacity = "0.5"; // Atenuar el botón

    const fecha = document.getElementById('date').value;
    const tbody = document.querySelector('.attendance-table tbody');
    const registrosAsistencia = [];

    // Recorrer cada fila de la tabla para obtener los datos de asistencia
    tbody.querySelectorAll('tr').forEach(fila => {
        const idAlumno = fila.getAttribute('data-id-alumno'); // Recuperar el idAlumno desde el atributo oculto
        const idtipoAsistencia = fila.querySelector('select').value; // Recuperar el idtipoAsistencia seleccionado

        // Crear el objeto de asistencia y añadirlo a la lista
        registrosAsistencia.push({
            idAlumno: idAlumno,
            idtipoAsistencia: idtipoAsistencia,
            fecha: fecha
        });
    });

    // Crear el JSON para la solicitud
    const asistenciaJSON = {
        asistencias: registrosAsistencia
    };

    // Mostrar el JSON en la consola para verificarlo
    console.log(asistenciaJSON);

    // Enviar la solicitud POST a la función serverless
    fetch('https://q6aor9s71g.execute-api.us-east-1.amazonaws.com/crearAsistencia', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(asistenciaJSON)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al registrar la asistencia');
        }
        return response.json();
    })
    .then(data => {
        alert('Asistencia registrada exitosamente');
        tbody.innerHTML = '';
    })
    .catch(error => {
        console.error('Error al registrar la asistencia:', error);
        alert('No se pudo registrar la asistencia');
    })
    .finally(() => {
        // Habilitar el botón nuevamente después de que la operación finalice
        button.disabled = false;
        button.style.opacity = "1"; // Restaurar la opacidad del botón
    });
}

// Asignar la función al botón de guardar
document.querySelector('.save-button').addEventListener('click', guardarAsistencia);

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