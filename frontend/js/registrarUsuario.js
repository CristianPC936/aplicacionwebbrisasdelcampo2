document.getElementById('nav-toggle').addEventListener('click', function() {
    var sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('hidden');
});

async function cargarRoles() {
    try {
        const response = await fetch('http://localhost:8888/.netlify/functions/leerRol');
        
        // Verificar si la respuesta fue exitosa
        if (!response.ok) {
            throw new Error('Error en la solicitud al cargar los roles');
        }
        
        // Convertir la respuesta en JSON
        const roles = await response.json();
        
        // Obtener el elemento select con id 'rolUsuario'
        const selectRolUsuario = document.getElementById('rolUsuario');
        
        // Limpiar cualquier opción previa en el select
        selectRolUsuario.innerHTML = '';

        // Iterar sobre los roles obtenidos y crear opciones para el select
        roles.forEach(rol => {
            const option = document.createElement('option');
            option.value = rol.idRol;  // Asignar idRol como el valor de la opción
            option.textContent = rol.nombreRol;  // Mostrar nombreRol como el texto de la opción
            selectRolUsuario.appendChild(option);
        });
    } catch (error) {
        console.error('Error al cargar los roles:', error);
    }
}

// Ejecutar la función cargarRoles cuando la página cargue
window.addEventListener('load', cargarRoles);

document.getElementById('registerButton').addEventListener('click', async function (event) {
    // Prevenir el envío del formulario y que se limpie automáticamente
    event.preventDefault();

    // Obtener los datos del formulario
    const primerNombre = document.getElementById('primerNombre').value.trim();
    const segundoNombre = document.getElementById('segundoNombre').value.trim();
    const tercerNombre = document.getElementById('tercerNombre').value.trim();
    const primerApellido = document.getElementById('primerApellido').value.trim();
    const segundoApellido = document.getElementById('segundoApellido').value.trim();
    const nombreUsuario = document.getElementById('nombreUsuario').value.trim();
    const contrasena = document.getElementById('contrasena').value.trim();
    const rolUsuario = document.getElementById('rolUsuario').value;
    
    // Añadir el ciclo escolar actual y estado (activo)
    const cicloEscolar = new Date().getFullYear(); // Año actual
    const estado = 1; // Estado activo

    // Expresión regular para validar nombres y apellidos (solo letras, tildes y espacios)
    const nombreRegex = /^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/;

    // Validar campos obligatorios
    if (!primerNombre || !primerApellido || !nombreUsuario || !contrasena) {
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

    // Validar seguridad de la contraseña
    if (!esContrasenaSegura(contrasena)) {
        alert("La contraseña no es segura. Debe contener al menos:\n- 8 caracteres\n- Una letra mayúscula\n- Una letra minúscula\n- Un número\n- Un carácter especial");
        return;
    }

    // Preparar los datos para enviar al backend
    const data = {
        primerNombre,
        segundoNombre,
        tercerNombre,
        primerApellido,
        segundoApellido,
        nombreUsuario,
        contrasena,
        rolUsuario,
        cicloEscolar, // Año en curso
        estado // Siempre activo (1)
    };

    try {
        // Enviar los datos al backend utilizando fetch
        const response = await fetch('../../backend/registrarUsuario.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            alert("Usuario registrado exitosamente.");
            
            // Limpiar el formulario después del registro exitoso
            document.getElementById('primerNombre').value = '';
            document.getElementById('segundoNombre').value = '';
            document.getElementById('tercerNombre').value = '';
            document.getElementById('primerApellido').value = '';
            document.getElementById('segundoApellido').value = '';
            document.getElementById('nombreUsuario').value = '';
            document.getElementById('contrasena').value = '';
            document.getElementById('rolUsuario').value = '';
            
        } else {
            alert("Error al registrar el usuario: " + result.message);
        }
    } catch (error) {
        console.error("Error al registrar el usuario:", error);
        alert("Hubo un error al intentar registrar el usuario.");
    }
});

// Función para validar la seguridad de la contraseña
function esContrasenaSegura(contrasena) {
    const longitudMinima = 8;
    const tieneMayuscula = /[A-Z]/.test(contrasena);
    const tieneMinuscula = /[a-z]/.test(contrasena);
    const tieneNumero = /\d/.test(contrasena);
    const tieneCaracterEspecial = /[!@#$%^&*(),.?":{}|<>]/.test(contrasena);

    return contrasena.length >= longitudMinima && tieneMayuscula && tieneMinuscula && tieneNumero && tieneCaracterEspecial;
}