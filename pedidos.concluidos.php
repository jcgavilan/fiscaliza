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

$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);



//$f -> abre_card(12);

$id_pedido = (int)$_GET['id_pedido'];

// Faz a vinculação do policial ao pedido.

$query2 = "update tb_cidadao_pedidos set id_policial = '".$_SESSION['usuario_fis_cpf']."', nome_policial = '".$_SESSION['usuario_fis_nome']."' where id = $id_pedido";
$result2 = mysqli_query($link, $query2);


//echo "<h2 style = 'color: #999999;'>Dados do Solicitante:<h3>";

$query = "select * from tb_cidadao_pedidos where id = ".$id_pedido;
    $result=mysqli_query($link, $query);

    $a =  str_shuffle($base);
    $str = substr($a, 0, 40);
    $row = mysqli_fetch_array($result);
    $id_pedido = $row[id];
    $status = $row[status];
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
    $documento_final = stripslashes($row[documento_final]);
    $pagina_resumo= (stripslashes($row[pagina_resumo]));

//$f -> fecha_card();

    echo "<h1>Painel de documentos do pedido concluído <a href = 'https://sistemas.pc.sc.gov.br/cidadao/alvaras/$documento_final' target= '_blank' class = 'btn btn-primary'>VER ALVARÁ</a>";
    
    switch ($status) {
      case '3':
        echo " &nbsp; <a href = '#' class = 'btn btn-success'> ALVARÁ VÁLIDO</a>";
        if ($_SESSION['usuario_fis_is_delegado'] == 1 || $_SESSION['usuario_fis_cpf'] == '02200965974' ) {
          echo " &nbsp; <a href = 'pedidos.revogar.php?id_pedido=$id_pedido&data_pedido=$data_pedido' class = 'btn btn-danger'> CANCELAR ALVARÁ E REABRIR PARA HOMOLOGAÇÃO</a>";
        }
      break;
      
      case '4':
        echo " &nbsp; <a href = '#' class = 'btn btn-danger'> ALVARÁ CANCELADO</a>";
      break;

      case '5':
        echo " &nbsp; <a href = '#' class = 'btn btn-dark'> ALVARÁ VENCIDO</a>";
      break;
    }
    echo "</h1>";
    //echo " &nbsp;<a href = 'https://sistemas.pc.sc.gov.br/cidadao/alvaras/$documento_final' target= '_blank' class = 'btn btn-primary'>VER ALVARÁ</a>";



$f -> abre_card(12);
$x = $cnpj;
echo "<h2 style = 'color: #666666; padding-right:16px;'>Documentos do Solicitante: $nome_estabelecimento / CNPJ: ".$x[0].$x[1]."-".$x[2].$x[3].$x[4]."-".$x[5].$x[6].$x[7]."/".$x[8].$x[9].$x[10].$x[11]."-".$x[12].$x[13];
?>
<a href = 'gera.resumo.empresa.print.php?id_pedido=<?php echo $id_pedido;?>&data_pedido=<?php echo $data_pedido;?>' style = 'float: right; margin: 0px 5px;' class = 'btn btn-primary'><i class='icon-printer'></i></a>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style = 'float: right; '>
 VER TODOS OS DADOS DA EMPRESA
</button>
<!--<a href = 'gera.resumo.empresa.print.php?id_pedido=<?php echo $id_pedido;?>&data_pedido=<?php echo $data_pedido;?>' style = 'float: right; margin: 0px 5px;' class = 'btn btn-primary'><i class='icon-printer'></i></a> -->
</h2>

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

$f -> abre_form("pedidos.painel.concluir.php?id_pedido=$id_pedido");    
    echo "<div style = 'display: block; width: 100%; height: 600px; overflow-y: scroll;'>";
    echo "<table class='table table-bordered'  >";
    echo "<thead>";
      echo "<tr>";

      echo "<th scope='col'>Documento</th>";

      echo "</tr>";
    echo "</thead>";
    echo "<tbody style = 'border: 0px;'>";
    


  
    $query = "select * from tb_cidadao_arquivos where id_pedido = ".$id_pedido." and (aprovado = 'A' or aprovado = 'S') order by id asc";

    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++) 
        {
            $row = mysqli_fetch_array($result);
            $nome_documento =  (stripslashes($row[nome]));
            $nome_arquivo =  trim(stripslashes($row[arquivo]));
            $id_documento_tipo =  (stripslashes($row[id_documento_tipo]));
            $aprovado =  (stripslashes($row[aprovado]));
            $justificativa_cidadao =  (stripslashes($row[justificativa_cidadao]));

            if($i == 0){
                $arquivo_inicial = $nome_arquivo;
            }

            echo "<tr>"; 
                echo "<td>";
                echo "<a href = '#' onClick='showPdf(\"arquivos_cidadao_prev/$nome_arquivo\");' style = 'font-size:12px;' data-toggle='tooltip' >".$doc_nomes[$id_documento_tipo]." </a>";
                if (strlen($justificativa_cidadao)) {
                  echo "<p>OBS.: $justificativa_cidadao</p>";
                }
                echo "</td>";

                echo "</tr>";

        }


        echo "</tbody>";
            echo "</table>";
            echo "</div>";

           // echo  "<br><h4><input id='show_botao_enviar' type='radio' name='show_botao_enviar' value='1'show_botao_enviar/> Confirmo que analisei todos os documentos, e estão de acordo.</h4>";
          //  echo  "<br><h4><input id='show_botao_enviar' type='radio' name='show_botao_enviar' value='1' onchange='show_botao()'/> Confirmo que analisei todos os documentos, e a pendências.</h4>";

           

            $f -> fecha();

   // $f -> abre(8);

    echo "<div id = 'show' class='col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8' style = 'float: right;'>";

    echo "<object data='arquivos_cidadao_prev/$arquivo_inicial' type='application/pdf' style = 'width:100%; height:680px;'><embed src='arquivos_cidadao_prev/$arquivo_inicial' type='application/pdf' /></object>";

    $f -> fecha();
    


//3rtyt8fe4sj3jh8dytailz6cerxrixugjuom9f3l.pdf*Contrato Social; 3rtyt8fe4sj3jh8dytailz6cerxrixugjuom9f3l.pdf*Alvará de Funcionamento emitido pela Prefeitura Local; 3rtyt8fe4sj3jh8dytailz6cerxrixugjuom9f3l.pdf*Autorização expedida pela Vigilância Sanitária, ou protocolo; 


$f -> fecha_card();

$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();


?>

?>