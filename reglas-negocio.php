<?php
session_start();
date_default_timezone_set('America/Santiago');
$date=date('Y-m-d H:i:s');
include __DIR__.'/includes/class/ClassTodas.php';
$ClassTodas = new ClassTodas();

$ctx = hash_init('sha1');
hash_update($ctx, 'SGF');
$hash = hash_final($ctx).date('DdMYHis');

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

$type = $_GET['type'];
if($type == ''){
    $type= $_POST['type'];
}

switch ($type){
    case 'reglasNegocio':
        $imprimeTableTr = '';

        $buscaReglas = $ClassTodas->get_datoVariosWhereOrder('reglasNegocio','','ORDER BY modulo ASC');

        if (empty($buscaReglas)) {
            $datosTabla =<<<EOD
            <tr>              
                <td colspan="4" class="text-center py-3">No hay datos para mostrar en la tabla.</td>
            </tr>
            EOD;
        } else {
            foreach ($buscaReglas as $value) {
            $id_buscaReglas                 = $value['id'];
            $regla_buscaReglas              = $value['regla'];
            $modulo_buscaReglas             = $value['modulo'];
            $activo_buscaReglas             = ($value['activo'] == 1) ? '<span class="badge badge-success">Activa</span>' : '<span class="badge badge-danger">Inactiva</span>';
            $fc_buscaReglas                 = $value['fecha_creacion'];
            $imprimeTableTr .=<<<EOD
                <tr id="$id_buscaReglas">
                    <td class="align-middle text-center">$id_buscaReglas</td>
                    <td class="align-middle font-weight-bold">$modulo_buscaReglas</td>
                    <td class="align-middle">$regla_buscaReglas</td>
                    <td class="align-middle text-center">$activo_buscaReglas</td>
                    <td class="align-middle" nowrap></td>
                </tr>
            EOD;
            }
        }

        $table =<<<EOD
            <!--<div class="col-12 d-flex mb-3 justify-content-end">       
                <button type="button" class="btn btn-primary btn-lg" title="Agregar" onclick=""><i class="fas fa-plus pr-1"></i>Agregar Regla</button>
            </div>-->
            <div class="table-responsive-lg mt-3">
                <table class="table table-condensed table-bordered table-sm table-striped input-group-reflow no-footer" id="tablaReglas">
                    <thead>
                        <tr>
                        <th class="align-middle text-center">Id</th>
                        <th class="align-middle">Módulo</th>
                        <th class="align-middle">Regla</th>
                        <th class="align-middle text-center">Estatus</th>
                        <th class="align-middle text-center">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        $imprimeTableTr
                    </tbody>
                </table>
            </div>
        EOD;

        echo $table;
    break;


} /* Fin Switch */?> 
