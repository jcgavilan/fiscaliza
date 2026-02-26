<?php


include "mysql.conecta.rep.php";
$string_aleatoria = str_shuffle("ABCEDEFGHIJKLMEN");

  $query = "update tb_cidadao_pedidos set string_aleatoria = '$string_aleatoria' where id = ".$id_pedido;
  $result=mysqli_query($link, $query);

  
$id_pedido = (int)$_GET[id_pedido];
$data_pedido = (int)$_GET[data_pedido];

// A QUERY ABAIXO VISA EVITAR QUE SE COLOQUEM IDS ALEATÓRIOS DA VARIAVEL GET
$query = "select * from tb_cidadao_pedidos where id = $id_pedido AND data_pedido = $data_pedido";
$result=mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
$id_pedido = $row[id];
$string_aleatoria =  (stripslashes($row[string_aleatoria]));

$s = "display: block; float: left; width:100%; border:1px solid #000000; padding: 6px; text-align: center; font-weight: bolder; margin-top:-6; margin-bottom:8px;";

$html_print = "";
$html_print .= "<html><head> <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />\n";
$html_print .= "<style type='text/css' media='print'>";
$html_print .= "@media print {    a[href]:after {        content: none    }}";  

$html_print .= "</style><title>PCSC FISCALIZA;</title>";
// PARA QUEM CHEGAR AQUI, FIZ O CSS INLINE PORQUE FOI O ÚNICO JEITO QUE LEU NA INTERFACE FINAL
$html_print .= "<link rel='stylesheet' href='assets/vendor/fonts/simple-line-icons/css/simple-line-icons.css'>";
$html_print .= "<style>    @media print {
      .btn-imprimir {
        display: none;
      }
    }
  </style>";

$html_print .= "</head><body><span style = 'color:#ffffff;' class='btn-imprimir'>$string_aleatoria</span>";
$html_print .= "<div style = 'display: block; width:770px; align: center; float: center; margin-left:auto; margin-right:auto;'>\n";

$html_print .= "<div style ='display:block; width: 100%; text-align: right'>";
$html_print .= "<a href='javascript:history.back()' class='btn-imprimir'><i class='icon-arrow-left-circle'></i></a> &nbsp; <a href = 'javascript:window.print();' class='btn-imprimir'><i class='icon-printer'></i></a>";
$html_print .= "</div>";

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
        $html_print .= '<img src="' . $src . '" style = "height:150px;">';
    //     $html .= "<img src = 'logo-pc.png'>"; //../assets/images/
    $html_print .= "</td>";

$html_print .= "</tr></table>";

$html_print .= "<table style = 'width: 100%'>";
$html_print .= "<tr><td style = 'text-align:center;'>"; 
    $html_print .= "<h2 style = 'font-weight: 700; font-family: Arial, Helvetica, sans-serif;font-size: 24px'>DADOS DO SOLICITANTE</h2>";
    $html_print .= "<div>";
$html_print .= "</td></tr>";
$html_print .= "</table>";

$html_print .= "<table style = 'width: 100%'>";

// CONTINUA RESULTADOS DA QUERY



$id = stripslashes($row[id]);
$data_pedido = stripslashes($row[data_pedido]); if ($data_pedido != 0) {$html_print .= "<tr><td>Data do Pedido</td><td>".date("d/m/Y", $data_pedido)."</td></tr>";}
$nome_estabelecimento = stripslashes($row[nome_estabelecimento]); if ($nome_estabelecimento != '') {$html_print .= "<tr><td>Estabelecimento</td><td>$nome_estabelecimento</td></tr>";}
$razao_social = stripslashes($row[razao_social]);if ($razao_social != "") {$html_print .= "<tr><td>Razão Social</td><td>$razao_social</td></tr>";}
$id_ramo_atividade = stripslashes($row[id_ramo_atividade]); if ($id_ramo_atividade != "") { $html_print .= "<tr><td>Ramo de Atividade</td><td>$id_ramo_atividade</td></tr>";}
$id_area_vistoria = stripslashes($row[id_area_vistoria]);
if ($id_area_vistoria != 0) {
  $query2 = "select area from tb_area_vistoria where id = $id_area_vistoria";
  $result2=mysqli_query($link, $query2);
  $row2 = mysqli_fetch_array($result2);
  $area = stripslashes($row2[area]);
  $html_print .= "<tr><td>Área de Vistoria</td><td>$area</td></tr>";
}
$cnpj = stripslashes($row[cnpj]);
if ($cnpj != "") {$html_print .= "<tr><td>CNPJ</td><td>$cnpj</td></tr>";}
$endereco_rua = stripslashes($row[endereco_rua]);
$endereco_numero = stripslashes($row[endereco_numero]);
$endereco_bairro = stripslashes($row[endereco_bairro]);
$endereco_cep = stripslashes($row[endereco_cep]);
$html_print .= "<tr><td>Endereço:</td><td>$endereco_rua, $endereco_numero, $endereco_bairro - CEP $endereco_cep</td></tr>";

$id_municipio = stripslashes($row[id_municipio]);
$query2 = "select nome from tb_municipios_ibge where ibge_reduzido = $id_municipio";
$result2=mysqli_query($link, $query2);
$row2 = mysqli_fetch_array($result2);
$nome = stripslashes($row2[nome]);
$html_print .= "<tr><td>Municipio</td><td>$nome</td></tr>";

$telefone_fixo = stripslashes($row[telefone_fixo]); if ($telefone_fixo != "") {$html_print .= "<tr><td>Telefone Fixo</td><td>$telefone_fixo</td></tr>";}
$telefone_celular = stripslashes($row[telefone_celular]);
if ($telefone_celular != "") {$html_print .= "<tr><td>Telefone Celular</td><td>$telefone_celular</td></tr>";}
$email = stripslashes($row[email]);if ($email != "") {$html_print .= "<tr><td>Email</td><td>$email</td></tr>";}
$nome_proprietario = stripslashes($row[nome_proprietario]);if ($nome_proprietario != "") {$html_print .= "<tr><td>Nome do Proprietário</td><td>$nome_proprietario</td></tr>";}
$obs_cidadao = stripslashes($row[obs_cidadao]);if ($obs_cidadao != "") {$html_print .= "<tr><td>Observações do Cidadão</td><td>$obs_cidadao</td></tr>";}

$valor_taxa = stripslashes($row[valor_taxa]);if ($valor_taxa != "") {$html_print .= "<tr><td>Valor da Taxa</td><td>$valor_taxa</td></tr>";}
$cidadao_nome = stripslashes($row[cidadao_nome]); if ($cidadao_nome != "") {$html_print .= "<tr><td>Nome do Cidadão</td><td>$cidadao_nome</td></tr>";}
$cidadao_cpf = stripslashes($row[cidadao_cpf]); if ($cidadao_cpf != "") {$html_print .= "<tr><td>CPF do cidadão</td><td>$cidadao_cpf</td></tr>";}
$requerente_nome = stripslashes($row[requerente_nome]); if ($requerente_nome != "") {$html_print .= "<tr><td>Nome do Requerente</td><td>$requerente_nome</td></tr>";}
$requerente_data_nasc = stripslashes($row[requerente_data_nasc]); if ($requerente_data_nasc != "") {$html_print .= "<tr><td>Data de nascimento do requerente</td><td>$requerente_data_nasc</td></tr>";}
$requerente_cpf = stripslashes($row[requerente_cpf]); if ($requerente_cpf != "") {$html_print .= "<tr><td>CPF do requerente: </td><td>$requerente_cpf</td></tr>";}
$requerente_endereco = stripslashes($row[requerente_endereco]);if ($requerente_endereco != "") {$html_print .= "<tr><td>Endereço do Requerente</td><td>$requerente_endereco</td></tr>";}
$requerente_telefone = stripslashes($row[requerente_telefone]);if ($requerente_telefone != "") {$html_print .= "<tr><td>Telefone do Requerente</td><td>$requerente_telefone</td></tr>";}
$requerente_email = stripslashes($row[requerente_email]); if ($requerente_email != "") {$html_print .= "<tr><td>Email do Requerente</td><td>$requerente_email</td></tr>";}
$pedido_dispensa = stripslashes($row[pedido_dispensa]);if ($pedido_dispensa != 0) {$html_print .= "<tr><td>Houve Pedido de Dispensa de pagto?</td><td>SIM</td></tr>";}

// AGORA BUSCA OS DADOS DOS CAMPOS OPCIONAIS
$campo_array = array();
$query = "select campo_nome, campo_label from tb_campos_especiais_ref";
$result = mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for ($i = 0; $i < $num; $i++) {
    $row = mysqli_fetch_array($result);
    $campo_nome = stripslashes($row[campo_nome]);
    $campo_label = stripslashes($row[campo_label]);
    $campo_array[$campo_nome] = $campo_label;
  }

$query = "select campo_nome, campo_value from tb_campos_especiais where id_pedido = $id_pedido";
$result = mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for ($i = 0; $i < $num; $i++) {
    $row = mysqli_fetch_array($result);
    $campo_nome = stripslashes($row[campo_nome]);
    $campo_value = stripslashes($row[campo_value]);
    $html_print .= "<tr><td>Houve ".$campo_array[$campo_nome]."</td><td>".$campo_value."</td></tr>";
}

// $query = "select resumo from tb_empresa_resumo where id_pedido = $id_pedido";
// $result=mysqli_query($link, $query);
// $row = mysqli_fetch_array($result);
// $resumo =  (stripslashes($row[resumo]));




$html_print .= $resumo;

$html_print .= "</table>";
$html_print .= "</div></body>";

echo $html_print;


?>