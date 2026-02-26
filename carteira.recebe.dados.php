<?php

session_start();

if(!isset($_SESSION["usuario_fis_cpf"]))
{
  header('Location: https://integra.pc.sc.gov.br/');
  exit();
}

include "classes/class.html.php";
include "classes/classe.forms.php";
include "mysql.conecta.rep.php";

$header=new Header_adm_WEB(); 

$f = new Forms();

$a=new Menu_adm($link);

require_once 'htmlpurifier/HTMLPurifier.standalone.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$nome_pagina= "Bem-Vindo";
$a=new Abre_titulo();
$a->titulo_pagina($nome_pagina);
$f -> abre(12);
$f -> abre_card(12);
$tipo = $_GET['tipo'];

switch ($tipo) {
    case 'blaster':
       // echo "ok";
        include "carteira.int.blaster.branca.php";
        $titulo_pagina = "Carteira de Blaster";
        $id_tipo = 17; // ID NA TABELA TB_ALVARAS_TIPO
        break;
    
    case 'aposentado':
        include "carteira.int.aposentado.branca.php";
        $titulo_pagina = "Licença de Porte de Arma para Policial Aposentado";
        $id_tipo = 18; // ID NA TABELA TB_ALVARAS_TIPO
        break;

    case 'arma_particular':
        include "carteira.int.armaparticular.branca.php";
        $titulo_pagina = "Autorização para uso de arma particular em serviço";
        $id_tipo = 19; // ID NA TABELA TB_ALVARAS_TIPO
        break;
}

//echo "<h1>Requerimento - $titulo_pagina</h1>";


$f -> fecha_card();
$f -> fecha();



$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();


?>




?>