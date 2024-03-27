<?php
  session_start();
  date_default_timezone_set('America/Santiago');
  setlocale(LC_TIME, 'spanish');
  $date=date('Y-m-d H:i:s');
  include 'includes/class/ClassTodas.php';
  $ClassTodas = new ClassTodas();
  $nombre_sistema           = $ClassTodas->title_system();
  $nombre_sistemaAbreviado  = $ClassTodas->title_abreviado();
  $title_abreviado          = $ClassTodas->title_abreviado();
  $habilitaDebug            = $ClassTodas->habilitaDebug();
  $empresa                  = $ClassTodas->empresa();
  $title_system             = $ClassTodas->title_system();
  $subtitle_system          = $ClassTodas->subtitle_system();
  $dominioPrincipal         = $ClassTodas->dominioPrincipal();
  $Bienvenido               = $ClassTodas->title_system();
  $siglaSistema             = $ClassTodas->siglaSistema();
  if (isset($_REQUEST['logout'])) {
      session_destroy();
      echo '<script>location="login.php";</script>';
      exit;  
  }
  if(!isset($_SESSION[$siglaSistema.'_login'])){
      session_destroy();
      echo '<script>location="login.php";</script>';
      exit; 
  }
  if (isset($_REQUEST['limpiar'])) {
      session_destroy();
  }
  $datoUltimoIngreso = $ClassTodas->get_datoVariosWhereOrder('ingresosSistema',' where rutUsuario='.$_SESSION[$siglaSistema.'_rut'],'');
      foreach ($datoUltimoIngreso as $value_datoUltimoIngreso) {
        $rutUsuario_datoUltimoIngreso= $value_datoUltimoIngreso['rutUsuario'];
        $fecha_datoUltimoIngresoSinTrab = $value_datoUltimoIngreso['fecha_ingreso_tabla'];
        $dateUIngreso = date('d-m-Y', strtotime($fecha_datoUltimoIngresoSinTrab));
        $timeUIngreso = date('H:i:s', strtotime($fecha_datoUltimoIngresoSinTrab));
        $fecha_datoUltimoIngreso = $dateUIngreso.' '.$timeUIngreso;
  }
  $datosCredenciales = $ClassTodas->get_datoVariosWhereOrder('credenciales',' where rut='.$_SESSION[$siglaSistema.'_rut'],'');
  foreach ($datosCredenciales as $value_datosCredenciales) {
    $_SESSION[$siglaSistema.'_id']                            = $value_datosCredenciales['id'];
    $_SESSION[$siglaSistema.'_activo']                        = $value_datosCredenciales['activo'];
    $_SESSION[$siglaSistema.'_dv']                            = $value_datosCredenciales['dv'];
    $_SESSION[$siglaSistema.'_pideCambioPass']                = $value_datosCredenciales['pideCambioPass'];
    $_SESSION[$siglaSistema.'_nombreProveedor']               = $value_datosCredenciales['nombre'];
    $_SESSION[$siglaSistema.'_hashUnico']                     = $value_datosCredenciales['hashUnico'];
    $_SESSION[$siglaSistema.'_nivel']                         = $value_datosCredenciales['nivel'];
    $_SESSION[$siglaSistema.'_email']                         = $value_datosCredenciales['email'];   
  }

  $rutUsuario    = $_SESSION[$siglaSistema.'_rut'];
  $hashUsuario   = $_SESSION[$siglaSistema.'_hashUnico'];
  $idUsuario     = $_SESSION[$siglaSistema.'_id'];
  $nivelUsuario  = $_SESSION[$siglaSistema.'_nivel'];
  $emailUsuario  = $_SESSION[$siglaSistema.'_email'];

  if (!$_SESSION[$siglaSistema.'_login']== 1 && !$_SESSION[$siglaSistema.'_activo']== 1) {
    echo "<script>alert('Usted no está habilitado para usar este sistema. Por favor contacte al administrador de la liga.');location='login.php';</script>";
  }
  
  $ctx = hash_init('sha1');
  hash_update($ctx, 'SGF');
  $hash = hash_final($ctx).date('DdMYHis');

?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <title>  <?php echo $nombre_sistemaAbreviado; ?> | <?php echo $nombre_sistema; ?> </title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta property="og:title" content="<?php echo $nombre_sistemaAbreviado; ?> | <?php echo $nombre_sistema; ?>">
    <meta name="author" content="Caio Águia de Chiara">
    <meta property="og:locale" content="es_ES">
    <meta name="description" content="Sistema de gestión de jugadores de la Liga de Naciones.">
    <meta property="og:description" content="Sistema de gestión de jugadores de la Liga de Naciones.">
    <meta property="og:url" content="https://delegados.ligadenaciones.cl">
    <meta property="og:site_name" content="<?php echo $nombre_sistemaAbreviado; ?> | <?php echo $nombre_sistema; ?>">
    <meta name="theme-color" content="#3063A0">
    <link rel="canonical" href="https://delegados.ligadenaciones.cl">
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">
    <link rel="manifest" href="images/favicon/site.webmanifest">
    <link rel="mask-icon" href="images/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans:400,500,600" rel="stylesheet">
    <link rel="stylesheet" href="assets/vendor/open-iconic/font/css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/vendor/select2/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/w/bs4/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/datatables.min.css"/>
    <link rel="stylesheet" href="assets/vendor/toastr/build/toastr.min.css">
    <link rel="stylesheet" href="https://rawgit.com/enyo/dropzone/master/dist/dropzone.css">
    <link rel="stylesheet" href="assets/vendor/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="assets/stylesheets/theme.min.css" data-skin="default">
    <link rel="stylesheet" href="assets/stylesheets/theme-dark.min.css" data-skin="dark">
    <link rel="stylesheet" MEDIA="screen, print" href="assets/stylesheets/custom.css?v=<?php echo $hash?>">
    <script>
      var skin = localStorage.getItem('skin') || 'default';
      var isCompact = JSON.parse(localStorage.getItem('hasCompactMenu'));
      var disabledSkinStylesheet = document.querySelector('link[data-skin]:not([data-skin="' + skin + '"])');
      // Disable unused skin immediately
      disabledSkinStylesheet.setAttribute('rel', '');
      disabledSkinStylesheet.setAttribute('disabled', true);
      // add flag class to html immediately
      if (isCompact == true) document.querySelector('html').classList.add('preparing-compact-menu');
    </script><!-- END THEME STYLES -->
  </head>
  <body>
    <div class="app">
      <!--[if lt IE 10]>
      <div class="page-message" role="alert">You are using an <strong>outdated</strong> browser. Please <a class="alert-link" href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</div>
      <![endif]-->
      <header class="app-header app-header-dark bg-dark">
        <div class="top-bar">
          <div class="top-bar-brand">
            <button class="hamburger hamburger-squeeze mr-2" type="button" data-toggle="aside-menu" aria-label="toggle aside menu"><span class="hamburger-box"><span class="hamburger-inner"></span></span></button> 
            <a href="index.php"><img src="images/logo-liga-de-naciones.png" alt="Liga de Naciones" height="50"></a>
          </div>
          <div class="top-bar-list">
            <div class="top-bar-item px-2 d-md-none d-lg-none d-xl-none">
              <button class="hamburger hamburger-squeeze" type="button" data-toggle="aside" aria-label="toggle menu"><span class="hamburger-box"><span class="hamburger-inner"></span></span></button> 
              <a href="index.php" class="navbar-brand ml-3"><img src="images/logo-liga-de-naciones.png" alt="Liga de Naciones" height="50"></a>
            </div>
            <div class="top-bar-item top-bar-item-right px-0 d-flex">
              <ul class="header-nav nav">
                <li class="nav-item">
                  <a class="nav-link" href="https://www.instagram.com/liganaciones/" target="_blank"><span class="fab fa-instagram"></span></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="https://www.facebook.com/liganaciones/" target="_blank"><span class="fab fa-facebook"></span></a>
                </li>
              </ul>
              <div class="dropdown d-none d-md-flex">
                <button class="btn-account" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="user-avatar user-avatar-md"><img src="assets/images/avatars/unknown-profile.jpg" alt=""></span> <span class="account-summary pr-lg-4 d-none d-lg-block"><span class="account-name">Hola, <?php echo strtok(ucfirst(strtolower($_SESSION[$siglaSistema.'_nombreProveedor'])), ' '); ?>!</span> <span class="account-description"><?php echo $_SESSION[$siglaSistema.'_rut']."-". $_SESSION[$siglaSistema.'_dv']; ?></span></span></button> 
                <div class="dropdown-menu">
                  <div class="dropdown-arrow d-lg-none" x-arrow=""></div>
                  <div class="dropdown-arrow ml-3 d-none d-lg-block"></div>
                  <h6 class="dropdown-header d-none d-md-block d-lg-none"> <?php echo $_SESSION[$siglaSistema.'_nombreProveedor']; ?> </h6>
                  <a class="dropdown-item" onclick="alertCambiaPass('credenciales','Debe ingresar una Contraseña numérica de hasta 12 dígitos','<?php echo $idUsuario; ?>')" href="#"><span class="dropdown-icon fa fa-lock"></span> Cambiar Contraseña</a>
                  <a class="dropdown-item" href="?logout"><span class="dropdown-icon oi oi-account-logout"></span> Salir</a> 
                </div>
              </div>
            </div>
          </div>
        </div>
      </header>
      <aside class="app-aside app-aside-expand-md app-aside-light">
        <div class="aside-content">
          <header class="aside-header d-block d-md-none">
            <button class="btn-account" type="button" data-toggle="collapse" data-target="#dropdown-aside"><span class="user-avatar user-avatar-lg"><img src="assets/images/avatars/unknown-profile.jpg" alt=""></span> <span class="account-icon"><span class="fa fa-caret-down fa-lg"></span></span> <span class="account-summary"><span class="account-name">Hola, <?php echo strtok(ucfirst(strtolower($_SESSION[$siglaSistema.'_nombreProveedor'])), ' '); ?>!</span> <span class="account-description"><?php echo $_SESSION[$siglaSistema.'_rut']."-". $_SESSION[$siglaSistema.'_dv']; ?></span></span></button> 
            <div id="dropdown-aside" class="dropdown-aside collapse">
              <div class="pb-3">
                <a class="dropdown-item" onclick="alertCambiaPass('credenciales','Debe ingresar una Contraseña numérica de hasta 12 dígitos','<?php echo $idUsuario; ?>')" href="#"><span class="dropdown-icon fa fa-lock"></span> Cambiar Contraseña</a>
                <a class="dropdown-item" href="?logout"><span class="dropdown-icon oi oi-account-logout"></span> Salir</a>
              </div>
            </div>
          </header>
          <div class="aside-menu overflow-hidden">
            <nav id="stacked-menu" class="stacked-menu"></nav>
          </div>
          <footer class="aside-footer p-2">
            <input class="d-none" type="text" id="idUsuarioInput" value="<?php echo $idUsuario; ?>">
            <input class="d-none" type="text" id="emailUsuario" value="<?php echo $emailUsuario; ?>">
          </footer><!-- /Skin changer -->
        </div>
      </aside>
      <main class="app-main">
        <div class="wrapper">
          <div id="page" class="page">
            <?php if ($habilitaDebug == 1) { $muestraDebug=''; $keepAlive = 0; } else { $muestraDebug='hidden'; $keepAlive = 1; } ?>
            <header class="page-navs shadow-sm pr-3" <?php echo $muestraDebug; ?>>
              <a class="btn-account">
                <div class="user-avatar mt-n3">
                  <i class="fas fa-medkit"></i>
                </div>
                <div class="account-summary">
                  <h1 class="card-title"> Barra de Ayuda </h1>
                  <h6 class="card-subtitle text-muted"> Último ingreso al sistema: <?php echo $fecha_datoUltimoIngreso; ?> </h6>
                </div>
              </a>
              <div class="ml-auto">
                <button type="button" class="btn btn-light btn-icon" onclick="btnAsideOpenClose();"><i class="fa fa-angle-double-left"></i></button>
              </div>
            </header>
            <div class="page-inner">
              <div class="page-section">
                <div class="section-block">
                  <div class="card" id="card00">
                    <div class="card-header"><h5 id="opcion-title" class="mb-0"></h5></div>
                    <div class="card-body">
                      <section id="ayudaBotones"></section>
                      <section id="opcion"></section>
                      <section id="opcionRespuestasFinal"></section>
                    </div>
                  </div>
                  <div id="respuestaSVO_vxrutEscribe"></div>
                  <div class="page-sidebar">
                    <header class="sidebar-header">
                      <span class="h6" id="sidebar-title">Barra de Información</span>
                    </header>
                    <div class="sidebar-section aside-menu">
                      <button type="button" class="close mt-n1 d-none d-xl-none d-sm-block" onclick="Looper.toggleSidebar()" aria-label="Close"><span aria-hidden="true">×</span></button>
                      <p  id="sidebar-body"><?php include('includes/debug.php'); ?></p>
                    </div>
                    <footer class="aside-footer border-top p-3">
                      <button class="btn btn-info btn-block" onclick="btnAsideOpenClose();">Cerrar</button>                      
                    </footer>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main> 
    </div>

    <div class="modal modal-alert pr-0" id="modalGeneral" tabindex="-1" role="dialog" style="overflow-y: scroll;">
      <div id="modalTamano" class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitle"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div id="modalBody" class="modal-body"><p></p></div>
          <div id="respuestasEnviadasAmodal" class="modal-body"></div>
        </div>
      </div>
    </div>
    <div class="modal modal-alert" id="modalGeneral_otro" tabindex="-1" role="dialog">
      <div id="modalTamano_otro" class="modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitle_otro"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div id="modalBody_otro" class="modal-body"><p></p></div>
          <div id="respuestasEnviadasAmodal" class="modal-body"></div>
        </div>
      </div>
    </div>
  </body>
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/popper.js/umd/popper.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script> 
  <script src="assets/vendor/pace-progress/pace.min.js"></script>
  <script src="assets/vendor/stacked-menu/js/stacked-menu.min.js"></script>
  <script src="assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
  <script src="assets/vendor/select2/js/select2.min.js"></script>
  <script src="assets/vendor/select2/js/i18n/es.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.18.0/dist/sweetalert2.all.min.js"></script> 
  <script src="assets/vendor/moment/min/moment.min.js"></script>
  <script src="https://cdn.datatables.net/w/bs4/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/datatables.min.js"></script>
  <script src="assets/vendor/toastr/build/toastr.min.js"></script>
  <script src="assets/vendor/bootstrap-session-timeout/bootstrap-session-timeout.js"></script>
  <script src="https://rawgit.com/enyo/dropzone/master/dist/dropzone.js"></script> 
  <script src="assets/vendor/html2pdf.js/html2pdf.bundle.min.js"></script>
  <script src="assets/javascript/theme.min.js"></script> 
  <script src="includes/js/main.js?v=<?php echo $hash?>"></script>
  <script>
    $(document).ready(function(){ 
      muestraAccesosUsuario("<?php echo $idUsuario; ?>","10");
      muestraAccesosUsuario("<?php echo $idUsuario; ?>","20");
      muestraAccesosUsuario("<?php echo $idUsuario; ?>","30");
      muestraAccesosUsuario("<?php echo $idUsuario; ?>","40");
      muestraAccesosUsuario("<?php echo $idUsuario; ?>","50");
      muestraAccesosUsuario("<?php echo $idUsuario; ?>","60");
      muestraAccesosUsuario("<?php echo $idUsuario; ?>","70");
      muestraAccesosUsuario("<?php echo $idUsuario; ?>","80");
      muestraAccesosUsuario("<?php echo $idUsuario; ?>","90");
      muestraAccesosUsuario("<?php echo $idUsuario; ?>","100");
      cargaMenu("<?php echo $idUsuario; ?>");
      cargaDashBoard();
    });      
  </script>
  <?php 
  if ($keepAlive==0) {
  } else {
    $showAlive =<<<EOD
    <script>
      $.sessionTimeout({
          title: 'Su sesión está a punto de caducar',
          message: '',
          logoutButton: 'Salir',
          keepAliveButton: 'Seguir conectado',
          keepAliveUrl: 'alive.php',
          logoutUrl: '?logout',
          redirUrl: '?logout',
          warnAfter: 900000, // 15 minutos
          redirAfter: 1020000, // 17 minutos
          countdownSmart: true,
          countdownMessage: '',
          countdownBar: true
      });
    </script>
    EOD;
    echo $showAlive;
  }
  if($_SESSION[$siglaSistema.'_pideCambioPass']=="1"){
      echo '<script>alertCambiaPass("credenciales","Debe ingresar una Contraseña numérica de hasta 12 dígitos","'.$idUsuario.'");</script>';
  }
  ?>
</html>