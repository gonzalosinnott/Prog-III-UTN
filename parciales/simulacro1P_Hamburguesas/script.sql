CREATE TABLE ventaHamburguesas (
  idVenta int NOT NULL AUTO_INCREMENT,
  fechaDeVenta DATE NOT NULL,
  numeroDePedido varchar(50) ,
  email varchar(50) ,
  nombre varchar(25) ,
  tipo varchar(25) ,
  cantidad int(11),
	PRIMARY KEY (idVenta)
);