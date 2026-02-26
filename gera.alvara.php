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

require_once 'htmlpurifier/HTMLPurifier.standalone.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$id_estabelecimento = $_GET[id_estabelecimento];
settype($id_estabelecimento, 'integer');

$query = "select * from tb_estabelecimentos where id = $id_estabelecimento";
$result=mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
$id = $row[id];
$nome_estabelecimento =  (stripslashes($row[nome_estabelecimento]));
$razao_social =  (stripslashes($row[razao_social]));
$id_ramo_atividade =  (stripslashes($row[id_ramo_atividade]));
$cnpj =  (stripslashes($row[cnpj]));
$endereco_rua =  (stripslashes($row[endereco_rua]));
$endereco_numero =  (stripslashes($row[endereco_numero]));
$endereco_bairro =  (stripslashes($row[endereco_bairro]));
$endereco_cep =  (stripslashes($row[endereco_cep]));
$id_municipio =  (stripslashes($row[id_municipio]));
$telefone_fixo =  (stripslashes($row[telefone_fixo]));
$telefone_celular =  (stripslashes($row[telefone_celular]));
$email =  (stripslashes($row[email]));
$nome_proprietario =  (stripslashes($row[nome_proprietario]));
$idade_permitida =  (stripslashes($row[idade_permitida]));  

//busca o nome da atividade
$query2 = "select * from tb_ramos_atividade where id = ".$id_ramo_atividade;
$result2=mysqli_query($link, $query2);    
$row2 = mysqli_fetch_array($result2);
$nome_atividade =  (stripslashes($row2[nome]));


$query2 = "select * from tb_municipios_nacional where id = ".$id_municipio;
$result2=mysqli_query($link, $query2);    
$row2 = mysqli_fetch_array($result2);
$nome_municipio =  (stripslashes($row2[cidade]));

//  RECEBE AS VARIÁVEIS PREENCHIDAS MANUALMENTE NA PÁGINA ANTERIOR

// $cod_unid_policial  = $f->limpa_variavel($_POST['cod_unid_policial'], 90, $purifier);
// $taxa_estadual  = $f->limpa_variavel($_POST['taxa_estadual'], 90, $purifier);
// $data_expedicao  = $f->limpa_variavel($_POST['data_expedicao'], 90, $purifier);
// $data_validade  = $f->limpa_variavel($_POST['data_validade'], 90, $purifier);

$cod_unid_policial  = $_POST['cod_unid_policial'];
$taxa_estadual = $_POST['taxa_estadual'];
$data_expedicao = $_POST['data_expedicao'];
$data_validade = $_POST['data_validade'];

$empresa_seguranca = $_POST['empresa_seguranca'];

$header=new Header_adm_WEB(); 

$f = new Forms();

$a=new Menu_adm($link);

$nome_pagina= "Bem-Vindo";
$a=new Abre_titulo();
$a->titulo_pagina($nome_pagina);

$f -> abre(12);

$f -> abre(8);
    $f -> abre_card(12);

    echo "<h2>Geração de Alvarás</h2><hr><br>";

    // busca o nome do arquivo para incluir, e gerar a variável $html
    $id_alvara = $_GET[id_alvara];
    settype($id_alvara, 'integer');

    $query = "select html_base, nome from tb_alvaras_tipo where id = ".$id_alvara;
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $html_base =  (stripslashes($row[html_base]));
    $nome_alvara =  (stripslashes($row[nome]));

    include "html_base/".$html_base;
    
    // aqui começa o ponto que faz a geração do arquivo html, que vai servir de base para gerar o pdf

    $str_base = "abcdefghijklmnopqrstuvxywz";
    $str_meio = "";
    for($i=0;$i<4;$i++)
    {
        $str_prov = str_shuffle($str_base);
        $str_meio .= substr($str_prov, 0, 1);
    }

    $data_hora=time();

    $prov = $nome_alvara."_".$nome_estabelecimento;
    $prov = preg_replace('/[^a-z0-9_ ]/i', '', $prov);
    $prov = str_replace(" ", "_", $prov);
    $prov = substr($prov, 0, 90)."-".$data_hora.$str_meio;


    
    $nome_arquivo = $str_meio.".html";
    $myfile = fopen("html_descartavel/$nome_arquivo", "w") or die("erro na abertura do arquivo");
    
    fwrite($myfile, $html);
    fclose($myfile);

    $nome_final = $prov.".pdf";

    $output = exec("python3 html2pdf.py html_descartavel/$nome_arquivo alvaras/$nome_final 2>&1");

    if(!$output)
    {
        echo "FALHA  python3 html_descartavel/$nome_arquivo alvaras/$nome_final";
    }

    // FAZ A INCLUSÃO DA TABELA DE ALVARÁS
  
    $query = "insert into tb_alvaras_gerados (id_estabelecimento, id_alvara, arquivo_pdf, data_registro) values ($id_estabelecimento, $id_alvara, '$nome_final', $data_hora)";
    $result = mysqli_query($link, $query);
    if(!$result)
    {   
        echo " <div class='alert alert-danger' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL CONCLUIR O CADASTRO<br>$query<br> </h4>".mysqli_error($link);
        echo "</div>";
    }

    echo "<div style = 'width: 100%; display: table; text-align: right; padding-bottom: 20px;'>";
    echo "<a href = 'pagina.estabelecimento.php?id_estabelecimento=$id_estabelecimento'  class = 'btn btn-primary'> VOLTAR PARA O ESTABELECIMENTO</a>";
    echo "  &nbsp;&nbsp;<a href = 'alvaras/$nome_final' class = 'btn btn-primary' style = 'float: right;' download> BAIXAR DOCUMENTO</a><br>";
    echo "</div>";

    echo "<object data='alvaras/$nome_final' type='application/pdf' style = 'width:100%; height:680px;'><embed src='alvaras/$nome_final' type='application/pdf' /></object>";

    unlink("html_descartavel/$nome_arquivo");

    $f -> fecha_card();
               
    $f -> fecha();
    
    $f -> abre(4);

    $f -> fecha();
    
    $f -> fecha();
    
    
$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();
/**/
?>