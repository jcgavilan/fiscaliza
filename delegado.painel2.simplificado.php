<?php

session_start();

// if(!isset($_SESSION["usuario_fis_cpf"]))
// {
//   // header('Location: https://integra.pc.sc.gov.br/');
//   // exit();
// }

// if($_SESSION["usuario_fis_is_delegado"] == 0)
// {
//   header('Location: https://integra.pc.sc.gov.br/');
//   exit();
// }


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
echo "<h1>Painel de Homologação de Alvarás:</h1>";//echo "<h1>Painel de Análise de Pedidos</h1>";
$f -> fecha();
$f -> abre(6);
echo "<div style = 'display: block; width: 100%; text-align:right;  '>";

//echo "<a href = '#' class = 'btn btn-primary'><i class='fa-solid fa-video'></i> VÍDEO DE ORIENTAÇÃO PARA ESTA PÁGINA</a>";

?>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#videoModal" style = 'float: right; '>
VÍDEO DE ORIENTAÇÃO PARA ESTA PÁGINA
</button><br>
</h2>
<div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel" aria-hidden="true" style = 'width: 100%' >
  <div class="modal-dialog" role="document" style = 'width: 100%'>
    <div class="modal-content" style = 'width: 100%'>
      <div class="modal-header" style = 'width: 100%'>
        <h3 class="modal-title" id="videoModalLabel"></h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style = "width: 100%; height: 400px; background-color: #FFFFFF; overflow-y: scroll;padding-top:8px;">
      <iframe width="440" height="320" src="https://www.youtube.com/embed/ozIpKDkJsls" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">FECHAR</button>

      </div>
    </div>
  </div>
</div>
<br>
<?php
echo "</div>";
$f -> fecha();
echo "</div>";
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
        echo "<th scope='row'><a HREF = 'delegado.painel2.php?id_pedido=$id_pedido' class = 'btn btn-primary'>VER SOLICITAÇÃO</a></th>";

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
    $pagina_resumo= (stripslashes($row[pagina_resumo]));
    $comentarios_policiais= (stripslashes($row[comentarios_policiais]));
    
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

$query = "Select nome from tb_alvaras_tipo where id = ".$tipo_pedido;
$result=mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
$nome_tipo=  stripslashes($row[nome]);

echo "<h2>$nome_tipo</h2>";


$x = $cnpj;

//echo "<h2 style = 'color: #999999;'>Documentos do Solicitante: $nome_estabelecimento / CNPJ $cnpj  ";
echo "<h2 style = 'color: #666666; padding-right:16px;'>Documentos do Solicitante: $nome_estabelecimento / CNPJ: ".$x[0].$x[1]."-".$x[2].$x[3].$x[4]."-".$x[5].$x[6].$x[7]."/".$x[8].$x[9].$x[10].$x[11]."-".$x[12].$x[13];

?>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style = 'float: right; '>
 VER TODOS OS DADOS DA EMPRESA
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
      <div class="modal-body" style = "width: 100%; height: 500px; background-color: #FFFFFF; overflow-y: scroll;padding-top:8px;">
  <?php
  
  $query2 = "select resumo from tb_empresa_resumo where id_pedido = $id_pedido LIMIT 1";
  $result2=mysqli_query($link, $query2);
  $row2 = mysqli_fetch_array($result2);
  $pagina_resumo = stripslashes($row2[resumo]);
  echo $pagina_resumo;
   

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

//$f -> abre_form("gera.alvara.prev.cidadao.php");  
$f -> abre_form("delegado.painel.concluir.php");  
echo "<input type='hidden' name='id_pedido' value='$id_pedido'>";  

  


  
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
            $nome_policial_aprovacao =  (stripslashes($row[nome_policial_aprovacao]));

            if($i == 0){
                $arquivo_inicial = $nome_arquivo;
            }


                echo "<a href = 'arquivos_cidadao_prev/$nome_arquivo' style = 'font-size:12px;' target='_blank' >".$doc_nomes[$id_documento_tipo]." </a>";

                echo "<p><span  style = 'font-size: 11px;'>Aprovado por ".$nome_policial_aprovacao." em ".date("d/m/Y H:i", $data_analise)."</span></p>";

        }


            //echo  "<br><h4><input id='show_botao_enviar' type='checkbox' name='show_botao_enviar' value='1' onchange='show_botao()'/> Confirmo que analisei todos os documentos</h4>";

            if (strlen($comentarios_policiais) > 1) {
              echo "<b>Comentários de análises policiais</b><br>$comentarios_policiais";
            }
            echo "<div id = 'botao_enviar' style = 'display:TABLE; PAdding-top: 36px;'>";
            $f->f_button("SALVAR");
            echo "</div>";

            echo "</form>";


            $f -> fecha();

   // $f -> abre(8);

  
    
}

//3rtyt8fe4sj3jh8dytailz6cerxrixugjuom9f3l.pdf*Contrato Social; 3rtyt8fe4sj3jh8dytailz6cerxrixugjuom9f3l.pdf*Alvará de Funcionamento emitido pela Prefeitura Local; 3rtyt8fe4sj3jh8dytailz6cerxrixugjuom9f3l.pdf*Autorização expedida pela Vigilância Sanitária, ou protocolo; 


$f -> fecha_card();

$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();

?>