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

$nome_pagina= "Termo Geral";
$a=new Abre_titulo();
$a->titulo_pagina($nome_pagina);

echo "<h2>Cadastro de Modelos de Peças</h2>";

if($_GET['acao'] == "prev"){


    echo "<a HREF = 'form.pecas.cadastro.php?acao=inserir_form' class='btn btn-primary'>NOVO DOCUMENTO</a>";
    echo "<a HREF = 'form.pecas.cadastro.php?acao=listar_pecas' class='btn btn-primary' style = 'margin-left: 20px;'>VER LISTA DE DOCUMENTOS</a>";

}


if($_GET['acao'] == "inserir_form"){

   
    echo "<div class='col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12' style = 'width:100%;'>";

        echo "<div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6' style = 'width:100%; float: left;'>";

        // apresenta formulário.
        echo "<form  enctype = 'multipart/form-data' action='grava.pecas.cadastro.php?acao=inserir' method='post'>";

        $f->f_input("Nome do Documento", "nome", "");
        $f->f_text("Texto Inicial", "texto_inicial", "");
        //$f->f_text("Texto Editável", "texto_editavel", "");
        echo "<div class='form-group'>";
        echo "<label for='exampleFormControlTextarea1'>Texto Editável</label>";
        echo "<textarea class='form-control' id='texto_editavel'  name='texto_editavel' rows='10'></textarea>";
        echo "</div>";
        $f->f_text("Texto Final", "texto_final", "");
        $f->f_button("CADASTRAR");

        echo "</div>";

        $a="A-B-C-D-E-F-G-H-J-K-L-M-N-P-Q-R-S-T-U-V-X-Y-W-Z";
        $b= explode("-", $a);


        echo "<div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6' style = 'width:100%; float: left; height: 650px; overflow-y: scroll'>";
            
            echo "<div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6' style = 'width:100%; float: left;'>";
            for ($i=0; $i < 24; $i++) { 
                $f->f_input("Nome do campo ", "var_nome[$i]", "");
            }
               
            echo "</div>";

            echo "<div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6' style = 'width:100%; float: left;'>";
               
            for ($i=0; $i < 24; $i++) { 
                $var_prov = $b[$i].$b[$i].$b[$i].$b[$i].$b[$i];
                $f->f_input("Nome da Variável", "var_var[$i]", $var_prov);
            }
 
            echo "</div>";

        echo "</div>";


 
   echo "</form>";

   echo "</div>";
}

   
   
   

if($_GET['acao'] == "listar_pecas"){

    echo "<div class='col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12' style = 'width:100%; background-color: #ffffff; padding:18px;'>";
    echo "<table class='table table-striped'><tbody>";

    $query = "select id_peca_ref, nome from tb_pecas_ref order by nome asc";
    $result = mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++)
    {
        $row = mysqli_fetch_array($result);
        $id_peca_ref = $row[id_peca_ref];
        $nome = stripslashes($row[nome]);
        
        echo "<tr><th scope='row'>";
        echo "<a href = '#'><i class='icon-doc'></i> &nbsp;<span>$nome</a>";
        echo "</th></tr>";
    }

    echo "</tbody></table></div>";
}

$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();

?>