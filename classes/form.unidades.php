<?php

session_start();
include "mysql.conecta.rep.php";
include "classes/class.html.php";
include "classes/class.forms.php";

$header=new Header_adm_WEB(); 

$f = new Forms();

$a=new Menu_adm();

$nome_pagina= "Gerar Documento";
$a=new Abre_titulo();
$a->titulo_pagina($nome_pagina);


//echo "<a href = 'form.geradoc.php?id_procedimento=$id_procedimento&fase=2' class = 'btn btn-primary'>BUSCAR MODELO</a> ";
echo "<h2>Cadastro de Unidades Policiais</h2>";

echo "<form  enctype = 'multipart/form-data' action='inclui.fiscalizacao.php' method='post'>";

echo "<div class='form-group'>";
echo "<label for='inputText3' class='col-form-label'><br>Selecione o Município</label><br>";
echo "<select class='form-control' id='id_municipio' required='' name='id_municipio'>";
echo "<option value=''></option>";

$query = "select id, nome from municipios order by nome asc";
$result = mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for($i=0;$i<$num;$i++)
{
    $row = mysqli_fetch_array($result);
    $id = $row[id];
    $nome = stripslashes(utf8_decode($row[nome]));
    echo "<option value='".$id."'>".$nome."</option>";
}
echo "</select></div>";

$f->f_input("Nome", "nome", "");
$f->f_input("Email", "email", "");
$f->f_input("Telefone", "telefone", "");
$f->f_input("Endereço", "endereco", "");

$f->f_button("CADASTRAR");
echo "</form>";




?>