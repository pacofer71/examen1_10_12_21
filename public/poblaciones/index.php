<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location:../login.php");
}
require dirname(__DIR__, 2) . "/vendor/autoload.php";

use Examen\Poblaciones;

$stmt= (!isset($_GET['prov'])) ? (new Poblaciones)->read() : (new Poblaciones)->setProvinciaId($_GET['prov'])->read();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/all.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <title>Poblaciones</title>
</head>

<body style="background-color:#7986cb">
<h4 class="mt-2 text-center text-light"><i class="fas fa-city"></i> Gestión de Poblaciones
    <?php
    if(isset($_GET['prov'])) {
        echo "(Cod Prov: {$_GET['prov']})";
    }
    ?>
    <i class='fas fa-city'></i>
</h4>
<div class="container mt-4">
    <?php
    if (isset($_SESSION['mensaje'])) {
        echo <<<TXT
        <div class="alert alert-info">
        <strong>Info! </strong> {$_SESSION['mensaje']}
        </div>
        TXT;

        unset($_SESSION['mensaje']);
    }
    ?>
    <a href="cpoblacion.php" class="btn btn-secondary"><i class="fas fa-plus"></i> Nueva</a>
    <a href="../" class="btn btn-success"><i class="fas fa-home"></i> Inicio</a>
    <a href="../cerrar.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
    <?php
    if(isset($_GET['prov'])) {
        echo "<a href='index.php' class='btn btn-success'><i class='fas fa-city'></i> Ver Todas</a>";
    }
    ?>
    <table class="table table-light table-striped mt-2">
        <thead>
        <tr>
            <th scope="col">Detalle</th>
            <th scope="col">Nombre</th>
            <th scope="col">Provincia</th>
            <th scope="col">Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php
        while($item = $stmt->fetch(PDO::FETCH_OBJ)) {
            $pob=serialize($item);
            echo <<<TXT
                <tr>
                    <th scope="row"><a href="detalle.php?id={$item->id}" class="btn btn-info"><i class="fas fa-info"></i></a></th>
                    <td>{$item->nombre}</td>
                    <td class="text-wrap" style="color:#4c4444">{$item->pnombre}</td>
                    <td class="text-nowrap">
                    <form name="a" method='POST' action="bpoblacion.php" class="form-inline">
                    <input name="obj" value='$pob' type="hidden" />
                    <a href="epoblacion.php?id={$item->id}" class="btn btn-warning"><i class="fas fa-edit"></i></a>
                    <button class="btn btn-danger" type="submit" onclick="return confirm('¿Borrar localidad?')"><i class="fas fa-trash"></i></button>
                    
                    </form>
                    </td>
                </tr>
                TXT;
        }
        ?>
        </tbody>
    </table>


</div>
</body>

</html>