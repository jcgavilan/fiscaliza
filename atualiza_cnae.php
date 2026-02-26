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



$f -> abre(6);

$f -> abre_card(12);

    echo "<h2>Atualização</h2><hr>";

    if (isset($_GET['id_cnae'])) {

        $id_cnae = (int)$_GET['id_cnae'];
       
        $taxa_valor = $_POST['taxa_valor'];
        $taxa_valor = str_replace(",", ".", $taxa_valor);
        settype($taxa_valor, 'float');

        $query = "update tb_cnaes set taxa_valor = $taxa_valor where id = $id_cnae";
        $result = mysqli_query($link, $query);
        if(!$result)
        {   
            echo " <div class='alert alert-danger' role='alert'>";
            echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL CONCLUIR O CADASTRO<br>$query<br> </h4>".mysqli_error($link);
            echo "</div>";
        }else{
            echo " <div class='alert alert-info' role='alert'>";
            echo " <h4 class='alert-heading' align = 'center'>CADASTRO CONCLUÍDO COM SUCESSO</h4><h4  class='alert-heading' align = 'center'>Taxa atualizada para R$ $taxa_valor</h4>";
            echo "</div>";
        }

    }

$f -> fecha_card();
$f -> fecha();


$f -> abre(6);

$f -> abre_card(12);

echo "<h3>Atualização de TAXAS</h3>";

$documento_nome = array();
$query = "select * from tb_documentos_tipo";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for($i=0;$i<$num;$i++) 
    {
        $row = mysqli_fetch_array($result);
        $id = $row[id];
        $nome =  (stripslashes($row[nome]));
        $documento_nome[$id] = $nome;
    }


    echo "<h2>Dados da Empresa</h2>";

    echo "<table class='table table-striped'>";
            echo "<thead>";
            echo "<tr>";
                echo "<th scope='col' style = 'width:70%;'> CNAE</th>";
                echo "<th >TAXA</th>";
                //
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            $query = "select * from tb_cnaes";
            $result=mysqli_query($link, $query);
            $num = mysqli_num_rows($result);
            for($i=0;$i<$num;$i++) 
                {
                    $row = mysqli_fetch_array($result);
                    $id = $row[id];
                    $cnae =  (stripslashes($row[cnae]));
                    $taxa_valor = $row[taxa_valor];

                    echo "<tr><th scope='row'>$cnae</td><td><form action='atualiza_cnae.php?id_cnae=$id' method='post'><input id='taxa_valor' name='taxa_valor' value ='$taxa_valor' style='width:50px;'> &nbsp;<button type='submit' name='Submit' >ALTERAR</button></form></td></tr>";
                }


    echo "</table>";


$f -> fecha_card();
$f -> fecha();








mysqli_close($link);

?>