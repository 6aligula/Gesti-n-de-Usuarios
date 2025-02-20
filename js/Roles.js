
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

// Event Listeners para cuando el documento esté listo
function actualizarBotonesRol() {
    const rolSelect = document.getElementById('rol');
    const btnCrear = document.getElementById('btnCrearRol');
    const btnEditar = document.getElementById('btnEditarRol');
    const btnEliminar = document.getElementById('btnEliminarRol');

    // Si algo no existe, salimos
    if (!rolSelect || !btnCrear || !btnEditar || !btnEliminar) return;

    // Si hay un rol seleccionado
    if (rolSelect.value && rolSelect.value !== '') {
        // Ocultamos Crear
        btnCrear.classList.add('d-none');
        // Mostramos Editar y Eliminar
        btnEditar.classList.remove('d-none');
        btnEliminar.classList.remove('d-none');
    } else {
        // No hay rol seleccionado
        // Mostramos Crear
        btnCrear.classList.remove('d-none');
        // Ocultamos Editar y Eliminar
        btnEditar.classList.add('d-none');
        btnEliminar.classList.add('d-none');
    }
}


// Modificar el event listener
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded');
    
    const rolSelect = document.getElementById('rol');
    if (rolSelect) {
        console.log('Select de roles encontrado');
        // Cada vez que cambie el valor del select de roles, actualizamos los botones
        rolSelect.addEventListener('change', function() {
            console.log('Cambio en select de roles - valor:', this.value);
            actualizarBotonesRol();
        });
        
        // Llamada inicial (por si ya viene algo seleccionado)
        actualizarBotonesRol();
    } else {
        console.log('No se encontró el select de roles');
    }
});
