<?php

$s = "display: block; float: left; width:100%; border:1px solid #000000; padding: 6px; text-align: center; font-weight: bolder; margin-top:-6; margin-bottom:8px;";

$html = "";
$html .= "<html><head> <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />\n";
$html .= "<style type='text/css' media='print'>";
$html .= "@media print {    a[href]:after {        content: none    }}";  
$html .= "\nbody {
    font-family: 'Circular Std Book';
    font-style: normal;
    font-weight: normal;
    font-size: 14px;
    color: #55576a;
    background-color: #efeff6;
}";

$html .= "</style><title>PCSC FISCALIZA;</title>";
// AQUI VAMOS FAZER A INCLUSÃO DO CSS INDISPENSÁVEL



$html .= "</head><body>";
$html .= "<div style = 'display: block; width:770px; align: center; float: center; margin-left:auto; margin-right:auto;'>\n";

$html .= " <div class='col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'>";

    $html .= " <div class='col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2' style = 'float: left;'>";

$image = "logo-estado.png";
$imageData = base64_encode(file_get_contents($image));
$src = 'data: ' . mime_content_type($image) . ';base64,' . $imageData;
$html .= '<img src="' . $src . '" style = "height:150px;">';


      //  $html .= "<img src = 'logo-estado.png'>"; //../assets/images/
    $html .= "</div>";

    $html .= " <div class='col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8' style = 'float: left; text-align:center;'>";
        
        $html .= "<br>ESTADO DE SANTA CATARINA<BR>";
        $html .= "SECRETARIA DA SEGURANÇA PÚBLICA<BR>";
        $html .= "DELEGACIA GERAL DA POLÍCIA CIVIL<BR>";
        $html .= "<B>GERÊNCIA DE FISCALIZAÇÃO DE JOGOS E DIVERSÕES<BR>";
        $html .= "E DE PRODUTOS CONTROLADOS 6</B>";

    $html .= "</div>";
        
    $html .= " <div class='col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2' style = 'float: left;'>";
   
    $image = "logo-pc.png";
    $imageData = base64_encode(file_get_contents($image));
    $src = 'data: ' . mime_content_type($image) . ';base64,' . $imageData;
    $html .= '<img src="' . $src . '" style = "height:150px;">';
    //     $html .= "<img src = 'logo-pc.png'>"; //../assets/images/
    $html .= "</div>";

$html .= "</div>";


    
$html .= " <div class='col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12' style = 'display: table; text-align:center; '>";

$html .= "<br><h1 style = 'font-weight: 900;'>POLICIA CIVIL</h1>";
$html .= "<h2 style = 'font-weight: 700;'>ALVARÁ ANUAL 2022</h2><br>";
    $html .= "<p>Razão Social</p>";
    $html .= "<div style = '$s'>  $razao_social  </div>";


$html .= "<p>Nome Fantasia</p>";
$html .= "<div style = '$s'>   $nome_estabelecimento </div>";
$html .= "</div>";

$html .= " <div class='col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'>";
    $html .= " <div class='col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4' style = 'float: left;'>";
        $html .= "<p>CNPJ</p>";
        $html .= "<div style = '$s'>   $cnpj </div>";
    $html .= "</div>";

    $html .= " <div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3' style = 'float: left;'>";
        $html .= "<p>Cód. Unid. Policial</p>";
        $html .= "<div style = '$s'>   $cod_unid_policial</div>";
    $html .= "</div>";

    $html .= " <div class='col-xl-5 col-lg-5 col-md-5 col-sm-5 col-5' style = 'float: left;'>";
        $html .= "<p>Gênero</p>";
        $html .= "<div style = '$s'>   $nome_atividade </div>";
    $html .= "</div>";
$html .= "</div>";

$html .= " <div class='col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12' style = 'display: table;'>";
$html .= " <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6' style = 'float: left;'>";
        $html .= "<p>Endereço do Estabelecimento</p>";
        $html .= "<div style = '$s'>$endereco_rua </div>";
    $html .= "</div>";

    $html .= " <div class='col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2' style = 'float: left;'>";
        $html .= "<p>Número</p>";
        $html .= "<div style = '$s'>$endereco_numero </div>";
    $html .= "</div>";

    $html .= " <div class='col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4' style = 'float: left;'>";
        $html .= "<p>Bairro</p>";
        $html .= "<div style = '$s'>   $endereco_bairro </div>";
    $html .= "</div>";
$html .= "</div>";

$html .= " <div class='col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12' style = 'display: table;'>";
$html .= " <div class='col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4' style = 'float: left;'>";
        $html .= "<p>Município</p>";
        $html .= "<div style = '$s'>   $nome_municipio </div>";
    $html .= "</div>";

    $html .= " <div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3' style = 'float: left;'>";
        $html .= "<p>CEP</p>";
        $html .= "<div style = '$s'>   $endereco_cep</div>";
    $html .= "</div>";

    $html .= " <div class='col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2' style = 'float: left;'>";
        $html .= "<p>Taxa Est.</p>";
        $html .= "<div style = '$s'>   $taxa_estadual</div>";
    $html .= "</div>";

    $html .= " <div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3' style = 'float: left;'>";
        $html .= "<p>Expedição</p>";
        $html .= "<div style = '$s'>   $data_expedicao</div>";
    $html .= "</div>";
$html .= "</div>";

$html .= " <div class='col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12' style = 'display: table;'>";
    $html .= " <div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6' style = 'float: left;'>";
        $html .= "<p>Nome do Proprietário/Gerente</p>";
        $html .= "<div style = '$s'>   $nome_proprietario </div>";
    $html .= "</div>";

    $html .= " <div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3' style = 'float: left;'>";
        $html .= "<p>Idade Permitida</p>";
        $html .= "<div style = '$s'>   $idade_permitida</div>";
    $html .= "</div>";

    $html .= " <div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3' style = 'float: left;'>";
        $html .= "<p>VALIDADE</p>";
        $html .= "<div style = '$s'>   $data_validade</div>";
    $html .= "</div>";
$html .= "</div>";

$html .= "<hr>";
$html .= " <div class='col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12' style = 'display: table;'>";

    $html .= "<p style = 'text-align: justify'>A <strong>Polícia Civil do Estado de Santa Catarina</strong>, em conformidade com o art. 106, inciso VI, da Constituição Estadual, Decreto Estadual n.° 894/1972 e Resolução n.o 002/GAB/DGPC/SSP/2020, por intermédio da Autoridade Policial que esta subscreve, atendendo ao requerimento da parte interessada e considerando a documentação apresentada, concede <strong>ALVARÁ POLICIAL</strong> para o funcionamento do estabelecimento acima mencionado, no horário entre <strong>13h00 às 04h00</strong>, conforme autorização da Prefeitura Municipal, mediante o cumprimento da legislação vigente e estando sujeito à fiscalização das autoridades competentes.</p>";
    $html .= "<br>";
    $html .= "<p style = 'text-align: justify'>A presente licença deverá estar em local visível e <strong>NÃO AUTORIZA a exploração de atividades com música</strong> sem a apresentação de Licença Policial Mensal expedido por este Órgão.</p>";

    $html .= "</div>";
    $html .= "</div>";

$html .= ' </body></html>';

//echo $html;

// $str_base = "abcdefghijklmnopqrstuvxywz";
// $str_meio = "";
// for($i=0;$i<8;$i++)
// {
//     $str_prov = str_shuffle($str_base);
//     $str_meio .= substr($str_prov, 0, 1);
// }

// $nome_arquivo = $str_meio.".html";
// $myfile = fopen("pdfs/$nome_arquivo", "w") or die("erro na abertura do arquivo");

// fwrite($myfile, $html_final);
// fclose($myfile);
// $data_hora = time();

// $nome_final = "Relatorio_fiscaliza_"."-".$data_hora.".pdf";
// $output = shell_exec("python3 html2pdf.py /home/marcos/fiscaliza/pdfs/$nome_arquivo /home/marcos/fiscaliza/pdfs/$nome_final");

?>