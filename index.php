<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Bienvenidos a Nuestro Restaurante</h1>
    </header>
    <nav>
        <ul>
            <li><a href="#login">Login</a></li>
            <li><a href="#sobre">Quiénes Somos</a></li>
            <li><a href="#contacto">Dónde Encontrarnos</a></li>
        </ul>
    </nav>
    <section id="login">
        <h2>Login</h2>
        <form action="login.php" method="post">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>
            <button type="submit">Ingresar</button>
        </form>
    </section>
    <section id="sobre">
        <h2>Quiénes Somos</h2>
        <p>Somos un restaurante.....</p>
    </section>
    <section id="contacto">
        <h2>Dónde Encontrarnos</h2>
        <p>Nos encontramos en la calle....</p>
    </section>
</body>
</html>