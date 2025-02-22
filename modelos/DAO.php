<?php
define('HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('USER', getenv('DB_USER') ?: 'root');
define('PASS', getenv('DB_PASSWORD') ?: '');
define('DB', getenv('DB_NAME') ?: 'di24');

class DAO
{
    private $conexion;
    private $error;

    public function __construct()
    {
        $this->conexion = new mysqli(HOST, USER, PASS, DB);
        if ($this->conexion->connect_errno) {
            die('Error de conexión: ' . $this->conexion->connect_error);
        }
        $this->error = '';
    }

    // Método para consultas SELECT con parámetros preparados
    public function consultaFila($sql, $parametros = [])
    {
        $stmt = $this->conexion->prepare($sql);
        if ($parametros) {
            $tipos = str_repeat('s', count($parametros)); // Suponiendo que todos los parámetros son strings
            $stmt->bind_param($tipos, ...$parametros);
        }
        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        $stmt->close();
        return $fila;
    }

    // Método para consultas INSERT con parámetros preparados
    public function insertar($sql, $parametros = [])
    {
        try {
            $stmt = $this->conexion->prepare($sql);
            if ($parametros) {
                $tipos = str_repeat('s', count($parametros));
                $stmt->bind_param($tipos, ...$parametros);
            }
            $stmt->execute();
            $ultimoId = $stmt->insert_id; // Obtiene el ID del último registro insertado
            $stmt->close();
            return $ultimoId; // Retorna el ID
        } catch (Exception $e) {
            error_log("Error en inserción: " . $e->getMessage());
            return false;
        }
    }

    // Método para consultas UPDATE o DELETE con parámetros preparados
    public function actualizar($sql, $parametros = [])
    {
        try {
            $stmt = $this->conexion->prepare($sql);
            if ($parametros) {
                $tipos = str_repeat('s', count($parametros));
                $stmt->bind_param($tipos, ...$parametros);
            }
            $stmt->execute();
            $filasAfectadas = $stmt->affected_rows; // Obtiene el número de filas afectadas
            $stmt->close();
            return $filasAfectadas; // Retorna el número de filas afectadas
        } catch (Exception $e) {
            error_log("Error en actualización: " . $e->getMessage());
            return false;
        }
    }

    // Método para obtener múltiples filas (para consultas SELECT)
    public function consultaMultiple($sql, $parametros = [])
    {
        $stmt = $this->conexion->prepare($sql);
        if ($parametros) {
            $tipos = str_repeat('s', count($parametros));
            $stmt->bind_param($tipos, ...$parametros);
        }
        $stmt->execute();
        $resultado = $stmt->get_result();
        $filas = [];
        while ($fila = $resultado->fetch_assoc()) {
            $filas[] = $fila;
        }
        $stmt->close();
        return $filas;
    }

    // Método para cerrar la conexión
    public function cerrarConexion()
    {
        $this->conexion->close();
    }

    // Método para consultas INSERT, UPDATE o DELETE con parámetros preparados
    public function ejecutarConsulta($sql, $parametros = [])
    {
        $stmt = $this->conexion->prepare($sql);
        if ($parametros) {
            $tipos = str_repeat('s', count($parametros));
            $stmt->bind_param($tipos, ...$parametros);
        }
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }
}
