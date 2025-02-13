<?php
    $getPost=array_merge($_GET, $_POST, $_FILES);

    if(isset($getPost['controlador']) && $getPost['controlador']!=''){
        // Recibido controlador
        $controlador='C_'.$getPost['controlador'];
        if( file_exists('./controladores/'.$controlador.'.php') ){
            // Existe el controlador
            $metodo=$getPost['metodo'];
            $metodo=$getPost['metodo'];
            require_once './controladores/'.$controlador.'.php';
            $objControlador= new $controlador();
            if(method_exists($objControlador, $metodo)){
                $objControlador->$metodo($getPost);
            }else{
                echo 'Error CF-NFC-03'; // No existe el metodo
            }
        }else{
            echo 'Error CF-NFC-02'; // No existe el fichero del controlador
        }

    }else{
        // No recibido controlador
        echo 'Error CF-01';
    }

?>