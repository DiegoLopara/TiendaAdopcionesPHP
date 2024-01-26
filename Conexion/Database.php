<?php

require_once("config.php");

class Database {

    private $host;
    private $user;
    private $pass;
    private $db;
    private $conexion;

    public function __construct() {
        $this->host = DB_HOST;
        $this->user = DB_USER;
        $this->pass = DB_PASS;
        $this->db = DB_NAME;

        try {
            $dsn = "mysql:host=$this->host;dbname=$this->db;";
            $this->conexion = new PDO($dsn, $this->user, $this->pass);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("Error en la conexion: " . $e->getMessage());
        }
    }

    public function ejecutarConsulta($sql, $parametros = array()) {
        try {
            if ($this->conexion) {
                $stmt = $this->conexion->prepare($sql);
                $stmt->execute($parametros);
                return $stmt;
            } else {
                throw new Exception("Error: Intentando ejecutar consulta en conexión cerrada.");
            }
        } catch (PDOException $e) {
            throw new Exception("Error en la consulta: " . $e->getMessage());
        }
    }
    public function lastInsertId(){
        return $this->conexion->lastInsertId();
    }

    public function cerrarConexion() {
        $this->conexion = null;
    }
}