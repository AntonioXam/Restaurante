<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Restaurante</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Inicio <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Menú</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Reservas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contacto</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Bienvenido a nuestro Restaurante</h1>
        <p>Este es un ejemplo de plantilla Bootstrap para tu proyecto.</p>
        <!-- Aquí puedes agregar más contenido -->
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <h2>Nuestro Menú</h2>
            <p>Descubre la variedad de platos que ofrecemos, preparados con los ingredientes más frescos y de la mejor calidad.</p>
            <ul>
                <li>Entrantes</li>
                <li>Platos principales</li>
                <li>Postres</li>
                <li>Bebidas</li>
            </ul>
        </div>
        <div class="col-md-4">
            <h2>Reservas</h2>
            <p>Reserva tu mesa con antelación para disfrutar de una experiencia gastronómica única.</p>
            <form>
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control" id="nombre" placeholder="Tu nombre">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="Tu email">
                </div>
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" class="form-control" id="telefono" placeholder="Tu teléfono">
                </div>
                <div class="form-group">
                    <label for="fecha">Fecha</label>
                    <input type="date" class="form-control" id="fecha">
                </div>
                <div class="form-group">
                    <label for="hora">Hora</label>
                    <input type="time" class="form-control" id="hora">
                </div>
                <button type="submit" class="btn btn-primary">Reservar</button>
            </form>
        </div>
    </div>
</div>