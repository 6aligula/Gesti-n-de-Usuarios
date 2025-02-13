<?php
require_once 'controladores/Controlador.php';
require_once 'modelos/M_Permisos.php';
require_once 'vistas/Vista.php';

class C_Permisos extends Controlador {
    private $modeloPermisos;

    public function __construct() {
        $this->modeloPermisos = new M_Permisos(); // Modelo para permisos
    }

    // Renderiza la vista para crear un nuevo permiso
    public function getVistaNuevo($datos = []) {
        $id_Menu = $datos['id_Menu'] ?? null; // ID de la opción a la que pertenece el permiso
        Vista::render('vistas/Permisos/V_Permisos_NuevoEditar.php', ['id_Menu' => $id_Menu]);
    }

    // Renderiza la vista para editar un permiso
    public function getVistaEditar($datos = []) {
        $id = $datos['id'] ?? null;
    
        if ($id) {
            $permiso = $this->modeloPermisos->obtenerPermisoPorId($id);
            if ($permiso) {
                // Registro de depuración para verificar los datos enviados a la vista
                error_log("Datos enviados a la vista: " . print_r($permiso, true));
    
                Vista::render('vistas/Permisos/V_Permisos_NuevoEditar.php', $permiso);
            } else {
                echo 'Error: Permiso no encontrado.';
            }
        } else {
            echo 'Error: ID del permiso no proporcionado.';
        }
    }
    

    // Guardar permiso (nuevo o edición)
    public function guardarPermiso($datos = []) {
        $resultado = $this->modeloPermisos->guardarPermiso($datos);
    
        if ($resultado) {
            echo json_encode(['correcto' => 'S', 'msj' => 'Permiso guardado correctamente.']);
        } else {
            echo json_encode(['correcto' => 'N', 'msj' => 'Error al guardar el permiso.']);
        }
    }    

    // Eliminar un permiso
    public function eliminarPermiso($datos = []) {
        $id = $datos['id'] ?? null;

        if ($id) {
            $resultado = $this->modeloPermisos->eliminarPermiso($id);

            if ($resultado) {
                echo json_encode(['correcto' => 'S', 'msj' => 'Permiso eliminado correctamente.']);
            } else {
                echo json_encode(['correcto' => 'N', 'msj' => 'Error al eliminar el permiso.']);
            }
        } else {
            echo json_encode(['correcto' => 'N', 'msj' => 'ID del permiso no proporcionado.']);
        }
    }

    // Listar todos los permisos de un menú
    public function listarPermisosPorMenu($datos = []) {
        $idMenu = $datos['id_Menu'] ?? null;

        if ($idMenu) {
            $permisos = $this->modeloPermisos->obtenerPermisosPorMenu($idMenu);

            if (!empty($permisos)) {
                Vista::render('vistas/Permisos/V_Permisos_Listado.php', ['permisos' => $permisos]);
            } else {
                echo json_encode(['correcto' => 'N', 'msj' => 'No se encontraron permisos para el menú.']);
            }
        } else {
            echo json_encode(['correcto' => 'N', 'msj' => 'ID del menú no proporcionado.']);
        }
    }
}
