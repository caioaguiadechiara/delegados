<?php
require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ClassTodas
{
    // INICIO DATOS ADMINISTRACIÓN
    private $myconn;
    private $hostname;
    private $username;
    private $password;
    private $database;

    private $empresa;
    private $depto;
    private $depto_url;
    private $title_system;
    private $subtitle_system;

    private $mensajeSemanal;

    private $base_url;
    //private $the_last_error = '';
    private $show_errors = false;

    private $webmaster_email;
    private $webmaster_name;
    private $title_abreviado;
    private $habilitaDebug;

    private $pwd_delete_DC1; //$config['pwd_delete_DC1']
    private $pwd_delete_DC2; //$config['pwd_delete_DC1']
    private $pwd_delete_DC3; //$config['pwd_delete_DC1']
    private $pwd_delete_DC4; //$config['pwd_delete_DC1']
    private $pwd_delete_DC5; //$config['pwd_delete_DC1']

    private $dominioPrincipal;
    private $tablaInformat;
    private $siglaSistema;
    private $uploadDir;

    function __construct(){
        $config = "";
        include("includes/settings.php");
        $this->hostname            = $config['hostname'];
        $this->username            = $config['username'];
        $this->password            = $config['password'];
        $this->database            = $config['database'];
        $this->base_url            = $config['universal_admin_url'];
        $this->empresa             = $config['empresa'];
        $this->depto               = $config['depto'];
        $this->depto_url           = $config['depto_url'];
        $this->title_system        = $config['title_system'];
        $this->subtitle_system     = $config['subtitle_system'];
        $this->title_abreviado     = $config['title_abreviado'];
        $this->habilitaDebug       = $config['habilitaDebug'];
        $this->mensajeSemanal      = $config['mensajeSemanal'];
        $this->dominioPrincipal    = $config['dominioPrincipal'];
        $this->siglaSistema        = $config['siglaSistema'];
        $this->uploadDir           = $config['uploadDir'];
        $this->connect();
    }

    public function uploadDir(){
        return $this->uploadDir;
    }

    public function tablaInformat(){
        return $this->tablaInformat;
    }

    public function siglaSistema(){
        return $this->siglaSistema;
    }

    public function dominioPrincipal(){
        return $this->dominioPrincipal;
    }

    public function webmaster_email(){
        return $this->webmaster_email;
    }

    public function title_abreviado(){
        return $this->title_abreviado;
    }

    public function habilitaDebug(){
        return $this->habilitaDebug;
    }

    public function empresa(){
        return $this->empresa;
    }

    public function depto(){
        return $this->depto;
    }

    public function depto_url(){
        return $this->depto_url;
    }

    public function title_system(){
        return $this->title_system;
    }

    public function subtitle_system(){
        return $this->subtitle_system;
    }

    public function mensajeSemanal(){
        return $this->mensajeSemanal;
    }

    public function webmaster_name(){
        return $this->webmaster_name;
    }

    public function get_base_url(){
        return $this->base_url;
    }

    public function connect(){
        $con = mysqli_connect($this->hostname, $this->username, $this->password, $this->database);
        if (!$con) {
            die('Could not connect to database!');
        } else {
            $this->myconn = $con;
            //echo 'Connection established!';
        }
        return $this->myconn;
    }

    public function disconnect(){
        return  mysqli_close($this->myconn);
    }

    // FIN DATOS ADMINISTRACIÓN

    public function cleanInput($input){

        $search = array(
            '@<script[^>]*?>.*?</script>@si',   // Elimina javascript
            '@<[\/\!]*?[^<>]*?>@si',            // Elimina las etiquetas HTML
            '@<style[^>]*?>.*?</style>@siU',    // Elimina las etiquetas de estilo
            '@<![\s\S]*?--[ \t\n\r]*>@'         // Elimina los comentarios multi-línea
        );

        $output = preg_replace($search, '', $input);
        return $output;
    }
    
    public function cleanInput_otro($input){

        $search = array(
            '@´@si',
            "@'@si",
            '@"@si',
            '@¦@si',
            '@\|@si',
            '@°@si',
            '@`@si',
            '@¬@si'
            
        );

        $output = preg_replace($search, '', $input);
        return $output;
    }

    public function sanitize($input){
        if (is_array($input)) {
            foreach ($input as $var => $val) {
                $output[$var] = htmlspecialchars($val);
            }
        } else {
            $input  = $this->cleanInput($input);
            $output = mysqli_real_escape_string($this->myconn, $input);
        }
        return $output;
    }

    function eliminar_acentos($cadena){

        //Reemplazamos la A y a
        $cadena = str_replace(
            array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
            array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
            $cadena
        );

        //Reemplazamos la E y e
        $cadena = str_replace(
            array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
            array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
            $cadena
        );

        //Reemplazamos la I y i
        $cadena = str_replace(
            array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
            array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
            $cadena
        );

        //Reemplazamos la O y o
        $cadena = str_replace(
            array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
            array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
            $cadena
        );

        //Reemplazamos la U y u
        $cadena = str_replace(
            array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
            array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
            $cadena
        );

        //Reemplazamos la N, n, C y c
        $cadena = str_replace(
            array('Ñ', 'ñ', 'Ç', 'ç'),
            array('N', 'n', 'C', 'c'),
            $cadena
        );

        return $cadena;
    }

    public function validaUserEnter($table, $username, $password){

        $return = false;
        $query = '';
        //$query = "INSERT INTO $table (id_proceso, rut, sec) VALUES ('$id_proceso','$rut','$sec')";            
        $query = "SELECT rut, password  FROM $table WHERE rut='$username' and password='$password'";
        //echo $query;
        $qry = mysqli_query($this->myconn, $query) or die('Query: ' . $query . '<br>Error validaUserEnter(): ' . mysqli_error($this->myconn));
        //print_r($qry);
        if (mysqli_num_rows($qry) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function get_datoVariosWhereOrderInformes($queryEnviado){

        $qry = '';
        $results = array();

        mysqli_query($this->myconn, "SET NAMES 'utf8'");
        $query = "$queryEnviado";
        //echo $query.'<br>';
        $qry = mysqli_query($this->myconn, $query) or die('Query: ' . $query . '<br>Error get_datoVariosWhereOrder(): ' . mysqli_error($this->myconn));
        //$result_count = mysqli_num_rows($qry);
        if ($qry) {
            while ($row = mysqli_fetch_array($qry)) {
                $results[] = $row;
            }
        }
        return $results;
    }

    public function checkSiExiste($queryEnviado){

        $qry = '';
        $results = array();

        mysqli_query($this->myconn, "SET NAMES 'utf8'");
        $query = "$queryEnviado";
        //echo $query.'<br>';
        $qry = mysqli_query($this->myconn, $query) or die('Query: ' . $query . '<br>Error checkSiExiste(): ' . mysqli_error($this->myconn));
        //$result_count = mysqli_num_rows($qry);
        if (mysqli_num_rows($qry) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function limpiaTabla($tabla){
        $qry = '';
        mysqli_query($this->myconn, "SET NAMES 'utf8'");
        $query = "TRUNCATE TABLE $tabla";
        //echo $query.'<br>';
        $qry = mysqli_query($this->myconn, $query) or die('Query: ' . $query . '<br>Error limpiaTabla(): ' . mysqli_error($this->myconn));
        //$result_count = mysqli_num_rows($qry);
        if ($qry) {
            return 1;
        } else {
            return 0;
        }
    }

    public function get_dato($id, $table){
        $qry = '';
        $results = array();
        $id = intval($id);
        mysqli_query($this->myconn, "SET NAMES 'utf8'");
        $query = "SELECT * FROM $table WHERE id = $id";
        $qry = mysqli_query($this->myconn, $query) or die('Error get_dato(): ' . mysqli_error($this->myconn) . "<br> SQL: " . $query . "<br> ErrorNumber: " . mysqli_errno($this->myconn));


        if ($qry) {
            $row = mysqli_fetch_array($qry);
            $results =  $row;
        } else {
            if ($this->show_errors)
                die(mysqli_error($this->myconn));
        }
        return $results;
    }

    public function get_contador($tabla,$where,$order){ 
        mysqli_query($this->myconn,"SET NAMES 'utf8'");
        $query = "SELECT COUNT(*) as total FROM $tabla $where $order";
        //$query.='<br>';
        //echo $query.'<br>';
        $qry = mysqli_query($this->myconn,$query);
        $result = mysqli_fetch_array($qry);
        return $result[0];        
    }

    public function get_datoVariosWhereOrder($tabla, $where, $order){
        $qry = '';
        $results = array();

        mysqli_query($this->myconn, "SET NAMES 'utf8'");
        $query = "SELECT * FROM $tabla $where $order ";
        //echo $query.'<br>';
        $qry = mysqli_query($this->myconn, $query) or die('Error get_datoVariosWhereOrder(): ' . mysqli_error($this->myconn) . "<br> SQL: " . $query . "<br> ErrorNumber: " . mysqli_errno($this->myconn));
        //$result_count = mysqli_num_rows($qry);
        if ($qry) {
            while ($row = mysqli_fetch_array($qry)) {
                $results[] = $row;
            }
        }
        return $results;
    }

    public function get_datoDistintoWhereOrder($campo, $tabla, $where, $order){

        $qry = '';
        //$results = array();

        mysqli_query($this->myconn, "SET NAMES 'utf8'");
        $query = "SELECT distinct($campo) FROM $tabla $where $order ";
        //echo $query.'<br>';
        $qry = mysqli_query($this->myconn, $query) or die('Error get_datoDistintoWhereOrder(): ' . mysqli_error($this->myconn) . "<br> SQL: " . $query . "<br> ErrorNumber: " . mysqli_errno($this->myconn));
        //$result_count = mysqli_num_rows($qry);
        if ($qry) {
            while ($row = mysqli_fetch_array($qry)) {
                $results[] = $row;
            }
        }
        return $results;
    }

    public function get_datoDistintoCamposWhereOrder($campo,$campo2, $tabla, $where, $order){
        $qry = '';
        mysqli_query($this->myconn, "SET NAMES 'utf8'");
        $query = "SELECT distinct($campo),$campo2 FROM $tabla $where $order";
        //echo $query.'<br>';
        $qry = mysqli_query($this->myconn, $query) or die('Error get_datoDistintoWhereOrder(): ' . mysqli_error($this->myconn) . "<br> SQL: " . $query . "<br> ErrorNumber: " . mysqli_errno($this->myconn));
        //$result_count = mysqli_num_rows($qry);
        if ($qry) {
            while ($row = mysqli_fetch_array($qry)) {
                $results[] = $row;
            }
        }
        return $results;
    }

    public function actualizaCosasVarias($tabla,$ValorCampoDelId,$campoAcambiar,$nuevoValor,$cualCampoTabla1){
        mysqli_query($this->myconn, "SET NAMES 'utf8'");
        if (!$cualCampoTabla1) { 
            $query = "UPDATE  $tabla SET  $campoAcambiar = '$nuevoValor' WHERE id = $ValorCampoDelId"; 
        } else {
            $query = "UPDATE  $tabla SET  $campoAcambiar = '$nuevoValor' WHERE idUsuario = $ValorCampoDelId and idModulo='$cualCampoTabla1'"; 
        }
              
        mysqli_query($this->myconn, "SET NAMES 'utf8'");
        //echo $query.'<br>';
        $qry = mysqli_query($this->myconn, $query) or die('Error actualizaCosasVarias(): ' . mysqli_error($this->myconn) . "<br> SQL: " . $query); 
        if($qry){
            return 1;  
        } else {
            return 0;
        }
    }

    public function actualizaCosasVariasSetWhere($tabla, $set, $where){
        mysqli_query($this->myconn, "SET NAMES 'utf8'");
        $query = "UPDATE  $tabla SET  $set WHERE $where";
        //echo $query;
        //return false;      
        mysqli_query($this->myconn, "SET NAMES 'utf8'");
        $qry = mysqli_query($this->myconn, $query) or die('Error actualizaCosasVariasSetWhere(): ' . mysqli_error($this->myconn) . "<br> SQL: " . $query);

        if ($qry) {
            return 1;
        } else {
            return 0;
        }
    }

    public function insertCosasVarias($tabla, $camposEnTabla, $camposEnviado) {
        mysqli_query($this->myconn, "SET NAMES 'utf8'");
        $query = "INSERT INTO  $tabla ($camposEnTabla) VALUES ($camposEnviado)";
        //echo $query;      
        mysqli_query($this->myconn, "SET NAMES 'utf8'");
        $qry = mysqli_query($this->myconn, $query) or die('Error insertCosasVarias(): ' . mysqli_error($this->myconn) . "<br> SQL: " . $query);
        if ($qry) {
            return 1;
        } else {
            return 0;
        }
    }

    public function insertCosasVariasDevuelveId($tabla, $camposEnTabla, $camposEnviado){
        mysqli_query($this->myconn, "SET NAMES 'utf8'");
        $query = "INSERT INTO  $tabla ($camposEnTabla) VALUES ($camposEnviado)";
        //echo $query;      
        mysqli_query($this->myconn, "SET NAMES 'utf8'");
        $qry = mysqli_query($this->myconn, $query);
        //return mysqli_insert_id($this->myconn);
        if ($qry) {
            return mysqli_insert_id($this->myconn);
        } else {
            if ($this->show_errors) {
                die('Error insertCosasVarias(): ' . mysqli_error($this->myconn) . "<br> SQL: " . $query);
            }
        }
    }

    public function get_accesoUsuarioInicio($idUsuario){

        mysqli_query($this->myconn, "SET NAMES 'utf8'");
        $query = "SELECT * FROM credenciales_acciones where idUsuario=$idUsuario";
        //$query.='<br>';
        //echo $query.'<br>';
        $qry = mysqli_query($this->myconn, $query) or die('Query: ' . $query . '<br>Error get_accesoUsuarioInicio(): ' . mysqli_error($this->myconn));
        //$result_count = mysqli_num_rows($qry);
        if ($qry) {
            while ($row = mysqli_fetch_array($qry)) {
                $results[] = $row;
            }
        }
        return $results;
    }

    public function get_accesoUsuario($idUsuario, $idModulo){

        mysqli_query($this->myconn, "SET NAMES 'utf8'");
        $query = "SELECT * FROM credenciales_acciones where idUsuario=$idUsuario and idModulo='$idModulo'";
        //$query.='<br>';
        //echo $query.'<br>';
        $qry = mysqli_query($this->myconn, $query) or die('Query: ' . $query . '<br>Error get_accesoUsuario(): ' . mysqli_error($this->myconn));
        if ($qry) {
            while ($row = mysqli_fetch_array($qry)) {
                $results[] = $row;
            }
        }
        return $results;
    }

    public function cambiaPassFromCambioContrasenaInicioSistema($tabla, $nuevaPass, $idUsuario){

        $return = "";
        $query = '';
        mysqli_query($this->myconn, "SET NAMES 'utf8'");
        $query = "UPDATE $tabla SET  password = $nuevaPass WHERE id = $idUsuario ";
        //echo $query;      
        $qry = mysqli_query($this->myconn, $query) or die('Error cambiaPassFromCambioContrasenaInicioSistema(): ' . mysqli_error($this->myconn));
        if ($qry) {
            //echo $nuevoValor;
            return 1;
        } else {
            return 0;
        }
    }

    public function eliminarLinea($tabla, $param, $idLinea){
        $query = '';
        mysqli_query($this->myconn, "SET NAMES 'utf8'");
        $query = "DELETE FROM $tabla WHERE $param = $idLinea ";
        //echo $query;      
        $qry = mysqli_query($this->myconn, $query) or die('Error eliminarLinea(): ' . mysqli_error($this->myconn));
        if ($qry) {
            //echo $nuevoValor;
            return 1;
        } else {
            return 0;
        }
    }

    public function eliminarLineaTodas($tabla, $idLinea, $nombreCampo){
        $query = '';
        mysqli_query($this->myconn, "SET NAMES 'utf8'");
        if ($tabla == "tecnicos" || $tabla == "supervisores" || $tabla == "tipoEquipos" || $tabla == "ordenTrabajo") {
            $query = "";
        } else {
            $query = "DELETE FROM $tabla WHERE $nombreCampo = $idLinea ";
        }
        //echo $query;      
        $qry = mysqli_query($this->myconn, $query) or die('Error eliminarLinea(): ' . mysqli_error($this->myconn));
        if ($qry) {
            //echo $nuevoValor;
            return 1;
        } else {
            return 0;
        }
    }

    public function devuelve_dv($rol){
        /* Bonus: remuevo los ceros del comienzo. */
        while (empty($rol[0])) {
            $rol = substr($rol, 1);
        }
        $factor = 2;
        $suma = 0;
        for ($i = strlen($rol) - 1; $i >= 0; $i--) {
            $suma += $factor * $rol[$i];
            $factor = $factor % 7 == 0 ? 2 : $factor + 1;
        }
        $dv = 11 - $suma % 11;
        /* Por alguna razón me daba que 11 % 11 = 11. Esto lo resuelve. */
        $dv = $dv == 11 ? 0 : ($dv == 10 ? "K" : $dv);
        return $dv;
    }

    public function validaRut($rut){
        $suma = '';
        if (strpos($rut, "-") == false) {
            $RUT[0] = substr($rut, 0, -1);
            $RUT[1] = substr($rut, -1);
        } else {
            $RUT = explode("-", trim($rut));
        }
        $elRut = str_replace(".", "", trim($RUT[0]));
        $factor = 2;
        for ($i = strlen($elRut) - 1; $i >= 0; $i--) :
            $factor = $factor > 7 ? 2 : $factor;
            $suma += $elRut[$i] * $factor++;
        endfor;
        $resto = $suma % 11;
        $dv = 11 - $resto;
        if ($dv == 11) {
            $dv = 0;
        } else if ($dv == 10) {
            $dv = "k";
        } else {
            $dv = $dv;
        }
        if ($dv == trim(strtolower($RUT[1]))) {
            return true;
        } else {
            return false;
        }
    }

    public function CambiaPassword($tabla, $nuevaPass, $idUsuario, $solicitaCambioPass){
        $query = '';
        mysqli_query($this->myconn, "SET NAMES 'utf8'"); 
        if ($solicitaCambioPass == 1) {
            $query = "UPDATE  $tabla SET  password = '$nuevaPass', pideCambioPass = 1 WHERE  id = '$idUsuario' LIMIT 1 ;";
        } else {
            $query = "UPDATE  $tabla SET  password = '$nuevaPass' WHERE  id = '$idUsuario' LIMIT 1 ;";
        }
        //echo $query;      
        mysqli_query($this->myconn, "SET NAMES 'utf8'"); 
        $qry = mysqli_query($this->myconn, $query) or die('Error CambiaPassword - Query: ' . $query . '<br> Error SQL:' . mysqli_error($this->myconn));
        if ($qry) {
            //echo $nuevoValor;
            return 1;
        } else {
            //echo $datoAntiguo;
            return 0;
        }
    }

    public function generatePassword($length){
        $characters = '123456789';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public function generatePasswordAlfaNum($length){
        $characters = '123456789abcdefghjkmnpqrstuvyz';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public function generaHash($frase){
        $hashUnico = hash_init('md5');
        hash_update($hashUnico, $frase . date('DdMYHis'));
        return hash_final($hashUnico);
    }

    public function ExcelDateToUnix($dateValue = 0){
        return ($dateValue - 25569) * 86400;
    }

    public function cortar_palabras($texto, $largor = 20, $puntos = "..."){
        $palabras = explode(' ', $texto);
        if (count($palabras) > $largor) {
            return implode(' ', array_slice($palabras, 0, $largor)) . " " . $puntos;
        } else {
            return $texto;
        }
    }

    public function mostrar_adjunto($idLinea, $storeFolder){

        $ds = DIRECTORY_SEPARATOR;
        $targetPath = dirname(__FILE__) . $ds . $storeFolder . $ds;  //4
        //$ejec=array_map('unlink', glob($targetPath.$idLinea."_*.*"));

        foreach (glob($targetPath . $idLinea . "_*.*") as $nombre_fichero) {
            echo "Archivo: $nombre_fichero ";
        }
    }

    public function zerofill($entero, $largo, $tipo){
        // Limpiamos por si se encontraran errores de tipo en las variables
        $entero = $entero;
        $largo = $largo;

        $relleno = '';

        /**
         * Determinamos la cantidad de caracteres utilizados por $entero
         * Si este valor es mayor o igual que $largo, devolvemos el $entero
         * De lo contrario, rellenamos con ceros a la izquierda del número
         **/
        if (strlen($entero) <= $largo) {

            switch ($tipo) {
                case 'num':
                    $relleno = str_repeat('0', $largo - strlen($entero));
                    $resultado =  $relleno . $entero;
                    break;

                case 'car':
                    $relleno = str_repeat(' ', $largo - strlen($entero));
                    $resultado =   $entero . $relleno;
                    break;

                default:
                    # code...
                    break;
            }
            return $resultado;
        }
    }

    public function descargarArchivo($fichero){
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($fichero) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fichero));
        readfile($fichero);
        exit;
    }
    
    public function verificaRut($table, $rutenviado){

        $return = false;
        $query = '';
        //$query = "INSERT INTO $table (id_proceso, rut, sec) VALUES ('$id_proceso','$rut','$sec')";            
        $query = "SELECT rut  FROM $table WHERE rut='$rutenviado'";
        //echo $query;
        $qry = mysqli_query($this->myconn, $query); // or die('Error: '.$query.'<br> Error SQL:'.mysqli_error($this->myconn))
        //echo "<script> alert('change_user: INSERT INTO $table (id_proceso, rut, sec) VALUES ('$id_proceso','$rut','$sec')); </script>";
        //print_r($qry);
        if (mysqli_num_rows($qry) > 0) {
            // 1: si está
            return 1;
        } else {
            // 0: no está
            return 0;
        }
    }
    
    public function eliminarArchivosGeneral($nombreArchivo,$directorio){
        $qry = unlink($directorio.'/'.$nombreArchivo);       
        if($qry)
        {
          return 1;
          
        } else {
          return 0;
        }
    }

    public function soloNumDelCodigo($codigoEnviado){
        $pos = strrpos($codigoEnviado, '-');
        if ($pos === false) {
            return false;
        } else {
            return substr($codigoEnviado, $pos + 1);
        }
    }

    public function get_dv($_rol) {
        /* Bonus: remuevo los ceros del comienzo. */
        while($_rol[0] == "0") {
            $_rol = substr($_rol, 1);
        }
        $factor = 2;
        $suma = 0;
        for($i = strlen($_rol) - 1; $i >= 0; $i--) {
            $suma += $factor * $_rol[$i];
            $factor = $factor % 7 == 0 ? 2 : $factor + 1;
        }
        $dv = 11 - $suma % 11;
        /* Por alguna razón me daba que 11 % 11 = 11. Esto lo resuelve. */
        $dv = $dv == 11 ? 0 : ($dv == 10 ? "K" : $dv);
        return $dv;
    }
    
    public function obtener_edad_segun_fecha($fecha_nacimiento){ 
        $nacimiento = new DateTime($fecha_nacimiento);
        $ahora = new DateTime(date("Y-m-d"));
        $diferencia = $ahora->diff($nacimiento);

        return $diferencia->format("%y");
    }

    public function cambiaf_a_normal($fecha){ 
        $dia = substr($fecha, -10, 2);
        $mes = substr($fecha, -7, 2);
        $ano = substr($fecha, -4, 4);
        if ($mes == "01"){ $mes = "Enero";      }
        if ($mes == "02"){ $mes = "Febrero";    }
        if ($mes == "03"){ $mes = "Marzo";      }
        if ($mes == "04"){ $mes = "Abril";      }
        if ($mes == "05"){ $mes = "Mayo";       }
        if ($mes == "06"){ $mes = "Junio";      }
        if ($mes == "07"){ $mes = "Julio";      }
        if ($mes == "08"){ $mes = "Agosto";     }
        if ($mes == "09"){ $mes = "Septiembre"; }
        if ($mes == "10"){ $mes = "Octubre";    }
        if ($mes == "11"){ $mes = "Noviembre";  }
        if ($mes == "12"){ $mes = "Diciembre";  }
        $lafecha = $dia." de ".$mes." de ".$ano; 
        return $lafecha; 
    } 

    public function cambiaf_a_normal1($fecha){ 
        $dia = substr($fecha, -2, 2);
        $mes = substr($fecha, -5, 2);
        $ano = substr($fecha, -10, 4);

        if ($mes=="01") {
            $mes="Enero";
        } 

        if ($mes=="02"){
            $mes="Febrero";
        } 
        if ($mes=="03"){
            $mes="Marzo";
        } 
        if ($mes=="04"){
            $mes="Abril";
        } 
        if ($mes=="05"){
            $mes="Mayo";
        } 
        if ($mes=="06"){
            $mes="Junio";
        } 
        if ($mes=="07"){
            $mes="Julio";
        } 
        if ($mes=="08"){
            $mes="Agosto";
        } 
        if ($mes=="09"){
            $mes="Septiembre";
        } 
        if ($mes=="10"){
            $mes="Octubre";
        } 
        if ($mes=="11"){
            $mes="Noviembre";
        } 
        if ($mes=="12"){
            $mes="Diciembre";
        } 
        $lafecha=$dia." de ".$mes." de ".$ano; 
        return $lafecha; 
    } 

    public function cambiaf_a_normal2($fecha){ 
        $lafecha = '';
        if ($fecha !== null) {
            $dia = substr($fecha, -2, 2);
            $mes = substr($fecha, -5, 2);
            $ano = substr($fecha, -10, 4);
            $lafecha = $dia."-".$mes."-".$ano; 
        }

        return $lafecha;
    }
    
    public function eliminaDatosEnTabla($table,$campoid,$id) {
        $return = false;
        $query = '';
        $query = "DELETE FROM $table WHERE $campoid='$id' ";            
        $qry = mysqli_query($this->myconn,$query);
        if($qry){   
            return 1;
        } else {            
            return 0;
        }
    }

    public function validaJugador($table, $documento){

        $return = false;
        $query = '';
        //$query = "INSERT INTO $table (id_proceso, rut, sec) VALUES ('$id_proceso','$rut','$sec')";            
        $query = "SELECT documento FROM $table WHERE documento='$documento'";
        //echo $query;
        $qry = mysqli_query($this->myconn, $query) or die('Query: ' . $query . '<br>Error validaJugador(): ' . mysqli_error($this->myconn));
        //print_r($qry);
        if (mysqli_num_rows($qry) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function cambiaDiaSemanaAEspanol($DiaSemana) {
        $find = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'); 
        $replace = array('Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado','Domingo'); 
        return str_replace($find, $replace, $DiaSemana);   
    } 

    public function strReplaceAssoc(array $replace, $subject) { 
        return str_replace(array_keys($replace), array_values($replace), $subject);    
    } 

    public function generaAccesosSistemaFinal($idUsuario){
        mysqli_query($this->myconn, "SET NAMES 'utf8'"); 
        $query1 = "INSERT INTO `credenciales_acciones` (`idUsuario`, `idModulo`, `idModuloNombre`, `idModuloSub`, `modulo`, `activo`, `botonVer`, `botonEditar`, `botonAgregar`, `botonEliminar`, `botonImprimir`, `fecha_ingreso_tabla`) VALUES 
        ('$idUsuario','10', 'Jugadores', '10', 'Jugadores', '1','1', '1', '1', '1', '1', now()),
        ('$idUsuario','10', 'Jugadores', '11', 'Listar Jugadores', '1','1', '1', '1', '1', '1', now()),
        ('$idUsuario','10', 'Jugadores', '12', 'Agregar Jugadores', '1','1', '1', '1', '1', '1', now())
        ";

        $qry1 = mysqli_query($this->myconn, $query1) or die('Error generaAccesosSistema(): query1 ' . mysqli_error($this->myconn) . "<br> SQL: " . $query1);
        if($qry1) {
            $this->actualizaCosasVariasSetWhere('credenciales', 'accesoCreado=1', ' id='.$idUsuario);
            return 1;
        } else {
            return 0;
        }
    }
    
    public function enviaCorreoVariosTipos($tipoEnvio,$emailEnviadoUnico,$asuntoEnviado,$bodyEnviado){
        
        $mail = new PHPMailer(true);

        if ($tipoEnvio=='recuperarContrasena' || $tipoEnvio=='enviaUsuarioPass') {
            $Subject = $asuntoEnviado;
            $body=$bodyEnviado;
            $mail->AddAddress($emailEnviadoUnico);
        } 

        $mail->IsSMTP();  
        $mail->Subject = $Subject; 

        /* SMTP CONFIG */
        try {
            $mail->CharSet      = "utf8"; 
            $nombre_emisor      = "Liga de Naciones";
            $correo_emisor      = "no-responder@ligadenaciones.cl";   
            $mail->SMTPAuth     = true;
            $mail->SMTPSecure   = "ssl";
            $mail->Host         = "ligadenaciones.cl";
            $mail->Port         = 465;
            $mail->Username     = "no-responder@ligadenaciones.cl";
            $mail->Password     = '?v_A]4z*OaMW';
            
            $mail->SetFrom($correo_emisor, $nombre_emisor);
            $mail->Body = $body;
            $mail->IsHTML(true);

            if($mail->Send()){
                return 1; //Exito
            } else {
                return 0; //Fallo
            }
        } 
        catch (phpmailerException $e) {
        //echo 'e1-ERROR: '.$e->errorMessage(); //Errores de PhpMailer
        } 
        catch (Exception $e) {
        //echo 'e2-ERROR: '.$e->getMessage(); //Errores de cualquier otra cosa.
        }
    }  
          
}