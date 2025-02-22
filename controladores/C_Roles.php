<?php
require_once 'controladores/Controlador.php';
require_once 'modelos/M_Roles.php';
require_once 'vistas/Vista.php';

class C_Roles extends Controlador {
    private $modelo;

    public function __construct() {
        parent::__construct();
        $this->modelo = new M_Roles();
    }

    public function getVistaNuevo() {
        Vista::render('vistas/Roles/V_Roles_NuevoEditar.php');
    }

    public function getVistaEditar($datos = []) {
        $rol = $this->modelo->obtenerPorId($datos['id']);
        Vista::render('vistas/Roles/V_Roles_NuevoEditar.php', ['rol' => $rol]);
    }

    public function guardarRol($datos = []) {
        $resultado = $this->modelo->guardarRol($datos);
        echo json_encode([
            'correcto' => $resultado ? 'S' : 'N',
            'msj' => $resultado ? 'Rol guardado correctamente' : 'Error al guardar el rol'
        ]);
    }

    public function eliminarRol($datos = []) {
        $resultado = $this->modelo->eliminarRol($datos['id']);
        echo json_encode([
            'correcto' => $resultado ? 'S' : 'N',
            'msj' => $resultado ? 'Rol eliminado correctamente' : 'Error al eliminar el rol'
        ]);
    }

    public function asignarRol($datos = []) {
        $resultado = $this->modelo->asignarRolAUsuario($datos['usuarioId'], $datos['rolId']);
    
        if ($resultado['success']) {
            echo json_encode([
                'correcto' => 'S',
                'msj'      => 'Rol asignado correctamente'
            ]);
        } else {
            if ($resultado['reason'] === 'EXISTE') {
                echo json_encode([
                    'correcto' => 'N',
                    'msj'      => 'El usuario ya tiene este rol asignado.'
                ]);
            } else {
                echo json_encode([
                    'correcto' => 'N',
                    'msj'      => 'Error al asignar rol (falló el INSERT).'
                ]);
            }
        }
    }    
    
    public function quitarRol($datos = []) {
        $usuarioId = $datos['usuarioId'];
        $rolId = $datos['rolId'];
        // Aquí llamas al modelo para hacer el DELETE
        $ok = $this->modelo->quitarRolAUsuario($usuarioId, $rolId);
        
        echo json_encode([
            'correcto' => $ok ? 'S' : 'N',
            'msj' => $ok ? 'Rol quitado correctamente' : 'Error al quitar rol'
        ]);
    }
    
}