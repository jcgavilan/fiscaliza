<?php

session_start();

if(!isset($_SESSION["usuario_fis_cpf"]))
{
  header('Location: https://integra.pc.sc.gov.br/');
  exit();
}

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

echo "<h1>Painel de Análise de Pedidos</h1>";
$f -> abre_card(12);

$id_pedido = (int)$_POST['id_pedido'];



$query = "select * from tb_cidadao_pedidos where id = ".$id_pedido;
$result=mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
$nome_estabelecimento= (stripslashes($row[nome_estabelecimento]));

$arquivos= (stripslashes($row[arquivos]));
$id_ramo_atividade = $row[id_atividade_ref];
$id_area_vistoria = $row[id_area_vistoria];
$tipo_pedido = $row[tipo_pedido];
$valor_taxa_ref = $row[valor_taxa];

$query = "select taxa_emissao from tb_alvaras_tipo where id = $tipo_pedido";
$result=mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
$taxa_emissao = $row['taxa_emissao'];

if ($taxa_emissao == 0) {
    $query = "select taxa_valor from tb_cnaes where id = ".$id_ramo_atividade;
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $valor_taxa = $row['taxa_valor'];
}else{
    $valor_taxa = $taxa_emissao;
}

if($valor_taxa_ref != 0){
    $valor_taxa = $valor_taxa_ref;
}

/// AVALIAR SE AS TAXAS ESTÃO SENDO APRESENTADAS CORRETAMENTE                                                                                                                                                                                                                                                                                           '

$query = "select valor from tb_area_vistoria where id = ".$id_area_vistoria;
$result=mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
$valor_vistoria = $row['valor'];


echo "<h2>$nome_estabelecimento</h2>";

$pedido_aprovado = 1; // ESSA VARIÁVEL EXECUTARÁ O PROCEDIMENTO DE GERAÇÃO DO ALVARÁ, SE NÃO HOUVER NENHUMA NEGATIVA DE DOCUMENTO

$doc_nomes = array(); // para deixar os nomes dos documentos disponível, caso haja negativa e necessidade de fazer a montagem do email para o cidadão.
$query = "select id, nome from tb_documentos_tipo";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for($i=0;$i<$num;$i++) 
    {
        $row = mysqli_fetch_array($result);
        $id = $row['id'];
        $doc_nomes[$id] =  (stripslashes($row[nome]));
    }
$msg_email = '';

$query = "select * from tb_cidadao_arquivos where id_pedido = ".$id_pedido." and  aprovado = 'S'"; // repete a busca anterior, para que a variável $i aponte para os mesmos arquivos

$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for($i=0;$i<$num;$i++) 
    {
       
        $row = mysqli_fetch_array($result);
        $aprovado_ref  = $row[aprovado];

        $id = $row[id];
        $id_documento_tipo = $row[id_documento_tipo];
        $data_analise = time();

        $aprovado = $_POST['aprovado_'.$i];
        

        if($aprovado == 0){ // nesse caso o arquivo foi NEGADO pelo delegado.
        
            $pedido_aprovado = 0;
        
            $justificativa  = $f->limpa_variavel($_POST['justificativa_'.$i], 1000, $purifier);
           
            $query2 = "update tb_cidadao_arquivos set aprovado = 'N', justificativa = '$justificativa', data_analise = $data_analise where id = $id";
                $result2 = mysqli_query($link, $query2);
                if(!$result2)
                {   
                    echo " <div class='alert alert-danger' role='alert'>";
                    echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL CONCLUIR O REGISTRO<br>$query<br> </h4>".mysqli_error($link);
                    echo "</div>";
                }
                $msg_email .= "<p>".$doc_nomes[$id_documento_tipo]."</p>";
        }
    }

if($pedido_aprovado == 1){

    // APROVADO PELO DELEGADO - APRESENTA FORMULÁRIO PARA DADOS ADICIONAIS

    echo "<h2>Geração de Documento - Dados adicionais</h2><hr><br>";


    $id_pedido = $_POST[id_pedido];
    settype($id_pedido, 'integer');

    //busca o nome do alvará;

    $query = "select tipo_pedido, nome_estabelecimento, valor_taxa, valor_vistoria from tb_cidadao_pedidos where id = $id_pedido";
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $id_alvara =  (stripslashes($row[tipo_pedido]));
    $nome_estabelecimento =  (stripslashes($row[nome_estabelecimento]));
    $valor_taxa = $row['valor_taxa'];
    $valor_vistoria = $row['valor_vistoria'];


     $query = "select periodo_validade from tb_alvaras_tipo where id = ".$id_alvara;
     $result=mysqli_query($link, $query);
     $row = mysqli_fetch_array($result);
     $periodo_validade =  (stripslashes($row[periodo_validade]));

    // $query = "select nome_estabelecimento from tb_estabelecimentos where id = ".$id_estabelecimento;
    // $result=mysqli_query($link, $query);
    // $row = mysqli_fetch_array($result);
    // $nome_estabelecimento =  (stripslashes($row[nome_estabelecimento]));

    $now = time();
    $data_expedicao = date("d/m/Y", $now);

    $f -> abre_form("gera.alvara.cidadao.php");
    echo "<input type='hidden' name='id_pedido' value='$id_pedido'>"; 
    echo "<input type='hidden' name='id_alvara' value='$id_alvara'>"; 
    echo "<h3>Estabelecimento: $nome_estabelecimento</h3>";

    $query = "select unidade_numero, unidade_nome from tb_unidades_policiais WHERE `cpf_policial` = '".$_SESSION['usuario_fis_cpf']."'";
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $unidade_numero =  (stripslashes($row[unidade_numero]));
 


   // $f -> f_input("Código da Unidade Policial", "cod_unid_policial", "$unidade_numero");
    $taxas_total = $valor_taxa + $valor_vistoria;
    echo "<p>Valor das guias arrecadadas ( tarifa do alvará + vistoria ) : <strong>R$ $taxas_total</strong></p>"; //( R$ $valor_taxa + R$ $valor_vistoria )

    $f -> f_input("Taxa Estadual", "taxa_estadual", "$taxas_total");



    //$f -> f_input("Data de Expedição", "data_expedicao", "$data_expedicao");

    echo "<div class='form-group' id = 'inputmask'>";
    echo  "<label for='inputText3' class='col-form-label'>Data de Expedição</label>";
    echo "<input  type='text'  name='data_expedicao' class='form-control date-inputmask' id='data_expedicao' value = '$data_expedicao'>";
    echo "</div>";

    if ($periodo_validade == 'anual') {
        $ano_print = date("Y");
        $data_print = '31/12/'.$ano_print;
    }

    if ($periodo_validade == 'mensal') {
        $m = date("m");
        
        if($m == '01' || $m == '03' || $m == '05' || $m == '07' || $m == '08' || $m == '10' || $m == '12'){
            $mes_final = 31;
        }

        if($m == '04' ||$m == '06' || $m == '09' ||$m == '11'){
            $mes_final = 30;
        }
        
        if($m == '02'){
            if ( $ano_print == 2028 || $ano_print == 2032 || $ano_print == 2036 || $ano_print == 2040 || $ano_print == 2044 || $ano_print == 2048 ) { // solução elegante não funcionou, vai assim.
                $mes_final = 29;
            }else{
                $mes_final = 28;
            }
        }
        
        $ano_print = date("Y");
        $data_print = "$mes_final/$m/$ano_print";
    }

    if ($periodo_validade == 'diario') {

        // vai busca a data final do evento, cadastrada pelo usuário no formulario.cidadao. É um daqueles campos especiais
        $query = "select campo_value from tb_campos_especiais where campo_nome = 'evento_data_fim' and id_pedido = $id_pedido";
        $result=mysqli_query($link, $query);
        $row = mysqli_fetch_array($result);
        $data_print = $row[campo_value];
    }


    echo "<div class='form-group' id = 'inputmask'>";
    echo  "<label for='inputText3' class='col-form-label'>Data de Validade do Alvará</label>";
    echo "<input  type='text'  name='data_validade' class='form-control date-inputmask' id='data_validade' value = '$data_print' required>";
    echo "</div>";

   // $f -> f_input_mask("Data de Validade do Alvará", "data_validade", "data");
    
    // BUSCA CAMPOS ESPECIAIS A SEREM PREENCHIDOS PELO POLICIAL, SE HOUVER

    $query = "select * from tb_campos_especiais_policia where id_alvara = $id_alvara"; 
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++) 
            {
                $row = mysqli_fetch_array($result);
                $campo_nome = $row[campo_nome];
                $campo_label =  stripslashes($row[campo_label]);
                $value_prev =  stripslashes($row[value_prev]);
                $f -> f_input("$campo_label", "$campo_nome", "$value_prev");
            }


    $f->f_button("EXPEDIR ALVARÁ");

    echo "</form>";


}else{
    // HOUVE AO MENOS UMA NEGATIVA PELO DELEGADO.

    // DISPARA O EMAIL PARA O CIDADÃO
    
    // Busca dados do pedido para email do usuário

    $query = "select data_pedido, tipo_pedido, nome_estabelecimento, cnpj, senha, hash, email, historico from tb_cidadao_pedidos where id = ".$id_pedido;
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $data_pedido = $row['data_pedido'];
    $tipo_pedido = $row['tipo_pedido'];
    $nome_estabelecimento =  stripslashes($row[nome_estabelecimento]);
    $cnpj =  stripslashes($row[cnpj]);
    $hash =  stripslashes($row[hash]);
    $email =  stripslashes($row[email]);
    $senha =  stripslashes($row[senha]);
    $historico =  stripslashes($row[historico]);



    //busca o nome do documento que será emitido
    $query = "select nome from tb_alvaras_tipo where id = ".$tipo_pedido;
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $nome_documento =  stripslashes($row[nome]);

    $data_atual = time();
    $link_enviar = "https://sistemas.pc.sc.gov.br/cidadao/login.cidadao.php?token=$hash";
    $msg_final = "<html><body><p>Prezado usuário, seu pedido de emissão de $nome_documento para o estabelecimento $nome_estabelecimento foi analisado em ".date("d/m/Y H:i", $data_atual)." e os seguintes documentos receberam negativa: </p> ";
    $msg_final .= $msg_email;
    $msg_final .= "<p>Você poderá visualizar as justificativas das negativas em relação a estes documentos, e a opção de carregar novos arquivos para correção, em seu painel exclusivo para este pedido</p>";
    $msg_final .= "<p>Por favor, acesse o painel a partir <a href = '$link_enviar'>deste link</a></p>";
    $msg_final .= "<br>Você também deverá informar os seguintes dados:";
    $msg_final .= "<br>Login: ".$cnpj;
    $msg_final .= "<br>senha: ".$senha;
    $msg_final .= "</body></html>";

    $titulo_email = "Emissão de $nome_documento - documentos pendentes";

    $envia = exec("curl -d 'to=$email&subject=$titulo_email&html=$msg_final' -X POST https://getin.pc.sc.gov.br/sendmail/");
        if (!$envia) {
            $f->msg("ERRO NO ENVIO PARA O EMAIL<br> curl -d 'to=$email&subject=$titulo_email&html=$msg_final' -X POST https://getin.pc.sc.gov.br/sendmail/ ","danger");
        }else{
            $f->msg("Email para o cidadão enviado com Sucesso","info");
          
        }

        // por fim, seta o status do pedido para 1, para que não apareça no painel do policial enquanto o usuário não carregar os arquivos pedidos
        $agora = time();
        $historico = "ATUALIZAÇÃO EM ".date("d/m/Y H:i")."<br>Análise dos documentos realizada.<br>Há pendências.<br><hr><br>.$historico";
        $query2 = "update tb_cidadao_pedidos set status = 1, ultima_movimentacao = $agora, historico = '$historico' where id = $id_pedido";
        $result2 = mysqli_query($link, $query2);
}

echo "<br><br><br><br><a href = 'delegado.painel2.php' class = 'btn btn-light'>VOLTAR PARA LISTA DE HOMOLOGAÇÃO</a>";


$f -> fecha_card();

$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();



?>