<?php
session_start();
$ctx = hash_init('sha1');
hash_update($ctx, 'SGF');
$hash = hash_final($ctx).date('DdMYHis');

include __DIR__.'/includes/class/ClassTodas.php';
$ClassTodas = new ClassTodas();

$nombre_sistema = $ClassTodas->title_system();
$nombre_sistemaAbreviado = $ClassTodas->title_abreviado();

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <!-- Required meta tags -->
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"><!-- End Required meta tags -->
  <!-- Begin SEO tag -->
  <title> <?php echo $nombre_sistemaAbreviado; ?> | <?php echo $nombre_sistema; ?> </title>
  <meta property="og:title" content="Eagle Design">
  <meta name="author" content="Caio Águia de Chiara">
  <meta property="og:locale" content="es_ES">
  <meta name="description" content="Sistema de Gestión de Ligas de Fútbol">
  <meta property="og:description" content="Sistema de Gestión de Ligas de Fútbol">
  <link rel="canonical" href="https://ligadenaciones.cl">
  <meta property="og:url" content="https://ligadenaciones.cl">
  <meta property="og:site_name" content="Sistema de Gestión de Ligas de Fútbol">
  <script type="application/ld+json">
    {
      "name": "Sistema de Gestión de Ligas de Fútbol",
      "description": "Sistema de Gestión de Ligas de Fútbol",
      "author":
      {
        "@type": "Person",
        "name": "Caio Águia"
      },
      "@type": "WebSite",
      "url": "https://www.ligadenaciones.cl",
      "headline": "Sistema de Gestión de Ligas de Fútbol",
      "@context": "http://schema.org"
    }
  </script><!-- End SEO tag -->
  <!-- Favicons -->
  <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
  <link rel="manifest" href="images/favicon/site.webmanifest">
  <link rel="mask-icon" href="images/favicon/safari-pinned-tab.svg" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#3063A0"><!-- Google font -->
  <link href="https://fonts.googleapis.com/css?family=Fira+Sans:400,500,600" rel="stylesheet"><!-- End Google font -->
  <!-- BEGIN PLUGINS STYLES -->
  <link rel="stylesheet" href="assets/vendor/fontawesome/css/all.css"><!-- END PLUGINS STYLES -->
  <!-- BEGIN THEME STYLES -->

  <link rel="stylesheet" href="assets/vendor/toastr/toastr.css">

  <link rel="stylesheet" href="assets/stylesheets/theme.min.css" data-skin="default">
  <link rel="stylesheet" href="assets/stylesheets/theme-dark.min.css" data-skin="dark">
  <link rel="stylesheet" href="assets/stylesheets/custom.css"><!-- Disable unused skin immediately -->
  <script>
    var skin = localStorage.getItem('skin') || 'default';
    var unusedLink = document.querySelector('link[data-skin]:not([data-skin="' + skin + '"])');
    unusedLink.setAttribute('rel', '');
    unusedLink.setAttribute('disabled', true);
  </script><!-- END THEME STYLES -->
  <link rel="stylesheet" href="assets/vendor/sweetalert2/sweetalert2.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>
<body>
  <!--[if lt IE 10]>
    <div class="page-message" role="alert">You are using an <strong>outdated</strong> browser. Please <a class="alert-link" href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</div>
  <![endif]-->
  <main class="auth">
    <header id="auth-header" class="auth-header bg-dark">
      <h1><img class="rounded" src="images/logo-liga-de-naciones.png" alt="" height=""><span class="sr-only">Recuperar Contraseña</span></h1>
    </header><!-- form -->
    <form class="auth-form">
      <!-- .form-group -->
      <div class="form-group">
        <div class="form-label-group">
          <input type="text" id="inputUser" class="form-control placeholder-shown" autofocus=""> <label for="inputUser">RUT</label>
          <small id="inputUserHelp" class="form-text text-muted text-left">Digite tu rut sin guión ni dígito verificador.</small>
        </div>
      </div><!-- /.form-group -->
      <!-- .form-group -->
      <div class="g-recaptcha mb-3" data-sitekey="6Ldqd6AUAAAAAPeRN1ARq_IdgOr_sahoq1hi_aZn"></div>
      <div class="align-items-center d-flex form-group justify-content-between">
        <button id="button_recover" class="btn btn-lg btn-primary" type="button" onclick="recuperarContrasena()" tabindex="-1">Recuperar Contraseña</button>
        <a href="login.php" class="btn btn-lg btn-success ml-3 h5 mb-0"> Entrar</a>
      </div><!-- /.form-group -->
    </form><!-- /.auth-form -->
    <!-- copyright -->
    <footer class="auth-footer">
      © 2022 Todos los derechos reservados <a target="_blank" href="https://www.ligadenaciones.cl">Liga de Naciones</a>
    </footer>
  </main>
  <!-- BEGIN BASE JS -->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/popper.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script> <!-- END BASE JS -->

    <!-- END PLUGINS JS -->
    <!-- BEGIN THEME JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.18.0/dist/sweetalert2.all.min.js"></script>

    <script src="assets/javascript/theme.min.js"></script>
    <script src="includes/js/main.js?v=<?php echo $hash?>"></script>
    <!-- END THEME JS -->
    <script src="assets/vendor/toastr/toastr.min.js"></script>
    <?php
//include('includes/debug.php');
    ?>
  </body>
  </html>