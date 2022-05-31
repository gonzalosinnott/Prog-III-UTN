-- TP 01 SQL Sinnott Segura Gonzalo - 3° C

-- Creacion Tabla: Usuarios (id autoincrement ,nombre,apellido, clave,mail,fecha_de_registro,localidad )

CREATE TABLE Usuarios (
    id int NOT NULL AUTO_INCREMENT,
    nombre varchar(255) NOT NULL,
    apellido varchar(255) NOT NULL,
    clave int NOT NULL,
    mail varchar(255) NOT NULL,
    fecha_de_registro DATE NOT NULL,
	localidad varchar(255) NOT NULL,
	PRIMARY KEY (id)
);

--  Tabla Usuarios

ALTER TABLE Usuarios AUTO_INCREMENT=101;

-- Valores tabla Usuarios

INSERT INTO Usuarios VALUES (101, 'Esteban' , 'Madou' , 2345 , 'dkantor0@example.com' , '2021-01-07' , 'Quilmes');
INSERT INTO Usuarios VALUES (102, 'German' , 'Gerram' , 1234 , 'ggerram1@hud.gov' , '2020-05-08' , 'Berazategui');
INSERT INTO Usuarios VALUES (103, 'Deloris' , 'Fosis' , 5678 , 'bsharpe2@wisc.edu' , '2020-11-28' , 'Avellaneda');
INSERT INTO Usuarios VALUES (104, 'Brok' , 'Neiner' , 4567 , 'bblazic3@desdev.cn' , '2020-12-08' , 'Quilmes');
INSERT INTO Usuarios VALUES (105, 'Garrick' , 'Brent' , 6789 , 'gbrent4@theguardian.com' , '2020-12-17' , 'Moron');
INSERT INTO Usuarios VALUES (106, 'Bili' , 'Baus' , 0123 , 'bhoff5@addthis.com' , '2020-11-27' , 'Moreno');


-- Creacion Tabla:Producto (id autoincremental,código_de_barra (6 cifras ),nombre ,tipo, stock,precio,fecha_de_creación,fecha_de_modificacion )

CREATE TABLE Producto (
    id int NOT NULL AUTO_INCREMENT,
    código_de_barra int NOT NULL,
    nombre varchar(255) NOT NULL,
    tipo varchar(255) NOT NULL,
    stock int NOT NULL,
    precio DECIMAL(13, 2) NOT NULL,
    fecha_de_creacion DATE NOT NULL,
    fecha_de_modificacion DATE NOT NULL,
	PRIMARY KEY (id)
);

-- Indice Tabla Productos

ALTER TABLE Producto AUTO_INCREMENT=1001;

-- Valores tabla Productos

INSERT INTO Producto VALUES (1001, 77900361, 'Westmacott', 'liquido', 33, 15.87, '2021-02-09', '2020-09-26');
INSERT INTO Producto VALUES (1002, 77900362, 'Spirit', 'solido', 45, 69.74, '2020-09-18', '2020-04-14');
INSERT INTO Producto VALUES (1003, 77900363, 'Newgrosh', 'polvo', 14, 68.19, '2020-11-29', '2021-02-11');
INSERT INTO Producto VALUES (1004, 77900364, 'McNickle', 'polvo', 19, 53.51, '2020-11-28', '2020-04-17');
INSERT INTO Producto VALUES (1005, 77900365, 'Hudd', 'solido', 68, 26.56, '2020-12-19', '2020-06-19');
INSERT INTO Producto VALUES (1006, 77900366, 'Schrader', 'polvo', 17, 96.54, '2020-08-02', '2020-04-18');
INSERT INTO Producto VALUES (1007, 77900367, 'Bachellier', 'solido', 59, 69.17, '2021-01-30', '2020-06-07');
INSERT INTO Producto VALUES (1008, 77900368, 'Fleming', 'solido', 38, 66.77, '2020-10-26', '2020-10-03');
INSERT INTO Producto VALUES (1009, 77900369, 'Hurry', 'solido', 44, 43.01, '2020-07-04', '2020-05-30');
INSERT INTO Producto VALUES (1010, 77900310, 'Krauss', 'polvo', 73, 35.73, '2021-03-03', '2020-08-30');

-- Creacion Tabla:Venta (id autoincremental,id_producto ,id_usuario, cantidad,fecha_de_venta, )

CREATE TABLE Venta (
    id_producto int NOT NULL,
    id_usuario int NOT NULL,
    cantidad int NOT NULL,
    fecha_de_venta DATE NOT NULL,
	FOREIGN KEY (id_producto) REFERENCES Producto(id),
	FOREIGN KEY (id_usuario) REFERENCES Usuarios(id)
);

-- Valores tabla Venta

INSERT INTO Venta VALUES (1001, 101, 2, '2020-07-19');
INSERT INTO Venta VALUES (1008, 102, 3, '2020-08-16');
INSERT INTO Venta VALUES (1007, 102, 4, '2021-01-24');
INSERT INTO Venta VALUES (1006, 103, 5, '2021-01-14');
INSERT INTO Venta VALUES (1003, 104, 6, '2021-03-20');
INSERT INTO Venta VALUES (1005, 105, 7, '2021-02-22');
INSERT INTO Venta VALUES (1003, 104, 6, '2020-12-02');
INSERT INTO Venta VALUES (1003, 106, 6, '2020-06-10');
INSERT INTO Venta VALUES (1002, 106, 6, '2021-02-04');
INSERT INTO Venta VALUES (1001, 106, 1, '2020-05-17');

----------------------------------------------------------------------------------------
------------------                  QUERIES                            -----------------
----------------------------------------------------------------------------------------

-- 1. Obtener los detalles completos de todos los usuarios, ordenados alfabéticamente.
SELECT * FROM Usuarios
    ORDER BY apellido, nombre ASC

-- 2. Obtener los detalles completos de todos los productos líquidos.
SELECT * FROM Producto
    WHERE tipo = 'liquido';

-- 3. Obtener todas las compras en los cuales la cantidad esté entre 6 y 10 inclusive.
SELECT * FROM Venta
    WHERE cantidad > 5 AND cantidad <11;

-- 4. Obtener la cantidad total de todos los productos vendidos.
SELECT SUM(cantidad) as total
    FROM Venta;

-- 5. Mostrar los primeros 3 números de productos que se han enviado.
SELECT Venta.id_producto as vendidos FROM Venta
    ORDER BY Venta.fecha_de_venta ASC
    LIMIT 3;

-- 6. Mostrar los nombres del usuario y los nombres de los productos de cada venta.
SELECT  U.nombre, P.nombre
    FROM usuarios AS U
    JOIN venta AS V
    ON U.id = V.id_usuario
    JOIN producto AS P
    ON P.id = V.id_producto;

-- 7. Indicar el monto (cantidad * precio) por cada una de las ventas.
SELECT SUM(Venta.cantidad * Producto.precio) as monto
    FROM Producto INNER JOIN Venta 
    ON Producto.id = Venta.id_producto
    GROUP BY Venta.id_producto

-- 8. Obtener la cantidad total del producto 1003 vendido por el usuario 104.
SELECT SUM(Venta.cantidad) as total 
    FROM Venta 
    WHERE Venta.id_producto = 1003 AND Venta.id_usuario = 104

-- 9. Obtener todos los números de los productos vendidos por algún usuario de ‘Avellaneda’.
SELECT Venta.id_producto FROM Venta 
    INNER JOIN Usuarios
    ON Venta.id_usuario = Usuarios.id
    WHERE Usuarios.localidad = 'Avellaneda';

-- 10. Obtener los datos completos de los usuarios cuyos nombres contengan la letra ‘u’.
SELECT * FROM Usuarios 
    WHERE Usuarios.nombre LIKE '%u%'

-- 11. Traer las ventas entre junio del 2020 y febrero 2021.
SELECT * FROM Venta 
    WHERE Venta.fecha_de_venta BETWEEN  "2020-06-01" AND "2021-02-28"

-- 12. Obtener los usuarios registrados antes del 2021.
SELECT * FROM Usuarios
    WHERE Usuarios.fecha_de_registro < '2021-01-01'

-- 13. Agregar el producto llamado ‘Chocolate’, de tipo Sólido y con un precio de 25,35.
INSERT INTO Producto VALUES (1011,45235431,"Chocolate","solido",100,25.35,"2021-02-09","2020-09-26");

-- 14. Insertar un nuevo usuario .
INSERT INTO Usuarios VALUES (107, 'Gonzalo','Sinnott Segura',1989,'gonzalo.sinnott@gmail.com','1989/10/30','Alejandro Korn');

-- 15. Cambiar los precios de los productos de tipo sólido a 66,60.
UPDATE Producto
    SET precio = 66.60
    WHERE tipo = 'solido'; 

-- 16. Cambiar el stock a 0 de todos los productos cuyas cantidades de stock sean menores a 20 inclusive.
UPDATE Producto
    SET stock = 0
    WHERE stock < 21;

-- 17. Eliminar el producto número 1010.
DELETE FROM Producto
    WHERE Producto.id = 1010;

-- 18. Eliminar a todos los usuarios que no han vendido productos.
DELETE FROM Usuarios
    WHERE Usuarios.id NOT IN (SELECT Venta.id_usuario FROM Venta)