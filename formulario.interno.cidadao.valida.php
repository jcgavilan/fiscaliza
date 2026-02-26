<?php
//echo "04";
header("Cache-Control: no-cache, no-store, must-revalidate, max-age=0, s-maxage=0, proxy-revalidate, private");
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
header_remove("ETag");
header_remove("Last-Modified");
session_start();
include "mysql.conecta.rep.php";
include "classes/classe.forms.php";

$f = new Forms();

require_once 'htmlpurifier/HTMLPurifier.standalone.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$id_documento= (int)$_POST['id_documento'];

$nome_estabelecimento  = $f->limpa_variavel($_POST['nome_estabelecimento'], 190, $purifier);
$razao_social  = $f->limpa_variavel($_POST['razao_social'], 190, $purifier);
$id_ramo_atividade = (int)$_POST[id_ramo_atividade];
$id_area_vistoria = (int)$_POST[id_area_vistoria];
$endereco_rua  = $f->limpa_variavel($_POST['endereco_rua'], 190, $purifier);
$endereco_numero  = $f->limpa_variavel($_POST['endereco_numero'], 40, $purifier);
$endereco_bairro  = $f->limpa_variavel($_POST['endereco_bairro'], 90, $purifier);
$endereco_cep  = $f->limpa_variavel($_POST['endereco_cep'], 12, $purifier);
$id_municipio = $_POST[id_municipio];
$telefone_fixo  = $f->limpa_variavel($_POST['telefone_fixo'], 90, $purifier);
$telefone_celular  = $f->limpa_variavel($_POST['telefone_celular'], 90, $purifier);
$email  = $f->limpa_variavel($_POST['email'], 90, $purifier);
$nome_proprietario  = $f->limpa_variavel($_POST['nome_proprietario'], 90, $purifier);
$obs_cidadao = $f->limpa_variavel($_POST['obs_cidadao'], 1000, $purifier);

$requerente_nome  = $f->limpa_variavel($_POST['requerente_nome'], 90, $purifier);
$requerente_data_nasc  = $f->limpa_variavel($_POST['requerente_data_nasc'], 90, $purifier);
$requerente_cpf  = $f->limpa_variavel($_POST['requerente_cpf'], 90, $purifier);
$requerente_endereco  = $f->limpa_variavel($_POST['requerente_endereco'], 90, $purifier);
$requerente_telefone  = $f->limpa_variavel($_POST['requerente_telefone'], 90, $purifier);
$requerente_email  = $f->limpa_variavel($_POST['requerente_email'], 90, $purifier);
$requerente_responsavel  = $f->limpa_variavel($_POST['requerente_responsavel'], 90, $purifier);


// primeiro, faz a conferência do CNPJ e do email, se os campos de confirmação foram corretamente preenchidos.

$cnpj = trim($_POST['cnpj']);
$cnpj = preg_replace("/[^0-9]/", "",$cnpj);
$cnpj_confirma = trim($_POST['cnpj_confirma']);
$cnpj_confirma = preg_replace("/[^0-9]/", "",$cnpj_confirma);
//$cnpj_confirma = str_replace("-", "", $cnpj_confirma);
// $cnpj_confirma = str_replace("/", "", $cnpj_confirma);
$email = trim($_POST['email']);
$email_confirma = trim($_POST['email_confirma']);


if( ($cnpj != $cnpj_confirma) || ($email != $email_confirma) ){
    // nesse caso, abre formulário para confirmar dados, e reenvia as variáveis para a mesma página

    
    $header=new Header_adm_WEB(); 
    $a=new Menu_adm($link);
    $nome_pagina= "Bem-Vindo";
    $a=new Abre_titulo();
    $a->titulo_pagina($nome_pagina);
    $f -> abre_card(12);
    echo "<h1>Atendimento ao Cidadão - Solicitação Interna de Alvará!</h1><hr><br>";
    $f->abre(12);
    $f -> abre_form("formulario.cidadao.valida.php");

    echo "<div class='alert alert-danger' role='alert' STYLE = 'MARGIN-TOP:18PX;'>";
    echo " <h4 class='alert-heading' align = 'center'>ATENÇÃO - ERRO DE PREENCHIMENTO</h4>";
    echo "</div><BR>";

    // echo "<p>cnpj -> $cnpj</p>";
    // echo "<p>cnpj conf-> $cnpj_confirma</p>";

    if ($cnpj != $cnpj_confirma) {
        
        echo "<div style = 'display: table; width:100%; margin-bottom: 24px; background-color: #f5f5f5; padding:18px;'>";
        echo "<h4>Os campos '<strong>CNPJ</strong>' e 'Confirma CNPJ' são diferentes.</h4>";
        echo "<h5>Por favor, informe novamente</h5>";
        $f->f_input_coluna_mask(6, "CNPJ", "cnpj", "", 'cnpj');
        $f->f_input_coluna_mask(6, "Confirma CNPJ", "cnpj_confirma", "", 'cnpj');
        echo "</div>";
    }

    if ($email != $email_confirma) {

        // echo "<p>email -> $email</p>";
        // echo "<p>email conf-> $email_confirma</p>";
        
        echo "<div style = 'display: table; width:100%; margin-bottom: 24px; background-color: #f5f5f5; padding:18px;'>";
        echo "<h4>Os campos '<strong>Email</strong>' e 'Confirma Email' são diferentes.</h4>";
        echo "<h4 style = 'color:#cc0000;'>Este email receberá o protocolo para o painel, e também o documento do Alvará.</h4>";
        echo "<h5>Por favor, informe novamente</h5>";
        $f->f_input_coluna_mask(6, "email", "email", "", 'email');
        $f->f_input_coluna_mask(6, "Confirma email", "email_confirma", "", 'email');
        echo "</div>";
    }

    
    //$obs_cidadao = $regras_txt."<br><br>".$obs_cidadao;
    echo "<input type='hidden' name='id_documento' value='$id_documento'>";
    echo "<input type='hidden' name='nome_estabelecimento' value='$nome_estabelecimento'>";
    echo "<input type='hidden' name='razao_social' value='$razao_social'>";
    if ($cnpj == $cnpj_confirma) {
        echo "<input type='hidden' name='cnpj' value='$cnpj'>"; 
        echo "<input type='hidden' name='cnpj_confirma' value='$cnpj_confirma'>";
    }

    echo "<input type='hidden' name='id_ramo_atividade' value='$id_ramo_atividade'>";
    echo "<input type='hidden' name='endereco_rua' value='$endereco_rua'>";
    echo "<input type='hidden' name='endereco_numero' value='$endereco_numero'>";
    echo "<input type='hidden' name='endereco_bairro' value='$endereco_bairro'>";
    echo "<input type='hidden' name='endereco_cep' value='$endereco_cep'>";
    echo "<input type='hidden' name='id_municipio' value='$id_municipio'>";
    echo "<input type='hidden' name='telefone_fixo' value='$telefone_fixo'>";
    echo "<input type='hidden' name='telefone_celular' value='$telefone_celular'>";

    if ($email == $email_confirma) {
        echo "<input type='hidden' name='email' value='$email'>";
        echo "<input type='hidden' name='email_confirma' value='$email_confirma'>";
    }

    echo "<input type='hidden' name='nome_proprietario' value='$nome_proprietario'>";
    echo "<input type='hidden' name='obs_cidadao' value='$obs_cidadao'>";
    echo "<input type='hidden' name='regras_txt' value='$regras_txt'>";
    echo "<input type='hidden' name='id_documento' value='$id_documento'>";
    echo "<input type='hidden' name='id_area_vistoria' value='$id_area_vistoria'>";

    echo "<input type='hidden' name='requerente_nome' value='".$_POST[requerente_nome]."'>";
    echo "<input type='hidden' name='requerente_data_nasc' value='".$_POST[requerente_data_nasc]."'>";
    echo "<input type='hidden' name='requerente_cpf' value='".$_POST[requerente_cpf]."'>";
    echo "<input type='hidden' name='requerente_endereco' value='".$_POST[requerente_endereco]."'>";
    echo "<input type='hidden' name='requerente_telefone' value='".$_POST[requerente_telefone]."'>";
    echo "<input type='hidden' name='requerente_email' value='".$_POST[requerente_email]."'>";

    echo "<input type='hidden' name='requerente_responsavel' value='".$_POST[requerente_responsavel]."'>";

    

    $query = "select id_documento_tipo, regra from tb_regras_documentos_obrigatorios  where id_alvara = $id_documento";
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++) 
            {
                $row = mysqli_fetch_array($result);
                $id_documento_tipo = $row[id_documento_tipo];
               
                if($_POST['regra_'.$id_documento_tipo] != 0){
                    
                    echo "<input type='hidden' name='regra_$id_documento_tipo' value='".$_POST['regra_'.$id_documento_tipo]."'>";
                }
            }

// TEM QUE REFAZER OS CAMPOS DE DOCUMENTOS EXTRAS PARA ESSE ALVARÁ

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

        $f->f_button("SALVAR E PROSSEGUIR");
echo "</form><br><br>";
$f->fecha();
$f -> fecha_card();
$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();

}else{

  
    // QUE BELEZA, NÃO TEM ERRO DE CNPJ E EMAIL, LOGO PODEMOS CADASTRAR OS DADOS NA TABELA PROVISÓRIA E SEGUIR COM A VIDA.

    $str_base = "bcdfghjkmnpqrstvxywzbcdfghjkmnpqrstvxywzbcdfghjkmnpqrstvxywzbcdfghjkmnpqrstvxywz2345678923456789234567892345678923456789234567892345678923456789bcdfghjkmnpqrstvxywzbcdfghjkmnpqrstvxywzbcdfghjkmnpqrstvxywzbcdfghjkmnpqrstvxywz2345678923456789234567892345678923456789234567892345678923456789";
    $str_ref = "";
    for($i=0;$i<8;$i++)
    {
        $str_prov = str_shuffle($str_base);
        $str_ref .= substr($str_prov, 0, 1);
    }

    // trata dos documentos obrigatórios para este alvará, de acordo com as opções do usuário.

    $regras_txt = '';

    $documentos_extras = array();
    $query = "select id_documento_tipo, regra from tb_regras_documentos_obrigatorios  where id_alvara = $id_documento";
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++) 
            {
                $row = mysqli_fetch_array($result);
                $id_documento_tipo = $row[id_documento_tipo];
                $regras_txt .= '<tr><td>  &nbsp;'.(stripslashes($row[regra]));

                if($_POST['regra_'.$id_documento_tipo] == 0){
                    $regras_txt .= ': NÃO &nbsp; &nbsp;</td></tr>';
                    $campo_value_inserir = 'NÃO';
                }else{
                    $documentos_extras[] = $id_documento_tipo;
                    $regras_txt .= ': SIM &nbsp; &nbsp;</td></tr>';
                    $campo_value_inserir = 'SIM';
                }

                // INSERE NA TABELA DE CAMPOS REQUERIDOS, PARA FACILITAR A VISUALIZAÇÃO
                $query2 = "insert into tb_campos_especiais_prev (`campo_nome`, `campo_value`, `str_ref`) values ('".stripslashes($row[regra])."','$campo_value_inserir','$str_ref')";
                $result2 = mysqli_query($link, $query2);
                if(!$result2)
                {   
                    echo " <div class='alert alert-danger' role='alert'>";
                    echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL CADASTRAR O CAMPO ESPECIAL<br>$query<br> </h4>".mysqli_error($link);
                    echo "</div>";
                }

            }
            // AQUI DÁ PARA DEIXAR PRONTO PRA GRAVAR EM UM CAMPO DO PEDIDOS_PREV

            /// - mas, é mais importante fazer a lista dos itens que vai ter que carregar junto com os arquivos.
    
            // transforma a lista de documentos dispensados em uma string, para inserir no campo provisório, 
            //para saber os arquivos adicionais que tem que o cidadão marcou como NÃO, porque nesses casos não vai precisar carregar o arquivo
            
            for ($i3=0; $i3 < count($documentos_extras); $i3++) { 
                if ($i3 == 0) {
                    $documentos_extras_str = $documentos_extras[$i3];
                }else{
                    $documentos_extras_str .= ','.$documentos_extras[$i3];
                }
            }

    $query = "select campo_nome from tb_campos_especiais_ref where id_alvara = $id_documento";
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++) 
            {
                $row = mysqli_fetch_array($result);
                $campo_nome = $row[campo_nome];

            //  echo "<input type='hidden' name='$campo_nome' value='".$_POST[$campo_nome]."'>";
            
                $campo_value = $f->limpa_variavel($_POST[$campo_nome], 254, $purifier);

                $query2 = "insert into tb_campos_especiais_prev (`campo_nome`, `campo_value`, `str_ref`) values ('$campo_nome','$campo_value','$str_ref')";
                $result2 = mysqli_query($link, $query2);
                if(!$result2)
                {   
                    echo " <div class='alert alert-danger' role='alert'>";
                    echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL CADASTRAR O CAMPO ESPECIAL<br>$query<br> </h4>".mysqli_error($link);
                    echo "</div>";
                }
            }

    // REALIZA O MESMO PROCEDIMENTO PARA AS PERGUNTAS QUE SE REFEREM AOS DOCUMENTOS REQUERIDOS.

    $query = "INSERT INTO tb_pedidos_prev( tipo_pedido, nome_estabelecimento, razao_social,  id_atividade_ref, id_area_vistoria, cnpj, endereco_rua, endereco_numero, endereco_bairro, endereco_cep, id_municipio, telefone_fixo, telefone_celular, email, nome_proprietario, obs_cidadao, cidadao_nome, cidadao_cpf, requerente_nome, requerente_data_nasc, requerente_cpf, requerente_endereco, requerente_telefone, requerente_email, str_ref, requerente_responsavel, regras_txt, documentos_extras_str) VALUES ('$id_documento', '$nome_estabelecimento', '$razao_social',  '$id_ramo_atividade', '$id_area_vistoria', '$cnpj', '$endereco_rua', '$endereco_numero', '$endereco_bairro', '$endereco_cep', '$id_municipio', '$telefone_fixo', '$telefone_celular', '$email', '$nome_proprietario', '$obs_cidadao', '$cidadao_nome', '$cidadao_cpf', '$requerente_nome', '$requerente_data_nasc', '$requerente_cpf', '$requerente_endereco', '$requerente_telefone', '$requerente_email', '$str_ref', $requerente_responsavel, '$regras_txt', '$documentos_extras_str')";
    $result=mysqli_query($link, $query);
    if (!$result) {
        echo "<h1>ERRO NO CADASTRO PROVISÓRIO DE DADOS</h1>";
     //   echo $query;
    }else{
      //  echo "query ok";
    }

    // VERIFICA SE O ALVARÁ SOLICITADO NÃO É DE POSTO DE GASOLINA
    // TEM POSSIBILIDADE DE SER ESTABELECIMENTO DE COMBUSTÍVEL, MAS SE FOR SÓ DEPÓSITO, SEGUE O FLUXO, POR ISSO SE FOR ID_ARTIGO = 10, BUSCA A SUBCATEGORIA
    // É... HARDCODE... FEIO NÉ... MAS É O JEITO POR ENQUANTO

    $flow = "normal";

    if ($id_documento == 10 && $id_ramo_atividade == 59) {
      $flow = "posto_combustivel";

    }

    if($flow == "normal"){

     ?>
     <html>
<head>
    <meta charset="utf-8"/>
    <title>Meu Redirect</title>

    <meta http-equiv="refresh" content="0; URL='formulario.interno.cidadao.requerimento.php?code=<?php echo $str_ref; ?>'"/>
</head>
<body>
...
</body>
</html>
     <?php


        exit();
  
    }else{

        // NESSE CASO TRATA-SE DO POSTO DE COMBUSTÍVEL.
       
      //  echo "--> ".$id_ramo_atividade." - ".$id_documento;
      include "classes/class.html.php";
        $header=new Header_adm_WEB(); 
        $a=new Menu_adm($link);
        $nome_pagina= "Bem-Vindo";
        $a=new Abre_titulo();
        $a->titulo_pagina($nome_pagina);
        $f -> abre_card(12);
        echo "<h1>Atendimento ao Cidadão - Solicitação Interna de Alvará!</h1><hr><br>";
        $f->abre(12);
        $f -> abre_form("formulario.interno.cidadao.combustivel.php?code=$str_ref");

        $query = "select nome from tb_alvaras_tipo where id = $id_documento";
        $result=mysqli_query($link, $query);
        $row = mysqli_fetch_array($result);
        $nome = $row[nome];
 
        echo "<br><br>";
        echo "<div class='form-group'>";
        echo "<label for='inputText3' class='col-form-label'><span  class='policia'>Informe o número de bombas de combustível</a></label><br>";
        echo "<select class='form-control' id='n_bombas'  name='n_bombas'>";
        echo "<option value='' selected disabled></option>";
        for($i=1;$i<41;$i++) 
                {
                    
                    echo "<option value='".$i."'";
                    echo ">".$i."</option>";         
                }
        echo "</select></div>";

      //  echo "<input type='hidden' name='n_bombas' value='$n_bombas'>";

        echo "<div style = 'display:block; width: 100%; padding: 48px 0; '>";
        $f->f_button("SALVAR E PROSSEGUIR");
        echo "</form></div>";
        $f->fecha();
        $f -> fecha_card();
        $footer=new Footer_adm_WEB();
        $footer->Footer_adm_WEB();

    }

}


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
document.getElementById("id_area_vistoria").required = true;

</script>

<!-- essa sequência tá aqui porque a máscara parou de funcionar e não consegui encontrar o porquê. bora para outra solução-->
<script>
$(":input").inputmask();

$("#cnpj").inputmask({"mask": "99.999.999/9999-99"});
$("#cnpj_confirma").inputmask({"mask": "99.999.999/9999-99"});

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

<!-- </body>

</html> -->


<?php

$f->fecha();
$f -> fecha_card();
$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();

?>


