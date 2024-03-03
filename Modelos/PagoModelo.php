<?php
// Configuración para usar Gmail como servidor SMTP
ini_set("smtp_crypto", "tls"); // Configura el cifrado TLS
ini_set("SMTP", "smtp.gmail.com");
ini_set("smtp_port", 587);
ini_set("sendmail_from", "amandaroblesurena@gmail.com"); // Cambia esto por tu dirección de correo de Gmail
include_once('../Conexion/Database.php');
include_once('../Modelos/MascotaModelo.php');
include_once('../Modelos/CarritoModelo.php');




class PagoModelo
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }


    public function algoritmoLuhn($numero)
    {
        $numero = strrev(preg_replace('/[^\d]/', '', $numero));
        $suma = 0;

        for ($i = 0, $longitud = strlen($numero); $i < $longitud; $i++) {
            $digito = (int) $numero[$i];

            if ($i % 2 === 1) {
                $digito *= 2;

                if ($digito > 9) {
                    $digito -= 9;
                }
            }

            $suma += $digito;
        }

        return $suma % 10 === 0;
    }

    public function verificarTarjetaCredito($numero, $fecha_vencimiento, $cvv)
    {
        $luhn = new self();
    
        // Verificar el algoritmo de Luhn
        if (!$luhn->algoritmoLuhn($numero)) {
            return false;
        }
    
        // Verificar la fecha de vencimiento
        $fecha_actual = new DateTime();
        $fecha_vencimiento_obj = DateTime::createFromFormat('m/y', $fecha_vencimiento);
    
        if ($fecha_vencimiento_obj && $fecha_vencimiento_obj > $fecha_actual) {
            return true;
        } else {
            return false;
        }
    }



    public function enviarCorreo($correo, $asunto)
    {
        $carritoModelo = new CarritoModelo();
        $precioTotal = $carritoModelo->calcularTotalCarrito($_SESSION['id']);
        $mascotas = $carritoModelo->obtenerCarrito($_SESSION['id']);

        $texto = "Detalles de la compra: ";

        foreach ($mascotas as $mascotaCarrito) {
            $mascotaModelo = new MascotaModelo();
            $mascotaCompleta = $mascotaModelo->obtenerMascotaPorID($mascotaCarrito->mascota_id);

            if ($mascotaCarrito->estado == "adoptado") {
                $texto .= $mascotaCompleta->nombre . " de tipo " . $mascotaCompleta->tipo . " y raza " . $mascotaCompleta->raza  . " ha sido adoptado por usted.";
            }
        }
        $texto .= " Fecha de adquisición: $mascotaCarrito->fecha";
        $texto .= " Precio total : $precioTotal €";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: $correo" . "\r\n";

        $correoLink =  'https://mail.google.com/mail/?view=cm&fs=1&to=' . $correo . '&su=' . rawurlencode($asunto) . '&body=' . rawurlencode($texto);
        return $correoLink;
    }
}
