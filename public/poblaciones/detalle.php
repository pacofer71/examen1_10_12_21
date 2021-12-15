<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location:../login.php");
}
if(!isset($_GET['id'])){
    header("Location:index.php");
}
$id=$_GET['id'];
require dirname(__DIR__,2)."/vendor/autoload.php";
use Examen\Poblaciones;
$ciudad=(new Poblaciones())->read1($id);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/all.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <title>Detalle poblacion</title>
</head>
<body style="background-color:#7986cb">
<h4 class="mt-2 text-center">Población: <b><?php echo $ciudad->id; ?></b></h4>
<div class="container mt-4">
    <div class="card mx-auto shadow-lg rounded" style="width: 62rem; background-color: #ffffdd">
        <div class="mx-auto">
            <img src="<?php echo "..".$ciudad->imagen; ?>" class="img-thumbnail mt-2" width="450rem" height="450rem" alt="imagen" />
        </div>
        <div class="card-body">
            <h5 class="card-title text-center"><?php echo $ciudad->nombre ?></h5>
            <p class="card-text">Provincia: <a href="index.php?prov=<?php echo $ciudad->provincia_id; ?>" class="py-1 px-4 bg-danger text-light rounded-pill" style="text-decoration:none"><?php echo $ciudad->pnombre ?></a></p>
            <p class="card-text">Población: <b><?php echo $ciudad->poblacion ?> habs.</b></p>
            <p class="card-text">Descripción</p>
            <div class="my-2">
                <textarea class="form-control" rows="5"><?php echo $ciudad->descripcion ?></textarea>
            </div>
           <a href="epoblacion.php?id=<?php echo $id; ?>" class="btn btn-info"><i class="fas fa-edit"></i> Editar</a>
           <a href="../cerrar.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
            <button class="btn btn-primary" onClick='window.history.back()'><i class="fas fa-backward"></i> Volver</button>
        </div>
    </div>
</div>

</body>
</html>
