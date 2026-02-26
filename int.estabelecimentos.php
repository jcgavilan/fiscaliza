<?php

$id_policial = 1;

session_start();

if(!isset($_SESSION["usuario_fis_cpf"]))
{
  header('Location: https://integra.pc.sc.gov.br/');
  exit();
}
$id_usuario_rpca = 1;
$id_orgao = 0;

include "classes/class.html.php";
include "classes/classe.forms.php";
include "mysql.conecta.rep.php";

require_once 'htmlpurifier/HTMLPurifier.standalone.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

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

// RECEBE OS DADOS E FAZ O CADASTRO DA CRIANÇA

    
if(!isset($_GET['acao'])){
    echo "<h4>Cadastro do Estabelecimento</h4><br><br>";

    $nome_estabelecimento  = $f->limpa_variavel($_POST['nome_estabelecimento'], 90, $purifier);
    $razao_social  = $f->limpa_variavel($_POST['razao_social'], 90, $purifier);
    $cnpj  = $f->limpa_variavel($_POST['cnpj'], 20, $purifier);
    $cnpj = preg_replace("/[^0-9]/", "",$cnpj);
    $id_ramo_atividade = $_POST[id_ramo_atividade];
    $endereco_rua  = $f->limpa_variavel($_POST['endereco_rua'], 90, $purifier);
    $endereco_numero  = $f->limpa_variavel($_POST['endereco_numero'], 40, $purifier);
    $endereco_bairro  = $f->limpa_variavel($_POST['endereco_bairro'], 90, $purifier);
    $endereco_cep  = $f->limpa_variavel($_POST['endereco_cep'], 12, $purifier);
    $id_municipio = $_POST[id_municipio];
    $telefone_fixo  = $f->limpa_variavel($_POST['telefone_fixo'], 90, $purifier);
    $telefone_celular  = $f->limpa_variavel($_POST['telefone_celular'], 90, $purifier);
    $email  = $f->limpa_variavel($_POST['email'], 90, $purifier);
    $nome_proprietario  = $f->limpa_variavel($_POST['nome_proprietario'], 90, $purifier);
    $idade_permitida  = $f->limpa_variavel($_POST['idade_permitida'], 90, $purifier);

    

    $query = "insert into tb_estabelecimentos (id_policial, nome_estabelecimento, razao_social, id_ramo_atividade, cnpj, endereco_rua, endereco_numero, endereco_bairro, endereco_cep, id_municipio, telefone_fixo, telefone_celular, email, nome_proprietario, idade_permitida) values ($id_policial, '$nome_estabelecimento', '$razao_social', $id_ramo_atividade, '$cnpj', '$endereco_rua', '$endereco_numero', '$endereco_bairro', '$endereco_cep', $id_municipio, '$telefone_fixo', '$telefone_celular', '$email', '$nome_proprietario', '$idade_permitida')";
    $result = mysqli_query($link, $query);
    if(!$result)
    {   
        echo " <div class='alert alert-danger' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL CONCLUIR O CADASTRO<br>$query<br> </h4>".mysqli_error($link);
        echo "</div>";
    }else{
        echo " <div class='alert alert-info' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>CADASTRO CONCLUÍDO COM SUCESSO</h4>";
        $id_estabelecimento = mysqli_insert_id($link);
        echo "</div>";
        echo "<a href = 'pagina.estabelecimento.php?id_estabelecimento=$id_estabelecimento' class = 'btn btn-primary' style = 'width: 100%; margin-top: 24px;'>IR PARA A PÁGINA DO ESTABELECIMENTO</a>";
        
    }

}



$f -> fecha_card();

$f -> fecha();

$f -> abre(4);
$f -> fecha();

$f -> fecha();


$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();


?>