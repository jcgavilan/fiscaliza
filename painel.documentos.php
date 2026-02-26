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

$id_estabelecimento = $_GET[id_estabelecimento];
settype($id_estabelecimento, 'integer');

$f -> abre(12);

$f -> abre(4);
    $f -> abre_card(12);
    echo "<h2><a href = 'pagina.estabelecimento.php?id_estabelecimento=$id_estabelecimento'><i class='icon-action-undo'></i></a> &nbsp;Painel de documentos  </h2> <hr>";


    // BUSCA O NOME DO ESTABELECIMENTO
    $query = "select nome_estabelecimento from tb_estabelecimentos where id = ".$id_estabelecimento;
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $nome_estabelecimento =  (stripslashes($row[nome_estabelecimento]));
    echo "<h4>$nome_estabelecimento</h4>";


    echo "<table class='table table-bordered'>";
    echo "<thead>";
      echo "<tr>";
      echo "<th scope='col'>Data do registro</th>";
      echo "<th scope='col'>Documento</th>";
      echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
  
    $query = "select * from tb_documentos_estabelecimentos where id_estabelecimento = ".$id_estabelecimento." order by data_registro desc";
    $result=mysqli_query($link, $query);
        $num = mysqli_num_rows($result);
        for($i=0;$i<$num;$i++) 
                {
                    $row = mysqli_fetch_array($result);
                    $nome_documento =  (stripslashes($row[nome_documento]));
                    $nome_arquivo =  (stripslashes($row[nome_arquivo]));
                    $data_registro =  (stripslashes($row[data_registro]));
                    $data_print = date("d/m/Y", $data_registro);

                    if($i == 0){
                        $arquivo_inicial = $nome_arquivo;
                    }

                    echo "<tr>";
                        echo "<td>$data_print</td>";
                        echo "<td>";
                        echo "<a href = '#' onClick='showPdf(\"arquivos/$nome_arquivo\");' style = 'font-size:12px;' data-toggle='tooltip' >$nome_documento </a>";
                        echo "</td>";
                    echo "</tr>";

                }

                echo "</tbody>";
                    echo "</table>";

                    echo "<a href = 'pagina.estabelecimento.php?id_estabelecimento=$id_estabelecimento' class = 'btn btn-primary' style = 'width:100%; margin-top:24px;'>VOLTAR PARA PÁGINA DO ESTABELECIMENTO</a>";

    $f -> fecha_card();

    $f -> fecha();

    echo "<div id = 'show' class='col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8' style = 'float: right;'>";

    echo "<object data='arquivos/$arquivo_inicial' type='application/pdf' style = 'width:100%; height:680px;'><embed src='arquivos/$arquivo_inicial' type='application/pdf' /></object>";

    $f -> fecha();

$f -> fecha();


$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();



?>