-- Si la tabla roles ya existe pero no tiene la columna "nombre",
-- puedes agregarla (nota: MariaDB 10.4+ permite usar IF NOT EXISTS en ADD COLUMN)
ALTER TABLE roles 
  ADD COLUMN IF NOT EXISTS nombre VARCHAR(100) NOT NULL;

-- En caso de que la tabla no exista, la crea
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

-- Insertar algunos roles de prueba
-- Usamos INSERT IGNORE para no duplicar si ya existen registros con clave única.
INSERT IGNORE INTO roles (nombre) VALUES 
    ('Administrador'),
    ('Usuario'),
    ('Editor');

-- Tabla de asignación de permisos a usuarios
CREATE TABLE IF NOT EXISTS permisosusuarios (
    id_Usuario INT,
    id_Permiso INT,
    PRIMARY KEY (id_Usuario, id_Permiso),
    CONSTRAINT fk_permisosusuarios_usuario FOREIGN KEY (id_Usuario)
        REFERENCES usuarios(id_Usuario) ON DELETE CASCADE,
    CONSTRAINT fk_permisosusuarios_permiso FOREIGN KEY (id_Permiso)
        REFERENCES permisos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Tabla de asignación de permisos a roles
CREATE TABLE IF NOT EXISTS permisosroles (
    id_Rol INT,
    id_Permiso INT,
    PRIMARY KEY (id_Rol, id_Permiso),
    CONSTRAINT fk_permisosroles_rol FOREIGN KEY (id_Rol)
        REFERENCES roles(id) ON DELETE CASCADE,
    CONSTRAINT fk_permisosroles_permiso FOREIGN KEY (id_Permiso)
        REFERENCES permisos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

ALTER TABLE rolesusuarios 
    ADD PRIMARY KEY (id_Usuario, id_Rol);
