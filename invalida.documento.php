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

$f -> abre(12);

$f -> abre_card(12);

echo "<h2>Invalidação de Documentos</h2><hr>";

echo "<h4>Documento a ser invalidado</h4>";

$id = (int)$_GET[id];
$id_municipio = (int)$_GET[id_municipio];

if (!isset($_GET[concluir])) {

  $query = "select nome_estabelecimento, razao_social, data_conclusao, nome_delegado, tipo_pedido from tb_cidadao_pedidos where id = $id AND id_municipio = $id_municipio LIMIT 1";
  $result=mysqli_query($link, $query);
  $row = mysqli_fetch_array($result);
  $data_conclusao = $row[data_conclusao];
  $tipo_pedido = $row[tipo_pedido];
  $nome_estabelecimento =  (stripslashes($row[nome_estabelecimento]));
  $razao_social =  (stripslashes($row[razao_social]));
  $nome_delegado =  (stripslashes($row[nome_delegado]));

  $query = "select nome from tb_alvaras_tipo where id = $tipo_pedido";
  $result=mysqli_query($link, $query);
  $row = mysqli_fetch_array($result);
  $nome_documento =  (stripslashes($row[nome]));

  echo "<p><strong>$nome_documento</strong></p>";
  echo "<p>Nome Fantasia: $nome_estabelecimento</p>";
  echo "<p>Razão Social: $razao_social</p>";
  echo "<p>Data da Emissão: ".date("d/m/Y", $data_conclusao)."</p>";
  echo "<p>Delegado que gerou o documento: $nome_delegado</p>";
  $f -> abre_form("invalida.documento.php?concluir=1&id=$id&id_municipio=$id_municipio");
  $n_caracteres = 2000;
  $n_minimo = 30;
  $f -> f_text_contador_minimo("Justificativa da Invalidação do documento", "justificativa", '', 1, $n_caracteres, $n_minimo) ;
  echo "<br>";
  //$f -> f_area("Justificativa da Invalidação do documento", "justificativa", "");
  $f->f_button("Invalidar Documento");
    echo "</form>";

}

if (isset($_GET[concluir]) && $_GET[concluir] == 1) {

  
//  no banco de dados, este documento vai ter o status 4 (cancelado ou vencido),
// e a justificativa vai ser incorporada ao histórico, e o documento vai ser substituído por outro do mesmo nome.
// por isso, primeiro busca o nome do pdf gerado, e também o histórico, que vai receber um novo nome.

$query = "select documento_final, historico, nome_estabelecimento, razao_social, data_conclusao, tipo_pedido, cnpj from tb_cidadao_pedidos where id = $id AND id_municipio = $id_municipio LIMIT 1";
  
$result=mysqli_query($link, $query);
  $row = mysqli_fetch_array($result);
  $documento_final =  (stripslashes($row[documento_final]));
  $historico =  (stripslashes($row[historico]));
  $data_conclusao = $row[data_conclusao];
  $data_expedicao = date("d/m/Y", $data_conclusao);
  $tipo_pedido = $row[tipo_pedido];
  $nome_estabelecimento =  (stripslashes($row[nome_estabelecimento]));
  $razao_social =  (stripslashes($row[razao_social]));
  $cnpj =  (stripslashes($row[cnpj]));
  $a = $cnpj;
  $cnpj = $a[0].$a[1].".".$a[2].$a[3].$a[4].".".$a[5].$a[6].$a[7]."/".$a[8].$a[9].$a[10].$a[11]."-".$a[12].$a[13];

  $justificativa = $f->limpa_variavel($_POST['justificativa'], 3000, $purifier);

  $copia = copy("../cidadao/alvaras/$documento_final", "alvaras_vencidos/$documento_final");
            if (!$copia) {
                echo " <div class='alert alert-danger' role='alert'>";
                echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL COPIAR O ARQUIVO<br></h4>";
                echo "</div>";
            }


  include "html_base/alvara_cancelado.php";
  
  // aqui começa o ponto que faz a geração do arquivo html, que vai servir de base para gerar o pdf

  $str_base = "abcdefghijklmnopqrstuvxywz";
  $str_meio = "";
  for($i=0;$i<8;$i++)
  {
      $str_prov = str_shuffle($str_base);
      $str_meio .= substr($str_prov, 0, 1);
  }

  //echo $html;
  $nome_arquivo = $str_meio.".html";
  $myfile = fopen("html_descartavel/$nome_arquivo", "w") or die("erro na abertura do arquivo");
  
  fwrite($myfile, $html);
  fclose($myfile);

 

  $output = exec("python3 html2pdf.py html_descartavel/$nome_arquivo ../cidadao/alvaras/$documento_final 2>&1");

  if(!$output)
  {
      echo "FALHA  python3 html_descartavel/$nome_arquivo alvaras/$nome_final";
  }

  $query = "update tb_cidadao_pedidos set status = 4, historico = CONCAT(historico, 'DOCUMENTO CANCELADO. JUSTIFICATIVA: $justificativa ') where id = $id AND id_municipio = $id_municipio LIMIT 1";
  $result=mysqli_query($link, $query);
  if (!$result) {
    echo "<H1>NÃO ATUALIZOU</H1>";
  }
}







$f -> fecha_card();
$f -> fecha();



$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();


?>