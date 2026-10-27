<?php
// aqui inicio la sesion para verificar si el usuario ya inicio sesion
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gamix | Tienda de Videojuegos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>GAM<span>IX</span></h1>
    </header>

    <nav>
        <a href="#nintendo">Nintendo</a>
        <a href="#play">PlayStation</a>
        <a href="#xbox">Xbox</a>

        <?php if (isset($_SESSION["usuario"])): ?>
            <span class="bienvenida">Bienvenido <?php echo $_SESSION["usuario"]; ?></span>
            <a href="cerrar.php" class="btn-sesion">Cerrar Sesion</a>
        <?php else: ?>
            <a href="registro.php">Registrarse</a>
            <a href="iniciar.php">Iniciar Sesion</a>
        <?php endif; ?>
    </nav>

    <?php // aqui coloco los juegos de nintendo ?>
    <section id="nintendo">
        <h2>Juegos de Nintendo</h2>
        <div>
            <div>
                <img src="img/galaxy.jpg" alt="Super Mario Galaxy">
                <p>Super Mario Galaxy</p>
                <p>$1,200 MXN</p>
                <a href="comprar.php?juego=Super+Mario+Galaxy&precio=1200">Comprar</a>
            </div>
            <div>
                <img src="img/zelda_botw.jpg" alt="The Legend of Zelda: Breath of the Wild">
                <p>The Legend of Zelda: Breath of the Wild</p>
                <p>$1,399 MXN</p>
                <a href="comprar.php?juego=Zelda+Breath+of+the+Wild&precio=1399">Comprar</a>
            </div>
            <div>
                <img src="img/mario_odyssey.jpg" alt="Super Mario Odyssey">
                <p>Super Mario Odyssey</p>
                <p>$1,199 MXN</p>
                <a href="comprar.php?juego=Super+Mario+Odyssey&precio=1199">Comprar</a>
            </div>
            <div>
                <img src="img/mariokart8.jpg" alt="Mario Kart 8 Deluxe">
                <p>Mario Kart 8 Deluxe</p>
                <p>$1,299 MXN</p>
                <a href="comprar.php?juego=Mario+Kart+8+Deluxe&precio=1299">Comprar</a>
            </div>
            <div>
                <img src="img/smash_ultimate.jpg" alt="Super Smash Bros. Ultimate">
                <p>Super Smash Bros. Ultimate</p>
                <p>$1,399 MXN</p>
                <a href="comprar.php?juego=Super+Smash+Bros+Ultimate&precio=1399">Comprar</a>
            </div>
            <div>
                <img src="img/pokemon_scarlet.jpg" alt="Pokémon Escarlata">
                <p>Pokémon Escarlata</p>
                <p>$1,199 MXN</p>
                <a href="comprar.php?juego=Pokemon+Escarlata&precio=1199">Comprar</a>
            </div>
            <div>
                <img src="img/animal_crossing.jpg" alt="Animal Crossing: New Horizons">
                <p>Animal Crossing: New Horizons</p>
                <p>$1,199 MXN</p>
                <a href="comprar.php?juego=Animal+Crossing+New+Horizons&precio=1199">Comprar</a>
            </div>
            <div>
                <img src="img/metroid_dread.jpg" alt="Metroid Dread">
                <p>Metroid Dread</p>
                <p>$1,099 MXN</p>
                <a href="comprar.php?juego=Metroid+Dread&precio=1099">Comprar</a>
            </div>
            <div>
                <img src="img/kirby.jpg" alt="Kirby and the Forgotten Land">
                <p>Kirby and the Forgotten Land</p>
                <p>$1,149 MXN</p>
                <a href="comprar.php?juego=Kirby+and+the+Forgotten+Land&precio=1149">Comprar</a>
            </div>
            <div>
                <img src="img/luigi_mansion3.jpg" alt="Luigi's Mansion 3">
                <p>Luigi's Mansion 3</p>
                <p>$1,199 MXN</p>
                <a href="comprar.php?juego=Luigis+Mansion+3&precio=1199">Comprar</a>
            </div>
        </div>
    </section>

    <?php // aqui coloco los juegos de playstation ?>
    <section id="play">
        <h2>Juegos de PlayStation</h2>
        <div>
            <div>
                <img src="img/gow_ragnarok.jpg" alt="God of War Ragnarök">
                <p>God of War Ragnarök</p>
                <p>$1,499 MXN</p>
                <a href="comprar.php?juego=God+of+War+Ragnarok&precio=1499">Comprar</a>
            </div>
            <div>
                <img src="img/spiderman2.jpg" alt="Marvel's Spider-Man 2">
                <p>Marvel's Spider-Man 2</p>
                <p>$1,599 MXN</p>
                <a href="comprar.php?juego=Spiderman+2&precio=1599">Comprar</a>
            </div>
            <div>
                <img src="img/tlou1.jpg" alt="The Last of Us Part I">
                <p>The Last of Us Part I</p>
                <p>$1,399 MXN</p>
                <a href="comprar.php?juego=The+Last+of+Us+Part+I&precio=1399">Comprar</a>
            </div>
            <div>
                <img src="img/ghost_tsushima.jpg" alt="Ghost of Tsushima">
                <p>Ghost of Tsushima</p>
                <p>$1,199 MXN</p>
                <a href="comprar.php?juego=Ghost+of+Tsushima&precio=1199">Comprar</a>
            </div>
            <div>
                <img src="img/bloodborne.jpg" alt="Bloodborne">
                <p>Bloodborne</p>
                <p>$699 MXN</p>
                <a href="comprar.php?juego=Bloodborne&precio=699">Comprar</a>
            </div>
        </div>
    </section>

    <?php // aqui coloco los juegos de xbox ?>
    <section id="xbox">
        <h2>Juegos de Xbox</h2>
        <div>
            <div>
                <img src="img/halo_infinite.jpg" alt="Halo Infinite">
                <p>Halo Infinite</p>
                <p>$1,199 MXN</p>
                <a href="comprar.php?juego=Halo+Infinite&precio=1199">Comprar</a>
            </div>
            <div>
                <img src="img/forza5.jpg" alt="Forza Horizon 5">
                <p>Forza Horizon 5</p>
                <p>$1,399 MXN</p>
                <a href="comprar.php?juego=Forza+Horizon+5&precio=1399">Comprar</a>
            </div>
            <div>
                <img src="img/gears5.jpg" alt="Gears 5">
                <p>Gears 5</p>
                <p>$799 MXN</p>
                <a href="comprar.php?juego=Gears+5&precio=799">Comprar</a>
            </div>
            <div>
                <img src="img/starfield.jpg" alt="Starfield">
                <p>Starfield</p>
                <p>$1,499 MXN</p>
                <a href="comprar.php?juego=Starfield&precio=1499">Comprar</a>
            </div>
            <div>
                <img src="img/hifi_rush.jpg" alt="Hi-Fi Rush">
                <p>Hi-Fi Rush</p>
                <p>$649 MXN</p>
                <a href="comprar.php?juego=Hi-Fi+Rush&precio=649">Comprar</a>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy;Rodriguez Ugalde Jaime Emmanuel 5°J 0893</p>
    </footer>
</body>
</html>
