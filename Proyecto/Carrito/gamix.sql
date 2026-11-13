-- aqui creo la base de datos si no existe
CREATE DATABASE IF NOT EXISTS gamix CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- aqui selecciono la base de datos para usarla
USE gamix;

SET NAMES utf8mb4;

-- aqui elimino las tablas si ya existian para evitar duplicados al importar
DROP TABLE IF EXISTS compras;
DROP TABLE IF EXISTS productos;
DROP TABLE IF EXISTS usuarios;

-- aqui creo la tabla para guardar los usuarios registrados
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    direccion VARCHAR(200) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    codigo_postal VARCHAR(10) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    tarjeta_titular VARCHAR(100) DEFAULT '',
    tarjeta_numero VARCHAR(20) DEFAULT '',
    tarjeta_expiracion VARCHAR(10) DEFAULT '',
    tarjeta_cvv VARCHAR(5) DEFAULT '',
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- aqui creo la tabla para guardar los pedidos de compras con sus detalles
CREATE TABLE IF NOT EXISTS compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    folio VARCHAR(20) NOT NULL,
    usuario VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    direccion VARCHAR(200) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    codigo_postal VARCHAR(10) NOT NULL,
    marca VARCHAR(50) NOT NULL,
    plataforma VARCHAR(50) NOT NULL,
    juego VARCHAR(100) NOT NULL,
    cantidad INT DEFAULT 1,
    precio DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    metodo_pago VARCHAR(50) NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- aqui creo la tabla con el catalogo de productos
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    imagen VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- aqui inserto los 10 usuarios oficiales con sus datos completos y actualizados
INSERT INTO usuarios (nombre, telefono, direccion, ciudad, codigo_postal, fecha_nacimiento, correo, password, tarjeta_titular, tarjeta_numero, tarjeta_expiracion, tarjeta_cvv) VALUES
('Rodriguez Ugalde Jaime Emmanuel', '6562324061', 'Calle Francisco Villa #1723', 'Cd. Juárez, Chih.', '32055', '2005-04-12', 'a.24308051280893@cbtis128.edu.mx', '12Ro57', 'Jaime Emmanuel Rodriguez Ugalde', '4152313456789012', '08/29', '742'),
('Rodriguez Martinez Oliver Orlando', '6561234567', 'Av. Tecnológico #1200, Col. Oasis', 'Cd. Juárez, Chih.', '32000', '2006-05-15', 'a.24308051280887@cbtis128.edu.mx', 'ssasdw2', 'Oliver Orlando Rodríguez Martínez', '4152876512340987', '07/29', '904'),
('Melissa Hernández Gómez', '3398765432', 'Calle Hidalgo #42, Col. San Juan', 'Guadalajara, Jal.', '44100', '2006-08-20', 'melissa.hg@hotmail.com', 'mely2024', 'Melissa Hernández Gómez', '5204123456789013', '11/28', '318'),
('Carlos Eduardo Ramírez Nava', '4771239874', 'Blvd. Adolfo López Mateos #812', 'León, Gto.', '37000', '2004-11-05', 'carlos.ramirez@gmail.com', 'carlosgamer99', 'Carlos Eduardo Ramírez Nava', '4912876543210984', '05/27', '529'),
('Valeria Sofía Martínez Soto', '8187654321', 'Calle 5 de Mayo #304, Col. Obrera', 'Monterrey, N.L.', '64000', '2005-02-18', 'valeria.mtz@outlook.com', 'valepassword', 'Valeria Sofía Martínez Soto', '5520987654321095', '09/30', '641'),
('Diego Alejandro Torres Cruz', '5598761234', 'Av. Revolución #540, Int. 3B, Col. Del Valle', 'Ciudad de México, CDMX', '03100', '2006-09-30', 'diego.torres@yahoo.com', 'diegotorres123', 'Diego Alejandro Torres Cruz', '4023123498765436', '12/28', '185'),
('Ana Paula Morales Delgado', '2221234567', 'Av. Juárez #1905, Col. La Paz', 'Puebla, Pue.', '72160', '2005-07-22', 'ana.morales@gmail.com', 'anapau2025', 'Ana Paula Morales Delgado', '4532890123456781', '03/29', '452'),
('Fernando Gabriel Castro Ortiz', '6649876543', 'Paseo de los Héroes #9211, Zona Río', 'Tijuana, B.C.', '22010', '2004-03-14', 'fernando.castro@hotmail.com', 'fergamer2024', 'Fernando Gabriel Castro Ortiz', '5412789012345672', '10/28', '819'),
('Mariana Elizabeth Silva Ruiz', '9991238765', 'Calle 60 #342, Centro Histórico', 'Mérida, Yuc.', '97000', '2005-10-11', 'mariana.silva@outlook.com', 'marisilva88', 'Mariana Elizabeth Silva Ruiz', '4152901234567893', '06/30', '367'),
('Sebastián Arturo López Mendoza', '4428765432', 'Blvd. Bernardo Quintana #4010', 'Querétaro, Qro.', '76160', '2006-01-25', 'sebastian.lopez@gmail.com', 'sebaslopez99', 'Sebastián Arturo López Mendoza', '5301890123456784', '04/29', '625');

-- aqui inserto las compras de ejemplo vinculadas a las cuentas
INSERT INTO compras (folio, usuario, correo, telefono, direccion, ciudad, codigo_postal, marca, plataforma, juego, cantidad, precio, subtotal, metodo_pago, fecha) VALUES
('GMX-1001', 'Rodriguez Ugalde Jaime Emmanuel', 'a.24308051280893@cbtis128.edu.mx', '6562324061', 'Calle Francisco Villa #1723', 'Cd. Juárez, Chih.', '32055', 'Nintendo', 'Nintendo', 'Super Mario Odyssey', 1, 1199.00, 1199.00, 'Tarjeta', '2026-10-15 14:32:10'),
('GMX-1002', 'Melissa Hernández Gómez', 'melissa.hg@hotmail.com', '3398765432', 'Calle Hidalgo #42, Col. San Juan', 'Guadalajara, Jal.', '44100', 'PlayStation', 'PlayStation', 'Marvel\'s Spider-Man 2', 1, 1599.00, 1599.00, 'Transferencia', '2026-10-16 11:20:45'),
('GMX-1003', 'Carlos Eduardo Ramírez Nava', 'carlos.ramirez@gmail.com', '4771239874', 'Blvd. Adolfo López Mateos #812', 'León, Gto.', '37000', 'Xbox', 'Xbox', 'Halo Infinite', 1, 1199.00, 1199.00, 'Efectivo en OXXO', '2026-10-17 18:05:12'),
('GMX-1004', 'Valeria Sofía Martínez Soto', 'valeria.mtz@outlook.com', '8187654321', 'Calle 5 de Mayo #304, Col. Obrera', 'Monterrey, N.L.', '64000', 'Nintendo', 'Nintendo', 'Mario Kart 8 Deluxe', 1, 1299.00, 1299.00, 'Tarjeta', '2026-10-18 09:45:00'),
('GMX-1005', 'Diego Alejandro Torres Cruz', 'diego.torres@yahoo.com', '5598761234', 'Av. Revolución #540, Int. 3B, Col. Del Valle', 'Ciudad de México, CDMX', '03100', 'PlayStation', 'PlayStation', 'God of War Ragnarök', 1, 1499.00, 1499.00, 'Tarjeta', '2026-10-19 16:15:30'),
('GMX-1006', 'Rodriguez Ugalde Jaime Emmanuel', 'a.24308051280893@cbtis128.edu.mx', '6562324061', 'Calle Francisco Villa #1723', 'Cd. Juárez, Chih.', '32055', 'Xbox', 'Xbox', 'Forza Horizon 5', 1, 1399.00, 1399.00, 'Transferencia', '2026-10-20 20:10:05'),
('GMX-1007', 'Rodriguez Martinez Oliver Orlando', 'a.24308051280887@cbtis128.edu.mx', '6561234567', 'Av. Tecnológico #1200, Col. Oasis', 'Cd. Juárez, Chih.', '32000', 'Nintendo', 'Nintendo', 'The Legend of Zelda: Breath of the Wild', 1, 1399.00, 1399.00, 'Tarjeta', '2026-10-21 13:50:22'),
('GMX-1008', 'Ana Paula Morales Delgado', 'ana.morales@gmail.com', '2221234567', 'Av. Juárez #1905, Col. La Paz', 'Puebla, Pue.', '72160', 'Nintendo', 'Nintendo', 'Super Smash Bros. Ultimate', 1, 1399.00, 1399.00, 'Tarjeta', '2026-10-22 17:35:40'),
('GMX-1009', 'Fernando Gabriel Castro Ortiz', 'fernando.castro@hotmail.com', '6649876543', 'Paseo de los Héroes #9211, Zona Río', 'Tijuana, B.C.', '22010', 'Steam', 'Steam', 'Cyberpunk 2077', 1, 1199.00, 1199.00, 'Transferencia', '2026-10-23 12:00:15'),
('GMX-1010', 'Mariana Elizabeth Silva Ruiz', 'mariana.silva@outlook.com', '9991238765', 'Calle 60 #342, Centro Histórico', 'Mérida, Yuc.', '97000', 'Nintendo', 'Nintendo', 'Animal Crossing: New Horizons', 1, 1199.00, 1199.00, 'Tarjeta', '2026-10-24 15:25:50');

-- aqui inserto todos los videojuegos del catalogo divididos por plataforma
INSERT INTO productos (nombre, categoria, precio, imagen) VALUES
('Super Mario Galaxy', 'Nintendo', 1200.00, 'img/galaxy.jpg'),
('The Legend of Zelda: Breath of the Wild', 'Nintendo', 1399.00, 'img/zelda_botw.jpg'),
('Super Mario Odyssey', 'Nintendo', 1199.00, 'img/mario_odyssey.jpg'),
('Mario Kart 8 Deluxe', 'Nintendo', 1299.00, 'img/mariokart8.jpg'),
('Super Smash Bros. Ultimate', 'Nintendo', 1399.00, 'img/smash_ultimate.jpg'),
('Pokémon Escarlata', 'Nintendo', 1199.00, 'img/pokemon_scarlet.jpg'),
('Animal Crossing: New Horizons', 'Nintendo', 1199.00, 'img/animal_crossing.jpg'),
('Metroid Dread', 'Nintendo', 1099.00, 'img/metroid_dread.jpg'),
('Kirby and the Forgotten Land', 'Nintendo', 1149.00, 'img/kirby.jpg'),
('Luigi\'s Mansion 3', 'Nintendo', 1199.00, 'img/luigi_mansion3.jpg'),

('God of War Ragnarök', 'PlayStation', 1499.00, 'img/gow_ragnarok.jpg'),
('Marvel\'s Spider-Man 2', 'PlayStation', 1599.00, 'img/spiderman2.jpg'),
('The Last of Us Part I', 'PlayStation', 1399.00, 'img/tlou1.jpg'),
('Ghost of Tsushima', 'PlayStation', 1199.00, 'img/ghost_tsushima.jpg'),
('Bloodborne', 'PlayStation', 699.00, 'img/bloodborne.jpg'),
('Horizon Forbidden West', 'PlayStation', 1399.00, 'img/horizon_fw.jpg'),
('Gran Turismo 7', 'PlayStation', 1299.00, 'img/gran_turismo7.jpg'),
('Uncharted: Legado de Ladrones', 'PlayStation', 999.00, 'img/uncharted4.jpg'),
('Ratchet & Clank: Rift Apart', 'PlayStation', 1199.00, 'img/ratchet_clank.jpg'),
('Demon\'s Souls', 'PlayStation', 1399.00, 'img/demons_souls.jpg'),

('Halo Infinite', 'Xbox', 1199.00, 'img/halo_infinite.jpg'),
('Forza Horizon 5', 'Xbox', 1399.00, 'img/forza5.jpg'),
('Gears 5', 'Xbox', 799.00, 'img/gears5.jpg'),
('Starfield', 'Xbox', 1499.00, 'img/starfield.jpg'),
('Hi-Fi Rush', 'Xbox', 649.00, 'img/hifi_rush.jpg'),
('Senua\'s Saga: Hellblade II', 'Xbox', 1199.00, 'img/hellblade2.jpg'),
('Sea of Thieves', 'Xbox', 799.00, 'img/sea_of_thieves.jpg'),
('Microsoft Flight Simulator', 'Xbox', 1399.00, 'img/flight_simulator.jpg'),
('Psychonauts 2', 'Xbox', 899.00, 'img/psychonauts2.jpg'),
('State of Decay 2', 'Xbox', 699.00, 'img/state_of_decay2.jpg'),

('Cyberpunk 2077', 'Steam', 1199.00, 'img/cyberpunk2077.jpg'),
('Elden Ring', 'Steam', 1299.00, 'img/elden_ring.jpg'),
('Baldur\'s Gate 3', 'Steam', 1299.00, 'img/baldur_gate3.jpg'),
('Black Myth: Wukong', 'Steam', 1099.00, 'img/black_myth.jpg'),
('Red Dead Redemption 2', 'Steam', 1299.00, 'img/red_dead2.jpg'),
('Half-Life: Alyx', 'Steam', 999.00, 'img/half_life_alyx.jpg'),
('Hollow Knight', 'Steam', 249.00, 'img/hollow_knight.jpg'),
('Helldivers 2', 'Steam', 799.00, 'img/helldivers2.jpg'),
('Palworld', 'Steam', 499.00, 'img/palworld.jpg'),
('Resident Evil 4', 'Steam', 899.00, 'img/resident_evil4.jpg');
