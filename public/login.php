<?php
session_start();




const TIEMPO_LOGOUT = 30;
const INTENTOS = 5;
$users = [
    "admin" => hash('sha256', 'secreto'),
    "usuario" => hash('sha256', 'usuario')
];

function hayError($c, $v)
{
    if (strlen($v) < 5) {
        $_SESSION[$c] = "****Este campo debe conterner al menos 5 carácteres";
        return true;
    }

    return false;
}
function isUserValid($u, $p): bool
{
    global $users;
    foreach ($users as $user => $pass) {
        if ($user == $u && $pass == $p) return true;
    }
    return false;
}
if (isset($_POST['btnLogin'])) {
    $error = false;
    $un = trim($_POST['username']);
    $p = trim($_POST['password']);

    if (hayError("error_nombre", $un)) $error = true;
    if (hayError("error_password", $p)) $error = true;
    if (!$error) {
        //comprobamos usuario
        $pass = hash('sha256', $p);
        if (isUserValid($un, $pass)) {
            setcookie("errorV", "", time() - 100);
            $_SESSION['user'] = $un;
            header("Location: index.php");
        } else {
            if (isset($_COOKIE['errorV'])) {
                $cont = ++$_COOKIE['errorV'];
                if ($cont == INTENTOS) {
                    setcookie("errorV", $cont, time() + TIEMPO_LOGOUT); //30s
                } else {
                    setcookie('errorV', $cont, time() + 3600);
                }
            } else {
                setcookie('errorV', 1, time() + 3600);
            }
            $total = (isset($_COOKIE['errorV'])) ? (INTENTOS - $_COOKIE['errorV']) : 4;
            $_SESSION['error_validacion'] = "Usuario o contraseña incorrectos, quedan " . $total . " intentos.";
            header("Location:{$_SERVER['PHP_SELF']}");
        }
    } else {
        header("Location:{$_SERVER['PHP_SELF']}");
    }
} else {
?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- CSS only -->
        <link rel="stylesheet" href="css/all.min.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <title>login usuario</title>
    </head>

    <body style="background-color: #7986cb;">
        <h5 class="mt-4 text-center">Login Usuario</h5>
        <div class="container mt-2">
            <div class="p-4 text-white rounded shadow-lg m-auto" style="width:32rem; background-color:#546e7a">
                <?php
                if (isset($_SESSION['error_validacion'])) {
                    echo "<div class='bg-dark text-danger my-2 p-2'>{$_SESSION['error_validacion']}</div>";
                    unset($_SESSION['error_validacion']);
                }
                ?>
                <form name="cautor" action="<?php echo $_SERVER['PHP_SELF']; ?>" method='POST'>

                    <div class="mb-3">
                        <label for="n" class="form-label">Nombre Usuario</label>
                        <input type="text" class="form-control" id="n" placeholder="Nombre Usuario" name="username" required>
                        <?php
                        if (isset($_SESSION['error_nombre'])) {
                            echo <<<TXT
                            <div class="mt-2 text-danger fw-bold">
                                {$_SESSION['error_nombre']}
                            </div>
                            TXT;
                            unset($_SESSION['error_nombre']);
                        }
                        ?>
                    </div>

                    <div class="mb-3">
                        <label for="p" class="form-label">Password</label>
                        <input type="password" class="form-control" id="p" placeholder="Password" name="password" required>
                        <?php
                        if (isset($_SESSION['error_password'])) {
                            echo <<<TXT
                            <div class="mt-2 text-danger fw-bold" style="font-size:small">
                                {$_SESSION['error_password']}
                            </div>
                            TXT;
                            unset($_SESSION['error_password']);
                        }
                        ?>
                    </div>

                    <div>
                        <?php
                        if (isset($_COOKIE['errorV']) && $_COOKIE['errorV'] == 5) {
                            echo <<<TXT
                        <button type='submit' name="btnLogin" class="btn btn-info" disabled><i class="fas fa-sign-in-alt"></i> Login (espera 30s)</button>
                        TXT;
                        } else {
                            echo <<<TXT
                            <button type='submit' name="btnLogin" class="btn btn-info"><i class="fas fa-sign-in-alt"></i> Login</button>
                            TXT;
                        }
                        ?>
                        <button type="reset" class="btn btn-warning"><i class="fas fa-broom"></i> Limpiar</button>

                    </div>

                </form>
            </div>
        </div>


    </body>

    </html>
<?php } ?>