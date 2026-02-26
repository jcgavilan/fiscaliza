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

require_once 'htmlpurifier/HTMLPurifier.standalone.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$nome_pagina= "Bem-Vindo";
$a=new Abre_titulo();
$a->titulo_pagina($nome_pagina);

$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

$f -> abre(6);

$f -> abre_card(12);

$id_pedido = (int)$_GET[id_pedido];

$query = "select * from tb_cidadao_pedidos where id = ".$id_pedido;
  $result=mysqli_query($link, $query);

  $a =  str_shuffle($base);
  $str = substr($a, 0, 40);
  $row = mysqli_fetch_array($result);
  $id_pedido = $row[id];
  $data_pedido = $row[data_pedido];
  $tipo_pedido = $row[tipo_pedido];
  $cnpj= (stripslashes($row[cnpj]));
  $nome_estabelecimento= (stripslashes($row[nome_estabelecimento]));
  $razao_social= (stripslashes($row[razao_social]));
  $id_ramo_atividade= (stripslashes($row[id_ramo_atividade]));
  $endereco_rua= (stripslashes($row[endereco_rua]));
  $endereco_numero= (stripslashes($row[endereco_numero]));
  $endereco_bairro= (stripslashes($row[endereco_bairro]));
  $endereco_cep= (stripslashes($row[endereco_cep]));
  $id_municipio= (stripslashes($row[id_municipio]));
  $telefone_fixo= (stripslashes($row[telefone_fixo]));
  $telefone_celular= (stripslashes($row[telefone_celular]));
  $email= (stripslashes($row[email]));
  $nome_proprietario= (stripslashes($row[nome_proprietario]));
  $arquivos= (stripslashes($row[arquivos]));
  $documento_final = stripslashes($row[documento_final]);
  $obs_cidadao = stripslashes($row[obs_cidadao]);
  $historico = stripslashes($row[historico]);

  $query = "select nome from tb_alvaras_tipo where id = ".$tipo_pedido;
  $result=mysqli_query($link, $query);
  $row = mysqli_fetch_array($result);
  $nome_alvara = (stripslashes($row[nome]));

  $query2 = "select nome from tb_municipios_ibge where ibge_reduzido = ".$id_municipio;
  $result2 = mysqli_query($link, $query2);
  $row2 = mysqli_fetch_array($result2);
  $nome_municipio = (stripslashes($row2[nome])); 


echo "<h2>Dados da Empresa</h2>";

echo "<table class='table table-striped'>";
        echo "<thead>";
        echo "<tr>";
            echo "<th scope='col'></th>";
            echo "<th scope='col'></th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";


        echo "<tr><th scope='row'>Estabelecimento </td><td>$nome_estabelecimento </td></tr>";
        echo "<tr><th scope='row'>Razão Social </td><td>$razao_social </td></tr>";
        echo "<tr><th scope='row'>CNPJ </td><td>$cnpj </td></tr>";
        echo "<tr><th scope='row'>Ramo de Atividade</td><td>$id_ramo_atividade </td></tr>";
        echo "<tr><th scope='row'>Alvará solicitado: </td><td>$nome_alvara </td></tr>";
        echo "<tr><th scope='row'>Endereço: </td><td>$endereco_rua. $endereco_numero - $endereco_bairro  </td></tr>";
        echo "<tr><th scope='row'>CEP </td><td>$endereco_cep</td></tr>";
        echo "<tr><th scope='row'>Municipio</td><td>$nome_municipio</td></tr>";
        echo "<tr><th scope='row'>Proprietário</td><td>$nome_proprietario </td></tr>";
        echo "<tr><th scope='row'>Email</td><td>$email </td></tr>";
        echo "<tr><th scope='row'>Telefone</td><td>$telefone_fixo </td></tr>";
        echo "<tr><th scope='row'>Celular</td><td>$telefone_celular </td></tr>";
        echo "<tr><th scope='row'>Observações</td><td>$obs_cidadao </td></tr>";
        // echo "<tr><th scope='row'> </td><td> </td></tr>";
        // echo "<tr><th scope='row'> </td><td> </td></tr>";
        // echo "<tr><th scope='row'> </td><td> </td></tr>";
        echo "</table>";


$f -> fecha_card();
$f -> fecha();


$f -> abre(6);

$f -> abre_card(12);

echo "<h2>Histórico</h2><hr>";

echo "<br>".$historico;

$f -> fecha_card();
$f -> fecha();

$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();


?>
