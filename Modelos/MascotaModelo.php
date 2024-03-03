<?php
require_once '../Conexion/config.php';
require_once '../Conexion/Database.php';

class MascotaModelo
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    //Obtiene todas las mascotas
    public function obtenerTodo()
    {
        $sql = "SELECT * FROM mascotas";
        $resultado = $this->db->ejecutarConsulta($sql);
        $mascotas = $resultado->fetchAll(PDO::FETCH_OBJ);
        return $mascotas;
    }

    //Muestra el cuadro de la mascota con su boton de añadir al carrito
    public function mostrarMascota($mascota)
    {
        $foto = $mascota->foto;
        echo "<div class='grid-item'>";
        echo "<p> Nombre: " . $mascota->nombre . "</p>";
        echo "<p>Tipo: " . $mascota->tipo . "</p>";
        echo "<p> Raza: " . $mascota->raza . "</p>";

        if (!empty($foto)) {
            echo "<img src='../$foto' width='200' height='150'>";
        } else {
            echo "No hay imagen disponible";
        }
?>
        <br><br>
        <form method='POST' action='../Controladores/Controlador.php'>
            <input type='hidden' name='mascota_id' value='<?php echo $mascota->id; ?>'>
            <button type='submit' name='addCarrito'>Añadir al carrito</button>
        </form>
        </div>
<?php
    }

    //Obtiene las ultimas mascotas insertadas en nuestra protectora y las muestra en el menu.
    public function obtenerMascotasRecienInsertadas($cantidad)
    {
        try {
            $consulta = "SELECT *, 
            CASE 
                WHEN id > (SELECT MAX(id) - $cantidad FROM mascotas) THEN 'Recientemente insertada'
                ELSE 'No recientemente insertada'
            END AS estado_reciente 
            FROM mascotas 
            ORDER BY id DESC 
            LIMIT $cantidad";

            $resultado = $this->db->ejecutarConsulta($consulta);

            $mascotas = array();

            while ($fila = $resultado->fetch(PDO::FETCH_OBJ)) {
                $mascotas[] = $fila;
            }
            $mensajeBienvenida = "<strong>Demos la bienvenida a nuestra tienda a: </strong>";
            foreach ($mascotas as $mascota) {
                $nombreMascota = $mascota->nombre;
                $mensajeBienvenida .= "<strong>$nombreMascota</strong>; ";
            }
            $mensajeBienvenida = rtrim($mensajeBienvenida, '; '); // Eliminar el último punto y coma
            $mensajeBienvenida .= "!&#x1F496 😺🐶 Desliza para conocerlos 😉";
            echo "<p>$mensajeBienvenida</p>";
            return $mascotas;
        } catch (PDOException $e) {
            echo "Error al obtener mascotas recientes: " . $e->getMessage();
            return array();
        }
    }

    //Obtener mascota por ID
    public function obtenerMascotaPorID($id)
    {
        try {
        $sql = "SELECT * FROM mascotas WHERE id = ?";
        $resultado = $this->db->ejecutarConsulta($sql, array($id));
        $mascota = $resultado->fetch(PDO::FETCH_OBJ);

        return $mascota;
        } catch (PDOException $e) {
            error_log("Error en la consulta: " . $e->getMessage());
            echo "Error en la consulta: " . $e->getMessage();
            return null;
        }
    }


    public function obtenerMascotas()
    {

        $sql = "SELECT * FROM mascotas INNER JOIN razas ON mascotas.id = razas.id_mascota";

        $resultado = $this->db->ejecutarConsulta($sql);

        $mascotas = $resultado->fetchAll(PDO::FETCH_OBJ);

        $this->db->cerrarConexion();
        return $mascotas;
    }

    public function mostrarCarrito()
    {
        $sql = "SELECT * FROM carrito INNER JOIN mascotas ON carrito.mascota_id = mascotas.id";
        $resultado = $this->db->ejecutarConsulta($sql);
        $carrito = $resultado->fetchAll(PDO::FETCH_OBJ);
        $this->db->cerrarConexion();
        return $carrito;
    }



    // Funciones para la busqueda de una mascota.

    public function obtenerCriteriosUnicos()
    {
        $sql = "SELECT DISTINCT LOWER(tipo) as tipo, LOWER(raza) as raza, LOWER(edad) as edad, LOWER(color) as color FROM mascotas";
        $resultado = $this->db->ejecutarConsulta($sql);
        $criteriosUnicos = $resultado->fetchAll(PDO::FETCH_ASSOC);

        return [
            'tipos' => array_unique(array_map('ucfirst', array_column($criteriosUnicos, 'tipo'))),
            'razas' => array_unique(array_map('ucfirst', array_column($criteriosUnicos, 'raza'))),
            'edades' => array_unique(array_map('ucfirst', array_column($criteriosUnicos, 'edad'))),
            'colores' => array_unique(array_map('ucfirst', array_column($criteriosUnicos, 'color'))),
        ];
    }



    public function filtrarMascotas($filtroTipo, $filtroRaza, $filtroEdad, $filtroColor)
    {
        $sql = "SELECT * FROM mascotas INNER JOIN razas ON mascotas.id = razas.mascota_id WHERE LOWER(tipo) LIKE ? AND LOWER(raza) LIKE ? AND LOWER(edad) LIKE ? AND LOWER(color) LIKE ?";
        $parametros = array(
            '%' . strtolower($filtroTipo) . '%',
            '%' . strtolower($filtroRaza) . '%',
            '%' . strtolower($filtroEdad) . '%',
            '%' . strtolower($filtroColor) . '%'
        );
        $resultado = $this->db->ejecutarConsulta($sql, $parametros);
        $mascotasFiltradas = $resultado->fetchAll(PDO::FETCH_OBJ);
        return $mascotasFiltradas;
    }

    public function mostrarMascotaFiltrada($mascota)
    {
        $foto = $mascota->foto;
        echo "<div class='grid-item'>";
        echo "<p> Nombre: " . $mascota->nombre . "</p>";
        echo "<p>Tipo: " . $mascota->tipo . "</p>";
        echo "<p> Raza: " . $mascota->raza . "</p>";

        if (!empty($foto)) {
            echo "<img src='../$foto' width='200' height='150'>";
        } else {
            echo "No hay imagen disponible";
        }

        echo "<br><br>";

        echo "<form method='POST' action='../Controladores/Controlador.php'>";
        echo "<input type='hidden' name='mascota_id' value='$mascota->id'>";
        echo "<button type='submit' name='addCarrito'>Añadir al carrito</button>";
        echo "</form>";
        echo "</div>";
    }

    public function mostrarMascotasFiltradas($mascotasFiltradas)
    {
        if (!is_null($mascotasFiltradas)) {
            foreach ($mascotasFiltradas as $mascota) {
                $this->mostrarMascotaFiltrada($mascota);
            }
        }
    }










    // FUNCIONES DE ADMIN
    //Insertar mascota en la Base de datos por admin:
    public function insertarMascota($nombre, $tipo, $raza, $edad, $id_dueño, $color, $foto, $tamaño){

        $sql = "INSERT INTO mascotas (nombre, tipo, raza, edad,id_dueño, color ,foto) VALUES (?, ?, ?, ?, ?, ?,?)";
        $parametros = array($nombre, $tipo, $raza, $edad, $id_dueño, $color, $foto);
        $resultado1=$this->db->ejecutarConsulta($sql, $parametros);

        $idMascota =$this->db->lastInsertId();
        $sql2 = "INSERT INTO razas (mascota_id,nombre_raza,tamaño) VALUES (?,?, ?)";
        $parametros2 = array($idMascota,$raza, $tamaño);
        $resultado2=$this->db->ejecutarConsulta($sql2, $parametros2);

        if($resultado1 && $resultado2){
            return true;
        }else{
            return false;
        }
    }



     // Actualizar mascota en la Base de datos por admin:
    public function actualizarMascota($id, $nombre, $tipo, $raza, $edad, $id_dueño, $color, $foto)
    {
        $foto_nombre = $_FILES['foto']['name'];
        $carpeta_destino = '../img/';
        $ruta_relativa = 'img/' . $foto_nombre;
        move_uploaded_file($_FILES['foto']['tmp_name'], $carpeta_destino . $foto_nombre);

        $sql1 = "UPDATE mascotas SET nombre = ?, tipo = ?, raza = ?, edad = ?, id_dueño = ?,color = ?,  foto = ? WHERE id = ?";
        $parametros1 = array($nombre, $tipo, $raza, $edad, $id_dueño, $color, $ruta_relativa, $id);
        $resultado1= $this->db->ejecutarConsulta($sql1, $parametros1);

        $sql2= "UPDATE razas SET nombre_raza = ? WHERE mascota_id = ?";
        $parametros2 = array($raza, $id);
        $resultado2 = $this->db->ejecutarConsulta($sql2, $parametros2);

        if($resultado1 && $resultado2){
            return true;
        }else{
            return false;
        }
    }

    //Eliminar de la Base de datos por admin: 
    public function obtenerMascotaPorNombre($nombreMascota)
    {
        $sql = "SELECT * FROM mascotas WHERE nombre = ?";
        $resultado = $this->db->ejecutarConsulta($sql, array($nombreMascota));
        $mascota = $resultado->fetch(PDO::FETCH_OBJ);
        return $mascota;
    }

    public function eliminarMascota($nombreMascota)
    {
        // Obtén la información de la mascota antes de eliminarla
        $mascotaAEliminar = $this->obtenerMascotaPorNombre($nombreMascota);
        if ($mascotaAEliminar) {
            // La mascota existe, procede con la eliminación
            $sql = "DELETE FROM mascotas WHERE nombre=?";
            $parametros = array($nombreMascota);
            $resultado = $this->db->ejecutarConsulta($sql, $parametros);
            return $resultado;
        }else{
            return false;
        }
    }

    public function eliminarMascotaPorID($id)   {
        // Obtén la información de la mascota antes de eliminarla
        $mascotaAEliminar = $this->obtenerMascotaPorID($id);
        if ($mascotaAEliminar) {
            // La mascota existe, procede con la eliminación
            $sql = "DELETE FROM mascotas WHERE id=?";
            $parametros = array($id);
            $resultado = $this->db->ejecutarConsulta($sql, $parametros);
            return $resultado;
        }else{
            return false;
        }
    }

    public function importarDatosDesdeXML($rutaXml)
    {

        if (file_exists($rutaXml)) {
            $xml = simplexml_load_file($rutaXml);

            foreach ($xml->mascota as $mascota) {
                $id = (int)$mascota['id'];
                $nombre = (string)$mascota->nombre;
                $tipo = (string)$mascota->tipo;
                $raza = (string)$mascota->raza;
                $edad = (int)$mascota->edad;
                $id_dueño = (int)$mascota->id_dueño;
                $color = (string)$mascota->color;
                $foto = (string)$mascota['foto'];
                $tamaño = (string)$mascota->tamaño;

                $sqlMascotas = "INSERT INTO mascotas(nombre, tipo, raza, edad, id_dueño, color, foto) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $parametrosMascotas = array($nombre, $tipo, $raza, $edad, $id_dueño, $color, $foto);
                $this->db->ejecutarConsulta($sqlMascotas, $parametrosMascotas);

                $idMascota = $this->db->lastInsertId();

                $sqlRazas = "INSERT INTO razas (mascota_id,nombre_raza,tamaño) VALUES (?, ?, ?)";
                $parametrosRazas = array($idMascota, $raza, $tamaño);
                $this->db->ejecutarConsulta($sqlRazas, $parametrosRazas);
            }
            $this->db->cerrarConexion();

            return true;
        } else {
            echo "El archivo no existe";
            return false;
        }
    }


    public function obtenerProductosRecientesEnCarrito($usuario_id)
    {
        try {
            $cantidad = 5;

            $consulta = "SELECT *, 
            CASE 
                WHEN carrito.carrito_id > (SELECT MAX(carrito_id) - $cantidad FROM carrito WHERE usuario_id = ?) THEN 'Recientemente insertado'
                ELSE 'No recientemente insertado'
            END AS estado_reciente 
            FROM carrito 
            INNER JOIN mascotas ON carrito.mascota_id = mascotas.id 
            WHERE usuario_id = ?
            ORDER BY carrito.carrito_id DESC 
            LIMIT $cantidad";

            $resultado = $this->db->ejecutarConsulta($consulta, array($usuario_id, $usuario_id));

            $productosCarrito = array();

            while ($fila = $resultado->fetch(PDO::FETCH_OBJ)) {
                $productosCarrito[] = $fila;
            }

            // Obtener el nombre del último producto insertado en el carrito
            $nombreUltimoProducto = !empty($productosCarrito) ? $productosCarrito[0]->nombre : '';

            return $productosCarrito;
        } catch (PDOException $e) {
            // Manejo básico de errores
            echo "Error al obtener productos recientes en el carrito: " . $e->getMessage();
            return array(); // Retorna un array vacío en caso de error
        }
    }
}
?>