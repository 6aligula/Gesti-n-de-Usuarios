<?php
require_once 'modelos/DAO.php';

class M_Permisos {
    private $DAO;

    public function __construct() {
        $this->DAO = new DAO(); 
    }

    // Obtener permisos de un menú específico
    public function obtenerPermisosPorMenu($idMenu) {
        $query = "SELECT * FROM permisos WHERE id_Menu = ?";
        $parametros = [$idMenu];
        return $this->DAO->consultaMultiple($query, $parametros);
    }

    // Obtener todos los permisos
    public function obtenerTodosLosPermisos() {
        $query = "SELECT * FROM permisos ORDER BY id_Menu";
        return $this->DAO->consultaMultiple($query);
    }

    // Guardar un permiso (actualizar/insertar)
    public function guardarPermiso($datos) {
        $permiso = $datos['nombre'] ?? null; // Nombre del permiso
        $id_Menu = $datos['id_Menu'] ?? null; // ID del menú al que pertenece
        $codigo = $datos['codigo'] ?? null; // Código del permiso

        if (!$permiso || !$id_Menu || !$codigo) {
            error_log("Error: Datos incompletos para guardar el permiso.");
            return false;
        }

        if (!empty($datos['id'])) {
            // Actualizar permiso existente
            $query = "UPDATE permisos SET permiso = ?, codigo_Permiso = ? WHERE id = ?";
            $parametros = [$permiso, $codigo, $datos['id']];
            return $this->DAO->ejecutarConsulta($query, $parametros);
        } else {
            // Insertar nuevo permiso
            $query = "INSERT INTO permisos (permiso, id_Menu, codigo_Permiso) VALUES (?, ?, ?)";
            $parametros = [$permiso, $id_Menu, $codigo];
            return $this->DAO->insertar($query, $parametros);
        }
    }

    // Eliminar un permiso por su ID
    public function eliminarPermiso($id) {
        $query = "DELETE FROM permisos WHERE id = ?";
        $parametros = [$id];
        return $this->DAO->ejecutarConsulta($query, $parametros);
    }

    // Obtener un permiso específico por su ID
    public function obtenerPermisoPorId($id) {
        $query = "SELECT * FROM permisos WHERE id = ?";
        $parametros = [$id];
        return $this->DAO->consultaFila($query, $parametros);
    }
}
