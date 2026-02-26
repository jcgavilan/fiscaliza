<?php
//include "mysql.conecta.pcporelas.php";


$s = "display: block; float: left; width:100%; border:1px solid #000000; padding: 6px; text-align: center; font-weight: bolder; margin-top:-6; margin-bottom:8px;";

$html_print = "";
$html_print .= "<div style = 'display: block; width:100%; align: center; float: center; margin-left:auto; margin-right:auto;'>\n";





    //$html_print .= "<p>";
    $x = $cnpj;

    $html_print .= "\n<table class='table table-striped'>";
    
    $html_print .= "<tr><td> Estabelecimento: <strong>$nome_estabelecimento</strong></td></tr>";
    $html_print .= "<tr><td> Razão Social: <strong>".$x[0].$x[1]."-".$x[2].$x[3].$x[4]."-".$x[5].$x[6].$x[7]."/".$x[8].$x[9].$x[10].$x[11]."-".$x[12].$x[13]."</strong></td></tr>";
    $html_print .= "<tr><td> CNPJ: <strong>$cnpj</strong></td></tr>";
    $html_print .= "<tr><td> Ramo de Atividade: <strong>$id_ramo_atividade</strong></td></tr>";

    $query = "select area, valor from tb_area_vistoria where id = $id_area_vistoria";
    $result=mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $area_vistoria = stripslashes($row[area]);
    $valor_vistoria = stripslashes($row[valor]);

    $html_print .= "<tr><td>Área Vistoria: <strong>$area_vistoria</strong></td></tr>";
    $html_print .= "<tr><td>Valor da  Vistoria: <strong>$valor_vistoria</strong></td></tr>";

    $html_print .= "<tr><td> Endereço: <strong>$endereco_rua, $endereco_numero</strong></td></tr>";
    $html_print .= "<tr><td> Bairro: <strong>$endereco_bairro</strong> /  Municipio: <strong>$nome_municipio</strong> / CEP $endereco_cep</td></tr>";
    $html_print .= "<tr><td> Telefone fixo: <strong>$telefone_fixo</strong></td></tr>";
    $html_print .= "<tr><td> Telefone celular: <strong>$telefone_celular</strong></td></tr>";
    $html_print .= "<tr><td> Email: <strong>$email</strong></td></tr>";
    $html_print .= "<tr><td> Proprietário: <strong>$nome_proprietario</strong></td></tr>";
    if (strlen(strip_tags($obs_cidadao)) > 3) {
        $html_print .= "<tr><td> Observações: <strong>".nl2br($obs_cidadao)."</strong></td></tr>";
    }
    
    $html_print .= "<tr><td> Nome do requerente: <strong>$requerente_nome</strong></td></tr>";
    $html_print .= "<tr><td> Data de nascimento do requerente: <strong>$requerente_data_nasc</strong></td></tr>";
    $html_print .= "<tr><td> CPF do Requerente: <strong>$requerente_cpf</strong></td></tr>";
    $html_print .= "<tr><td> Endereço do endereço: <strong>$requerente_endereco</strong></td></tr>";
    $html_print .= "<tr><td> Telefone do Requerente: <strong>$requerente_telefone</strong></td></tr>";
    $html_print .= "<tr><td> Email do requerente: <strong>$requerente_email</strong></td></tr>";
    if(strlen($pedido_dispensa) < 2)
    {
        $pedido_dispensa = "Não";
    }
    $html_print .= "<tr><td> Houve pedido de Dispensa: <strong>$pedido_dispensa</strong></td></tr>";

    // inclui os campos especiais, se houver
$campo_nome = array();
$query = "select campo_nome, campo_label from tb_campos_especiais_ref where id_alvara = ".$id_alvara;
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
        $html_print .= "<tr><td> ".$campo_label[$campo_nome].": <strong>$campo_value</strong</td></tr>";    
    }
    $html_print .= "</table>";
$html_print .= "</div>";

$pagina_resumo = addslashes($html_print);


?>