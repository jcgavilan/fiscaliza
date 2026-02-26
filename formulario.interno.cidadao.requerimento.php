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
echo "<h1>Atendimento ao Cidadão - Solicitação Interna de Alvará!</h1><hr>";
$f->abre(12);



//busca os dados relevantes com base no str_ref ;
        $str_ref = $f->limpa_variavel($_GET[code], 9, $purifier);

        $str_ref = str_replace("a", "", $str_ref);
        $str_ref = str_replace("e", "", $str_ref);
        $str_ref = str_replace("i", "", $str_ref);
        $str_ref = str_replace("o", "", $str_ref);
        $str_ref = str_replace("u", "", $str_ref);

        $str_ref = str_replace("A", "", $str_ref);
        $str_ref = str_replace("E", "", $str_ref);
        $str_ref = str_replace("I", "", $str_ref);
        $str_ref = str_replace("O", "", $str_ref);
        $str_ref = str_replace("U", "", $str_ref);

        if (strlen($str_ref) != 8) {
            
            // HOUVE ALTERAÇÃO MANUAL DO CÓDIGO - ABORTA TUDO.
            echo "<h1 class='policia'>$str_ref -> ".strlen($str_ref)."</h1>";
            ECHO "<H1 class='policia'>ERRO NA CODIFICAÇÃO DO PEDIDO.</H1>";
            exit();
        }

        $query = "select id_area_vistoria, tipo_pedido, requerente_responsavel, id_atividade_ref from tb_pedidos_prev where str_ref = '$str_ref' LIMIT 1";
        $result=mysqli_query($link, $query);
        $row = mysqli_fetch_array($result);
        $id_documento = stripslashes($row[tipo_pedido]);
        $id_area_vistoria = stripslashes($row[id_area_vistoria]);
        $requerente_responsavel = $row[requerente_responsavel];
        $id_ramo_atividade = $row[id_atividade_ref];
        
       

        $f->abre(12);
        
        $f -> abre_form("formulario.interno.cidadao.arquivos.php?code=".$_GET[code]);

       // $id_documento= (int)$_POST['id_documento'];

        $query = " select requer_requerimento, requerimento, taxa_emissao, requer_vistoria, requer_dare from tb_alvaras_tipo where id = $id_documento";
  
        $result=mysqli_query($link, $query);
        $row = mysqli_fetch_array($result);
        $requerimento = stripslashes($row[requerimento]);
        $requer_requerimento = $row[requer_requerimento];
        $taxa_emissao = $row[taxa_emissao];
        $requer_vistoria = $row[requer_vistoria];
        $requer_dare = $row[requer_dare];

        
        if ($requer_requerimento == 1) {

            echo "<br><div style = 'display: block; width:100%; margin-top: 18px; border:1px solid #cccccc; padding: 12px; background-color: #f5f5f5; text-align: center;'>";
            echo "<h2><span style = 'font-weight: bolder'>Preenchimento de Requerimento</span></h2><br>";

            //    echo "<div style = 'width: 100%; padding: 1px; background-color: #ffa500; color: #ffffff; text-align: center;'>";
                ECHO "<H1 style = 'margin:0px 0px; '>ATENÇÃO<h1>";
              //  echo "</div>";

                echo "<br><h5>Nesta página você deve realizar o Download do requerimento para este alvará específico, com dados adicionais.</h5>";
                echo "<br><h5>O documento é um PDF editável, então depois de baixar o documento, você deverá preencher todas as informações requeridas, salvar, e carregar novamente nesta mesma página.</h5>";
                echo "<br><h5>Então por favor, siga os passos adiante:</h5>";

                echo "<br><a href = '../fiscaliza/html_base/$requerimento' class = 'btn btn-primary' download>CLIQUE AQUI PARA FAZER O DOWNLOAD DO MODELO DO REQUERIMENTO</a>";
                
                echo "<br><br><h5>Preencha de forma completa, todos os campos são obrigatórios.</h5>";
              //  echo "<br><h5></h5>";

                echo "<div id = 'teste_1' style = 'display: block; width:100%; margin-top: 18px; border: 1px solid #cccccc; background-color:#ffffff; padding: 20px;'>";
                echo "<h4><span style = 'font-weight: 700;'>Após concluir, carregue aqui o documento preenchido.</span></h4><br>";
                echo "<input type='file' name='requerimento_preenchido' id='teste_1' class='filestyle' required = '' accept='.pdf' ";
                echo "onchange='return somentePdf(1);'";
                echo ">"; 
                echo "</div>";
            echo "</div>";
        }

        if ($requerente_responsavel == 0) { // o requerente não é o proprietário ou responsável pela empresa, e deve apresentar procuração
            
            echo "<br><div style = 'display: block; width:100%; margin-top: 18px; border:1px solid #cccccc; padding: 12px; background-color: #f5f5f5; text-align: center;'>";
            echo "<h2><span style = 'font-weight: bolder' class= 'policia'>Carregamento da Procuração</span></h2><br>";

        
            echo "<div id = 'teste_777' style = 'display: block; width:100%; margin-top: 18px; border: 1px solid #cccccc; background-color:#ffffff; padding: 20px;'>";
            echo "<h4><span style = 'font-weight: 700;' class= 'policia'>Carregue aqui o arquivo PDF da Procuração <br>que o autoriza a solicitar este Alvará.</span></h4><br>";
            echo "<input type='file' name='procuracao' id='teste_1' class='filestyle' required = '' accept='.pdf' ";
            echo "onchange='return somentePdf(777);'";
            echo ">"; 
            echo "</div>";
            echo "</div><br>";

        }



        // PRIMEIRO, MONTA A ORIENTAÇÃO PARA PAGAR A TARIFA, COM LINK PARA PAGAR, E O BOTÃO PARA CARREGAR A DARF PAGA.



// aqui entra a possibilidade do cidadão pedir a dispensa de algum documento.
echo "<strong><br><br>";
echo "<div class='form-group' style = 'padding: 6px;'>";
echo "<label for='inputText3' class='col-form-label'><h3 class='policia'>Deseja solicitar a dispensa de algum pagamento de taxa?</h3></label><br>";
echo "<label class='custom-control custom-radio custom-control-inline'>";
    echo "<input type='radio' name='dispensa'   id='bt_dispensa_on' class='custom-control-input' value='1' onclick='botao_dispensa_on()'";
    echo "><span class='custom-control-label'>&nbsp;<span  class='policia'>SIM. </span>&nbsp;</span>";
echo "</label>";

echo "<label class='custom-control custom-radio custom-control-inline'>";
    echo "<input type='radio' name='dispensa' id='bt_dispensa_off' class='custom-control-input' value='0' onclick='botao_dispensa_off()'";
    echo " checked><span class='custom-control-label'>&nbsp; <span  class='policia'>NÃO.</span> </span>";
echo "</label>";
echo "</div>";
echo "</strong>";

echo "<div id = 'div_dispensa' style = 'display: none'>";
    echo "<br><p  class='policia'>";
    echo "É possível o pedido de dispensa de documento ou comprovante, a critério da Autoridade Policial responsável, nos seguintes casos: ";
    echo "<ul style = 'padding-left:36px;'>";
    echo "<li  class='policia'>licenças para festividades de caráter beneficente, promovidas por pessoas, instituições, clubes de serviços ou entidades sem fins lucrativos, mediante comprovação;</li>";
    echo "<li  class='policia'>os atos relativos ao Microempreendedor Individual (MEI).</li>";
    echo "</ul>";
    echo "</p>";

    echo "<div class='form-group'>";
    echo "<label for='exampleFormControlTextarea1'><span  class='policia'>Pedido de Dispensa</span></label>";
    echo "<textarea class='form-control' id='pedido_dispensa'  name='pedido_dispensa' rows='3'></textarea>";
    echo "</div>";


    echo "<br><H5  class='policia'><strong  class='policia'>ATENÇÃO:</strong> Ao pedir dispensa do pagamento de alguma taxa, você deve carregar, no lugar do comprovante, o documento que prova a sua habilitação para a referida dispensa.</H5><br>";

    echo "</div><br>";

//$id_ramo_atividade = (int)$_POST[id_ramo_atividade];

$query = "select taxa_valor from tb_cnaes where id = ".$id_ramo_atividade;
//echo $query;


$result=mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
$taxa_valor = $row[taxa_valor];

if (strlen($taxa_valor) < 2) {
    $taxa_valor = $taxa_emissao; // para o caso em que a taxa é referente ao alvará, e não à atividade.
}

if(isset($_POST[n_bombas])){ // o tratamento a seguir se refere a postos de gasolina,pois altera o valor da taxa.

 

    $n_bombas = (int)$_POST[n_bombas];

    $x = 0;

    for ($i=1; $i <= $n_bombas; $i++) { 

        $n = (int)$_POST['produtos_bomba_'.$i];

        $x += $n;
        
    }

  ////  echo "<h2>( $n_bombas ) ( $x ) ($taxa_valor) </h2>";
    $taxa_valor = $taxa_valor*$x;

    echo "<input type='hidden' name='n_bombas' value='Bombas: $n_bombas'>";

}

echo "<input type='hidden' name='taxa_valor' value='$taxa_valor'>";

//$id_area_vistoria = $_POST[id_area_vistoria];
$query = "select valor from tb_area_vistoria where id = ".$id_area_vistoria;
$result=mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
$valor_vistoria = $row[valor];

// AQUI VOU TRABALHAR COM VARIÁVEL DE SESSÃO PARA ESSE VALOR ESTAR DISPONÍVEL SEM PRECISAR CRIAR UMA TABELA SÓ PARA UM DADO PROVISÓRIO, PARA INSERIR NO RESUMO DA EMPRESA.
$_SESSION[valor_total_resumo] = $taxa_valor + $valor_vistoria; 

echo "<div id = 'teste' style = 'display: block; width:100%; margin-top: 18px; border:1px solid #cccccc; padding: 12px; background-color: #f5f5f5; text-align: center;'>";
    echo "<h2><span style = 'font-weight: bolder'  class='policia'>Pagamento da GUIA ESTADUAL</span></h2>";
    echo "<br><h4  class='policia'>Valor da taxa para emissão do alvará: &nbsp;<strong><span style = 'color: #ff0000; font-weight: 700;'>R$ $taxa_valor</span></strong></h4>";
    if ($requer_vistoria == 1) {
        echo "<br><h4  class='policia'>Taxa de Vistoria Policial: &nbsp;<strong><span style = 'color: #ff0000; font-weight: 700;'>R$ $valor_vistoria</span></strong></h4><br>";
    }
    
    echo "<h3><a href = 'https://sat.sef.sc.gov.br/tax.NET/Sat.Arrecadacao.Web/DARE_online/EmissaoDareOnline.aspx' target= '_blank' class = 'btn btn-primary'>CLIQUE AQUI PARA ACESSAR O SITE DA RECEITA ESTADUAL E REALIZAR O PAGAMENTO</a></h3><br>";
    
    
    if ($requer_dare == 1) { // NO MOMENTO, SÓ AS EMPRESAS DE SEGURANÇA PRIVADA SE ENQUADRAM AQUI, POR ISSO TÁ NO HARD CODING MESMO
        echo "<div id = 'teste_1000' style = 'display: block; width:100%; margin-top: 18px; border: 1px solid #cccccc; background-color:#ffffff; padding: 20px;'>";
        echo "<h4><span style = 'font-weight: 700;' class= 'policia'>Carregue aqui o o arquivo PDF do Documento de Arrecadação de Receitas Estaduais - DARE de CERTIDÃO  <br>ATENÇÃO: o número SAT deve estar legível.</span></h4><br>";
        echo "<input type='file' name='documento_dare_certidao' id='documento_dare_certidao' class='filestyle' required = '' accept='.pdf' ";
        echo "onchange='return somentePdf(1000);'";
        echo ">"; 
        echo "</div>";

        echo "<div id = 'teste_1001' style = 'display: block; width:100%; margin-top: 18px; border: 1px solid #cccccc; background-color:#ffffff; padding: 20px;'>";
        echo "<h4><span style = 'font-weight: 700;' class= 'policia'>Carregue aqui o arquivo PDF com o comprovante de pagamento da TAXA DE CERTIDÃO.</span></h4><br>";
        echo "<input type='file' name='dare_comprovante_certidao' id='dare_comprovante_certidao' class='filestyle' required = '' accept='.pdf' ";
        echo "onchange='return somentePdf(1001);'";
        echo ">"; 
        echo "</div>";
    }    

    if ($requer_dare == 2) { // o requerente não é o proprietário ou responsável pela empresa, e deve apresentar procuração
                
        echo "<div id = 'teste_1000' style = 'display: block; width:100%; margin-top: 18px; border: 1px solid #cccccc; background-color:#ffffff; padding: 20px;'>";
        echo "<h4><span style = 'font-weight: 700;' class= 'policia'>Carregue aqui o Documento de Arrecadação de Receitas Estaduais – DARE da TAXA DE ALVARÁ</span></h4> <h4 class= 'policia'>ATENÇÃO: o número SAT deve estar legível.</h4><br>";
        echo "<input type='file' name='dare_principal_doc' id='dare_principal_doc' class='filestyle' required = '' accept='.pdf' ";
        echo "onchange='return somentePdf(1000);'";
        echo ">"; 
        echo "</div>";

        echo "<div id = 'teste_1001' style = 'display: block; width:100%; margin-top: 18px; border: 1px solid #cccccc; background-color:#ffffff; padding: 20px;'>";
        echo "<h4><span style = 'font-weight: 700;' class= 'policia'>Carregue aqui o arquivo PDF com o comprovante de pagamento da TAXA DE ALVARÁ.</span></h4><br>";
        echo "<input type='file' name='dare_principal_comprovante' id='dare_principal_comprovante' class='filestyle' required = '' accept='.pdf' ";
        echo "onchange='return somentePdf(1001);'";
        echo ">"; 
        echo "</div>";

        echo "<div id = 'teste_1002' style = 'display: block; width:100%; margin-top: 18px; border: 1px solid #cccccc; background-color:#ffffff; padding: 20px;'>";
        echo "<h4><span style = 'font-weight: 700;' class= 'policia'>Documento de Arrecadação de Receitas Estaduais – DARE da vistoria</span></h4><h4 class= 'policia'>ATENÇÃO: o número SAT deve estar legível.</h4><br>";
        echo "<input type='file' name='dare_vistoria_doc' id='dare_vistoria_doc' class='filestyle' required = '' accept='.pdf' ";
        echo "onchange='return somentePdf(1002);'";
        echo ">"; 
        echo "</div>";

        echo "<div id = 'teste_1003' style = 'display: block; width:100%; margin-top: 18px; border: 1px solid #cccccc; background-color:#ffffff; padding: 20px;'>";
        echo "<h4><span style = 'font-weight: 700;' class= 'policia'>Carregue aqui o arquivo PDF com o comprovante de pagamento TAXA DE VISTORIA POLICIAL</span></h4><br>";
        echo "<input type='file' name='dare_vistoria_comprovante' id='dare_vistoria_comprovante' class='filestyle' required = '' accept='.pdf' ";
        echo "onchange='return somentePdf(1003);'";
        echo ">"; 
        echo "</div>";
    }  

echo "</div>";

        echo "<br><h5  class='policia'>Na próxima página, você carregará os demais arquivos para a solicitação deste alvará.</h5><br>";

        $f->f_button("SALVAR E PROSSEGUIR");


            echo "</form><br><br>";
            
        $f->fecha();
    
// fim do else confirma cnpj e email  -> } 

$f -> fecha_card();
$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();

?>

