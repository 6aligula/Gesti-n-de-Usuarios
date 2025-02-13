<?php
require_once 'modelos/Modelo.php';
require_once 'modelos/DAO.php';

class M_Menu extends Modelo {
    private $DAO;

    public function __construct() {
        parent::__construct();
        $this->DAO = new DAO();
    }

    public function obtenerOpcionesMenu() {
        $sql = "SELECT * FROM menu_opciones ORDER BY nivel, id_padre, posicion";
        return $this->DAO->consultaMultiple($sql);
    }
}
?>
