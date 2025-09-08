<?php
    $config 						            = array();
    $dominioPrincipal				        = 'ligadenaciones';
    $siglaSistema					          = 'delegados';
    $config['title_system']         = 'Delegados';
    $config['title_abreviado']      = 'Liga de Naciones';
    $config['database']             = "ligadena_ufut";
    $config['uploadDir']            = "uploads/";
    $config['siglaSistema']     	  = $siglaSistema;
    $config['dominioPrincipal']     = $dominioPrincipal;
    $config['hostname']             = "localhost";
    $config['username']             = "root";
    $config['password']             = "";
    $config['table_prefix']         = "";
    $config['universal_admin_url']  = 'https://'.$dominioPrincipal.'.cl/'.$siglaSistema;
    $config['userCorreoWeb']   		  = 'no-responder@ligadenaciones.cl';
    $config['passCorreoWeb']   		  = '?v_A]4z*OaMW';
    $config['show_errors']          = true;
    $config['webmaster_email']      = 'no-responder@ligadenaciones.cl';
    $config['webmaster_name']       = 'Liga de Naciones';
    $config['language']             = 'spanish';
    $config['empresa']              = 'Liga de Naciones';
    $config['depto']                = 'Administracion';
    $config['depto_url']            = 'https://www.'.$dominioPrincipal.'.cl/contacto';
    $config['subtitle_system']      = '';
    $config['mensajeSemanal']       = '';
    $config['habilitaDebug']       	= 0; //MOSTRAR DEBUG: 1=si y 0=No;
?>
