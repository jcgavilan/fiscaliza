<?php

// A FUNÇÃO DESTA PÁGINA É CANCELAR O ALVARÁ JÁ GERADO, RETORNAR O STATUS PARA 'HOMOLOGAÇÃO DO DELEGADO'

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


    $id_pedido = (int)$_GET[id_pedido];
    $data_pedido = (int)$_GET[data_pedido];

$f -> abre(12);


$f -> abre_card(12);
  echo "<h2>CANCELAMENTO DE ALVARÁ JÁ EMITIDO.</h2>";


if (!isset($_GET['confirmar'])) {

    $f->abre(6);
  
    echo "<h3>Confirmação de Cancelamento de Alvará</h3>";

    $f -> abre_form("pedidos.revogar.php?confirmar=1&id_pedido=$id_pedido&data_pedido=$data_pedido");

    $f -> f_area("Fundamentação do cancelamento", 'justificativa', '');

    $f->f_button("CONFIRMAR CANCELAMENTO");
    echo "</form>";
    $f->fecha();

    
    $f->abre(12);
        echo "<br><hr><br><a href='javascript:history.back()' class = 'btn btn-primary' style = 'float: right;'>VOLTAR</a>";
    $f -> fecha();

}


if ($_GET['confirmar'] == 1) {

    // busca a parte dos comentários

    $query = "select historico, documento_final from tb_cidadao_pedidos where id = $id_pedido LIMIT 1";
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $historico = (stripslashes($row[historico]));
    $documento_final = (stripslashes($row[documento_final]));
    $justificativa  = $f->limpa_variavel($_POST['justificativa'], 5000, $purifier);

    $historico = $historico."<br><hr><br>ALVARÁ CANCELADO APÓS SUA EMISSÃO EM ".date("d/m/Y H:i")." por ".$_SESSION['usuario_fis_nome']."<br>Justificativa: $justificativa";

   // $query = "update tb_cidadao_pedidos set status = 4, data_conclusao = ".time().", nome_delegado = '".$_SESSION['usuario_fis_nome']."', historico = '$historico' where id = $id_pedido  AND data_pedido = $data_pedido LIMIT 1";
    $query = "update tb_cidadao_pedidos set status = 2, historico = '$historico', ultima_movimentacao = ".time().", concluido = 0, data_conclusao = 0, documento_final = '', nome_delegado = '', data_validade = 0, hash_alvara_pdf = '' where id = ".$id_pedido." AND data_pedido = $data_pedido";
    $result = mysqli_query($link, $query);
    if(!$result)
    {   
        echo " <div class='alert alert-danger' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL CANCELAR O PEDIDO<br>$query<br> </h4>".mysqli_error($link);
        echo "</div>";
    }else{
        echo " <div class='alert alert-info' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>PEDIDO CANCELADO COM SUCESSO</h4>";
        echo "</div>";  
    }

    // FAZ A INVALIDAÇÃO DO ARQUIVO ORIGINAL, PARA O CASO DE ALGUÉM USAR O QRCODE


    include "html_base/alvara_cancelado.php";

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

  $f->abre(12);
    echo "<br><hr><br><a href = 'pedidos.painel2.php?id_pedido=$id_pedido' class = 'btn btn-primary' style = 'float: right;'>VOLTAR PARA A PÁGINA DO PEDIDO</a>";
$f -> fecha();
}



$f -> fecha_card();
$f -> fecha();


$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();

?>