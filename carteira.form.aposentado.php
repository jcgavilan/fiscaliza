<?php

$f->abre(12);
echo "<br><h2>Qualificação do Interessado</h2><hr><br>";
$f->fecha();

$f->abre(6);
$f -> f_input("Nome Completo", "nome", "");
$f -> f_input_mask("Data de Nascimento", "data_nascimento", "date");
$f -> f_input("Matricula", "matricula", "");
$f -> f_input("Logradouro", "logradouro", "");
$f -> f_input("Bairro", "bairro", "");
//$f -> f_input("Cidade", "cidade", "");

echo "<div class='form-group'>";
echo "<label for='inputText3' class='col-form-label'><span class = 'policia'>Cidade</span></label><br>";
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


$f -> f_input("Contato", "contato", "");
$f->fecha();
$f->abre(6);
$f -> f_input_mask("CPF", "cpf", "cpf");
echo "<div class='form-group'>";
echo "<label for='inputText3' class='col-form-label'><span class = 'policia'>Cargo</span></label><br>";
echo "<select class='form-control' id='cargo'  name='cargo' required = ''>";
echo "<option value='' selected disabled></option>";
echo "<option value='Delegado de Polícia'>Delegado de Polícia</option>";
echo "<option value='Agente de Polícia'>Agente de Polícia</option>";
echo "<option value='Escrivão de Polícia'>Escrivão de Polícia</option>";
echo "<option value='Psicólogo Policial'>Psicólogo Policial</option>";
echo "</select></div>";
//$f -> f_input("Cargo", "cargo", "");
$f -> f_input("E-mail", "email", "");
$f -> f_input("Nº", "numero", "");
$f -> f_input("Complemento", "complemento", "");
$f -> f_input("CEP", "cep", "");
$f -> f_input("SGPE do pedido", "sgpe", "");
$f->fecha();


$f->abre(12);
echo "<br><h2>Prazo de validade da Carteira</h2><hr><br>";
echo "<h3>ORIENTAÇÕES</h3>";
echo "<p style = 'margin-left:30px;'>O prazo de validade será de <strong>10 anos</strong> da data da avaliação psicológica;</p>";

//$f -> f_input_data("Data de Validade da Carteira:", "data_validade", "");
    $f->abre(2);
    echo "<div class='form-group'>";
    echo "<label for='inputText3' class='col-form-label'>Data de Validade da Carteira:</label>";
    echo "<input  type='text' name='data_validade' class='form-control date-inputmask' id='date-mask' value =''>";
    echo "</div>";
    $f->fecha();

$f->fecha();


$f->abre(12);
echo "<br><h2>Arquivos requeridos para a geração da Carteira</h2><hr>";

$id_documento = 18;

$documento_nome = array();
$query = "select id, nome from tb_documentos_tipo";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for($i=0;$i<$num;$i++) 
    {
        $row = mysqli_fetch_array($result);
        $id = $row[id];
        $documento_nome[$id]= (stripslashes($row[nome]));
    }


$query = "select id, id_documento_tipo from tb_documentos_requeridos where id_alvara = $id_documento";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);

$inicial = 1; $num_inicial = $num+$inicial; // necessário para não repetir nome do campo, já que tem alguns fixos antes
$numero = $inicial;
for($i=$inicial;$i<$num_inicial;$i++) 
    {
        $row = mysqli_fetch_array($result);
        $id = $row['id'];
        $id_documento_tipo = $row[id_documento_tipo];
       // $numero = $i+1;
       //$numero = $i;

      //  if(!in_array($id_documento_tipo, $documentos_dispensados)){
            
            echo "<div id = 'teste_$i' style = 'display: block; width:100%; margin-top: 18px; border:1px solid #cccccc; padding: 12px; background-color: #fafafa;'>";
            echo "<p> $numero - ".$documento_nome[$id_documento_tipo]."</p>";
            echo "<input type='file' name='arquivo_$id_documento_tipo' id='arquivo_$id_documento_tipo' class='filestyle' required = '' accept='.pdf' ";
            echo "onchange='return somentePdf($numero);'";
            echo ">"; // no id vai o $i para poder aplicar a validação para arquivos PDF, somente
            echo "<input type='hidden' name='id_documento_tipo_ref_$i' value='$id_documento_tipo'>";
            // echo "<div style = 'width: 100%; height: 20px;' >&nbsp</div>";
            echo "</div>";
            $numero++;
      // }

    }
    
    echo "<br><p><strong>Atenção sobre a dispensa de documentos!</strong> O Policial Civil aposentado que já possui autorização válida para o porte de arma de fogo particular expedido pela GEFID, fica <strong>dispensado dos requisitos II e III</strong>, porém deve juntar a cópia da autorização para o porte de arma de fogo. </p>";

$f->fecha();

?>