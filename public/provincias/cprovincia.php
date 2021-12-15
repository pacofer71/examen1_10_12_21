<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location:../login.php");
}
require dirname(__DIR__, 2) . "/vendor/autoload.php";

use Examen\Provincias;
$error=false;
function comprobar(string $nombre, string $string)
{
    global $error;
    if(strlen($nombre)==0){
        $_SESSION[$string]="****Rellene este campo.";
        $error=true;
    }
}

if(isset($_POST['guardar'])){
    $nombre=trim(ucwords($_POST['nombre']));
    $des=trim(ucfirst($_POST['descripcion']));
    comprobar($nombre, "errnombre");
    comprobar($des, "errdes");
    if((new Provincias)->existeNombre($nombre)){
        $error=true;
        $_SESSION['errnombre']="**** Esta provincia Ya está registrada.";
        $_SESSION['nombre']=$nombre;
    }
    if(!$error){
        (new Provincias)->setNombre($nombre)
            ->setDescripcion($des)
            ->create();
        $_SESSION['mensaje']="Provincia Guardada.";
        header("Location:index.php");
    }else{
        header("Location:{$_SERVER['PHP_SELF']}");
    }
}else{

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/all.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <title>Crear Provincia</title>
</head>
<body style="background-color:#7986cb">
<h4 class="mt-2 text-center"><i class="fas fa-plus"></i><i class="fas fa-plus"></i> Nueva Provincia <i
            class="fas fa-plus"></i><i
            class="fas fa-plus"></i></h4>
<div class="container mt-4">
    <div class="mx-auto py-2 px-4 rounded-3 shadow-lg" style="background-color: #6c757d; width:50rem">
    <form name="cp" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
        <div class="mb-3">
            <label for="n" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="n" placeholder="Nombre Provincia" name="nombre" value="<?php echo (isset($_SESSION['nombre'])) ? $_SESSION['nombre'] : ""; ?>" required />
            <?php
            if(isset($_SESSION['errnombre'])){
                echo <<<TXT
                <p class="text-danger p-2"><b>{$_SESSION['errnombre']}</b></p>
                TXT;
                unset($_SESSION['errnombre']);
                if(isset($_SESSION['nombre'])) unset($_SESSION['nombre']);

            }

            ?>
        </div>
        <div class="mb-3">
            <label for="d" class="form-label">Descripción</label>
            <textarea class="form-control" id="d" rows="3" name="descripcion" required></textarea><?php
            if(isset($_SESSION['errdes'])){
                echo <<<TXT
                <p class="text-danger p-2"><b>{$_SESSION['errdes']}</b></p>
                TXT;
                unset($_SESSION['errdes']);

            }
            ?>
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary" name="guardar"><i class="fas fa-save"></i> Guardar</button>
            <button type="reset" class="btn btn-warning"><i class="fas fa-brush"></i> Limpiar</button>
            <a href="../cerrar.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
            <a href="index.php" class="btn btn-success"><i class="fas fa-backward"></i> Volver</a>
        </div>
    </form>
</div>
</div>
</body>
</html>
<?php } ?>