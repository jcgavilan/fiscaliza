<?php

session_start();

header("Cache-Control: no-cache, no-store, must-revalidate, max-age=0, s-maxage=0, proxy-revalidate, private");
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
header_remove("ETag");
header_remove("Last-Modified");

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



$f -> abre(12);

echo "<br>";
$f->fecha();

//$f -> abre_card(12);

$id_pedido = (int)$_GET['id_pedido'];

// Faz a vinculação do policial ao pedido.

$query2 = "update tb_cidadao_pedidos set id_policial = '".$_SESSION['usuario_fis_cpf']."', nome_policial = '".$_SESSION['usuario_fis_nome']."' where id = $id_pedido";
$result2 = mysqli_query($link, $query2);


//echo "<h2 style = 'color: #999999;'>Dados do Solicitante:<h3>";

$string_aleatoria = str_shuffle("ABCEDEFGHIJKLMEN");

$query = "update tb_cidadao_pedidos set string_aleatoria = '$string_aleatoria' where id = ".$id_pedido;
$result=mysqli_query($link, $query);

$query = "select * from tb_cidadao_pedidos where id = ".$id_pedido;
    $result=mysqli_query($link, $query);

    $a =  str_shuffle($base);
    $str = substr($a, 0, 40);
    $row = mysqli_fetch_array($result);
    $id_pedido = $row[id];
    $string_aleatoria = $row[string_aleatoria];
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
    
    $comentarios_policiais = (stripslashes($row[comentarios_policiais]));
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

 
$query = "Select nome from tb_alvaras_tipo where id = ".$tipo_pedido;
echo "<span style = 'color: #e1e1e1;'>$query</span>"; // A PAGINA TAVA QUEBRANDO SEM ESSE PRINT. NÃO CONSEGUI ENTENDER O PORQUÊ.
$result=mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
$nome_tipo=  stripslashes($row[nome]);

//$f -> fecha_card();

$x = $cnpj;

$f -> abre(6);
echo "<h1>Painel de Análise de Pedidos</h1>";
$f -> fecha();
$f -> abre(6);
echo "<div style = 'display: block; width: 100%; text-align:right; padding-right: 30px; '>";

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
      <iframe width="440" height="320" src="https://www.youtube.com/embed/bzkoMRik-FQ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
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
echo "<h2 style = 'padding-left:30px;'>$nome_tipo</h2>";

$f -> abre_card(12);
echo "<h2 style = 'color: #666666; padding-right:16px;'>Documentos do Solicitante: $nome_estabelecimento / CNPJ: ".$x[0].$x[1]."-".$x[2].$x[3].$x[4]."-".$x[5].$x[6].$x[7]."/".$x[8].$x[9].$x[10].$x[11]."-".$x[12].$x[13]."<span style = 'color: #ffffff'>$string_aleatoria</span>";

?>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style = 'float: right; '>
 VER TODOS OS DADOS DA EMPRESA
</button> <a href = 'pedidos.edicao.php?id_pedido=<?php echo $id_pedido;?>' class = 'btn btn-primary' style = 'float: right; margin-right: 5px;'>EDITAR DADOS</a> &nbsp;
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
      echo "<th scope='col'>Aprovação</th>";
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
                echo "<td>";
                if ($aprovado == 'A') {
                    echo '<div class="form-group form-check-inline">';
                    echo '<label for="inputText3" class="col-form-label"></label>';
                    echo '<label class="custom-control custom-radio custom-control-inline">';
                    echo "<input type='radio' name='aprovado_$i' class='custom-control-input' value='YES' onclick='show_tr_nao($i);' checked><span class='custom-control-label' style = 'font-size:10px;'><p>Sim</p></span>";
                    echo "</label>";
                    echo '<label class="custom-control custom-radio custom-control-inline">';
                    echo "<input type='radio' name='aprovado_$i' class='custom-control-input' value='NO'  onclick='show_tr_sim($i);' ><span class='custom-control-label'><p>Não</p></span>";
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
                echo "</td>";
                echo "</tr>";

        }


        echo "</tbody>";
            echo "</table>";
            echo "</div>";

           // echo  "<br><h4><input id='show_botao_enviar' type='radio' name='show_botao_enviar' value='1'show_botao_enviar/> Confirmo que analisei todos os documentos, e estão de acordo.</h4>";
          //  echo  "<br><h4><input id='show_botao_enviar' type='radio' name='show_botao_enviar' value='1' onchange='show_botao()'/> Confirmo que analisei todos os documentos, e a pendências.</h4>";

            ?>
            
            <br><div class="form-group">
             <div id="allYesDiv" style="display: block;">
                <label class="custom-control custom-radio custom-control-inline">
                <input type="radio" name="show_botao_enviar" id='show_botao_enviar' class="custom-control-input" value="1"  onchange='show_botao()'><span class="custom-control-label">Confirmo que analisei todos os documentos, e <strong>estão de acordo.</strong></span>
                </label>
            </div>
            <div id="oneNoDiv" style="display: none;">
            <label class="custom-control custom-radio custom-control-inline">
                <input type="radio" name="show_botao_enviar" id='show_botao_enviar2' class="custom-control-input" value="0"  onchange='show_botao2()'><span class="custom-control-label">Confirmo que analisei todos os documentos, e <strong>HÁ PENDÊNCIAS</strong></span>
            </label>
            </div>
          </div>
            
            
            <?php

            echo "<div id = 'botao_enviar' style = 'display:none'>";

            if (strlen($comentarios_policiais) > 1) {
              echo "<b>Comentários de análises anteriores</b><br>$comentarios_policiais";
            }
            $f->f_area("Comentários da Análise (opcional)", 'comentarios_policiais', '');
            $f->f_button("CONCLUIR ANÁLISE DE DOCUMENTOS");
            echo "</div>";

            echo "</form>";


            $f -> fecha();

   // $f -> abre(8);

    echo "<div id = 'show' class='col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8' style = 'float: right;'>";

    echo "<object data='arquivos_cidadao_prev/$arquivo_inicial' type='application/pdf' style = 'width:100%; height:680px;'><embed src='arquivos_cidadao_prev/$arquivo_inicial' type='application/pdf' /></object>";

    $f -> fecha();
    


//3rtyt8fe4sj3jh8dytailz6cerxrixugjuom9f3l.pdf*Contrato Social; 3rtyt8fe4sj3jh8dytailz6cerxrixugjuom9f3l.pdf*Alvará de Funcionamento emitido pela Prefeitura Local; 3rtyt8fe4sj3jh8dytailz6cerxrixugjuom9f3l.pdf*Autorização expedida pela Vigilância Sanitária, ou protocolo; 


$f -> fecha_card();

// ----------------------------------------------------------    JAVASCRIPT PARA APRESENTAR MENSAGEM SOMENTE SE O POLICIAL MARCAR SIM OU NÃO PARA APROVAÇÃO DE DOCUMENTOS

?>

<script>
        function checkIfAllYesChecked() {
            const radioButtons = document.querySelectorAll('input[type="radio"][value="YES"]');
            for (const radioButton of radioButtons) {
                if (!radioButton.checked) {
                    return false;
                }
            }
            return true;
        }

        function checkIfOneNoChecked() {
            const radioButtons = document.querySelectorAll('input[type="radio"][value="NO"]');
            for (const radioButton of radioButtons) {
                if (radioButton.checked) {
                    return true;
                }
            }
            return false;
        }

        function showAllYesDiv() {
            const allYesDiv = document.getElementById('allYesDiv');
            allYesDiv.style.display = 'block';
        }

        function showOneNoDiv() {
            const oneNoDiv = document.getElementById('oneNoDiv');
            oneNoDiv.style.display = 'block';
        }

        function allYes() {
            alert('Congratulations! You have marked all "YES" options.');
        }

        function oneNo() {
            alert('You have selected at least one "NO" option.');
        }

        const radioButtons = document.querySelectorAll('input[type="radio"]');
        for (const radioButton of radioButtons) {
            radioButton.addEventListener('change', function () {
                if (checkIfAllYesChecked()) {
                    showAllYesDiv();
                    hideOneNoDiv();
                } else if (checkIfOneNoChecked()) {
                    showOneNoDiv();
                    hideAllYesDiv();
                } else {
                    hideAllYesDiv();
                    hideOneNoDiv();
                }
            });
        }

        function hideAllYesDiv() {
            const allYesDiv = document.getElementById('allYesDiv');
            allYesDiv.style.display = 'none';
        }

        function hideOneNoDiv() {
            const oneNoDiv = document.getElementById('oneNoDiv');
            oneNoDiv.style.display = 'none';
        }
    </script>
<?php
// ---------------------------------------------------------------  FIM DO CÓDIGO JAVASCRIPT

$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();


?>

