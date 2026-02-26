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


$id_pedido = (int)$_GET[id_pedido];
$data_pedido = (int)$_GET[data_pedido];

// busca o tipo de alvará para buscar a lista de documentos de referêcncia

$query = "select tipo_pedido from tb_cidadao_pedidos where id = $id_pedido AND data_pedido = $data_pedido";
$result=mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
$id_alvara = $row['tipo_pedido']; // sim, me arrependo dessa inconsistência de nomenclatura

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

             $destino = "arquivos_cidadao_prev/".$nome_final;


             if(!move_uploaded_file($arquivo, $destino)){
             echo "<p><b>Não foi possível carregar o arquivo!</b></p>".$_FILES["arquivo"]["tmp_name"];
             }else{

            
                $query2 = "insert into tb_cidadao_arquivos (id_pedido, data_carregamento, id_documento_tipo, arquivo, aprovado) values ($id_pedido,  $data_pedido, '$id_documento_tipo', '$nome_final', 'A')";
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

$f -> abre_form("form.alvara.adiciona.arquivo.php?id_pedido=$id_pedido&data_pedido=$data_pedido");

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

$query = " select t1.id_documento_tipo, t2.nome from tb_regras_documentos_obrigatorios AS t1 JOIN tb_documentos_tipo AS t2 on t2.id = t1.id_documento_tipo where t1.id_alvara = $id_alvara";
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
// tenho que buscar também os facultativos



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
echo "<BR><a href = 'pedidos.painel2.php?id_pedido=$id_pedido' class='btn btn-primary'>VOLTAR PARA PÁGINA DO ALVARÁ</a><br><br>";
echo "</div>";
$f->fecha();



  $f -> fecha_card();
$f -> fecha();


$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();

?>