
<?php

session_start();

if(!isset($_SESSION["usuario_fis_cpf"]))
{
  header('Location: https://integra.pc.sc.gov.br/');
  exit();
}

$id_policial = 1;

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

$f -> abre(12);

echo "<div style = 'display: block; width: 100%; text-align:right; padding-right: 30px; '>";

//echo "<a href = '#' class = 'btn btn-primary'><i class='fa-solid fa-video'></i> VÍDEO DE ORIENTAÇÃO PARA ESTA PÁGINA</a>";

?>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style = 'float: right; '>
VÍDEO DE ORIENTAÇÃO PARA ESTA PÁGINA
</button><br>
</h2>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style = 'width: 100%' >
  <div class="modal-dialog" role="document" style = 'width: 100%'>
    <div class="modal-content" style = 'width: 100%'>
      <div class="modal-header" style = 'width: 100%'>
        <h3 class="modal-title" id="exampleModalLabel"></h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style = "width: 100%; height: 400px; background-color: #FFFFFF; overflow-y: scroll;padding-top:8px;">
      <iframe width="440" height="320" src="https://www.youtube.com/embed/bH0kdLN5w-U" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">FECHAR</button>

      </div>
    </div>
  </div>
</div>
<br>
<?php
  echo "</div>";
  echo "<br>";
  $f->fecha();

  $f -> abre(12);
  $f -> abre(6);
  $f -> abre_card(12);

  echo "<h2>Novas solicitações do Cidadão</h2><hr><br>";

  // VERIFICA DADOS O USUÁRIO EMULADO

  // if ( isset($_SESSION['usuario_emulado']) || $_SESSION['usuario_emulado'] = 1) {

  //   echo "<h3>USUÁRIO EMULADO</h3>";
  //   echo  "Nome: ".$_SESSION['usuario_fis_nome']."<br>";
  //   echo  "CPF: ".$_SESSION['usuario_fis_cpf']."<br>"; 
  //   echo  "IBGE: ".$_SESSION['usuario_fis_ibge']."<br>"; 
    
  // }

  $nome_documento = array();
  $query = "select id, nome from tb_alvaras_tipo";
  $result = mysqli_query($link, $query);
  $num = mysqli_num_rows($result);
  for($i=0;$i<$num;$i++)
  {
    $row = mysqli_fetch_array($result);
    $id = $row[id];
    $nome = stripslashes($row[nome]);
    $nome_documento[$id] = $nome;
  }

  echo "<table class='table'>";
  echo "<thead>";
  echo "<tr>";
  echo "<th scope='col'>Estabelecimento</th>";
  //echo "<th scope='col'>CNPJ</th>";
  echo "<th scope='col'>Data do pedido</th>";
  echo "<th scope='col'>Alvará/Cidade</th>";
  echo "<th scope='col'> </th>";
  echo "</tr>";
  echo "</thead>";
  echo "<tbody>";

  $nome_municipio  = array();
  $query = "select nome, ibge_reduzido from tb_municipios_ibge";
  $result=mysqli_query($link, $query);
  $num = mysqli_num_rows($result);
  for($i=0;$i<$num;$i++) 
  {
    $row = mysqli_fetch_array($result);
    $ibge_reduzido = $row[ibge_reduzido];
    $nome = stripslashes($row[nome]);
    $nome_municipio[$ibge_reduzido] = $nome;
  }

  $query = "select id, nome_estabelecimento, cnpj, data_pedido, tipo_pedido, id_municipio from tb_cidadao_pedidos where status = 0 and id_policial = '' and id_municipio in (".$_SESSION['usuario_fis_ibge'].")"; // ainda não foram recebidos.
  
  if ($_SESSION["usuario_fis_cpf"] == '08151009810') {
    echo $query;
    die;
  }
  
  $result=mysqli_query($link, $query);
  $num = mysqli_num_rows($result);
  for($i=0;$i<$num;$i++) 
  {
    $a =  str_shuffle($base);
    $str = substr($a, 0, 40);
    $row = mysqli_fetch_array($result);
    $id_pedido = $row[id];
    $data_pedido = $row[data_pedido];
    $tipo_pedido = $row[tipo_pedido];
    $nome_estabelecimento= (stripslashes($row[nome_estabelecimento]));
    $cnpj= (stripslashes($row[cnpj]));
    $id_municipio= (stripslashes($row[id_municipio]));

    echo "<tr>";
    echo "<th scope='row'>$nome_estabelecimento <span style = 'color: #888888;'>[$id_pedido]</span></th>";
    // echo "<td>$cnpj</td>";
    echo "<td>".date("d/m/y", $data_pedido)."</td>";
    echo "<td>".$nome_documento[$tipo_pedido]." / ".$nome_municipio[$id_municipio]."<br></td>";
    echo "<th scope='row'><a HREF = 'pedidos.painel2.php?id_pedido=$id_pedido' class = 'btn btn-primary'>RECEBER PROCESSO</a></th>";

    echo "</tr>";

  }

  echo "</tbody>";
  echo "</table>";
   


  $f -> fecha_card();
  $f -> fecha();

  $f -> abre(6);
  $f -> abre_card(12);
  echo "<h2>Solicitações em Andamento</h2><hr><br>";

  echo "<table class='table'>";
  echo "<thead>";
  echo "<tr>";
  echo "<th scope='col'>Estabelecimento</th>";
  //echo "<th scope='col'>CNPJ</th>";
  echo "<th scope='col'>Retornado em:</th>";
  echo "<th scope='col'>Alvará/Cidade</th>";
  echo "<th scope='col'> </th>";
  echo "</tr>";
  echo "</thead>";
  echo "<tbody>";

$query = "select id, nome_estabelecimento, cnpj, ultima_movimentacao, tipo_pedido, id_municipio from tb_cidadao_pedidos where status = 0 and id_policial != '' and id_municipio in (".$_SESSION['usuario_fis_ibge'].")"; ";//and id_policial = '".$_SESSION['usuario_fis_cpf']."'"; // SE SÓ BUSCA DO POLICIAL A BUSCA PELO NUNICIPIO FICA REDUNDANTE and id_municipio in (".$_SESSION['usuario_fis_ibge'].")"; // já foram analisados, e o cidadão complementou os arquivos.
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for($i=0;$i<$num;$i++) 
{
    $a =  str_shuffle($base);
    $str = substr($a, 0, 40);
    $row = mysqli_fetch_array($result);
    $id_pedido = $row[id];
    $data_pedido = $row[ultima_movimentacao];
    $tipo_pedido = $row[tipo_pedido];
    $nome_estabelecimento= (stripslashes($row[nome_estabelecimento]));
    $cnpj= (stripslashes($row[cnpj]));
    $id_municipio= (stripslashes($row[id_municipio]));

    echo "<tr>";
    echo "<th scope='row'>$nome_estabelecimento <span style = 'color: #888888;'>[$id_pedido]</span></th>";
    // echo "<td>$cnpj</td>";
    echo "<td>".date("d/m/y", $data_pedido)."</td>";
    echo "<td>".$nome_documento[$tipo_pedido]." / <br>".$nome_municipio[$id_municipio]."<br></td>";
    echo "<th scope='row'><a HREF = 'pedidos.painel2.php?id_pedido=$id_pedido' class = 'btn btn-primary'>PROCEDER ANÁLISE</a></th>";

    echo "</tr>";

}

echo "</tbody>";
echo "</table>";


$f -> fecha_card();
$f -> fecha();

$f -> fecha();


$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();


?>

<!-- CREATE TABLE tb_unidades_policiais (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
id_municipio int not null default 0,
nome VARCHAR(160) NOT NULL default ''
) -->

<!-- CREATE TABLE tb_policiais (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
id_unidade int not null default 0,
nome VARCHAR(180) NOT NULL default '',
cpf VARCHAR(11) NOT NULL default '',
senha VARCHAR(250) NOT NULL default ''
) -->

<!-- CREATE TABLE tb_municipios_ibge (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
nome VARCHAR(160) NOT NULL default '',
ibge VARCHAR(7) NOT NULL default '',
)  -->