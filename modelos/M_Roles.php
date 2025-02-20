<?php
require_once 'modelos/DAO.php';

class M_Roles {

    private $DAO;

    public function __construct() {
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
}