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

document.querySelector('.generate-button').addEventListener('click', async function() {
    const button = document.querySelector('.generate-button'); // Seleccionar el botón "Generar"
    button.disabled = true; // Deshabilitar el botón
    button.style.opacity = "0.5"; // Atenuar el botón

    const grade = document.getElementById('grade').value;
    const section = document.getElementById('section').value;
    const fromDate = document.getElementById('from').value;
    const toDate = document.getElementById('to').value;

    // Verificar que los campos no estén vacíos antes de enviar la solicitud
    if (!grade || !section || !fromDate || !toDate) {
        alert('Por favor, complete todos los campos antes de generar el reporte.');
        button.disabled = false; // Restaurar el botón
        button.style.opacity = "1";
        return;
    }

    const data = {
        grade: grade,
        section: section,
        fromDate: fromDate,
        toDate: toDate
    };

    try {
        const response = await fetch('../../backend/reporte_asistencia.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            window.open(url); // Abrir el PDF generado en una nueva pestaña
        } else {
            console.error('Error al generar el reporte');
            alert('Ocurrió un error al generar el reporte.');
        }
    } catch (error) {
        console.error('Error en la solicitud:', error);
        alert('Ocurrió un error en la solicitud.');
    } finally {
        // Habilitar el botón nuevamente y restaurar su opacidad
        button.disabled = false;
        button.style.opacity = "1";
    }
});

function cargarFechas() {
    const fechaActual = new Date();

    // Obtener el primer día del mes en curso
    const primerDia = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), 1);
    // Obtener el último día del mes en curso
    const ultimoDia = new Date(fechaActual.getFullYear(), fechaActual.getMonth() + 1, 0);

    // Formatear las fechas en formato yyyy-mm-dd
    const formatoPrimerDia = primerDia.toISOString().split('T')[0];
    const formatoUltimoDia = ultimoDia.toISOString().split('T')[0];

    // Asignar las fechas a los inputs
    document.getElementById('from').value = formatoPrimerDia;
    document.getElementById('to').value = formatoUltimoDia;
}

// Ejecutar las funciones al cargar la página
window.addEventListener('load', function() {
    cargarGrados();
    cargarSecciones();
    cargarFechas();
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