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

$id = (int)$_GET['id'];
$data_expedicao = (int)$_GET['data_expedicao'];

$municipio_nome = array();
$query = "select nome, ibge_reduzido from tb_municipios_ibge";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for($i=0;$i<$num;$i++) 
        {
            $row = mysqli_fetch_array($result);
            $ibge = $row[ibge_reduzido];
            $nome =  (stripslashes($row[nome]));
            $municipio_nome[$ibge] = $nome;
        }


//echo "<h2 style = 'color: #999999;'>Dados do Solicitante:<h3>";

$query = "select * from tb_carteiras where id = ".$id." and data_expedicao = $data_expedicao";
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $id_carteira = $row[id];
    $nome = (stripslashes($row[nome]));
    $cargo = (stripslashes($row[cargo]));
    $tipo = (stripslashes($row[tipo]));
    $sgpe = (stripslashes($row[sgpe]));
    $matricula= (stripslashes($row[matricula]));
    $cpf= (stripslashes($row[cpf]));
    $rg = (stripslashes($row[rg]));
    $filiacao_pai = (stripslashes($row[filiacao_pai]));
    $filiacao_mae = (stripslashes($row[filiacao_mae]));
    $rg = (stripslashes($row[rg]));
    $empresa = (stripslashes($row[empresa]));
    $capacitacao = (stripslashes($row[capacitacao]));
    $categoria = (stripslashes($row[categoria])); 
    $data_expedicao= (stripslashes($row[data_expedicao]));
    $data_validade= (stripslashes($row[data_validade]));
    $validade_txt = (stripslashes($row[validade_txt]));
    $arquivo= (stripslashes($row[arquivo]));
    $policial_cpf= (stripslashes($row[policial_cpf]));
    $policial_nome= (stripslashes($row[policial_nome]));
    $id_municipio= (stripslashes($row[id_municipio]));
    $blaster_empresa_nome = (stripslashes($row[blaster_empresa_nome]));
    $blaster_empresa_cnpj = (stripslashes($row[blaster_empresa_cnpj]));
    $id_municipio= (stripslashes($row[id_municipio]));
    $municipio_txt = (stripslashes($row[municipio_txt]));
    $delegado_aprov = (stripslashes($row[delegado_aprov]));

    // verificando o resto dos dados que não veio aqui

    $query = "select * from tb_carteiras_complemento where id_carteira = $id_carteira";
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $data_nascimento = (stripslashes($row[data_nascimento]));
    $logradouro = (stripslashes($row[logradouro]));
    $bairro = (stripslashes($row[bairro]));
    $numero = (stripslashes($row[numero]));
    $complemento = (stripslashes($row[complemento]));
    $cep = (stripslashes($row[cep]));
    $contato = (stripslashes($row[contato]));
    $email = (stripslashes($row[email]));
    $registro_exercito = (stripslashes($row[registro_exercito]));
    $numero_registro = (stripslashes($row[numero_registro]));
    $nome_empresa_curso = (stripslashes($row[nome_empresa_curso]));
    $cnpj_empresa_curso = (stripslashes($row[cnpj_empresa_curso]));
    $nome_empresa_empregadora = (stripslashes($row[nome_empresa_empregadora]));
    $cnpj_empresa_empregadora = (stripslashes($row[cnpj_empresa_empregadora]));
    $responsavel_legal = (stripslashes($row[responsavel_legal]));
    $logradouro_empresa_empregadora = (stripslashes($row[logradouro_empresa_empregadora]));
    $bairro_empresa_empregadora = (stripslashes($row[bairro_empresa_empregadora]));
    $numero_empresa_empregadora = (stripslashes($row[numero_empresa_empregadora]));
    $cep_empresa_empregadora = (stripslashes($row[cep_empresa_empregadora]));
    $id_municipio_empresa_empregadora = (stripslashes($row[id_municipio_empresa_empregadora]));
    $municipio_txt_empresa_empregadora = (stripslashes($row[municipio_txt_empresa_empregadora]));
    $contato_empresa_empregadora = (stripslashes($row[contato_empresa_empregadora]));
    $email_empresa_empregadora = (stripslashes($row[email_empresa_empregadora]));
    $registro_exercito_empresa_empregadora = (stripslashes($row[registro_exercito_empresa_empregadora]));
    $numero_registro_empresa_empregadora = (stripslashes($row[numero_registro_empresa_empregadora]));
    $arma_especie = (stripslashes($row[arma_especie]));
    $arma_modelo = (stripslashes($row[arma_modelo]));
    $arma_registro = (stripslashes($row[arma_registro]));
    $arma_marca = (stripslashes($row[arma_marca]));
    $arma_calibre = (stripslashes($row[arma_calibre]));
    $arma_numero_serie = (stripslashes($row[arma_numero_serie]));
    $arma_numero_sigma = (stripslashes($row[arma_numero_sigma]));
    $arma_numero_sinarm = (stripslashes($row[arma_numero_sinarm]));

    // SIMPLESMENTE PARA A APRESENTAÇÃO SE A PESSOA NÃO É DELEGADA, E A CARTEIRA AINDA NÃO FOI AVALIADA.
if ($_SESSION['usuario_fis_cpf'] != '02200965974') {
  # code...

    // if ($_SESSION['usuario_fis_is_delegado'] == 0 && $delegado_aprov != 1){

    //   echo " <div class='alert alert-info' role='alert'>";
    //   echo "<h1 class='alert-heading' align = 'center'>Pedido de Emissão de Carteira <BR><span style = 'color: #cc0000;'><strong>NÃO DISPONÍVEL</strong></span><br>para Visualização</h1>";
    //   echo "</div>";

    //   $f -> fecha();
    //   $f -> fecha_card();
    //   $footer=new Footer_adm_WEB();
    //   $footer->Footer_adm_WEB();
    //   exit();

    // }
 
}
//$f -> fecha_card();

    echo "<h1>Painel de visualização de carteiras e outros documentos</h1>";

    // O ITEM ABAIXO SÓ É APRESENTADO SE A FLAG DE APROVAÇÃO FOR == 0
    if ($delegado_aprov == 0){

      if ( ($_SESSION['usuario_fis_is_delegado'] == 1 || $_SESSION['usuario_fis_cpf'] == '02200965974') && $delegado_aprov == 0) { // QUE HARDCODE MAIS FEIO, MAS TEM QUE TESTAR NÉ

        $f -> abre_card(12);
        $f->abre(12); 

        echo "<h2>Esta Carteira está <span style = 'color:#cc0000;'>AGUARDANDO</span> Aprovação.</h2>";

          $f -> abre_form("carteira.aprovar.php?id=$id&data_expedicao=$data_expedicao");

          echo "&nbsp;<BR>O parecer é pela APROVAÇÃO ou REJEIÇÃO do pedido de emissão de carteira? &nbsp; &nbsp; &nbsp;";
        echo "<label class='custom-control custom-radio custom-control-inline'>";
        echo "<input type='radio' name = 'carteira_aprova_rejeita' id='show_div_carteira_aprova' class='custom-control-input' value='1' required='' onchange='show_carteira_aprova()'";
        echo "required><span class='custom-control-label'>&nbsp;APROVAÇÃO. &nbsp;</span>";
        echo "</label>";  

        echo "<label class='custom-control custom-radio custom-control-inline'>";
        echo "<input type='radio' name = 'carteira_aprova_rejeita'  id='show_div_carteira_rejeita' class='custom-control-input' value='0' onchange='show_carteira_rejeita()'";
        echo "><span class='custom-control-label'>&nbsp;REJEIÇÃO. &nbsp;</span>";
        echo "</label>";


        echo "\n<div id='div_carteira_aprova' style = ' width: 100%; padding: 18px; background-color: #f5f5f5;display:none;'>"; 
        
        $f-> f_button("CONCLUIR APROVAÇÃO");

        echo "</div>";

        echo "\n<div id='div_carteira_rejeita' style = ' width: 100%; padding: 18px; background-color: #f5f5f5;display:none;'>"; 
        
        // echo "<div style = 'display: table; width: 40%; height: 200px;'>";
        // $f-> f_area('Justificativa da Rejeição do pedido', 'delegado_rejeicao_justificativa', '');
        // echo "</div>";

        echo "<div class='form-group'>";
        echo "<label for='exampleInputUsername1'><span class = 'policia'>Justificativa da Rejeição do pedido</span></label>";
        echo "<textarea class='form-control' id='delegado_rejeicao_justificativa'  name='delegado_rejeicao_justificativa' rows='4' style= 'width: 40%; height:100px;'></textarea>";
        echo "</div>";

        $f-> f_button("REJEITAR PEDIDO");

        echo "</div>";

          echo "</form>";

        $f -> fecha();
        $f -> fecha_card();

      }
  }

$f -> abre_card(12);

$f->abre(12); 
    

switch ($tipo) {
    case 'blaster':
        $tipo_print = 'Carteira de Bláster';
    break;

    case 'arma_particular':
        $tipo_print = 'Carteira para Uso de Arma Particular';
    break;

    case 'aposentado':
        $tipo_print = 'Carteira de Porte de arma para Aposentado';
    break;
}

echo "<h2>$tipo_print / $nome";
echo "<a href = 'carteira.adiciona.arquivo.php?id_carteira=$id_carteira&data_expedicao=$data_expedicao' class='btn btn-primary' style = 'float: right'>ADICIONAR ARQUIVO</a>";
?>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style = 'float: right; margin: 0px 8px;'>&nbsp;
 VER TODOS OS DADOS
</button>
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

      <table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">&nbsp;</th>
      <th scope="col">&nbsp;</th>
    </tr>
  </thead>
  <tbody>
  <?php

    

if (strlen($nome) > 0) {echo "<tr><td>Nome</td><td>$nome</td></tr>";}
if (strlen($data_nascimento) > 0) {echo "<tr><td>Data de Nascimento</td><td>$data_nascimento</td></tr>";}
if (strlen($cargo) > 0) {echo "<tr><td>Cargo</td><td>$cargo</td></tr>";}
if (strlen($tipo) > 0) {echo "<tr><td>Tipo</td><td>$tipo</td></tr>";}
if (strlen($matricula) > 0) {echo "<tr><td>Matrícula</td><td>$matricula</td></tr>";}
if (strlen($cpf) > 0) {echo "<tr><td>CPF</td><td>$cpf</td></tr>";}
if (strlen($rg) > 0) {echo "<tr><td>RG</td><td>$rg</td></tr>";}
if (strlen($filiacao_pai) > 0) {echo "<tr><td>Filiação (Pai)</td><td>$filiacao_pai</td></tr>";}
if (strlen($filiacao_mae) > 0) {echo "<tr><td>Filiação (Mãe)</td><td>$filiacao_mae</td></tr>";}
if (strlen($logradouro) > 0) {echo "<tr><td>Logradouro</td><td>$logradouro</td></tr>";}
if (strlen($bairro) > 0) {echo "<tr><td>Bairro</td><td>$bairro</td></tr>";}
if (strlen($numero) > 0) {echo "<tr><td>Número</td><td>$numero</td></tr>";}
if (strlen($complemento) > 0) {echo "<tr><td>Complemento</td><td>$complemento</td></tr>";}
if (strlen($cep) > 0) {echo "<tr><td>CEP</td><td>$cep</td></tr>";}
if (strlen($municipio_txt) > 0) {echo "<tr><td>CEP</td><td>$municipio_txt</td></tr>";}

if ($id_municipio != 0 && $id_municipio != '') {
    echo "<tr><td>Municipio</td><td>".$municipio_nome[$id_municipio]."</td></tr>";
}
if (strlen($sgpe) > 0) {echo "<tr><td>SGPE</td><td>$sgpe</td></tr>";}
if (strlen($contato) > 0) {echo "<tr><td>Contato</td><td>$contato</td></tr>";}
if (strlen($email) > 0) {echo "<tr><td>Email</td><td>$email</td></tr>";}
if (strlen($empresa) > 0) {echo "<tr><td>Empresa</td><td>$empresa</td></tr>";}
if (strlen($capacitacao) > 0) {echo "<tr><td>Capacitação</td><td>$capacitacao</td></tr>";}
if (strlen($categoria) > 0) {echo "<tr><td>Categoria</td><td>$categoria</td></tr>";}
if (strlen($data_expedicao) > 0) {echo "<tr><td>Data de Expedição</td><td>$data_expedicao</td></tr>";}
if (strlen($data_validade) > 0) {echo "<tr><td>Data de Validade</td><td>$data_validade</td></tr>";}
if (strlen($validade_txt) > 0) {echo "<tr><td>Data de Validade</td><td>$validade_txt</td></tr>";}
if (strlen($policial_nome) > 0) {echo "<tr><td>Policial que atendeu:</td><td>$policial_nome</td></tr>";}
if (strlen($blaster_empresa_nome) > 0) {echo "<tr><td>Empresa que certificou:</td><td>$blaster_empresa_nome</td></tr>";}
if (strlen($blaster_empresa_cnpj) > 0) {echo "<tr><td>CNPJ da certificadora:</td><td>$blaster_empresa_cnpj</td></tr>";}
if (strlen($registro_exercito) > 0) {echo "<tr><td>Registro no Exército</td><td>$registro_exercito</td></tr>";}
if (strlen($numero_registro) > 0) {echo "<tr><td>Número de Registro</td><td>$numero_registro</td></tr>";}
if (strlen($nome_empresa_curso) > 0) {echo "<tr><td>Empresa que ministrou curso</td><td>$nome_empresa_curso</td></tr>";}
if (strlen($cnpj_empresa_curso) > 0) {echo "<tr><td>CNPJ da Empresa que ministrou curso</td><td>$cnpj_empresa_curso</td></tr>";}
if (strlen($nome_empresa_empregadora) > 0) {echo "<tr><td>Empresa Empregadora</td><td>$nome_empresa_empregadora</td></tr>";}
if (strlen($cnpj_empresa_empregadora) > 0) {echo "<tr><td>CNPJ da Empresa Empregadora</td><td>$cnpj_empresa_empregadora</td></tr>";}
if (strlen($responsavel_legal) > 0) {echo "<tr><td>Responsável Legal</td><td>$responsavel_legal</td></tr>";}
if (strlen($logradouro_empresa_empregadora) > 0) {echo "<tr><td>Logradouro (empresa)</td><td>$logradouro_empresa_empregadora</td></tr>";}
if (strlen($bairro_empresa_empregadora) > 0) {echo "<tr><td>Bairro (empresa)</td><td>$bairro_empresa_empregadora</td></tr>";}
if (strlen($numero_empresa_empregadora) > 0) {echo "<tr><td>Número (empresa)</td><td>$numero_empresa_empregadora</td></tr>";}
if (strlen($cep_empresa_empregadora) > 0) {echo "<tr><td>CEP (empresa)</td><td>$cep_empresa_empregadora</td></tr>";}
if (strlen($municipio_txt_empresa_empregadora) > 0) {echo "<tr><td>CEP</td><td>$municipio_txt_empresa_empregadora</td></tr>";}

    $id_municipio_empresa_empregadora = (stripslashes($row[id_municipio_empresa_empregadora]));
    if ($id_municipio_empresa_empregadora != 0 && $id_municipio_empresa_empregadora != '') {
        echo "<tr><td>Municipio (empresa)</td><td>".$municipio_nome[$id_municipio_empresa_empregadora]."</td></tr>";
    }

if (strlen($contato_empresa_empregadora) > 0) {echo "<tr><td>Contato (empresa)</td><td>$contato_empresa_empregadora</td></tr>";}
if (strlen($email_empresa_empregadora) > 0) {echo "<tr><td>Email (empresa)</td><td>$email_empresa_empregadora</td></tr>";}
if (strlen($registro_exercito_empresa_empregadora) > 0) {echo "<tr><td>Registro da Empresa no Exército</td><td>$registro_exercito_empresa_empregadora</td></tr>";}
if (strlen($numero_registro_empresa_empregadora) > 0) {echo "<tr><td>Número do registro</td><td>$numero_registro_empresa_empregadora</td></tr>";}
if (strlen($arma_especie) > 0) {echo "<tr><td>Arma/Espécie</td><td>$arma_especie</td></tr>";}
if (strlen($arma_modelo) > 0) {echo "<tr><td>Arma/Modelo</td><td>$arma_modelo</td></tr>";}
if (strlen($arma_registro) > 0) {echo "<tr><td>Arma/Registro</td><td>$arma_registro</td></tr>";}
if (strlen($arma_marca) > 0) {echo "<tr><td>Arma/Marca</td><td>$arma_marca</td></tr>";}
if (strlen($arma_calibre) > 0) {echo "<tr><td>Arma/Calibre</td><td>$arma_calibre</td></tr>";}
if (strlen($arma_numero_serie) > 0) {echo "<tr><td>Arma/Número de Série</td><td>$arma_numero_serie</td></tr>";}
if (strlen($arma_numero_sigma) > 0) {echo "<tr><td>Arma/Sigma</td><td>$arma_numero_sigma</td></tr>";}
if (strlen($arma_numero_sinarm) > 0) {echo "<tr><td>Arma/Sinarm</td><td>$arma_numero_sinarm</td></tr>";}
 


  ?>
    </tbody>
    </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">FECHAR</button>

      </div>
    </div>
  </div>
</div>
<?php

echo "</h2>";

$f->fecha();


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

//$f -> abre_form("pedidos.painel.concluir.php?id_pedido=$id_pedido");    
    echo "<div style = 'display: block; width: 100%; height: 600px; overflow-y: scroll;'>";
    echo "<table class='table table-bordered'  >";
    echo "<thead>";
      echo "<tr>";

      echo "<th scope='col'>Documento</th>";

      echo "</tr>";
    echo "</thead>";
    echo "<tbody style = 'border: 0px;'>";
    
    // primeiro documento é o da carteira.
    echo "<tr>"; 
    echo "<td>";
    echo "<a href = '#' onClick='showPdf(\"carteiras/$arquivo\");' style = 'font-size:12px;' data-toggle='tooltip' >Carteira - Documento Gerado</a>";
    echo "</td>";
    echo "</tr>";

    $arquivo_inicial = $arquivo;
  
    $query = "select * from tb_carteiras_arquivos where id_carteira = ".$id_carteira."  order by id asc";
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++) 
        {
            $row = mysqli_fetch_array($result);
            $nome_arquivo =  trim(stripslashes($row[arquivo]));
            $id_documento_tipo =  (stripslashes($row[id_documento_tipo]));

            echo "<tr>"; 
                echo "<td>";
                echo "<a href = '#' onClick='showPdf(\"carteiras/$nome_arquivo\");' style = 'font-size:12px;' data-toggle='tooltip' >".$doc_nomes[$id_documento_tipo]." </a>";
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

    echo "<object data='carteiras/$arquivo_inicial' type='application/pdf' style = 'width:100%; height:680px;'><embed src='carteiras/$arquivo_inicial' type='application/pdf' /></object>";

    $f -> fecha();
    


//3rtyt8fe4sj3jh8dytailz6cerxrixugjuom9f3l.pdf*Contrato Social; 3rtyt8fe4sj3jh8dytailz6cerxrixugjuom9f3l.pdf*Alvará de Funcionamento emitido pela Prefeitura Local; 3rtyt8fe4sj3jh8dytailz6cerxrixugjuom9f3l.pdf*Autorização expedida pela Vigilância Sanitária, ou protocolo; 


$f -> fecha_card();

$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();


?>

