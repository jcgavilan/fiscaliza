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


    $id_pedido = (int)$_GET[id_pedido];
    $data_pedido = (int)$_GET[data_pedido];

$f -> abre(12);


$f -> abre_card(12);
  echo "<h2>CANCELAMENTO DE PEDIDO DE ALVARÁ</h2>";


if (!isset($_GET['confirmar'])) {

    $f->abre(6);
  
    echo "<h3>Confirmação do cancelamento do pedido</h3>";

    $f -> abre_form("pedidos.cancelar.php?confirmar=1&id_pedido=$id_pedido&data_pedido=$data_pedido");

    $f -> f_area("justificativa do cancelamento", 'justificativa', '');

    $f->f_button("CONFIRMAR CANCELAMENTO");
    echo "</form>";
    $f->fecha();

}


if ($_GET['confirmar'] == 1) {

    // busca a parte dos comentários

    $query = "select historico from tb_cidadao_pedidos where id = $id_pedido LIMIT 1";
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $historico = (stripslashes($row[historico]));

    $justificativa  = $f->limpa_variavel($_POST['justificativa'], 5000, $purifier);

    $historico = $historico."<br><hr><br>PEDIDO CANCELADO ANTES DA EMISSÃO DO ALVARÁ EM ".date("d/m/Y H:i")." por ".$_SESSION['usuario_fis_nome']."<br>Justificativa: $justificativa";

    $query = "update tb_cidadao_pedidos set status = 4, data_conclusao = ".time().", nome_delegado = '".$_SESSION['usuario_fis_nome']."', historico = '$historico' where id = $id_pedido  AND data_pedido = $data_pedido LIMIT 1";
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

}

$f->abre(12);
    echo "<br><hr><br><a href = 'pedidos.painel2.php?id_pedido=$id_pedido' class = 'btn btn-primary' style = 'float: right;'>VOLTAR PARA A PÁGINA DO PEDIDO</a>";
$f -> fecha();

$f -> fecha_card();
$f -> fecha();


$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();

?>