<?php

session_start();

if(!isset($_SESSION["id_usuario_des"]))
{
  //header('Location: login.php');
  //exit();
}
$id_policial = 1;
$nome_policial = 'MARCUS TESTE';

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


echo "<h1>Painel de Geração de Documentos</h1>";
//$f -> abre_card(12);

$f -> abre_card(12);

if (!isset($_GET['id_pedido'])) {


    $nome_documento = array();
    $query = "select id, nome from tb_alvaras_tipo";
    $result = mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++)
    {
        $row = mysqli_fetch_array($result);
        $id = $row[id];
        $nome = stripslashes($row[nome]);
        $nome_documento[$id] = $nome;
    }

    echo "<table class='table'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th scope='col'>Estabelecimento</th>";
    //echo "<th scope='col'>CNPJ</th>";
    echo "<th scope='col'>Data do pedido</th>";
    echo "<th scope='col'>Data da Análise</th>";
    echo "<th scope='col'>Tipo de Documento</th>";
    echo "<th scope='col'> </th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    $query = "select id, ultima_movimentacao, nome_estabelecimento, cnpj, data_pedido, tipo_pedido from tb_cidadao_pedidos where status = 2 and data_conclusao = 0"; // ainda não foram recebidos.
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++) 
    {
        $a =  str_shuffle($base);
        $str = substr($a, 0, 40);
        $row = mysqli_fetch_array($result);
        $id_pedido = $row[id];
        $data_pedido = $row[data_pedido];
        $ultima_movimentacao = $row[ultima_movimentacao];
        $tipo_pedido = $row[tipo_pedido];
        $nome_estabelecimento= (stripslashes($row[nome_estabelecimento]));
        $cnpj= (stripslashes($row[cnpj]));

        echo "<tr>";
        echo "<th scope='row'>$nome_estabelecimento</th>";
        // echo "<td>$cnpj</td>";
        echo "<td>".date("d/m/y H:i", $data_pedido)."</td>";
        echo "<td>".date("d/m/y H:i", $ultima_movimentacao)."</td>";
        echo "<td>".$nome_documento[$tipo_pedido]."</td>";
        echo "<th scope='row'><a HREF = 'delegado.painel.php?id_pedido=$id_pedido' class = 'btn btn-primary'>VER SOLICITAÇÃO</a></th>";

        echo "</tr>";

    }

    echo "</tbody>";
    echo "</table>";
    
}else{

$id_pedido = (int)$_GET['id_pedido'];

// Faz a vinculação do policial ao pedido.

//echo "<h2 style = 'color: #999999;'>Dados do Solicitante:<h3>";

$query = "select * from tb_cidadao_pedidos where id = ".$id_pedido;
    $result=mysqli_query($link, $query);

    $a =  str_shuffle($base);
    $str = substr($a, 0, 40);
    $row = mysqli_fetch_array($result);
    $id_pedido = $row[id];
    $data_pedido = $row[data_pedido];
    $tipo_pedido = $row[tipo_pedido];
    $cnpj= (stripslashes($row[cnpj]));
    $nome_estabelecimento= (stripslashes($row[nome_estabelecimento]));
    $razao_social= (stripslashes($row[razao_social]));
    $id_ramo_atividade= (stripslashes($row[id_ramo_atividade]));
    $endereco_rua= (stripslashes($row[endereco_rua]));
    $endereco_numero= (stripslashes($row[endereco_numero]));
    $endereco_bairro= (stripslashes($row[endereco_bairro]));
    $endereco_cep= (stripslashes($row[endereco_cep]));
    $id_municipio= (stripslashes($row[id_municipio]));
    $telefone_fixo= (stripslashes($row[telefone_fixo]));
    $telefone_celular= (stripslashes($row[telefone_celular]));
    $email= (stripslashes($row[email]));
    $nome_proprietario= (stripslashes($row[nome_proprietario]));
    $arquivos= (stripslashes($row[arquivos]));
    
    // $f->f_input_read(3, "Nome do Estabelecimento:", "", "$nome_estabelecimento");
    // $f->f_input_read(3, "Razão Social:", "", "$razao_social");
    // $f->f_input_read(2, "CNPJ:", "", "$cnpj");

    // $query2 = "select nome from tb_ramos_atividade where id = ".$id_ramo_atividade;
    // $result2 = mysqli_query($link, $query2);
    // $row2 = mysqli_fetch_array($result2);
    // $nome_ramo_atividade = (stripslashes($row2[nome]));
    // $f->f_input_read(2, "Ramo de Atividade:", "", "$nome_ramo_atividade");
    // $f->f_input_read(2, "Nome do Proprietário:", "", "$nome_proprietario");

    // $f->f_input_read(3, "Endereço:", "", "$endereco_rua, $endereco_numero - $endereco_bairro");
    // $f->f_input_read(1, "CEP:", "", "$endereco_cep");

    // $query2 = "select nome from tb_municipios_ibge where ibge = ".$id_municipio;
    // $result2 = mysqli_query($link, $query2);
    // $row2 = mysqli_fetch_array($result2);
    // $nome_municipio = (stripslashes($row2[nome]));        
    // $f->f_input_read(2, "Municipio:", "", "$nome_municipio");

    // $f->f_input_read(2, "Telefone Fixo:", "", "$telefone_fixo");
    // $f->f_input_read(2, "Telefone Celular:", "", "$telefone_celular");
    // $f->f_input_read(2, "Email:", "", "$email");

 


//$f -> fecha_card();




echo "<h2 style = 'color: #999999;'>Documentos do Solicitante: $nome_estabelecimento / CNPJ $cnpj  ";

?>
<!-- Button trigger modal -->
<button type="button" class="btn btn-light" data-toggle="modal" data-target="#exampleModal">
 VER DADOS COMPLETOS
</button>
</h3>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Dados do Solicitante</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
  <?php
  
    $f->f_input_read(12, "Nome do Estabelecimento:", "", "$nome_estabelecimento");
    $f->f_input_read(6, "Razão Social:", "", "$razao_social");
    $f->f_input_read(6, "CNPJ:", "", "$cnpj");

    /*$query2 = "select nome from tb_ramos_atividade where id = ".$id_ramo_atividade;
    $result2 = mysqli_query($link, $query2);
    $row2 = mysqli_fetch_array($result2);
    $nome_ramo_atividade = (stripslashes($row2[nome]));*/
    $f->f_input_read(6, "Ramo de Atividade:", "", "$id_ramo_atividade");
    $f->f_input_read(6, "Nome do Proprietário:", "", "$nome_proprietario");

    $f->f_input_read(8, "Endereço:", "", "$endereco_rua, $endereco_numero - $endereco_bairro");
    $f->f_input_read(4, "CEP:", "", "$endereco_cep");

    $query2 = "select nome from tb_municipios_ibge where ibge = ".$id_municipio;
    $result2 = mysqli_query($link, $query2);
    $row2 = mysqli_fetch_array($result2);
    $nome_municipio = (stripslashes($row2[nome]));        
    $f->f_input_read(6, "Municipio:", "", "$nome_municipio");
    $f->f_input_read(6, "Email:", "", "$email");

    $f->f_input_read(6, "Telefone Fixo:", "", "$telefone_fixo");
    $f->f_input_read(6, "Telefone Celular:", "", "$telefone_celular");
   

  ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">FECHAR</button>

      </div>
    </div>
  </div>
</div>
<?php

// PARA CONTROLE DOS ARQUIVOS, É MAIS INTERESSANTE TER EM UMA TABELA PROVISÓRIA, QUE VAI TER OS CAMPOS EXCLUÍDOS COM A FINALIZAÇÃO DO PROCESSO

// VERIFICA SE JÁ EXISTE O CADASTRO DOS ARQUIVOS PARA ESTE PEDIDO
$f->abre(4);

/*$query = "select id from tb_cidadao_arquivos where id_pedido = $id_pedido";
$result = mysqli_query($link, $query);
$num = mysqli_num_rows($result);
if($num == 0){

    $arq_total = explode(";", $arquivos);

    for ($i=0; $i < count($arq_total); $i++) { 
        $arq_fim = $arq_total[$i];

        $q = explode("*", $arq_fim);
        $arquivo_nome = $q[1];
        $arquivo_arquivo = $q[0];

       if (strlen($arquivo_arquivo) > 3) {
        $query = "insert into tb_cidadao_arquivos (id_pedido, id_policial, nome, arquivo) values ($id_pedido, $id_policial, '$arquivo_nome', '$arquivo_arquivo')";
        $result = mysqli_query($link, $query);
       }
       
    }
}*/

$doc_nomes = array();
$query = "select id, nome from tb_documentos_tipo";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for($i=0;$i<$num;$i++) 
    {
        $row = mysqli_fetch_array($result);
        $id = $row['id'];
        $doc_nomes[$id] =  (stripslashes($row[nome]));
    }

$f -> abre_form("gera.alvara.prev.cidadao.php");  
echo "<input type='hidden' name='id_pedido' value='$id_pedido'>";  

    echo "<table class='table table-bordered'>";
    echo "<thead>";
      echo "<tr>";

      echo "<th scope='col'>DOCUMENTOS FORNECIDOS PELO CIDADÃO</th>";
      echo "<th scope='col'>Aprovação</th>";
      echo "</tr>";
    echo "</thead>";
    echo "<tbody style = 'border: 0px;'>";


  
    $query = "select * from tb_cidadao_arquivos where id_pedido = ".$id_pedido." and aprovado = 'S'";
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++) 
        {
            $row = mysqli_fetch_array($result);
            $nome_documento =  (stripslashes($row[nome]));
            $nome_arquivo =  trim(stripslashes($row[arquivo]));
            $id_documento_tipo =  (stripslashes($row[id_documento_tipo]));
            $aprovado =  (stripslashes($row[aprovado]));
            $data_analise =  (stripslashes($row[data_analise]));
            $id_policial =  (stripslashes($row[id_policial]));

            if($i == 0){
                $arquivo_inicial = $nome_arquivo;
            }

            echo "<tr>"; 
                echo "<td>";
                echo "<a href = '#' onClick='showPdf(\"arquivos_cidadao_prev/$nome_arquivo\");' style = 'font-size:12px;' data-toggle='tooltip' >".$doc_nomes[$id_documento_tipo]." </a>";

                echo "</td>";
                echo "<td>";
                echo "<p><span  style = 'font-size: 11px;'>Conferido e provado por POLICIAL_TESTE em ".date("d/m/Y H:i", $data_analise)."</span></p>";
                echo "</td>";
          /*      echo "<td>";
                if ($aprovado == 'A') {
                    echo '<div class="form-group form-check-inline">';
                    echo '<label for="inputText3" class="col-form-label"></label>';
                    echo '<label class="custom-control custom-radio custom-control-inline">';
                    echo "<input type='radio' name='aprovado_$i' class='custom-control-input' value='1' onclick='show_tr_nao($i);' checked><span class='custom-control-label' style = 'font-size:10px;'><p>Sim</p></span>";
                    echo "</label>";
                    echo '<label class="custom-control custom-radio custom-control-inline">';
                    echo "<input type='radio' name='aprovado_$i' class='custom-control-input' value='0'  onclick='show_tr_sim($i);' ><span class='custom-control-label'><p>Não</p></span>";
                    echo "</label>";
                    echo "</div>";
                }

                if ($aprovado == 'S') {
                  echo "<h2><SPAN style = 'color: #008000;'>APROVADO</SPAN></h2>";
                }
                echo "</td>";
                echo "</tr>";

                echo "<tr style = 'display:none;' id = 'tr_$i'>";
                echo "<td colspan= '2'>";
                echo '<div class="form-group">';
                echo "<label for='exampleFormControlTextarea1'><p>Justificativa da negativa (orientação ao cidadão)</p></label>";
                echo "<textarea class='form-control' id='justificativa_$i'  name='justificativa_$i' rows='3'></textarea>";
                echo "</div>";
                echo "</td>"; */
                echo "</tr>";

        }


        echo "</tbody>";
            echo "</table>";

            //echo  "<br><h4><input id='show_botao_enviar' type='checkbox' name='show_botao_enviar' value='1' onchange='show_botao()'/> Confirmo que analisei todos os documentos</h4>";


            echo "<div id = 'botao_enviar' style = 'display:TABLE; PAdding-top: 36px;'>";
            $f->f_button("GERAR DOCUMENTO E CONCLUIR O PROCESSO");
            echo "</div>";

            echo "</form>";


            $f -> fecha();

   // $f -> abre(8);

    echo "<div id = 'show' class='col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8' style = 'float: right;'>";

    echo "<object data='arquivos_cidadao_prev/$arquivo_inicial' type='application/pdf' style = 'width:100%; height:680px;'><embed src='arquivos_cidadao_prev/$arquivo_inicial' type='application/pdf' /></object>";

    $f -> fecha();
    
}

//3rtyt8fe4sj3jh8dytailz6cerxrixugjuom9f3l.pdf*Contrato Social; 3rtyt8fe4sj3jh8dytailz6cerxrixugjuom9f3l.pdf*Alvará de Funcionamento emitido pela Prefeitura Local; 3rtyt8fe4sj3jh8dytailz6cerxrixugjuom9f3l.pdf*Autorização expedida pela Vigilância Sanitária, ou protocolo; 


$f -> fecha_card();

$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();


?>

?>