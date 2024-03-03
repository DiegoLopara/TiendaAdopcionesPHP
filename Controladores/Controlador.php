<?php
session_start();
require_once '../Modelos/CarritoModelo.php';
require_once '../Modelos/UsuarioModelo.php';
require_once '../Modelos/MascotaModelo.php';
require_once '../Modelos/PagoModelo.php';

class Controlador
{
    private $carritoModelo;
    private $usuarioModelo;
    private $mascotaModelo;
    private $pagoModelo;

    public function __construct()
    {
        $this->carritoModelo = new CarritoModelo();
        $this->usuarioModelo = new UsuarioModelo();
        $this->mascotaModelo = new MascotaModelo();
        $this->pagoModelo = new PagoModelo();
    }

    public function procesarAccion()
    {

        if (isset($_POST['login'])) {
            $this->login();
        } elseif (isset($_POST['addCarrito'])) {
            $this->clearSessionMessages();
            $this->addCarrito();
        } elseif (isset($_POST['eliminarCarrito'])) {
            $this->clearSessionMessages();
            $this->eliminarCarrito();
        } elseif (isset($_GET['buscar'])) {
            $this->clearSessionMessages();
            $this->buscar();
        } elseif (isset($_POST['insertar'])) {
            $this->clearSessionMessages();
            $this->insertar();
        } elseif (isset($_POST['importar'])) {
            $this->clearSessionMessages();
            $this->importar();
        } elseif (isset($_POST['actualizar'])) {
            $this->clearSessionMessages();
            $this->actualizar();
        } elseif (isset($_POST['eliminar'])) {
            $this->clearSessionMessages();
            $this->eliminar();
        } elseif (isset($_POST['accion']) && $_POST['accion'] == 'procesarPago') {
            $this->clearSessionMessages();
            $this->procesarPago();
        } elseif (isset($_POST['finalizarPago'])) {
            $this->clearSessionMessages();
            $this->finalizarPago();
        } elseif (isset($_GET['accion']) && $_GET['accion'] === 'cerrar_sesion') {

            $this->cerrarSesion();
        }
    }
    //1.Login
    private function login()
    {

        $identificador = isset($_POST['identificador']) ? $_POST['identificador'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $role = isset($_POST['role']) ? $_POST['role'] : '';

        if (empty($identificador) || empty($password) || empty($role)) {
            $_SESSION['mensaje_login'] = "Introduce todos los campos";
            $this->redirectTo('../Vistas/login.php');
        } else {

            try {
                $usuario = $this->usuarioModelo->comprobarUsuarioLogin($identificador, $password, $role);

                if ($usuario) {
                    $_SESSION['logged_in'] = true;
                    $_SESSION['identificador'] = $usuario->identificador;
                    $_SESSION['role'] = $usuario->role;
                    $_SESSION['id'] = $usuario->id;
                    $_SESSION['correo'] = $usuario->correo;

                    //Obtener la fecha actual desde la cookie si existe
                    $id = $_SESSION['id'];
                    $ultimaConexionActualizada = isset($_COOKIE['ultima_conexion_$id']) ? $_COOKIE['ultima_conexion_$id'] : ' ';

                    // Si la cookie no existe, actualizar la última conexión.
                    if (empty($ultimaConexion)) {
                        $ultimaConexion = $this->usuarioModelo->actualizarUltimaConexion($id);
                    }

                    $_SESSION['ultima_conexion_$id'] = $ultimaConexionActualizada;


                    //Redirigir segun el rol
                    $this->redirectTo($usuario->role == 'usuario' ? "../Vistas/menu_principal.php" : "../Vistas/menu_admin.php");
                } else {
                    $_SESSION['mensaje_login'] = "Usuario o contraseña incorrectos.";
                    $this->redirectTo("../Vistas/login.php");
                }
            } catch (PDOException $e) {
                $_SESSION['mensaje_login'] = "Error en la operación de base de datos: " . $e->getMessage();
                $this->redirectTo("../Vistas/login.php");
            }
        }
    }


    //2. Añadir al carrito
    private function addCarrito()
    {
        $usuario_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
        $mascota_id = isset($_POST['mascota_id']) ? $_POST['mascota_id'] : null;

        $this->carritoModelo->insertarMascotaEnCarrito($usuario_id, $mascota_id);
        // Obtener el número de elementos del carrito
        $numeroElementosCarrito = count($this->carritoModelo->mostrarAnimalesEnCarrito($usuario_id));
        $_SESSION['elementosCarrito'] = $numeroElementosCarrito;

        $_SESSION['mensaje'] = "La mascota se ha añadido al carrito. Con ID $mascota_id y usuario $usuario_id";

        $carrito = $this->carritoModelo->mostrarAnimalesEnCarrito($usuario_id);
        $_SESSION['carrito'] = $carrito;
        include_once('../Vistas/lista_mascotas.php');
        exit();
    }

    private function eliminarCarrito()
    {

        $mascota_id = isset($_POST['mascota_id']) ? $_POST['mascota_id'] : null;
        $usuario_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

        if (!$mascota_id) {
            $_SESSION['error'] = "Error al eliminar la mascota del carrito. Selecciona una mascota válida.";
            header('Location: ../Vistas/lista_mascotas.php');
            exit();
        }
        $this->carritoModelo->eliminarMascotaCarrito($mascota_id);

        $carrito = $this->carritoModelo->mostrarAnimalesEnCarrito($usuario_id);
        $_SESSION['carrito'] = $carrito;
        $numeroElementosCarrito = count($carrito);
        


        if (empty($carrito)) {
            $_SESSION['mensaje'] = "El carrito está vacío.";
            $_SESSION['elementosCarrito'] = $numeroElementosCarrito;
        } else {
            $_SESSION['mensaje'] = "La mascota se ha eliminado del carrito.";
            $_SESSION['elementosCarrito'] = $numeroElementosCarrito=0;
        }
        include_once('../Vistas/lista_mascotas.php');
        exit();
    }


    private function buscar()
    {
        if (!isset($_SESSION['logged_in']) || !isset($_SESSION['identificador'])) {
            $this->redirectTo('../Vistas/login.php');
        }
        // Obtener parámetros de búsqueda del formulario
        $filtroTipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
        $filtroRaza = isset($_GET['raza']) ? $_GET['raza'] : '';
        $filtroEdad = isset($_GET['edad']) ? $_GET['edad'] : '';
        $filtroColor = isset($_GET['color']) ? $_GET['color'] : '';

        if (!$filtroTipo && !$filtroRaza && !$filtroEdad && !$filtroColor) {
            $_SESSION['mensaje'] = "No se ha seleccionado ningún criterio de búsqueda.";
            $this->redirectTo(isset($_SESSION['role']) && $_SESSION['role'] == 'administrador' ? "../Vistas/menu_admin.php" : "../Vistas/menu_principal.php");
        }

        $mascotasFiltradas = $this->mascotaModelo->filtrarMascotas($filtroTipo, $filtroRaza, $filtroEdad, $filtroColor);
        $_SESSION['mascotasFiltradas'] = $mascotasFiltradas;
        include_once('../Vistas/mostrar_busqueda.php');
        exit();
    }

    //FUNCIONES DE ADMINISTRADOR
    //1.Inserta en la BD.
    private function insertar()
    {
        $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
        $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : null;
        $raza = isset($_POST['raza']) ? $_POST['raza'] : null;
        $edad = isset($_POST['edad']) ? $_POST['edad'] : null;
        $color = isset($_POST['color']) ? $_POST['color'] : null;
        $id_dueño = isset($_POST['id_dueño']) ? $_POST['id_dueño'] : null;
        $tamaño = isset($_POST['tamaño']) ? $_POST['tamaño'] : null;

        if (!$nombre || !$tipo || !$raza || !$edad || !$color || !$id_dueño || !$tamaño) {
            $_SESSION['mensaje'] = "Error al insertar la mascota. Introduce todos los campos";
            header("Location: ../Vistas/menu_admin.php");
            exit();
        }

        $foto_nombre = $_FILES['foto']['name'];
        $carpeta_destino = '../img/';
        $foto = 'img/' . $foto_nombre;

        if ($_FILES['foto']['error'] == 0) {
            if ($_FILES['foto']['size'] <= 2 * 1024 * 1024) {
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $carpeta_destino . $foto_nombre)) {
                    try {
                        $resultado = $this->mascotaModelo->insertarMascota($nombre, $tipo, $raza, $edad, $id_dueño, $color, $foto, $tamaño);

                        if ($resultado) {
                            $_SESSION['mensaje'] = "Mascota insertada correctamente";
                        } else {
                            $_SESSION['mensaje'] = "Error al insertar la mascota en la base de datos";
                        }
                        header("Location: ../Vistas/menu_admin.php");
                        exit();
                    } catch (Exception $e) {
                        $_SESSION['mensaje'] = "Error en la operación de base de datos: " . $e->getMessage();
                    }
                } else {
                    $_SESSION['mensaje'] = "Error al mover el archivo";
                }
            } else {
                $_SESSION['mensaje'] = "El tamaño del archivo es demasiado grande. Máximo 2 MB.";
            }
        } else {
            $_SESSION['mensaje'] = "No se seleccionó ningún archivo o hubo un problema durante la carga.";
        }

        header("Location: ../Vistas/menu_admin.php");
        exit();
    }

    //2.Importa en la BD con archivo XML.
    private function importar()
    {
        if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == UPLOAD_ERR_OK) {
            $nombreArchivo = $_FILES['archivo']['name'];
            $rutaTemporal = $_FILES['archivo']['tmp_name'];

            $rutaDestino = "../xml/" . $nombreArchivo;
            move_uploaded_file($rutaTemporal, $rutaDestino);
            $_SESSION['mensaje'] = "Archivo subido con exito";
            $rutaXml = $rutaDestino;

            $resultadoImportacion = $this->mascotaModelo->importarDatosDesdeXML($rutaXml);

            if ($resultadoImportacion) {
                $_SESSION['mensaje'] = "Datos importados correctamente.";
                header("Location: ../Vistas/menu_admin.php");
                exit();
            } else {
                $_SESSION['mensaje'] = "Error al importar los datos desde el archivo XML.";
                header("Location: ../Vistas/menu_admin.php");
                exit();
            }
        } else {
            $_SESSION['mensaje'] = "Error al subir el archivo.";
            header("Location: ../Vistas/menu_admin.php");
            exit();
        }
    }

    //3.Actualiza en la BD.
    private function actualizar()
    {
        $id = isset($_POST['mascota_id']) ? $_POST['mascota_id'] : null;
        $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
        $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : null;
        $raza = isset($_POST['raza']) ? $_POST['raza'] : null;
        $edad = isset($_POST['edad']) ? $_POST['edad'] : null;
        $id_dueño = isset($_POST['id_dueño']) ? $_POST['id_dueño'] : null;
        $color = isset($_POST['color']) ? $_POST['color'] : null;
        $foto = isset($_FILES['foto']['name']) ? $_FILES['foto']['name'] : null;

        if (!$id || !$nombre || !$tipo || !$raza || !$edad || !$id_dueño || !$color || !$foto) {
            $_SESSION['mensaje'] = "Error al actualizar la mascota. Introduce todos los campos";
            header("Location: ../Vistas/menu_admin.php");
            exit();
        }

        $resultado = $this->manejarArchivoSubido($id, $nombre, $tipo, $raza, $edad, $id_dueño, $color, $foto);

        if ($resultado === true) {
            $_SESSION['mensaje'] = "Mascota actualizada correctamente";
        } else {
            $_SESSION['mensaje'] = "Error al actualizar la mascota.  $resultado";
        }
        header("Location: ../Vistas/menu_admin.php");
        exit();
    }

    //**Manejar archivo subido (foto).
    private function manejarArchivoSubido($id, $nombre, $tipo, $raza, $edad, $id_dueño, $color, $foto)
    {
        if ($_FILES['foto']['error'] == 0) {
            $foto_nombre = $_FILES['foto']['name'];
            $carpeta_destino = '../img/';
            $ruta_relativa = 'img/' . $foto_nombre;

            if ($_FILES['foto']['size'] <= 2 * 1024 * 1024) {
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $carpeta_destino . $foto_nombre)) {
                    try {
                        $resultado = $this->mascotaModelo->actualizarMascota($id, $nombre, $tipo, $raza, $edad, $id_dueño, $color, $foto);
                        return $resultado ? true : "Error al actualizar en la base de datos";
                    } catch (Exception $e) {
                        $_SESSION['mensaje'] = "Error en la operación de base de datos: " . $e->getMessage();
                    }
                } else {
                    $_SESSION['mensaje'] = "Error al mover el archivo";
                }
            } else {
                $_SESSION['mensaje'] = "El tamaño del archivo es demasiado grande. Máximo 2 MB.";
            }
        } else {
            $_SESSION['mensaje'] = "No se seleccionó ningún archivo o hubo un problema durante la carga.";
        }
    }

    //4.Elimina en la BD.
    private function eliminar()
    {
        $nombreMascota = isset($_POST['nombre']) ? $_POST['nombre'] : null;

        if (!$nombreMascota) {
            $_SESSION['mensaje'] = "Error al eliminar la mascota. Introduce el nombre";
        } else {
            $resultado = $this->mascotaModelo->eliminarMascota($nombreMascota);

            if ($resultado !== false) {
                $_SESSION['mensaje'] = "Mascota eliminada correctamente";
            } else {
                $_SESSION['mensaje'] = "Error al eliminar la mascota. El nombre no existe en la base de datos.";
            }
        }
        header("Location: ../Vistas/menu_admin.php");
        exit();
    }


    // FUNCION PAGO (PROCESAR PAGO Y PAGINA FINAL)
    private function procesarPago()
    {
        $usuario_id = $_SESSION['id'];
        $carrito = $this->carritoModelo->mostrarAnimalesEnCarrito($usuario_id);
        $numeroElementosCarrito = count($carrito);


        if ($numeroElementosCarrito != 0) {
            $precio = 10;
            $precioTotal = $this->carritoModelo->calcularTotalCarrito($usuario_id);

            include_once('../Vistas/procesar_pago.php');
            exit();
        }
    }
    private function finalizarPago()
    {
        $usuario_id = $_SESSION['id'];
        $cvv = $_POST['cvv'];
        $numero = $_POST['numeroTarjeta'];
        $fecha_vencimiento = $_POST['vencimiento'];
        $nombre = $_POST['nombreTarjeta'];
        $mascota_id = $_POST['mascota_id'];
        $asunto = "Gracias. Tus animales están en camino";

        if ($this->pagoModelo->verificarTarjetaCredito($numero, $fecha_vencimiento, $cvv)) {

            $this->carritoModelo->actualizarEstadoMascotaCarrito($mascota_id, "adoptado");
            $_SESSION['mensaje'] = "Compra procesada con éxito. Gracias por adoptar.❤️";
               // Eliminar la mascota del carrito y de la base de datos
               $this->carritoModelo->eliminarMascotaCarrito($mascota_id);
               $this->mascotaModelo->eliminarMascotaPorID($mascota_id);

                   // Actualizar la variable de sesión con el nuevo número de elementos en el carrito
            $numeroElementosCarrito = count($this->carritoModelo->mostrarAnimalesEnCarrito($usuario_id));
            $_SESSION['elementosCarrito'] = $numeroElementosCarrito;

            include_once('../Vistas/pagina_final.php');
         
            
        } else {
            echo "<p>El número de tarjeta no es válido. Pruebe otra vez</p>";
        }
}





    //Cerrar sesión
    private function cerrarSesion()
    {
        // Limpiamos los datos
        $_SESSION = array();

        // Destruimos la sesión
        session_destroy();

        // Redirigimos al login
        header("Location: ../Vistas/login.php");
        exit();
    }

    // Funciones para manejar sesiones y redirecciones
    private function setErrorMessage($message)
    {
        $_SESSION['mensaje'] = "<span style='color:red'>$message</span>";
    }
    private function setSuccessMessage($message)
    {
        $_SESSION['mensaje'] = "<span style='color:green'>$message</span>";
    }
    private function clearSessionMessages()
    {
        unset($_SESSION['mensaje']);
    }
    private function redirectTo($location)
    {
        header("Location: $location");
        exit();
    }
}


    $controlador = new Controlador();
    $controlador->procesarAccion();
