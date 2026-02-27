<?php



$link = mysqli_connect('marcus-mysql', 'kremmee', 'krm479357hjh7ade');

if (!$link) {
    die('Não foi possível conectar: ');
}

$db_selected = mysqli_select_db( $link, 'fiscaliza2');
if (!$db_selected) {
    die ('nao seleciona o banco');
    
}


?>
