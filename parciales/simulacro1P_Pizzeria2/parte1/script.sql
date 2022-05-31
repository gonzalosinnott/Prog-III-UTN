CREATE TABLE ventaPizzasV2 (
  idVenta int NOT NULL AUTO_INCREMENT,
  fechaDeVenta DATE NOT NULL,
  numeroDePedido varchar(50) ,
  email varchar(50) ,
  sabor varchar(25) ,
  tipo varchar(25) ,
  cantidad int(11),
	PRIMARY KEY (idVenta)
);