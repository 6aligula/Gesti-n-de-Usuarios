<?php
require_once 'modelos/Modelo.php';
require_once 'modelos/DAO.php';

class M_Usuarios extends Modelo {
    private $DAO;

    public function __construct() {
        parent::__construct(); // Ejecutar constructor del padre
        $this->DAO = new DAO();
    }

    public function login($datos = array()) {
        $usuario = '';
        $pass = '';
        extract($datos);

        $sql = "SELECT * FROM usuarios
                WHERE login = ? AND pass = MD5(?)";

        $usuarios = $this->DAO->consultaFila($sql, [$usuario, $pass]);
        $id_Usuario = '';

        if ($usuarios) { // Usuario encontrado
            $_SESSION['login'] = $usuario;
            $_SESSION['usuario'] = $usuarios['nombre'];
            $_SESSION['id_Usuario'] = $usuarios['id_Usuario'];
            $id_Usuario = $usuarios['id_Usuario'];
        }

        return $id_Usuario;
    }

    public function buscarUsuarios($filtros = array()) {
    $ftexto = '';
    $factivo = '';
    $id_Usuario = '';
    extract($filtros);

    $sql = "SELECT * FROM usuarios WHERE 1=1";
    $parametros = [];

    // Filtrar por texto (nombre, apellido, email, etc.)
    if (!empty($ftexto)) {
        $aPalabras = explode(' ', $ftexto);
        $sql .= ' AND (1=0'; // Inicia la condición
        foreach ($aPalabras as $palabra) {
            $sql .= " OR (nombre LIKE ? OR apellido_1 LIKE ? OR apellido_2 LIKE ? OR mail LIKE ? OR login LIKE ?)";
            $parametros = array_merge($parametros, array_fill(0, 5, "%$palabra%"));
        }
        $sql .= ')'; // Cerrar paréntesis
    }

    // Filtrar por estado activo/inactivo
    if (isset($factivo) && $factivo !== '') {
        $sql .= " AND activo = ?";
        $parametros[] = $factivo;
    }

    // Filtrar por ID de usuario
    if (isset($id_Usuario) && $id_Usuario !== '') {
        $sql .= " AND id_Usuario = ?";
        $parametros[] = $id_Usuario;
    }

    $sql .= ' ORDER BY apellido_1, apellido_2, nombre, login';

    // Depuración
    error_log("SQL generado: $sql");
    error_log("Parámetros: " . print_r($parametros, true));

    return $this->DAO->consultaMultiple($sql, $parametros);
}


    public function insertarUsuario($datos = array()) {
        $nombre = '';
        $apellido_1 = '';
        $apellido_2 = '';
        $sexo = 'H';
        $fecha_Alta = date('Y-m-d');
        $mail = '';
        $movil = '';
        $login = '';
        $pass = '';
        $activo = 'S';
        extract($datos);
    
        // Calcula el hash MD5 de la contraseña antes de usarla en la consulta
        $hashedPassword = md5($pass);
    
        $sql = "INSERT INTO usuarios (nombre, apellido_1, apellido_2, sexo, fecha_Alta, mail, movil, login, pass, activo)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
        return $this->DAO->insertar($sql, [$nombre, $apellido_1, $apellido_2, $sexo, $fecha_Alta, $mail, $movil, $login, $hashedPassword, $activo]);
    }

    public function actualizarUsuario($datos, $idUsuario) {
        $sql = "UPDATE usuarios 
                SET nombre = ?, 
                    apellido_1 = ?, 
                    apellido_2 = ?, 
                    sexo = ?, 
                    mail = ?, 
                    movil = ?, 
                    login = ?, 
                    activo = ? 
                WHERE id_Usuario = ?";
    
        return $this->DAO->actualizar($sql, [
            $datos['nombre'],
            $datos['apellido_1'],
            $datos['apellido_2'],
            $datos['sexo'],
            $datos['mail'],
            $datos['movil'],
            $datos['login'],
            $datos['activo'],
            $idUsuario
        ]);
    }
      

    public function actualizarEstadoUsuario($id, $estado) {
        $sql = "UPDATE usuarios SET activo = ? WHERE id_Usuario = ?";
        error_log("SQL generado: $sql con parámetros: [$estado, $id]");
        return $this->DAO->actualizar($sql, [$estado, $id]) > 0; // Verificar si se afectaron filas
    }
    

    public static function obtenerPorId($idUsuario) {
        $dao = new DAO();
        $sql = "SELECT * FROM usuarios WHERE id_Usuario = ?";
        return $dao->consultaFila($sql, [$idUsuario]);
    }

    public static function actualizarEstado($idUsuario, $nuevoEstado) {
        $dao = new DAO();
        $sql = "UPDATE usuarios SET activo = ? WHERE id_Usuario = ?";
        return $dao->ejecutarConsulta($sql, [$nuevoEstado, $idUsuario]);
    }

    public function buscarPorLogin($login, $idUsuario = null) {
        $sql = "SELECT * FROM usuarios WHERE login = ?";
        $parametros = [$login];
    
        if ($idUsuario) {
            $sql .= " AND id_Usuario != ?";
            $parametros[] = $idUsuario;
        }
    
        return $this->DAO->consultaFila($sql, $parametros);
    }

    // public function obtenerUsuariosConRoles() {
    //     $sql = "SELECT 
    //                 u.id_Usuario,
    //                 u.nombre,
    //                 u.apellido_1,
    //                 -- más campos si quieres
    //                 GROUP_CONCAT(ru.id_Rol) AS roles_asignados
    //             FROM usuarios u
    //             LEFT JOIN rolesusuarios ru ON u.id_Usuario = ru.id_Usuario
    //             GROUP BY u.id_Usuario";
    
    //     $resultado = $this->DAO->consultaMultiple($sql);
    
    //     // Convertir "1,3,5" a [1,3,5]
    //     foreach ($resultado as &$usuario) {
    //         if ($usuario['roles_asignados']) {
    //             $usuario['roles'] = array_map('intval', explode(',', $usuario['roles_asignados']));
    //         } else {
    //             $usuario['roles'] = [];
    //         }
    //     }
    
    //     return $resultado;
    // }
    public function obtenerRolesPorUsuario($idUsuario) {
        $sql = "SELECT id_Rol FROM rolesusuarios WHERE id_Usuario = ?";
        $roles = $this->DAO->consultaMultiple($sql, [$idUsuario]);
        
        // Convertimos el resultado a un array de enteros
        $rolesArray = array_map(function($row) {
            return (int)$row['id_Rol'];
        }, $roles);
        
        return $rolesArray;
    }
       
    
}
?>
