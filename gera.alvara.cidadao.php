<?php

session_start();

if(!isset($_SESSION["id_usuario_des"]))
{
  //header('Location: login.php');
  //exit();
}

include "classes/class.html.php";
include "classes/classe.forms.php";
include "mysql.conecta.rep.php";

require_once 'htmlpurifier/HTMLPurifier.standalone.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$id_pedido = $_POST[id_pedido];
settype($id_pedido, 'integer');

$query = "select * from tb_cidadao_pedidos where id = ".$id_pedido;
    $result=mysqli_query($link, $query);

    $a =  str_shuffle($base);
    $str = substr($a, 0, 40);
    $row = mysqli_fetch_array($result);
    $id_pedido = $row[id];
    $data_pedido = $row[data_pedido];
    $tipo_pedido = $row[tipo_pedido];
    $cnpj= (stripslashes($row[cnpj]));
    $x = $cnpj;
    $cnpj = $x[0].$x[1]."-".$x[2].$x[3].$x[4]."-".$x[5].$x[6].$x[7]."/".$x[8].$x[9].$x[10].$x[11]."-".$x[12].$x[13];
    $senha= (stripslashes($row[senha]));
    $nome_estabelecimento= (stripslashes($row[nome_estabelecimento]));
    $razao_social= (stripslashes($row[razao_social]));
    $nome_atividade = (stripslashes($row[id_ramo_atividade]));
    $endereco_rua= (stripslashes($row[endereco_rua]));
    $endereco_numero= (stripslashes($row[endereco_numero]));
    $endereco_bairro= (stripslashes($row[endereco_bairro]));
    $endereco_cep= (stripslashes($row[endereco_cep]));
    $id_municipio= (stripslashes($row[id_municipio]));
    $telefone_fixo= (stripslashes($row[telefone_fixo]));
    $telefone_celular= (stripslashes($row[telefone_celular]));
    $email= (stripslashes($row[email]));
    $nome_proprietario= (stripslashes($row[nome_proprietario]));
    $id_atividade_ref= (stripslashes($row[id_atividade_ref]));

    $hash = (stripslashes($row[hash]));


$idade_permitida =  (stripslashes($row[idade_permitida]));  // SERÁ QUE ESSE DADO DEVE ESTAR NESSA TABELA (ESQUEMA DE SIM OU NÃO)?

//busca o nome da atividade
//$query2 = "select * from tb_ramos_atividade where id = ".$id_ramo_atividade;
//$result2=mysqli_query($link, $query2);    
//$row2 = mysqli_fetch_array($result2);
//$nome_atividade =  (stripslashes($row2[nome]));


$query2 = "select nome from tb_municipios_ibge where ibge_reduzido = ".$id_municipio;
$result2=mysqli_query($link, $query2);    
$row2 = mysqli_fetch_array($result2);
$nome_municipio =  (stripslashes($row2[nome]));

//  RECEBE AS VARIÁVEIS PREENCHIDAS MANUALMENTE NA PÁGINA ANTERIOR

// $cod_unid_policial  = $f->limpa_variavel($_POST['cod_unid_policial'], 90, $purifier);
// $taxa_estadual  = $f->limpa_variavel($_POST['taxa_estadual'], 90, $purifier);
// $data_expedicao  = $f->limpa_variavel($_POST['data_expedicao'], 90, $purifier);
// $data_validade  = $f->limpa_variavel($_POST['data_validade'], 90, $purifier);

$cod_unid_policial  = $_POST['cod_unid_policial'];
$taxa_estadual = $_POST['taxa_estadual'];
$data_expedicao = $_POST['data_expedicao'];
$data_validade = $_POST['data_validade'];

$empresa_seguranca = $_POST['empresa_seguranca'];

$campo_especial = array();
$query = "select campo_nome from tb_campos_especiais_policia where id_alvara = $id_alvara";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for($i=0;$i<$num;$i++) 
        {
            $row = mysqli_fetch_array($result);
            $campo_nome = $row[campo_nome];
            $campo_especial[$campo_nome] = $_POST['campo_value'];
        }

$header=new Header_adm_WEB(); 

$f = new Forms();

$a=new Menu_adm($link);

$nome_pagina= "Bem-Vindo";
$a=new Abre_titulo();
$a->titulo_pagina($nome_pagina);

$f -> abre(12);

$f -> abre(8);
    $f -> abre_card(12);

    echo "<h2>Geração de Alvarás</h2><hr><br>";

    // busca o nome do arquivo para incluir, e gerar a variável $html
    $id_alvara = $_POST[id_alvara];

    settype($id_alvara, 'integer');

   // $campo_especial = array();
    $query = "select campo_nome, campo_value from tb_campos_especiais where id_alvara = $id_alvara and id_pedido = ".$id_pedido;
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++) 
            {
                $row = mysqli_fetch_array($result);
                $campo_nome = $row[campo_nome];
                $campo_value =  (stripslashes($row[campo_value]));
               $campo_especial[$campo_nome] = $campo_value;
            //    echo "<br> nome-> ".$campo_nome;
            //    echo "<br> valor-> ".$campo_value;
            }




    $query = "select html_base, nome from tb_alvaras_tipo where id = ".$id_alvara;
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $html_base =  (stripslashes($row[html_base]));
    $nome_alvara =  (stripslashes($row[nome]));

    $nome_alvara = str_replace("ç", "c", $nome_alvara); // só para melhorar a legibilidade do arquivo final
    $nome_alvara = str_replace("á", "a", $nome_alvara);
    $nome_alvara = str_replace("ã", "a", $nome_alvara);
    $nome_alvara = str_replace("é", "e", $nome_alvara);
    $nome_alvara = str_replace("í", "i", $nome_alvara);
    $nome_alvara = str_replace("ó", "o", $nome_alvara);


    $data_hora=time();

    $prov = $nome_alvara."_".$nome_estabelecimento;
    $prov = preg_replace('/[^a-z0-9_ ]/i', '', $prov);
    $prov = str_replace(" ", "_", $prov);
    $prov = substr($prov, 0, 90)."-".$data_hora.$str_meio;
    $nome_final = $prov.".pdf";

   // $url_provisoria = "sistemas.pc.sc.gov.br%2Ffiscaliza%2Falvaras%2F$nome_final";
    $url_provisoria = "https://sistemas.pc.sc.gov.br/cidadao/alvaras/$nome_final";
    //$url_qrcode = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=https%3A%2F%2F".$url_provisoria;

    $str_png = str_shuffle("abctrrdc6cefghijklmnopqrstuvywz1234567890_abcdefghijklmnopqrstuvywz1234567890_abcdefghijklmnopqrstuvywz1234567890_abcdefghijklmnopqrstuvywz1234567890_");
    $png_nome = substr($str_png, 0, 40);
    include "phpqrcode/qrlib.php";
    QRcode::png($url_provisoria, 'phpqrcode/pngs/'.$png_nome.'.png', 'L', 4, 2);  
    $url_qrcode = "https://sistemas.pc.sc.gov.br/fiscaliza/phpqrcode/pngs/$png_nome.png";      
    
    include "html_base/".$html_base;
    
    // aqui começa o ponto que faz a geração do arquivo html, que vai servir de base para gerar o pdf

    $str_base = "abcdefghijklmnopqrstuvxywz";
    $str_meio = "";
    for($i=0;$i<8;$i++)
    {
        $str_prov = str_shuffle($str_base);
        $str_meio .= substr($str_prov, 0, 1);
    }

    
    $nome_arquivo = $str_meio.".html";
    $myfile = fopen("html_descartavel/$nome_arquivo", "w") or die("erro na abertura do arquivo");
    
    fwrite($myfile, $html);
    fclose($myfile);

   

    $output = exec("python3 html2pdf.py html_descartavel/$nome_arquivo ../cidadao/alvaras/$nome_final 2>&1");

    if(!$output)
    {
        echo "FALHA  python3 html_descartavel/$nome_arquivo alvaras/$nome_final";
    }

    // FAZ A INCLUSÃO DA TABELA DE ALVARÁS
  
    // $query = "insert into tb_alvaras_gerados (id_estabelecimento, id_alvara, arquivo_pdf, data_registro) values ($id_estabelecimento, $id_alvara, '$nome_final', $data_hora)";
    // $result = mysqli_query($link, $query);
    // if(!$result)
    // {   
    //     echo " <div class='alert alert-danger' role='alert'>";
    //     echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL CONCLUIR O CADASTRO<br>$query<br> </h4>".mysqli_error($link);
    //     echo "</div>";
    // }

    // FAZ A ATUALIZAÇÃO DA TABELA PEDIDOS

    $query2 = "select historico from tb_cidadao_pedidos where id= $id_pedido";
    $result2=mysqli_query($link, $query2);
    $row2 = mysqli_fetch_array($result2);
    $historico =  stripslashes($row2[historico]);
    $historico = "ATUALIZAÇÃO EM ".date("d/m/Y H:i")."<br>Pedido deferido pela autoridade policial.<br><hr><br>.$historico";

    // prepara a inclusão da data de validade do alvará
    $d = explode("/", $data_validade);
    $dia = $d[0];
    $mes = $d[1];
    $ano = $d[2];
    $data_validade_insere = mktime(0,0,0,$mes,$dia,$ano);

    $hash_alvara_pdf = hash_file('md5', "../cidadao/alvaras/$nome_final");

    $query = "update tb_cidadao_pedidos set status = 3, historico = '$historico', ultima_movimentacao = $data_hora, concluido = 1, data_conclusao = $data_hora, documento_final = '$nome_final', nome_delegado = '".$_SESSION['usuario_fis_nome']."', data_validade = $data_validade_insere, hash_alvara_pdf = '$hash_alvara_pdf' where id = ".$id_pedido;
    $result = mysqli_query($link, $query);
    if(!$result)
    {   
        echo " <div class='alert alert-danger' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL REGISTRAR A CONCESSÃO DO DOCUMENTO<br>$query<br> </h4>".mysqli_error($link);
        echo "</div>";
    }else{
        // DISPARA EMAIL INFORMANDO O CIDADÃO
       // $x = $cnpj;
        $link_enviar = "https://sistemas.pc.sc.gov.br/cidadao/login.cidadao.php?token=$hash";
        //$titulo_email = "Pedido de Alvará - CNPJ ".$x[0].$x[1]."-".$x[2].$x[3].$x[4]."-".$x[5].$x[6].$x[7]."/".$x[8].$x[9].$x[10].$x[11]."-".$x[12].$x[13]." - DEFERIMENTO";
        $titulo_email = "Pedido de Alvará - CNPJ $cnpj- DEFERIMENTO";
        $mensagem = "<html><body>Prezado(a) Usuario(a),<br>Seu pedido de concessão de Alvará foi DEFERIDO. <br>Você pode ter acesso ao documento PDF <a href = '$link_enviar'>neste link</a> <br>";
        $mensagem .= "<br>O link é exclusivo para seu documento.";
        $mensagem .= "<br>Você também deverá informar os seguintes dados:";
        $mensagem .= "<br>Login: ".$cnpj;
        $mensagem .= "<br>senha: ".$senha;
        $mensagem .= "</body></html>";

        $envia = exec("curl -d 'to=$email&subject=$titulo_email&html=$mensagem' -X POST https://getin.pc.sc.gov.br/sendmail/");
        if (!$envia) {
            $f->msg("ERRO NO ENVIO PARA O EMAIL<br> curl -d 'to=$email&subject=$titulo_email&body=$mensagem' -X POST https://getin.pc.sc.gov.br/sendmail/ ","danger");
        }

    }

    echo "<div style = 'width: 100%; display: table; text-align: right; padding-bottom: 20px;'>";
   // echo "<a href = 'pagina.estabelecimento.php?id_estabelecimento=$id_estabelecimento'  class = 'btn btn-primary'> VOLTAR PARA O ESTABELECIMENTO</a>";
   echo "<a href = 'delegado.painel.php'  class = 'btn btn-primary'> VOLTAR PARA PAINEL DE PEDIDOS APROVADOS</a>";
    echo "  &nbsp;&nbsp;<a href = '../cidadao/alvaras/$nome_final' class = 'btn btn-primary' style = 'float: right;' download> BAIXAR DOCUMENTO</a><br>";
    echo "</div>";

    echo "<object data='../cidadao/alvaras/$nome_final' type='application/pdf' style = 'width:100%; height:680px;'><embed src='alvaras/$nome_final' type='application/pdf' /></object>";

    unlink("html_descartavel/$nome_arquivo");

    $f -> fecha_card();
               
    $f -> fecha();
    
    $f -> abre(4);

    $f -> fecha();
    
    $f -> fecha();
    
    
$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();
/**/
?>