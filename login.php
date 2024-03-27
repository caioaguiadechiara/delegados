<?php
$ctx = hash_init('sha1');
hash_update($ctx, 'SGF');
$hash = hash_final($ctx) . date('DdMYHis');
session_start();
include 'includes/class/ClassTodas.php';
$ClassTodas = new ClassTodas();
$nombre_sistema = $ClassTodas->title_system();
$nombre_sistemaAbreviado = $ClassTodas->title_abreviado();
$dominioPrincipal = $ClassTodas->dominioPrincipal();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title> <?php echo $nombre_sistemaAbreviado; ?> | <?php echo $nombre_sistema; ?> </title>
  <meta property="og:title" content="<?php echo $nombre_sistemaAbreviado; ?> | <?php echo $nombre_sistema; ?>">
  <meta name="author" content="Caio Águia de Chiara">
  <meta property="og:locale" content="es_ES">
  <meta name="description" content="Sistema de gestión de jugadores de la Liga de Naciones.">
  <meta property="og:description" content="Sistema de gestión de jugadores de la Liga de Naciones.">
  <link rel="canonical" href="https://delegados.ligadenaciones.cl">
  <meta property="og:url" content="https://delegados.ligadenaciones.cl">
  <meta property="og:site_name" content="<?php echo $nombre_sistemaAbreviado; ?> | <?php echo $nombre_sistema; ?>">
  <meta property="og:updated_time" content="1">
  <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
  <meta name="theme-color" content="#3063A0">
  <link href="https://fonts.googleapis.com/css?family=Fira+Sans:400,500,600" rel="stylesheet">
  <link rel="stylesheet" href="assets/vendor/open-iconic/font/css/open-iconic-bootstrap.min.css">
  <link rel="stylesheet" href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="assets/vendor/select2/css/select2.min.css">
  <link rel="stylesheet" href="assets/vendor/toastr/build/toastr.min.css">
  <link rel="stylesheet" href="assets/stylesheets/theme.min.css" data-skin="default">
  <link rel="stylesheet" href="assets/stylesheets/theme-dark.min.css" data-skin="dark">
  <link rel="stylesheet" href="assets/stylesheets/custom.css?v=<?php echo $hash ?>">
  <script>
    var skin = localStorage.getItem('skin') || 'default';
    var unusedLink = document.querySelector('link[data-skin]:not([data-skin="' + skin + '"])');
    unusedLink.setAttribute('rel', '');
    unusedLink.setAttribute('disabled', true);
  </script>
</head>

<body>
  <!--[if lt IE 10]>
    <div class="page-message" role="alert">You are using an <strong>outdated</strong> browser. Please <a class="alert-link" href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</div>
  <![endif]-->
  <main class="auth">
    <header id="auth-header" class="auth-header bg-dark">
      <h1><a href="https://delegados.ligadenaciones.cl"><img class="rounded" src="images/logo-liga-de-naciones.png" alt="" height="150"></a><span class="sr-only">Entrar</span></h1>
    </header>
    <form class="auth-form">
      <h1 class="text-center"></h1>
      <div class="form-group">
        <div class="form-label-group">
          <input type="text" id="inputUser" class="form-control placeholder-shown" autofocus="" maxlength="9"> <label for="inputUser">RUT</label>
          <small id="inputUserHelp" class="form-text text-muted text-left">Digite tu rut sin guión ni dígito verificador.</small>
        </div>
      </div>
      <div class="form-group">
        <div class="form-label-group">
          <input type="password" class="form-control placeholder-shown" value="" id="inputPass" autocomplete="current-password" maxlength="12" tabindex="0"><label for="inputPassword">Contraseña</label>
          <small id="inputUserHelp" class="form-text text-muted text-left">Digite tu contraseña.</small>
        </div>
      </div>
      <div class="form-group">
        <button class="btn btn-lg btn-primary btn-block" type="button" onclick="validaUserEnter();" tabindex="-1">Entrar</button>
      </div>
      <div class="text-center pt-2">
        <a href="recuperar-contrasena.php" class="link">¿Olvidó su contraseña?</a>
      </div>
    </form>
  </main>
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/popper.js/umd/popper.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>
  <script src="assets/javascript/theme.min.js"></script>
  <script src="includes/js/main.js?v=<?php echo $hash ?>"></script>
  <script src="assets/vendor/toastr/build/toastr.min.js"></script>
</body>

</html>