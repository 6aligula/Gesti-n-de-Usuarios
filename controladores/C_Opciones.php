<?php
require_once 'controladores/Controlador.php';
require_once 'modelos/M_Opciones.php';
require_once 'modelos/M_Permisos.php'; // Importa el modelo de permisos
require_once 'vistas/Vista.php';

class C_Opciones extends Controlador {
    private $modeloOpciones;
    private $modeloPermisos; // Declarar propiedad para el modelo de permisos

    public function __construct() {
        $this->modeloOpciones = new M_Opciones(); // Inicializa el modelo de opciones
        $this->modeloPermisos = new M_Permisos(); // Inicializa el modelo de permisos
    }

    public function mostrarOpcionesPermisos($datos) {
        $usuarioId = $datos['usuario'] ?? null;
        $rolId = $datos['rol'] ?? null;
        
        // Si no se han filtrado ni usuario ni rol, se muestra la gestión de opciones
        if (!$usuarioId && !$rolId) {
            $this->mostrarOpciones();
            return;
        }
        
        // Obtener todas las opciones junto con los permisos asociados
        $opciones = $this->modeloOpciones->obtenerOpcionesConPermisos();
        
        // Obtener permisos asignados al usuario o rol seleccionado
        $permisosAsignados = [];
        if ($usuarioId) {
            $permisosAsignados = $this->modeloOpciones->obtenerPermisosUsuario($usuarioId);
        } elseif ($rolId) {
            $permisosAsignados = $this->modeloOpciones->obtenerPermisosRol($rolId);
        }
        
        Vista::render('vistas/Opciones/V_Opciones_Permisos.php', [
            'opciones' => $opciones,
            'permisosAsignados' => $permisosAsignados,
            'modoAsignacion' => true // Este flag lo puedes usar en la vista para ocultar botones de CRUD
        ]);
    }
     
    
    public function togglePermiso($datos) {
        $permisoId = $datos['permiso'] ?? null;
        $usuarioId = $datos['usuario'] ?? null;
        $rolId = $datos['rol'] ?? null;
        $asignar = $datos['asignar'] ?? 0;
        
        $resultado = false;
        if ($usuarioId) {
            $resultado = $this->modeloOpciones->togglePermisoUsuario($usuarioId, $permisoId, $asignar);
        } elseif ($rolId) {
            $resultado = $this->modeloOpciones->togglePermisoRol($rolId, $permisoId, $asignar);
        }
        
        echo json_encode(['success' => $resultado]);
    }

    public function getVistaFiltros($datos = []) {
        // Obtener arrays de usuarios y roles
        $usuarios = $this->modeloOpciones->obtenerUsuarios();
        $roles    = $this->modeloOpciones->obtenerRoles();
    
        // Renderizar la vista pasando los arrays en $datos
        Vista::render('vistas/Opciones/V_Opciones_Filtros.php', [
            'usuarios' => $usuarios,
            'roles'    => $roles
        ]);
    }    

    // Renderiza el formulario para nuevo o editar
    public function getVistaNuevoEditar($datos = []) {
        $id = $datos['id'] ?? null;

        if ($id) {
            $opcion = $this->modeloOpciones->obtenerOpcionPorId($id);

            if ($opcion) {
                $datos = array_merge($datos, $opcion);
            } else {
                $datos['error'] = 'Opción no encontrada';
            }
        } else {
            $idReferencia = $datos['id_referencia'] ?? null;
            $posicion = $datos['posicion'] ?? null;
            $ubicacion = $datos['ubicacion'] ?? null;

            if ($idReferencia && $posicion !== null && $ubicacion) {
                if ($ubicacion === 'arriba') {
                    $datos['posicion'] = $posicion;
                } elseif ($ubicacion === 'debajo') {
                    $datos['posicion'] = $posicion + 1;
                }
            } else {
                $datos['posicion'] = 1;
            }
        }

        Vista::render('vistas/Opciones/V_Opciones_NuevoEditar.php', $datos);
    }

    // Renderiza el listado de opciones
    public function getVistaListadoOpciones($filtros = []) {
        $opciones = $this->modeloOpciones->obtenerOpciones();
        Vista::render('vistas/Opciones/V_Opciones_Listado.php', ['opciones' => $opciones]);
    }

    // Guardar opción
    public function guardarOpcion($datos = []) {
        if (isset($datos['ubicacion']) && ($datos['ubicacion'] === 'arriba' || $datos['ubicacion'] === 'debajo')) {
            // Actualizar posiciones antes de guardar la nueva opción
            $this->actualizarPosiciones($datos['posicion'], $datos['nivel'], $datos['id_padre'] ?? null);
        }

        $respuesta = ['correcto' => 'S', 'msj' => 'Opción guardada correctamente.'];

        $resultado = $this->modeloOpciones->guardarOpcion($datos);
        if (!$resultado) {
            $respuesta['correcto'] = 'N';
            $respuesta['msj'] = 'Error al guardar la opción.';
        }

        echo json_encode($respuesta);
    }

    // Eliminar opción
    public function eliminarOpcion($datos = []) {
        $respuesta = ['correcto' => 'S', 'msj' => 'Opción eliminada correctamente.'];

        $id = $datos['id'] ?? null;
        if ($id && $this->modeloOpciones->eliminarOpcion($id)) {
            echo json_encode($respuesta);
        } else {
            $respuesta['correcto'] = 'N';
            $respuesta['msj'] = 'Error al eliminar la opción.';
            echo json_encode($respuesta);
        }
    }

    public function mostrarOpciones() {
        // Usamos obtenerOpcionesConPermisos del modelo de opciones, no de permisos
        $opcionesMenu = $this->modeloOpciones->obtenerOpcionesConPermisos(); 
        Vista::render('vistas/Opciones/V_Opciones.php', ['opcionesMenu' => $opcionesMenu]);
    }
    
}