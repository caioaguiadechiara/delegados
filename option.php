<?php
  session_start(); 
  setlocale(LC_TIME, 'spanish');  
  date_default_timezone_set('America/Santiago');
  $date = date('Y-m-d H:i:s');
  $SOLOdate = date('Y-m-d');
  include __DIR__.'/includes/class/ClassTodas.php';
  require_once __DIR__ . '/vendor/autoload.php';
  use Shuchkin\SimpleXLSX;
  $ClassTodas               = new ClassTodas();
  $siglaSistema             = $ClassTodas->siglaSistema();
  $get_base_url             = $ClassTodas->get_base_url();
  $depto_url                = $ClassTodas->depto_url();
  //DATOS DE SESIÓN
  $idUsuarioGeneral         = isset($_SESSION[$siglaSistema.'_id']) ? $_SESSION[$siglaSistema.'_id'] : null;
  $emailUsuarioGeneral      = isset($_SESSION[$siglaSistema.'_email']) ? $_SESSION[$siglaSistema.'_email'] : null;
  $nivelUsuarioGeneral      = isset($_SESSION[$siglaSistema.'_nivel']) ? $_SESSION[$siglaSistema.'_nivel'] : null;
  $nombreUsuarioGeneral     = isset($_SESSION[$siglaSistema.'_nombreProveedor']) ? $_SESSION[$siglaSistema.'_nombreProveedor'] : null;
  $rutUsuarioGeneral        = isset($_SESSION[$siglaSistema.'_rut']) ? $_SESSION[$siglaSistema.'_rut'] : null;
  $dvUsuarioGeneral         = isset($_SESSION[$siglaSistema.'_dv']) ? $_SESSION[$siglaSistema.'_dv'] : null;
  $equiposUsuarioGeneral    = isset($_SESSION[$siglaSistema.'_equipos']) ? $_SESSION[$siglaSistema.'_equipos'] : null;

  $type = $_GET['type'];
  if($type == ''){
    $type= $_POST['type'];
  }
  $times = array();
  $time = strtotime("00:00:00");
  $times["00:00:00"] = date("H:i",$time);
    for($i = 1;$i < 48;$i++) {
    $time = strtotime("+ 30 minutes",$time);
    $key = date("H:i:s",$time);
    $times[$key] = date("H:i",$time);
    }
  $hashUsuario = isset($_SESSION[$siglaSistema.'_hashUnico']) ? $_SESSION[$siglaSistema.'_hashUnico'] : null;

  
// Iniciar Switch
switch ($type){
  
  default:
    $defaultSistema = <<<EOD
      <!-- PANEL DEFAULT -->
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title">Error de Sistema</h3>
        </div>
        <div class="panel-body">
          <div><i class="far fa-thumbs-down fa-3x"></i> Lo lamentamos, pero ha ocurrido un Error.<br><strong>Descripción Error: #ERR1000: Sin 'case' en 'option'. <br>Case enviado: '$type'.</strong><br> Intente nuevamente o llame al Administrador del Sistema.</div>
        </div>
      </div>
      <!-- END PANEL DEFAULT -->
    EOD;
    echo $defaultSistema;
  break;
  
  case 'cargaMenu':
    $idUsuario =$_GET['idUsuario'];
    /* RESTRICCIONES DE MENUS */
    $btnSistema1 = '';
    $btnSistema2 = '';
    $btnSistema3 = '';
    $btnSistema4 = '';
    $btnSistema5 = '';
    $btnSistema6 = '';
    $btnSistema7 = '';
    $btnSistema8 = '';
    $btnSistema9 = '';
    $btnSistema10 = '';
    $btnSistema11 ='';
    $btnSistema12 ='';
    $btnSistema13 ='';
    $btnSistema21 ='';
    $btnSistema22 ='';
    $menuLi01='';
    $menuLi02='';
    $menuLi03='';
    $menuLi04='';
    $menuLi05='';
    $admin01='';
    if ($_SESSION[$siglaSistema.'_activo_10_2']=='1') {
      $btnSistema11=<<<EOD
        <li class="menu-item"> 
          <a href="#" id="listarJugadores" class="menu-link" onclick="listarJugadores('Listado de Jugadores','$idUsuarioGeneral','10','2','100');"> Listado de Jugadores </a>
        </li>
      EOD;
    }
    if ($_SESSION[$siglaSistema.'_activo_10_3']=='1' && (!empty($equiposUsuarioGeneral) || in_array($nivelUsuarioGeneral, array(8,9)))) {	
      $btnSistema12=<<<EOD
        <li class="menu-item">
          <a href="#" class="menu-link" onclick="formJugadores('Agregar Jugador', '', 'jugadores', 'agregarJugadores');"> Agregar Jugador</a>
        </li>
      EOD;
    } 
    if ($_SESSION[$siglaSistema.'_activo_10_1']=='1') {
      $btnSistema1 =<<<EOD
        <li id="modulo1" class="menu-item has-child">
          <a href="#" class="menu-link"><span class="menu-icon fas fa-running"></span><span class="menu-text"> Jugadores</span></a>
          <ul class="menu">
            $btnSistema11
            $btnSistema12
          </ul>
        </li>   
      EOD;
    }
    if ($_SESSION[$siglaSistema.'_nivel'] == 9 || $_SESSION[$siglaSistema.'_nivel'] == 8) {
      $admin01=<<<EOD
        <li class="menu-header">Administrar </li>
        <li class="menu-item has-child">
          <a href="#" class="menu-link"><span class="menu-icon fa fa-cog"></span></span> <span class="menu-text"> Ajustes</span></a>
          <ul class="menu">
            <li class="menu-item"> 
              <a href="#" id="opcSeguros" class="menu-link" onclick="opcionesSeguro('Opciones Generales','Todas');">Valor Seguro</span></a>
            </li> 
            <li class="menu-item"> 
              <a href="#" id="reglasnegocio" class="menu-link" onclick="reglasNegocio('Reglas de Negócio');">Reglas de Negócio</a>
            </li> 
          </ul>
        </li>
        <li class="menu-item has-child">
          <a href="#" class="menu-link" ><span class="menu-icon fa fa-user"></span></span> <span class="menu-text"> Usuarios</span></a>
          <ul class="menu">
            <li class="menu-item"> 
              <a href="#" id="listarUsuarios" class="menu-link" onclick="credencialesInicio();">Listar Usuarios</a>
            </li> 
            <li class="menu-item"> 
              <a href="#" id="agregarUsuarios" class="menu-link" onclick="credenciales_nuevo('credenciales','Agregar Usuario de Sistema');">Agregar Usuario</a>
            </li> 
          </ul>
        </li> 
        <li class="menu-item has-child">
          <a href="#" class="menu-link"><span class="menu-icon fas fa-futbol"></span></span> <span class="menu-text"> Competiciones</span></a>
          <ul class="menu">
            <li class="menu-item"> 
              <a href="#" id="listarCompeticiones" class="menu-link" onclick="listarCompeticiones('Listado de Competiciones','$idUsuarioGeneral','','');">Listar Competiciones</a>
            </li>
            <li class="menu-item"> 
              <a href="#"  class="menu-link" onclick="formCompeticiones('Agregar','','competiciones','agregarCompeticiones');">Agregar Competición</a>
            </li>
          </ul>
        </li>
        <li class="menu-item has-child">
          <a href="#" class="menu-link"><span class="menu-icon fas fa-user-shield"></span></span> <span class="menu-text"> Equipos</span></a>
          <ul class="menu">
            <li class="menu-item"> 
              <a href="#" id="listarEquipos" class="menu-link" onclick="listarEquipos('Listado de Equipos','$idUsuarioGeneral','','');">Listar Equipos</a>
            </li>
            <li class="menu-item"> 
              <a href="#" class="menu-link" onclick="formEquipos('Agregar','','equipos','agregarEquipos');">Agregar Equipo</a>
            </li>
          </ul>
        </li>
        <li class="menu-item"> 
          <a href="#" class="menu-link" onclick="formSumula('Generar Hoja del Partido');"><span class="menu-icon fa fa-file-excel"></span></span> <span class="menu-text"> Hoja del Partido</span></a>
        </li> 
        <li class="menu-item has-child">
          <a class="menu-link"><span class="menu-icon fas fa-chart-line"></span></span> <span class="menu-text"> Reportes</span></a>
          <ul class="menu">           
            <li class="menu-item"> 
              <a href="#" id="reporteInscritos" class="menu-link" onclick="reportesSeguros('Inscritos sin pagos efectuados','1');">Seguro Inscritos</a>
            </li>  
            <li class="menu-item"> 
              <a href="#" id="reporteSegurosPagados" class="menu-link" onclick="reportesSeguros('Total de seguros pagados','2');">Seguro Pagados</a>
            </li> 
            <li class="menu-item"> 
              <a href="#" id="reporteSaldoSeguro" class="menu-link" onclick="reportesSeguros('Saldo financiero asegurados','3');">Seguro Saldo Financiero </a>
            </li> 
          </ul>
        </li>
        <li class="menu-item">
          <a class="menu-link" onclick="importarDB('Importar Jugadores')"><span class="menu-icon fa fa-upload"></span></span> <span class="menu-text"> Carga Masiva</span></a>
        </li>
      EOD;
    }
    $menuCrear=<<<EOD
      <ul id="cargaMenu" class="menu">
        <li class="menu-item">
          <a href="index.php" class="menu-link"><span class="menu-icon fas fa-home"></span> <span class="menu-text">Inicio </span></a>
        </li>
        $btnSistema1
        <li class="menu-item">
          <a href="$depto_url" class="menu-link" target="_blank"><span class="menu-icon fas fa-ticket-alt"></span> <span class="menu-text">Soporte </span></a>
        </li>
        $admin01
      </ul>
    EOD;
    echo $menuCrear;
  break;

  case 'validaUserEnter':
    $emailVerifica = $_GET['inputUser'];
    $passVerifica = $_GET['inputPass'];
    //$limpiaCaracteresEmail  = $emailVerifica;
    $limpiaCaracteresEmail  = $ClassTodas->sanitize($emailVerifica);
    //$limpiaCaracteresPass   = $passVerifica;
    $limpiaCaracteresPass   = $ClassTodas->sanitize($passVerifica);
    $usuarioOK = $ClassTodas->validaUserEnter('credenciales',$limpiaCaracteresEmail,$limpiaCaracteresPass);
    if ($usuarioOK == 1) {
      //echo '<br>Usuario OK<br>';
    //  $datosUsuarios = $ClassTodas->get_datoUsuarioSistemaXUsuario($limpiaCaracteresEmail, 'proveedores','externo');
      $_SESSION[$siglaSistema.'_nivel']        = 0;
      $_SESSION[$siglaSistema.'_login']        = 1;
      $_SESSION[$siglaSistema.'_tipoUsuario']  = 0; // 1: interno o 0:externo
      $_SESSION[$siglaSistema.'_rut']          = $limpiaCaracteresEmail;
      $campos_ingreso = "rutUsuario"; 
      $datos_ingreso="'{$limpiaCaracteresEmail}'";
      $ejecuta_ingreso = $ClassTodas->insertCosasVarias('ingresos_sistema',$campos_ingreso,$limpiaCaracteresEmail);
      echo 1;
    } else {
      echo 0;
    }
  break;

  case 'recuperarContrasena':
    $inputUser = $_GET['inputUser'];
    $inputUserLimpio  = $ClassTodas->sanitize($inputUser);
    $getContrasena = $ClassTodas->get_datoVariosWhereOrder('credenciales','WHERE rut='.$inputUserLimpio,'LIMIT 1');
    foreach ($getContrasena as $value){
      $contrasena = $value['password'];
      $email = $value['email'];
    }
    $emailEnviado = $email; //just for better format
    $asuntoEnviado = 'Recuperar Contraseña';
    $bodyEnviado =<<<EOD
      <p class="mt-3">Hola<br>Tu contraseña es: $contrasena</p>
      <a href="$get_base_url" target="_blank" class="mb-3"> Ingresa al sistema aquí</a>
    EOD;
    $enviaContrasenaCorreo = $ClassTodas->enviaCorreoVariosTipos('recuperarContrasena', $emailEnviado, $asuntoEnviado, $bodyEnviado);
    if (empty($getContrasena)) {
      $returnRecuperarContrasena = '0';
    } else {
      if ($enviaContrasenaCorreo == '0') {
        $returnRecuperarContrasena = '2';
      } else {
        $returnRecuperarContrasena = 'yes';
      }
    }   
    echo $returnRecuperarContrasena;
  break;

  case 'CambiaPassword':
    $nuevaPass = $_GET['nuevaPass'];
    $idUsuario = $_GET['idUsuario'];
    $tabla = $_GET['tabla'];
    $solicitaCambioPass = $_GET['idSolicitaCambioPass'];
    $CambiaPasswordEnviaDato = $ClassTodas->CambiaPassword($tabla,$nuevaPass,$idUsuario,$solicitaCambioPass);
    echo $CambiaPasswordEnviaDato;
  break;

  case 'cambiaPassFromCambioContrasenaInicioSistema':
    $id = $_GET['id'];
    $password = $_GET['password'];
    $cambiaPassFromCambioContrasenaInicioSistemaEjecuta = $ClassTodas->cambiaPassFromCambioContrasenaInicioSistema('credenciales',$password,$id);
    $set_cambiaPideCambioPass="pideCambioPass=0";
    $where_cambiaPideCambioPass="id={$id}";
    $cambiaPideCambioPass   = $ClassTodas->actualizaCosasVariasSetWhere('credenciales',$set_cambiaPideCambioPass,$where_cambiaPideCambioPass);
    $_SESSION['pideCambioPass']=0;
  break;

  case 'eliminarLinea':
    $tabla = $_GET['tabla'];
    $idLinea = $_GET['idLinea'];
    if ($tabla == 'credenciales') {
      $eliminarCredenciales = $ClassTodas->eliminarLinea('credenciales_acciones','idUsuario',$idLinea);
      $eliminarCredenciales = $ClassTodas->eliminarLinea('credenciales_equipos','id_credencial',$idLinea);
      $eliminarLinea_ejecuta = $ClassTodas->eliminarLinea($tabla,'id',$idLinea);
    }elseif($tabla == 'equipos') {      
      $eliminarJugadores = $ClassTodas->eliminarLinea('jugadores_equipos','id_equipo',$idLinea);
      $eliminarJugadores = $ClassTodas->eliminarLinea('jugadores','id_equipo',$idLinea);
      $eliminarLinea_ejecuta = $ClassTodas->eliminarLinea($tabla,'id',$idLinea);
    }elseif($tabla == 'jugadores'){
      $checkSiAsegurado = $ClassTodas->get_datoVariosWhereOrder($tabla,'WHERE id='.$idLinea,'');
      foreach($checkSiAsegurado as $row) { $aseguradoCheck = $row['asegurado']; }
      if($aseguradoCheck == 1){
        $checkJugEqp = $ClassTodas->get_datoVariosWhereOrderInformes("SELECT count(id) as total FROM jugadores_equipos WHERE id_jugador='$idLinea'");
        $totalEquipos = $checkJugEqp[0]['total'];
        $eliminarLinea_ejecuta = $ClassTodas->actualizaCosasVariasSetWhere($tabla,'activo=0','id='.$idLinea);
      } else {
        $eliminarJugadores = $ClassTodas->eliminarLinea('jugadores_equipos','id_jugador',$idLinea);
        $eliminarLinea_ejecuta = $ClassTodas->eliminarLinea($tabla,'id',$idLinea);
      }
    } else {
      $eliminarLinea_ejecuta = $ClassTodas->eliminarLinea($tabla,'id',$idLinea);
    }
    echo $eliminarLinea_ejecuta;
  break;

  case 'eliminarMasivo':
    $arrayIds = json_decode(stripslashes($_GET['arrayIds']));
    $ejSuma = 0;
    $count = 0;
    foreach($arrayIds as $value){
      $ej = $ClassTodas->eliminarLinea('jugadores','id',$value);
      $ejSuma = $ejSuma + $ej;
      $count++;
    }
    if($ejSuma === $count){      
      echo 1;
    } else {
      echo 0;
    }
  break;

  case 'eliminarLineaTodas':
    $tabla = $_GET['tabla'];
    $idLinea = $_GET['idLinea'];
    $nombreCampo = $_GET['nombreCampo'];
    $eliminarLinea_ejecuta = $ClassTodas->eliminarLineaTodas($tabla,$idLinea,$nombreCampo);
    echo $eliminarLinea_ejecuta;
  break;

  case 'cambioCheckbox_ejecuta':
    $tabla = $_GET['tabla'];
    $campoAcambiar = $_GET['campoAcambiar'];
    $idLinea = $_GET['idLinea'];
    $valorCheckbox = $_GET['valorCheckbox'];
    if ($valorCheckbox=="true") {$valorCheckbox_num="1";}
    if ($valorCheckbox=="false") {$valorCheckbox_num="0";} 
    $set="{$campoAcambiar}={$valorCheckbox_num}";
    $where="id={$idLinea}";
    $cambioCheckbox_ejecutaResp   = $ClassTodas->actualizaCosasVariasSetWhere($tabla,$set,$where);
    echo $cambioCheckbox_ejecutaResp;
  break;

  case 'credencialesInicio':
    $imprime_credencialesInicio = '';
    $get_credencialesInicio = $ClassTodas->get_datoVariosWhereOrder('credenciales','',' order by rut desc');
    if (empty($get_credencialesInicio)) {
      $imprime_credencialesInicio = '<tr><td colspan="100">No existen Datos.</td></tr>';
    } else {
      foreach ($get_credencialesInicio as $value) {
        $id             = $value['id'];
        $rut            = $value['rut'];
        $dv             = $value['dv'];
        $nombre         = $value['nombre'];
        $email          = $value['email'];
        $password       = $value['password'];
        $nivel          = $value['nivel'];
        if ($nivel == "0") {$nivelTXT='<span class="badge badge-subtle badge-danger">Delegado</span>';}
        if ($nivel == "8") {$nivelTXT='<span class="badge badge-subtle badge-success">Usuario Administrador</span>';}
        if ($nivel == "9") {$nivelTXT='<span class="badge badge-subtle badge-warning">Super Admin</span>';}
        $activo      = $value['activo'];
        $accesoCreado     = $value['accesoCreado'];    
        if ($activo == 1) {
          $imprime_activo=<<<EOD
            <div class="custom-control custom-switch" style="padding-top:5px;">
              <input type="checkbox" class="custom-control-input" id="inputCambia_activo_$id" checked  onclick="cambioCheckbox('credenciales','#inputCambia_activo_$id','activo','$id')">
              <label class="custom-control-label" for="inputCambia_activo_$id"></label>
            </div>
          EOD;
        }
        if ($activo == 0 || $activo == '0')  {
          $imprime_activo=<<<EOD
            <div class="custom-control custom-switch" style="padding-top:5px;">
              <input type="checkbox" class="custom-control-input" id="inputCambia_activo_$id"  onclick="cambioCheckbox('credenciales','#inputCambia_activo_$id','activo','$id')">
              <label class="custom-control-label" for="inputCambia_activo_$id"></label>
            </div>
          EOD;
        }
        $pideCambioPass      = $value['pideCambioPass'];
        if ($pideCambioPass==1) {
          $imprime_pideCambioPass=<<<EOD
            <div class="custom-control custom-switch" style="padding-top:5px;">
              <input type="checkbox" class="custom-control-input" id="inputCambia_pideCambioPass_$id" checked onclick="cambioCheckbox('credenciales','#inputCambia_pideCambioPass_$id','pideCambioPass','$id')">
              <label class="custom-control-label" for="inputCambia_pideCambioPass_$id"></label>
            </div>
          EOD;
        }
        if ($pideCambioPass == 0 || $pideCambioPass == '0')  {
          $imprime_pideCambioPass=<<<EOD
            <div class="custom-control custom-switch" style="padding-top:5px;">
              <input type="checkbox" class="custom-control-input" id="inputCambia_pideCambioPass_$id" onclick="cambioCheckbox('credenciales','#inputCambia_pideCambioPass_$id','pideCambioPass','$id')">
              <label class="custom-control-label" for="inputCambia_pideCambioPass_$id"></label>
            </div>
          EOD;
        }
        if ($accesoCreado == '0' || $accesoCreado == 0) {
          $showGeneraAccesos =<<<EOD
            <a class="btn btn-sm btn-warning" title="Agregar acceso usuario" alt="Agregar acceso usuario" onclick="generaAccesosSistemaFinal('$id')"><i class="fas fa-lock"></i> Generar Accesos</a>
          EOD;
        } else { 
          $showGeneraAccesos =<<<EOD
            <a class="btn btn-sm btn-icon btn-secondary" title="Editar acceso usuario" alt="Editar acceso usuario" onclick="modalEditarDatosUsuarioSistema('Editar acceso usuario','credenciales','$id','accesosUsuarios')"><i class="fas fa-lock"></i></a> 
            <a class="btn btn-sm btn-icon btn-secondary" title="Enviar Contraseña" alt="Enviar Contraseña" onclick="enviarCorreoVarios('credenciales',$id,'$email','deCredenciales')"><i class="far fa-paper-plane"></i></a>
            <a class="btn btn-sm btn-icon btn-secondary" title="Editar usuario" alt="Editar usuario" onclick="credenciales_editar('credenciales','Editar Usuarios del Sistema','$id')"><i class="fa fa-edit"></i></a>
            <a class="btn btn-sm btn-icon btn-secondary" title="Eliminar línea usuario" alt="Eliminar línea usuario" title="ELIMINAR" onclick="eliminarLinea('credenciales','$id');"><i class="far fa-trash-alt"></i></a>
          EOD;
        }

        $equiposUsuario = '';
        $datosEquipos = $ClassTodas->get_datoVariosWhereOrderInformes("SELECT eq.nombre FROM credenciales_equipos as ce LEFT JOIN equipos as eq ON eq.id = ce.id_equipo WHERE ce.id_credencial=$id");
        if(empty($datosEquipos)){
          $equiposUsuario = 'Sin Equipo';
        } else {
          foreach($datosEquipos as $value){
            $equiposUsuario .= '<span class="badge badge-subtle badge-primary m-1">'.$value['nombre'].'</span>';
          }
        }

        $imprime_credencialesInicio .=<<<EOD
          <tr>
            <td hidden>$id</td>
            <td>$rut-$dv</td>
            <td>$nombre</td>
            <td>$equiposUsuario</td>
            <td>$nivelTXT</td>
            <td nowrap class="text-center">$imprime_activo</td>
            <td nowrap class="text-center">   
              $showGeneraAccesos
            </td>
          </tr>
        EOD;
      }
    }
    $tabla_credencialesInicio =<<<EOD
      <div class="col-12 d-flex mb-3 justify-content-end">       
        <button type="button" class="btn btn-primary btn-lg" title="Agregar" onclick="credenciales_nuevo('credenciales','Agregar Usuarios del Sistema');"><i class="fas fa-plus pr-1"></i>Agregar Usuario</button>
      </div>
      <div class="table-responsive-lg">
        <table class="table  table-condensed table-bordered table-sm table-striped input-group-reflow no-footer" id="tablacredencialesInicio">
              <thead>
                <tr>
                  <th hidden>Id</th>
                  <th>Rut</th>
                  <th>Nombre</th>
                  <th>Equipo</th>
                  <th>Nivel</th>
                  <th>Activo</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
                $imprime_credencialesInicio
              </tbody>
        </table>
      </div>
    EOD;
    echo $tabla_credencialesInicio;
  break;

  case 'enviarCorreoVarios':
    $tabla = $_GET['tabla'];
    $idEnviado = $_GET['idEnviado'];
    $emailAenviar = $_GET['emailAenviar'];
    $desdeDonde = $_GET['desdeDonde'];
    if ($desdeDonde=='deCredenciales') {
      $get_deCredenciales = $ClassTodas->get_datoVariosWhereOrder('credenciales',' where id='.$idEnviado,'');
      if (empty($get_deCredenciales)) {
      } else {
        foreach ($get_deCredenciales as $value) {
              $id_deCredenciales          = $value['id'];
              $rut_deCredenciales         = $value['rut'];
              $dv_deCredenciales          = $value['dv'];
              $nombre_deCredenciales      = $value['nombre'];
              $email_deCredenciales       = $value['email'];
              $validador1_deCredenciales  = $value['nombreValidador1'];
              $validador2_deCredenciales  = $value['nombreValidador2'];
              $validador3_deCredenciales  = $value['nombreValidador3'];
              $password_deCredenciales    = $value['password'];
        }
      }
      $Subject = "Recuperación Usuario y Contraseña - $date";
      $body = <<<EOD
        <br>Estimado(a),
        <br>Los datos para en ingreso al sistema son:
        <br>
        <br>Usuario: $rut_deCredenciales (Sin guión ni dígito verificador. ej:79775100)
        <br>Contraseña: $password_deCredenciales
        <br>
        <br>
        <br>Para entrar al sistema haga clic <a href="$get_base_url" target="">aquí</a>
        <br>
        <br>
        <br>
        <br>*** NO RESPONDA ESTE EMAIL YA QUE NO ES SUPERVISADO ***
        <br>
        <p>Fecha y Hora: $date</p>
        <br>
        <br>
        <br>
        <br>
      EOD;
      $enviarCorreo =$ClassTodas->enviaCorreoVariosTipos('enviaUsuarioPass',$emailAenviar,$Subject,$body);
      echo $enviarCorreo;
    }
  break;

  case 'credenciales_nuevo':
    $tabla = $_GET['tabla'];
    $imprime_datosCredenciales = '';
    $optEmptyAgrega = '';
    $valoresOptEquipos = '';
   $get_datosCredenciale = $ClassTodas->get_datoVariosWhereOrder('credenciales','',' order by nombre asc');
    if (empty($get_datosCredenciale)) {
      // do nothing
    } else {
      foreach ($get_datosCredenciale as $value_get_datosCredenciale) {
        $id_get_datosCredenciale         = $value_get_datosCredenciale['id'];
        $nombres_get_datosCredenciale    = $value_get_datosCredenciale['nombre'];
        $email_get_datosCredenciale      = $value_get_datosCredenciale['email'];
        $rut_get_datosCredenciale        = $value_get_datosCredenciale['rut'];
        $dv_get_datosCredenciale         = $value_get_datosCredenciale['dv'];
        $imprime_datosCredenciales .=<<<EOD
          <option value="$nombres_get_datosCredenciale" data-emailcredencial="$email_get_datosCredenciale">$nombres_get_datosCredenciale - $email_get_datosCredenciale </option>
        EOD;
      }
    }
    if ($tabla=='credenciales') {
      $buscaDatosEquipos = $ClassTodas->get_datoVariosWhereOrder('equipos','','ORDER BY nombre ASC');
      foreach ($buscaDatosEquipos as $value) {
        $id_equipo            = $value['id'];
        $nombreEquipo         = $value['nombre']; 
        $id_compet            = $value['id_competicion']; 
        $buscaDatosCompet = $ClassTodas->get_datoVariosWhereOrder('competiciones','WHERE id='.$id_compet,'ORDER BY nombre ASC');
        foreach ($buscaDatosCompet as $value) {
          $nombreCompet         = $value['nombre']; 
        }
        $valoresOptEquipos .= "<option value='$id_equipo'>$nombreEquipo ($nombreCompet)</option>";
      }
      $passwordInicial=$ClassTodas->generatePassword(5);
      $hashCredencialesProveedores=$ClassTodas->generaHash('CES_Proveedores');
      $imprime_credenciales_nuevo=<<<EOD
      <form class="pt-3">
        <div class="form-row">
          <div class="col-9 col-md-4 mb-3">
            <label >Rut (Usuario) <abbr title="Required">*</abbr></label>
            <input type="text" class="form-control" id="inputNuevo_rut" name="inputNuevo_rut" placeholder="Sólo números" value="" required maxlength="8" onkeypress="return permite(event, 'num');" onBlur="entregaDV('inputNuevo_rut','inputNuevo_dv','inputNuevo_nombre');">
              <small>Este será el Usuario para el sistema</small>
          </div>
          <div class="col-3 col-md-2 mb-3">
            <label>Dv <abbr title="Required">*</abbr></label>
            <input type="text" class="form-control" id="inputNuevo_dv" name="inputNuevo_dv" placeholder="dv" value="" required maxlength="1" onkeypress="return permite(event, 'dv');" disabled>
          </div>
          <div class="col-12 col-md-6 mb-3">
            <label>Nombre</label>
            <input type="text" class="form-control text-uppercase" id="inputNuevo_nombre" name="inputNuevo_nombre" placeholder="Nombre completo" disabled>
            <div class="custom-control custom-checkbox pt-1">
              <input type="checkbox" class="custom-control-input" id="desbloqueaNombre"> <label class="custom-control-label" for="desbloqueaNombre"> Desbloquear</label>
            </div>        
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="col-12 col-md-6 mb-3">
            <label>Email <abbr title="Required">*</abbr></label>
            <input type="text" class="form-control" id="inputNuevo_email" name="inputNuevo_email" placeholder="Ingrese Email" required>
          </div>
          <div class="col-12 col-md-6 mb-3">
            <label>Contraseña (12 dígitos) <abbr title="Required">*</abbr></label>
            <input type="text" class="form-control" id="inputNuevo_pass" placeholder="Contraseña" required maxlength="12" onkeypress="return permite(event, 'num');" value="$passwordInicial">
          </div> 
        </div>
        <div class="form-row">
          <div class="col-12 col-md-6 mb-3">
            <label>Nivel <abbr title="Required">*</abbr></label>
              <select class="form-control custom-select" id="inputNuevo_nivel1" name="inputNuevo_nivel1" required="">
                <option value="99"> Seleccione...</option>
                <option value="0"> Delegado</option>
                <option value="8"> Usuario Administrador</option>
                <option value="9"> Super Admin</option>
              </select>
          </div>
          <div class="col-12 col-md-6 mb-3">
            <label>Equipo</label>
              <select class="form-control custom-select" id="inputNuevo_equipo" name="inputNuevo_equipo[]" multiple="multiple">
                <option></option>
                $valoresOptEquipos
              </select>
          </div>
        </div>
        <div class="form-row d-none">
          <div class="col-12 col-md-12 mb-3">
            <label >Código Único</label>
            <input type="text" class="form-control" id="inputNuevo_hash" placeholder="Código Único" value="$hashCredencialesProveedores" disabled>
          </div>
        </div>
      </form>
      <div id="modalFooter" class="modal-footer">
        <button type="button" class="btn btn-danger" onclick="$('#listarUsuarios').click();"><i class="fas fa-arrow-left"></i> Volver</button>
        <button type="button" class="btn btn-primary" onclick="credenciales_nuevoEjecuta('credenciales');"><i class="far fa-save"></i> Guardar</button>
      </div>
    EOD;
    }
    echo $imprime_credenciales_nuevo;
  break;

  case 'credenciales_nuevoEjecuta':
    $nuevaCredencialEjec  = '';
    $tabla                = $_GET['tabla'];
    $inputNuevo_rut       = $_GET['inputNuevo_rut'];
    $inputNuevo_dv        = $_GET['inputNuevo_dv'];
    $inputNuevo_nombre    = mb_strtoupper($_GET['inputNuevo_nombre']);
    $inputNuevo_email     = $_GET['inputNuevo_email'];
    $inputNuevo_pass      = $_GET['inputNuevo_pass'];
    $inputNuevo_nivel     = $_GET['inputNuevo_nivel'];
    $inputNuevo_hash      = $_GET['inputNuevo_hash'];
    $getArrayEquipos      = array_column(json_decode($_GET['inputNuevo_equipo']), $inputNuevo_equipo);
    $verificaRUTCred      = $ClassTodas->verificaRut('credenciales',$inputNuevo_rut);

    if($verificaRUTCred == 0) {
      $campos = "rut,dv,nombre,email,password,nivel,hashUnico,pideCambioPass"; 
      $datos="'{$inputNuevo_rut}','{$inputNuevo_dv}','{$inputNuevo_nombre}','{$inputNuevo_email}','{$inputNuevo_pass}','{$inputNuevo_nivel}','{$inputNuevo_hash}','1'";
      $getIDadded = $ClassTodas->insertCosasVariasDevuelveId($tabla,$campos,$datos);

      if(empty($getIDadded)){
        echo 0;
      } else {
        if(empty($getArrayEquipos)) {
          $ejecutaCP = 1;
        } else {
          foreach ($getArrayEquipos as $key => $value) {
            $campos2 = "id_credencial,id_equipo"; 
            $datos2 = "'{$getIDadded}','{$value}'";
            $ejecutaCP = $ClassTodas->insertCosasVarias('credenciales_equipos',$campos2,$datos2);    
          } 
        }
        echo $ejecutaCP;
      }

    } else {
      echo 2;
    }
  break;

  case 'credenciales_editar':
    $optEmptyAgrega            = '';
    $option_nivel_getAN        = '';
    $tabla                     = $_GET['tabla'];
    $idUsuario                 = $_GET['idUsuario'];
    $imprime_datosCredenciales = '';
    $disabled                  = '';
    $displayEquipo             = '';
    $optAgrega                 = '';
    $valoresOptEquipos         = '';
    $valoresOptEquiposSel      = '';
    $get_credencialesInicio = $ClassTodas->get_datoVariosWhereOrder('credenciales','WHERE id='.$idUsuario,'');
    //$getDatosepBorradores = array();
    if (empty($get_credencialesInicio)) {
      $imprime_credencialesInicio = '';
    } else {
      foreach ($get_credencialesInicio as $value) {
          $id_get               = $value['id'];
          $rut_get              = $value['rut'];
          $dv_get               = $value['dv'];
          $nombre_get           = $value['nombre'];
          $password_get         = $value['password'];
          $email_get            = $value['email'];
          $hashUnico_get        = $value['hashUnico'];
          $nivel_get            = $value['nivel'];
          $arrayNiveles = [
            "0" => "Delegado",
            "8" => "Administrador",
            "9" => "Super Admin",
        ];
        foreach($arrayNiveles as $key => $valueNiveles) {
          if ($key == $nivel_get) {
            $nivelSeleccionado = "selected";
          } else {
            $nivelSeleccionado = "";
            $optEmptyAgrega = '<option></option>';
          }
          $option_nivel_getAN .=<<<EOD
            <option value="$key" $nivelSeleccionado>$key - $valueNiveles</option>        
          EOD;
        }
      }

      $arrayEquipos = array();
      $datosEquipos = $ClassTodas->get_datoVariosWhereOrder('credenciales_equipos','WHERE id_credencial='.$idUsuario,'');
      if($datosEquipos){
        foreach($datosEquipos as $row2){
          $id_equipo_de = $row2['id_equipo'];
          array_push($arrayEquipos,$id_equipo_de);
        }
      }
      $gp2 = $ClassTodas->get_datoVariosWhereOrder('equipos','','');
      foreach($gp2 as $gprow){
        $id_gp2     = $gprow['id'];
        $nombre_gp2 = $gprow['nombre'];

        if(in_array($id_gp2, $arrayEquipos)){
          $valoresOptEquiposSel .=<<<EOD
            <option value="$id_gp2" selected>$nombre_gp2</option>
          EOD;
        } 
        $valoresOptEquipos .=<<<EOD
          <option value="$id_gp2">$nombre_gp2</option>
        EOD;
      }
    }
    $get_datosCredenciale = $ClassTodas->get_datoVariosWhereOrder('credenciales','',' order by nombre asc');
    //$getDatosepBorradores = array();
    if (empty($get_datosCredenciale)) {
      $imprime_datosCredenciales = '';
    } else {
      foreach ($get_datosCredenciale as $value_get_datosCredenciale) {
        $id_get_datosCredenciale         = $value_get_datosCredenciale['id'];
        $nombres_get_datosCredenciale    = $value_get_datosCredenciale['nombre'];
        $email_get_datosCredenciale      = $value_get_datosCredenciale['email'];
        $rut_get_datosCredenciale        = $value_get_datosCredenciale['rut'];
        $dv_get_datosCredenciale         = $value_get_datosCredenciale['dv'];
        $imprime_datosCredenciales .=<<<EOD
          <option value="$nombres_get_datosCredenciale" data-emailcredencial="$email_get_datosCredenciale">$nombres_get_datosCredenciale - $email_get_datosCredenciale </option>
        EOD;
      }
    }
    if ($tabla=='credenciales') {
      $imprime_credenciales_nuevo=<<<EOD
        <form class="pt-3">
          <div class="form-row">
            <div class="col-9 col-md-4 mb-3">
              <label>Rut (Usuario) <abbr title="Required">*</abbr></label>
              <input type="text" class="form-control" id="inputNuevo_rut" name="inputNuevo_rut" placeholder="Sólo números" required maxlength="8" onkeypress="return permite(event, 'num');" onBlur="entregaDV('inputNuevo_rut','inputNuevo_dv','inputNuevo_nombre');" value="$rut_get" disabled>
            </div>
            <div class="col-3 col-md-2 mb-3">
              <label>Dv <abbr title="Required">*</abbr></label>
              <input type="text" class="form-control" id="inputNuevo_dv" name="inputNuevo_dv" placeholder="dv" required maxlength="1" onkeypress="return permite(event, 'dv');" disabled value="$dv_get">
            </div>
            <div class="col-12 col-md-6 mb-3">
              <label>Nombre</label>
                <input type="text" class="form-control text-uppercase" id="inputNuevo_nombre" name="inputNuevo_nombre" placeholder="Nombre completo" disabled value="$nombre_get">       
              </div>
            </div>
          </div>
          <div class="form-row">
            <div class="col-12 col-md-6 mb-3">
              <label>Email <abbr title="Required">*</abbr></label>
              <input type="text" class="form-control" id="inputNuevo_email" name="inputNuevo_email" placeholder="Ingrese Email" required value="$email_get">
            </div>
            <div class="col-12 col-md-6 mb-3">
              <label>Contraseña (12 dígitos) <abbr title="Required">*</abbr></label>
              <input type="text" class="form-control" id="inputNuevo_pass" placeholder="Contraseña" required maxlength="12" onkeypress="return permite(event, 'num');" value="$password_get">
            </div>  
          </div>
          <div class="form-row">  
            <div class="col-12 col-12 col-md-6 mb-3">
              <label>Nivel <abbr title="Required">*</abbr></label>
                <select class="form-control custom-select" id="inputNuevo_nivel" name="inputNuevo_nivel" required="">
                  {$optAgrega}
                  $option_nivel_getAN
                </select>
            </div>
            <div class="col-12 col-md-6 mb-3 $displayEquipo">
                <label>Equipo <abbr title="Required">*</abbr></label>
                  <select class="form-control" id="inputNuevo_equipo" name="inputNuevo_equipo[]" multiple="multiple">
                    <option></option>
                    $valoresOptEquiposSel
                    $valoresOptEquipos
                  </select>
              </div>
          </div>
          <div class="form-row">
            <div class="col-12 col-md-12 mb-3">
              <label >Código Único</label>
              <input type="text" class="form-control" id="inputNuevo_hash" placeholder="Código Único" value="$hashUnico_get" disabled>
            </div>
          </div>
        </form>
        <div id="modalFooter" class="modal-footer">
          <button type="button" class="btn btn-danger" onclick="$('#listarUsuarios').click();"><i class="fas fa-arrow-left"></i> Volver</button>
          <button type="button" class="btn btn-primary" onclick="credenciales_editarEjecuta('$tabla','$idUsuario');"><i class="far fa-save"></i> Guardar</button>
        </div>
      EOD;
    }
    echo $imprime_credenciales_nuevo;
  break;
  
  case 'credenciales_editarEjecuta':
    $tabla              = $_GET['tabla'];
    $inputNuevo_rut     = $_GET['inputNuevo_rut'];
    $inputNuevo_dv      = $_GET['inputNuevo_dv'];
    $inputNuevo_nombre  = $_GET['inputNuevo_nombre'];
    $inputNuevo_email   = $_GET['inputNuevo_email'];
    $inputNuevo_pass    = $_GET['inputNuevo_pass'];
    $inputNuevo_nivel   = $_GET['inputNuevo_nivel'];
    $inputNuevo_hash    = $_GET['inputNuevo_hash'];
    $idUsuario          = $_GET['idUsuario'];
    $getArrayEquipos    = json_decode($_GET['inputNuevo_equipo']);

    $setCE = "email='$inputNuevo_email',password='$inputNuevo_pass',nivel='$inputNuevo_nivel'";
    $whereCE = "id={$idUsuario}";
    $ejecutaCE = $ClassTodas->actualizaCosasVariasSetWhere($tabla,$setCE,$whereCE);

    if($ejecutaCE == 1){
      $eliminaEquipos = $ClassTodas->eliminarLinea('credenciales_equipos','id_credencial',$idUsuario);
      foreach ($getArrayEquipos as $key => $value) {
        $campos2 = "id_credencial,id_equipo"; 
        $datos2 = "'{$idUsuario}','{$value}'";
        $ejecutaCP = $ClassTodas->insertCosasVarias('credenciales_equipos',$campos2,$datos2);    
      } 
      echo 1;
    } else {
      echo 0;
    }
  break;

  case 'alertCambiaPass_ejecuta':
    $tabla = $_GET['tabla'];
    $id = $_GET['id'];
    $nuevaPass = $_GET['nuevaPass'];
    if ($tabla == 'credenciales') {
      $set_alertCambiaPass_ejecuta = "password='{$nuevaPass}'";
      $where_alertCambiaPass_ejecuta = "id={$id}";
      $cambia_alertCambiaPass_ejecuta   = $ClassTodas->actualizaCosasVariasSetWhere('credenciales',$set_alertCambiaPass_ejecuta,$where_alertCambiaPass_ejecuta);
      echo $cambia_alertCambiaPass_ejecuta;
    }
  break;

  case 'muestraAccesosUsuario':
    $idUsuario = $_GET['idUsuario'];
    $idModulo = $_GET['idModulo'];
    // $tau = $ClassTodas->get_accesoUsuario($idUsuario,$idModulo);
    $tau = $ClassTodas->get_datoVariosWhereOrder('credenciales_acciones', 'where idUsuario='.$idUsuario.' AND idModulo='.$idModulo,'');
    $countCA=1;
    foreach ($tau as $valueTau) {
      //$_SESSION[$siglaSistema.'_idUsuario_'.$countCA]         = $valueTau['idUsuario'];
      //$_SESSION[$siglaSistema.'_idModulo_'.$countCA]          = $valueTau['idModulo'];
      $_SESSION[$siglaSistema.'_modulo_'.$idModulo.'_'.$countCA]            = $valueTau['modulo'];
      $_SESSION[$siglaSistema.'_activo_'.$idModulo.'_'.$countCA]            = $valueTau['activo'];
      $_SESSION[$siglaSistema.'_botonVer_'.$idModulo.'_'.$countCA]          = $valueTau['botonVer'];
      $_SESSION[$siglaSistema.'_botonEditar_'.$idModulo.'_'.$countCA]       = $valueTau['botonEditar'];
      $_SESSION[$siglaSistema.'_botonAgregar_'.$idModulo.'_'.$countCA]      = $valueTau['botonAgregar'];
      $_SESSION[$siglaSistema.'_botonEliminar_'.$idModulo.'_'.$countCA]     = $valueTau['botonEliminar'];
      $_SESSION[$siglaSistema.'_botonImprimir_'.$idModulo.'_'.$countCA]     = $valueTau['botonImprimir'];
      $_SESSION[$siglaSistema.'_idModuloSub_'.$idModulo.'_'.$countCA]       = $valueTau['idModuloSub'];
      $countCA++;
    }
  break;

  case 'cargaDashBoard':
    $edadMinima = '';
    $competInactivas = '';
    $competicionesActivas = $ClassTodas->get_datoVariosWhereOrder('competiciones','WHERE activo = 1','');
    if(empty($competicionesActivas)){
      $competInactivas = '<li><strong>Ninguna por el momento</strong></li>';
    } else {
      foreach($competicionesActivas as $row){
        $competInactivas .='<li><strong>'.$row['nombre'].'</strong>';
      }
    } 

    $competicionesTodas = $ClassTodas->get_datoVariosWhereOrder('competiciones','','');
    if($competicionesTodas){
      foreach($competicionesTodas as $row){
        $nombre_compet = $row['nombre'];
        $activo_compet = $row['activo'];
        $edadMinima_compet = $row['edadMinima'];

        $anio = date('Y');
        $añoMenos = $anio - $edadMinima_compet;
        $edadMinima .='<li><strong>'.$nombre_compet.'</strong> - '.$añoMenos.' ('.$edadMinima_compet.'+)';
      }
    } 

    $resumenDashboard =<<<EOD
      <div class="row">
        <div class="col">
          <div class="alert alert-danger has-icon" role="alert">
            <div class="alert-icon">
              <span class="fa fa-bullhorn"></span>
            </div>Competiciones inactivas para modificación de jugadores:<ul class="mt-3">$competInactivas</ul>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="alert alert-primary has-icon" role="alert">
            <div class="alert-icon">
              <span class="fa fa-bullhorn"></span>
            </div>Edad mínima para jugar en cada categoría: <ul class="mt-3">$edadMinima</ul>
          </div>
        </div>
      </div>
    EOD;
    echo $resumenDashboard;
  break;

  case 'listarJugadores':
    $idUsuario              = isset($_GET['idUsuario']) ? $_GET['idUsuario'] : null;
    $idModulo               = isset($_GET['idModulo']) ? $_GET['idModulo'] : null;
    $countSubModulo         = isset($_GET['countSubModulo']) ? $_GET['countSubModulo'] : null;
    $cantity                = ($_GET['cantity'] == 'Todos') ? '' : $cantity = 'LIMIT '.$_GET['cantity'];
    $datosTabla             = '';
    $foto                   = '';
    $aseguradoSelected0     = '';
    $aseguradoSelected1     = '';
    $tdAsegurado            = '';
    $thAsegurado            = '';
    $filtrosPreElegidos     = '';

    if (!$idUsuario || !$idModulo || !$countSubModulo){
      echo '<h5 class="text-danger mb-0">Error en los datos enviados para la consulta</h5>';
      exit;
    }
    
    if ($_SESSION[$siglaSistema.'_botonVer_'.$idModulo.'_'.$countSubModulo] == 1) {$classVer = "";} else {$classVer="hidden";}
    if ($_SESSION[$siglaSistema.'_botonEditar_'.$idModulo.'_'.$countSubModulo] == 1) {$classEditar = "";} else {$classEditar="hidden";}
    if ($_SESSION[$siglaSistema.'_botonAgregar_'.$idModulo.'_'.$countSubModulo] == 1) {$classAgregar = "";} else {$classAgregar="hidden";}
    if ($_SESSION[$siglaSistema.'_botonEliminar_'.$idModulo.'_'.$countSubModulo] == 1) {$classEliminar = "";} else {$classEliminar="hidden";}
    if ($_SESSION[$siglaSistema.'_botonImprimir_'.$idModulo.'_'.$countSubModulo] == 1) {$classImprimir = "";} else {$classImprimir="hidden";}

    //Equipos asignados al usuario loggeado
    $arrayEquipos = array();
    $equiposUsuario = '';
    $datosEquipos = $ClassTodas->get_datoVariosWhereOrder('credenciales_equipos','WHERE id_credencial='.$idUsuario,'');
    if(empty($datosEquipos)){
      $equiposUsuario = 0;
    } else {
      foreach($datosEquipos as $value){
        array_push($arrayEquipos, $value['id_equipo']);
      }
      //Prepara array para SQL    
      $equiposUsuario = implode("', '", $arrayEquipos);
      $equiposUsuario = "'" . $equiposUsuario . "'";
    }

    if(empty($equiposUsuario) && in_array($nivelUsuarioGeneral, array(8,9))){
      $buscaDatosEquipos = $ClassTodas->get_datoVariosWhereOrder('equipos','','');
      $buscaDatosJugadores = $ClassTodas->get_datoVariosWhereOrder('jugadores','WHERE activo=1','ORDER BY fecha_creacion DESC '.$cantity);
    } else {
      $buscaDatosEquipos = $ClassTodas->get_datoVariosWhereOrder('equipos','WHERE id IN('.$equiposUsuario.')','');
      $buscaDatosJugadores = $ClassTodas->get_datoVariosWhereOrderInformes("SELECT jug.*,jugeqp.id_equipo FROM jugadores as jug INNER JOIN jugadores_equipos as jugeqp ON jug.id = jugeqp.id_jugador WHERE jugeqp.id_equipo IN ($equiposUsuario) AND jug.activo = 1");
    }

    if(in_array($nivelUsuarioGeneral, array(8,9))){
      $filtrosPreElegidos =<<<EOD
        <div class="col-12 col-md-4 mb-3">
          <div class="btn-group dropright" role="group">
            <button id="btnCantities" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Filtrar por cantidad</button>
            <div class="dropdown-menu" aria-labelledby="btnCantities" style="">
              <div class="dropdown-arrow"></div>
              <a type="button" class="dropdown-item" href="#" onclick="listarJugadores('Listado de Jugadores','$idUsuarioGeneral','10','2','250');">Últimos 250 creados</a> 
              <a type="button" class="dropdown-item" href="#" onclick="listarJugadores('Listado de Jugadores','$idUsuarioGeneral','10','2','500');">Últimos 500 creados</a> 
              <a type="button" class="dropdown-item" href="#" onclick="listarJugadores('Listado de Jugadores','$idUsuarioGeneral','10','2','1000');">Últimos 1000 creados</a> 
              <a type="button" class="dropdown-item" href="#" onclick="listarJugadores('Listado de Jugadores','$idUsuarioGeneral','10','2','Todos');">Todos</a> 
            </div>
          </div>
        </div>
      EOD;
    }

    $displayJugadores = 'd-none';
    if (!empty($buscaDatosJugadores)) {
      $count=0;
      foreach ($buscaDatosJugadores as $value) {
        $count++;
        $id_jg                  = $value['id'];
        $foto                   = $value['foto'];
        if(!file_exists(__DIR__.'/images/jugadores/'.$foto)) {
          $foto                   = 'sinimagen300x300.png';
        }
        $nombre                 = $value['nombre'];
        $apellido               = $value['apellido'];
        $documento              = $value['documento'];
        $fnacimientoSinTrab     = $value['fnacimiento'];
        $fnacimiento            = $ClassTodas->cambiaf_a_normal2($fnacimientoSinTrab);
        $posicion               = $value['posicion'];
        $nacionalidad           = $value['nacionalidad'];
        $email                  = $value['email'];
        $celular                = $value['celular'];
        $fecha_creacion         = $value['fecha_creacion'];
        $asegurado              = $value['asegurado'];
        $segModPor              = $value['segModPor'];
        $fechaSegMod            = $value['fechaSegMod'];
        if($asegurado == 0){ 
          $aseguradoSelected0 = 'selected'; 
          $aseguradoSelected1 = ''; 
        } elseif($asegurado == 1) { 
          $aseguradoSelected0 = ''; 
          $aseguradoSelected1 = 'selected'; 
        }
        $edadJugador            = $ClassTodas->obtener_edad_segun_fecha($fnacimiento);

        $equiposJugador = '';
        $datosEquipos = $ClassTodas->get_datoVariosWhereOrderInformes("SELECT eq.nombre, eq.id FROM jugadores_equipos as je LEFT JOIN equipos as eq ON eq.id = je.id_equipo WHERE je.id_jugador=$id_jg");
        if(empty($datosEquipos)){
          $equiposJugador = 'Sin Equipo';
        } else {
          foreach($datosEquipos as $value){
            if((in_array($value['id'], $equiposUsuarioGeneral) && !empty($equiposUsuarioGeneral)) || in_array($nivelUsuarioGeneral, array(8,9))){
              $equiposJugador .= '<span class="badge badge-subtle badge-primary m-1">'.$value['nombre'].'</span>';
            }
          }
        }


        $buscaPosicion = $ClassTodas->get_datoVariosWhereOrder('posiciones','WHERE id='.$posicion,'');
        foreach ($buscaPosicion as $value) {
          $nombrePosicion         = $value['nombre'];
        }
        $buscarNacionalidades = $ClassTodas->get_datoVariosWhereOrder('nacionalidades','WHERE id='.$nacionalidad,'');
        foreach ($buscarNacionalidades as $value) {
          $nombreNacion         = $value['nombre'];
        }
        if($nivelUsuarioGeneral == 8 || $nivelUsuarioGeneral == 9){
          $tdAsegurado =<<<EOD
            <td class="align-middle">$segModPor</td>
            <td class="align-middle">$fechaSegMod</td>
            <td class="align-middle">  
              <div class="flex-nowrap input-group input-group-alt">
              
                <select id="aseguradoValue_$id_jg" class="form-control custom-select" style="width:60px" disabled>              
                  <option value="1" $aseguradoSelected1>Sí</option>
                  <option value="0" $aseguradoSelected0>No</option>
                </select>
                <div class="input-group-append">
                  <span class="input-group-text cursor-pointer bg-primary text-white" id="btnEditarAsegurado_$id_jg" onclick="habilitaGuardarAsegurado('$id_jg')"><i class="fa fa-edit"></i></span>
                  <span class="input-group-text cursor-pointer bg-primary text-white" id="btnGuardarAsegurado_$id_jg" onclick="guardarAseguradoDato('$id_jg')" style="display: none;"><i class="fa fa fa-save"></i></span>
                </div>
              </div>   
            </td>
          EOD;
          $thAsegurado = '<th style="width: 100px;">Modif. Por</th><th style="width: 100px;">Fecha modif.</th><th style="width: 100px;">Asegurado?</th>';
        }
        $datosTabla .=<<<EOD
          <tr id="tr_$id_jg">  
            <td class="align-middle text-center col-checker">
              <div class="custom-control custom-control-nolabel custom-checkbox">
                <input type="checkbox" class="custom-control-input" name="jugadoresCheck" id="$id_jg"> <label class="custom-control-label" for="$id_jg"></label>
              </div>
            </td>
            <td class="align-middle text-center">$count</td>
            <td class="align-middle text-center"><img id="fotoJugador_$id_jg" class="img-fluid" src="images/jugadores/$foto" alt="$foto" style="height: 70px;"></td>
            <td class="align-middle text-left">$nombre $apellido</td>
            <td class="align-middle text-center">$documento</td>
            <td class="align-middle text-center">$fnacimiento <small>(Edad: $edadJugador)</small></td>
            <td class="align-middle text-center">$equiposJugador</td>
            <td class="align-middle text-center">$nombrePosicion</td>
            <td class="align-middle text-center">$nombreNacion</td>
            <td class="align-middle text-center $displayJugadores">$fecha_creacion</td>
            $tdAsegurado
            <td class="align-middle text-center"> 
              <a id="editarLinea_$id_jg" class="btn btn-sm btn-icon btn-secondary" onclick="formJugadores('Editar','$id_jg','jugadores','editarJugadores')" $classEditar><i class="fa fa-edit"></i></a>
              <a class="btn btn-sm btn-icon btn-secondary" onclick="enDesarrollo()" $classEliminar><i class="far fa-trash-alt"></i></a>
            </td>
          </tr>
        EOD;
      }
    }
    $tablaDatosJugadores =<<<EOD
      $filtrosPreElegidos
      <div class="table-responsive">
        <table class="table  table-condensed table-bordered table-sm table-striped font-size-sm" id="tablaAdministraJugadores">
          <thead>
            <tr style="height: 55px;">
              <th class="text-center" style="min-width: 50px;">
                <div class="thead-dd dropdown">
                  <span class="custom-control custom-control-nolabel custom-checkbox"><input type="checkbox" class="custom-control-input" id="check-handle"> <label class="custom-control-label" for="check-handle"></label></span>
                  <div class="thead-btn" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="fa fa-caret-down"></span>
                  </div>
                  <div class="dropdown-menu" style="">
                    <div class="dropdown-arrow"></div><a class="dropdown-item" href="#" onclick="enDesarrollo()">Borrar Todos</a>
                  </div>
                </div>
              </th>
              <th>Nº</th>
              <th style="width: 100px;">Foto Documento</th>
              <th>Nombre</th>
              <th>Documento</th>
              <th>Fecha Nacimiento</th>                
              <th>Equipo</th>
              <th>Posición</th>
              <th>Nacionalidad</th>
              <th class="$displayJugadores">Fecha Creación</th>
              $thAsegurado
              <th>Opciones</th>
            </tr>
          </thead>
          <tbody>
            $datosTabla
          </tbody>
        </table>
      </div>
    EOD;
    echo $tablaDatosJugadores;  
  break;

  case 'formJugadores':
    $tipo                      = $_GET['tipo'];
    $id                        = $_GET['id'];
    $tabla                     = $_GET['tabla'];
    $valoresOptEquipos         = '';
    $datosFooterJugadores      = '';
    $formJugadores             = '';
    $disabled                  = '';
    $valoresOptEquiposSel      = '';
    $valoresOptEquipos         = '';
    $valoresOptionNacion       = '';
    $valoresOptionPosicion     = '';
    $valoresOptTipo            = '';
    $selectAsegurado0          = '';
    $selectAsegurado1          = '';
    $equiposUsuario            = '0';
    $arrayTipoDoc              = array("Rut","Pasaporte","Identidad");

    if ($tipo == 'editarJugadores') {
      $disabled = 'disabled';
      $buscaDatosJugadores = $ClassTodas->get_datoVariosWhereOrder($tabla, 'WHERE id='.$id,'ORDER BY id asc');
      foreach ($buscaDatosJugadores as $value) {
        $id_jg                  = $value['id'];
        $nombre                 = $value['nombre'];
        $apellido               = $value['apellido'];
        $tipoDoc                = $value['tipoDoc'];
        $documento              = $value['documento'];
        $fnacimiento            = $value['fnacimiento'];
        $posicion               = $value['posicion'];        
        $nacionalidad           = $value['nacionalidad'];
        $email                  = $value['email'];
        $celular                = $value['celular'];
        $foto                   = $value['foto'];
        $fecha_creacion         = $value['fecha_creacion'];
        $asegurado              = $value['asegurado'];
        $selectAsegurado0       = ($asegurado == 0) ? 'selected' : '';
        $selectAsegurado1       = ($asegurado == 1) ? 'selected' : '';
        
        $arrayEquipos = array();
        $datosEquipos = $ClassTodas->get_datoVariosWhereOrder('credenciales_equipos','WHERE id_credencial='.$idUsuarioGeneral,'');
        if($datosEquipos){
          foreach($datosEquipos as $row){
            array_push($arrayEquipos, $row['id_equipo']);
          }
          $equiposUsuario = implode("', '", $arrayEquipos);
          $equiposUsuario = "'" . $equiposUsuario . "'";
        }

        if(empty($equiposUsuario)) {
          $gp2 = $ClassTodas->get_datoVariosWhereOrder('equipos','','ORDER BY nombre ASC');
        } else {
          $gp2 = $ClassTodas->get_datoVariosWhereOrder('equipos','WHERE id IN('.$equiposUsuario.')','');
        }
        
        $arrayEquiposJugador = array();
        $datosEquiposJugador = $ClassTodas->get_datoVariosWhereOrder('jugadores_equipos','WHERE id_jugador='.$id,'');
        if(!empty($datosEquiposJugador)){
          foreach($datosEquiposJugador as $row){
            array_push($arrayEquiposJugador, $row['id_equipo']);
          }
          $equiposJugador = implode("', '", $arrayEquiposJugador);
          $equiposJugador = "'" . $equiposJugador . "'";
        }

        foreach($gp2 as $gprow){
          $id_gp2     = $gprow['id'];
          $nombre_gp2 = $gprow['nombre'];

          if(in_array($id_gp2, $arrayEquipos)){
            if(in_array($id_gp2, $arrayEquiposJugador)){
              $valoresOptEquiposSel .=<<<EOD
                <option value="$id_gp2" selected>$nombre_gp2</option>
              EOD;
            } else {
              $valoresOptEquipos .=<<<EOD
                <option value="$id_gp2">$nombre_gp2</option>
              EOD;
            }
          } else {
            if(in_array($id_gp2, $arrayEquiposJugador)){
              $valoresOptEquiposSel .=<<<EOD
                <option value="$id_gp2" selected>$nombre_gp2</option>
              EOD;
            } else {
              $valoresOptEquipos .=<<<EOD
                <option value="$id_gp2">$nombre_gp2</option>
              EOD;
            }
          }
        }
        foreach($arrayTipoDoc as $row){
          if($row == $tipoDoc) {
            $valoresOptTipo .= '<option value="'.$row.'" selected>'.$row.'</option>';

          } else {
            $valoresOptTipo .= '<option value="'.$row.'">'.$row.'</option>';
          }          
        }
        

      }
      
      $traeNacionalidades    = $ClassTodas->get_datoVariosWhereOrder('nacionalidades', '', 'ORDER BY nombre');
      foreach ($traeNacionalidades as $value) {
        $idNacion                  = $value['id'];
        $nacionNombre              = $value['nombre']; 
        if ($nacionalidad == $idNacion) {            
          $nacionSeleccionada = "selected"; 
        } else {
          $nacionSeleccionada =  "";
          $optEmptyAgrega = '<option></option>';
        } 
        $valoresOptionNacion .= "<option value='$idNacion' $nacionSeleccionada>$nacionNombre</option>";
      }
      $traePosiciones    = $ClassTodas->get_datoVariosWhereOrder('posiciones', '', 'ORDER BY id');
      foreach ($traePosiciones as $value) {
        $idPosicion                  = $value['id'];
        $nombrePosicion              = $value['nombre']; 
        if ($posicion == $idPosicion) {            
          $posicionSeleccionada = "selected"; 
        } else {
          $posicionSeleccionada =  "";
          $optEmptyAgrega = '<option></option>';
        } 
        $valoresOptionPosicion .= "<option value='$idPosicion' $posicionSeleccionada>$nombrePosicion</option>";
      }
      $datosFotoJugadores =<<<EOD
        <h6>Editar Foto Documento</h6>
        <div id="imagesOld" class="form-row my-3">
          <div class="card card-figure col-6 col-md-2 mx-md-2">
            <figure class="figure">
              <img class="img-fluid" src="images/jugadores/$foto" title="$foto">
              <figcaption class="figure-caption">
                <h6 class="figure-title text-center mb-2">$foto</h6>
                <input type="hidden" name="fotoID" id="fotoID" value="$foto"> 
              </figcaption>
            </figure>
            <button type="button" class="btn btn-secondary font-size-sm" onclick="modalCambiaFoto('$id_jg','Cambiar Imagen','foto');"> Cambiar Imagen</button>
            <a id="eliminaFotoBtn" class="text-center font-weight-bold mt-2" type="button" onclick="eliminaFoto('$id_jg','foto','$foto');"><u>Eliminar</u></a>
          </div>
        </div>
      EOD;
      $datosFooterJugadores =<<<EOD
          <button type="button" class="btn btn-primary btn-block" onclick="guardarEditaJugadores('$id_jg','jugadores','editarJugadores')"><i class="far fa-save"></i> Guardar</button>
      EOD;
    } 
    if ($tipo == 'agregarJugadores') {
      $nombre = $apellido = $fnacimiento = $documento = $email = $celular = '';
      $optEmptyAgrega = '<option></option>';

      $arrayEquipos = array();
      $datosEquipos = $ClassTodas->get_datoVariosWhereOrder('credenciales_equipos','WHERE id_credencial='.$idUsuarioGeneral,'');
      if($datosEquipos){
        foreach($datosEquipos as $row){
          array_push($arrayEquipos, $row['id_equipo']);
        }
        $equiposUsuario = implode("', '", $arrayEquipos);
        $equiposUsuario = "'" . $equiposUsuario . "'";
      }

      if($equiposUsuario == '0') {
        $gp2 = $ClassTodas->get_datoVariosWhereOrder('equipos','','ORDER BY nombre ASC');
      } else {
        $gp2 = $ClassTodas->get_datoVariosWhereOrder('equipos','WHERE id IN('.$equiposUsuario.')','');
      }

      foreach($gp2 as $gprow){
        $id_gp2     = $gprow['id'];
        $nombre_gp2 = $gprow['nombre'];

        if(in_array($id_gp2, $arrayEquipos)){
          $valoresOptEquipos .=<<<EOD
            <option value="$id_gp2">$nombre_gp2</option>
          EOD;
        } else {
          $valoresOptEquipos .=<<<EOD
            <option value="$id_gp2">$nombre_gp2</option>
          EOD;
        }
      }

      $traeNacionalidades    = $ClassTodas->get_datoVariosWhereOrder('nacionalidades', '', 'ORDER BY nombre');
      foreach ($traeNacionalidades as $value) {
        $idNacion                  = $value['id'];
        $nombreNacion              = $value['nombre']; 
        $valoresOptionNacion .= "<option value='$idNacion'>$nombreNacion</option>";
      }
      $traePosiciones    = $ClassTodas->get_datoVariosWhereOrder('posiciones', '', 'ORDER BY id');
      foreach ($traePosiciones as $value) {
        $idPosicion                  = $value['id'];
        $nombrePosicion              = $value['nombre']; 
        $valoresOptionPosicion .= "<option value='$idPosicion'>$nombrePosicion</option>";
      }

      foreach($arrayTipoDoc as $row){
        $valoresOptTipo .= '<option value="'.$row.'">'.$row.'</option>';
      }

      $datosFotoJugadores =<<<EOD
        <div id="imagesNew" class="form-row my-3">
          <div class="col-12 col-md-12">
            <div class="form-group">
              <h6>Foto Documento</h6>
              <form action="upload.php" class="dropzone fileinput-dropzone border-info mb-3" id="dropzonewidget"></form> 
              <div id="inputNames"></div>          
            </div> 
          </div>
        </div>
        <script>  
          Dropzone.autoDiscover = false;
            var myDropzone = new Dropzone(".dropzone", { 
             autoProcessQueue: true,
             dictDefaultMessage:"<h5>Haga clic o arrastre aquí su documento</h5>",
             dictInvalidFileType:"Extensión Incorrecta",
             uploadMultiple: false,
             paramName: "file",
             maxFilesize: 0.5,
             parallelUploads: 1,
             acceptedFiles: "image/*",              
             init: function ()  {
               this.on("error", function (file, message) {
                   notifica('error','El archivo pesa más que el maximo permitido de 500kb.');
                   this.removeFile(file);
               }); 
             },              
             renameFile: function (file) {
               let newName = new Date().getTime() + '_' + file.name.replace(/ /g, "_");
               $('#inputNames').append('<input type="hidden" class="form-control mb-1" id="fotoID" name="" disabled value="' + newName + '">');
               return newName;
             },
           });
       </script>
      EOD;
      $datosFooterJugadores =<<<EOD
        <button type="button" class="btn btn-primary btn-block" onclick="guardarEditaJugadores('','jugadores','agregarJugadores')"><i class="far fa-save"></i> Guardar</button>
      EOD;
    }
    if($nivelUsuarioGeneral == 8 || $nivelUsuarioGeneral == 9){
      $adminPartOfForm =<<<EOD
      <hr>
        <h5>Solo Administración</h5><hr>
        <div class="form-row" id="aseguradoIDContainer">
          <div class="col-12 col-md-4">
            <div class="form-group">
              <label>Asegurado <abbr title="Required">*</abbr></label>
              <select class="form-control custom-select" id="aseguradoID" name="aseguradoID">
                  <option value="0" $selectAsegurado0>No</option>
                  <option value="1" $selectAsegurado1>Sí</option>
              </select>
              <small id="" class="form-text text-muted">Indique si jugador tiene seguro.</small>
              <div class="invalid-feedback"> Ingrese este Campo. </div>
            </div>
          </div>           
        </div>
        <div id="containerJugadoresSeguros"></div>
      EOD;
    } else {
      $adminPartOfForm = '';
    }   
    $formJugadores =<<<EOD
      <form class="form-horizontal pt-2 needs-validation" role="form" id="formContratoID">
        <div class="form-row">
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label>Nombre <abbr title="Required">*</abbr></label>
                <input class="form-control" type="text" id="nombreID" name="nombreID" placeholder="" value="$nombre" required>
                <small id="" class="form-text text-muted">Indique el nombre.</small>
                <div class="invalid-feedback"> Ingrese este Campo. </div>
              </div> 
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group">
                <label>Apellido <abbr title="Required">*</abbr></label>
                <input class="form-control" type="text" id="apellidoID" name="apellidoID" placeholder="" value="$apellido" required>
                <small id="" class="form-text text-muted">Indique el apellido.</small>
                <div class="invalid-feedback"> Ingrese este Campo. </div>
              </div> 
            </div>
            <div class="col-12 col-md-4">
              <div class="form-group mb-2">
                <label>Fecha Nacimiento <abbr title="Required">*</abbr></label>
                <input class="form-control" type="date" id="fnacimientoID" name="fnacimientoID" placeholder="" value="$fnacimiento" required>
                <small id="" class="form-text text-muted">Indique la fecha de nacimiento.</small>
                <div class="invalid-feedback"> Ingrese este Campo. </div>
              </div> 
            </div>
          </div>
        <div class="form-row">
          <div class="col-12 col-md-4">              
            <div class="form-group">
              <label>Tipo Doc. (Rut, Pass., Identidad) <abbr title="Required">*</abbr></label>
              <select class="form-control" id="tipoDocumentoID" name="tipoDocumentoID" onchange="formatearDocumento()" required="" $disabled>
                $valoresOptTipo
              </select>
              <small id="" class="form-text text-muted">Indique el tipo de documento.</small>
              <div class="invalid-feedback"> Ingrese este Campo. </div>                       
            </div>
          </div>
          <div class="col-12 col-md-4">              
            <div class="form-group">
              <label>Documento (Rut, Pass., DNI) <abbr title="Required">*</abbr></label>
              <input maxlength="9" class="form-control" type="text" id="documentoID" name="documentoID" value="$documento" required="" onkeypress="return permite(event, 'num_car_vip');" onblur="formatearDocumento()" $disabled>
              <small id="" class="form-text text-muted">Indique un Rut, Pasaporte, DNI válido.</small>
              <div class="invalid-feedback"> Ingrese este Campo. </div>                       
            </div>
          </div>
          <div class="col-12 col-md-4">
            <div class="form-group">
              <label>Equipo(s) <abbr title="Required">*</abbr></label>
              <select class="form-control custom-select" id="equipoID" name="equipoID[]" multiple="multiple" required>
                  {$optEmptyAgrega}
                  $valoresOptEquiposSel
                  $valoresOptEquipos
              </select>
              <small id="" class="form-text text-muted">Indique uno o más equipos.</small>
              <div class="invalid-feedback"> Ingrese este Campo. </div>
            </div> 
          </div>
        </div>
        <div class="form-row">
          <div class="col-12 col-md-4">
            <div class="form-group">
              <label>Posición <abbr title="Required">*</abbr></label>
              <select class="form-control custom-select" id="posicionID" name="posicionID" required>
                  {$optEmptyAgrega}
                  $valoresOptionPosicion
              </select>
              <small id="" class="form-text text-muted">Indique la posición.</small>
              <div class="invalid-feedback"> Ingrese este Campo. </div>
            </div> 
          </div>
          <div class="col-12 col-md-4">
            <div class="form-group">
              <label>Nacionalidad <abbr title="Required">*</abbr></label>
              <select class="form-control custom-select" id="nacionalidadID" name="nacionalidadID" required>
                  {$optEmptyAgrega}
                  $valoresOptionNacion
              </select>
              <small id="" class="form-text text-muted">Indique la nacionalidad.</small>
              <div class="invalid-feedback"> Ingrese este Campo. </div>
            </div> 
          </div>
          <div class="col-12 col-md-4">
            <div class="form-group">
              <label>E-mail</label>
              <input class="form-control" type="email" id="emailID" name="emailID" placeholder="" value="$email">
              <small id="" class="form-text text-muted">Indique el e-mail.</small>
              <div class="invalid-feedback"> Ingrese este Campo. </div>
            </div> 
          </div>
        </div> 
        <div class="form-row">
          <div class="col-12 col-md-4">
            <div class="form-group">
              <label>Celular</label>
              <input class="form-control" type="text" id="celularID" name="celularID" placeholder="" maxlength="13" value="$celular" onkeypress="return permite(event, 'num');">
              <small id="" class="form-text text-muted">Indique el celular, formato ej.: 56978762996.</small>
              <div class="invalid-feedback"> Ingrese este Campo. </div>
            </div> 
          </div>
        </div>
        $adminPartOfForm  
      </form>
      $datosFotoJugadores
      $datosFooterJugadores    
    EOD;
    echo $formJugadores;
  break;

  case 'guardarEditaJugadores':
    $maxJugadores                 = 30; 
    $opcion                       = $_GET['opcion'];
    $tabla                        = $_GET['tabla'];
    $idRecibido                   = $_GET['idRecibido'];
    $nombreID                     = mb_strtoupper($_GET['nombreID']);
    $apellidoID                   = mb_strtoupper($_GET['apellidoID']);
    $tipoDocumentoID              = $_GET['tipoDocumentoID'];
    $documentoID                  = $_GET['documentoID'];
    $fnacimientoID                = $_GET['fnacimientoID'];
    $posicionID                   = mb_strtoupper($_GET['posicionID']);
    $getArrayEquipos              = json_decode($_GET['equipoID']);
    $nacionalidadID               = mb_strtoupper($_GET['nacionalidadID']);
    $fotoID                       = $_GET['fotoID'];
    $emailID                      = $_GET['emailID'];
    $celularID                    = $_GET['celularID'];   
    $aseguradoID                  = isset($_GET['aseguradoID']) ?? null; 
    $arrayRespuestas              = array();

    if($opcion == 'editarJugadores'){
      $verificaDoc = 0;
    } else {
      $nacionalidad = $ClassTodas->get_datoVariosWhereOrder('nacionalidades','WHERE id='.$nacionalidadID,'');
      $prefixNacion = substr($nacionalidad[0][1], 0, 3);
      if($tipoDocumentoID == 'Pasaporte' ){
        $documentoID = $prefixNacion.'P'.$documentoID;
      } elseif($tipoDocumentoID == 'Identidad'){
        $documentoID = $prefixNacion.'I'.$documentoID;
        $documentoID = mb_strtoupper($documentoID);
      }
      $verificaDoc = $ClassTodas->validaJugador('jugadores',$documentoID); 
    }
    //Verifica si jugador existe en BD
    if($verificaDoc == 1){
      echo 0;
    } else {
      //Validación Competición, Maximo Jugador, Edad Minima
      if(!empty($getArrayEquipos)) {
        foreach($getArrayEquipos as $row) {      
          $buscaEq = $ClassTodas->get_datoVariosWhereOrder('equipos','WHERE id='.$row,'');
          $nombreEquipo = $buscaEq[0][2];
          $totalJugadores = $ClassTodas->get_contador('jugadores as jg','LEFT JOIN jugadores_equipos as jge ON jg.id = jge.id_jugador WHERE jge.id_equipo='.$row.' AND jg.activo = 1','');
          $sql_idCompet = $ClassTodas->get_datoVariosWhereOrder('equipos','WHERE id='.$row,'LIMIT 1');
          foreach($sql_idCompet as $value) {
            $id_compet = $value['id_competicion'];
            $competActivas = $ClassTodas->get_datoVariosWhereOrder('competiciones','WHERE id='.$id_compet,' LIMIT 1');
            foreach($competActivas as $row2){
              $activo_ca = $row2['activo'];
              $edad_ca = $row2['edadMinima'];
              if($activo_ca == 1){//Valida si competición de este equipo está inactiva
                $arrayRespuestas[$nombreEquipo] = "2";
              } else {
                $maxJugadores = ($id_compet == 3) ? 16 : 30; //Si Competición igual Futbolito(3), maximo es 16 jugadores
                if($totalJugadores >= $maxJugadores){//Valida maximo jugadores por equipo
                  $arrayRespuestas[$nombreEquipo] = "0"; 
                } else {                  
                  $añoActual = date('Y');
                  $anio = date("Y", strtotime($fnacimientoID));
                  $añoMenos = $añoActual - $anio;
                  if($añoMenos >= '18') {//Valida si tiene la edad minima para jugar agregar $edad_ca si hay q volver a reglas de validación por cat.
                    $arrayRespuestas[$nombreEquipo] = "1";
                  } else {
                    $arrayRespuestas[$nombreEquipo] = "3"; //poner en 3 si hay reglas de edad
                  }
                }
              }
            }
          }
        }
      }

      //Verifica Validación y confirma cambios
      $arrayParaEnviar = array();
      $sonTodosUnos = true; // Asumimos que todos los valores son 1 inicialmente
  
      foreach ($arrayRespuestas as $clave => $valor) {
        if ($valor !== '1') {
          $sonTodosUnos = false; // Si encontramos un valor que no es 1, cambiamos la bandera
          break; // No es necesario seguir comprobando el resto del array
        }
      }
      
      if ($sonTodosUnos) {
          $equiposAgregados = '';
          foreach($arrayRespuestas as $key => $value){
            $equiposAgregados .= $key.', ';
          }
          $equiposAgregados = substr($equiposAgregados, 0, -2);
          if($opcion == 'editarJugadores'){
            $setJugadores = "nombre='{$nombreID}',apellido='{$apellidoID}',fnacimiento='{$fnacimientoID}',posicion='{$posicionID}',nacionalidad='{$nacionalidadID}',email='{$emailID}',celular='{$celularID}',foto='{$fotoID}',modificadoPor='{$nombreUsuarioGeneral}',fechaModificacion='{$date}'";
            $whereJugadores = "id={$idRecibido}";
            $actualizaJugadores = $ClassTodas->actualizaCosasVariasSetWhere($tabla,$setJugadores,$whereJugadores);
      
            if($actualizaJugadores == 1){
              if(empty($equiposUsuarioGeneral)){
                $eliminaEquipos = $ClassTodas->eliminarLinea('jugadores_equipos','id_jugador',$idRecibido);
                foreach ($getArrayEquipos as $key => $value) {
                  $campos2 = "id_jugador,id_equipo"; 
                  $datos2 = "'{$idRecibido}','{$value}'";
                  $ejecutaCP = $ClassTodas->insertCosasVarias('jugadores_equipos',$campos2,$datos2);    
                }
                $arrayRespuestas[$row] = "1";
              } else {
                foreach ($getArrayEquipos as $key => $value) {
                  //agrega equipos a jugadores sin borrar los que ya tiene
                  $getEquiposJugador = $ClassTodas->get_datoVariosWhereOrder('jugadores_equipos','WHERE id_jugador="'.$idRecibido.'" AND id_equipo="'.$value.'"','');
                  if(empty($getEquiposJugador)){
                    $campos2 = "id_jugador,id_equipo"; 
                    $datos2 = "'{$idRecibido}','{$value}'";
                    $ejecutaCP = $ClassTodas->insertCosasVarias('jugadores_equipos',$campos2,$datos2);  
                  }  
                }
                $arrayRespuestas[$row] = "1";
              }
            }
            $arrayParaEnviar['mensaje'] = "Jugador actualizado en:<br>$equiposAgregados";          
            $arrayParaEnviar['tipo'] = 'success';
            $arrayParaEnviar['principal'] = '¡Éxito!';
  
          } elseif($opcion == 'agregarJugadores'){          
            if($aseguradoID == 1) {
              $camposIngresaJugadores = "nombre,apellido,tipoDoc,documento,fnacimiento,posicion,nacionalidad,email,celular,foto,asegurado,modificadoPor,fechaModificacion,segModPor,fechaSegMod"; 
              $datosIngresaJugadores  = "'{$nombreID}','{$apellidoID}','{$tipoDocumentoID}','{$documentoID}','{$fnacimientoID}','{$posicionID}','{$nacionalidadID}','{$emailID}','{$celularID}','{$fotoID}','{$aseguradoID}','{$nombreUsuarioGeneral}','{$date}','{$nombreUsuarioGeneral}','{$date}'"; 
            } else {
              $camposIngresaJugadores = "nombre,apellido,tipoDoc,documento,fnacimiento,posicion,nacionalidad,email,celular,foto,asegurado,modificadoPor,fechaModificacion"; 
              $datosIngresaJugadores  = "'{$nombreID}','{$apellidoID}','{$tipoDocumentoID}','{$documentoID}','{$fnacimientoID}','{$posicionID}','{$nacionalidadID}','{$emailID}','{$celularID}','{$fotoID}','{$aseguradoID}','{$nombreUsuarioGeneral}','{$date}'"; 
            }
            if(empty($getArrayEquipos)){
              $arrayParaEnviar['mensaje'] = "Ocurrió un problema al agregar, por favor agregar el jugador a al menos un equipo.";
              $arrayParaEnviar['tipo'] = 'error';
              $arrayParaEnviar['principal'] = 'Error';
            } else {
              $ingresaJugadores = $ClassTodas->insertCosasVariasDevuelveId($tabla,$camposIngresaJugadores,$datosIngresaJugadores);
              if($ingresaJugadores >= 1){
                foreach ($getArrayEquipos as $key => $value) {
                  $campos2 = "id_jugador,id_equipo"; 
                  $datos2 = "'{$ingresaJugadores}','{$value}'";
                  $ejecutaCP = $ClassTodas->insertCosasVarias('jugadores_equipos',$campos2,$datos2);    
                }
                $arrayRespuestas[$row] = "1";
              }
              $arrayParaEnviar['mensaje'] = "Jugador agregado en:<br>$equiposAgregados";
              $arrayParaEnviar['tipo'] = 'success';
              $arrayParaEnviar['principal'] = '¡Éxito!';
            }
          }
      } else {
        foreach($arrayRespuestas as $key => $value){
          if($value == 1) continue;
          if($value == 0){
            $equiposAgregados .= $key.' ya posee el maximo de jugadores inscritos.<br>';
          }
          if($value == 2){
            $equiposAgregados .= $key.' está fuera del periodo permitido para modificaciones.<br>';
          }
          if($value == 3){
            $equiposAgregados .= 'El jugador no tiene la edad mínima para ser inscripto en: '.$key.'<br>';
          }
        }
        if($opcion == 'editarJugadores'){
          $arrayParaEnviar['mensaje'] = "Ocurrió un problema al editar, por favor verificar el mensaje abajo:<br>$equiposAgregados";          
          $arrayParaEnviar['tipo'] = 'error';
          $arrayParaEnviar['principal'] = 'Error';
  
        } elseif($opcion == 'agregarJugadores'){
          $arrayParaEnviar['mensaje'] = "Ocurrió un problema al agregar, por favor verificar el mensaje abajo:<br>$equiposAgregados";
          $arrayParaEnviar['tipo'] = 'error';
          $arrayParaEnviar['principal'] = 'Error';
        }
      }
      header('Content-Type: application/json');
      echo json_encode($arrayParaEnviar);
    }  
  break;

  case 'modalEditarDatosUsuarioSistema':
    $tabla        = $_GET['tabla'];
    $idDeLaTabla  = $_GET['idDeLaTabla'];
    $tipo         = $_GET['tipo'];
    $activoSiNo01 = '';
    $TRtablaResumenAccesosSistemas = '';
    $datosCredenciales = $ClassTodas->get_datoVariosWhereOrder($tabla,' where id='.$idDeLaTabla,'');
    foreach ($datosCredenciales as $value) {
      $id_acceso            = $value['id'];
      $rut                  = $value['rut'];
      $dv                   = $value['dv'];
      $nombre               = $value['nombre'];
      $password             = $value['password'];
      $email                = $value['email'];  
      $nivel                = $value['nivel'];
      $estado               = $value['activo'];
    }
    if ($tipo == "accesosUsuarios") {
      $datosAcciones = $ClassTodas->get_datoVariosWhereOrder('credenciales_acciones',' where idUsuario='.$idDeLaTabla,'');
      if (!$datosAcciones) {
        $TRtablaResumenAccesosSistemas .= <<<EOD
          <tr>
            <td colspan="9">No hay accesos generados aun!</td>
          </tr>
        EOD;
      } else {
        $count=0;
        foreach ($datosAcciones as $value) {
          $idLinea001 = $value['id'];
          $modulo = $value['modulo'];
          $idModulo = $value['idModulo'];
          $idModuloNombre = $value['idModuloNombre'];
          $idModuloSub = $value['idModuloSub'];
          $activo = $value['activo'];
          if ($activo == "1") {$botonActivoChk = "checked"; } else {$botonActivoChk = ""; }
          $botonVer = $value['botonVer'];
          if ($botonVer == "1") {$botonVerChk = "checked"; } else {$botonVerChk = ""; }
          $botonEditar = $value['botonEditar'];
          if ($botonEditar == "1") {$botonEditarChk = "checked"; } else {$botonEditarChk = ""; }
          $botonAgregar = $value['botonAgregar'];
          if ($botonAgregar == "1") {$botonAgregarChk = "checked"; } else {$botonAgregarChk = ""; }
          $botonEliminar = $value['botonEliminar'];
          if ($botonEliminar == "1") {$botonEliminarChk = "checked"; } else {$botonEliminarChk = ""; }
          $botonImprimir = $value['botonImprimir'];
          if ($botonImprimir == "1") {$botonImprimirChk = "checked"; } else {$botonImprimirChk = ""; }
          $count++;
          $TRtablaResumenAccesosSistemas .=<<<EOD
            <tr>
              <td>$idLinea001 </td>
              <td>$idModulo: $idModuloNombre</td>
              <td>$idModuloSub: $modulo <span style="float: right;" id="respuestaCambiaAccesosChkbox"></span></td>
              <td class="text-center">
                <label class="switcher-control switcher-control-success">
                  <input type="checkbox" class="switcher-input" id="botonHabilitar_$idLinea001" name="activo" title="Activar/Desactivar" alt="Activar/Desactivar" onChange="cambiaStateCheckbox('cambiaAccesos','#botonHabilitar_$idLinea001','credenciales_acciones','$idLinea001','activo')" $botonActivoChk> 
                  <span class="switcher-indicator"></span>
                </label>
              </td>
              <td class="text-center">
                <label class="switcher-control switcher-control-success">
                  <input type="checkbox" class="switcher-input" id="botonVer_{$idLinea001}" name="botonVer" title="Activar/Desactivar" alt="Activar/Desactivar" onChange="cambiaStateCheckbox('cambiaAccesos','#botonVer_{$idLinea001}','credenciales_acciones','$idLinea001','botonVer')" $botonVerChk> 
                  <span class="switcher-indicator"></span>
                </label>
              </td>
              <td class="text-center">
                <label class="switcher-control switcher-control-success">
                  <input type="checkbox" class="switcher-input" id="botonEditar_{$idLinea001}" name="botonEditar" title="Activar/Desactivar" alt="Activar/Desactivar" onChange="cambiaStateCheckbox('cambiaAccesos','#botonEditar_{$idLinea001}','credenciales_acciones','$idLinea001','botonEditar')" $botonEditarChk> 
                  <span class="switcher-indicator"></span>
                </label>
              </td>
              <td class="text-center">
                <label class="switcher-control switcher-control-success">
                  <input type="checkbox" class="switcher-input" id="botonAgregar_{$idLinea001}" name="botonAgregar" title="Activar/Desactivar" alt="Activar/Desactivar" onChange="cambiaStateCheckbox('cambiaAccesos','#botonAgregar_{$idLinea001}','credenciales_acciones','$idLinea001','botonAgregar')" $botonAgregarChk> 
                  <span class="switcher-indicator"></span>
                </label>
              </td>
              <td class="text-center">
                <label class="switcher-control switcher-control-success">
                  <input type="checkbox" class="switcher-input" id="botonEliminar_{$idLinea001}" name="botonEliminar" title="Activar/Desactivar" alt="Activar/Desactivar" onChange="cambiaStateCheckbox('cambiaAccesos','#botonEliminar_{$idLinea001}','credenciales_acciones','$idLinea001','botonEliminar')" $botonEliminarChk> 
                  <span class="switcher-indicator"></span>
                </label>
              </td>
              <td class="text-center">
                <label class="switcher-control switcher-control-success">
                  <input type="checkbox" class="switcher-input" id="botonImprimir_{$idLinea001}" name="botonImprimir" title="Activar/Desactivar" alt="Activar/Desactivar" onChange="cambiaStateCheckbox('cambiaAccesos','#botonImprimir_{$idLinea001}','credenciales_acciones','$idLinea001','botonImprimir')" $botonImprimirChk> 
                  <span class="switcher-indicator"></span>
                </label>
              </td>
            </tr>
          EOD;
        }
      }
      $tablaResumenAccesosSistemas =<<<EOD
        <h6 class="py-3">ID: <span class="font-weight-normal"> $idDeLaTabla</span><br>Nombre: <span class="font-weight-normal"> $nombre</span></h6>
        <div class="table-responsive">
          <table class="table table-condensed table-sm table-bordered table-striped input-group-reflow no-footer" id="tablaResumenAccesosSistemas">
            <thead>
              <tr>
                <th>ID</th>
                <th>Menú</th>
                <th>SubMenú</th>
                <th class="text-center">
                  <label class="list-group-item custom-control custom-checkbox">
                    <input id="marcaTodos_activo" type="checkbox" class="custom-control-input" onChange="marcaTodos('#marcaTodos_activo','Activo','{$id_acceso}','activo')"> 
                    <span class="custom-control-label"><br>Todos</span>
                  </label> Activo
                </th>
                <th class="text-center">
                  <label class="list-group-item custom-control custom-checkbox">
                    <input id="marcaTodos_ver" type="checkbox" class="custom-control-input" onChange="marcaTodos('#marcaTodos_ver','Ver','{$id_acceso}','botonVer')"> 
                    <span class="custom-control-label"><br>Todos</span>
                  </label> Ver
                </th>
                <th class="text-center">
                  <label class="list-group-item custom-control custom-checkbox">
                    <input id="marcaTodos_editar" type="checkbox" class="custom-control-input" onChange="marcaTodos('#marcaTodos_editar','Editar','{$id_acceso}','botonEditar')"> 
                    <span class="custom-control-label"><br>Todos</span>
                  </label> Editar
                </th>
                <th class="text-center">
                  <label class="list-group-item custom-control custom-checkbox">
                    <input id="marcaTodos_agregar" type="checkbox" class="custom-control-input" onChange="marcaTodos('#marcaTodos_agregar','Agregar','{$id_acceso}','botonAgregar')"> 
                    <span class="custom-control-label"><br>Todos</span>
                  </label> Agregar
                </th>
                <th class="text-center">
                  <label class="list-group-item custom-control custom-checkbox">
                    <input id="marcaTodos_eliminar" type="checkbox" class="custom-control-input" onChange="marcaTodos('#marcaTodos_eliminar','Eliminar','{$id_acceso}','botonEliminar')"> 
                    <span class="custom-control-label"><br>Todos</span>
                  </label> Eliminar
                </th>
                <th class="text-center">
                  <label class="list-group-item custom-control custom-checkbox">
                    <input id="marcaTodos_imprimir" type="checkbox" class="custom-control-input" onChange="marcaTodos('#marcaTodos_imprimir','Imprimir','{$id_acceso}','botonImprimir')"> 
                    <span class="custom-control-label"><br>Todos</span>
                  </label> Imprimir
                </th>
              </tr>
            </thead>
            <tbody>
              $TRtablaResumenAccesosSistemas
            </tbody>
          </table>
        </div>
        <div id="modalFooter" class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick=""><i class="far fa-times-circle"></i> Cerrar</button>
        </div>
      EOD;
      echo $tablaResumenAccesosSistemas;
    }
  break;

  case 'generaAccesosSistemaFinal':
    $idUsuario = $_GET['idUsuario'];
    $generaAccesosSistemaRealizaFinal = $ClassTodas->generaAccesosSistemaFinal($idUsuario);
    echo $generaAccesosSistemaRealizaFinal;
  break;

  case 'cambiaStateCheckbox':
    $estadoNuevo = $_GET['estadoNuevo'];
    $tipo = $_GET['tipo'];
    $idChkBox = $_GET['idChkBox'];
    $tabla = $_GET['tabla'];
    $idLinea = $_GET['idLinea'];
    $nombreCampo = $_GET['nombreCampo'];
    if ($tipo== 'cambiaAccesos') {
      $setCambiaChkBox          = "{$nombreCampo}='{$estadoNuevo}'";
      $whereCambiaChkBox        = "id={$idLinea}";
      $actualizaCambiaChkBox    = $ClassTodas->actualizaCosasVariasSetWhere($tabla,$setCambiaChkBox,$whereCambiaChkBox);  
    }
    echo $actualizaCambiaChkBox;
  break;

  case 'marcaTodos':
    $idInput = $_GET['idInput'];
    $btnTipo = $_GET['btnTipo'];
    $idUsuario = $_GET['idUsuario'];
    $estadoNuevo = $_GET['estadoNuevo'];
    $nombreBoton = $_GET['nombreBoton'];
    $setCambiaChkBox          = "$nombreBoton='{$estadoNuevo}'";
    $whereCambiaChkBox        = "idUsuario={$idUsuario}";
    $actualizaCambiaChkBox    = $ClassTodas->actualizaCosasVariasSetWhere('credenciales_acciones',$setCambiaChkBox,$whereCambiaChkBox);  
    echo $actualizaCambiaChkBox;
  break;

  case 'modalCambiaFoto':
    $idRecibido = $_GET['idRecibido'];
    $numFoto = $_GET['numFoto'];
    $formCambiaFoto =<<<EOD
      <div class="col-md-12 mt-2 mb-4">
        <h6>Subir foto documento</h6> 
        <form action="upload.php" class="dropzone fileinput-dropzone border-info mb-3" id='importaArchivosAbd'></form>
        <h6>Nombre:</h6>
        <div id="inputNames" class="py-2"></div>
        <script>
        Dropzone.autoDiscover = false;      
        var myDropzone = new Dropzone(".dropzone", { 
          autoProcessQueue: true,
          dictDefaultMessage:"<h5>Haga clic o arrastre aquí su documento</h5>",
          dictInvalidFileType:"Extensión Incorrecta",
          uploadMultiple: false,
          paramName: "file",
          maxFilesize: 2,
          parallelUploads: 1,
          acceptedFiles: "image/*",
          init: function ()  {
            this.on("error", function (file, message) {
                notifica('error',message);
                this.removeFile(file);
            });   
          },
          renameFile: function (file) {
            let newName = new Date().getTime() + '_' + file.name.replace(/ /g, "_");
            $('#inputNames').append('<input type="text" class="form-control mb-1" id="fotoID" name="" disabled value="' + newName + '">');
            return newName;
          },        
        });
      </script>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="far fa-times-circle"></i> Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarFoto" onclick="guardaFotoCambiada('$idRecibido','foto')"><i class="far fa-save"></i> Guardar</button>
      </div>   
    EOD;
    echo $formCambiaFoto;
  break;

  case 'guardaFotoCambiada':
    $idRecibido    = $_GET['idRecibido'];
    $numFoto       = $_GET['numFoto'];
    $fotoID        = $_GET['fotoID'];
    $set_guardarLinea_ejecuta = "foto='{$fotoID}'";
    $where_guardarLinea_ejecuta = "id={$idRecibido}";
    $cambia_guardarLinea_ejecuta = $ClassTodas->actualizaCosasVariasSetWhere('jugadores',$set_guardarLinea_ejecuta,$where_guardarLinea_ejecuta);
    echo $cambia_guardarLinea_ejecuta;
  break;

  case 'eliminaFoto':
    $idRecibido = $_GET['idRecibido'];
    $numFoto    = $_GET['numFoto'];
    $url        = $_GET['url'];
    if ($url == 'sinimagen300x300.png') {
      echo 2;
    } else {
      $eliminaFotoCarpeta = $ClassTodas->eliminarArchivosGeneral($url,'images/jugadores');
      $eliminaFotoQuery = $ClassTodas->actualizaCosasVariasSetWhere('jugadores',$numFoto.'="sinimagen300x300.png"','id='.$idRecibido);
    }
    echo $eliminaFotoQuery;
  break;

  case 'formSumula':    
    $optionEquipos             = ''; 
    $optionCompeticiones       = ''; 
    $buscaDatosEquipos = $ClassTodas->get_datoVariosWhereOrder('equipos','','');
    foreach ($buscaDatosEquipos as $value){
      $id_Equipo = $value['id'];
      $idliga_Equipo = $value['id_competicion'];
      $nombre_Equipo = $value['nombre'];
      $optionEquipos .= "<option value='$id_Equipo'>$nombre_Equipo</option>";
    }
    $buscaDatosCompet = $ClassTodas->get_datoVariosWhereOrder('competiciones','','');
    foreach ($buscaDatosCompet as $value){
      $id_Compet = $value['id'];
      $nombre_Compet = $value['nombre'];
      $optionCompeticiones .= "<option value='$id_Compet'>$nombre_Compet</option>";
    }
    $formSumula =<<<EOD
      <form class="needs-validation" id="formSumula">
      <div class="form-row">
        <div class="col-12 col-md-12">
          <div class="form-group">
            <label>Competición <abbr title="Required">*</abbr></label>
            <select class="form-control custom-select" id="competSumID" name="competSumID" onchange="refrescaEquipos()" required>
              <option></option>
              $optionCompeticiones
            </select>
            <small id="" class="form-text text-muted">Elija la competición referente a este partido.</small>
            <div class="invalid-feedback"> Ingrese este Campo. </div>
          </div> 
        </div>
      </div>
      <div class="form-row">
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label>Equipo Casa <abbr title="Required">*</abbr></label>
            <select class="form-control custom-select" id="equipo1SumID" name="equipo1SumID" required>
              <option></option>
              $optionEquipos
            </select>
            <small id="" class="form-text text-muted">Elija el equipo casa.</small>
            <div class="invalid-feedback"> Ingrese este Campo. </div>
          </div> 
        </div>
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label>Equipo Visitante <abbr title="Required">*</abbr></label>
            <select class="form-control custom-select" id="equipo2SumID" name="equipo2SumID" required>
              <option></option>
              $optionEquipos
            </select>
            <small id="" class="form-text text-muted">Elija el equipo visitante.</small>
            <div class="invalid-feedback"> Ingrese este Campo. </div>
          </div> 
        </div>
      </div>
      <div class="form-row">
        <div class="col-12 col-md-12">
          <div class="form-group">
            <label>Fecha del Partido <abbr title="Required">*</abbr></label>
            <input type="date" class="form-control" id="fechaSumID" name="fechaSumID" required>
            <small id="" class="form-text text-muted">Elija la fecha del partido.</small>
            <div class="invalid-feedback"> Ingrese este Campo. </div>
          </div> 
        </div>
      </div>    
      <div class="form-row">
        <div class="col-12 col-md-12">
          <div class="form-group">
            <label>Jornada <abbr title="Required">*</abbr></label>
            <input type="number" class="form-control" id="jornadaSumID" name="jornadaSumID" required>
            <small id="" class="form-text text-muted">Elija la jornada del partido.</small>
            <div class="invalid-feedback"> Ingrese este Campo. </div>
          </div> 
        </div>
      </div> 
      <div class="form-row">
        <div class="col-12 col-md-12">
          <div class="form-group">
            <label>Tipo Jornada <abbr title="Required">*</abbr></label>
            <select class="form-control custom-select" id="tipoJornadaID" name="tipoJornadaID" required>
              <option value="normal">Jornada Normal</option>
              <option value="octavos">Octavos</option>
              <option value="cuartos">Cuartos</option>
              <option value="semis">Semis</option>
              <option value="3erOro">3er Lugar Oro</option>
              <option value="3erPlata">3er Lugar Plata</option>
              <option value="final">Final</option>
              <option value="amistoso">Amistoso Oficial</option>
            </select>
            <small id="" class="form-text text-muted">Elija el tipo de jornada.</small>
            <div class="invalid-feedback"> Ingrese este Campo. </div>
          </div> 
        </div>
      </div>    
      <div class="form-row">
        <div class="col-12 col-md-12">
          <div class="form-group">
            <label>Cancha <abbr title="Required">*</abbr></label>
            <input type="number" class="form-control" id="canchaSumID" name="canchaSumID" maxlength="2" required>
            <small id="" class="form-text text-muted">Elija la cancha del partido.</small>
            <div class="invalid-feedback"> Ingrese este Campo. </div>
          </div> 
        </div>
      </div>    
      <div class="form-row">
        <div class="col-12 col-md-12">
          <div class="form-group">
            <label>Hora <abbr title="Required">*</abbr></label>
            <input type="time" class="form-control" id="horaPartidoSumID" name="horaPartidoSumID" required>
            <small id="" class="form-text text-muted">Elija la hora del partido.</small>
            <div class="invalid-feedback"> Ingrese este Campo. </div>
          </div> 
        </div>
      </div>
      </form>
      <hr>
      <button type="button" class="btn btn-primary" id="generarSumula" onclick="generaSumula('Hoja del Partido');"><i class="fas fa-eye"></i> Visualizar Hoja del Partido</button
    EOD;
    echo $formSumula;
  break;
  case 'refrescaEquipos':
    $id_compet = $_GET['id_compet'];
    $buscaDatosEquipos = $ClassTodas->get_datoVariosWhereOrder('equipos','WHERE id_competicion ='.$id_compet,'');
    foreach ($buscaDatosEquipos as $value){
      $id_Equipo = $value['id'];
      $nombre_Equipo = $value['nombre'];
      $optionEquipos .= "<option value='$id_Equipo'>$nombre_Equipo</option>";
    }
    echo $optionEquipos;
  break;

  case 'generaSumula':
    $equipo1SumID              = $_GET['equipo1SumID'];
    $equipo2SumID              = $_GET['equipo2SumID'];
    $fechaSumID                = $ClassTodas->cambiaf_a_normal2($_GET['fechaSumID']);  
    $jornadaSumID              = $_GET['jornadaSumID'];
    $canchaSumID               = $_GET['canchaSumID'];
    $competSumID               = $_GET['competSumID'];
    $horaPartidoSumID          = $_GET['horaPartidoSumID'];
    $tipoJornadaID             = $_GET['tipoJornadaID'];
    $trDatosJugadores1 = $trDatosJugadores2 = $trResta1 = $trResta2 = '';


    $buscaDatosEquipos1 = $ClassTodas->get_datoVariosWhereOrder('equipos','WHERE id='.$equipo1SumID,'');
    foreach ($buscaDatosEquipos1 as $value){
      $nombre_Equipo1 = $value['nombre'];
    }    
    $buscaDatosEquipos2 = $ClassTodas->get_datoVariosWhereOrder('equipos','WHERE id='.$equipo2SumID,'');
    foreach ($buscaDatosEquipos2 as $value){
      $nombre_Equipo2 = $value['nombre'];
    }
    $countJugadores1 = 1;
    $buscaDatosJugadores1 = $ClassTodas->get_datoVariosWhereOrderInformes("SELECT jug.* FROM jugadores as jug LEFT JOIN jugadores_equipos as jeq ON jug.id = jeq.id_jugador WHERE jeq.id_equipo='$equipo1SumID' AND jug.activo=1 ORDER BY jug.nombre ASC");
    foreach ($buscaDatosJugadores1 as $value){
      $nombre_Jugador1    = mb_strtoupper($value['nombre']);
      $apellido_Jugador1    = mb_strtoupper($value['apellido']);
      $num_Jugador1       = $value['num_camisa'];
      if($num_Jugador1 == 0) { $num_Jugador1 = ''; }
      $fnacimiento_Jugador1       = $value['fnacimiento'];
      $asegurado_Jugador1       = $value['asegurado'];
      $yearNacimiento = date('Y', strtotime($fnacimiento_Jugador1));
      $yearNow = date('Y', strtotime($SOLOdate));
      $edadNow = $yearNow - $yearNacimiento;
      $buscaEquipo = $ClassTodas->get_datoVariosWhereOrder('equipos','WHERE id='.$equipo1SumID,'');  
      foreach ($buscaEquipo as $value1) {
        $idCompet_BE1 = $value1['id_competicion'];
      }
      $bgSeguro = '';
      $txtSeguro = '';
      $suspendidoSeguro1 = '';
      if($asegurado_Jugador1 == 0 && $idCompet_BE1 != 3 && $idCompet_BE1 != 5 && $idCompet_BE1 != 7) {        
        $bgSeguro = 'background-color: #a9a9a9;';
        $txtSeguro = 'text-decoration: line-through;font-weight: bold;';
        $suspendidoSeguro1 = '<span class="font-weight: bold;">NO ASEGURADO</span>';
      } 
      $trDatosJugadores1 .=<<<EOD
        <tr id="tr1_$countJugadores1" style="$bgSeguro">
          <td class="text-center cursor-pointer" onclick="pintaSuspendido('$countJugadores1','1');">$countJugadores1</td>
          <td id="nombre1_$countJugadores1" style="$txtSeguro">$nombre_Jugador1 $apellido_Jugador1</td>
          <td class="text-center">$edadNow</td>
          <td id="firma1_$countJugadores1" class="text-center">$suspendidoSeguro1</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      EOD;
      $countJugadores1++;
    }
    $countJugadores2 = 1;
    $buscaDatosJugadores2 = $ClassTodas->get_datoVariosWhereOrderInformes("SELECT jug.* FROM jugadores as jug LEFT JOIN jugadores_equipos as jeq ON jug.id = jeq.id_jugador WHERE jeq.id_equipo='$equipo2SumID' AND jug.activo=1 ORDER BY jug.nombre ASC");
    foreach ($buscaDatosJugadores2 as $value){
      $nombre_Jugador2    = mb_strtoupper($value['nombre']);
      $apellido_Jugador2    = mb_strtoupper($value['apellido']);
      $num_Jugador2       = $value['num_camisa'];
      if($num_Jugador2 == 0) { $num_Jugador2 = ''; }
      $fnacimiento_Jugador2       = $value['fnacimiento'];
      $asegurado_Jugador2       = $value['asegurado'];
      $yearNacimiento = date('Y', strtotime($fnacimiento_Jugador2));
      $yearNow = date('Y', strtotime($SOLOdate));
      $edadNow = $yearNow - $yearNacimiento;
      $buscaEquipo = $ClassTodas->get_datoVariosWhereOrder('equipos','WHERE id='.$equipo2SumID,'');  
      foreach ($buscaEquipo as $value1) {
        $idCompet_BE2 = $value1['id_competicion'];
      }
      $bgSeguro2 = '';
      $txtSeguro2 = '';
      $suspendidoSeguro2 = '';
      if($asegurado_Jugador2 == 0 && $idCompet_BE2 != 3 && $idCompet_BE2 != 5 && $idCompet_BE2 != 7) {        
        $bgSeguro2 = 'background-color: #a9a9a9;';
        $txtSeguro2 = 'text-decoration: line-through;font-weight: bold;';
        $suspendidoSeguro2 = '<span class="font-weight: bold;">NO ASEGURADO</span>';
      } 
      $trDatosJugadores2 .=<<<EOD
        <tr id="tr2_$countJugadores2" style="$bgSeguro2">
          <td class="text-center cursor-pointer" onclick="pintaSuspendido('$countJugadores2','2');">$countJugadores2</td>
          <td id="nombre2_$countJugadores2" style="$txtSeguro2">$nombre_Jugador2 $apellido_Jugador2</td>
          <td class="text-center">$edadNow</td>
          <td id="firma2_$countJugadores2" class="text-center">$suspendidoSeguro2</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      EOD;
      $countJugadores2++;
    }
    //AGREGA LÍNEAS VACÍAS A LA TABLA
    $restaCountJugadores1 = 30 - $countJugadores1;
    $restaCountJugadores2 = 30 - $countJugadores2;
    $j1 = 0;
    for($j1=0; $j1 <= $restaCountJugadores1; $j1++) {
      $trResta1 .=<<<EOD
        <tr>
          <td class="text-center">$countJugadores1</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      EOD;
      $countJugadores1++;
    }
    $j2 = 0;
    for($j2=0; $j2 <= $restaCountJugadores2; $j2++) {
      $trResta2 .=<<<EOD
        <tr>
          <td class="text-center">$countJugadores2</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      EOD;
      $countJugadores2++;
    }
    // BUSCAR JOGADORES 1 - ID, NOMBRE, NUMERO, MAX 30
    // BUSCAR JOGADORES 2 - ID, NOMBRE, NUMERO, MAX 30
    $equipo1SumID       = $_GET['equipo1SumID'];
    $equipo2SumID       = $_GET['equipo2SumID'];
    $fechaSumIDSinTrab  = $_GET['fechaSumID'];  
    $fechaSumID         = $ClassTodas->cambiaf_a_normal2($fechaSumIDSinTrab);  
    $jornadaSumID       = $_GET['jornadaSumID'];
    $canchaSumID        = $_GET['canchaSumID'];
    $competSumID        = $_GET['competSumID'];
    $horaPartidoSumID   = $_GET['horaPartidoSumID'];
    $colHeader1 = '5';
    $colHeader2 = '5';

    $tipoPartidoTxt = '';
    $casillerosPartidoLocalTxt =<<<EOD
      <div class="float-right">
        <div class="text-center font-size-xs"><strong>Penaltis</strong></div>
        <div class="d-block mx-auto text-center" style="border: 1px solid #000;width: 80%;height: 50px;"></div>
      </div>
      <div class="float-right mx-1">
        <div class="text-center font-size-xs"><strong>Resultado</strong></div>
        <div class="float-left text-center" style="border: 1px solid #000;width: 98%;height: 50px;"></div>
      </div>
    EOD;
    $casillerosPartidoVisitaTxt =<<<EOD
      <div class="float-left">
        <div class="text-center font-size-xs"><strong>Penaltis</strong></div>
        <div class="d-block mx-auto text-center" style="border: 1px solid #000;width: 80%;height: 50px;"></div>
      </div>
      <div class="float-left mx-1">
        <div class="text-center font-size-xs"><strong>Resultado</strong></div>
        <div class="float-left text-center" style="border: 1px solid #000;width: 98%;height: 50px;"></div>
      </div>
    EOD;
    if($tipoJornadaID == 'octavos'){
      $tipoPartidoTxt = '<h6 class="text-center text-uppercase mb-0 mt-2">Octavos de Final</h6>';
      
    } elseif($tipoJornadaID == 'cuartos'){
      $tipoPartidoTxt = '<h6 class="text-center text-uppercase mb-0 mt-2">Cuartos de Final</h6>';
    } elseif($tipoJornadaID == 'semis'){
      $tipoPartidoTxt = '<h6 class="text-center text-uppercase mb-0 mt-2">Semifinal</h6>';
    } elseif($tipoJornadaID == 'final'){
      $tipoPartidoTxt = '<h6 class="text-center text-uppercase mb-0 mt-2">Final</h6>';
    } else {
      $tipoPartidoTxt = '<br>';
      $casillerosPartidoLocalTxt =<<<EOD
        <div class="float-right">
          <div class="text-center font-size-sm"><strong>Resultado</strong></div>
          <div class="float-left text-center" style="border: 1px solid #000;width: 100%;height: 50px;"></div>
        </div>
      EOD;
      $casillerosPartidoVisitaTxt =<<<EOD
        <div class="float-left">
          <div class="text-center font-size-sm"><strong>Resultado</strong></div>
          <div class="float-left text-center" style="border: 1px solid #000;width: 100%;height: 50px;"></div>
        </div>
      EOD;

    }
    $tablaSumula =<<<EOD
      <div id="imprimirSumula" class="overflow-x-auto">
        <div class="row mb-2" style="min-width: 768px;">
          <div class="col-4">
            <img class="float-left" src="images/logo-liga-de-naciones.png" alt="" height="50">
            <div class="float-left">
              <table class="table mx-3 my-1 font-size-sm tabla-personalizada">
                <thead class="text-uppercase text-nowrap bg-gray">
                  <tr><td colspan="2"><strong>Tiempo del Partido</strong></td></tr>
                </thead>
                <tbody>
                  <tr><td>1T:</td></tr>
                  <tr><td>2T:</td></tr>
                </tbody>
              </table>
            </div>
            $casillerosPartidoLocalTxt
          </div>
          <div class="col-4">
            $tipoPartidoTxt 
            <h6 class="text-center mb-0"><b>Fecha: $fechaSumID - Horario: $horaPartidoSumID</b></h6> 
            <h6 class="text-center mb-0"><b>Jornada: $jornadaSumID - Cancha: $canchaSumID - $competSumID</b></h6> 
          </div>
          <div class="col-4">
            $casillerosPartidoVisitaTxt
            <img class="float-right" src="images/logo-liga-de-naciones.png" alt="" height="50">
          </div>
        </div>
        <div class="row" style="min-width: 768px;"> 
          <div class="col-6"> 
            <table class="table font-size-xs tabla-personalizada">
              <thead class="text-uppercase text-nowrap bg-gray">
                <tr style="border-bottom: 0 !important;">
                  <td colspan="$colHeader1" class="text-center"><b>$nombre_Equipo1</b></td>
                  <td colspan="2" class="text-center"><b>TARJETAS</b></td>
                  <td colspan="1" class="text-center"><b>GOLES</b></td>
                </tr>
                <tr style="border-bottom: 0 !important;" class="text-center">
                  <td style="border-bottom: 0 !important;"><b>#</b></td>
                  <td style="border-bottom: 0 !important;"><b>NOMBRE JUGADOR</b></td>
                  <td style="border-bottom: 0 !important;"><b>EDAD</b></td>
                  <td style="border-bottom: 0 !important; width: 100px;"><b>FIRMA</b></td>
                  <td style="border-bottom: 0 !important;"><b>Nº</b></td>
                  <td style="border-bottom: 0 !important;"><b>TA</b></td>
                  <td style="border-bottom: 0 !important;"><b>TR</b></td>
                  <td style="border-bottom: 0 !important;"><b>GF</b></td>
                </tr>
              </thead>
              <tbody style="border-top: 0 !important;">
                $trDatosJugadores1
                $trResta1
                <tr>
                  <td class="bg-gray text-center">DT:</td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-6"> 
            <table class="table font-size-xs tabla-personalizada">
              <thead class="text-uppercase text-nowrap bg-gray">
                <tr style="border-bottom: 0 !important;">
                  <td colspan="$colHeader2" class="text-center"><b>$nombre_Equipo2</b></td>
                  <td colspan="2" class="text-center"><b>TARJETAS</b></td>
                  <td colspan="1" class="text-center"><b>GOLES</b></td>
                </tr>
                <tr style="border-bottom: 0 !important;" class="text-center">
                  <td style="border-bottom: 0 !important;"><b>#</b></td>
                  <td style="border-bottom: 0 !important;"><b>NOMBRE JUGADOR</b></td>
                  <td style="border-bottom: 0 !important;"><b>EDAD</b></td>
                  <td style="border-bottom: 0 !important; width: 120px;"><b>FIRMA</b></td>
                  <td style="border-bottom: 0 !important;"><b>Nº</b></td>
                  <td style="border-bottom: 0 !important;"><b>TA</b></td>
                  <td style="border-bottom: 0 !important;"><b>TR</b></td>
                  <td style="border-bottom: 0 !important;"><b>GF</b></td>
                </tr>
              </thead>
              <tbody>
                $trDatosJugadores2
                $trResta2
                <tr>
                  <td class="bg-gray">DT:</td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-4"> 
            <table class="table font-size-xs tabla-personalizada">
              <thead class="text-uppercase text-nowrap bg-gray">
                <tr style="border-bottom: 0 !important;">
                  <td style="border-bottom: 0 !important;"><b>NOMBRE ÁRBITRO:</b></td>
                </tr>
              </thead>
              <tbody>
                <tr style="border-bottom: 0 !important;"><td>Asistente 1:</td></tr>
                <tr style="border-bottom: 0 !important;"><td>Asistente 2:</td></tr>
                <tr style="border-bottom: 0 !important;"><td>Turno:</td></tr>
              </tbody>
            </table>
            <table class="table font-size-xs tabla-personalizada">
              <tbody>
                <tr style="border-bottom: 0 !important;"><td>Retira Carnet:</td></tr>
                <tr style="border-bottom: 0 !important;"><td>Retira Carnet:</td></tr>
              </tbody>
            </table>
          </div>
          <div class="col-8"> 
            <table class="table font-size-xs tabla-personalizada">
              <thead class="text-uppercase text-nowrap bg-gray">
                <tr style="border-bottom: 0 !important;">
                  <td style="border-bottom: 0 !important;"><b>INFORME ÁRBITRO:</b></td>
                </tr>
              </thead>
              <tbody>
                <tr style="border-bottom: 0 !important;"><td class="py-5"></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <button type="button" class="btn btn-success btn-block my-3" onclick="imprimePDF('imprimirSumula','$nombre_Equipo1-vs-$nombre_Equipo2')"><i class="fas fa-file-pdf"></i> Descargar PDF</button>
    EOD;
    echo $tablaSumula;
  break;

  case 'listarCompeticiones':
    $idUsuario = $_GET['idUsuario'];
    $idModulo = $_GET['idModulo'];
    $countSubModulo = $_GET['countSubModulo'];
    $datosTabla = '';
    $buscaDatosCompeticiones = $ClassTodas->get_datoVariosWhereOrder('competiciones','','');
    if (empty($buscaDatosCompeticiones)) {
      // do nothing
    } else {
      foreach ($buscaDatosCompeticiones as $value) {
          $id_Compet              = $value['id'];
          $nombre_Compet          = $value['nombre'];        
          $edadMinima_Compet      = $value['edadMinima'];        
          $activo_Compet          = $value['activo'];        
          $fcreacion_Compet       = $value['fecha_creacion'];   
          if ($activo_Compet == 1) {
            $checkedOrNot = 'checked';
          } else {
            $checkedOrNot = '';
          }
          $datosTabla .=<<<EOD
            <tr id="tr_$id_Compet">              
              <td class="align-middle">$nombre_Compet</td>
              <td class="align-middle">
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" id="inputCambia_activo_$id_Compet" $checkedOrNot onclick="cambioCheckbox('competiciones','#inputCambia_activo_$id_Compet','activo','$id_Compet')">
                  <label class="custom-control-label cursor-pointer" for="inputCambia_activo_$id_Compet"></label>
                </div>
              </td>
              <td class="align-middle">$edadMinima_Compet</td>
              <td class="align-middle">$fcreacion_Compet</td>
              <td class="align-middle text-center"> 
                <a id="editarLinea_$id_Compet" class="btn btn-sm btn-icon btn-secondary" onclick="formCompeticiones('Editar','$id_Compet','competiciones','editarCompeticiones')"><i class="fa fa-edit"></i></a>
              </td>
            </tr>
          EOD;
          }
      }
      $tablaDatosJugadores =<<<EOD
        <div class="col-12 d-flex mb-3 justify-content-end">       
          <button type="button" class="btn btn-primary btn-lg" title="Agregar" onclick="formCompeticiones('Agregar','','competiciones','agregarCompeticiones');"><i class="fas fa-plus pr-1"></i>Agregar Competición</button>
        </div>
        <div class="table-responsive">
          <table class="table  table-condensed table-bordered table-sm table-striped input-group-reflow no-footer" id="tablaAdministraCompeticiones">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>¿Bloquea Jugadores?</th>
                <th>Edad Minima</th>
                <th>Fecha Creación</th>
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody>
              $datosTabla
            </tbody>
          </table>
        </div>
      EOD;
    echo $tablaDatosJugadores;  
  break;
  
  case 'formCompeticiones':
    $tipo                = $_GET['tipo'];
    $id                  = $_GET['id'];
    $tabla               = $_GET['tabla'];
    $datosFooterCompet   = '';
    $nombre_compet       = '';
    $edadMinima_compet   = '18';
    $formCompeticiones   = '';

    if ($tipo == 'editarCompeticiones') {
      $buscaDatosJugadores = $ClassTodas->get_datoVariosWhereOrder($tabla, 'WHERE id='.$id.'','ORDER BY id asc');
      foreach ($buscaDatosJugadores as $value) {
        $id_compet           = $value['id'];
        $nombre_compet       = $value['nombre'];
        $edadMinima_compet   = $value['edadMinima'];
      }
      $datosFooterCompet =<<<EOD
          <button type="button" class="btn btn-primary btn-block" onclick="guardarEditaCompeticiones('$id_compet','competiciones','editarCompeticiones')"><i class="far fa-save"></i> Guardar</button>
      EOD;
    } 
    if ($tipo == 'agregarCompeticiones') {
      $datosFooterCompet =<<<EOD
        <button type="button" class="btn btn-primary btn-block" onclick="guardarEditaCompeticiones('','competiciones','agregarCompeticiones')"><i class="far fa-save"></i> Guardar</button>
      EOD;
    }
    $formCompeticiones =<<<EOD
      <form class="form-horizontal pt-2 needs-validation" role="form" id="formContratoID">
        <div class="form-row">
            <div class="col-12 col-md-12">
              <div class="form-group">
                <label>Nombre Competición <abbr title="Required">*</abbr></label>
                <input class="form-control" type="text" id="nombreCompetID" name="nombreCompetID" placeholder="" value="$nombre_compet" required>
                <small id="" class="form-text text-muted">Indique el nombre de la competición.</small>
                <div class="invalid-feedback"> Ingrese este Campo. </div>
              </div> 
            </div>
            <div class="col-12 col-md-12">
              <div class="form-group">
                <label>Edad Mínima <abbr title="Required">*</abbr></label>
                <input class="form-control" type="text" id="edadMinimaCompetID" name="edadMinimaCompetID" onkeypress="return permite(event, 'num')"; value="$edadMinima_compet" required>
                <small id="" class="form-text text-muted">Indique el nombre de la competición.</small>
                <div class="invalid-feedback"> Ingrese este Campo. </div>
              </div> 
            </div>
        </div> 
        <p><span class="list-icon"><span class="oi oi-media-record text-pink pulse"></span></span> 
        <small><u>Cuidado al crear, porque no es posible borrar una competición todavía.</small></u></p>
      </form>
      $datosFooterCompet    
    EOD;
    echo $formCompeticiones;
  break;

  case 'guardarEditaCompeticiones':
    $opcion                       = $_GET['opcion'];
    $tabla                        = $_GET['tabla'];
    $idRecibido                   = $_GET['idRecibido'];
    $edadMinimaCompet             = $_GET['edadMinimaCompet'];
    $nombreCompet                 = mb_strtoupper($_GET['nombreCompet']);

    if ($opcion == 'editarCompeticiones') {
      $setCompeticiones       = "nombre='{$nombreCompet}',edadMinima='{$edadMinimaCompet}'";
      $whereCompeticiones     = "id={$idRecibido}";
      $actualizaCompeticiones = $ClassTodas->actualizaCosasVariasSetWhere($tabla,$setCompeticiones,$whereCompeticiones);
      echo $actualizaCompeticiones;
    }

    if ($opcion == 'agregarCompeticiones') {
      $camposIngresaCompeticiones = "nombre,edadMinima"; 
      $datosIngresaCompeticiones  = "'{$nombreCompet}','{$edadMinimaCompet}'";    
      $IngresaCompeticiones       = $ClassTodas->insertCosasVarias($tabla,$camposIngresaCompeticiones,$datosIngresaCompeticiones);   
      echo $IngresaCompeticiones;
    }
    
  break;

  case 'listarEquipos':
    $idUsuario = $_GET['idUsuario'];
    $idModulo = $_GET['idModulo'];
    $countSubModulo = $_GET['countSubModulo'];
    $datosTabla = '';
    $buscaDatosEquipos = $ClassTodas->get_datoVariosWhereOrder('equipos','','ORDER BY id ASC');
    if (empty($buscaDatosEquipos)) {
      // do nothing
    } else {
      foreach ($buscaDatosEquipos as $value) {
          $id_buscaEquipos             = $value['id'];
          $id_liga_buscaEquipos        = $value['id_competicion'];
          $nombre_buscaEquipos         = $value['nombre'];
          $fecha_creacion_buscaEquipos = $value['fecha_creacion'];
          $buscaDatosCompet = $ClassTodas->get_datoVariosWhereOrder('competiciones','WHERE id='.$id_liga_buscaEquipos,'');
          foreach ($buscaDatosCompet as $value) {
            $nombre_buscaCompet         = $value['nombre'];
          }          
          $datosTabla .=<<<EOD
            <tr id="tr_$id_buscaEquipos">              
              <td class="align-middle">$nombre_buscaEquipos</td>
              <td class="align-middle">$nombre_buscaCompet</td>
              <td class="align-middle">$fecha_creacion_buscaEquipos</td>
              <td class="align-middle text-center"> 
                <a id="editarLinea_$id_buscaEquipos" class="btn btn-sm btn-icon btn-secondary" onclick="formEquipos('Editar','$id_buscaEquipos','equipos','editarEquipos')"><i class="fa fa-edit"></i></a>              
               <a id="editarLinea_$id_buscaEquipos" class="btn btn-sm btn-icon btn-secondary" onclick="eliminarLinea('equipos','$id_buscaEquipos')"><i class="fa fa-trash"></i></a>              
              </td>
            </tr>
          EOD;
          }
      }
      $tablaDatosEquipos =<<<EOD
        <div class="col-12 d-flex mb-3 justify-content-end">       
          <button type="button" class="btn btn-primary btn-lg" title="Agregar" onclick="formEquipos('Agregar','','equipos','agregarEquipos');"><i class="fas fa-plus pr-1"></i>Agregar Equipos</button>
        </div>
        <div class="table-responsive">
          <table class="table  table-condensed table-bordered table-sm table-striped input-group-reflow no-footer" id="tablaAdministraEquipos">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Competición</th>
                <th>Fecha Creación</th>
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody>
              $datosTabla
            </tbody>
          </table>
        </div>
      EOD;
    echo $tablaDatosEquipos;  
  break;

  case 'formEquipos':
    $tipo                 = $_GET['tipo'];
    $id                   = $_GET['id'];
    $tabla                = $_GET['tabla'];
    $datosFooterEquipos   = '';
    $formEquipos          = '';
    $optEmptyAgrega       = '';
    $valoresOptionCompet  = '';
    $nombre_busca_equipos = '';

    if ($tipo == 'editarEquipos') {
      $buscarDatosEquipos = $ClassTodas->get_datoVariosWhereOrder($tabla, 'WHERE id='.$id.'','ORDER BY id asc');
        foreach ($buscarDatosEquipos as $value) {
          $id_busca_equipos        = $value['id'];
          $id_liga_busca_equipos   = $value['id_competicion'];
          $nombre_busca_equipos    = $value['nombre'];
        $buscaDatosCompet    = $ClassTodas->get_datoVariosWhereOrder('competiciones', '', 'ORDER BY nombre');
        foreach ($buscaDatosCompet as $value) {
          $id_buscaDatosCompet        = $value['id'];
          $nombre_buscaDatosCompet    = $value['nombre']; 
          if ($id_buscaDatosCompet == $id_liga_busca_equipos) {            
            $competicionSeleccionada = "selected"; 
          } else {
            $competicionSeleccionada =  "";
            $optEmptyAgrega = '<option></option>';
          } 
          $valoresOptionCompet .= "<option value='$id_buscaDatosCompet' $competicionSeleccionada>$nombre_buscaDatosCompet</option>";
        }
      }
      $datosFooterEquipos =<<<EOD
          <button type="button" class="btn btn-primary btn-block" onclick="guardarEditaEquipos('$id_busca_equipos','equipos','editarEquipos')"><i class="far fa-save"></i> Guardar</button>
      EOD;
    } 
    if ($tipo == 'agregarEquipos') {
      $optEmptyAgrega = '<option></option>';
      $buscaDatosCompet    = $ClassTodas->get_datoVariosWhereOrder('competiciones', '', 'ORDER BY id');
      foreach ($buscaDatosCompet as $value) {
        $id_buscaDatosCompet     = $value['id'];
        $nombre_buscaDatosCompet = $value['nombre']; 
        $valoresOptionCompet  .= "<option value='$id_buscaDatosCompet'>$nombre_buscaDatosCompet</option>";
      }
      $datosFooterEquipos =<<<EOD
        <button type="button" class="btn btn-primary btn-block" onclick="guardarEditaEquipos('','equipos','agregarEquipos')"><i class="far fa-save"></i> Guardar</button>
      EOD;
    }
    $formEquipos =<<<EOD
      <form class="form-horizontal pt-2 needs-validation" role="form" id="formContratoID">
        <div class="form-row">
            <div class="col-12 col-md-12">
              <div class="form-group">
                <label>Nombre <abbr title="Required">*</abbr></label>
                <input class="form-control" type="text" id="nombreEquipoID" name="nombreEquipoID" placeholder="" value="$nombre_busca_equipos" required>
                <small id="" class="form-text text-muted">Indique el nombre.</small>
                <div class="invalid-feedback"> Ingrese este Campo. </div>
              </div> 
            </div>
        </div>
        <div class="form-row">
            <div class="col-12 col-md-12">
              <div class="form-group">
                <label>Competición <abbr title="Required">*</abbr></label>
                <select class="form-control custom-select" id="competicionID" name="competicionID" required>
                    {$optEmptyAgrega}
                    $valoresOptionCompet
                </select>
                <small id="" class="form-text text-muted">Indique una competición.</small>
                <div class="invalid-feedback"> Ingrese este Campo. </div>
              </div> 
            </div>
        </div>           
      </form>
      $datosFooterEquipos    
    EOD;
    echo $formEquipos;
  break;

  case 'guardarEditaEquipos':
    $opcion                       = $_GET['opcion'];
    $tabla                        = $_GET['tabla'];
    $idRecibido                   = $_GET['idRecibido'];
    $competicionID                = $_GET['competicionID'];
    $nombreEquipoID               = mb_strtoupper($_GET['nombreEquipoID']);
    $idEquipoUsuario              = $_GET['idEquipoUsuario'];
    if ($opcion == 'editarEquipos') {
      $setJugadores       = "nombre='{$nombreEquipoID}',id_competicion='{$competicionID}'";
      $whereJugadores     = "id={$idRecibido}";
      $actualizaJugadores = $ClassTodas->actualizaCosasVariasSetWhere($tabla,$setJugadores,$whereJugadores);
      echo $actualizaJugadores;
    } 
    if ($opcion == 'agregarEquipos') {
      $camposIngresaJugadores = "nombre,id_competicion"; 
      $datosIngresaJugadores  = "'{$nombreEquipoID}','{$competicionID}'";    
      $IngresaJugadores       = $ClassTodas->insertCosasVarias($tabla,$camposIngresaJugadores,$datosIngresaJugadores);   
      echo $IngresaJugadores;
    }
  break;

  case 'reportesSeguros':
    $numReport = $_GET['numReport'];
    $datosTabla = '';
    if($numReport == '1'){
      $count = 0;
      $countValorDebido = 0;
      $buscaDatosJugadores = $ClassTodas->get_datoVariosWhereOrderInformes("SELECT  jg.*, js.valorPagado, js.fechaPagoSeguro, js.siniestro, js.modificadoPor, js.fechaLesion, js.jornadaLesion, js.competicionLesion, js.comentarios, js.seguimiento FROM jugadores as jg LEFT JOIN jugadores_seguros as js ON jg.id = js.id_jugador WHERE jg.asegurado = 1 AND js.id IS NULL");
      if (empty($buscaDatosJugadores)) {
        $datosTabla =<<<EOD
          <tr>              
            <td colspan="100" class="text-center py-3">No hay datos para mostrar en la tabla.</td>
          </tr>
        EOD;
      } else {
        $buscaValorSeguro = $ClassTodas->get_datoVariosWhereOrder('seguro_opciones','WHERE tipo="ValorSeguro"','ORDER BY id DESC LIMIT 1');
        foreach($buscaValorSeguro as $value){ $valorSeguroFijado = $value['valor'];}
        foreach ($buscaDatosJugadores as $value) {
          $count++;
          $id_jg                  = $value['id'];
          $foto                   = $value['foto'];
          $nombre                 = $value['nombre'];
          $apellido               = $value['apellido'];
          $documento              = $value['documento'];
          $fnacimientoSinTrab     = $value['fnacimiento'];
          $fnacimiento            = $ClassTodas->cambiaf_a_normal2($fnacimientoSinTrab);
          $id_equipo              = $value['id_equipo'];
          $email                  = $value['email'];
          $celular                = $value['celular'];
          $asegurado              = $value['asegurado'];
          $seguimiento            = $value['seguimiento'];
          $fechaLesion            = (isset($value['fechaLesion'])) ? $ClassTodas->cambiaf_a_normal2($value['fechaLesion']) : 'No Aplica';
          $jornadaLesion          = $value['jornadaLesion'];
          $jornadaLesion          = (empty($jornadaLesion)) ? 'No Aplica' : $jornadaLesion;
          $competicionLesion      = $value['competicionLesion'];
          $competicionLesion      = (empty($competicionLesion)) ? 'No Aplica' : $competicionLesion;
          $comentarios            = $value['comentarios'];
          $aseguradoTxT           = ($asegurado == 1) ? 'Sí' : 'No';
          $seguimientoTxT         = ($seguimiento == 1) ? 'Sí' : 'No';
          $fechaPagoSeguro        = (empty($value['fechaPagoSeguro'])) ? 'No Aplica' : $ClassTodas->cambiaf_a_normal2($value['fechaPagoSeguro']);
          $siniestro              = (empty($value['siniestro'])) ? 'No Aplica' : $value['siniestro'];
          $valorPagado            = (empty($value['valorPagado'])) ? $valorSeguroFijado : $value['valorPagado'];
          $modificadoPor          = (empty($value['modificadoPor'])) ? 'No aplica' : $value['modificadoPor'];
          $fechaModificacion      = $value['fechaModificacion'];
          $edadJugador            = $ClassTodas->obtener_edad_segun_fecha($fnacimiento);
          $buscaDatosEquipos = $ClassTodas->get_datoVariosWhereOrder('equipos','WHERE id='.$id_equipo,'');
          foreach ($buscaDatosEquipos as $value) {
            $equipo         = $value['nombre'];
          }  
          $datosTabla .=<<<EOD
            <tr id="tr_$id_jg">  
              <td class="align-middle">$count</td>
              <td class="align-middle">$nombre $apellido <a href="#" tilte="Editar Jugador" onclick="formJugadores('Editar','$id_jg','jugadores','editarJugadores')"><i class="fa fa-edit"></i></a></td>
              <td class="align-middle">$documento</td>
              <td class="align-middle text-nowrap">$fnacimiento <small>(Edad: $edadJugador)</small></td>
              <td class="align-middle">$equipo</td>
              <td class="align-middle">$aseguradoTxT</td>
              <td class="align-middle">$seguimientoTxT</td>
              <td class="align-middle text-nowrap">$fechaPagoSeguro</td>
              <td class="align-middle">$siniestro</td>
              <td class="align-middle" title="$fechaModificacion">$modificadoPor</td>
              <td class="align-middle text-nowrap">$fechaLesion</td>
              <td class="align-middle">$jornadaLesion</td>
              <td class="align-middle">$competicionLesion</td>
              <td class="align-middle text-center"><i class="fa fa-eye" title="$comentarios"></i></td>
            </tr>
          EOD;
        }
      }
      $countValorDebido       = $valorSeguroFijado * $count;
      $tablaReportes =<<<EOD
        <div class="table-responsive-lg">
          <table class="table table-condensed table-bordered table-striped table-sm font-size-sm w-100" id="tablaReportes">
            <thead>
              <tr class="bg-primary text-center text-white">
                <th colspan="100" class="align-middle"><h5 class="mb-0">Total de jugadores asegurados: $count</h5></th>
              </tr>
              <tr>
                <th>Nº</th>
                <th>Nombre</th>
                <th>Documento</th>
                <th>Nacimiento</th>
                <th>Equipo</th>
                <th>Asegurado</th>
                <th>¿Dar Seguimiento?</th>
                <th>Fecha Pago</th>
                <th>Siniestro</th>
                <th>Modif. por</th>
                <th>Fecha Jornada</th>
                <th>Nº Jornada</th>
                <th>Competición</th>
                <th>Obs.</th>
              </tr>
            </thead>
            <tbody>
              $datosTabla
            </tbody>
            </tfoot>
          </table>
        </div>
      EOD; 
    } elseif($numReport == '2') {
      $count=0;
      $countAsegurado = 0;
      $countValorP = 0;
      $buscaDatosJugadores = $ClassTodas->get_datoVariosWhereOrderInformes("SELECT jg.*, js.valorPagado, js.fechaPagoSeguro, js.siniestro, js.modificadoPor, js.fechaLesion, js.jornadaLesion, js.competicionLesion, js.comentarios, js.seguimiento FROM jugadores AS jg LEFT JOIN jugadores_seguros AS js ON jg.id = js.id_jugador WHERE jg.asegurado = 1 AND js.siniestro > 0");
      if (empty($buscaDatosJugadores)) {
        $datosTabla =<<<EOD
          <tr>              
            <td colspan="100" class="text-center py-3">No hay datos para mostrar en la tabla.</td>
          </tr>
        EOD;
      } else {
        foreach ($buscaDatosJugadores as $value) {
          $count++;
          $id_jg                  = $value['id'];
          $foto                   = $value['foto'];
          $nombre                 = $value['nombre'];
          $apellido               = $value['apellido'];
          $documento              = $value['documento'];
          $fnacimientoSinTrab     = $value['fnacimiento'];
          $fnacimiento            = $ClassTodas->cambiaf_a_normal2($fnacimientoSinTrab);
          $id_equipo              = $value['id_equipo'];
          $email                  = $value['email'];
          $celular                = $value['celular'];
          $asegurado              = $value['asegurado'];
          $seguimiento            = $value['seguimiento'];
          $fechaLesion            = (isset($value['fechaLesion'])) ? $ClassTodas->cambiaf_a_normal2($value['fechaLesion']) : 'No Aplica';
          $jornadaLesion          = $value['jornadaLesion'];
          $jornadaLesion          = (empty($jornadaLesion)) ? 'No Aplica' : $jornadaLesion;
          $competicionLesion      = $value['competicionLesion'];
          $competicionLesion      = (empty($competicionLesion)) ? 'No Aplica' : $competicionLesion;
          $comentarios            = $value['comentarios'];
          $aseguradoTxT           = ($asegurado == 1) ? 'Sí' : 'No';
          $seguimientoTxT         = ($seguimiento == 1) ? 'Sí' : 'No';
          $fechaPagoSeguro        = (empty($value['fechaPagoSeguro'])) ? 'No Aplica' : $ClassTodas->cambiaf_a_normal2($value['fechaPagoSeguro']);
          $siniestro              = $value['siniestro'];
          $valorPagado            = $value['valorPagado'];
          $modificadoPor          = (empty($value['modificadoPor'])) ? 'No aplica' : $value['modificadoPor'];
          $fechaModificacion      = $value['fechaModificacion'];
          $edadJugador            = $ClassTodas->obtener_edad_segun_fecha($fnacimiento);
          $valorPagado            = $valorPagado * $siniestro;
          if($asegurado == 1) { $countAsegurado++; }
          $countValorP       = $countValorP + $valorPagado;
          $buscaDatosEquipos = $ClassTodas->get_datoVariosWhereOrder('equipos','WHERE id='.$id_equipo,'');
          foreach ($buscaDatosEquipos as $value) {
            $equipo         = $value['nombre'];
          }  
          $datosTabla .=<<<EOD
            <tr id="tr_$id_jg">  
              <td class="align-middle">$count</td>
              <td class="align-middle">$nombre $apellido <i class="fa fa-edit cursor-pointer" onclick="formJugadores('Editar','$id_jg','jugadores','editarJugadores')"></i></td>
              <td class="align-middle">$documento</td>
              <td class="align-middle text-nowrap">$fnacimiento <small>(Edad: $edadJugador)</small></td>
              <td class="align-middle">$equipo</td>
              <td class="align-middle">$aseguradoTxT</td>
              <td class="align-middle">$seguimientoTxT</td>
              <td class="align-middle text-nowrap">$fechaPagoSeguro</td>
              <td class="align-middle">$siniestro</td>
              <td class="align-middle" title="$fechaModificacion">$modificadoPor</td>              
              <td class="align-middle">$$valorPagado</td>
              <td class="align-middle text-nowrap">$fechaLesion</td>
              <td class="align-middle">$jornadaLesion</td>
              <td class="align-middle">$competicionLesion</td>
              <td class="align-middle text-center"><i class="fa fa-eye" title="$comentarios"></i></td>
            </tr>
          EOD;
        }
      }
      $countValorP = '$'.number_format($countValorP, 0, ',', '.');
      $tablaReportes =<<<EOD
        <div class="table-responsive-lg">
          <table class="table table-condensed table-bordered table-striped table-sm font-size-sm w-100" id="tablaReportes">
            <thead>
              <tr class="bg-success text-center text-white">
                <th colspan="100" class="align-middle"><h5 class="mb-0">Total de valores pagados: $countValorP</h5></th>
              </tr>
              <tr>
                <th>Nº</th>
                <th>Nombre</th>
                <th>Documento</th>
                <th>Nacimiento</th>                
                <th>Equipo</th>
                <th>Asegurado</th>
                <th>¿Dar Seguimiento?</th>
                <th>Fecha Pago</th>
                <th>Siniestro</th>
                <th>Modif. por</th>
                <th>Valor Pagado</th>
                <th>Fecha Jornada</th>
                <th>Nº Jornada</th>
                <th>Competición</th>
                <th>Obs.</th>
              </tr>
            </thead>
            <tbody>
              $datosTabla
            </tbody>
          </table>
        </div>
      EOD; 
    } elseif($numReport == '3') {
      $count = 0;
      $countAsegurado = 0;
      $countValorP = 0;
      $valorDebido = 0;
      $buscaDatosJugadores = $ClassTodas->get_datoVariosWhereOrderInformes("SELECT jg.*, js.id AS idJS, js.valorPagado, js.fechaPagoSeguro, js.siniestro, js.modificadoPor, js.fechaLesion, js.jornadaLesion, js.competicionLesion, js.comentarios, js.seguimiento FROM jugadores AS jg LEFT JOIN jugadores_seguros AS js ON jg.id = js.id_jugador WHERE jg.asegurado = 1");
      if (empty($buscaDatosJugadores)) {
        $datosTabla =<<<EOD
          <tr>              
            <td colspan="100" class="text-center py-3">No hay datos para mostrar en la tabla.</td>
          </tr>
        EOD;
      } else {
        $buscaValorSeguro = $ClassTodas->get_datoVariosWhereOrder('seguro_opciones','WHERE tipo="ValorSeguro"','ORDER BY id DESC LIMIT 1');
        foreach($buscaValorSeguro as $value){ $valorSeguroFijado = $value['valor'];}
        foreach ($buscaDatosJugadores as $value) {
          $count++;
          $id_jg                          = $value['id'];
          $foto                           = $value['foto'];
          $nombre                         = $value['nombre'];
          $apellido                       = $value['apellido'];
          $documento                      = $value['documento'];
          $fnacimientoSinTrab             = $value['fnacimiento'];
          $fnacimiento                    = $ClassTodas->cambiaf_a_normal2($fnacimientoSinTrab);
          $id_equipo                      = $value['id_equipo'];
          $email                          = $value['email'];
          $celular                        = $value['celular'];
          $asegurado                      = $value['asegurado'];
          $seguimiento                    = $value['seguimiento'];
          $fechaLesion                    = (isset($value['fechaLesion'])) ? $ClassTodas->cambiaf_a_normal2($value['fechaLesion']) : 'No Aplica';
          $jornadaLesion                  = $value['jornadaLesion'];
          $jornadaLesion                  = (empty($jornadaLesion)) ? 'No Aplica' : $jornadaLesion;
          $competicionLesion              = $value['competicionLesion'];
          $competicionLesion              = (empty($competicionLesion)) ? 'No Aplica' : $competicionLesion;
          $comentarios                    = $value['comentarios'];
          $aseguradoTxT                   = ($asegurado == 1) ? 'Sí' : 'No';
          $seguimientoTxT                 = ($seguimiento == 1) ? 'Sí' : 'No';
          $fechaPagoSeguro                = (empty($value['fechaPagoSeguro'])) ? 'No Aplica' : $ClassTodas->cambiaf_a_normal2($value['fechaPagoSeguro']);
          $siniestro                      = (empty($value['siniestro'])) ? 'No Aplica' : $value['siniestro'];
          $valorPagado                    = (empty($value['valorPagado'])) ? '0' : $value['valorPagado'];
          $modificadoPor                  = (empty($value['modificadoPor'])) ? 'No aplica' : $value['modificadoPor'];
          $fechaModificacion              = $value['fechaModificacion'];
          $edadJugador                    = $ClassTodas->obtener_edad_segun_fecha($fnacimiento);
          if ($siniestro !== null && is_numeric($siniestro)) {
            $valorPagado                    = $valorPagado * $siniestro;
          }
          if ($valorSeguroFijado !== null && is_numeric($valorSeguroFijado)) {
            $valorDebido                    = $valorDebido + $valorSeguroFijado;
          }
          if ($valorPagado !== null && is_numeric($valorPagado)) {
            $countValorP                    = $countValorP + $valorPagado;
          }
          $gEquipos                       = $ClassTodas->get_datoVariosWhereOrder('equipos','WHERE id='.$id_equipo,'');
          foreach ($gEquipos as $value) { $equipo = $value['nombre']; }  
          // if($valorPagado == 0) { $valorPagado = '-'.$valorSeguroFijado;}
          $datosTabla .=<<<EOD
            <tr id="tr_$id_jg">  
              <td class="align-middle">$count</td>
              <td class="align-middle">$nombre $apellido <i class="fa fa-edit cursor-pointer" onclick="formJugadores('Editar','$id_jg','jugadores','editarJugadores')"></i></td>
              <td class="align-middle">$documento</td>
              <td class="align-middle text-nowrap">$fnacimiento <small>(Edad: $edadJugador)</small></td>
              <td class="align-middle">$equipo</td>
              <td class="align-middle">$aseguradoTxT</td>
              <td class="align-middle">$seguimientoTxT</td>
              <td class="align-middle text-nowrap">$fechaPagoSeguro</td>
              <td class="align-middle">$siniestro</td>
              <td class="align-middle" title="$fechaModificacion">$modificadoPor</td>
              <td class="align-middle">$$valorPagado</td>
              <td class="align-middle text-nowrap">$fechaLesion</td>
              <td class="align-middle">$jornadaLesion</td>
              <td class="align-middle">$competicionLesion</td>
              <td class="align-middle text-center"><i class="fa fa-eye" title="$comentarios"></i></td>
            </tr>
          EOD;
        }
      }
      $diferenciaPagadoDebido = $valorDebido - $countValorP;
      $bgNegativoPositivo = ($diferenciaPagadoDebido < 0) ? 'bg-red' : 'bg-success';
      $diferenciaPagadoDebido = '$'.number_format($diferenciaPagadoDebido, 0, ',', '.');
      $tablaReportes =<<<EOD
        <div class="table-responsive-lg">
          <table class="table table-condensed table-bordered table-striped table-sm font-size-sm w-100" id="tablaReportes">
            <thead>
              <tr class="$bgNegativoPositivo text-center text-white">
                <th colspan="100" class="align-middle"><h5 class="mb-0">Saldo Financiero: $diferenciaPagadoDebido</h5></th>
              </tr>
              <tr>
                <th>Nº</th>
                <th>Nombre</th>
                <th>Documento</th>
                <th>Nacimiento</th>                
                <th>Equipo</th>
                <th>Asegurado</th>
                <th>¿Dar seguimiento?</th>
                <th>Fecha Pago</th>
                <th>Siniestro</th>
                <th>Modif. por</th>
                <th>Valor Pagado</th>
                <th>Fecha Jornada</th>
                <th>Nº Jornada</th>
                <th>Competición</th>
                <th>Obs.</th>
              </tr>
            </thead>
            <tbody>
              $datosTabla
            </tbody>
          </table>
        </div>
      EOD;    
    }
    echo $tablaReportes;  
  break;
  case 'formJugadorSeguro':
    $idRecibido = $_GET['idRecibido'];
    $opcion    = $_GET['opcion'];
    $idJugador = $_GET['idJugador'];
    
    if($opcion == 'agregar'){
      
      $getNombreJugador = $ClassTodas->get_datoVariosWhereOrder('jugadores','WHERE id='.$idJugador,'LIMIT 1');
      if(empty($getNombreJugador)){
      } else {
        foreach($getNombreJugador as $value){
          $nombre = $value['nombre'];
          $apellido = $value['apellido'];
          $nombreJugador = $nombre.' '.$apellido;
        }
      }

      $getCompeticiones = $ClassTodas->get_datoVariosWhereOrder('competiciones','WHERE activo = 1','');
      if(empty($getCompeticiones)){
      } else {
        foreach($getCompeticiones as $value){
          $nombre = $value['nombre'];
          $optionCompeticionesLesion .= '<option value="'.$nombre.'">'.$nombre.'</option>';
        }
      }
      $modalFooter =<<<EOD
        <div id="modalFooter" class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick=""><i class="far fa-times-circle"></i> Cerrar</button>
          <button type="button" class="btn btn-primary" onclick="guardarSeguroJugador('','jugadores_seguros','agregar');"><i class="far fa-save"></i> Agregar</button>
        </div>
      EOD;
    } elseif ($opcion == 'editar'){
      $getDatosSeguros = $ClassTodas->get_datoVariosWhereOrderInformes("SELECT * FROM jugadores_seguros INNER JOIN jugadores ON jugadores.id = jugadores_seguros.id_jugador WHERE jugadores_seguros.id=$idRecibido");
      if(empty($getDatosSeguros)){
      } else {
        foreach($getDatosSeguros as $value){
          $siniestro            = $value['siniestro'];
          $fechaLesion          = $value['fechaLesion'];
          $jornadaLesion        = $value['jornadaLesion'];
          $competicionLesion    = $value['competicionLesion'];
          $valorPagado          = $value['valorPagado'];
          $fechaPagoSeguro      = $value['fechaPagoSeguro'];
          $nombre               = $value['nombre'];
          $apellido             = $value['apellido'];
          $comentarios          = $value['comentarios'];
          $nombreJugador        = $nombre.' '.$apellido;
        }
      }

      $getCompeticiones = $ClassTodas->get_datoVariosWhereOrder('competiciones','WHERE activo = 1','');
      if(empty($getCompeticiones)){
      } else {
        foreach($getCompeticiones as $value){
          $nombre = $value['nombre'];
          if($nombre == $competicionLesion){
            $optionCompeticionesLesion .= '<option value="'.$nombre.'" selected>'.$nombre.'</option>';
          } else {
            $optionCompeticionesLesion .= '<option value="'.$nombre.'">'.$nombre.'</option>';
          }
          
        }
      }
      $modalFooter =<<<EOD
        <div id="modalFooter" class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick=""><i class="far fa-times-circle"></i> Cerrar</button>
          <button type="button" class="btn btn-primary" onclick="guardarSeguroJugador('$idRecibido','jugadores_seguros','editar');"><i class="far fa-save"></i> Guardar</button>
        </div>
      EOD;
    }   
    $formulario =<<<EOD
      <form class="pt-3">
        <div class="form-row">
          <div class="col-12 col-md-6 mb-3">
            <label>Jugador <abbr title="Required">*</abbr></label>
            <input type="text" class="form-control" id="inputNuevo_nombreJugador" name="inputNuevo_nombreJugador" value="$idJugador - $nombreJugador" disabled required>
            <input type="hidden" class="form-control" id="inputNuevo_idJugador" name="inputNuevo_idJugador" value="$idJugador" disabled>
          </div>
          <div class="col-12 col-md-6 mb-3">
            <label>Siniestro <abbr title="Required">*</abbr></label>
            <input type="number" class="form-control" id="inputNuevo_siniestro" name="inputNuevo_siniestro" value="$siniestro" required>
            <div class="invalid-feedback"> Ingrese un numero de siniestro </div>
            <small id="" class="form-text text-muted">Ingrese un numero de siniestro</small>
          </div>
        </div>
        <div class="form-row">
          <div class="col-12 col-md-6 mb-3">
            <label>Fecha Lesión <abbr title="Required">*</abbr></label>
            <input type="date" class="form-control" id="inputNuevo_fechaLesion" name="inputNuevo_fechaLesion" value="$fechaLesion" required>
            <div class="invalid-feedback"> Ingrese una fecha de lesión </div>
            <small id="" class="form-text text-muted">Ingrese una fecha de lesión</small>
          </div>
          <div class="col-12 col-md-3 mb-3">
            <label>Jornada <abbr title="Required">*</abbr></label>
            <input type="number" class="form-control" id="inputNuevo_jornadaLesion" name="inputNuevo_jornadaLesion" value="$jornadaLesion" maxlength="15" required>
            <div class="invalid-feedback"> Ingrese la jornada de la lesión </div>
            <small id="" class="form-text text-muted">Ingrese la jornada de la lesión</small>
          </div>
          <div class="col-12 col-md-3 mb-3">
            <label>Valor Pagado</label>
            <input type="text" class="form-control" id="inputNuevo_valorPagado" name="inputNuevo_valorPagado" value="$valorPagado" required>
            <div class="invalid-feedback"> Ingrese el valor pagado </div>
            <small id="" class="form-text text-muted">Ingrese el valor pagado</small>
          </div>
        </div>
        <div class="form-row">
          <div class="col-12 col-md-6 mb-3">
            <label>Competición Lesión</label>
            <select class="form-control" id="inputNuevo_competicionLesion" name="inputNuevo_competicionLesion" required>
              <option></option>
              $optionCompeticionesLesion
            </select>
            <div class="invalid-feedback"> Ingrese la competición en que el jugador se lesionó </div>
            <small id="" class="form-text text-muted">Ingrese la competición en que el jugador se lesionó</small>
          </div>
          <div class="col-12 col-md-6 mb-3">
            <label>Fecha Pago</label>
            <input type="date" class="form-control text-uppercase" id="inputNuevo_fechaPagoSeguro" name="inputNuevo_fechaPagoSeguro" value="$fechaPagoSeguro" min="1990-01-01" max="$SOLOdate" required>
            <div class="invalid-feedback"> Ingrese la fecha de pago </div>
            <small id="" class="form-text text-muted">Ingrese la fecha de pago</small>
          </div>
        </div>
        <div class="form-row">
          <div class="col-12 mb-3">
            <label>Comentarios <abbr title="Required">*</abbr></label>
            <textarea class="form-control" id="inputNuevo_comentarios" name="inputNuevo_comentarios" rows="5" cols="40" required>$comentarios</textarea>
            <div class="invalid-feedback"> Ingrese comentarios </div>
            <small id="" class="form-text text-muted">Ingrese comentarios</small>
          </div>
        </div>
      </form>
      $modalFooter
    EOD;
    echo $formulario;
  break;
  case 'guardarSeguroJugador':
    $opcion                         = $_GET['opcion'];
    $tabla                          = $_GET['tabla'];
    $idRecibido                     = $_GET['idRecibido'];
    $inputNuevo_idJugador           = $_GET['inputNuevo_idJugador'];
    $inputNuevo_siniestro           = $_GET['inputNuevo_siniestro'];
    $inputNuevo_valorPagado         = $_GET['inputNuevo_valorPagado'];
    $inputNuevo_fechaPagoSeguro     = $_GET['inputNuevo_fechaPagoSeguro'];
    $inputNuevo_fechaLesion         = $_GET['inputNuevo_fechaLesion'];
    $inputNuevo_jornadaLesion       = $_GET['inputNuevo_jornadaLesion'];
    $inputNuevo_competicionLesion   = $_GET['inputNuevo_competicionLesion'];
    $inputNuevo_comentarios         = $_GET['inputNuevo_comentarios'];
    if($opcion == 'agregar'){
      $campos_gsj = "id_jugador,siniestro,fechaLesion,jornadaLesion,competicionLesion,valorPagado,fechaPagoSeguro,comentarios,modificadoPor,fechaModificacion";
      $valores_gsj = "'{$inputNuevo_idJugador}','{$inputNuevo_siniestro}','{$inputNuevo_fechaLesion}','{$inputNuevo_jornadaLesion}','{$inputNuevo_competicionLesion}','{$inputNuevo_valorPagado}','{$inputNuevo_fechaPagoSeguro}','{$inputNuevo_comentarios}','{$nombreUsuarioGeneral}','{$date}'";
      $resultadoQuery = $ClassTodas->insertCosasVarias($tabla,$campos_gsj,$valores_gsj);
    } elseif($opcion == 'editar'){
      $set_gsj = "id_jugador='{$inputNuevo_idJugador}',siniestro='{$inputNuevo_siniestro}',fechaLesion='{$inputNuevo_fechaLesion}',jornadaLesion='{$inputNuevo_jornadaLesion}',competicionLesion='{$inputNuevo_competicionLesion}',valorPagado='{$inputNuevo_valorPagado}',fechaPagoSeguro='{$inputNuevo_fechaPagoSeguro}',comentarios='{$inputNuevo_comentarios}',modificadoPor='{$nombreUsuarioGeneral}',fechaModificacion='{$date}'";
      $where_gsj = "id='{$idRecibido}'";
      $resultadoQuery = $ClassTodas->actualizaCosasVariasSetWhere($tabla,$set_gsj,$where_gsj);
    }
    echo $resultadoQuery;
  break;
  case 'listarJugadorSeguro':
    $idRecibido  = $_GET['idRecibido'];
    $dato        = $_GET['dato'];
    $getJugadoresSeguros = $ClassTodas->get_datoVariosWhereOrderInformes("SELECT jugadores_seguros.* FROM jugadores_seguros INNER JOIN jugadores ON jugadores.id = jugadores_seguros.id_jugador WHERE jugadores_seguros.id_jugador=$idRecibido AND jugadores.asegurado = 1 ORDER BY jugadores_seguros.siniestro DESC");
    if(empty($getJugadoresSeguros)){
      $trListarJugadores = '<tr><td colspan="100" class="text-center">No hay datos todavía</td></tr>';
    } else {
      foreach($getJugadoresSeguros as $value){
        $idJugSeg_gjs            = $value['id'];
        $siniestro_gjs           = $value['siniestro'];
        $valorPagado_gjs         = $value['valorPagado'];
        $fechaLesion_gjs         = $ClassTodas->cambiaf_a_normal2($value['fechaLesion']);
        $jornadaLesion_gjs       = $value['jornadaLesion'];
        $competicionLesion_gjs   = $value['competicionLesion'];
        $comentarios_gjs         = $value['comentarios'];
        $fechaPagoSeguro_gjs     = $ClassTodas->cambiaf_a_normal2($value['fechaPagoSeguro']);
        $modificadoPor_gjs       = $value['modificadoPor'];
        $seguimiento_gjs         = $value['seguimiento'];
        if ($seguimiento_gjs == 1) {
          $switchSeg =<<<EOD
            <div class="custom-control custom-switch" style="padding-top:5px;">
              <input type="checkbox" class="custom-control-input" id="inputCambia_seguimiento_$idJugSeg_gjs" checked  onclick="cambioCheckbox('jugadores_seguros','#inputCambia_seguimiento_$idJugSeg_gjs','seguimiento','$idJugSeg_gjs')">
              <label class="custom-control-label" for="inputCambia_seguimiento_$idJugSeg_gjs"></label>
            </div>
          EOD;
        }
        if (empty($seguimiento_gjs))  {
          $switchSeg =<<<EOD
            <div class="custom-control custom-switch" style="padding-top:5px;">
              <input type="checkbox" class="custom-control-input" id="inputCambia_seguimiento_$idJugSeg_gjs"  onclick="cambioCheckbox('jugadores_seguros','#inputCambia_seguimiento_$idJugSeg_gjs','seguimiento','$idJugSeg_gjs')">
              <label class="custom-control-label" for="inputCambia_seguimiento_$idJugSeg_gjs"></label>
            </div>
          EOD;
        }
        $trListarJugadores .=<<<EOD
          <tr>
            <td style="display: none;">$idJugSeg_gjs</td>
            <td>$siniestro_gjs</td>
            <td>$fechaLesion_gjs</td>
            <td>$jornadaLesion_gjs</td>
            <td>$competicionLesion_gjs</td>
            <td><span>$</span>$valorPagado_gjs</td>
            <td>$fechaPagoSeguro_gjs</td>
            <td>$comentarios_gjs</td>
            <td>$modificadoPor_gjs</td>
            <td>$switchSeg</td>
            <td>
              <button type="button" title="Editar" class="btn btn-icon btn-sm btn-secondary" onclick="formJugadorSeguro('Editar Pago Seguro', '$idJugSeg_gjs', '$idRecibido', 'editar')"><i class="fa fa-edit"></i></button>
              <button type="button" title="Eliminar" class="btn btn-icon btn-sm btn-secondary" onclick="eliminarLinea('jugadores_seguros','$idJugSeg_gjs');"><i class="fa fa-trash"></i></button>
            </td>
          </tr>
        EOD;
      }
    }
    $tablaListarJugadores =<<<EOD
      <a type="button" style="display:none;" id="listarJugadoresSeguro" onclick="listarJugadorSeguro('$idRecibido','');"></a>
      <button type="button" class="btn btn-primary my-3" title="Agregar" onclick="formJugadorSeguro('Agregar Pago Seguro', '', '$idRecibido', 'agregar')"><i class="fa fa-plus"></i> Agregar Pago Seguro</button>
      <h6>Listado de pagos de seguro para este jugador</h6>
      <div class="table-responsive-lg">
        <table class="table table-condensed table-bordered table-striped table-sm font-size-sm w-100" id="tableListarSeguros">
          <thead>
            <tr>
              <th style="display: none;">Jugador</th>
              <th>Siniestro</th>
              <th>Fecha Lesión</th>
              <th>Jornada Lesión</th>
              <th>Competición Lesión</th>
              <th>Valor Pagado</th>
              <th>Fecha Pago</th>
              <th>Comentarios</th>
              <th>Modificado Por</th>
              <th>Seguimiento</th>
              <th>Opciones</th>
            </tr>
          </thead>
          <tbody>
            $trListarJugadores
          </tbody>
        </table>
      </div>
    EOD;
    echo $tablaListarJugadores;
  break;

  case 'guardarAseguradoDato':
    $idRecibido = $_GET['idRecibido'];
    $asegurado = $_GET['asegurado'];
    $resultadoQuery = '';
    $setJug = "asegurado='{$asegurado}',segModPor='{$nombreUsuarioGeneral}',fechaSegMod='{$date}'";
    $whereJug = "id='{$idRecibido}'";
    $resultadoQuery = $ClassTodas->actualizaCosasVariasSetWhere('jugadores',$setJug,$whereJug);
    if($asegurado == 0 && $resultadoQuery == 1){      
      $eliminarCredenciales = $ClassTodas->eliminarLinea('jugadores_seguros','id_jugador',$idRecibido);
    }
    echo $resultadoQuery;
  break;

  case 'opcionesSeguro':
    $dato = $_GET['dato'];
    $trTabla = '';
    $getOpciones = $ClassTodas->get_datoVariosWhereOrder('seguro_opciones','','');
    if(empty($getOpciones)) {
      $trTabla = '<tr><td colspan="6" class="text-center">No hay datos todavía</td></tr>';
    } else {
      foreach($getOpciones as $value){
        $id_opt                  = $value['id'];
        $tipo_opt                = $value['tipo'];
        $valor_opt               = $value['valor'];
        $modificadoPor           = $value['modificadoPor'];
        $fecha_ingreso_tabla_opt = $value['fecha_ingreso_tabla'];
        $bigPiece                = explode("-",$fecha_ingreso_tabla_opt);
        $littlePiece             = explode(' ', $bigPiece[2]);
        $fecha_ingreso_tabla_opt = $littlePiece[0].'-'.$bigPiece[1].'-'.$bigPiece[0].' '.$littlePiece[1];
        if($tipo_opt == 'ValorSeguro') { 
          $btnEliminar = '';
          $btnEditar = '';
        } else {
          $btnEliminar =<<<EOD
            <button type="button" id="eliminarOpciones" title="Eliminar" class="btn btn-icon btn-sm btn-secondary" onclick="eliminarLinea('seguro_opciones','$id_opt')"><i class="fa fa-trash"></i></button>
          EOD;
          $btnEditar =<<<EOD
            <button type="button" id="editarOpciones" title="Editar" class="btn btn-icon btn-sm btn-secondary" onclick="formOpcSeguros('Editar','$id_opt','seguro_opciones','editar')"><i class="fa fa-edit"></i></button>
          EOD;
        }
        $trTabla .=<<<EOD
          <tr>
            <td class="align-middle">$id_opt</td>
            <td class="align-middle">$tipo_opt</td>
            <td class="align-middle">$$valor_opt</td>
            <td class="align-middle">$modificadoPor</td>
            <td class="align-middle">$fecha_ingreso_tabla_opt</td>
          </tr>
        EOD;
      }
    }
    $tabla =<<<EOD
      <div class="d-flex justify-content-end">
        <button type="button" id="editarOpciones" title="Editar" class="btn btn-primary my-3" onclick="formOpcSeguros('Agregar','','seguro_opciones','agregar')"><i class="fa fa-plus"></i> Agregar</button>
      </div>
      <table class="table table-bordered table-condensed table-striped table-sm" id="tableOpcionesSeguro">
        <thead>
          <tr>
            <th>ID</th>
            <th>Tipo</th>
            <th>Valor</th>
            <th>Modificado Por</th>
            <th>Fecha Ingreso Tabla</th>
          </tr>
        </thead>
        <tbody>
          $trTabla
        </tbody>
      </table>
    EOD;
    echo $tabla;
  break;

  case 'formOpcSeguros':
    $tipo                = $_GET['tipo'];
    $id                  = $_GET['id'];
    $tabla               = $_GET['tabla'];
    $datosFooter         = '';
    $formOpcSeguros      = '';
    $valorSeguroSel      = '';
    $valor_opt           = '';

    if ($tipo == 'editar') {
      $buscaOpt = $ClassTodas->get_datoVariosWhereOrder($tabla, 'WHERE id='.$id.'','ORDER BY id asc');
      foreach ($buscaOpt as $value) {
        $id_opt           = $value['id'];
        $tipo_opt         = $value['tipo'];
        $valor_opt        = $value['valor'];
        $valorSeguroSel   = ($valor_opt == 'ValorSeguro') ? 'selected' : '';
      }
      $datosFooter =<<<EOD
          <button type="button" class="btn btn-primary btn-block" title="Guardar" onclick="guardarEditaOpcSeguros('$id_opt','seguro_opciones','editar')"><i class="far fa-save"></i> Guardar</button>
      EOD;
    } 
    if ($tipo == 'agregar') {
      $datosFooter =<<<EOD
        <button type="button" class="btn btn-primary btn-block" title="Agregar" onclick="guardarEditaOpcSeguros('','seguro_opciones','agregar')"><i class="far fa-save"></i> Agregar</button>
      EOD;
    }
    $formOpcSeguros =<<<EOD
      <form class="form-horizontal pt-2 needs-validation" role="form" id="formOpcSegurosID">
        <div class="form-row">
            <div class="col-12 col-md-12">
              <div class="form-group">
                <label>Tipo <abbr title="Required">*</abbr></label>
                <select class="form-control custom-select" id="tipoID" name="tipoID" required>
                  <option value="ValorSeguro" $valorSeguroSel>Valor Seguro</option>
                </select>
                <small id="" class="form-text text-muted">Indique el tipo de la opción.</small>
                <div class="invalid-feedback"> Ingrese este Campo. </div>
              </div> 
            </div>
            <div class="col-12 col-md-12">
              <div class="form-group">
                <label>Valor <abbr title="Required">*</abbr></label>
                <input class="form-control" type="number" id="valorID" name="valorID" placeholder="" value="$valor_opt" required>
                <small id="" class="form-text text-muted">Indique el valor de la opción.</small>
                <div class="invalid-feedback"> Ingrese este Campo. </div>
              </div> 
            </div>
        </div> 
      </form>
      $datosFooter   
    EOD;
    echo $formOpcSeguros;
  break;

  case 'guardarEditaOpcSeguros':
    $opcion                       = $_GET['opcion'];
    $tabla                        = $_GET['tabla'];
    $idRecibido                   = $_GET['idRecibido'];
    $tipoID                       = $_GET['tipoID'];   
    $valorID                      = $_GET['valorID'];   
    if($opcion == 'editar'){
      $set_opt       = "tipo='{$tipoID}',valor='{$valorID}',modificadoPor='{$nombreUsuarioGeneral}'";
      $where_opt     = "id='{$idRecibido}'";
      $resultadoQuery = $ClassTodas->actualizaCosasVariasSetWhere($tabla,$set_opt,$where_opt);
    } elseif($opcion == 'agregar'){
      $camposIngresa_opt = "tipo,valor,modificadoPor"; 
      $datosIngresa_opt  = "'{$tipoID}','{$valorID}','{$nombreUsuarioGeneral}'";
      $resultadoQuery = $ClassTodas->insertCosasVarias($tabla,$camposIngresa_opt,$datosIngresa_opt);   
    }
    echo $resultadoQuery;
  break;

  case 'eImgNoUsadas':
  
    $buscarFoto = $ClassTodas->get_datoVariosWhereOrderInformes("SELECT foto FROM jugadores WHERE foto <> '' AND foto IS NOT NULL");
    $fotos = array();

    foreach($buscarFoto as $row){
      array_push($fotos,$row['foto']);
    }

    $directorio = dirname( __FILE__ ).'/images/jugadores';
    $gestor = opendir($directorio);

    while (false !== ($archivo = readdir($gestor))) {
        if ($archivo != '.' && $archivo != '..') {          
            if (!in_array($archivo, $fotos)) {
                unlink($directorio . '/' . $archivo);
                if (!file_exists($rutaCompleta)) {
                  $response = 1;
              }
            }
        }
    }

    closedir($gestor);

    echo $response;

  break;

  case 'importarDB':
    $imprimeHola = <<<EOD
      <div class="alert alert-primary has-icon" role="alert">
        <div class="alert-icon">
          <span class="fas fa-bullhorn"></span>
        </div><p class="mb-0">Para realizar la carga masiva, elija que tipo de base de datos quieres alimentar</p>
      </div>
      <button type="button" class="btn btn-info btn-lg" onclick="enDesarrollo()" title="CARGAR ARCHIVO" alt="CARGAR ARCHIVO"><i class="fas fa-cloud-upload-alt"></i> Cargar Jugadores</button>
    EOD;
    echo $imprimeHola;
  break;

  case 'cargaMasiva':
    $show_cargaMasiva = <<<EOD
      <div class="form-group">
      <input type="radio" class="custom-control-input" name="tablaImportarGrupo1" id="tablaImportar1" value="jugadores" hidden checked>
        <label for="tf3">Envío de archivo para carga masiva</label>
        <div id="divFormImportaArchivos">
            <form action="upload.php" class="dropzone fileinput-dropzone" id='importaArchivosAbd'></form>
        </div>
        <br>
        <div><button id="btnMuestraArchivoImportado" type="submit" class="btn btn-primary" onclick="">Importar</button></div>
        <div id="respuestaImportPagosEfectuados"></div>
      </div>
    EOD;
    echo $show_cargaMasiva;
    break;

  case 'muestraArchivoImportado':
    $archivo = $_GET['archivo'];
    $tablaImportar = $_GET['tablaImportar'];
    $directorioUpload = 'documentos/';
    $ClassTodas = new ClassTodas();

    if ($xlsx = SimpleXLSX::parse($directorioUpload . $archivo)) {
      $datoBntVerDatosSubidos = '';
      if ($tablaImportar == 'informat_temp') {
        $datoBntVerDatosSubidos = "busquedaPorRut('Listar Vacaciones', 'listavacaciones');";
        $ClassTodas->get_datoVariosWhereOrderInformes("TRUNCATE TABLE `informat_temp`;");
      }

      $ver_acumulaInsert = '';
      $acumulaInsertMayus = '';  // Inicializa la variable aquí
      $k = '';

      foreach ($xlsx->rows() as $k => $r) {
        if ($k === 0) {
          // Sanitizar los nombres de las columnas: eliminar el prefijo entre paréntesis y espacios innecesarios
          $columnasTitulos = implode(',', array_map(function ($columna) {
            // Eliminar cualquier texto entre paréntesis al inicio y luego eliminar espacios al principio y al final
            $columnaSanitizada = trim(preg_replace('/^\(\d+\)\s*/', '', $columna));
            // Escapar cada columna con comillas invertidas para manejar espacios y caracteres especiales
            return "`" . $columnaSanitizada . "`";
          }, $r));
          continue;
        }

        // Escape de valores y construcción de los valores a insertar
        $valoresFila = array_map(function ($value) {
          return addslashes($value); // Escapa comillas y otros caracteres problemáticos
        }, $r);

        // Construye la cadena para el INSERT
        $acumulaInsert = 'INSERT INTO ' . $tablaImportar . ' (id,' . $columnasTitulos . ') VALUES ';
        $acumulaInsertMayus .= ' ("' . $k . '","' . implode('","', $valoresFila) . '"),';
      }

      if (!empty($acumulaInsertMayus)) {
        $acumulaInsertFinal = rtrim($acumulaInsert . $acumulaInsertMayus, ',');
        // Ejecutar la consulta final de inserción
        $ClassTodas->get_datoVariosWhereOrderInformes($acumulaInsertFinal);
      }
    } else {
      echo SimpleXLSX::parseError();
      exit();
    }

    // Ejecutar las demás consultas
    $deleteTable = 'DROP TABLE informat';
    $renameTable = 'CREATE TABLE informat SELECT * FROM informat_temp';
    $truncateTable = 'TRUNCATE informat_temp';
    $log = 'INSERT INTO sis_historial (`accion`,`datoColumna`,`datoAntiguo`,`datoNuevo`,`modificadoPor`,`fechaModificacion`) VALUES ("i_informat","","","","' . $nombreUsuarioGeneral . '","' . $date . '")';

    // Ejecutar las consultas restantes
    $ClassTodas->get_datoVariosWhereOrderInformes($deleteTable);
    $ClassTodas->get_datoVariosWhereOrderInformes($renameTable);
    $ClassTodas->get_datoVariosWhereOrderInformes($truncateTable);
    $ClassTodas->get_datoVariosWhereOrderInformes($log);

    $imprimeDatosArchivoSubido = <<<EOD
    <div class="alert alert-primary has-icon" role="alert">
      <div class="alert-icon">
        <span class="oi oi-info"></span>
      </div>
      <div class="mb-4 h4"><strong>Nombre Archivo:</strong> $archivo</div>
      <div hidden><strong>Tabla:</strong> $tablaImportar</div>
      <div hidden><strong>Cant. Líneas:</strong> $k</div>
      <div class="font-size-lg text-success"><strong><i class="align-middle far fa-thumbs-up fa-3x"></i> Archivo Importado Satisfactoriamente. Se importaron: $k líneas</strong></div>
      <br>

      <div class="form-group">
        <label class="d-block"><strong></strong></label>
        <button type="button" class="btn btn-info" id="BtnIniciaImportar" onclick="$datoBntVerDatosSubidos $('#modalGeneral_soloCerrar').modal('toggle');"> Ir a Vacaciones</button>
      </div>
    </div>
    EOD;
    echo $imprimeDatosArchivoSubido;
    break;
} /* Fin Switch */?> 