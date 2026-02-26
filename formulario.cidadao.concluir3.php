<?php
header("Cache-Control: no-cache, no-store, must-revalidate, max-age=0, s-maxage=0, proxy-revalidate, private");
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
header_remove("ETag");
header_remove("Last-Modified");
session_start();
include "../fiscaliza/classes/classe.forms.php";
$f = new Forms();
include "mysql.formulario.cidadao.php";
require_once '../fiscaliza/htmlpurifier/HTMLPurifier.standalone.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
include "../fiscaliza/interface_cidadao/header.html";



?>

<!doctype html>
<html lang="pt">
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>

    <title>FORMULÁRIO JOGOS</title>
</head>


<body>
<header class="bg-image-full" style="background-image: url('topo-fiscaliza.jpg'); padding: 1px;"> 
    <div class="text-center my-5">
        <a href = 'index.php'><img src = 'brasao.png' width = '80'></a> <br><h1 class="text-white fs-3 fw-bolder">Polícia Civil de Santa Catarina</h1>
        <p class="text-white-50 mb-0"><STRONG>SISTEMA DE SOLICITAÇÃO DE ALVARÁS POLICIAIS</STRONG></p>
    </div>
</header>
<div class="container theme-showcase" role="main" style = 'display: table; min-height:500px; padding-top: 36px; padding-bottom:48px;'>



<?php
if(isset($_SESSION['cidadao_nome'])){
    echo "<div style = 'display: block; width:100%; text-align:right;'><a href = 'logout.php' class = 'btn btn-primary'>Sair do sistema</a></div>";
    echo "<h4  class='policia'>Bem-vindo(a): ".$_SESSION['cidadao_nome']."<h4>";
    
}else{
    echo "<BR><BR><h2  class='policia'>ATENÇÃO<h2>";
    echo "<h3  class='policia'>Para realizar o pedido de Alvará, é necessário estar logado no E-gov.</h3><BR>";
    echo "<a HREF = 'https://getin.pc.sc.gov.br/login_fiscaliza' class = 'btn btn-primary'>Entrar com gov.br</a>";
    exit();

}
?>

      <h1  class='policia'>Formulário do Cidadão</h1>
        <br>
        <br>

<?php


// recebe novamente todas as variáveis do formulário
$base = "abcdefghijklmnopqrstuvxywz23456789abcdefghijklmnopqrstuvxywz23456789abcdefghijklmnopqrstuvxywz23456789abcdefghijklmnopqrstuvxywz23456789abcdefghijklmnopqrstuvxywz23456789";

$divisor = "*";

// busca os nomes dos documentos, para gravar os arquivos no BD

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
//$id_documento = (int)$_POST[id_documento];

// VAI BUSCAR OS DADOS PROVISÓRIOS, COM BASE NO CODE


$str_ref = $f->limpa_variavel($_GET[code], 9, $purifier);

$str_ref = str_replace("a", "", $str_ref);
$str_ref = str_replace("e", "", $str_ref);
$str_ref = str_replace("i", "", $str_ref);
$str_ref = str_replace("o", "", $str_ref);
$str_ref = str_replace("u", "", $str_ref);

if (strlen($str_ref) != 8) {
     
    // HOUVE ALTERAÇÃO MANUAL DO CÓDIGO - ABORTA TUDO.

    ECHO "<H1  class='policia'>ERRO NA CODIFICAÇÃO DO PEDIDO.</H1>";
    exit();
}

// busca todos os dados do pedido.
$query = "select * from tb_pedidos_prev where str_ref = '$str_ref' LIMIT 1";
$result=mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
$data_pedido = $row[data_pedido];
$id_documento = $row[tipo_pedido];
$nome_estabelecimento= ($row[nome_estabelecimento]);
$razao_social= ($row[razao_social]);
$id_atividade_ref= ($row[id_atividade_ref]);
$id_area_vistoria= ($row[id_area_vistoria]);
$cnpj= ($row[cnpj]);
$endereco_rua= ($row[endereco_rua]);
$endereco_numero= ($row[endereco_numero]);
$endereco_bairro= ($row[endereco_bairro]);
$endereco_cep= ($row[endereco_cep]);
$id_municipio= ($row[id_municipio]);
$telefone_fixo= ($row[telefone_fixo]);
$telefone_celular= ($row[telefone_celular]);
$email= ($row[email]);
$nome_proprietario= ($row[nome_proprietario]);
$obs_cidadao= ($row[obs_cidadao]);
$cidadao_nome= ($row[cidadao_nome]);
$cidadao_cpf= ($row[cidadao_cpf]);
$requerente_nome= ($row[requerente_nome]);
$requerente_data_nasc= ($row[requerente_data_nasc]);
$requerente_cpf= ($row[requerente_cpf]);
$requerente_endereco= ($row[requerente_endereco]);
$requerente_telefone= ($row[requerente_telefone]);
$requerente_email= ($row[requerente_email]);
$documentos_extras_str= ($row[documentos_extras_str]);

// ESSA ETAPA EXTRA É SOMENTE PARA EVITAR PROBLEMA COM ASPAS SIMPLES, QUE GEROU ERRO NO INCLUDE
$nome_estabelecimento  = $f->limpa_variavel($nome_estabelecimento, 1000, $purifier);
$razao_social  = $f->limpa_variavel($razao_social, 1000, $purifier);
$endereco_rua  = $f->limpa_variavel($endereco_rua, 1000, $purifier);
$endereco_numero  = $f->limpa_variavel($endereco_numero, 1000, $purifier);
$endereco_bairro  = $f->limpa_variavel($endereco_bairro, 1000, $purifier);
$email  = $f->limpa_variavel($email, 1000, $purifier);
$nome_proprietario  = $f->limpa_variavel($nome_proprietario, 1000, $purifier);
$obs_cidadao  = $f->limpa_variavel($obs_cidadao, 1000, $purifier);
$cidadao_nome  = $f->limpa_variavel($cidadao_nome, 1000, $purifier);
$requerente_nome  = $f->limpa_variavel($requerente_nome, 1000, $purifier);
$requerente_endereco  = $f->limpa_variavel($requerente_endereco, 1000, $purifier);
$requerente_telefone  = $f->limpa_variavel($requerente_telefone, 1000, $purifier);
$requerente_email  = $f->limpa_variavel($requerente_email, 1000, $purifier);


$query = "select nome from tb_alvaras_tipo where id = $id_documento";
$result=mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
$nome_alvara = (stripslashes($row[nome]));

// busca o nome do ramo de atividade:

/*
$nome_estabelecimento  = $f->limpa_variavel($_POST['nome_estabelecimento'], 90, $purifier);

$razao_social  = $f->limpa_variavel($_POST['razao_social'], 90, $purifier);
$cnpj  = $f->limpa_variavel($_POST['cnpj'], 20, $purifier);
$cnpj = preg_replace("/[^0-9]/", "",$cnpj);
// $id_ramo_atividade = $f->limpa_variavel($_POST['id_ramo_atividade'], 250, $purifier);//(int)$_POST[id_ramo_atividade];
$id_atividade_ref = (int)$_POST[id_ramo_atividade];
$endereco_rua  = $f->limpa_variavel($_POST['endereco_rua'], 90, $purifier);
$endereco_numero  = $f->limpa_variavel($_POST['endereco_numero'], 40, $purifier);
$endereco_bairro  = $f->limpa_variavel($_POST['endereco_bairro'], 90, $purifier);
$endereco_cep  = $f->limpa_variavel($_POST['endereco_cep'], 12, $purifier);
$id_municipio = (int)$_POST[id_municipio];
$telefone_fixo  = $f->limpa_variavel($_POST['telefone_fixo'], 90, $purifier);
$telefone_celular  = $f->limpa_variavel($_POST['telefone_celular'], 90, $purifier);
$email  = $f->limpa_variavel($_POST['email'], 90, $purifier);
$nome_proprietario  = $f->limpa_variavel($_POST['nome_proprietario'], 90, $purifier);

$id_documento = (int)$_POST[id_documento];
$obs_cidadao = $f->limpa_variavel($_POST['obs_cidadao'], 1000, $purifier);
$regras_txt = $f->limpa_variavel($_POST['regras_txt'], 1000, $purifier);
$obs_cidadao = $regras_txt."<br><br>".$obs_cidadao;
$id_area_vistoria = (int)$_POST[id_area_vistoria];


$requerente_nome  = $f->limpa_variavel($_POST['requerente_nome'], 160, $purifier);
$requerente_data_nasc  = $f->limpa_variavel($_POST['requerente_data_nasc'], 12, $purifier);
$requerente_cpf  = $f->limpa_variavel($_POST['requerente_cpf'], 16, $purifier);
$requerente_endereco  = $f->limpa_variavel($_POST['requerente_endereco'], 160, $purifier);
$requerente_telefone  = $f->limpa_variavel($_POST['requerente_telefone'], 160, $purifier);
$requerente_email  = $f->limpa_variavel($_POST['requerente_email'], 160, $purifier);
*/



$pedido_dispensa  = $f->limpa_variavel($_POST['pedido_dispensa'], 1000, $purifier);

$valor_taxa  = $f->limpa_variavel($_POST['valor_taxa'], 1000, $purifier);

if(isset($_POST[n_bombas])){
    $n_bombas  = $f->limpa_variavel($_POST['n_bombas'], 1000, $purifier);
}

// FAZ O CADASTRO DO PEDIDO, PARA GERAR O ID DO PEDIDO E PREENCHER A TABELA DE ARQUIVOS



     

    // os arquivos já estão salvos na pasta provisória
    $data_pedido = time();

    include "../fiscaliza/classes/gera.hash.cidadao.php";
  
   // echo "<h3>hash -> $hash</h3>";
   // echo "<h3>senha -> $senha_cidadao</h3>";

   $query = "select cnae, taxa_valor from tb_cnaes where id = ".$id_atividade_ref;
   $result=mysqli_query($link, $query);
   $row = mysqli_fetch_array($result);
   $id_ramo_atividade =  (stripslashes($row[cnae]));
   $taxa_valor =  $row[taxa_valor]; // para guardar no formato textual e não precisar buscar em toda página
   if($taxa_valor == ''){
    $taxa_valor = 0;
   }

   if(isset($_POST[taxa_valor])){
    $taxa_valor = trim($_POST[taxa_valor]);
   }
   


    $historico = "Pedido cadastrado pelo responsável pelo CNPJ $cnpj em ".date("d/m/Y H:i", $data_pedido)."<br><hr><br>";

    $status = 0;
    $ultima_movimentacao = $data_pedido;

    $cidadao_nome = $f->limpa_variavel($_SESSION['cidadao_nome'], 49, $purifier);
    $cidadao_cpf = $f->limpa_variavel($_SESSION['cidadao_cpf'], 11, $purifier);

     // gera o texto html com o resumo do pedido // vai ter que criar em uma tabela separada, para fazer o insert.
    = 1;
    $query = "insert into tb_cidadao_pedidos (data_pedido, tipo_pedido, status, ultima_movimentacao, nome_estabelecimento, razao_social, id_ramo_atividade, id_atividade_ref, id_area_vistoria,cnpj, endereco_rua, endereco_numero, endereco_bairro, endereco_cep, id_municipio, telefone_fixo, telefone_celular, email, nome_proprietario, obs_cidadao, historico, registro_migrado, senha, hash, valor_taxa, cidadao_nome, cidadao_cpf, requerente_nome, requerente_data_nasc, requerente_cpf, requerente_endereco, requerente_telefone, requerente_email, pedido_dispensa, pagina_resumo) values ($data_pedido, $id_documento, $status, $ultima_movimentacao, '$nome_estabelecimento', '$razao_social', '$id_ramo_atividade', $id_atividade_ref, $id_area_vistoria, '$cnpj', '$endereco_rua', '$endereco_numero', '$endereco_bairro', '$endereco_cep', $id_municipio, '$telefone_fixo', '$telefone_celular', '$email', '$nome_proprietario', '$obs_cidadao','$historico', '$registro_migrado','$senha_cidadao', '$hash', '$taxa_valor', '$cidadao_nome', '$cidadao_cpf', '$requerente_nome', '$requerente_data_nasc', '$requerente_cpf', '$requerente_endereco', '$requerente_telefone', '$requerente_email', '$pedido_dispensa', '$pagina_resumo')";
    //echo $query;
    $result = mysqli_query($link, $query);
    if(!$result)
    {   
        echo " <div class='alert alert-danger' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'  class='policia'>NÃO FOI POSSÍVEL CONCLUIR O CADASTRO<br>$query<br> </h4>".mysqli_error($link);
        echo "</div>";
    }else{
        echo " <div class='alert alert-info' role='alert'>";
        echo " <h4  align = 'center'  class='policia'>CADASTRO CONCLUÍDO COM SUCESSO</h4>";
        echo " <h5  align = 'center'  class='policia'>Prezado Cidadão, seus documentos serão analisados.</h5>";
        echo " <h5  align = 'center'  class='policia'>Foi enviado um email para a conta informada, para acompanhamento do processo.</h5>";
        echo "<br><a href = 'index.php' class = 'policia btn btn-primary' style = 'width: 100%;'>VOLTAR PARA TELA INICIAL, PARA NOVO REQUERIMENTO</a>";
        $id_pedido = mysqli_insert_id($link);
        echo "</div>";

    
        // busca o valor do código, busca o requerimento, e faz o cadastro na tabela de arquivos.

       // $str_ref  = $f->limpa_variavel($_POST['str_ref'], 7, $purifier);

      /*  $query2 = "select arquivos from tb_requerimentos_prov where str_ref = '$str_ref'";
        //echo $query2;
        $result2=mysqli_query($link, $query2);
        $row2 = mysqli_fetch_array($result2);
        $nome_final = $row2[arquivos];

        $query2 = "insert into tb_cidadao_arquivos (id_pedido, data_carregamento, id_documento_tipo, arquivo, aprovado) values ($id_pedido, $data_pedido, '8', '$nome_final', 'A')";
        $result2 = mysqli_query($link, $query2);
        if(!$result2){
            echo "<h1>PAU -> $query2</h1>";
        }

        $query2 = "select arquivos from tb_procuracao_prov where str_ref = '$str_ref'";
        //echo $query2;
        $result2=mysqli_query($link, $query2);
        $num2 = mysqli_num_rows($result2);
        if($num2 != 0){
            $row2 = mysqli_fetch_array($result2);
            $nome_final = $row2[arquivos];

            $query2 = "insert into tb_cidadao_arquivos (id_pedido, data_carregamento, id_documento_tipo, arquivo, aprovado) values ($id_pedido, $data_pedido, '73', '$nome_final', 'A')";
            $result2 = mysqli_query($link, $query2);
            if(!$result2){
                echo "<h1>PAU -> $query2</h1>";
            }*/



        $query2 = "select arquivos, id_documento_tipo from tb_arquivos_prov where str_ref = '$str_ref'";
      //  echo $query2;
        $result2=mysqli_query($link, $query2);
        $num2 = mysqli_num_rows($result2);
        for($i2=0;$i2<$num2;$i2++) {
            $row2 = mysqli_fetch_array($result2);
            $nome_final = $row2[arquivos];
            $id_documento_tipo = $row2[id_documento_tipo];

            $query3 = "insert into tb_cidadao_arquivos (id_pedido, data_carregamento, id_documento_tipo, arquivo, aprovado) values ($id_pedido, $data_pedido, '$id_documento_tipo', '$nome_final', 'A')";
            $result3 = mysqli_query($link, $query3);
            if(!$result3){
                echo "<h1>ERROR -> $query3</h1>";
            }


        }
        


        // FAZ A RECEPÇÃO E INCLUSÃO DOS CAMPOS ESPECIAIS PARA ESTE DOCUMENTO
        
        $query2 = "select * from tb_campos_especiais_prev where str_ref = '$str_ref'";
        $result2=mysqli_query($link, $query2);
        $num2 = mysqli_num_rows($result2);
        for($i2=0;$i2<$num2;$i2++) 
                {
                    $row2 = mysqli_fetch_array($result2);
                    $campo_nome = $row2[campo_nome];
                    $campo_value = $row2[campo_value];

               //     $campo_valor_insere  = $f->limpa_variavel($_POST[$campo_nome], 250, $purifier);
                   
                    $query3 = "INSERT INTO `tb_campos_especiais`(`id_alvara`, `id_pedido`, `campo_nome`, `campo_value`, `data_cadastro`) VALUES ($id_documento, $id_pedido,'$campo_nome','$campo_value', $data_pedido)";
                    $result3 = mysqli_query($link, $query3);
                    if(!$result3)
                    {   
                        echo " <div class='alert alert-danger' role='alert'>";
                        echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL CADASTRAR O CAMPO ESPECIAL<br>$query3<br> </h4>".mysqli_error($link);
                        echo "</div>";
                    }
                }

                include "gera.resumo.empresa.bd.php";

                $query = "insert into tb_empresa_resumo(id_pedido, resumo) values ($id_pedido, '$pagina_resumo')";
                $result = mysqli_query($link, $query);
                if(!$result)
                {   
                    echo " <div class='alert alert-danger' role='alert'>";
                    echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL GRAVAR O RESUMO<br>$query<br> </h4>".mysqli_error($link);
                    echo "</div>";
                }

        // FAZ O DISPARO DOS DADOS DE AUTENTICAÇÃO PARA O EMAIL DO CIDADÃO, COM OS DADOS DA CONTA DE ACESSO.
     
        $c = $cnpj;
        $cnpj_login = $c[0].$c[1].".".$c[2].$c[3].$c[4].".".$c[5].$c[6].$c[7]."/".$c[8].$c[9].$c[10].$c[11]."-".$c[12].$c[13];

        $link_enviar = "https://sistemas.pc.sc.gov.br/cidadao/login.cidadao.php?token=$hash";
        $titulo_email = "Pedido de Alvará - CNPJ ".$cnpj_login." - Login e Senha";
        $mensagem = "<html><body>Prezado(a) Usuario(a),";
        $mensagem .= "<br>Sua solicitação de concessão de alvará  foi remetida à Unidade Policial responsável, e será analisada em breve.";
        $mensagem .= "<br>Você pode acompanhar o processo de autorização e geração do alvará a partir <a href = '$link_enviar'>deste link</a> <br>";
        $mensagem .= "<br>O link é exclusivo para esta solicitação de alvará.";
        $mensagem .= "<br>Você também deverá informar os seguintes dados:";
        $mensagem .= "<br>Login: ".$cnpj_login;
        $mensagem .= "<br>senha: ".$senha_cidadao;
        $mensagem .= "</body></html>";
        
    

        $envia = exec("curl -d 'to=$email&subject=$titulo_email&html=$mensagem' -X POST https://getin.pc.sc.gov.br/sendmail/");
        if (!$envia) {
            $f->msg("ERRO NO ENVIO PARA O EMAIL<br> curl -d 'to=$email&subject=$titulo_email&body=$mensagem' -X POST https://getin.pc.sc.gov.br/sendmail/ ","danger");
        }else{
          //  $f->msg("$mensagem_tela","info");
        //   echo "<a  class = 'btn btn-primary' href = 'login.php'>voltar para tela de login</a>";
        }
        
    //    echo "<a href = 'pagina.estabelecimento.php?id_estabelecimento=$id_estabelecimento' class = 'btn btn-primary' style = 'width: 100%; margin-top: 24px;'>IR PARA A PÁGINA DO ESTABELECIMENTO</a>";
        
    }


// faz a geração da folha de rosto.

//include "gera.resumo.empresa.php";

// busca os nomes dos documentos

$documentos_nome = array();
$query = "select id, nome from tb_documentos_tipo order by id desc";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for($i=0;$i<$num;$i++) 
    {
        $row = mysqli_fetch_array($result);
        $id = $row[id];
        $nome =  (stripslashes($row[nome]));
        $documentos_nome[$id] = $nome;
    }

//  PARA PODER CARREGAR TAMBÉM OS ARQUIVOS DE REGRAS ESPECIAIS NO MESMO PROCEDIMENTO, VOU FAZER UM MERGE DE ARRAYS, ASSIM GARANTO QUE ELES VEM NA MESMA ORDEM

$query = "select id_documento_tipo from tb_documentos_requeridos where id_alvara = $id_documento";
$array_documento_tipo_base = array();
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
//echo "<h2>$query --> $num</h2>";
for($i=0;$i<$num;$i++) 
    {
        $row = mysqli_fetch_array($result);
        $array_documento_tipo_base[] = $row[id_documento_tipo];
    }

    $array_prov = explode(',', $documentos_extras_str);
    $array_documento_tipo = array_merge($array_documento_tipo_base, $array_prov);

   // ECHO "<BR>3 -> "; print_r($array_documento_tipo);

    for ($i=0; $i <= count($array_documento_tipo); $i++) { 
        # code...
    
        $id_documento_tipo = $array_documento_tipo[$i];
        
      //  echo "<br>--> id documento -> $id_documento_tipo";
        
        $a =  str_shuffle($base);
        $str = substr($a, 0, 40);
       $nome_final = $str.".pdf";
       

       if (isset($_FILES["arquivo_$id_documento_tipo"]["name"])) {
       // echo " ==>".$id_documento_tipo;
        $arquivo = $_FILES["arquivo_$id_documento_tipo"]["tmp_name"];
        $arquivo_nome = $documentos_nome[$id_documento_tipo];//$_FILES["arquivo_$i"]["name"];
        //   echo "<hr>2";
        $d1 = $_FILES["arquivo_$id_documento_tipo"]["type"];
        $d2 = explode("/", $d1);
        $extensao = $d2[1];

            if($extensao == 'pdf'){

                $destino = "../fiscaliza/arquivos_cidadao_prev/".$nome_final;

                if(!move_uploaded_file($arquivo, $destino)){
                echo "<p><b>Não foi possível carregar o arquivo! - $arquivo_nome</b></p>".$_FILES["arquivo"]["tmp_name"];
                }else{

                 //   $id_documento_tipo_insere = (int)$_POST["id_documento_tipo_ref_$i"];

                    $query2 = "insert into tb_cidadao_arquivos (id_pedido, data_carregamento, id_documento_tipo, arquivo, aprovado) values ($id_pedido, $data_pedido, '$id_documento_tipo', '$nome_final', 'A')";
                 //   echo "<hr>$i -> $query2";
                    $result2 = mysqli_query($link, $query2);
                    if(!$result2){
                        echo "<h1>ERRO -> $query2</h1>";
                    }
                }
            }else{
                echo " <div class='alert alert-danger' role='alert'>";
                echo "<h2>DOCUMENTO NÃO ESTÁ EM FORMATO PDF</h2>";
                echo "<h1>INCLUSÃO NEGADA</h1>";
                echo "</div>"; 
            }
            
        }


    }
      
  //  echo "<h1>nome final: $str_arquivos</h1>";



?>
<div style = 'width: 100%; display: block; text-align: center;'><p>
<iframe width="560" height="315" src="https://www.youtube.com/embed/4lF-BSOexNA?si=amBbMLFOl67zMa6-" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
</p></div>
</div></div>
<script src="../fiscaliza/assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="../fiscaliza/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>

    <script src="../fiscaliza/assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <script src="../fiscaliza/assets/libs/js/main-js.js"></script>
    <!--<script src="assets/vendor/inputmask/js/jquery.inputmask.bundle.js"></script> -->
    <script src="../fiscaliza/assets/vendor/parsley/parsley.js"></script>
    <script src="../fiscaliza/formularios.js"></script>
    <!--<script src="js/show.js"></script>-->
    <script src="../fiscaliza/js/jquery.inputmask.bundle.js"></script>
    <script src="../fiscaliza/js/normas_corregedoria.js"></script>
    <script src="../fiscaliza/js_dinamico/corregedores_tramitacao.js"></script>
    <script src="../fiscaliza/s/show.js"></script>

    <script>
    // esse código existe porque o required não está funcionando direto nos select.
    
    document.getElementById("id_ramo_atividade").required = true;
    document.getElementById("id_municipio").required = true;

    </script>
    
    <script>
    $(function(e) {
        "use strict";
        $(".cc-inputmask").inputmask("999-999-999-99"),
        $(".cnpj-inputmask").inputmask("99-999-999/9999-99"),
        $(".date-inputmask").inputmask("99/99/9999"),
        $(".cpf-inputmask").inputmask("999.999.999-99"),
        $(".horario-inputmask").inputmask("99:99"),
        $(".cep-inputmask").inputmask("99.999-999"),
        $(".telefone_fixo-inputmask").inputmask("(99) 9999-9999"),
        $(".telefone_celular-inputmask").inputmask("(99) 99999-9999"),
            $(".phone-inputmask").inputmask("(999) 999-9999"),
            $(".international-inputmask").inputmask("+9(999)999-9999"),
            $(".xphone-inputmask").inputmask("(999) 999-9999 / x999999"),
            $(".purchase-inputmask").inputmask("aaaa 9999-****"),
            $(".cc-inputmask").inputmask("9999 9999 9999 9999"),
            $(".ssn-inputmask").inputmask("999-99-9999"),
            $(".isbn-inputmask").inputmask("999-99-999-9999-9"),
            $(".currency-inputmask").inputmask("$9999"),
            $(".percentage-inputmask").inputmask("99%"),
            $(".decimal-inputmask").inputmask({
                alias: "decimal",
                radixPoint: "."
            }),

            $(".email-inputmask").inputmask({
                mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[*{2,6}][*{1,2}].*{1,}[.*{2,6}][.*{1,2}]",
                greedy: !1,
                onBeforePaste: function(n, a) {
                    return (e = e.toLowerCase()).replace("mailto:", "")
                },
                definitions: {
                    "*": {
                        validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~/-]",
                        cardinality: 1,
                        casing: "lower"
                    }
                }
            })
    });
    </script>
    
    <?php

include "../fiscaliza/interface_cidadao/footer.html";

?>