<?php
$f->abre(12);
echo "<br><h2>Qualificação do Policial Civil</h2><hr><br>";
$f->fecha();

$f->abre(6);
    $f -> f_input("Nome Completo", "nome", "");
    $f -> f_input_mask("Data de Nascimento", "data", "date");
    
    
    $f -> f_input("Contato", "contato_policial", "");
    $f -> f_input("Unidade de lotação", "unidade", "");
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
$f->fecha();
$f->abre(6);
    $f -> f_input("Matricula", "matricula", "");
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
   
    $f -> f_input("Subordinado à", "subordinado", "");
    $f -> f_input("SGPE do pedido", "sgpe", "");

$f->fecha();

$f->abre(12);
echo "<br><h2>Dados a Arma de Fogo</h2><hr><br>";
$f->fecha();

$f->abre(6);
$f -> f_input("Especie", "arma_especie", "");
$f -> f_input("Modelo", "arma_modelo", "");
$f -> f_input("Nº Registro", "arma_registro", "");
$f->fecha();
$f->abre(6);
$f -> f_input("Marca", "arma_marca", "");
$f -> f_input("Calibre", "arma_calibre", "");
$f -> f_input("Nº de Série", "arma_numero_serie", "");
$f->fecha();


$f->abre(12);
echo "<br><h2>Encontra-se apostilada em qual sistema?</h2><hr><br>";
$f->fecha();
$f->abre(6);
$f -> f_input("SIGMA -  Sistema de Gerenciamento Militar de Armas  ", "arma_numero_sigma", "");
$f->fecha();
$f->abre(6);
$f -> f_input("SINARM -  Sistema Nacional de Armas (MJSP/PF)", "arma_numero_sinarm", "");
$f->fecha();

$f->abre(12);
echo "<br><h2>Prazo de validade da Carteira</h2><hr><br>";
echo "<h3>ORIENTAÇÕES</h3>";
echo "<p style = 'margin-left:30px;'>O prazo de validade da autorização especial institucional para uso em serviço de arma de fogo particular</p>";
echo "<p  style = 'margin-left:30px;'>I - corresponderá à <strong>validade do certificado de registro de arma de fogo (CRAF)</strong> apresentado no momento do requerimento da autorização; e</p>";
echo "<p  style = 'margin-left:30px;'>II - será de <strong>5 (cinco) anos</strong>, em caso de autorização judicial, a contar da data de assinatura da autorização especial.</p>";

//$f -> f_input_data("Data de Validade da Carteira:", "data_validade", "");
    $f->abre(3);
    echo "<div class='form-group'>";
    echo "<label for='inputText3' class='col-form-label'>Data de Validade da Carteira:</label>";
    echo "<input  type='text' name='data_validade' class='form-control date-inputmask' id='date-mask' value =''>";
    echo "</div>";
    $f->fecha();

    $f->abre(1);
    $f->fecha();

    $f->abre(6);
    echo "<div class='form-group' style = 'padding-top: 36px;'>";
    //$f->f_radio_unico("Marque aqui para determinar a validade como INDETERMINADA", 'validade_indeterminada');
        echo "<label class='custom-control custom-radio custom-control-inline'>";
        echo "<input type='radio' name='validade_indeterminada' class='custom-control-input' value='1'><span class='custom-control-label'>Marque aqui para determinar a validade como INDETERMINADA<br>(ao marcar esse campo, vai desconsiderar o campo anterior)</span>";
        echo "</label>";
    echo "</div>";
    $f->fecha();

$f->fecha();

$f->abre(12);
echo "<br><h2>Arquivos requeridos para a geração da Carteira</h2><hr>";

$id_documento = 19;

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
$f->fecha();




?>