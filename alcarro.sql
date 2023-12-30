CREATE DATABASE alcarro;

USE alcarro;

CREATE TABLE IF NOT EXISTS usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(70) UNIQUE NOT NULL,
    password VARCHAR(70) NOT NULL
);

CREATE TABLE IF NOT EXISTS cliente (
    id_cli INT(4) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    nome VARCHAR(25) NOT NULL);

CREATE TABLE IF NOT EXISTS marca (
    id_marca INT(4) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    nome VARCHAR(25) NOT NULL UNIQUE);

CREATE TABLE IF NOT EXISTS carro (
    codi CHAR(6) NOT NULL PRIMARY KEY,
    nome VARCHAR(25) NOT NULL,
    tipo VARCHAR(15) NOT NULL,
    id_marca INT(4) AUTO_INCREMENT,
    FOREIGN KEY (id_marca) REFERENCES marca(id_marca));

CREATE TABLE IF NOT EXISTS vendedor (
    id_vendedor INT(4) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    nome VARCHAR(25) NOT NULL);

CREATE TABLE IF NOT EXISTS aluguel (
    id_aluguel INT(6) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    data DATETIME NOT NULL,
    id_cli INT(4) NOT NULL,
    codi CHAR(6) NOT NULL,
    pagamento VARCHAR(12) NOT NULL,
    id_vendedor INT(4),
    FOREIGN KEY (codi) REFERENCES carro(codi),
    FOREIGN KEY (id_cli) REFERENCES cliente(id_cli),
    FOREIGN KEY (id_vendedor) REFERENCES vendedor(id_vendedor),
    UNIQUE KEY (data, codi));


CREATE VIEW vProxAluguel AS 
    SELECT id_aluguel, DATE_FORMAT(data, "%e/%c/%Y") AS data_c, 
    DATE_FORMAT(data, "%k:%i") AS hora, cliente.nome AS cliente, 
    carro.nome AS carro, marca.nome AS marca_nome, pagamento, vendedor.nome AS nome_vendedor, 
    TIMESTAMPDIFF(HOUR, NOW(), data) AS dif 
    FROM aluguel 
    INNER JOIN cliente ON aluguel.id_cli = cliente.id_cli 
    INNER JOIN carro ON aluguel.codi = carro.codi 
    INNER JOIN marca ON carro.id_marca = marca.id_marca
    INNER JOIN vendedor ON aluguel.id_vendedor = vendedor.id_vendedor 
    WHERE data > NOW()
    ORDER BY data, carro;


CREATE VIEW vClientesPorNome AS
    SELECT * FROM cliente ORDER BY nome;

CREATE VIEW vCarrosPorNome AS 
    SELECT * FROM carro ORDER BY nome;

CREATE VIEW vPagamentos AS 
    SELECT DISTINCT pagamento FROM aluguel ORDER BY pagamento;


DELIMITER $$

CREATE PROCEDURE spAluguelPorId (IN id INT(6))
BEGIN
    SELECT cliente.nome AS cliente, carro.nome AS carro FROM aluguel
    INNER JOIN cliente ON aluguel.id_cli = cliente.id_cli 
    INNER JOIN carro ON aluguel.codi = carro.codi
    WHERE id_aluguel = id;    
END $$

CREATE PROCEDURE spIncluiCliente (IN cli VARCHAR(25), OUT id INT(4))
BEGIN
    INSERT INTO cliente (nome) VALUES (cli);
    SELECT id_cli FROM cliente WHERE nome = cli;
END $$

CREATE PROCEDURE spIncluiCarro (IN codi CHAR(6), IN nome VARCHAR(25), IN tipo VARCHAR(15), IN marca INT(4))
BEGIN
    INSERT INTO carro (codi, nome, tipo, id_marca) VALUES (codi, nome, tipo, marca);
END $$

CREATE PROCEDURE spIncluiAluguel (IN data VARCHAR(20), IN cliente INT(4), IN carro CHAR(6), IN pagamento VARCHAR(12), IN vendedor_id INT(4))
BEGIN
    INSERT INTO aluguel (data, id_cli, codi, pagamento, id_vendedor) 
    VALUES (STR_TO_DATE(data, '%Y-%m-%d %H:%i'), cliente, carro, pagamento, vendedor_id);
END $$

CREATE PROCEDURE spCancelaAluguel (IN id INT(6))
BEGIN
    DELETE FROM aluguel WHERE id_aluguel = id;
END $$ 

CREATE PROCEDURE spAlteraAluguel (IN id INT(6), IN data_c VARCHAR(20))
BEGIN
    UPDATE aluguel SET data = STR_TO_DATE(data_c, '%Y-%m-%d %H:%i') WHERE id_aluguel = id;
END $$

DELIMITER ;


INSERT INTO marca (nome) VALUES ('Toyota'), ('Honda'), ('BMW'), ('Mercedes'), ('Chevrolet');


INSERT INTO vendedor (nome) VALUES 
('John Doe'),('Jane Smith'),('Alice Johnson'),('Bob Brown'),
('Michael Scott'), ('Jim Halpert'), ('Pam Beesly'), ('Dwight Schrute'), 
('Angela Martin'), ('Kevin Malone'), ('Meredith Palmer'), ('Kelly Kapoor'), 
('Ryan Howard'), ('Oscar Martinez'), ('Stanley Hudson'), ('Phyllis Vance'), 
('Toby Flenderson'), ('Creed Bratton'), ('Darryl Philbin'), ('Erin Hannon'), 
('Gabe Lewis'), ('Holly Flax'), ('Jan Levinson'), ('David Wallace'), 
('Karen Filippelli'), ('Nellie Bertram'), ('Pete Miller'), ('Clark Green'), 
('Hank Tate'), ('Roy Anderson'), ('Charles Miner'), ('Jo Bennett'), 
('Robert California'), ('Deangelo Vickers');



INSERT INTO cliente (nome) VALUES 
('Robert Redford'),('Meryl Streep'),('Tom Hanks'),('Natalie Portman'),
('Brad Pitt'), ('George Clooney'), ('Julia Roberts'), ('Morgan Freeman'),
('Johnny Depp'), ('Scarlett Johansson'), ('Leonardo DiCaprio'), ('Cate Blanchett'),
('Matt Damon'), ('Nicole Kidman'), ('Robert De Niro'), ('Charlize Theron'),
('Tom Cruise'), ('Amy Adams'), ('Denzel Washington'), ('Kate Winslet'),
('Hugh Jackman'), ('Jennifer Lawrence'), ('Will Smith'), ('Emma Stone'),
('Russell Crowe'), ('Natalie Portman'), ('Ben Affleck'), ('Sandra Bullock'),
('Christian Bale'), ('Reese Witherspoon'), ('Anne Hathaway'), ('Jessica Chastain'),
('Matthew McConaughey'), ('Angelina Jolie');


INSERT INTO carro (codi, nome, tipo, id_marca) VALUES 
('CR001', 'Corolla', 'Sedan', 1), ('CR002', 'Civic', 'Sedan', 2),
('CR003', 'X5', 'SUV', 3), ('CR004', 'C-Class', 'Sedan', 4), 
('CR005', 'Camaro', 'Sports', 5), ('CR006', 'Mustang', 'Sports', 6), 
('CR007', 'Altima', 'Sedan', 7), ('CR008', 'Golf', 'Hatchback', 8), 
('CR009', 'A3', 'Sedan', 9), ('CR010', 'Sonata', 'Sedan', 10), 
('CR011', 'F-150', 'Pickup', 6), ('CR012', 'Rogue', 'SUV', 7), 
('CR013', 'Passat', 'Sedan', 8), ('CR014', 'A4', 'Sedan', 9), 
('CR015', 'Elantra', 'Sedan', 10), ('CR016', 'Explorer', 'SUV', 6), 
('CR017', 'Focus', 'Hatchback', 6), ('CR018', 'Titan', 'Pickup', 7), 
('CR019', 'Maxima', 'Sedan', 7), ('CR020', 'Beetle', 'Hatchback', 8),
('CR021', 'Tiguan', 'SUV', 8), ('CR022', 'Q5', 'SUV', 9),
('CR023', 'Q7', 'SUV', 9), ('CR024', 'Tucson', 'SUV', 10), 
('CR025', 'Santa Fe', 'SUV', 10);


INSERT INTO aluguel (data, id_cli, codi, pagamento, id_vendedor) VALUES 
('2023-11-18 10:00', 1, 'CR001', 'Credito', 1), ('2023-11-20 14:00', 2, 'CR003', 'Debito', 2),
('2023-11-22 12:00', 3, 'CR005', 'Seguro', 3), ('2023-11-24 16:00', 4, 'CR002', 'Credito', 4),
('2023-11-26 09:00', 5, 'CR006', 'Credito', 5), ('2023-11-28 13:00', 6, 'CR007', 'Debito', 6),
('2023-11-30 11:00', 7, 'CR008', 'Seguro', 7), ('2023-12-02 15:00', 8, 'CR009', 'Credito', 8),
('2023-12-04 10:00', 9, 'CR010', 'Credito', 9), ('2023-12-06 14:00', 10, 'CR011', 'Debito', 10),
('2023-12-08 12:00', 11, 'CR012', 'Seguro', 11), ('2023-12-10 16:00', 12, 'CR013', 'Credito', 12);

INSERT INTO marca (nome) VALUES 
('Ford'), ('Nissan'), ('Volkswagen'), ('Audi'), ('Hyundai'), 
('Kia'), ('Porsche'), ('Subaru'), ('Mazda'), ('Jaguar'), 
('Land Rover'), ('Volvo'), ('Tesla'), ('Fiat'), ('Jeep'), 
('Dodge'), ('Chrysler'), ('Lincoln'), ('Cadillac'), ('Buick'), 
('GMC'), ('Ram'), ('Mini'), ('Mitsubishi'), ('Infiniti'), 
('Acura'), ('Alfa Romeo'), ('Genesis'), ('Suzuki'), ('Saab');