<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel=stylesheet href="../css/login.css">
</head>

<body>

  <div class="form">
    <h1>Protectora de animales &#10084</h1>

    <form action="../Controladores/Controlador.php" method="POST">
      <label for="identificador">Usuario:</label>
      <input type="text" name="identificador" id="identificador"><br><br>

      <label for="password">Contraseña:</label>
      <input type="password" name="password" id="password"><br><br>

      <label for="role">Seleccione su rol:</label>
      <select name="role" id="role">
        <option value="administrador">Administrador</option>
        <option value="usuario">Usuario</option>
      </select><br><br>

      <button type="submit" name="login">Login</button>
    </form>
    <p class="mensaje_login"><?php if (isset($_SESSION['mensaje_login'])) {
          echo $_SESSION['mensaje_login'];
        }
        ?></p>

  </div>
  <div class="imagenes">
    <img src="../img/login2.avif" class="imagen-item">
    <img src="../img/login5.avif" class="imagen-item">
    <img src="../img/login6.avif" class="imagen-item">
  </div>

</body>

</html>