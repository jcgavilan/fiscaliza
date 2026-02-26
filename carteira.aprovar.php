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

$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);



echo "<h1>Painel de Homologaçãol de Carteiras</h1>";

$f -> abre_card(12);
$f->abre(12); 


$id = (int)$_GET['id'];
$data_expedicao = (int)$_GET['data_expedicao'];



$carteira_aprova_rejeita = $_POST['carteira_aprova_rejeita'];

if ($carteira_aprova_rejeita == 1) {
    $query = "update tb_carteiras set delegado_aprov = 1, delegado_aprov_data = ".time().", delegado_aprov_nome = '".$_SESSION['usuario_fis_nome']."', delegado_aprov_cpf = '".$_SESSION['usuario_fis_cpf']."' where id = ".$id." and data_expedicao = $data_expedicao";
    $msg_extra = '<BR>PEDIDO DE CARTEIRA APROVADO';
}

if ($carteira_aprova_rejeita == 0) {
    $delegado_rejeicao_justificativa  = $f->limpa_variavel($_POST['delegado_rejeicao_justificativa'], 10000, $purifier);
    $query = "update tb_carteiras set delegado_aprov = 2, delegado_aprov_data = ".time().", delegado_aprov_nome = '".$_SESSION['usuario_fis_nome']."', delegado_aprov_cpf = '".$_SESSION['usuario_fis_cpf']."', delegado_rejeicao_justificativa = '$delegado_rejeicao_justificativa' where id = ".$id." and data_expedicao = $data_expedicao";
    $msg_extra = '<BR>PEDIDO DE CARTEIRA REJEITADO';
}

$result = mysqli_query($link, $query);
if(!$result)
{   
    echo " <div class='alert alert-danger' role='alert'>";
    echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL CONCLUIR O CADASTRO$msg_extra<br>$query<br> </h4>".mysqli_error($link);
    echo "</div>";
    
}else{
    echo " <div class='alert alert-info' role='alert'>";
    echo " <h4 class='alert-heading' align = 'center'>CADASTRO CONCLUÍDO COM SUCESSO$msg_extra</h4>";
    echo "</div>";
// busca o arquivo da carteira, para deixar disponível para impressão imediata, ou para salvar, se for o caso.
    if ($carteira_aprova_rejeita == 1) {
        $query  = "select arquivo from tb_carteiras where id = ".$id." and data_expedicao = $data_expedicao";
        $result=mysqli_query($link, $query);
        $row = mysqli_fetch_array($result);
        $arquivo = $row[arquivo];
    
        echo "<br><br><a href = 'carteiras/$arquivo' style = 'width:100%;' class = 'btn btn-primary' target= '_blank'>LINK PARA O ARQUIVO PDF DA CARTEIRA</a>";
    }
    
}

echo "<br><br><a href = 'delegado.painel2.php' style = 'width:100%;' class = 'btn btn-primary'>VOLTAR PARA PÁGINA DE DEMANDAS DO DELEGADO</a>";




    $f -> fecha();
    $f -> fecha_card();
    
    $footer=new Footer_adm_WEB();
    $footer->Footer_adm_WEB();
    
    
    ?>