<?php
class Vista {
    public static function render($vista, $datos = []) {
        extract($datos);
        require $vista;
    }
}
?>
