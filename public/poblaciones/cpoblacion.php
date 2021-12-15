<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location:../login.php");
}
require dirname(__DIR__, 2) . "/vendor/autoload.php";

use Examen\{Provincias, Poblaciones};
$provincias=(new Provincias)->read();

$error=false;

function comprobar(string $nombre, string $string)
{
    global $error;
    if(strlen($nombre)==0){
        $_SESSION[$string]="****Rellene este campo.";
        $error=true;
    }
}

function isImagen($tipo){

    $tiposBuenos=[
        'image/jpeg',
        'image/bmp',
        'image/png',
        'image/webp',
        'image/gif',
        'image/svg-xml',
        'image/x-icon'
    ];
    return in_array($tipo, $tiposBuenos);
}

if(isset($_POST['guardar'])){
    $imagen = "/img/poblaciones/default.jpg";
    $nombre=trim(ucwords($_POST['nombre']));
    $des=trim(ucfirst($_POST['descripcion']));
    $poblacion=$_POST['poblacion'];

    comprobar($nombre, "errnombre");
    comprobar($des, "errdes");

    if(is_uploaded_file($_FILES['imagen']['tmp_name'])){
        if(isImagen($_FILES['imagen']['type'])){
            $nombreImg=uniqid()."_".$_FILES['imagen']['name'];
            if(!move_uploaded_file($_FILES['imagen']['tmp_name'], dirname(__DIR__)."/img/poblaciones/".$nombreImg)){
                $_SESSION['errimg']="No se pudo guardar la imagen";
                $error=true;
            }
            else{
                $imagen="/img/poblaciones/$nombreImg";
            }

        }
        else{
            $_SESSION['errimg']="**** El campo debe ser de tipo imagen.";
            $error=true;
        }
    }
    if(!$error){
        (new Poblaciones)->setNombre($nombre)
            ->setDescripcion($des)
            ->setImagen($imagen)
            ->setProvinciaId($_POST['provincia_id'])
            ->setPoblacion($poblacion)
            ->create();
        $_SESSION['mensaje']="Población Guardada.";
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
        <title>Crear Poblacion</title>
    </head>
    <body style="background-color:#7986cb">
    <h4 class="mt-2 text-center"><i class="fas fa-plus"></i><i class="fas fa-plus"></i> Nueva Población <i
            class="fas fa-plus"></i><i
            class="fas fa-plus"></i></h4>
    <div class="container mt-4">
        <div class="mx-auto py-2 px-4 rounded-3 shadow-lg" style="background-color: #6c757d; width:50rem">
            <form name="cp" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="n" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="n" placeholder="Nombre Poblacion" name="nombre" value="<?php echo (isset($_SESSION['nombre'])) ? $_SESSION['nombre'] : ""; ?>" required />
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
                <div class="mb-3"h
                    <label for="pob" class="form-label">Número habs.</label>
                    <input type="number" name="poblacion" step="1" min="5" class="form-control" id="pob" required />
                </div>

                <div class="mb-3">
                    <label for="prov" class="form-label">Provincia</label>
                    <select name="provincia_id" class="form-control" id="prov">
                        <?php
                        foreach ($provincias as $item){
                            echo "<option value={$item->id}>{$item->nombre}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="ima" class="form-label">Imagen</label>
                    <input class="form-control" type="file" id="f" name='imagen'>
                    <?php
                    if(isset($_SESSION['errimg'])){
                        echo "<p class='text-danger'><b>{$_SESSION['errimg']}</b></p>";
                        unset($_SESSION['errimg']);
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