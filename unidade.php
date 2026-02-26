<?php

session_start();

if(!isset($_SESSION["usuario_fis_cpf"]))
{
  header('Location: https://integra.pc.sc.gov.br/');
  exit();
}

include "mysql.conecta.rep.php";

include "classes/class.html.php";
include "classes/classe.forms.php";


require_once 'htmlpurifier/HTMLPurifier.standalone.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);


$header=new Header_adm_WEB(); 
$f = new Forms();
$a=new Menu_adm($link);
$a=new Abre_titulo();
$a->titulo_pagina('');


if(!isset($_GET['atualizar_unidade_policial'])){

    $query = "select * from tb_unidades_policiais where cpf_policial = '".$_SESSION['usuario_fis_cpf']."'";
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $unidade_numero = $row[unidade_numero];
    $unidade_nome = (stripslashes($row[unidade_nome]));
    $unidade_endereco= (stripslashes($row[unidade_endereco]));

    echo "<h2>Dados da sua Unidade Policial</h2><hr><br>";
        $f -> abre_form("unidade.php?atualizar_unidade_policial=1");

        echo "<div class='form-group'>";
        echo "<label for='inputText3' class='col-form-label'>Número da sua Unidade Policial</label><br>";
        echo "<select class='form-control' id='unidade_numero' required='' name='unidade_numero'>";

        for($i=1;$i<1000;$i++) 
                {
                    $unid = sprintf('%03d', $i);
                    echo "<option value='".$i."'";
                    if ($unidade_numero == $i) {
                        echo " selected";
                    }
                    echo ">".$unid."</option>";         
                }
        echo "</select></div>";



        $f -> f_input("Nome da Unidade Policial (exemplo: Blumenau - Divisão de Investigação Criminal - DIC)", "unidade_nome", "$unidade_nome");
        $f -> f_input("Endereço da Unidade Policial", "unidade_endereco", "$unidade_endereco");
        $f->f_button("Salvar");

        echo "</form>";
}else{

    // RECEBE AS VARIÁVEIS E FAZ O UPDATE

    $unidade_numero = (int)$_POST['unidade_numero'];
    $unidade_nome  = $f->limpa_variavel($_POST['unidade_nome'], 160, $purifier);
    $unidade_endereco  = $f->limpa_variavel($_POST['unidade_endereco'], 240, $purifier);
    $query = "UPDATE `tb_unidades_policiais` SET `unidade_numero`= $unidade_numero,`unidade_nome`='$unidade_nome',`unidade_endereco`='$unidade_endereco'  WHERE `cpf_policial` = '".$_SESSION['usuario_fis_cpf']."'";
    $result = mysqli_query($link, $query);
    if(!$result)
    {   
        echo " <div class='alert alert-danger' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL ATUALIZAR O CADASTRO<br>$query<br> </h4>".mysqli_error($link);
        echo "</div>";
    }else{
        echo " <div class='alert alert-info' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>CADASTRO ATUALIZADO COM SUCESSO</h4>";
        
        echo "</div>";

        
    }


}



$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();
?>