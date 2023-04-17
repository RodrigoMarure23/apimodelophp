/*crear las tablas*/
CREATE TABLE clientes (
    id INT  PRIMARY KEY ,
    nombre VARCHAR(50)
);

CREATE TABLE productos (
    id INT PRIMARY KEY,
    nombre VARCHAR(50),
    precio DECIMAL(10, 2)
);

CREATE TABLE ventas (
    id INT auto_increment PRIMARY KEY,
    fecha DATE,
    id_cliente INT,
    total DECIMAL(10, 2),
    FOREIGN KEY (id_cliente) REFERENCES clientes(id)
);

CREATE TABLE detalles_venta (
    id_venta INT,
    id_producto INT,
    cantidad INT,
    precio_unitario DECIMAL(10, 2),
    PRIMARY KEY (id_venta, id_producto),
    FOREIGN KEY (id_venta) REFERENCES ventas(id),
    FOREIGN KEY (id_producto) REFERENCES productos(id)
);

/* poblar las bases con ejemplos para verificar el correcto funcionamiento de la BD */
/*  Tabla Clientes */

INSERT INTO clientes (id, nombre)
VALUES
(1, 'Juan Pérez'),
(2, 'María González'),
(3, 'Pedro Rodríguez'),
(4, 'Ana Martínez'),
(5, 'José Sánchez');

/*  Tabla Productos */
INSERT INTO productos (id, nombre, precio)
VALUES
(1, 'Leche', 20.50),
(2, 'Pan blanco', 10.75),
(3, 'Jabón de baño', 15.20),
(4, 'Huevo', 22.90),
(5, 'Papel higiénico', 25.80);

/*  Tabla Ventas */
INSERT INTO ventas (id, fecha, id_cliente, total)
VALUES
(1, '2023-04-01', 1, 41.0),
(2, '2023-04-02', 2, 47.45),
(3, '2023-04-03', 3, 149.0);

/*  Tabla Detalles venta */

INSERT INTO detalles_venta (id_venta, id_producto, cantidad, precio_unitario)
VALUES
(1,1,2,20.50),
(2,2,3,10.75),
(2,3,1,15.20),
(3,4,2,22.90),
(3,5,4,25.80);




