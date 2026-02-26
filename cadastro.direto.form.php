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

$tipo = $_GET['tipo'];

$f -> abre(12);
$f -> abre_card(12);

echo "<h1>Cadastro Direto de Solicitação de Alvará</h1><hr><br>";
$f -> abre_form("cadastro.direto.form.php");
echo "<h2>Formulário de dados do solicitante</h2><br>";
$id_documento = (int)$_POST[id_alvara];
$query = "select nome, mensagem, possui_ramo_atividade, requer_vistoria from tb_alvaras_tipo where id = $id_documento";
$result=mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
$mensagem =  (stripslashes($row[mensagem]));
$nome_documento =  (stripslashes($row[nome]));
$possui_ramo_atividade = $row[possui_ramo_atividade];
$requer_vistoria = $row[requer_vistoria];

echo "<h4 class = 'policia'>Documento: $nome_documento</h4>";

echo "<p class = 'policia'>$mensagem</p>";

$query = "select cnae, descricao from tb_cnaes where id_alvara = $id_documento";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
if ($num > 0) {
    echo "<div style = 'display: table; width: 100%;  background-color:#f5f5f5; padding: 16px;'>";
    echo "<p><strong class = 'policia'>Este alvará é necessário para a pessoa jurídica que explora as seguintes atividades econômicas:</strong></p>";
  //  echo "<ul style = 'margin-left:24px;'>";

$f->abre(12);
for($i=0;$i<$num;$i++) 
        {
            $row = mysqli_fetch_array($result);
            $cnae = (stripslashes($row[cnae]));
            $descricao = (stripslashes($row[descricao]));
            $f->abre(4);
            echo "<ul><li> <a href = '#' title = '$descricao'></a>$cnae</li></ul>";
            $f->fecha();
                   
        }
     //   echo "</ul>";
$f->fecha();
echo "</div>";
}
?>


<?php
$ast = " <span style = 'color: #ff0000;'><strong>*</strong></span>";
$colunas_cel = 12;
echo "<br><br><p><strong class= 'policia'>ATENÇÃO:</strong> todos os campos marcados com asterisco vermelho ($ast) são <strong class='policia'>obrigatórios.</strong></p>";
//$f -> abre_form("formulario.cidadao.requerimento.php");
$f -> abre_form("formulario.cidadao.valida.php");
// echo "<form  enctype = 'multipart/form-data' action='formulario.cidadao.valida.php' method='post' enctype='multipart/form-data' onsubmit='return validateForm();'>";
$f->f_input_coluna(6, "Nome Fantasia $ast", "nome_estabelecimento", "$nome_estabelecimento");
$f->f_input_coluna(6, "Razão Social $ast", "razao_social", "$razao_social");
$f->f_input_coluna_mask(6, "CNPJ $ast", "cnpj", "$cnpj", 'cnpj');
$f->f_input_coluna_mask(6, "Confirma CNPJ $ast", "cnpj_confirma", "$cnpj", 'cnpj');

echo "<br><div style = 'display: table; width: 100%;  background-color:#f5f5f5;padding: 16px; margin-top:12px!important; border-top: 12px solid #ffffff;' >";
echo "<h6 class = 'policia'><strong class='policia'>ATENÇÃO</strong>: Toda a comunicação a respeito do processo de emissão de alvará, inclusive o recebimento do arquivo, serão realizados por meio deste email.</h6>";
$f->f_input_coluna(6, "Email $ast", "email", "$email");
$f->f_input_coluna(6, "Confirma Email $ast", "email_confirma", "$email");
echo "</div>";

//$f->f_input_coluna(6, "Ramo de Atividade", "ramo_atividade", "$nome_atividade");
$colunas=6;
if ($possui_ramo_atividade == 1) {

$query = "select * from tb_cnaes where id_alvara = $id_documento order by cnae asc";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
if ($num == 0) {
    $f->f_input_coluna(6, "Ramo de Atividade", "id_ramo_atividade", ""); // caso não tenha cnae cadastrado, vai um campo de texto, com preenchimento livre.
}else{
    echo "<div class='col-xl-".$colunas." col-lg-".$colunas." col-md-".$colunas_cel." col-sm-".$colunas_cel." col-".$colunas_cel."' style = 'float: left; padding-right:10px;'>";
    echo "<div class='form-group'>";
    echo "<label for='inputText3' class='col-form-label'><span class = 'policia'>Ramos de Atividade $ast</span></label><br>";
    echo "<select class='form-control' id='id_ramo_atividade'  name='id_ramo_atividade'>";
    echo "<option value='' selected disabled></option>";
    for($i=0;$i<$num;$i++) // foi incluindo a contagem até 8 para ir somente até o superior/cursando no caso de criança ou adolescente
            {
                $row = mysqli_fetch_array($result);
                $id = $row[id];
                $cnae =  (stripslashes($row[cnae]));
            
                echo "<option value='".$id."'"; //   echo "<option value='".$cnae."'"; // ALTEREI PARA BUSCAR A TAXA
                echo ">".$cnae."</option>";         
            }
    echo "</select></div>";
    $f->fecha();
}
}

$f->f_input_coluna(6, "Rua $ast", "endereco_rua", "$endereco_rua");
$f->f_input_coluna(2, "Numero $ast", "endereco_numero", "$endereco_numero");
$f->f_input_coluna(4, "Bairro $ast", "endereco_bairro", "$endereco_bairro");
$f->f_input_coluna_mask(2, "CEP $ast", "endereco_cep", "$endereco_cep", 'cep');
$colunas=4;
echo "<div class='col-xl-".$colunas." col-lg-".$colunas." col-md-".$colunas_cel." col-sm-".$colunas_cel." col-".$colunas_cel."' style = 'float: left; padding-right:10px;'>";
echo "<div class='form-group'>";
echo "<label for='inputText3' class='col-form-label'><span class = 'policia'>Município $ast</span></label><br>";
echo "<select class='form-control' id='id_municipio'  name='id_municipio' required = ''>";
echo "<option value='' selected disabled></option>";
$query = "select * from tb_municipios_ibge  order by nome asc";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for($i=0;$i<$num;$i++) 
    {
        $row = mysqli_fetch_array($result);
        $ibge = $row[ibge_reduzido];
        $nome =  (stripslashes($row[nome]));
        echo "<option value='".$ibge."'";
        echo ">".$nome."</option>";         
    }
echo "</select></div>";
$f->fecha();
//$f->f_input_coluna(4, "Município", "municipio", "$nome_municipio");
$f->f_input_coluna_mask_nao_obrigatorio(3, "Telefone Fixo", "telefone_fixo", "$telefone_fixo", 'telefone_fixo');
$f->f_input_coluna_mask(3, "Telefone Celular $ast", "telefone_celular", "$telefone_celular", 'telefone_celular');
$f->f_input_coluna(3, "Responsável legal da empresa $ast", "nome_proprietario", "$nome_proprietario");

//$f->f_input_coluna(4, "Idade Permitida", "idade_permitida", "$idade_permitida");

$colunas=3;
echo "<div class='col-xl-".$colunas." col-lg-".$colunas." col-md-".$colunas_cel." col-sm-".$colunas_cel." col-".$colunas_cel."' style = 'float: left; padding-right:10px;'>";
if($requer_vistoria == 1){
echo "<div class='form-group'>";
echo "<label for='inputText3' class='col-form-label'><span class = 'policia'>Área do Estabelecimento (para vistoria) $ast</span></label><br>";
echo "<select class='form-control' id='id_area_vistoria'  name='id_area_vistoria' required=''>";

$query = "select * from tb_area_vistoria  order by id asc";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
echo "<option value='' selected disabled></option>";
for($i=0;$i<$num;$i++) 
        {
            $row = mysqli_fetch_array($result);
            $id = $row[id];
            $area = (stripslashes($row[area]));
            $valor =  (stripslashes($row[valor]));
            echo "<option value='".$id."'";
            echo ">".$area."(R$ $valor)</option>";         
        }
echo "</select></div>";
}

$f->fecha();



// BUSCA AS REGRAS ESPECIAIS PARA OS DOCUMENTOS OBRIGATÓRIOS PARA O ALVARÁ
$f->abre(12); 
echo "<div STYLE = 'display: table; padding: 18px;'>";

$query = "select * from tb_regras_documentos_obrigatorios  where id_alvara = $id_documento";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for($i=0;$i<$num;$i++) 
    {
        $row = mysqli_fetch_array($result);
        $id_documento_tipo = $row[id_documento_tipo];
        $regra =  (stripslashes($row[regra]));
        $f->f_radio_simnao_edit($regra, 'regra_'.$id_documento_tipo, 1);
    }
echo "</div>";

echo "<div style='display: table; width:100%; padding: 16px; background-color:#f5f5f5; margin-bottom:18px;'>";
echo "<h4 class='policia'>Qualificação do Requerente</h4>";
$f -> abre(12);
$f->f_input_coluna(4, "Seu nome completo $ast", "requerente_nome", $_SESSION['cidadao_nome']);
$f->f_input_coluna_mask(4, "Sua data de nascimento $ast", "requerente_data_nasc", "", 'date');
$f->f_input_coluna_mask(4, "Seu CPF $ast", "requerente_cpf", $_SESSION['cidadao_cpf'], 'cpf');

$f->f_input_coluna(4, "Seu Endereço $ast", "requerente_endereco", "");
$f->f_input_coluna(4, "Telefones de Contato $ast", "requerente_telefone", "");
$f->f_input_coluna(4, "Seu email pessoal de contato $ast", "requerente_email", "");

$f -> fecha();
$f -> f_radio_simnao_edit("<br><strong class='policia'>O requerente é o responsável ou proprietário da empresa?</strong> <span class = 'policia'>(Caso não seja, deverá anexar procuração na próxima etapa)</span>", "requerente_responsavel", 1);

echo "</div>";

$f -> f_area("Observações (opcional)", 'obs_cidadao', '');


// BUSCA OS CAMPOS ESPECIAIS PARA ESTE ALVARÁ

$query = "select * from tb_campos_especiais_ref where id_alvara = $id_documento";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for($i=0;$i<$num;$i++) 
    {
        $row = mysqli_fetch_array($result);
        $campo_nome = $row[campo_nome];
        $radio = $row[radio];
        $radio_sim = $row[radio_sim];
        $campo_label =  (stripslashes($row[campo_label]));
        if ($radio == 0) {
            $f->f_input_coluna(4, "$campo_label", "$campo_nome", "");
        }else{
            echo "<div class='col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6' style = 'float: left; padding-right:10px;'>";
            echo "<div class='form-group'>";
            echo "<label for='inputText3' class='col-form-label'><span class='policia'>$campo_label</span></label> &nbsp;";
            echo "<label class='custom-control custom-radio custom-control-inline'>";
            echo "<input type='radio' name='$campo_nome' class='custom-control-input' value='SIM' required ";
                if($radio_sim == 1){echo "checked";}
            echo "><span class='custom-control-label'> <span class = 'policia'>Sim</span>  &nbsp;</span>";
            echo "</label>";
            echo "<label class='custom-control custom-radio custom-control-inline'>";
            echo "<input type='radio' name='$campo_nome' class='custom-control-input' value='NÃO' required ";
                if($radio_sim == 0){echo "checked";}
            echo "><span class='custom-control-label' > <span class = 'policia'>Não</span></span>";
            echo "</label>";
            echo "</div></div>";
        }
        
    }




$f->f_button("SALVAR E PROSSEGUIR");
echo "</form>";

$f -> fecha_card();
$f -> fecha();

$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();