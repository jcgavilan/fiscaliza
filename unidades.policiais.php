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

$f -> abre(6);
$f -> abre_card(12);

    echo "<h2>Cadastro de Unidades Policiais</h2><hr><br>";

    if (!isset($_GET[acao])) {
        
        $f -> abre_form("unidades.policiais.php?acao=inserir");
        $f -> f_input("Nome da unidade policial", "nome", "");
        echo "<div class='form-group'>";
        echo "<label for='inputText3' class='col-form-label'>Município</label><br>";
        echo "<select class='form-control' id='id_municipio' required='' name='id_municipio'>";
        echo "<option value='0'></option>";
        $query = "select * from tb_municipios_nacional  where uf = 'SC' order by cidade asc";
        $result=mysqli_query($link, $query);
        $num = mysqli_num_rows($result);
        for($i=0;$i<$num;$i++) 
                {
                    $row = mysqli_fetch_array($result);
                    $id = $row[id];
                    $cidade =  (stripslashes($row[cidade]));
                    echo "<option value='".$id."'";
                    echo ">".$cidade."</option>";         
                }
        echo "</select></div>";
        $f->f_button("Salvar");

    echo "</form>";
    }

    if ($_GET['acao'] == 'inserir') {

        $id_municipio = $_POST[id_municipio];
        settype($id_municipio, 'integer');
        $nome  = $f->limpa_variavel($_POST['nome'], 160, $purifier);
        $query = "insert into tb_unidades_policiais (id_municipio, nome) values ($id_municipio, '$nome')";
        $result = mysqli_query($link, $query);
        if(!$result)
        {   
            echo " <div class='alert alert-danger' role='alert'>";
            echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL CONCLUIR O CADASTRO<br>$query<br> </h4>".mysqli_error($link);
            echo "</div>";
        }else{
            echo " <div class='alert alert-info' role='alert'>";
            echo " <h4 class='alert-heading' align = 'center'>CADASTRO CONCLUÍDO COM SUCESSO</h4>";
            echo "</div>";            
        }


    }



$f -> fecha_card();
$f -> fecha();

$f -> abre(6);
$f -> abre_card(12);
echo "<h2>Unidades cadastradas</h2><hr><br>";

$query = "select * from  tb_unidades_policiais  order by nome asc";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for($i=0;$i<$num;$i++) 
        {
            $row = mysqli_fetch_array($result);
            $id = $row[id];
            $nome =  (stripslashes($row[nome]));

            echo "<h4>$nome</h4><hr>";
      
        }
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