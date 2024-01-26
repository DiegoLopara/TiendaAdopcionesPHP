<?php
require_once '../Conexion/config.php';
require_once '../Conexion/Database.php';
require_once '../Modelos/MascotaModelo.php';

class CarritoModelo
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }
    // Funcion para insertar mascota en el carrito
    public function insertarMascotaEnCarrito($usuario_id, $mascota_id)
    {
        //Verifica si la mascota ya está en el carrito
        $sql = "SELECT COUNT(*) FROM carrito WHERE usuario_id = ? AND mascota_id = ?";
        $parametros = array($usuario_id, $mascota_id);
        $rowCount = $this->db->ejecutarConsulta($sql, $parametros)->fetch(PDO::FETCH_COLUMN);
     
        if ($rowCount == 0) {
            //Si no esta en el carrito, la añadimos
            $fecha = date("Y-m-d H:i:s");
            $estado = "pendiente";

            $sqlInsertar = "INSERT INTO carrito (usuario_id, mascota_id, fecha, estado) VALUES (?, ?, ?, ?)";
            $parametrosInsertar= array($usuario_id, $mascota_id, $fecha, $estado);
            try{
                $this->db->ejecutarConsulta($sqlInsertar, $parametrosInsertar);
                $_SESSION['mensaje'] = "La mascota se ha añadido al carrito. Con ID $mascota_id y usuario $usuario_id";
                return true;

            }catch(PDOException $e){
                error_log("Error en la consulta: " . $e->getMessage());
                error_log("SQL: " . $sqlInsertar);
                error_log("Error Trace: " . $e->getTraceAsString());

                throw $e;
            }
        } else {
            $_SESSION['mensaje'] = "La mascota ya está en el carrito.";
            return false;
        }
    
    }


    //Funcion eliminar del carrito
    public function eliminarMascotaCarrito($mascota_id)
    {
        $sql = "DELETE FROM carrito WHERE mascota_id = ?";
        try {
            $this->db->ejecutarConsulta($sql, array($mascota_id));
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            error_log("SQL: " . $sql);
            error_log("Error Trace: " . $e->getTraceAsString());
        }
    }

    // Mostrar animales en el carrito 
    public function mostrarAnimalesEnCarrito($usuario_id)
    {
        $sql = "SELECT * FROM carrito INNER JOIN mascotas ON carrito.mascota_id = mascotas.id WHERE usuario_id = ?";
        $resultado = $this->db->ejecutarConsulta($sql, array($usuario_id));
        $carrito = $resultado->fetchAll(PDO::FETCH_OBJ);
        return $carrito;
    }

    // Actualizar estado mascota carrito
    public function actualizarEstadoMascotaCarrito($mascota_id, $estado)
    {
        $sql = "UPDATE carrito SET estado = ? WHERE mascota_id = ?";
        try {
            $this->db->ejecutarConsulta($sql, array($estado, $mascota_id));
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            error_log("SQL: " . $sql);
            error_log("Error Trace: " . $e->getTraceAsString());
        }
    }

    // Obtener carrito
    public function obtenerCarrito($usuario_id)
    {
        $sql = "SELECT * FROM carrito WHERE usuario_id = ?";
        $resultado = $this->db->ejecutarConsulta($sql, array($usuario_id));
        $carrito = $resultado->fetchAll(PDO::FETCH_OBJ);
        return $carrito;
    }

    //Calcular total carrito 
    public function calcularTotalCarrito($usuario_id)
    {
        $carrito = $this->mostrarAnimalesEnCarrito($usuario_id);
        $total = 0;
        $precio = 10;
        foreach ($carrito as $mascota) {

            $total += $precio;
        }
        return $total;
    }

    //Vaciar carrito 
    public function vaciarCarrito($usuario_id)
    {
        $sql = "DELETE FROM carrito WHERE usuario_id = ?";
        try {
            $this->db->ejecutarConsulta($sql, array($usuario_id));
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            error_log("SQL: " . $sql);
            error_log("Error Trace: " . $e->getTraceAsString());
        }
    }
}
