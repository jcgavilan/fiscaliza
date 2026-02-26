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

$tipo = $_GET['tipo'];

$f -> abre(12);
$f -> abre_card(12);

echo "<h1>Cadastro Direto de Solicitação de Alvará</h1><hr><br>";
$f -> abre_form("cadastro.direto.form.php");

echo "<div class='form-group'>";

echo "<h3>Alvará Periódico</h3>";

$query = "select * from tb_alvaras_tipo where id_categoria = 1";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for($i=0;$i<$num;$i++) 
        {
            $row = mysqli_fetch_array($result);
            $id = $row[id];
            $nome =  (stripslashes($row[nome]));


            echo "<label class='custom-control custom-radio custom-control-inline'>";
            echo "<input type='radio' name='id_alvara' class='custom-control-input' value='$id'><span class='custom-control-label'>$nome</span>";
            echo "</label><br>";
              
        }

        echo "<br></br>";
echo "<h3>Produtos ou Atos Controlados</h3>";

$query = "select * from tb_alvaras_tipo  where id_categoria = 3";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for($i=0;$i<$num;$i++) 
        {
            $row = mysqli_fetch_array($result);
            $id = $row[id];
            $nome =  (stripslashes($row[nome]));

            echo "<label class='custom-control custom-radio custom-control-inline'>";
            echo "<input type='radio' name='id_alvara' class='custom-control-input' value='$id'><span class='custom-control-label'>$nome</span>";
            echo "</label><br>";
              
        }

echo "</div>";
echo "<br></br>";

$f->f_button("SALVAR E PROSSEGUIR");
echo "</form>";



$f -> fecha_card();
$f -> fecha();

$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();


?>

