<?php
include_once('../Conexion/Database.php');
class UsuarioModelo
{
    private $db;
    private $usuarios;

    //Constructor de la clase
    public function __construct()
    {
        $this->db = new Database();
    }

    //Funcion para comprobar si el usuario existe en la base de datos y hacer login
    public function comprobarUsuarioLogin($identificador, $password, $role)
    {
        $sql = "SELECT * FROM usuarios WHERE identificador = :identificador AND password = :password AND role = :role LIMIT 1";
        $parametros = array(':identificador' => $identificador, ':password' => $password, ':role' => $role);
        $stmt = $this->db->ejecutarConsulta($sql, $parametros);
        $usuario = $stmt->fetch(PDO::FETCH_OBJ);
        return $usuario;
    }

    //Funcion para actualizar la ultima conexion del usuario
    public function actualizarUltimaConexion($id)
    {
        //Obtener la fecha actual desde la cookie si existe
        $fechaActual =date("Y-m-d H:i:s");
        setcookie('ultima_conexion_$id', $fechaActual, time() + 36000, '/');
        return $fechaActual;
    }








    public function obtenerUsuarios()
    {
        $sql = "SELECT * FROM usuarios";
        $resultado = $this->db->ejecutarConsulta($sql);
        while ($fila = $resultado->fetch(PDO::FETCH_OBJ)) {
            $this->usuarios[] = $fila;
        }
        return $this->usuarios;
    }

    public function obtenerUsuario($id)
    {
        $sql = "SELECT * FROM usuarios WHERE id = $id";
        $resultado = $this->db->ejecutarConsulta($sql);
        $usuario = $resultado->fetch(PDO::FETCH_OBJ);
        return $usuario;
    }
}
