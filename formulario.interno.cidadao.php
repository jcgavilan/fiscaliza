<?php
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


$header=new Header_adm_WEB(); 
$a=new Menu_adm($link);
$nome_pagina= "Bem-Vindo";
$a=new Abre_titulo();
$a->titulo_pagina($nome_pagina);
$f -> abre_card(12);
echo "<h1>Atendimento ao Cidadão - Solicitação Interna de Alvará</h1><hr><br>";
$f->abre(12);

        $id_documento = (int)$_POST[id_alvara];

        // POR CONTA DA NECESSIDADE DE INDICAÇÃO DE MUNICIPIO DA EMPRESA, QUE PODE SER DE FORA DE SC, VAMOS TER UMA INVERSÃO NO TRATAMENTO DOS CAMPOS AQUI
        // RECERCUSSÕES - nesse documento, no painel de análise, no painel do delegado, no alvará.
        $label_endereco = array();
        $label_endereco['rua'] = "Rua, avenida ou logradouro";
        $label_endereco['numero'] = "Número";
        $label_endereco['bairro'] = "Bairro";
        $label_endereco['cep'] = "CEP";
        $label_endereco['municipio'] = "Município";

        // ENTRETANTO, Se for o caso da licença diária, ocorre a inversão, e os labels devem ser sobrescritos.

        if ($id_documento == 4 || $id_documento == 5) { // id da licença diária na tabela tb_alvaras_tipo
            $label_endereco['rua'] = "Rua, avenida ou logradouro DO EVENTO";
            $label_endereco['numero'] = "Número DO EVENTO";
            $label_endereco['bairro'] = "Bairro DO EVENTO";
            $label_endereco['cep'] = "CEP DO EVENTO";
            $label_endereco['municipio'] = "Município DO EVENTO";
        }


        $query = "select nome, mensagem, possui_ramo_atividade, requer_vistoria from tb_alvaras_tipo where id = $id_documento";
        $result=mysqli_query($link, $query);
        $row = mysqli_fetch_array($result);
        $mensagem =  (stripslashes($row[mensagem]));
        $nome_documento =  (stripslashes($row[nome]));
        $possui_ramo_atividade = $row[possui_ramo_atividade];
        $requer_vistoria = $row[requer_vistoria];

        echo "<h2 class = 'policia'>Documento: $nome_documento</h2>";

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
                echo "<ul style = 'padding-left:30px; l ist-style-type:none'><li> <a href = '#' title = '$descricao'><img src = '../cidadao/lupa.png'></a> <span class = 'policia'>$cnae</span></li></ul>";
                $f->fecha();
                        
            }
             //   echo "</ul>";
        $f->fecha();
        echo "</div>";
     }
        ?>
        
        <br>
      </div>
<?php
$ast = " <span style = 'color: #ff0000;'><strong>*</strong></span>";
$colunas_cel = 12;
echo "<p class = 'policia'><strong class= 'policia'>ATENÇÃO:</strong> todos os campos marcados com asterisco vermelho ($ast) são <strong class='policia'>obrigatórios.</strong></p>";
    //$f -> abre_form("formulario.cidadao.requerimento.php");
    $f -> abre_form("formulario.interno.cidadao.valida.php");

    echo "<br><div style = 'display: table; width: 100%;  background-color:#f5f5f5;padding: 16px; margin-bottom:12px!important; border-top: 12px solid #ffffff;' >";
    echo "<h6 class = 'policia'><strong class='policia'>ATENÇÃO</strong>: Toda a comunicação a respeito do processo de emissão de alvará, inclusive o recebimento do arquivo, serão realizados por meio deste email.</h6>";
    $f->f_input_coluna(6, "Email $ast", "email", "$email");
    $f->f_input_coluna(6, "Confirma Email $ast", "email_confirma", "$email");
    echo "</div>";
 // echo "<form  enctype = 'multipart/form-data' action='formulario.cidadao.valida.php' method='post' enctype='multipart/form-data' onsubmit='return validateForm();'>";
    $f->f_input_coluna(6, "Nome Fantasia $ast", "nome_estabelecimento", "$nome_estabelecimento");
    $f->f_input_coluna(6, "Razão Social $ast", "razao_social", "$razao_social");
    $f->f_input_coluna_mask(6, "CNPJ $ast", "cnpj", "$cnpj", 'cnpj');
    $f->f_input_coluna_mask(6, "Confirma CNPJ $ast", "cnpj_confirma", "$cnpj", 'cnpj');



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

    $f->f_input_coluna(6, $label_endereco['rua']." $ast", "endereco_rua", "$endereco_rua");
    $f->f_input_coluna(2, $label_endereco['numero']." $ast", "endereco_numero", "$endereco_numero");
    $f->f_input_coluna(4, $label_endereco['bairro']." $ast", "endereco_bairro", "$endereco_bairro");
    $f->f_input_coluna_mask(2, $label_endereco['cep']." $ast", "endereco_cep", "$endereco_cep", 'cep');
    $colunas=4;
    echo "<div class='col-xl-".$colunas." col-lg-".$colunas." col-md-".$colunas_cel." col-sm-".$colunas_cel." col-".$colunas_cel."' style = 'float: left; padding-right:10px;'>";
    echo "<div class='form-group'>";
    echo "<label for='inputText3' class='col-form-label'><span class = 'policia'>".$label_endereco['municipio']." $ast</span></label><br>";
    echo "<select class='form-control' id='id_municipio'  name='id_municipio' required = ''>";
    echo "<option value='' selected disabled></option>";
    $query = "select * from tb_municipios_ibge where ativo = 1 order by nome asc";
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
                    echo "<label for='inputText3' class='col-form-label'><span class='policia'>$campo_label</span></label> &nbsp;<br>";
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

    
    echo "<input type='hidden' name='id_documento' value='$id_documento'>";

    $f->abre(12); 

    //echo "<h3 id='errorMessage' style='display:block;'><BR><BR>PARA PROSSEGUIR, PREENCHA TODOS OS CAMPOS OBRIGATÓRIOS. <BR>(MARCADOS COM ASTERISCO VERMELHO: $ast)<BR><BR></h1>";

    ?>
    
    
    <!-- <label for="inputField2">Input Field 2:</label>
    <input type="text" id="inputField2">

    <br><br>

    <label for="copyCheckbox">Copy Value</label>
    <input type="checkbox" id="copyCheckbox"> -->
    
    <?PHP

        echo "<div id = 'submitBtn' style = 'display:block; width: 100%; padding: 24px 0; '>";
       // $f->f_button("SALVAR E PROSSEGUIR");
            echo "<button type='submit' class='btn btn-primary' onclick='validateForm()'>SALVAR E PROSSEGUIR</button>";
       // 
        echo "</div>";
        echo "<br><br><br>";
        echo "</form>";
    $f->fecha();
    $f->fecha();
        
    $f->fecha();

?> 

<!--  -------------------  F I M   D O    C O N T E U D O  -------------------------------------->
</div>


<script>
        function validateForm() {
            // var name = document.forms["myForm"]["name"].value;
            // if (name === "" ){
            //     alert('nome fantasia');
            // }
            const input1 = document.getElementById("nome_estabelecimento");  var var_valor = input1.value; if (var_valor === "" ){ alert('Informe o NOME FANTASIA');}
            const input2 = document.getElementById("razao_social"); var_valor = input2.value;   if (var_valor === "" ){ alert('Informe a RAZÃO SOCIAL');}
            const input3 = document.getElementById("cnpj");  var_valor = input3.value; if (var_valor === "" ){ alert('Informe o CNPJ');}
            const input4 = document.getElementById("cnpj_confirma");  var_valor = input4.value;  if (var_valor === "" ){ alert('Informe a confirmação do CNPJ');}
            const input5 = document.getElementById("email");  var_valor = input5.value; if (var_valor === "" ){ alert('Informe o EMAIL');}
            const input6 = document.getElementById("email_confirma");  var_valor = input6.value;  if (var_valor === "" ){ alert('Informe a CONFIRMAÇÃO DO EMAIL');}
            const input7 = document.getElementById("id_ramo_atividade");  var_valor = input7.value;  if (var_valor === "" ){ alert('Informe o RAMO DE ATIVIDADE');}
            const input8 = document.getElementById("endereco_rua");  var_valor = input8.value;  if (var_valor === "" ){ alert('Informe a RUA (endereço)');}
            const input9 = document.getElementById("endereco_numero");  var_valor = input9.value;  if (var_valor === "" ){ alert('Informe o NÚMERO (endereço)');}
            const input10 = document.getElementById("endereco_bairro");  var_valor = input10.value;  if (var_valor === "" ){ alert('Informe o BAIRRO ');}
            const input11 = document.getElementById("endereco_cep");  var_valor = input11.value;  if (var_valor === "" ){ alert('Informe o CEP do endereço');}
            const input12 = document.getElementById("id_municipio");  var_valor = input12.value;  if (var_valor === "" ){ alert('Indique o MUNICIPIO');}
        //    const input13 = document.getElementById("telefone_fixo");  var_valor = input13.value;  if (var_valor === "" ){ alert('Informe o TELEFONE FIXO');}
            const input14 = document.getElementById("telefone_celular");  var_valor = input14.value;  if (var_valor === "" ){ alert('Informe o TELEFONE CELULAR');}
            const input15 = document.getElementById("nome_proprietario");  var_valor = input15.value;  if (var_valor === "" ){ alert('Informe o RESPONSÁVEL LEGAL DA EMPRESA');}
            const input16 = document.getElementById("id_area_vistoria");  var_valor = input16.value;  if (var_valor === "" ){ alert('Informe o ÁREA DO ESTABELECIMENTO PARA VISTORIA');}
            const input17 = document.getElementById("requerente_nome");  var_valor = input17.value;  if (var_valor === "" ){ alert('Informe o NOME do requerente');}
            const input18 = document.getElementById("requerente_data_nasc");  var_valor = input18.value;  if (var_valor === "" ){ alert('Informe a DATA DE NASCIMENTO do requerente');}
            const input19 = document.getElementById("requerente_cpf");  var_valor = input19.value;  if (var_valor === "" ){ alert('Informe o CPF do requerente');}
            const input20 = document.getElementById("requerente_endereco");  var_valor = input20.value;  if (var_valor === "" ){ alert('Informe o ENDEREÇO do requerente');}
            const input21 = document.getElementById("requerente_telefone");  var_valor = input21.value;  if (var_valor === "" ){ alert('Informe o TELEFONE do requerente');}
            const input22 = document.getElementById("requerente_email");  var_valor = input22.value;  if (var_valor === "" ){ alert('Informe o EMAIL do requerente');}

        }
    </script>
<?php
$f -> fecha_card();
$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();

?>
