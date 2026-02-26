<?php
//include "mysql.conecta.pcporelas.php";
$str_base = "abcdefghijklmnopqrstuvxywz";
$str_meio = "";
for($i=0;$i<8;$i++)
{
    $str_prov = str_shuffle($str_base);
    $str_meio .= substr($str_prov, 0, 1);
}

$nome_arquivo = $str_meio.".html";
$myfile = fopen("html_descartavel/$nome_arquivo", "w") or die("erro na abertura do arquivo");




$s = "display: block; float: left; width:100%; border:1px solid #000000; padding: 6px; text-align: center; font-weight: bolder; margin-top:-6; margin-bottom:8px;";

$html_print = "";
$html_print .= "<html><head> <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />\n";
$html_print .= "<style type='text/css' media='print'>";
$html_print .= "@media print {    a[href]:after {        content: none    }}";  

$html_print .= "</style><title>PCSC FISCALIZA;</title>";
// PARA QUEM CHEGAR AQUI, FIZ O CSS INLINE PORQUE FOI O ÚNICO JEITO QUE LEU NA INTERFACE FINAL

$html_print .= "</head><body>";
$html_print .= "<div style = 'display: block; width:770px; align: center; float: center; margin-left:auto; margin-right:auto;'>\n";

$html_print .= "<table style = 'width: 100%'>";
$html_print .=  "<tr>";
    $html_print .= " <td style = ' width: 15%;'>";

        $image = "logo-estado.png";
        $imageData = base64_encode(file_get_contents($image));
        $src = 'data: ' . mime_content_type($image) . ';base64,' . $imageData;
        $html_print .= '<img src="' . $src . '" style = "height:150px;">';

    $html_print .= "</td>";

    $html_print .= " <td style = ' width: 70%; text-align:center;'>";
        
        $html_print .= "<br><p style = 'font-family: Arial, Helvetica, sans-serif;'>ESTADO DE SANTA CATARINA<BR>";
        $html_print .= "POLÍCIA CIVIL<BR>";
        $html_print .= "DELEGACIA GERAL<BR>";
        $html_print .= "<B>GERÊNCIA DE FISCALIZAÇÃO DE JOGOS E DIVERSÕES<BR>";
        $html_print .= "E DE PRODUTOS CONTROLADOS<br>";
        $html_print .= "Unidade: ".$cod_unid_policial."</p></B>";

    $html_print .= "</td>";
    
    $html_print .= " <td style = ' width: 15%;'>";
   
        $image = "logo-pc.png";
        $imageData = base64_encode(file_get_contents($image));
        $src = 'data: ' . mime_content_type($image) . ';base64,' . $imageData;
        $html .= '<img src="' . $src . '" style = "height:150px;">';
    //     $html .= "<img src = 'logo-pc.png'>"; //../assets/images/
    $html_print .= "</td>";

$html_print .= "</tr></table>";

$html_print .= "<table style = 'width: 100%'>";
$html_print .= "<tr><td style = 'text-align:center;'>"; 
    $html_print .= "<br><h1 style = 'font-weight: 900;font-family: Arial, Helvetica, sans-serif; font-size: 48px'>POLICIA CIVIL</h1>";
    $html_print .= "<br><h3 style = 'font-weight: 900;font-family: Arial, Helvetica, sans-serif; font-size: 24px'>nome do documento</h3>";
    $html_print .= "<div style = 'display: block; width: 100%; background-color: #000000; color: #ffffff;'>";
    $html_print .= "<h2 style = 'font-weight: 700; font-family: Arial, Helvetica, sans-serif;font-size: 24px'>DADOS DO SOLICITANTE</h2>";
    $html_print .= "<div>";
$html_print .= "</td></tr>";
$html_print .= "</table>";

$html_print .= "<table style = 'width: 100%'>";
$html_print .= "<tr><td style = 'text-align:left;'>"; 
    //$html_print .= "<p>";
    
    $html_print .= "<p> Estabelecimento: <strong>$nome_estabelecimento</strong><p>";
    $html_print .= "<p> Razão Social: <strong>$razao_social</strong><p>";
    $html_print .= "<p> CNPJ: <strong>$cnpj</strong><p>";
    $html_print .= "<p> Endereço: <strong>$endereco_rua, $endereco_numero</strong><p>";
    $html_print .= "<p> Bairro: <strong>$endereco_bairro</strong> /  Municipio: <strong>$nome_municipio</strong> / CEP $endereco_cep<p>";
    $html_print .= "<p> Telefone fixo: <strong>$telefone_fixo</strong><p>";
    $html_print .= "<p> Telefone celular: <strong>$telefone_celular</strong><p>";
    $html_print .= "<p> Email: <strong>$email</strong><p>";
    $html_print .= "<p> Proprietário: <strong>$nome_proprietario</strong><p>";
    $html_print .= "<p> Observações: <strong>".nl2br($obs_cidadao)."</strong><p>";
    $html_print .= "<p> Nome do requerente: <strong>$requerente_nome</strong><p>";
    $html_print .= "<p> Data de nascimento do requerente: <strong>$requerente_data_nasc</strong><p>";
    $html_print .= "<p> CPF do Requerente: <strong>$requerente_cpf</strong><p>";
    $html_print .= "<p> Endereço do endereço: <strong>$requerente_endereco</strong><p>";
    $html_print .= "<p> Telefone do Requerente: <strong>$requerente_telefone</strong><p>";
    $html_print .= "<p> Email do requerente: <strong>$requerente_email</strong><p>";
    $html_print .= "<p> Houve pedido de Dispensa: <strong>$pedido_dispensa</strong><p>";


    $query = "select area, valor from tb_area_vistoria where id = ".$id_area_vistoria;
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $area = stripslashes($row[area]);
    $valor = stripslashes($row[valor]);

    $html_print .= "<p>Área de vistoria policial: <strong>$area</strong><p>";
    $html_print .= "<p>Valor da vistoria policial: <strong>$valor</strong><p>";


if (strlen($pedido_dispensa) > 3) {
    $html_print .= "<p>Pediu dispensa de taxa: <strong>SIM</strong><p>";
    $html_print .= "<p>Justificativa do pedidos de dispensa: <strong>".nl2br($pedido_dispensa)."</strong><p>";
}

    // inclui os campos especiais, se houver
$campo_nome = array();
$query = "select campo_nome, campo_label from tb_campos_especiais_ref ";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for($i=0;$i<$num;$i++) 
    {
        $row = mysqli_fetch_array($result);
        $campo_nome = stripslashes($row[campo_nome]);
        $campo_label[$campo_nome]= (stripslashes($row[campo_label]));
    }

$query = "select campo_nome, campo_value from tb_campos_especiais where id_pedido = $id_pedido";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for($i=0;$i<$num;$i++) 
    {
        $row = mysqli_fetch_array($result);
        $campo_nome = stripslashes($row[campo_nome]);
        $campo_value = stripslashes($row[campo_value]);
        $html_print .= "<br> ".$campo_label[$campo_nome].": <strong>$campo_value</strong>";
    
        
    }


//$html_print .= "</p>";
$html_print .= "</td></tr>";
$html_print .= "</table>";

$html_print .= "</div></body>";

fwrite($myfile, $html_print);
fclose($myfile);
$data_hora = time();


$nome_final = "paginarosto_".$id_pedido."_".$data_hora.".pdf";


$output = exec("python3 html2pdf.py html_descartavel/$nome_arquivo arquivos_cidadao_prev/$nome_final 2>&1");

if(!$output)
{
    echo "FALHA  python3 html_descartavel/$nome_arquivo arquivos_cidadao_prev/$nome_final";
}

$data_carregamento =  time();

$query2 = "insert into tb_cidadao_arquivos (id_pedido, data_carregamento, id_documento_tipo, arquivo, aprovado) values ($id_pedido, $data_pedido, 69, '$nome_final', 'S')";
$result2 = mysqli_query($link, $query2);
if(!$result2){
    echo "<h1>PAU -> $query2</h1>";
}




?>