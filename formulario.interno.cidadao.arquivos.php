<?php
header("Cache-Control: no-cache, no-store, must-revalidate, max-age=0, s-maxage=0, proxy-revalidate, private");
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
header_remove("ETag");
header_remove("Last-Modified");
session_start();

if(!isset($_SESSION["usuario_fis_cpf"]))
{
  header('Location: https://integra.pc.sc.gov.br/');
  exit();
}

include "classes/classe.forms.php";
include "mysql.conecta.rep.php";
include "classes/class.html.php";
$f = new Forms();

require_once 'htmlpurifier/HTMLPurifier.standalone.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$header=new Header_adm_WEB(); 
$a=new Menu_adm($link);
$nome_pagina= "Bem-Vindo";
$a=new Abre_titulo();
$a->titulo_pagina($nome_pagina);
$f -> abre_card(12);
echo "<h1>Atendimento ao Cidadão - Solicitação Interna de Alvará</h1><hr><br>";
$f->abre(12);

$str_ref = $f->limpa_variavel($_GET[code], 9, $purifier);

$str_ref = str_replace("a", "", $str_ref);
$str_ref = str_replace("e", "", $str_ref);
$str_ref = str_replace("i", "", $str_ref);
$str_ref = str_replace("o", "", $str_ref);
$str_ref = str_replace("u", "", $str_ref);

if (strlen($str_ref) != 8) {
    
    // HOUVE ALTERAÇÃO MANUAL DO CÓDIGO - ABORTA TUDO.
    echo "<h1>$str_ref -> ".strlen($str_ref)."</h1>";
    ECHO "<H1>ERRO NA CODIFICAÇÃO DO PEDIDO.</H1>";
    exit();
}


$query = "select id_area_vistoria, tipo_pedido, requerente_responsavel, id_atividade_ref, documentos_extras_str from tb_pedidos_prev where str_ref = '$str_ref' LIMIT 1";
$result=mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
$id_documento = stripslashes($row[tipo_pedido]);
$id_area_vistoria = stripslashes($row[id_area_vistoria]);
$requerente_responsavel = $row[requerente_responsavel];
$id_ramo_atividade = $row[id_atividade_ref];
$documentos_extras_str = stripslashes($row[documentos_extras_str]);

// primeira etapa: recebe o REQUERIMENTO PREENCHIDO e faz a inclusão em tabela provisória, para na página seguinte vincular ao pedido, se o processo de cadastro for concluído.


if (isset($_FILES["requerimento_preenchido"]["name"])) {
   
    // echo " ==>".$id_documento_tipo;
     $arquivo = $_FILES["requerimento_preenchido"]["tmp_name"];
     $arquivo_nome = 'requerimento_preenchido Preenchido';//$_FILES["arquivo_$i"]["name"];
     //   echo "<hr>2";
     $d1 = $_FILES["requerimento_preenchido"]["type"];
     $d2 = explode("/", $d1);
     $extensao = $d2[1];

         if($extensao == 'pdf'){

            $base = "abcdefghjprstuvxywz2345678923456789";
            $a =  str_shuffle($base);
            $str = substr($a, 0, 40);
            $nome_final = $str.".pdf";

             $destino = "../fiscaliza/arquivos_cidadao_prev/".$nome_final;

             if(!move_uploaded_file($arquivo, $destino)){
             echo "<p><b>Não foi possível carregar o arquivo!</b></p>".$_FILES["requerimento_preenchido"]["tmp_name"];
             }else{
                $id_documento_tipo = 8;
                 $query2 = "insert into tb_arquivos_prov (str_ref, arquivos, id_documento_tipo) values ('$str_ref',  '$nome_final', $id_documento_tipo)";
                 $result2 = mysqli_query($link, $query2);
                 if(!$result2){
                     echo "<h1>ERROR -> $query2</h1>";
                 }
             }
         }else{
             echo " <div class='alert alert-danger' role='alert'>";
             echo "<h2>DOCUMENTO NÃO ESTÁ EM FORMATO PDF</h2>";
             echo "<h1>INCLUSÃO NEGADA</h1>";
             echo "</div>"; 
         }
         
     }

     // VERIFICA SE HOUVE O CARREGAMENTO DA PROCURAÇÃO

     if (isset($_FILES["procuracao"]["name"])) {

        
        //echo "<hr><h1>procuração ----> ".$_FILES["procuracao"]["name"]."</h1><hr>";
 
        // echo " ==>".$id_documento_tipo;
         $arquivo = $_FILES["procuracao"]["tmp_name"];
       //  $arquivo_nome = 'requerimento_preenchido Preenchido';//$_FILES["arquivo_$i"]["name"];
         //   echo "<hr>2";
         $d1 = $_FILES["procuracao"]["type"];
         $d2 = explode("/", $d1);
         $extensao = $d2[1];
    
             if($extensao == 'pdf'){
    
                $base = "abcdefghjprstuvxywz2345678923456789";
                $a =  str_shuffle($base);
                $str = substr($a, 0, 40);
                $nome_final = $str.".pdf";
    
                 $destino = "../fiscaliza/arquivos_cidadao_prev/".$nome_final;
                 
                 if(!move_uploaded_file($arquivo, $destino)){
                 echo "<p><b>Não foi possível carregar o arquivo!</b></p>".$_FILES["procuracao"]["tmp_name"];
                 }else{
                    $id_documento_tipo = 73;
                     $query2 = "insert into tb_arquivos_prov (str_ref, arquivos, id_documento_tipo) values ('$str_ref',  '$nome_final', $id_documento_tipo)";
                     $result2 = mysqli_query($link, $query2);
                     if(!$result2){
                         echo "<h1>ERROR -> $query2</h1>";
                     }
                 }
             }else{
                 echo " <div class='alert alert-danger' role='alert'>";
                 echo "<h2>DOCUMENTO NÃO ESTÁ EM FORMATO PDF</h2>";
                 echo "<h1>INCLUSÃO NEGADA</h1>";
                 echo "</div>"; 
             }
             
         }

//$f -> abre_form("formulario.cidadao.concluir.php");
echo "<form action='formulario.interno.cidadao.concluir3.php?code=".$_GET[code]."' enctype='multipart/form-data' method='post' >";

// PROCEDIMENTO QUE VERIFICA OS CAMPOS OBRIGATÓRIOS, DE ACORDO COM QUE O USUÁRIO MARCOU NA PÁGINA ANTERIOR

// $regras_txt = '';

// $documentos_dispensados = array();
// $query = "select id_documento_tipo, regra from tb_regras_documentos_obrigatorios  where id_alvara = $id_documento";
// $result=mysqli_query($link, $query);
// $num = mysqli_num_rows($result);
// for($i=0;$i<$num;$i++) 
//         {
//             $row = mysqli_fetch_array($result);
//             $id_documento_tipo = $row[id_documento_tipo];
//             $regras_txt .= '\n\r<p>'.(stripslashes($row[regra]));

//             if($_POST['regra_'.$id_documento_tipo] == 0){
//                 $documentos_dispensados[] = $id_documento_tipo;
//                 $regras_txt .= ': NÃO</p>';
//             }else{
//                 $regras_txt .= ': SIM</p>';
//             }
//         }


// BUSCA A LISTA DOS DOCUMENTOS REFERIDOS PARA O TIPO INDICADO

// primeiro, busca os nomes dos documentos requeridos

$documento_nome = array();
$query = "select id, nome from tb_documentos_tipo";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for($i=0;$i<$num;$i++) 
    {
        $row = mysqli_fetch_array($result);
        $id = $row[id];
        $documento_nome[$id]= (stripslashes($row[nome]));
    }


    $query = "select nome, requer_dare from tb_alvaras_tipo where id = $id_documento";
  //  echo $query;
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $alvara_nome = (stripslashes($row[nome]));
    $requer_dare = $row[requer_dare];
    echo "<h2  class='policia'>Documentos obrigatórios para:<span style = 'font-weight: bolder'  class='policia'> $alvara_nome</span></h2>";
    echo "<div class='alert alert-danger' role='alert'>";
    echo "<h3>ATENÇÃO: os arquivos devem ser carregados exclusivamente no <span style = 'font-weight: bolder'  class='policia'>formato PDF</span></h3>";
    echo "</div>"; 


// RECEBE E CARREGA OS ARQUIVOS COM COMPROVANTE DO PAGAMENTO DE TAXAS.

function carrega_arquivo_avulso($arquivo, $arquivo_tipo, $id_documento_tipo, $str_ref, $link){

    //   echo "<hr>2";
    $d1 = $arquivo_tipo;//$_FILES["valor_taxa"]["type"];
    $d2 = explode("/", $d1);
    $extensao = $d2[1];

   // echo "<h1>----->$arquivo - $arquivo_tipo - $extensao</h1>";

        if($extensao == 'pdf'){

           $base = "abcdefghjprstuvxywz2345678923456789";
           $a =  str_shuffle($base);
           $str = substr($a, 0, 40);
           $documento_taxa = $str.".pdf";

            $destino = "arquivos_cidadao_prev/".$documento_taxa;

            if(!move_uploaded_file($arquivo, $destino)){
            echo "<p><b>Não foi possível carregar o arquivo - > $arquivo!</b> </p>";
            }
        else{

              // $query2 = "insert into tb_cidadao_arquivos (id_pedido, data_carregamento, id_documento_tipo, arquivo, aprovado) values ($id_pedido, $data_pedido, '71', '$documento_taxa', 'A')";
              $query2 = "insert into tb_arquivos_prov (str_ref, arquivos, id_documento_tipo) values ('$str_ref',  '$documento_taxa', $id_documento_tipo)";
              $result2 = mysqli_query($link, $query2);
               if(!$result2){
                   echo "<h1>ERRO -> $query2</h1>".mysqli_error($link);
               }
            }
        }else{
            echo " <div class='alert alert-danger' role='alert'>";
            echo "<h2>GUIA ESTADUAL NÃO ESTÁ EM FORMATO PDF</h2>";
            echo "<h1>INCLUSÃO NEGADA</h1>";
            echo "</div>"; 
        }

}

// id comprovante dare serviço principal - 81
// id comprovante dare taxa de vistoria - 82
if($requer_dare == 2){
    carrega_arquivo_avulso($_FILES["dare_principal_doc"]["tmp_name"], $_FILES["dare_principal_doc"]["type"],  84, $str_ref, $link);
    carrega_arquivo_avulso($_FILES["dare_principal_comprovante"]["tmp_name"], $_FILES["dare_principal_comprovante"]["type"],  81, $str_ref, $link);
    carrega_arquivo_avulso($_FILES["dare_vistoria_doc"]["tmp_name"], $_FILES["dare_vistoria_doc"]["type"],  86, $str_ref, $link);
    carrega_arquivo_avulso($_FILES["dare_vistoria_comprovante"]["tmp_name"], $_FILES["dare_vistoria_comprovante"]["type"],  82, $str_ref, $link);
}

if($requer_dare == 1){ // no caso de empresas de segurança, somente.
    carrega_arquivo_avulso($_FILES["documento_dare_certidao"]["tmp_name"], $_FILES["documento_dare_certidao"]["type"],  84, $str_ref, $link);
    carrega_arquivo_avulso($_FILES["dare_comprovante_certidao"]["tmp_name"], $_FILES["dare_comprovante_certidao"]["type"],  81, $str_ref, $link);
}
// ------------------------------------------------------------ tratamento da guia estadual

if (isset($_FILES["valor_taxa"]["name"])) {

     $arquivo = $_FILES["valor_taxa"]["tmp_name"];
     $arquivo_nome = 'Comprovante do pagamento de Tarifa do Alvará';//$_FILES["arquivo_$i"]["name"];
     //   echo "<hr>2";
     $d1 = $_FILES["valor_taxa"]["type"];
     $d2 = explode("/", $d1);
     $extensao = $d2[1];

         if($extensao == 'pdf'){

            $base = "abcdefghjprstuvxywz2345678923456789";
            $a =  str_shuffle($base);
            $str = substr($a, 0, 40);
            $documento_taxa = $str.".pdf";

             $destino = "../fiscaliza/arquivos_cidadao_prev/".$documento_taxa;

             if(!move_uploaded_file($arquivo, $destino)){
             echo "<p><b>Não foi possível carregar o arquivo!</b></p>".$_FILES["valor_taxa"]["tmp_name"];
             }
	     else{

               // $query2 = "insert into tb_cidadao_arquivos (id_pedido, data_carregamento, id_documento_tipo, arquivo, aprovado) values ($id_pedido, $data_pedido, '71', '$documento_taxa', 'A')";
               $id_documento_tipo = 71;
               $query2 = "insert into tb_arquivos_prov (str_ref, arquivos, id_documento_tipo) values ('$str_ref',  '$documento_taxa', $id_documento_tipo)";
               $result2 = mysqli_query($link, $query2);
                if(!$result2){
                    echo "<h1>ERRO -> $query2</h1>";
                }
             }
         }else{
             echo " <div class='alert alert-danger' role='alert'>";
             echo "<h2>GUIA ESTADUAL NÃO ESTÁ EM FORMATO PDF</h2>";
             echo "<h1>INCLUSÃO NEGADA</h1>";
             echo "</div>"; 
         }
         
     }

// ----------------------------------------------------------------- TRATAMENTO DO DOCUMENTO DE DARE


if (isset($_FILES["documento_dare"]["name"])) {

    $arquivo = $_FILES["documento_dare"]["tmp_name"];
    $arquivo_nome = 'Documento de DARE';//$_FILES["arquivo_$i"]["name"];
    //   echo "<hr>2";
    $d1 = $_FILES["documento_dare"]["type"];
    $d2 = explode("/", $d1);
    $extensao = $d2[1];

        if($extensao == 'pdf'){

           $base = "abcdefghjprstuvxywz2345678923456789";
           $a =  str_shuffle($base);
           $str = substr($a, 0, 40);
           $documento_taxa = $str.".pdf";

            $destino = "../fiscaliza/arquivos_cidadao_prev/".$documento_taxa;

            if(!move_uploaded_file($arquivo, $destino)){
            echo "<p><b>Não foi possível carregar o arquivo!</b></p>".$_FILES["documento_dare"]["tmp_name"];
            }
        else{

              // $query2 = "insert into tb_cidadao_arquivos (id_pedido, data_carregamento, id_documento_tipo, arquivo, aprovado) values ($id_pedido, $data_pedido, '71', '$documento_taxa', 'A')";
              $id_documento_tipo = 15;
              $query2 = "insert into tb_arquivos_prov (str_ref, arquivos, id_documento_tipo) values ('$str_ref',  '$documento_taxa', $id_documento_tipo)";
              $result2 = mysqli_query($link, $query2);
               if(!$result2){
                   echo "<h1>ERRO -> $query2</h1>";
               }
            }
        }else{
            echo " <div class='alert alert-danger' role='alert'>";
            echo "<h2>DARE NÃO ESTÁ EM FORMATO PDF</h2>";
            echo "<h1>INCLUSÃO NEGADA</h1>";
            echo "</div>"; 
        }
        
    }
     // ------------------------------------------------------------ tratamento da guia estadual

if (isset($_FILES["comprovante_vistoria"]["name"])) {


    // $str = "abcdefghjprstuvxywz2345678923456789";
    // $str_ref = '';      
    // for ($i=0; $i < 6; $i++) {                 
    //    $a = str_shuffle($str);
    //    $str_ref .= $a[0];
    // }

    // echo " ==>".$id_documento_tipo;
     $arquivo = $_FILES["comprovante_vistoria"]["tmp_name"];
     $arquivo_nome = 'Comprovante do pagamento da taxa de vistoria policial';//$_FILES["arquivo_$i"]["name"];
     //   echo "<hr>2";
     $d1 = $_FILES["comprovante_vistoria"]["type"];
     $d2 = explode("/", $d1);
     $extensao = $d2[1];

         if($extensao == 'pdf'){

            $base = "abcdefghjprstuvxywz2345678923456789";
            $a =  str_shuffle($base);
            $str = substr($a, 0, 40);
            $comprovante_taxa = $str.".pdf";

             $destino = "../fiscaliza/arquivos_cidadao_prev/".$comprovante_taxa;

             if(!move_uploaded_file($arquivo, $destino)){
             echo "<p><b>Não foi possível carregar o arquivo!</b></p>".$_FILES["comprovante_vistoria"]["tmp_name"];
             }
	     else{

                //$query2 = "insert into tb_cidadao_arquivos (id_pedido, data_carregamento, id_documento_tipo, arquivo, aprovado) values ($id_pedido, $data_pedido, '72', '$comprovante_taxa', 'A')";
                $id_documento_tipo = 72;
                $query2 = "insert into tb_arquivos_prov (str_ref, arquivos, id_documento_tipo) values ('$str_ref',  '$comprovante_taxa', $id_documento_tipo)";
                $result2 = mysqli_query($link, $query2);
                if(!$result2){
                    echo "<h1>ERRO -> $query2</h1>";
                }
             }
         }else{
             echo " <div class='alert alert-danger' role='alert'>";
             echo "<h2>GUIA ESTADUAL NÃO ESTÁ EM FORMATO PDF</h2>";
             echo "<h1>INCLUSÃO NEGADA</h1>";
             echo "</div>"; 
         }
         
     }


    

$query = "select id, id_documento_tipo from tb_documentos_requeridos where id_alvara = $id_documento";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);

$inicial = 1; $num_inicial = $num+$inicial; // necessário para não repetir nome do campo, já que tem alguns fixos antes
$numero = $inicial;
for($i=$inicial;$i<$num_inicial;$i++) 
    {
        $row = mysqli_fetch_array($result);
        $id = $row['id'];
        $id_documento_tipo = $row[id_documento_tipo];
       // $numero = $i+1;
       //$numero = $i;

      //  if(!in_array($id_documento_tipo, $documentos_dispensados)){
            
            echo "<div id = 'teste_$i' style = 'display: block; width:100%; margin-top: 18px; border:1px solid #cccccc; padding: 12px; background-color: #fafafa;'>";
            echo "<h5  class='policia'> $numero - ".$documento_nome[$id_documento_tipo]."</h5>";
            echo "<input type='file' name='arquivo_$id_documento_tipo' id='arquivo_$id_documento_tipo' class='filestyle' required = '' accept='.pdf' ";
            echo "onchange='return somentePdf($numero);'";
            echo ">"; // no id vai o $i para poder aplicar a validação para arquivos PDF, somente
            echo "<input type='hidden' name='id_documento_tipo_ref_$i' value='$id_documento_tipo'>";
            // echo "<div style = 'width: 100%; height: 20px;' >&nbsp</div>";
            echo "</div>";
            $numero++;
      // }

    }

// BUSCA ARQUIVO ESPECIAL PARA ESTE ALVARÁ, SE HOUVER.

// $query = "select id_documento_tipo from tb_regras_documentos_obrigatorios where id_documento_tipo in ($documentos_extras_str)"; // o final da query é para apresentar somente os documentos que o cidadão não desmarcou
// $result=mysqli_query($link, $query);
// $num = mysqli_num_rows($result);
if (strlen(trim($documentos_extras_str)) > 1) {

    $array_prov = explode(',', $documentos_extras_str);
    //print_r($array_prov);

    for ($i=0; $i < count($array_prov); $i++) { 

        $id_documento_tipo = $array_prov[$i];
        // $row = mysqli_fetch_array($result);
        // $id_documento_tipo = $row[id_documento_tipo];



        echo "<div id = 'teste_$numero' style = 'display: block; width:100%; margin-top: 18px; border:1px solid #cccccc; padding: 12px; background-color: #fafafa;'>";
        echo "<h5  class='policia'>$numero - ".$documento_nome[$id_documento_tipo]."</h5>";
        echo "<input type='file' name='arquivo_$id_documento_tipo' id='arquivo_$id_documento_tipo' class='filestyle' required = '' accept='.pdf' ";
        echo "onchange='return somentePdf($numero);'";
        echo ">"; // no id vai o $i para poder aplicar a validação para arquivos PDF, somente
        echo "<input type='hidden' name='id_documento_tipo_ref_$numero' value='$id_documento_tipo'>";
        // echo "<div style = 'width: 100%; height: 20px;' >&nbsp</div>";
        echo "</div>"; 
        $numero++;
    }
}
/*$nome_estabelecimento  = $f->limpa_variavel($_POST['nome_estabelecimento'], 90, $purifier);
$razao_social  = $f->limpa_variavel($_POST['razao_social'], 90, $purifier);
$cnpj  = $f->limpa_variavel($_POST['cnpj'], 20, $purifier);
$cnpj = preg_replace("/[^0-9]/", "",$cnpj);
$id_ramo_atividade = (int)$_POST[id_ramo_atividade];
$endereco_rua  = $f->limpa_variavel($_POST['endereco_rua'], 90, $purifier);
$endereco_numero  = $f->limpa_variavel($_POST['endereco_numero'], 40, $purifier);
$endereco_bairro  = $f->limpa_variavel($_POST['endereco_bairro'], 90, $purifier);
$endereco_cep  = $f->limpa_variavel($_POST['endereco_cep'], 12, $purifier);
$id_municipio = $_POST[id_municipio];
$telefone_fixo  = $f->limpa_variavel($_POST['telefone_fixo'], 90, $purifier);
$telefone_celular  = $f->limpa_variavel($_POST['telefone_celular'], 90, $purifier);
$email  = $f->limpa_variavel($_POST['email'], 90, $purifier);
$nome_proprietario  = $f->limpa_variavel($_POST['nome_proprietario'], 90, $purifier);
$obs_cidadao = $f->limpa_variavel($_POST['obs_cidadao'], 1000, $purifier);
$id_area_vistoria = (int)$_POST[id_area_vistoria];
//$obs_cidadao = $regras_txt."<br><br>".$obs_cidadao;
echo "<input type='hidden' name='id_documento' value='$id_documento'>";
echo "<input type='hidden' name='nome_estabelecimento' value='$nome_estabelecimento'>";
echo "<input type='hidden' name='razao_social' value='$razao_social'>";
echo "<input type='hidden' name='cnpj' value='$cnpj'>";
echo "<input type='hidden' name='id_ramo_atividade' value='$id_ramo_atividade'>";
echo "<input type='hidden' name='endereco_rua' value='$endereco_rua'>";
echo "<input type='hidden' name='endereco_numero' value='$endereco_numero'>";
echo "<input type='hidden' name='endereco_bairro' value='$endereco_bairro'>";
echo "<input type='hidden' name='endereco_cep' value='$endereco_cep'>";
echo "<input type='hidden' name='id_municipio' value='$id_municipio'>";
echo "<input type='hidden' name='telefone_fixo' value='$telefone_fixo'>";
echo "<input type='hidden' name='telefone_celular' value='$telefone_celular'>";
echo "<input type='hidden' name='email' value='$email'>";
echo "<input type='hidden' name='nome_proprietario' value='$nome_proprietario'>";
echo "<input type='hidden' name='obs_cidadao' value='$obs_cidadao'>";
echo "<input type='hidden' name='regras_txt' value='$regras_txt'>";
echo "<input type='hidden' name='id_documento' value='$id_documento'>";
echo "<input type='hidden' name='str_ref' value='$str_ref'>";
echo "<input type='hidden' name='id_area_vistoria' value='$id_area_vistoria'>";

echo "<input type='hidden' name='requerente_nome' value='".$_POST[requerente_nome]."'>";
echo "<input type='hidden' name='requerente_data_nasc' value='".$_POST[requerente_data_nasc]."'>";
echo "<input type='hidden' name='requerente_cpf' value='".$_POST[requerente_cpf]."'>";
echo "<input type='hidden' name='requerente_endereco' value='".$_POST[requerente_endereco]."'>";
echo "<input type='hidden' name='requerente_telefone' value='".$_POST[requerente_telefone]."'>";
echo "<input type='hidden' name='requerente_email' value='".$_POST[requerente_email]."'>";
echo "<input type='hidden' name='requerente_responsavel' value='".$_POST[requerente_responsavel]."'>";

*/


// COLOCA AS VARIAVEIS DENTRO DE CAMPOS HIDDEN, PARA FAZER UMA ÚNICA INCLUSÃO NO FINAL.

// BUSCA OS CAMPOS ESPECIAIS, PARA TAMBÉM COLOCAR NOS CAMPOS HIDDENS, JÁ QUE A CRIAÇÃO DO PEDIDO SOMENTE ACONTECE NA PRÓXIMA PÁGINA

$query = "select * from tb_campos_especiais_ref where id_alvara = $id_documento";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for($i=0;$i<$num;$i++) 
        {
            $row = mysqli_fetch_array($result);
            $campo_nome = $row[campo_nome];
            $campo_label =  (stripslashes($row[campo_label]));
            echo "<input type='hidden' name='$campo_nome' value='".$_POST[$campo_nome]."'>";
        }



echo "<input type='hidden' name='pedido_dispensa' value='".$_POST[pedido_dispensa]."'>";
echo "<input type='hidden' name='n_bombas' value='".$_POST[n_bombas]."'>";
echo "<input type='hidden' name='taxa_valor' value='".$_POST[taxa_valor]."'>";


echo "<div style = 'display: table; width:100%; padding-top: 24px;'>";
$f->f_button("SALVAR E CONCLUIR");
echo "<br><br></div>";
echo "</form>";


?>
</div>
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
    <script src="../fiscaliza/js/show.js"></script>
    <script src="../fiscaliza/js/somentePdf.js"></script>

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



<!-- 
<script type="text/javascript">
    function checkFile() {
        var fileElement = document.getElementById("arquivo_0");
        var fileExtension = "";
        if (fileElement.value.lastIndexOf(".") > 0) {
            fileExtension = fileElement.value.substring(fileElement.value.lastIndexOf(".") + 1, fileElement.value.length);
        }
        if (fileExtension.toLowerCase() == "pdf") {
            return true;
        }
        else {
            alert("Somente arquivos em formato PDF podem ser carregados");
            document.getElementById("arquivo_0").value='';
            return false;
        }
    }
</script> -->

    

<?php
$f -> fecha_card();
$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();

?>