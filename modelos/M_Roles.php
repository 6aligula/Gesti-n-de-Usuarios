<?php
require_once 'modelos/Modelo.php';
require_once 'modelos/DAO.php';

class M_Roles extends Modelo {

    private $DAO;

    public function __construct() {
        parent::__construct(); // Ejecutar constructor del padre
        $this->DAO = new DAO(); // Inicializamos la conexión a la base de datos
    }

    public function obtenerTodos() {
        $sql = "SELECT id, nombre FROM roles ORDER BY nombre";
        return $this->DAO->consultaMultiple($sql);
    }

    public function obtenerPorId($id) {
        $sql = "SELECT id, nombre FROM roles WHERE id = ?";
        return $this->DAO->consultaFila($sql, [$id]);
    }

    public function guardarRol($datos) {
        if (empty($datos['id'])) {
            // Nuevo rol
            $sql = "INSERT INTO roles (nombre) VALUES (?)";
            return $this->DAO->ejecutarConsulta($sql, [$datos['nombre']]);
        } else {
            // Actualizar rol existente
            $sql = "UPDATE roles SET nombre = ? WHERE id = ?";
            return $this->DAO->ejecutarConsulta($sql, [$datos['nombre'], $datos['id']]);
        }
    }

    public function eliminarRol($id) {
        // No permitir eliminar el rol Administrador
        $rol = $this->obtenerPorId($id);
        if ($rol && $rol['nombre'] === 'Administrador') {
            return false;
        }
        
        $sql = "DELETE FROM roles WHERE id = ?";
        return $this->DAO->ejecutarConsulta($sql, [$id]);
    }

    public function asignarRolAUsuario($usuarioId, $rolId) {
        // Verificar si ya existe
        $sqlCheck = "SELECT COUNT(*) as total FROM rolesusuarios WHERE id_Usuario=? AND id_Rol=?";
        $existe = $this->DAO->consultaFila($sqlCheck, [$usuarioId, $rolId]);
    
        if ($existe && $existe['total'] > 0) {
            // Devolver un indicador para que el controlador sepa que ya estaba asignado
            return ['success' => false, 'reason' => 'EXISTE'];
        }
    
        // Insert normal
        $sql = "INSERT INTO rolesusuarios (id_Usuario, id_Rol) VALUES (?, ?)";
        $res = $this->DAO->ejecutarConsulta($sql, [$usuarioId, $rolId]);
        return $res
            ? ['success' => true]
            : ['success' => false, 'reason' => 'ERROR'];
    }
    
    
    public function quitarRolAUsuario($usuarioId, $rolId) {
        $sql = "DELETE FROM rolesusuarios WHERE id_Usuario = ? AND id_Rol = ?";
        $resultado = $this->DAO->ejecutarConsulta($sql, [$usuarioId, $rolId]);
        return $resultado;
    }    
    
}