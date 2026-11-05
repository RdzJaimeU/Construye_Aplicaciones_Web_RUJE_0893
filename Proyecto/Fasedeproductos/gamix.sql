-- aqui creo la base de datos si no existe
CREATE DATABASE IF NOT EXISTS gamix;

-- aqui selecciono la base de datos para usarla
USE gamix;

-- aqui creo la tabla para guardar los usuarios registrados
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    direccion VARCHAR(200) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- aqui creo la tabla para guardar los pedidos de compras
CREATE TABLE IF NOT EXISTS compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(100) NOT NULL,
    direccion VARCHAR(200) NOT NULL,
    marca VARCHAR(50) NOT NULL,
    juego VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    metodo_pago VARCHAR(50) NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- aqui creo la tabla con el catalogo de productos
CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    imagen VARCHAR(100) NOT NULL
);

-- aqui inserto los usuarios de prueba con sus datos completos
INSERT INTO usuarios (nombre, telefono, direccion, fecha_nacimiento, correo, password) VALUES
('Jaime Emmanuel Rodríguez Ugalde', '5512345678', 'Av. Juárez #105, Col. Centro, CDMX', '2005-04-12', 'jaime.rodriguez@gmail.com', 'jaime123'),
('Melissa Hernández Gómez', '3398765432', 'Calle Hidalgo #42, Col. San Juan, Guadalajara, Jal.', '2006-08-20', 'melissa.hg@hotmail.com', 'mely2024'),
('Carlos Eduardo Ramírez Nava', '4771239874', 'Blvd. Adolfo López Mateos #812, León, Gto.', '2004-11-05', 'carlos.ramirez@gmail.com', 'carlosgamer99'),
('Valeria Sofía Martínez Soto', '8187654321', 'Calle 5 de Mayo #304, Col. Obrera, Monterrey, N.L.', '2005-02-18', 'valeria.mtz@outlook.com', 'valepassword'),
('Diego Alejandro Torres Cruz', '5598761234', 'Av. Revolución #540, Int. 3B, Col. Del Valle, CDMX', '2006-09-30', 'diego.torres@yahoo.com', 'diegotorres123'),
('Rodriguez Martinez Oliver Orlando', '6561234567', 'Av. Tecnológico #1200, Col. Oasis, Cd. Juárez, Chih.', '2006-05-15', 'a.24308051280887@cbtis128.edu.mx', 'ssasdw2'),
('Rodriguez Ugalde Jaime Emmanuel', '6567891234', 'Calle Zaragoza #450, Col. Oasis, Cd. Juárez, Chih.', '2005-04-12', 'a.24308051280893@cbtis128.edu.mx', '12Ro57');

-- aqui inserto las compras de ejemplo realizadas
INSERT INTO compras (usuario, direccion, marca, juego, precio, metodo_pago, fecha) VALUES
('Jaime Emmanuel Rodríguez Ugalde', 'Av. Juárez #105, Col. Centro, CDMX', 'Nintendo', 'Super Mario Odyssey', 1199.00, 'Tarjeta', '2026-10-15 14:32:10'),
('Melissa Hernández Gómez', 'Calle Hidalgo #42, Col. San Juan, Guadalajara, Jal.', 'PlayStation', 'Marvel\'s Spider-Man 2', 1599.00, 'Transferencia', '2026-10-16 11:20:45'),
('Carlos Eduardo Ramírez Nava', 'Blvd. Adolfo López Mateos #812, León, Gto.', 'Xbox', 'Halo Infinite', 1199.00, 'Efectivo en OXXO', '2026-10-17 18:05:12'),
('Valeria Sofía Martínez Soto', 'Calle 5 de Mayo #304, Col. Obrera, Monterrey, N.L.', 'Nintendo', 'Mario Kart 8 Deluxe', 1299.00, 'Tarjeta', '2026-10-18 09:45:00'),
('Diego Alejandro Torres Cruz', 'Av. Revolución #540, Int. 3B, Col. Del Valle, CDMX', 'PlayStation', 'God of War Ragnarök', 1499.00, 'Tarjeta', '2026-10-19 16:15:30'),
('Jaime Emmanuel Rodríguez Ugalde', 'Av. Juárez #105, Col. Centro, CDMX', 'Xbox', 'Forza Horizon 5', 1399.00, 'Transferencia', '2026-10-20 20:10:05'),
('Melissa Hernández Gómez', 'Calle Hidalgo #42, Col. San Juan, Guadalajara, Jal.', 'Nintendo', 'The Legend of Zelda: Breath of the Wild', 1399.00, 'Tarjeta', '2026-10-21 13:50:22'),
('Carlos Eduardo Ramírez Nava', 'Blvd. Adolfo López Mateos #812, León, Gto.', 'PlayStation', 'Ghost of Tsushima', 1199.00, 'Efectivo en OXXO', '2026-10-22 17:35:40'),
('Valeria Sofía Martínez Soto', 'Calle 5 de Mayo #304, Col. Obrera, Monterrey, N.L.', 'Nintendo', 'Super Smash Bros. Ultimate', 1399.00, 'Transferencia', '2026-10-23 12:00:15'),
('Diego Alejandro Torres Cruz', 'Av. Revolución #540, Int. 3B, Col. Del Valle, CDMX', 'Xbox', 'Gears 5', 799.00, 'Tarjeta', '2026-10-24 15:25:50');

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
