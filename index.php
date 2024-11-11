<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Restaurante - Sistema de gestión">
    <title>Restaurante - Login</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1a237e;
            --secondary-color: #3949ab;
            --accent-color: #7986cb;
        }
        
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                        url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3') !important;
            background-size: cover !important;
            background-position: center !important;
            background-attachment: fixed !important;
        }

        .navbar {
            background: linear-gradient(to right, #1a237e, #3949ab) !important;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: linear-gradient(to right, #1a237e, #3949ab);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(to right, #3949ab, #7986cb);
            transform: translateY(-2px);
        }

        .btn-outline-light {
            border: 2px solid rgba(255, 255, 255, 0.8);
        }

        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(121, 134, 203, 0.25);
        }

        .footer {
            background: linear-gradient(to right, #1a237e, #3949ab) !important;
            color: white !important;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .input-group-text {
            background: linear-gradient(to right, #1a237e, #3949ab);
            color: white;
            border: none;
        }

        .navbar .nav-link {
            margin: 0.25rem;
            border-radius: 0.5rem;
            transition: all 0.3s;
            padding: 0.5rem 1rem !important;
        }
        .navbar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            transform: translateY(-1px);
        }
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: rgba(0,0,0,0.1);
                padding: 1rem;
                border-radius: 8px;
                margin-top: 0.5rem;
            }
            .navbar .nav-link {
                text-align: left;
                margin: 0.25rem 0;
                display: flex;
                align-items: center;
            }
            .navbar .nav-link i {
                width: 1.5rem;
                text-align: center;
                margin-right: 0.5rem;
            }
        }
        
        /* Nuevos estilos */
        .card {
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }
        
        .btn-primary {
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        @media (max-width: 576px) {
            .card {
                margin: 0 10px;
            }
            
            .card-body {
                padding: 1.5rem;
            }
            
            .btn {
                padding: 0.6rem;
            }
        }
        
        .nav-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
            width: 100%;
            margin-top: 10px;
        }
        
        .nav-buttons .btn {
            padding: 8px 16px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 140px;
            justify-content: center;
        }
        
        @media (max-width: 576px) {
            .navbar-brand {
                width: 100%;
                justify-content: center;
                margin-bottom: 10px;
            }
            .nav-buttons {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Restaurar navbar -->
    <header>
        <nav class="navbar navbar-dark bg-primary py-2 flex-column">
            <div class="container flex-column">
                <a class="navbar-brand d-flex align-items-center" href="#">
                    <i class="fas fa-utensils me-2"></i>
                    <span>Restaurante Champiñon</span>
                </a>
                <div class="nav-buttons">
                    <a class="btn btn-outline-light" href="quienes_somos.html">
                        <i class="fas fa-users"></i>
                        <span>Quiénes Somos</span>
                    </a>
                    <a class="btn btn-outline-light" href="donde_encontrarnos.html">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Ubicación</span>
                    </a>
                </div>
            </div>
        </nav>
    </header>
    
    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <h2 class="card-title text-center mb-4">
                            <i class="fas fa-utensils fs-1 text-primary mb-3 d-block"></i>
                            Bienvenido al Portal
                        </h2>
                        <form action="login.php" method="post" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="usuario" name="usuario" required>
                                </div>
                                <div class="invalid-feedback">Por favor, ingrese su usuario.</div>
                            </div>
                            <div class="mb-3">
                                <label for="contrasena" class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                                </div>
                                <div class="invalid-feedback">Por favor, ingrese su contraseña.</div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-sign-in-alt me-2"></i>Ingresar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted">© 2024 Restaurante. Todos los derechos reservados.</span>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (() => {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>
</html>