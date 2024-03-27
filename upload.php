<?php
    $ds          = DIRECTORY_SEPARATOR;
    $storeFolder = 'images/jugadores';
 
    if (!empty($_FILES)) {        
        $tempFile   = $_FILES['file']['tmp_name'];
        $targetPath = dirname( __FILE__ ).$ds.$storeFolder.$ds;
        $originalName = $_FILES['file']['name'];
        $safeName     = str_replace(' ', '_', $originalName);
        $targetFile =  $targetPath.$safeName;
        move_uploaded_file($tempFile,$targetFile); 
    }

?>  