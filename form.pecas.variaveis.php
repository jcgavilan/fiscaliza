<?php

session_start();
if(!isset($_SESSION["usuario_fis_cpf"]))
{
  header('Location: https://integra.pc.sc.gov.br/');
  exit();
}

include "mysql.conecta.rep.php";
include "classes/class.html.php";
include "classes/class.forms.php";

$header=new Header_adm_WEB(); 

$f = new Forms();

$a=new Menu_adm($link);

$a=new Abre_titulo();
$a->titulo_pagina($nome_pagina);

//echo "<h2>Geração de Termo Geral</h2>";
echo "<h3>Preenchimento dos Campos</h3>";


if (isset($_GET['id_procedimento'])) {
    $id_procedimento = $_GET['id_procedimento'];
}
if (isset($_GET['id_peca_ref'])) {
    $id_peca_ref = $_GET['id_peca_ref'];
}

settype($id_procedimento, 'integer');
settype($id_peca_ref, 'integer');



echo "<form  enctype = 'multipart/form-data' action='int.pecas.variaveis2.php?id_procedimento=$id_procedimento&id_peca_ref=$id_peca_ref' method='post'>";

$query = "select var_var, var_nome from tb_pecas_campo where id_peca_ref = $id_peca_ref";
    $result = mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++)
    {
        $row = mysqli_fetch_array($result);
        $var_var = stripslashes($row[var_var]);
        $var_nome = stripslashes($row[var_nome]);
        
        $f->f_input("$var_nome", "var_var[$var_var]", "");
      
    }
    $f->f_button("GERAR PEÇA");
    echo "</form>";


    echo "<br><h1><a href = 'form.geradoc.prev.php?id_procedimento=$id_procedimento' title = 'VOLTAR'><i class='icon-arrow-left-circle'></i></a></h1>";

$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();

?>