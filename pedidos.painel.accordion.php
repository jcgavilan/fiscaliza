<?php

session_start();

if(!isset($_SESSION["id_usuario_des"]))
{
  //header('Location: login.php');
  //exit();
}
$id_policial = 1;

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

$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);


echo "<h1>Painel de Análise de Pedidos</h1>";
$f -> abre_card(12);

$id_pedido = (int)$_GET['id_pedido'];

?>
<div class="accordion" id="accordionExample">
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingOne">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        Accordion Item #1
      </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingTwo">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
        Accordion Item #2
      </button>
    </h2>
    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingThree">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
        Accordion Item #3
      </button>
    </h2>
    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
      </div>
    </div>
  </div>
</div>

<?php

/*


echo "<h2 style = 'color: #999999;'>Dados do Solicitante:<h3>";

echo "<div class='accordion accordion-flush' id='accordionFlushExample'>";
echo "<div class='accordion-item'>";
echo '<h2 class="accordion-header" id="flush-headingOne">';
    echo '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">';
    echo "DADOS DO SOLICITANTE";
    echo "</button>";
echo "</h2>";
echo '<div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">';
echo "<div class='accordion-body'>";


$query = "select * from tb_cidadao_pedidos where id = ".$id_pedido;
    $result=mysqli_query($link, $query);

    $a =  str_shuffle($base);
    $str = substr($a, 0, 40);
    $row = mysqli_fetch_array($result);
    $id_pedido = $row[id];
    $data_pedido = $row[data_pedido];
    $tipo_pedido = $row[tipo_pedido];
    $cnpj= (stripslashes($row[cnpj]));
    $nome_estabelecimento= (stripslashes($row[nome_estabelecimento]));
    $razao_social= (stripslashes($row[razao_social]));
    $id_ramo_atividade= (stripslashes($row[id_ramo_atividade]));
    $endereco_rua= (stripslashes($row[endereco_rua]));
    $endereco_numero= (stripslashes($row[endereco_numero]));
    $endereco_bairro= (stripslashes($row[endereco_bairro]));
    $endereco_cep= (stripslashes($row[endereco_cep]));
    $id_municipio= (stripslashes($row[id_municipio]));
    $telefone_fixo= (stripslashes($row[telefone_fixo]));
    $telefone_celular= (stripslashes($row[telefone_celular]));
    $email= (stripslashes($row[email]));
    $nome_proprietario= (stripslashes($row[nome_proprietario]));
    $arquivos= (stripslashes($row[arquivos]));
    
    $f->f_input_read(3, "Nome do Estabelecimento:", "", "$nome_estabelecimento");
    $f->f_input_read(3, "Razão Social:", "", "$razao_social");
    $f->f_input_read(2, "CNPJ:", "", "$cnpj");

    $query2 = "select nome from tb_ramos_atividade where id = ".$id_ramo_atividade;
    $result2 = mysqli_query($link, $query2);
    $row2 = mysqli_fetch_array($result2);
    $nome_ramo_atividade = (stripslashes($row2[nome]));
    $f->f_input_read(2, "Ramo de Atividade:", "", "$nome_ramo_atividade");
    $f->f_input_read(2, "Nome do Proprietário:", "", "$nome_proprietario");

    $f->f_input_read(3, "Endereço:", "", "$endereco_rua, $endereco_numero - $endereco_bairro");
    $f->f_input_read(1, "CEP:", "", "$endereco_cep");

    $query2 = "select nome from tb_municipios_ibge where ibge = ".$id_municipio;
    $result2 = mysqli_query($link, $query2);
    $row2 = mysqli_fetch_array($result2);
    $nome_municipio = (stripslashes($row2[nome]));        
    $f->f_input_read(2, "Municipio:", "", "$nome_municipio");

    $f->f_input_read(2, "Telefone Fixo:", "", "$telefone_fixo");
    $f->f_input_read(2, "Telefone Celular:", "", "$telefone_celular");
    $f->f_input_read(2, "Email:", "", "$email");

 
    echo "</div>"; // FECHA O DIV DO ACCORDIUM

// $f -> fecha_card();
// $f -> abre_card(12);

echo "</div></div>";
echo '<div class="accordion-item">';
echo '<h2 class="accordion-header" id="flush-headingTwo">';
echo ' <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">';
echo "Documentos do Solicitante:";
echo "</button>";
echo "</h2>";
echo '<div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">';
echo '<div class="accordion-body">';


//echo "<h2 style = 'color: #999999;'>Documentos do Solicitante:</h3>";





// PARA CONTROLE DOS ARQUIVOS, É MAIS INTERESSANTE TER EM UMA TABELA PROVISÓRIA, QUE VAI TER OS CAMPOS EXCLUÍDOS COM A FINALIZAÇÃO DO PROCESSO

// VERIFICA SE JÁ EXISTE O CADASTRO DOS ARQUIVOS PARA ESTE PEDIDO
$f->abre(4);

$query = "select id from tb_cidadao_arquivos where id_pedido = $id_pedido";
$result = mysqli_query($link, $query);
$num = mysqli_num_rows($result);
if($num == 0){

    $arq_total = explode(";", $arquivos);

    for ($i=0; $i < count($arq_total); $i++) { 
        $arq_fim = $arq_total[$i];

        $q = explode("*", $arq_fim);
        $arquivo_nome = $q[1];
        $arquivo_arquivo = $q[0];

       if (strlen($arquivo_arquivo) > 3) {
        $query = "insert into tb_cidadao_arquivos (id_pedido, id_policial, nome, arquivo) values ($id_pedido, $id_policial, '$arquivo_nome', '$arquivo_arquivo')";
        $result = mysqli_query($link, $query);
       }
       
    }
}


    echo "<table class='table table-bordered'>";
    echo "<thead>";
      echo "<tr>";
    //  echo "<th scope='col'>Data do registro</th>";
      echo "<th scope='col'>Documento</th>";
      echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
  
    $query = "select * from tb_cidadao_arquivos where id_pedido = ".$id_pedido." and aprovado = 'A' ";
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++) 
        {
            $row = mysqli_fetch_array($result);
            $nome_documento =  (stripslashes($row[nome]));
            $nome_arquivo =  trim(stripslashes($row[arquivo]));

            if($i == 0){
                $arquivo_inicial = $nome_arquivo;
            }

            echo "<tr>";
             //   echo "<td>$data_print</td>";
                echo "<td>";
                echo "<a href = '#' onClick='showPdf(\"arquivos_cidadao_prev/$nome_arquivo\");' style = 'font-size:12px;' data-toggle='tooltip' >$nome_documento </a>";
                echo "</td>";
            echo "</tr>";

        }

        echo "</tbody>";
            echo "</table>";


            $f -> fecha();

   // $f -> abre(8);

    echo "<div id = 'show' class='col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8' style = 'float: right;'>";

    echo "<object data='arquivos_cidadao_prev/$arquivo_inicial' type='application/pdf' style = 'width:100%; height:680px;'><embed src='arquivos_cidadao_prev/$arquivo_inicial' type='application/pdf' /></object>";

    $f -> fecha();
    
echo "</div></div></div></div>";

//3rtyt8fe4sj3jh8dytailz6cerxrixugjuom9f3l.pdf*Contrato Social; 3rtyt8fe4sj3jh8dytailz6cerxrixugjuom9f3l.pdf*Alvará de Funcionamento emitido pela Prefeitura Local; 3rtyt8fe4sj3jh8dytailz6cerxrixugjuom9f3l.pdf*Autorização expedida pela Vigilância Sanitária, ou protocolo; 

*/
$f -> fecha_card();

$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();


?>

?>