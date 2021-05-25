<?php session_start();


//Si hay una sesion iniciada

if(isset($_SESSION['nombre'])){
    echo 'hola';
}


// Si el formulario se ha enviado

if($_SERVER['REQUEST_METHOD'] == 'POST'){


    //Si se quiere registrar
    if(strtolower($_POST['submit']) == 'registrarme'){
    
        
        //Variables

        $nombre_usuario = filter_var(strtolower($_POST['nombre_usuario']));                 //Nombre de usuario
        $nombre = filter_var(strtolower($_POST['nombre']), FILTER_SANITIZE_STRING);         //Nombre
        $email = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);                                                          //Correo Electronico
        $direccion = filter_var(strtolower($_POST['direccion']), FILTER_SANITIZE_STRING);   //Direccion
        $telefono = $_POST['telefono'];                                                     //Telefono
        $cp = $_POST['cp'];                        //Codigo Postal
        $password = $_POST['password'];             //Contraseña
        $rp_password = $_POST['rp-password'];          //Repetir Contraseña

        
        $errores_registro = '';      //Errores



        //SI LOS CAMPOS ESTAN VACIOS

        if(empty($nombre_usuario) or empty($nombre) or empty($email) or empty($direccion) or empty($telefono) or empty($cp) or empty($password) or empty($rp_password)){

            $errores .= 'Por favor asegurese que los campos estan correctos.';  

        }else{

            //Realizamos la conexion 

            try {

                $conexion = new PDO('mysql:host=localhost;dbname=mutare;', 'root', '');
                
            } catch (PDOException $e) {
                echo 'Error: ' . $e->getMessage();
            }

            //Si el nombre de usuario ya existe
            $statement = $conexion->prepare('SELECT * FROM tbl_usuari WHERE usuari = :nombre_usuario LIMIT 1');
            $statement->execute(array(':nombre_usuario' => $nombre_usuario));
            $resultado = $statement->fetch();

            //Si nos devuelve que el nombre de usuario ya existe mostramos el error
            if($resultado != false){

                $errores .= 'El nombre de usuario ya existe!';
            }

            //Hasheamos la contraseña
            $password = hash('sha512', $password);
            $rp_password = hash('sha512', $rp_password);

            //Si las contraseñas no coinciden 
            if($password != $rp_password){
                $errores .= 'Las contraseñas no son iguales';
            }

            //Si el número de teléfono no es igual a 9 caracteres y no es numerico mostramos error
            if(strlen($telefono) != 9 or is_numeric($telefono) != true ){
                $errores .= 'El número de teléfono debe contener 9 caracteres y debe ser numerico';
            }

            //Si el codigo postal no es igual a 5 caraccteres y no es numero mostramos error
            if(strlen($cp) != 5 or is_numeric($cp) != true){
                $errores .= 'El codigo postal debe contener 5 digitos y deben ser numericos';
            }
        }

        //Si no hay errores
        if($errores == ''){

        //Insertamos el usuario en la base de datos
        $statement = $conexion->prepare("INSERT INTO tbl_usuari (usuari, pass, email, nom, adreca, telf, cp_usuari) VALUES(:usuari, :pass, :email, :nom, :direccion, :telf, :cp_usuari)");

        $statement->execute(array(':usuari' => $nombre_usuario, ':pass' => $password, ':email' => $email, ':nom' => $nombre, ':direccion' => $direccion, ':telf' => $telefono, ':cp_usuari' => $cp));


        }

    }
    //Si quiere iniciar sesion
    else{

        $nombre_usuario = filter_var(strtolower($_POST['nombre_usuario']), FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        $errores_sesion = '';
        

        //Hasheamos la contraseña
        $password = hash('sha512', $password);


        //Realizamos la conexion a la base de datos
        try{

            $conexion = new PDO('mysql:host=localhost; dbname=mutare', 'root', '');

        }catch(PDOException $e){
            echo 'Error:' . $e->getMessage();
        }

        $statement = $conexion->prepare('SELECT * FROM tbl_usuari WHERE usuari = :usuario AND email = :email AND pass = :pass');
        $statement->execute(array(':usuario' => $nombre_usuario, ':email' => $email, ':pass' => $password));

        $resultado = $statement->fetch();
        
        if($resultado != false){
            $_SESSION['usuario'] = $nombre_usuario;
            echo 'Haz iniciado sesion';
        }else{
            $errores_sesion .= 'Datos incorrectos';
        }

    }

    

}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="./img/MUTARE_LOGO.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="tools/fontawesome-free-5.15.1-web/css/all.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <title>Mutare</title>
</head>
<body>
    <header>
        <div class="barranav">
            <nav>
                <div class="logo">
                    <img src="./img/MUTARE_LOGO.png" alt="">
                    <h1>MUTARE</h1>
                </div>
                <div class="buttons navbar">
                    <a href="#" id="registrate-btn">Regístrate</a>
                    <a href="#" id="inicio-btn">Iniciar Sesión</a>
                </div>
            </nav>
        </div>
    </header>

    <div class="inicio">
        <div class="contenedor">
            <h2>¡Comparte artículos con la red de intercambio cultural más grande!</h2>
            <div class="search">
                <span class="icon"><i class="fa fa-search"></i></span>    
                <input type="text" class="btn-search" id="btn-search" placeholder="Buscar">
            </div>
            <div class="buttons header">
                <a href="#"><p>Dar</p><img src="./img/open-book.png" alt=""></a>
                <a href="#"><p>Recibir</p><img src="./img/delivery.png" alt=""></a>
                <a href="#"><p>Intercambiar</p><img src="./img/exchange.png" alt=""></a>
            </div>
        </div>
    </div>
    <section class="cat-destacadas">
        <div class="contenedor">
            <h2>Categorias destacadas</h2>
            <div class="categorias">
                <div class="tarjeta">
                    <a href="#">
                        <div class="caja">
                            <div class="contenido-tarjeta">
                                <i class="fas fa-ghost"></i>
                                <h3>Terror</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="tarjeta">
                    <a href="./pages/cat-libro.html">
                        <div class="caja">
                            <div class="contenido-tarjeta">
                                <i class="fas fa-graduation-cap"></i>
                                <h3>Escolares</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="tarjeta">
                    <a href="#">
                        <div class="caja">
                            <div class="contenido-tarjeta">
                                <i class="far fa-futbol"></i>
                                <h3>Deportes</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="tarjeta">
                    <a href="#">
                        <div class="caja">
                            <div class="contenido-tarjeta">
                                <i class="fas fa-robot"></i>
                                <h3>Ciéncia ficción</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="tarjeta">
                    <a href="#">
                        <div class="caja">
                            <div class="contenido-tarjeta">
                                <i class="fas fa-rainbow"></i>
                                <h3>Cuentos infantiles</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="tarjeta">
                    <a href="#">
                        <div class="caja">
                            <div class="contenido-tarjeta">
                                <i class="far fa-laugh-squint"></i>
                                <h3>Humor</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="tarjeta">
                    <a href="#">
                        <div class="caja">
                            <div class="contenido-tarjeta">
                                <i class="fas fa-coins"></i>
                                <h3>Finanzas</h3>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="tarjeta">
                    <a href="#">
                        <div class="caja">
                            <div class="contenido-tarjeta">
                                <i class="far fa-grin-hearts"></i>
                                <h3>Romántico</h3>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="contenedor">
            <div class="copyright">
                <p><span class="far fa-copyright"></span> Mutare - Todos los derechos reservados</p>
            </div>
            <div class="links">
                <p><a href="#">Contacto</a></p>
                <p><a href="#">Aviso Legal</a></p>
                <p><a href="#">Privacidad y Condiciones</a></p>
            </div>
        </div>
    </footer>

    <div class="modal" id="registrate">
        <div class="registrate-content">
            <h1>Regístrate</h1>

            <?php if(!empty($errores_registro)): ?>
                <?php echo $errores_registro; ?>
            <?php endif; ?>


            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <input type="text" name="nombre_usuario" placeholder="Nombre de usuario:">
                <input type="text" name="nombre" placeholder="Nombre:">
                <input type="email" name="correo" placeholder="Correo Electrónico:">
                <input type="text" name="direccion" placeholder="Direccion de residencia">
                <input type="text" name="telefono" placeholder="Número de teléfono:">
                <input type="text" name="cp" placeholder="Código postal">
                <input type="password" name="password" placeholder="Contraseña:">
                <input type="password" name="rp-password" placeholder="Repite la contraseña">
                <input type="submit" name="submit" value="Registrarme">
            </form>
        </div>
    </div>
    
    <div class="modal" id="inicio-sesion">
        <div class="inicio-sesion-content">
            <h1>Inicia Sesión</h1>

            <?php if(!empty($errores_sesion)): ?>
                <?php echo $errores_sesion ?>
            <?php endif; ?>

            <form action="" method="POST">
                <input type="text" name="nombre_usuario" placeholder="Nombre de usuario:">
                <input type="email" name="email" placeholder="Correo Electrónico:">
                <input type="password" name="password" placeholder="Contraseña:">
                <input type="submit" name="submit" value="Iniciar Sesión">
            </form>
        </div>
    </div>

<script src="js/Jquery/jquery-3.5.1.min.js"></script>
<script src="js/modal.js"></script>
</body>
</html>