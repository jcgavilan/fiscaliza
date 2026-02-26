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

$f -> abre(12);

$f -> abre(8);
    $f -> abre_card(12);
    echo "<h2>Geração de Alvará - Dados adicionais</h2><hr><br>";

    $id_alvara = $_GET[id_alvara];
    settype($id_alvara, 'integer');

    $id_estabelecimento = $_GET[id_estabelecimento];
    settype($id_alvara, 'integer');

    //busca o nome do alvará;

    $query = "select nome from tb_alvaras_tipo where id = ".$id_alvara;
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $nome_alvara =  (stripslashes($row[nome]));

    $query = "select nome_estabelecimento from tb_estabelecimentos where id = ".$id_estabelecimento;
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $nome_estabelecimento =  (stripslashes($row[nome_estabelecimento]));

    $now = time();
    $data_expedicao = date("d/m/Y", $now);

    $f -> abre_form("gera.alvara.php?id_estabelecimento=$id_estabelecimento&id_alvara=$id_alvara");
    $f -> f_input("Código da Unidade Policial", "cod_unid_policial", "");
    $f -> f_input("Taxa Estadual", "taxa_estadual", "");
    $f -> f_input("Data de Expedição", "data_expedicao", "$data_expedicao");
    $f -> f_input_mask("Data de Validade do Alvará", "data_validade", "data");

    if ($id_alvara == 2) {
      $f -> f_input("Empresa de Segurança", "empresa_seguranca", "");
    }
    $f->f_button("GERAR ALVARÁ");

    echo "</form>";


    $f -> fecha_card();
        
    $f -> fecha();
    
    $f -> abre(4);


    $f -> fecha();
    
    $f -> fecha();
    
            
    $footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();




?>