
function crearRol() {
    fetch('C_Frontal.php?controlador=Roles&metodo=getVistaNuevo')
        .then(res => res.text())
        .then(html => {
            document.getElementById('capaEditarCrear').innerHTML = html;
            const modalRol = new bootstrap.Modal(document.getElementById('modalRol'));
            modalRol.show();
        })
        .catch(error => console.error('Error:', error));
}

function guardarRol() {
    const formData = new FormData(document.getElementById('formRol'));
    const params = new URLSearchParams();
    
    for (let [key, value] of formData.entries()) {
        params.append(key, value);
    }
    
    fetch('C_Frontal.php?controlador=Roles&metodo=guardarRol&' + params.toString())
        .then(res => res.json())
        .then(data => {
            const modalRol = bootstrap.Modal.getInstance(document.getElementById('modalRol'));
            if (data.correcto === 'S') {
                modalRol.hide();
                location.reload();
            } else {
                alert('Error al guardar el rol: ' + data.msj);
            }
        })
        .catch(error => console.error('Error:', error));
}

function editarRol() {
    const rolId = document.getElementById('rol').value;
    if (!rolId) return;

    fetch(`C_Frontal.php?controlador=Roles&metodo=getVistaEditar&id=${rolId}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('capaEditarCrear').innerHTML = html;
            const modalRol = new bootstrap.Modal(document.getElementById('modalRol'));
            modalRol.show();
        })
        .catch(error => console.error('Error:', error));
}

function eliminarRol() {
    const rolId = document.getElementById('rol').value;
    const rolSelect = document.getElementById('rol');
    const rolNombre = rolSelect.options[rolSelect.selectedIndex].text;
    
    if (!rolId) return;
    
    if (rolNombre === 'Administrador') {
        alert('No se puede eliminar el rol Administrador');
        return;
    }

    if (confirm(`¿Está seguro de eliminar el rol "${rolNombre}"?`)) {
        fetch(`C_Frontal.php?controlador=Roles&metodo=eliminarRol&id=${rolId}`)
            .then(res => res.json())
            .then(data => {
                if (data.correcto === 'S') {
                    location.reload();
                } else {
                    alert('Error al eliminar el rol: ' + data.msj);
                }
            })
            .catch(error => console.error('Error:', error));
    }
}

// Función para actualizar la visualización de roles asignados al usuario seleccionado
function actualizarRolesAsignados() {
    const usuarioSelect = document.getElementById('usuario');
    const rolSelect = document.getElementById('rol');
    const rolesUsuario = document.getElementById('rolesUsuario');

    // Reiniciamos el mensaje y eliminamos cualquier estilo asignado a los roles
    rolesUsuario.textContent = '';
    for (let option of rolSelect.options) {
        option.classList.remove('assigned');
    }

    // Si se ha seleccionado un usuario
    if (usuarioSelect.value !== '') {
        // Obtenemos la opción seleccionada y sus roles asignados (almacenados en data-roles)
        const selectedOption = usuarioSelect.options[usuarioSelect.selectedIndex];
        let assignedRoles = [];
        try {
            assignedRoles = JSON.parse(selectedOption.getAttribute('data-roles') || '[]');
            console.log("Roles asignados del usuario:", assignedRoles);
        } catch (e) {
            console.error("Error parseando data-roles:", e);
        }
        // Mostramos los roles asignados en un pequeño mensaje
        if (assignedRoles.length > 0) {
            rolesUsuario.textContent = "Roles asignados: " + assignedRoles.join(', ');
        } else {
            rolesUsuario.textContent = "El usuario no tiene roles asignados.";
        }
        // Aplicamos una clase visual (por ejemplo, 'assigned') a las opciones que estén asignadas
        if (rolSelect) {
            for (let option of rolSelect.options) {
                if (assignedRoles.includes(parseInt(option.value))) {
                    console.log("Asignando clase 'assigned' a la opción:", option);
                    option.classList.add('assigned');
                }
            }
        }
    }
}

// Función para actualizar la visibilidad de los botones de gestión de roles
function actualizarBotonesRol() {
    const rolSelect = document.getElementById('rol');
    const btnCrear = document.getElementById('btnCrearRol');
    const btnEditar = document.getElementById('btnEditarRol');
    const btnEliminar = document.getElementById('btnEliminarRol');

    // Si no existen algunos de los elementos, salimos
    if (!rolSelect || !btnCrear || !btnEditar || !btnEliminar) return;

    // Si se ha seleccionado un rol (valor no vacío)
    if (rolSelect.value !== '') {
        // Ocultamos el botón de crear y mostramos editar y eliminar
        btnCrear.classList.add('d-none');
        btnEditar.classList.remove('d-none');
        btnEliminar.classList.remove('d-none');
    } else {
        // Si no hay rol seleccionado, mostramos solo el botón de crear
        btnCrear.classList.remove('d-none');
        btnEditar.classList.add('d-none');
        btnEliminar.classList.add('d-none');
    }
}

/***************************************************
 * NUEVO: Funciones para ASIGNAR y QUITAR un rol
 ****************************************************/
function asignarRolAlUsuario() {
    const usuarioSelect = document.getElementById('usuario');
    const rolSelect = document.getElementById('rol');

    const usuarioId = usuarioSelect.value;
    const rolId = rolSelect.value;

    if (!usuarioId || !rolId) {
        alert('Seleccione usuario y rol antes de asignar.');
        return;
    }

    fetch(`C_Frontal.php?controlador=Roles&metodo=asignarRol&usuarioId=${usuarioId}&rolId=${rolId}`)
        .then(res => res.json())
        .then(data => {
            if (data.correcto === 'N') {
                alert('Error al asignar rol: ' + data.msj);
            } else {
                alert(data.msj);
                // 1) Actualizamos en memoria:
                const currentOption = usuarioSelect.options[usuarioSelect.selectedIndex];
                let assignedRoles = JSON.parse(currentOption.getAttribute('data-roles') || '[]');
                assignedRoles.push(parseInt(rolId));
                currentOption.setAttribute('data-roles', JSON.stringify(assignedRoles));

                // 2) Llamamos a actualizarRolesAsignados para refrescar visualmente
                actualizarRolesAsignados();
            }
        })
        .catch(error => console.error('Error al asignar rol:', error));
}

function quitarRolAlUsuario() {
    const usuarioSelect = document.getElementById('usuario');
    const rolSelect = document.getElementById('rol');
    
    const usuarioId = usuarioSelect.value;
    const rolId = rolSelect.value;
    
    if (!usuarioId || !rolId) {
        alert('Seleccione usuario y rol antes de quitar.');
        return;
    }

    fetch(`C_Frontal.php?controlador=Roles&metodo=quitarRol&usuarioId=${usuarioId}&rolId=${rolId}`)
        .then(res => res.json())
        .then(data => {
            if (data.correcto === 'N') {
                alert('Error al quitar rol: ' + data.msj);
            } else {
                alert(data.msj);

                // 1) Actualizamos en memoria, removiendo el rol
                const currentOption = usuarioSelect.options[usuarioSelect.selectedIndex];
                let assignedRoles = JSON.parse(currentOption.getAttribute('data-roles') || '[]');
                const index = assignedRoles.indexOf(parseInt(rolId));
                if (index !== -1) {
                    assignedRoles.splice(index, 1);
                }
                currentOption.setAttribute('data-roles', JSON.stringify(assignedRoles));

                // 2) Volvemos a pintar
                actualizarRolesAsignados();
            }
        })
        .catch(error => console.error('Error al quitar rol:', error));
}

// Añadimos los event listeners necesarios cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    const usuarioSelect = document.getElementById('usuario');
    const rolSelect = document.getElementById('rol');
    
    // Botones NUEVOS de asignar/quitar rol
    const btnAsignarRol = document.getElementById('btnAsignarRol');
    const btnQuitarRol  = document.getElementById('btnQuitarRol');

    // Listener para cambio de usuario
    if (usuarioSelect) {
        usuarioSelect.addEventListener('change', function() {
            console.log("Cambio de usuario detectado, valor:", usuarioSelect.value);
            actualizarRolesAsignados();
            if (rolSelect) {
                rolSelect.selectedIndex = 0;
                actualizarBotonesRol();
            }
        });
    }

    // Listener para cambio de rol
    if (rolSelect) {
        rolSelect.addEventListener('change', actualizarBotonesRol);
        // Llamada inicial
        actualizarBotonesRol();
    }

    // Listeners para los botones de asignar/quitar
    if (btnAsignarRol) {
        console.log('Click en Asignar detectado'); // <--- para debug
        btnAsignarRol.addEventListener('click', asignarRolAlUsuario);
    }
    if (btnQuitarRol) {
        console.log('Click en Eliminar detectado'); // <--- para debug
        btnQuitarRol.addEventListener('click', quitarRolAlUsuario);
    }
    
});

$(document).on('click', '#btnAsignarRol', asignarRolAlUsuario);
$(document).on('click', '#btnQuitarRol', quitarRolAlUsuario);

// Usamos jQuery para delegar el evento change en el select de usuario
$(document).on('change', '#usuario', function() {
    const usuarioId = $(this).val();
    console.log("Cambio de usuario detectado via jQuery, valor:", usuarioId);
    
    // Llamamos a la función para obtener los roles actualizados desde el servidor
    obtenerRolesDelUsuario(usuarioId);
});

// Función que hace la consulta AJAX para recuperar los roles del usuario
function obtenerRolesDelUsuario(usuarioId) {
    if (!usuarioId) {
        return;
    }
    
    fetch(`C_Frontal.php?controlador=Usuarios&metodo=obtenerRolesUsuario&id=${usuarioId}`)
        .then(res => res.json())
        .then(data => {
            if (data.correcto === 'S') {
                console.log("Roles obtenidos del servidor:", data.roles);
                // Actualizamos el atributo data-roles en la opción seleccionada
                const option = document.querySelector(`#usuario option[value="${usuarioId}"]`);
                option.setAttribute('data-roles', JSON.stringify(data.roles));
                // Refrescamos la visualización de roles asignados
                actualizarRolesAsignados();
            } else {
                console.error("Error:", data.msj);
            }
        })
        .catch(error => console.error("Error obteniendo roles:", error));
}


