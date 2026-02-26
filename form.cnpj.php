<?php


session_start();

if(!isset($_SESSION["id_usuario_des"]))
{
  //header('Location: login.php');
  //exit();
}

include "classes/class.html.php";
include "classes/classe.forms.php";
include "mysql.conecta.rep.php";

$header=new Header_adm_WEB(); 

$f = new Forms();

$a=new Menu_adm($link);

$nome_pagina= "Bem-Vindo";
$a=new Abre_titulo();
$a->titulo_pagina($nome_pagina);

$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

$f -> abre(12);

$f -> abre(8);
    $f -> abre_card(12);
    echo "<h2>Busca de CNPJ</h2><hr><br>";
    $f -> abre_form("int.cnpj.php");
    $f -> f_input_mask("CNPJ", "cnpj", "cnpj");

    $f->f_button("buscar");

    echo "</form>";

    $f -> fecha_card();

    $f -> fecha();

    $f -> abre(4);
    $f -> fecha();

$f -> fecha();


$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();



?>