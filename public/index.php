<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location:login.php");
}
require dirname(__DIR__) . "/vendor/autoload.php";

use Examen\{Provincias, Poblaciones};

(new Provincias)->crearProvincias(15);
(new Poblaciones())->crearPoblciones(100);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <title>Examen Index</title>
</head>

<body style="background-color:#7986cb">
    <h4 class="mt-2 text-center text-light"><i class="fas fa-home"></i> INICIO <i class="fas fa-home"></i></h4>
    <div class="container mt-4">
        <div class="d-flex justify-content-around">
            <a href="provincias/" class="btn btn-success"><i class="fas fa-cogs"></i> Gestionar Provincias</a>
            <a href="poblaciones/" class="btn btn-success"><i class="fas fa-city"></i> Gestionar Poblaciones</a>
        </div>
        <div class="d-flex justify-content-center mt-4">
            <img src="img/alandalus.jpg" class="img-thumbnail mt-2" width="400rem" height="400rem" alt="imagen" />
        </div>
        <div class=" mt-4 d-flex justify-content-center">
            <a href="cerrar.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
        </div>
    </div>
</body>

</html>