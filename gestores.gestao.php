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


$f -> abre_card(12);

  echo "<h2>GESTÃO DE USUÁRIOS ESPECIAIS</h2>";

if (!isset($_GET['acao'])) {

    echo "<table class='table table-striped'> <thead>";
    echo "<tr>";
    echo "<th scope='col'> Regional</th>";
    echo "<th scope='col'> Usuário Autorizado</th>";
    echo "<th scope='col'> &nbsp;</th>";
    echo "</tr>";
    echo "</thead><tbody>";
 
$query = "select * from tb_drps where id_ref != 0 order by id_ref asc";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for($i=0;$i<$num;$i++) 
    {
        $row = mysqli_fetch_array($result);
        $nome = $row[nome];
        $id_ref = $row[id_ref];
        $nome_usuario_especial =  (stripslashes($row[nome_usuario_especial]));

        echo "<tr>";
        echo "<td>$id_ref - $nome</td>";
        echo "<td>$nome_usuario_especial &nbsp;</td>";
        echo "<td><a href = 'gestores.gestao.php?acao=form&id_ref=$id_ref' class = 'btn btn-primary'>EDITAR</a></td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
}

if ($_GET[acao] == 'form') {

    $id_ref = (int)$_GET[id_ref];

    $query = "select nome, nome_usuario_especial, cpf_usuario_especial from tb_drps where id_ref = $id_ref";
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $nome = $row[nome];
    $nome_usuario_especial = (stripslashes($row[nome_usuario_especial]));
    $cpf_usuario_especial = (stripslashes($row[cpf_usuario_especial]));

    echo "<h4>Regional: $id_ref -  $nome</h4>";
    
    $f -> abre_form("gestores.gestao.php?acao=int&id_ref=$id_ref");
    $f -> f_input("Nome do usuário", "nome_usuario_especial", $nome_usuario_especial);
    $f -> f_input("CPF (apenas os números)", "cpf_usuario_especial", $cpf_usuario_especial);

    $f->f_button("Salvar");
    echo "</form>";
}

if ($_GET[acao] == 'int') {

    $id_ref = (int)$_GET[id_ref];
    $nome_usuario_especial  = $f->limpa_variavel($_POST['nome_usuario_especial'], 90, $purifier);
    $cpf  = $f->limpa_variavel($_POST['cpf_usuario_especial'], 90, $purifier);

    $cpf = str_replace(".", "", $cpf);
    $cpf = str_replace("-", "", $cpf);
    $cpf = substr($cpf, 0, 11);

    $query = "update tb_drps set nome_usuario_especial = '$nome_usuario_especial', cpf_usuario_especial = '$cpf' where id_ref = $id_ref";
    $result = mysqli_query($link, $query);
    if(!$result)
    {   
        echo " <div class='alert alert-danger' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL REALIZAR O CADASTRO<br>$query<br> </h4>".mysqli_error($link);
        echo "</div>";
    }else{
        echo " <div class='alert alert-info' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>CADASTRO REALIZADO COM SUCESSO</h4>";

        echo "<br><br>";
        echo "<a href = 'gestores.gestao.php' class = 'btn btn-primary' style = 'width: 100%;'>VOLTAR PARA LISTA DE REGIONAIS</a>";
        echo "</div>";

    }



}

$f -> fecha_card();


$f -> fecha();


$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();


?>
