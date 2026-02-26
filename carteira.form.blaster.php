<?php
$f->abre(12);
echo "<br><h2>Qualificação do Interessado</h2><hr><br>";
$f->fecha();

$f->abre(6);
    $f -> f_input("Nome Completo", "nome", "");
    $f -> f_input("Filiação (pai)", "filiacao_pai", "");
    $f -> f_input("Filiação (mãe)", "filiacao_mae", "");
    $f -> f_input_mask("Data de Nascimento", "data", "date");
    $f -> f_input_mask("CPF", "cpf", "cpf");
    $f -> f_input("Logradouro", "logradouro", "");
    $f -> f_input("Bairro", "bairro", "");
$f->fecha();
$f->abre(6);
 
    $f -> f_input("Número", "numero", "");
    $f -> f_input("CEP", "cep", "");
  /*  echo "<div class='form-group'>";
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
    echo "</select></div>";*/

    $f->abre(9);
        echo "<div style = 'display: block; margin-top:12px;'>";
        $f -> f_input("Municipio", "municipio_nome", "");
        echo "</div>";
    $f->fecha();
    $f->abre(3);
        echo "<div class='form-group'>";
        echo "<label for='inputText3' class='col-form-label'><span class = 'policia'>Estado</span></label><br>";
        echo "<select class='form-control' id='uf'  name='uf' required = ''>";
        echo "<option value='' selected disabled></option>";
        $query = "select distinct uf from tb_municipios_nacional  order by uf asc";
        $result=mysqli_query($link, $query);
        $num = mysqli_num_rows($result);
        for($i=0;$i<$num;$i++) 
                {
                    $row = mysqli_fetch_array($result);
                    $uf = $row[uf];
                    echo "<option value='".$uf."'";
                    echo ">".$uf."</option>";         
                }
        echo "</select></div>";
    $f->fecha();



    echo "<div style = 'display: table; width: 100%; padding-top:12px;'>";
    $f -> f_input("Contato", "contato", "");
    $f -> f_input("E-mail", "email", "");
    $f->f_radio_simnao_edit("Registro no Exército?", 'registro_exercito', 0);
    $f -> f_input("Se sim, número do registro", "numero_registro", "");
    $f -> f_input("SGPE do pedido", "sgpe", "");
    echo "</div>";
$f->fecha();

$f->abre(12);
echo "<br><h2>Empresa que ministrou o curso de Bláster</h2><hr><br>";
$f->fecha();

$f->abre(4);
$f -> f_input("Nome da Empresa", "nome_empresa_curso", "");
$f->fecha();
$f->abre(4);
$f -> f_input_mask("CNPJ", "cnpj_empresa_curso", "cnpj");
$f->fecha();
$f->abre(4);
?>
<div class="form-group">
   <label for="inputText3" class="col-form-label">Categoria:</label><br>
    <label class="custom-control custom-radio custom-control-inline">
       <input type="radio" name="categoria_curso" class="custom-control-input" value="Blaster Pirotecnico"><span class="custom-control-label">Blaster Pirotecnico</span>
   </label>
   <label class="custom-control custom-radio custom-control-inline">
       <input type="radio" name="categoria_curso" class="custom-control-input" value="Encarregado (cabo) de fogo"><span class="custom-control-label">Encarregado (cabo) de fogo</span>
   </label>
</div>
<?php 
$f->fecha();

$f->abre(12);
echo "<br><h2>Qualificação da empresa empregadora</h2><hr><br>";
$f->fecha();
$f->abre(6);
$f -> f_input("Nome da Empresa", "nome_empresa_empregadora", "");
$f -> f_input_mask("CNPJ", "cnpj_empresa_empregadora", "cnpj");
$f -> f_input("Responsável legal", "responsavel_legal", "");
$f -> f_input("Logradouro", "logradouro_empresa_empregadora", "");
$f -> f_input("Bairro", "bairro_empresa_empregadora", "");
$f -> f_input("Número", "numero_empresa_empregadora", "");
$f->fecha();

$f->abre(6);
$f -> f_input("CEP", "cep_empresa_empregadora", "");
/*echo "<div class='form-group'>";
echo "<label for='inputText3' class='col-form-label'><span class = 'policia'>Cidade</span></label><br>";
echo "<select class='form-control' id='id_municipio_empresa_empregadora'  name='id_municipio_empresa_empregadora' required = ''>";
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
echo "</select></div>";*/

$f->abre(9);
        echo "<div style = 'display: block; margin-top:12px;'>";
        $f -> f_input("Municipio", "municipio_nome_empresa_empregadora", "");
        echo "</div>";
    $f->fecha();
    $f->abre(3);
        echo "<div class='form-group'>";
        echo "<label for='inputText3' class='col-form-label'><span class = 'policia'>Estado</span></label><br>";
        echo "<select class='form-control' id='uf_empresa_empregadora'  name='uf_empresa_empregadora' required = ''>";
        echo "<option value='' selected disabled></option>";
        $query = "select distinct uf from tb_municipios_nacional  order by uf asc";
        $result=mysqli_query($link, $query);
        $num = mysqli_num_rows($result);
        for($i=0;$i<$num;$i++) 
                {
                    $row = mysqli_fetch_array($result);
                    $uf = $row[uf];
                    echo "<option value='".$uf."'";
                    echo ">".$uf."</option>";         
                }
        echo "</select></div>";
    $f->fecha();
    echo "<div style = 'display: table; width: 100%; padding-top:12px;'>";
        $f -> f_input("Contato", "contato_empresa_empregadora", "");
        $f -> f_input("E-mail", "email_empresa_empregadora", "");

        $f->f_radio_simnao_edit("Registro no Exército?", 'registro_exercito_empresa_empregadora', 0);
        $f -> f_input("Se sim, número do registro", "numero_registro_empresa_empregadora", "");
    echo "</div>";
$f->fecha();

$f->abre(12);
echo "<br><h2>Arquivos requeridos para a geração da Carteira</h2><hr>";

$id_documento = 17;

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