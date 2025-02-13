    <?php
    require_once 'controladores/Controlador.php';
    require_once 'modelos/M_Usuarios.php';
    require_once 'vistas/Vista.php';

    class C_Usuarios extends Controlador{
        private $modelo;

        public function __construct(){
            parent::__construct(); // Ejecutar constructor padre
            $this->modelo = new M_Usuarios();
        }

        public function validarUsuario($datos=array()){
            $id_Usuario=$this->modelo->login($datos);
            return $id_Usuario;
        }

        public function getVistaFiltros($datos=array()){
            Vista::render('vistas/Usuarios/V_Usuarios_Filtros.php');
        }
        public function getVistaNuevoEditar($datos=array()){
            if(!isset($datos['id']) || $datos['id']==''){
                //nuevo
                Vista::render('vistas/Usuarios/V_Usuarios_NuevoEditar.php');
            }else{
                //editando
                $filtros['id_Usuario']=$datos['id'];
                $usuarios=$this->modelo->buscarUsuarios($filtros);
                Vista::render('vistas/Usuarios/V_Usuarios_NuevoEditar.php', 
                                        array('usuario'=>$usuarios[0]));
            }
    
        }

        public function getVistaListadoUsuarios($filtros=array()){
            // var_dump($filtros);
            $usuarios=$this->modelo->buscarUsuarios($filtros);
            Vista::render('vistas/Usuarios/V_Usuarios_Listado.php',
                            array('usuarios'=>$usuarios));
        }
        

        function guardarUsuario($datos = array()) {
            $respuesta['correcto'] = 'S';
            $respuesta['msj'] = 'Usuario guardado correctamente.';
            $respuesta['errores'] = []; // Para almacenar los campos con errores
        
            // Verificar que todos los campos obligatorios estén completos
            $camposObligatorios = ['nombre', 'apellido_1', 'login', 'mail', 'sexo'];
            foreach ($camposObligatorios as $campo) {
                if (empty($datos[$campo])) {
                    $respuesta['correcto'] = 'N';
                    $respuesta['errores'][] = $campo; // Guardar el campo que falta
                }
            }
        
            // Verificar que el email tenga un formato válido
            if (!empty($datos['mail']) && !filter_var($datos['mail'], FILTER_VALIDATE_EMAIL)) {
                $respuesta['correcto'] = 'N';
                $respuesta['errores'][] = 'mail';
            }
        
            // Si hay errores, regresar los nombres de los campos y un mensaje
            if ($respuesta['correcto'] === 'N') {
                $respuesta['msj'] = 'Completa los campos obligatorios (*).';
                echo json_encode($respuesta);
                return;
            }
        
            $idUsuario = $datos['id'] ?? null;
        
            // Verificar si el login ya existe en otro usuario
            $loginExistente = $this->modelo->buscarPorLogin($datos['login'], $idUsuario);
            if ($loginExistente && $loginExistente['id_Usuario'] != $idUsuario) {
                $respuesta['correcto'] = 'N';
                $respuesta['errores'][] = 'login';
                $respuesta['msj'] = 'El login ingresado ya está en uso por otro usuario.';
                echo json_encode($respuesta);
                return;
            }
        
            // Guardar o actualizar el usuario
            if ($idUsuario) {
                // Editar usuario existente
                $resultado = $this->modelo->actualizarUsuario($datos, $idUsuario);
                if (!$resultado) {
                    $respuesta['correcto'] = 'N';
                    $respuesta['msj'] = 'Error al actualizar el usuario.';
                }
            } else {
                // Crear nuevo usuario
                $id = $this->modelo->insertarUsuario($datos);
                if ($id <= 0) {
                    $respuesta['correcto'] = 'N';
                    $respuesta['msj'] = 'Error al crear el usuario.';
                }
            }
        
            echo json_encode($respuesta);
        }                       

        public function cambiarEstado() {
            $idUsuario = $_GET['id'] ?? null;

            if (!$idUsuario) {
                echo json_encode(['correcto' => 'N', 'msj' => 'ID de usuario no proporcionado']);
                return;
            }

            // Obtener el usuario actual y cambiar el estado
            $usuario = M_Usuarios::obtenerPorId($idUsuario); // Método que puedes implementar
            if (!$usuario) {
                echo json_encode(['correcto' => 'N', 'msj' => 'Usuario no encontrado']);
                return;
            }

            $nuevoEstado = ($usuario['activo'] === 'S') ? 'N' : 'S';

            $resultado = M_Usuarios::actualizarEstado($idUsuario, $nuevoEstado);

            if ($resultado) {
                echo json_encode(['correcto' => 'S', 'estado' => $nuevoEstado]);
            } else {
                echo json_encode(['correcto' => 'N', 'msj' => 'Error al cambiar el estado']);
            }
        }
        

    } //Fin clase Usuarios



    ?>