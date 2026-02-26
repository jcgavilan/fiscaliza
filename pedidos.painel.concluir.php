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

echo "<h1>Painel de Análise de Pedidos</h1>";
$f -> abre_card(12);

$id_pedido = (int)$_GET['id_pedido'];



$query = "select * from tb_cidadao_pedidos where id = ".$id_pedido;
$result=mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
$nome_estabelecimento= (stripslashes($row[nome_estabelecimento]));

$arquivos= (stripslashes($row[arquivos]));

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

$query = "select * from tb_cidadao_arquivos where id_pedido = ".$id_pedido." and (aprovado = 'A' or aprovado = 'S')"; // repete a busca anterior, para que a variável $i aponte para os mesmos arquivos
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for($i=0;$i<$num;$i++) 
    {
        $row = mysqli_fetch_array($result);
        $aprovado_ref  = $row[aprovado];
        if ($aprovado_ref == 'A') { // como no painel anterior pode ser S ou A, com essa query mantem a contagem, mas analisa só os que vêm como A
            # code...
       
            $id = $row[id];
            $id_documento_tipo = $row[id_documento_tipo];
            $data_analise = time();

            $aprovado = $_POST['aprovado_'.$i];
            if($aprovado == 'YES'){$aprovado=1;} // para adequar à mudança do radiobutton, que mudou o value para mostrar a mensagem.
            if($aprovado == 1){

                $query2 = "update tb_cidadao_arquivos set aprovado = 'S', data_analise = $data_analise, nome_policial_aprovacao= '".$_SESSION['usuario_fis_nome']."' where id = $id";
                $result2 = mysqli_query($link, $query2);
                if(!$result2)
                {   
                    echo " <div class='alert alert-danger' role='alert'>";
                    echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL CONCLUIR O REGISTRO<br>$query<br> </h4>".mysqli_error($link);
                    echo "</div>";
                }
            }else{

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

    }
    //update tb_cidadao_pedidos set  comentarios_policiais = CONCAT(COALESCE(`comentarios_policiais`,''), '$comentarios_policiais_inserir') where id = 308


   // CONCAT(comentarios_policiais, '01-')
    $comentarios_policiais =  $f->limpa_variavel($_POST['comentarios_policiais'], 5000, $purifier);
    $comentarios_policiais_inserir = "<br><hr><br>".$comentarios_policiais."<br>Registrado por ".$_SESSION['usuario_fis_nome']." em ".date("d/m/Y H:i");
    
    //
if($pedido_aprovado == 1){

    // PROCESSO APROVADO -> ENCAMINHA PARA O DELEGADO

    // busca o histórico para fazer a concatenação
    $query2 = "select historico from tb_cidadao_pedidos where id= $id_pedido";
    $result2=mysqli_query($link, $query2);
    $row2 = mysqli_fetch_array($result2);
    $historico =  stripslashes($row2[historico]);
    $historico = "ATUALIZAÇÃO EM ".date("d/m/Y H:i")."<br>Documentos requeridos aprovados.<br>Processo encaminhado para homologação.<br><hr><br>.$historico";
    $agora = time();
    $query2 = "update tb_cidadao_pedidos set status = 2, ultima_movimentacao = $agora, historico = '$historico', comentarios_policiais = CONCAT(COALESCE(`comentarios_policiais`,''), '$comentarios_policiais_inserir') where id = $id_pedido"; // STATUS 2 = VALIDADO PELO POLICIAL, MAS AINDA NÃO TEVE A CERTIDÃO GERADA
    $result2 = mysqli_query($link, $query2);
   
    ECHO "<H1>DOCUMENTOS APROVADOS.</H1>";
    ECHO "<H2>Solicitação encaminhada para homologação pelo Delegado</H2>";
    // por fim, seta o status do pedido para 1, para que não apareça no painel do policial enquanto o usuário não carregar os arquivos pedidos
    echo "<br><a href = 'pedidos.lista.php' class='btn btn-primary'>VOLTAR PARA PAINEL DE SOLICITAÇÕES (Fila de Análise)</a>";
   
    // $query2 = "update tb_cidadao_pedidos set status = 2, ultima_movimentacao = $agora where id = $id_pedido";
    // $result2 = mysqli_query($link, $query2);

    // ---------------------------------- DISPARA EMAIL PARA O DELEGADO SELECIONADO NA PÁGINA ANTERIOR
    echo "<br><br>";
    $id_delegado = (int)$_POST['id_delegado'];
    $query = " select nome, email from tb_lotacao_expandida where id = $id_delegado";
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $nome =  stripslashes($row[nome]);
    $email =  stripslashes($row[email]);

    $msg_final = "<html><body><p>Excelentíssimo(a) Senhor(a) Delegado(a) de Polícia, novo pedido de alvará disponível para homologação.";
          
    $msg_final .= "<br>Estabelecimento: ".$nome_estabelecimento;

    $msg_final .= "</body></html>";

    $titulo_email = "FISCALIZA - Alvará pronto para homologação em ".date("d/m/y H:i");

    $envia = exec("curl -d 'to=$email&subject=$titulo_email&html=$msg_final' -X POST https://getin.pc.sc.gov.br/sendmail/");
        if (!$envia) {
            $f->msg("ERRO NO ENVIO PARA O EMAIL<br> curl -d 'to=$email&subject=$titulo_email&html=$msg_final' -X POST https://getin.pc.sc.gov.br/sendmail/ ","danger");
        }else{
            $f->msg("Email para o Delegado enviado com Sucesso","info");
          
        }

    // ---------------------------------- FIM DO DISPARO DE EMAIL

}else{

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

    $query = "select unidade_numero, unidade_nome from tb_unidades_policiais WHERE `cpf_policial` = '".$_SESSION['usuario_fis_cpf']."'";
    //echo $query;
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $unidade_numero =  stripslashes($row[unidade_numero]);
    $unidade_nome =  stripslashes($row[unidade_nome]);


    $data_atual = time();
    $link_enviar = "https://sistemas.pc.sc.gov.br/cidadao/login.cidadao.php?token=$hash";
    $msg_final = "<html><body><p>Prezado usuário, seu pedido de emissão de $nome_documento para o estabelecimento $nome_estabelecimento foi analisado em ".date("d/m/Y H:i", $data_atual)." e os seguintes documentos receberam negativa: </p> ";
    $msg_final .= $msg_email;
    $msg_final .= "<p>Você poderá visualizar as justificativas das negativas em relação a estes documentos, e a opção de carregar novos arquivos para correção, em seu painel exclusivo para este pedido</p>";
    $msg_final .= "<p>Por favor, acesse o painel a partir <a href = '$link_enviar'>deste link</a></p>";
    $msg_final .= "<br>Você também deverá informar os seguintes dados:";
    $msg_final .= "<br>Login: ".$cnpj;
    $msg_final .= "<br>senha: ".$senha;
    $msg_final .= "<br><br>$unidade_numero - $unidade_nome";
    $msg_final .= "</body></html>";

    $titulo_email = "Emissão de $nome_documento - Documentos pendentes";

    $envia = exec("curl -d 'to=$email&subject=$titulo_email&html=$msg_final' -X POST https://getin.pc.sc.gov.br/sendmail/");
        if (!$envia) {
            $f->msg("ERRO NO ENVIO PARA O EMAIL<br> curl -d 'to=$email&subject=$titulo_email&html=$msg_final' -X POST https://getin.pc.sc.gov.br/sendmail/ ","danger");
        }else{
            $f->msg("PROCEDIMENTO CONCLUÍDO<BR>Email para o cidadão enviado com Sucesso","info");
          
        }

        // por fim, seta o status do pedido para 1, para que não apareça no painel do policial enquanto o usuário não carregar os arquivos pedidos
        $agora = time();
        $historico = "ATUALIZAÇÃO EM ".date("d/m/Y H:i")."<br>Análise dos documentos realizada.<br>Há pendências.<br><hr><br>.$historico";
        $query2 = "update tb_cidadao_pedidos set status = 1, status_com_cidadao = 1, ultima_movimentacao = $agora, historico = '$historico', comentarios_policiais = CONCAT(COALESCE(`comentarios_policiais`,''), '$comentarios_policiais_inserir') where id = $id_pedido";
        $result2 = mysqli_query($link, $query2);
}




$f -> fecha_card();

$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();



?>