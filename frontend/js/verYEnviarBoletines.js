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

        // Cargar estudiantes después de cargar grados
        cargarEstudiantes();
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

        // Cargar estudiantes después de cargar secciones
        cargarEstudiantes();
    } catch (error) {
        console.error('Error al cargar las secciones:', error);
    }
}

// Función para cargar los estudiantes cuando cambia el grado o la sección
async function cargarEstudiantes() {
    const gradeSelect = document.getElementById('grade');
    const sectionSelect = document.getElementById('section');
    const studentSelect = document.getElementById('student');
    const idGrado = gradeSelect.value;
    const idSeccion = sectionSelect.value;
    const cicloEscolar = new Date().getFullYear(); // Obtener el año en curso

    // Limpiar el select de estudiantes antes de llenarlo
    studentSelect.innerHTML = '';

    // Verificar si ambos select tienen un valor seleccionado
    if (!idGrado || !idSeccion) {
        return; // No hacer nada si no se han seleccionado ambos valores
    }

    // Crear el objeto JSON que se enviará a la función serverless
    const data = {
        idGrado: idGrado,
        idSeccion: idSeccion,
        cicloEscolar: cicloEscolar
    };

    try {
        // Realizar la solicitud fetch a la función serverless
        const response = await fetch('https://b9u1ldaqo7.execute-api.us-east-1.amazonaws.com/leerAlumnoSoloNombre', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error('Error en la solicitud para obtener estudiantes.');
        }

        const estudiantes = await response.json();

        // Rellenar el select de estudiantes con las nuevas opciones
        estudiantes.forEach(estudiante => {
            const option = document.createElement('option');
            option.value = estudiante.idAlumno;
            option.textContent = `${estudiante.primerNombre} ${estudiante.primerApellido}`;
            studentSelect.appendChild(option);
        });
    } catch (error) {
        console.error('Error al obtener los estudiantes:', error);
    }
}

async function cargarBoletin() {
    const studentSelect = document.getElementById('student');
    const gradeSelect = document.getElementById('grade');
    const sectionSelect = document.getElementById('section');
    const studentId = studentSelect.value;
    const idGrado = gradeSelect.value;
    const idSeccion = sectionSelect.value;
    const cicloEscolar = new Date().getFullYear(); // Año en curso

    const data = {
        idAlumno: studentId,
        idGrado: idGrado,
        cicloEscolar: cicloEscolar
    };

    try {
        const response = await fetch('https://b9u1ldaqo7.execute-api.us-east-1.amazonaws.com/leerBoletin', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error('Error al obtener el boletín.');
        }

        const boletin = await response.json();

        // Limpiar la tabla antes de llenarla con las nuevas notas
        const tbody = document.querySelector('.attendance-table tbody');
        tbody.innerHTML = '';

        // Crear filas en la tabla para cada curso y sus notas por bimestre
        const cursosAgrupados = agruparNotasPorCurso(boletin);

        cursosAgrupados.forEach(curso => {
            const fila = document.createElement('tr');

            // Columna de nombre del curso
            const columnaCurso = document.createElement('td');
            columnaCurso.textContent = curso.nombreCurso;
            fila.appendChild(columnaCurso);

            // Columna para cada bimestre
            for (let bimestre = 1; bimestre <= 4; bimestre++) {
                const columnaNota = document.createElement('td');
                const nota = curso.notas[bimestre] || ''; // Mostrar nota o espacio vacío

                // Si la nota es menor a 60 y no es vacío, aplicar color rojo
                if (nota !== '' && nota < 60) {
                    columnaNota.innerHTML = `<span style="color: red;">${nota}</span>`; // Mostrar nota en rojo
                } else {
                    columnaNota.textContent = nota; // Mostrar nota normal
                }

                fila.appendChild(columnaNota);
            }

            tbody.appendChild(fila);
        });

    } catch (error) {
        console.error('Error al cargar el boletín:', error);
    }
}

// Función auxiliar para agrupar las notas por curso y bimestre
function agruparNotasPorCurso(boletin) {
    const cursosAgrupados = [];

    boletin.forEach(registro => {
        let curso = cursosAgrupados.find(c => c.nombreCurso === registro.nombreCurso);

        if (!curso) {
            curso = {
                nombreCurso: registro.nombreCurso,
                notas: {}
            };
            cursosAgrupados.push(curso);
        }

        curso.notas[registro.bimestre] = registro.nota;
    });

    return cursosAgrupados;
}

// Función para generar el reporte de boletín en PDF
async function generarReporteBoletin() {
    const studentSelect = document.getElementById('student');
    const gradeSelect = document.getElementById('grade');
    const sectionSelect = document.getElementById('section');
    const studentId = studentSelect.value;
    const idGrado = gradeSelect.value;
    const idSeccion = sectionSelect.value;
    const cicloEscolar = new Date().getFullYear(); // Año en curso

    const data = {
        idAlumno: studentId,
        idGrado: idGrado,
        idSeccion: idSeccion,
        cicloEscolar: cicloEscolar
    };

    try {
        const response = await fetch('../../backend/generarReporteBoletin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.status === 'success') {
            alert(result.message); // Mostrar mensaje de éxito
        } else {
            alert('Error: ' + result.message); // Mostrar mensaje de error
        }
    } catch (error) {
        console.error('Error al generar el reporte:', error);
        alert('Error al generar el reporte.');
    }
}

// Asignar la función al botón de "Guardar" para generar el reporte
document.querySelector('.save-button').addEventListener('click', generarReporteBoletin);

// Asignar eventos para cargar estudiantes al cambiar el grado o la sección
document.getElementById('grade').addEventListener('change', cargarEstudiantes);
document.getElementById('section').addEventListener('change', cargarEstudiantes);

// Ejecutar cargarGrados y cargarSecciones al cargar la página
window.addEventListener('load', function() {
    cargarGrados();
    cargarSecciones();
});

// Asignar la función al botón de buscar boletín
document.querySelector('.search-button').addEventListener('click', cargarBoletin);

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
