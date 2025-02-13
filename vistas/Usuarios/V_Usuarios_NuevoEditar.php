<?php 
    $nombre='';
    $apellido_1='';
    $apellido_2='';
    $sexo='';
    $fecha_Alta=date('Y-m-d');
    $mail='';
    $movil='';
    $login='';
    $pass='';
    $activo='S';
    if (isset($datos['usuario'])){ 
        extract($datos['usuario']);
    }

    $cHombre = $sexo=='H' ? ' checked ': '';
    $cMujer  = $sexo=='M' ? ' checked ': '';
?>
<h2>Añadir Usuario</h2>
<div class="container-fluid" id="capaAñadirUsuario">
    <form id="formularioAñadirUsuario" name="formularioAñadirUsuario">
        <!-- Campo oculto para el ID -->
        <input type="hidden" name="id" value="<?php echo $datos['usuario']['id_Usuario'] ?? ''; ?>">

        <div class="row">
            <div class="form-group col-md-6 col-sm-12">
                <label for="apellido_1">Apellido 1: <span style="color: red;">*</span></label>
                <input type="text" id="apellido_1" name="apellido_1" class="form-control" placeholder="Primer Apellido" value="<?php echo $apellido_1; ?>"/>
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label for="apellido_2">Apellido 2:</label>
                <input type="text" id="apellido_2" name="apellido_2" class="form-control" placeholder="Segundo Apellido" value="<?php echo $apellido_2; ?>"/>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6 col-sm-12">
                <label for="nombre">Nombre: <span style="color: red;">*</span></label>
                <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre" value="<?php echo $nombre; ?>"/>
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label>Sexo: <span style="color: red;">*</span></label>
                <div>
                    <div class="form-check">
                        <input type="radio" id="sexoM" name="sexo" value="H" 
                                <?php echo $cHombre; ?> class="form-check-input"/>
                        <label for="sexoM" class="form-check-label">Hombre</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" id="sexoF" name="sexo" value="M" 
                                <?php echo $cMujer; ?> class="form-check-input"/>
                        <label for="sexoF" class="form-check-label">Mujer</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6 col-sm-12">
                <label for="fechaAlta">Fecha de Alta:</label>
                <input type="date" id="fechaAlta" name="fechaAlta" class="form-control" value="<?php echo $fecha_Alta; ?>"/>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6 col-sm-12">
                <label for="login">Login: <span style="color: red;">*</span></label>
                <input type="text" id="login" name="login" class="form-control" placeholder="Login" value="<?php echo $login; ?>"/>
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label for="mail">Email: <span style="color: red;">*</span></label>
                <input type="email" id="mail" name="mail" class="form-control" placeholder="Email" value="<?php echo $mail; ?>"/>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6 col-sm-12">
                <label for="movil">Móvil:</label>
                <input type="tel" id="movil" name="movil" class="form-control" placeholder="Móvil" value="<?php echo $movil; ?>"/>
            </div>
            <div class="form-group col-md-6 col-sm-12">
                <label for="pass">Contraseña:</label>
                <input type="password" id="pass" name="pass" class="form-control" placeholder="Contraseña" value="<?php echo $pass; ?>"/>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6 col-sm-12">
                <label for="activo">Estado:</label>
                <select id="activo" name="activo" class="form-select">
                    <option value="S" <?php if($activo=='S') echo ' selected '; ?> > Activo </option>
                    <option value="N" <?php if($activo=='N') echo ' selected '; ?> > Inactivo </option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <button type="button" class="btn btn-primary" onclick="guardarUsuario();">
                    Guardar
                </button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('capaEditarCrear').innerHTML = '';">
                    Cancelar
                </button>
                <span id="msjError" name="msjError" style="color: red;"></span>
            </div>
        </div>
    </form>
</div>

