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

$nome_pagina= "Bem-Vindo";
$a=new Abre_titulo();
$a->titulo_pagina($nome_pagina);

$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

$f -> abre(12);

$f -> abre(8);
    $f -> abre_card(12);
    echo "<div style = 'float:right;'><a href = 'form.cnpj.php'><i class='icon-magnifier'></i></a></div>";
    echo "<h2>Cadastro de Estabelecimento</h2><hr><br>";
    $f -> abre_form("int.estabelecimentos.php");
    $f -> f_input("Nome Fantasia", "nome_estabelecimento", "");
    $f -> f_input("Razão Social", "razao_social", "");
    $f -> f_input_mask("CNPJ", "cnpj", "cnpj");

    echo "<div class='form-group'>";
    echo "<label for='inputText3' class='col-form-label'>Ramos de Atividade</label><br>";
    echo "<select class='form-control' id='id_ramo_atividade' required='' name='id_ramo_atividade'>";
    echo "<option value='0'></option>";
    $query = "select * from tb_ramos_atividade order by nome asc";
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++) // foi incluindo a contagem até 8 para ir somente até o superior/cursando no caso de criança ou adolescente
            {
                $row = mysqli_fetch_array($result);
                $id = $row[id];
                $nome =  (stripslashes($row[nome]));
                echo "<option value='".$id."'";
                echo ">".$nome."</option>";         
            }
    echo "</select></div>";

    //echo "<div style = 'display: table; width: 100%; border: 1px solid #cccccc; padding: 16px;'>";
        $f -> f_input("Rua", "endereco_rua", "");
        $f -> f_input("Número", "endereco_numero", "");
        $f -> f_input("Bairro", "endereco_bairro", "");
        $f -> f_input("CEP:", "endereco_cep", "");

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
    //echo "</div>";

    $f -> f_input("Telefone fixo", "telefone_fixo", "");
    $f -> f_input("Telefone Celular", "telefone_celular", "");
    $f -> f_input("Nome do Proprietário", "nome_proprietario", "");
    $f -> f_input("Email", "email", "");
    $f -> f_input("Idade Permitida", "idade_permitida", "");


    $f->f_button("Salvar");

    echo "</form>";

    $f -> fecha_card();

    $f -> fecha();

    $f -> abre(4);
    $f -> fecha();

$f -> fecha();


$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();



?>