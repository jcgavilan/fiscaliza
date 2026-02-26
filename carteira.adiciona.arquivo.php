<?php

session_start();

if(!isset($_SESSION["usuario_fis_cpf"]))
{
  header('Location: https://integra.pc.sc.gov.br/');
  exit();
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


$id_carteira = (int)$_GET[id_carteira];
$data_expedicao = (int)$_GET[data_expedicao];

// busca o tipo de carteira, para buscar a lista de documentos de referêcncia

$query = "select tipo from tb_carteiras where id = $id_carteira";
$result=mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
$tipo = $row['tipo'];

switch ($tipo) {
  case 'aposentado':
   $id_alvara = 18;
    break;

  case 'blaster':
    $id_alvara = 17;
    break;

  case 'arma_particular':
  $id_alvara = 19;
    break;
  
}



$f -> abre(12);


$f -> abre_card(12);
  echo "<h2>Inclusão de novo arquivo</h2>";


  if (isset($_FILES["arquivo"]["name"])) {

    // echo " ==>".$id_documento_tipo;
     $arquivo = $_FILES["arquivo"]["tmp_name"];

     $id_documento_tipo = (int)$_POST[id_documento_tipo];

        // busca o nome do arquivo
        $query = "select nome from tb_documentos_tipo where id = ".$id_documento_tipo;
        $result=mysqli_query($link, $query);
        $row = mysqli_fetch_array($result);
        $arquivo_nome = (stripslashes($row[nome]));     
        //   echo "<hr>2";
        $d1 = $_FILES["arquivo"]["type"];
        $d2 = explode("/", $d1);
        $extensao = $d2[1];

         if($extensao == 'pdf'){

            $base = "abcdefghijklmnopqrstuvwxyz23456789abcdefghijklmnopqrstuvwxyz675423289abcdefghijklmnopqrstuvwxyz9587694abcdefghijklmnopqrstuvwxyz";
            $a =  str_shuffle($base);
            $str = substr($a, 0, 40);
            $nome_final = $str.".pdf";

             $destino = "carteiras/".$nome_final;


             if(!move_uploaded_file($arquivo, $destino)){
             echo "<p><b>Não foi possível carregar o arquivo!</b></p>".$_FILES["arquivo"]["tmp_name"];
             }else{

            
            $query2 = "INSERT INTO `tb_carteiras_arquivos`(`id_carteira`, `cpf_policial`, `data_carregamento`, `id_documento_tipo`, `arquivo`) VALUES ( $id_carteira, '".$_SESSION['usuario_fis_cpf']."', '".time()."', $id_documento_tipo, '$nome_final')";
               //  $query2 = "insert into tb_carteira_arquivos (id_carteira, cpf_policial, data_carregamento, id_documento_tipo, arquivo) values ($id_pedido, '".$_SESSION['usuario_fis_cpf']."', ".time().", '$id_documento_tipo', '$nome_final')";
              //   echo "<hr>$i -> $query2";
                 $result2 = mysqli_query($link, $query2);
                 if(!$result2){
                     echo "<h1>ERRO -> $query2</h1>";
                 }else{
                    echo " <div class='alert alert-info' role='alert'>";
                    echo "<h2>ARQUIVO CARREGADO COM SUCESSO</h2>";
                    echo "</div>"; 
                 }
             }
         }else{
             echo " <div class='alert alert-danger' role='alert'>";
             echo "<h2>DOCUMENTO NÃO ESTÁ EM FORMATO PDF</h2>";
             echo "<h1>INCLUSÃO NEGADA</h1>";
             echo "</div>"; 
         } 
    }





// sempre apresenta o formulário para carregar documento

$f -> abre_form("carteira.adiciona.arquivo.php?id_carteira=$id_carteira&data_expedicao=$data_expedicao");

echo "<div class='form-group'>";
echo "<label for='inputText3' class='col-form-label'>Indique o documento que está carregando</label><br>";
echo "<select class='form-control' id='id_documento_tipo'  name='id_documento_tipo' required = ''>";
echo "<option value='' selected disabled></option>";
$query = " select t1.id_documento_tipo, t2.nome from tb_documentos_requeridos AS t1 JOIN tb_documentos_tipo AS t2 on t2.id = t1.id_documento_tipo where t1.id_alvara = $id_alvara";
$result=mysqli_query($link, $query);
$num = mysqli_num_rows($result);
for($i=0;$i<$num;$i++) 
        {
            $row = mysqli_fetch_array($result);
            $id_documento_tipo = $row[id_documento_tipo];
            $nome =  (stripslashes($row[nome]));
            echo "<option value='".$id_documento_tipo."'";
            echo ">".$nome."</option>";         
        }
echo "</select></div>";

echo " <div class='form-group '>";
        echo "<input type='file' name='arquivo' id='arquivo' style='display: none;'>";
        echo "<label for='arquivo' style = 'background-color: #fdfdff; border: 1px solid #e4e6fc; border-radius: 5px; color: #6c7989; cursor: pointer; margin: 0px; padding: 10px 20px'>Selecionar arquivo PDF</label>";
        echo "</div>";

echo "<br><br><button type='submit' class='btn btn-primary'>SALVAR</button>";
echo "</form>";
$f->fecha();

$f->abre(12);
echo "<div style = 'display: block; width: 100%; text-align: right;'>";
echo "<BR><a href = 'carteira.painel.php?id=$id_carteira&data_expedicao=$data_expedicao' class='btn btn-primary'>VOLTAR PARA PÁGINA DA CARTEIRA</a><br><br>";
echo "</div>";
$f->fecha();



  $f -> fecha_card();
$f -> fecha();


$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();

?>