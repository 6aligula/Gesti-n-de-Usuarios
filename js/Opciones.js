function buscarPermisos() {
    const usuarioId = document.getElementById('usuario').value;
    const rolId = document.getElementById('rol').value;
    
    const params = new URLSearchParams({
        controlador: 'Opciones',
        metodo: 'mostrarOpcionesPermisos',
        usuario: usuarioId,
        rol: rolId
    });

    fetch('C_Frontal.php?' + params.toString())
        .then(res => res.text())
        .then(html => {
            document.getElementById('capaResultadoBusqueda').innerHTML = html;
        });
}

function togglePermiso(checkBox, permisoId) {
    const usuarioId = document.getElementById('usuario').value;
    const rolId = document.getElementById('rol').value;
    
    const params = new URLSearchParams({
        controlador: 'Opciones',
        metodo: 'togglePermiso',
        permiso: permisoId,
        usuario: usuarioId,
        rol: rolId,
        asignar: checkBox.checked ? 1 : 0
    });

    fetch('C_Frontal.php?' + params.toString())
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                alert('Error al actualizar el permiso');
                checkBox.checked = !checkBox.checked; // Revertir cambio
            }
        });
}

function obtenerVista_EditarCrear(controlador, metodo, destinoId, id = '', params = {}) {
    const parametros = new URLSearchParams({
        controlador: controlador,
        metodo: metodo,
        id: id,
        ...params
    });

    console.log(`🔄 Obteniendo vista: ${controlador}, método: ${metodo}, ID: ${id}`);

    fetch("C_Frontal.php?" + parametros.toString(), { method: "GET" })
        .then(res => res.text())
        .then(html => {
            const destino = document.getElementById(destinoId);
            if (destino) {
                destino.innerHTML = html.trim(); // Inserta el contenido recibido
                console.log(`✅ Se insertó el contenido en #${destinoId}`);

                // *** Solución: Forzar la visibilidad después de cargar ***
                setTimeout(() => {
                    destino.style.display = 'block'; // Mostrar la capa de edición
                }, 100); // Espera 100ms para asegurar que se renderiza
            } else {
                console.error(`⛔ No se encontró el destino #${destinoId}`);
            }
        })
        .catch(err => console.error('❌ Error al obtener la vista:', err));
}


// Función para manejar la creación de una nueva opción
function nuevaOpcion(padreId = null, nivel = 1) {
    obtenerVista_EditarCrear('Opciones', 'getVistaNuevoEditar', 'capaEditarCrear', '', { id_padre: padreId, nivel: nivel });
}

function nuevaOpcionArriba(opcionId) {
    const filaActual = document.querySelector(`[data-id="${opcionId}"]`);
    if (!filaActual) {
        console.error('No se encontró la fila para la opción con ID:', opcionId);
        return;
    }

    const posicionActual = parseInt(filaActual.getAttribute('data-posicion')) || 1; // Fallback a 1 si no se encuentra
    const nuevaPosicion = Math.max(1, posicionActual); // Evita posiciones menores a 1

    obtenerVista_EditarCrear('Opciones', 'getVistaNuevoEditar', `new-option-form-${opcionId}`, '', {
        id_referencia: opcionId,
        posicion: nuevaPosicion,
        nivel: filaActual.getAttribute('data-nivel') || 1,
        ubicacion: 'arriba',
    });
    document.getElementById(`new-option-form-${opcionId}`).style.display = 'flex';
}

function nuevaOpcionDebajo(opcionId) {
    const filaActual = document.querySelector(`[data-id="${opcionId}"]`);
    if (!filaActual) {
        console.error('No se encontró la fila para la opción con ID:', opcionId);
        return;
    }

    const posicionActual = parseInt(filaActual.getAttribute('data-posicion')) || 1; // Fallback a 1 si no se encuentra
    const nuevaPosicion = posicionActual + 1;

    obtenerVista_EditarCrear('Opciones', 'getVistaNuevoEditar', `new-option-form-${opcionId}`, '', {
        id_referencia: opcionId,
        posicion: nuevaPosicion,
        nivel: filaActual.getAttribute('data-nivel') || 1,
        ubicacion: 'debajo',
    });
    document.getElementById(`new-option-form-${opcionId}`).style.display = 'flex';
}

// Función para manejar la edición de una opción
function editarOpcion(opcionId) {
    obtenerVista_EditarCrear('Opciones', 'getVistaNuevoEditar', 'capaEditarCrear', opcionId);
}



// Función para manejar la eliminación de una opción
function eliminarOpcion(opcionId) {
    if (confirm('¿Estás seguro de eliminar esta opción?')) {
        const parametros = new URLSearchParams({
            controlador: 'Opciones',
            metodo: 'eliminarOpcion',
            id: opcionId
        });

        fetch(`C_Frontal.php?${parametros.toString()}`, { method: 'GET' })
            .then(res => res.json())
            .then(data => {
                if (data.correcto === 'S') {
                    alert('Opción eliminada correctamente');
                    location.reload();
                } else {
                    alert('Error al eliminar la opción: ' + data.msj);
                }
            })
            .catch(err => console.error('Error al eliminar la opción:', err.message));
    }
}

// Función para guardar una opción
function guardarOpcion() {
    const formulario = document.getElementById('formularioOpcion');
    const formData = new FormData(formulario);

    const parametros = new URLSearchParams({
        controlador: 'Opciones',
        metodo: 'guardarOpcion'
    });

    fetch(`C_Frontal.php?${parametros.toString()}`, {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(resultado => {
            if (resultado.correcto === 'S') {
                alert('Opción guardada correctamente');
                location.reload();
            } else {
                alert('Error al guardar la opción: ' + resultado.msj);
            }
        })
        .catch(err => console.error('Error al guardar la opción:', err.message));
}


function cancelarEdicion() {
    const capaEditarCrear = document.getElementById('capaEditarCrear');
    if (capaEditarCrear) {
        capaEditarCrear.innerHTML = '';
    }
}

// Función para añadir un nuevo permiso sin recargar la página
function nuevaPermiso(opcionId, botonAñadir) {
    if (!opcionId) {
        console.error('No se proporcionó un ID de opción para añadir un permiso.');
        return;
    }

    if (!botonAñadir || !(botonAñadir instanceof HTMLElement)) {
        console.error('No se proporcionó un botón válido.');
        return;
    }

    const capaEditarCrear = document.getElementById('capaEditarCrear');

    // Obtener la vista de creación del permiso
    obtenerVista_EditarCrear('Permisos', 'getVistaNuevo', 'capaEditarCrear', '', { id_Menu: opcionId });

    let intervalo = setInterval(() => {
        if (capaEditarCrear.innerHTML.trim() !== "") {
            clearInterval(intervalo);
            const rect = botonAñadir.getBoundingClientRect();
            capaEditarCrear.style.top = `${window.scrollY + rect.top}px`;
            capaEditarCrear.style.left = `${window.scrollX + rect.right + 10}px`;
            capaEditarCrear.style.display = 'block';
        }
    }, 100);
}

// Función para editar un permiso sin cerrar el menú
function editarPermiso(permisoId, botonEditar) {
    if (!permisoId) {
        console.error('No se proporcionó un ID de permiso para editar.');
        return;
    }

    if (!botonEditar || !(botonEditar instanceof HTMLElement)) {
        console.error('No se proporcionó un botón válido.');
        return;
    }

    const capaEditarCrear = document.getElementById('capaEditarCrear');

    // Obtener la vista de edición del permiso
    obtenerVista_EditarCrear('Permisos', 'getVistaEditar', 'capaEditarCrear', permisoId);

    let intervalo = setInterval(() => {
        if (capaEditarCrear.innerHTML.trim() !== "") {
            clearInterval(intervalo);
            const rect = botonEditar.getBoundingClientRect();
            capaEditarCrear.style.top = `${window.scrollY + rect.top}px`;
            capaEditarCrear.style.left = `${window.scrollX + rect.right + 10}px`;
            capaEditarCrear.style.display = 'block';
        }
    }, 100);
}

// Función para guardar un permiso sin recargar la página
function guardarPermiso() {
    const formulario = document.getElementById('formularioPermiso');
    const formData = new FormData(formulario);

    fetch('C_Frontal.php?controlador=Permisos&metodo=guardarPermiso', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.correcto === 'S') {
                alert('Permiso guardado correctamente.');

                const permisoId = formulario.querySelector('input[name="id"]').value;
                const idMenu = formulario.querySelector('input[name="id_Menu"]').value;
                const nombre = formulario.querySelector('input[name="nombre"]').value;
                const codigo = formulario.querySelector('input[name="codigo"]').value;

                if (permisoId) {
                    // Actualizar un permiso existente
                    const permisoElemento = document.querySelector(`button[onclick="editarPermiso(${permisoId}, this)"]`).closest('li');
                    if (permisoElemento) {
                        permisoElemento.querySelector('span').textContent = nombre;
                    }
                } else {
                    // Añadir un nuevo permiso
                    const listaPermisos = document.querySelector(`button[onclick="nuevaPermiso(${idMenu}, this)"]`).closest('ul');
                    if (listaPermisos) {
                        const nuevoPermiso = document.createElement('li');
                        nuevoPermiso.innerHTML = `
                            <span>${nombre}</span>
                            <button class="edit-permiso" onclick="editarPermiso(${data.id}, this)">📝</button>
                            <button class="delete-permiso" onclick="eliminarPermiso(${data.id})">🗑️</button>
                        `;
                        listaPermisos.insertBefore(nuevoPermiso, listaPermisos.lastElementChild);
                    }
                }

                // Ocultar la capa de edición después de guardar
                document.getElementById('capaEditarCrear').style.display = 'none';

            } else {
                alert('Error al guardar el permiso: ' + data.msj);
            }
        })
        .catch(err => console.error('Error al guardar el permiso:', err));
}


// Función para eliminar un permiso
function eliminarPermiso(permisoId) {
    if (!permisoId) {
        console.error('No se proporcionó un ID de permiso para eliminar.');
        return;
    }

    if (!confirm('¿Estás seguro de que deseas eliminar este permiso?')) {
        return; // Si el usuario cancela, no hace nada
    }

    const parametros = new URLSearchParams({
        controlador: 'Permisos',
        metodo: 'eliminarPermiso',
        id: permisoId
    });

    fetch(`C_Frontal.php?${parametros.toString()}`, { method: 'GET' })
        .then(res => res.json())
        .then(data => {
            if (data.correcto === 'S') {
                alert('Permiso eliminado correctamente.');
                // Remover el permiso de la interfaz después de eliminarlo
                const permisoElemento = document.querySelector(`button[onclick="eliminarPermiso(${permisoId})"]`).closest('li');
                if (permisoElemento) {
                    permisoElemento.remove();
                }
            } else {
                alert('Error al eliminar el permiso: ' + data.msj);
            }
        })
        .catch(err => console.error('Error al eliminar el permiso:', err));
}


