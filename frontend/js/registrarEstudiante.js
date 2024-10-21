document.getElementById('nav-toggle').addEventListener('click', function() {
    var sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('hidden');
});

// Función para llenar el select con los grados
async function cargarGrados() {
    try {
        const response = await fetch('https://as0o0gl0d5.execute-api.us-east-1.amazonaws.com/leerGrado');
        
        if (!response.ok) {
            throw new Error('Error en la solicitud');
        }
        
        const grados = await response.json();
        const selectGrado = document.getElementById('grado'); // El id correcto es "grado"
        
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
        const selectSeccion = document.getElementById('seccion'); // El id correcto es "seccion"
        
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

// Función para registrar al estudiante
async function registrarEstudiante(event) {
    event.preventDefault(); // Evitar que se recargue la página al enviar el formulario

    const button = document.querySelector('.register-button'); // Asumiendo que el botón tiene esta clase
    button.disabled = true; // Deshabilitar el botón
    button.style.opacity = "0.5"; // Atenuar el botón

    // Obtener los valores de los inputs
    const primerNombre = document.getElementById('primerNombre').value;
    const segundoNombre = document.getElementById('segundoNombre').value;
    const tercerNombre = document.getElementById('tercerNombre').value;
    const primerApellido = document.getElementById('primerApellido').value;
    const segundoApellido = document.getElementById('segundoApellido').value;
    const claveAlumno = document.getElementById('clave').value;
    const idGrado = document.getElementById('grado').value;
    const idSeccion = document.getElementById('seccion').value;
    const correoElectronico = document.getElementById('correoElectronico').value; // Nuevo campo de correo electrónico
    const cicloEscolar = new Date().getFullYear(); // Obtener el año en curso

    // Validar que los campos requeridos no estén vacíos
    if (!primerNombre || !primerApellido || !claveAlumno || !idGrado || !idSeccion || !cicloEscolar || !correoElectronico) {
        alert("Por favor, llene todos los campos obligatorios.");
        button.disabled = false;
        button.style.opacity = "1"; // Restaurar el botón
        return;
    }

    // Validar que los nombres y apellidos solo contengan letras
    const nombreApellidoRegex = /^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/;
    if (!nombreApellidoRegex.test(primerNombre) || !nombreApellidoRegex.test(primerApellido)) {
        alert("El primer nombre y el primer apellido solo deben contener letras.");
        button.disabled = false;
        button.style.opacity = "1"; // Restaurar el botón
        return;
    }

    // Validar segundo y tercer nombre (si se llenan) que contengan solo letras
    if ((segundoNombre && !nombreApellidoRegex.test(segundoNombre)) || 
        (tercerNombre && !nombreApellidoRegex.test(tercerNombre)) || 
        (segundoApellido && !nombreApellidoRegex.test(segundoApellido))) {
        alert("Todos los nombres y apellidos solo deben contener letras.");
        button.disabled = false;
        button.style.opacity = "1"; // Restaurar el botón
        return;
    }

    // Validar que la clave del alumno solo contenga números
    if (isNaN(claveAlumno)) {
        alert("La clave del alumno debe ser un número.");
        button.disabled = false;
        button.style.opacity = "1"; // Restaurar el botón
        return;
    }

    // Validar formato de correo electrónico
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(correoElectronico)) {
        alert("Por favor, ingrese un correo electrónico válido.");
        button.disabled = false;
        button.style.opacity = "1"; // Restaurar el botón
        return;
    }

    // Crear el documento JSON
    const estudiante = {
        primerNombre: primerNombre,
        segundoNombre: segundoNombre || "",
        tercerNombre: tercerNombre || "",
        primerApellido: primerApellido,
        segundoApellido: segundoApellido || "",
        claveAlumno: parseInt(claveAlumno),
        idGrado: parseInt(idGrado),
        idSeccion: parseInt(idSeccion),
        cicloEscolar: cicloEscolar,
        correoElectronico: correoElectronico // Añadido al objeto JSON
    };

    // Realizar la solicitud fetch para registrar el estudiante
    try {
        const response = await fetch('https://q6aor9s71g.execute-api.us-east-1.amazonaws.com/crearAlumno', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(estudiante)
        });

        const result = await response.json();

        if (response.ok) {
            alert("Estudiante registrado exitosamente.");
            document.querySelector('.register-form').reset(); // Limpiar el formulario
        } else {
            alert("Error al registrar el estudiante: " + result.message);
        }
    } catch (error) {
        console.error("Error al registrar el estudiante:", error);
        alert("Hubo un error al intentar registrar el estudiante.");
    } finally {
        // Habilitar el botón nuevamente y restaurar su opacidad
        button.disabled = false;
        button.style.opacity = "1";
    }
}

// Asignar la función al evento submit del formulario
document.querySelector('.register-form').addEventListener('submit', registrarEstudiante);

// Ejecutar las funciones cuando se carga la página
window.addEventListener('load', function() {
    cargarGrados();
    cargarSecciones();
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