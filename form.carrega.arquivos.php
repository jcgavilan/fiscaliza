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

require_once 'htmlpurifier/HTMLPurifier.standalone.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);

$header=new Header_adm_WEB(); 

$f = new Forms();

$a=new Menu_adm($link);

$nome_pagina= "Bem-Vindo";
$a=new Abre_titulo();
$a->titulo_pagina($nome_pagina);

$id_estabelecimento = filter_var($_GET['id_estabelecimento'], FILTER_SANITIZE_NUMBER_INT);

$f -> abre(12);

$f -> abre(8);
    $f -> abre_card(12);

    $id_estabelecimento = $_GET[id_estabelecimento];
    settype($id_estabelecimento, 'integer');

    echo "<h2>Carregamento de Arquivos</h2><hr><br>";

    if(isset($_FILES["arquivo"]["tmp_name"]) && strlen($_FILES["arquivo"]["tmp_name"]) > 3){
        $arquivo = $_FILES["arquivo"]["tmp_name"];
        $arquivo_name = $_FILES["arquivo"]["name"];
     //   echo "<hr>2";
        $d1 = $_FILES["arquivo"]["type"];
        $d2 = explode("/", $d1);
        $extensao = $d2[1];

        $str_base = "abcdefghijklmnopqrstuvxywz"; // faz a geração da str final, como critério extra de segurança
        $str_meio = "";
        for($i=0;$i<4;$i++)
        {
            $str_prov = str_shuffle($str_base);
            $str_meio .= substr($str_prov, 0, 1);
        }

        $id_documento_tipo = filter_var($_POST['id_documento_tipo'], FILTER_SANITIZE_NUMBER_INT);

        $data_registro = time();

        $nome_documento  = $f->limpa_variavel($_POST['nome_documento'], 90, $purifier);

      

        $prov = str_replace(" ", "_", $nome_documento);
        $prov = preg_replace('/[^a-z0-9_ ]/i', '', $prov);
        $prov = $prov."_".$data_registro.$str_meio;

        $nome_final = $id_estabelecimento."_".$id_documento_tipo."_".$prov.".".$extensao;

        $destino = "arquivos/".$nome_final;
       // echo $destino;

       if($extensao == 'pdf')
        {
        if(!move_uploaded_file($arquivo, $destino)){
                echo "<p><b>Não foi possível carregar o arquivo!</b></p>".$_FILES["arquivo"]["tmp_name"];
            }else{
                

                // faz a inclusão no banco de dados

                $query = "insert into tb_documentos_estabelecimentos (id_documento_tipo, id_estabelecimento, nome_documento, nome_arquivo, data_registro) values ($id_documento_tipo, $id_estabelecimento, '$nome_documento','$nome_final', $data_registro)";
                $result = mysqli_query($link, $query);
                if(!$result)
                {   
                    echo " <div class='alert alert-danger' role='alert'>";
                    echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL CARREGAR O ARQUIVO<br>$query<br> </h4>".mysqli_error($link);
                    echo "</div>";
                }else{

                    echo " <div class='alert alert-primary' role='alert'>";
                    echo " <h4 class='alert-heading' align = 'center'>Arquivo carregado com sucesso</h4>";
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

    $f -> abre_form("form.carrega.arquivos.php?id_estabelecimento=$id_estabelecimento");
    echo "<h3>Cadastro de Documento</h3>";
    $f -> f_input("Nome para o arquivo (ex: Alvará prefeitura 2022):", "nome_documento", "");

    echo "<div class='form-group'>";
    echo "<label for='inputText3' class='col-form-label'>Indique o tipo de documento</label><br>";
    echo "<select class='form-control' id='id_documento_tipo' required='' name='id_documento_tipo'>";
    echo "<option value='0'></option>";
    $query = "select * from tb_documentos_tipo order by nome asc";
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++) 
            {
                $row = mysqli_fetch_array($result);
                $id = $row[id];
                $nome =  (stripslashes($row[nome]));
                echo "<option value='".$id."'";
                echo ">".$nome."</option>";         
            }
    echo "</select></div>";



    echo "<div class='form-group'>";
    echo "<label for='arquivo'>Formato: PDF</label>";
    echo "<input type='file' class='form-control-file' id='arquivo' name = 'arquivo'>";
    echo "</div>";

    $f->f_button("Salvar");

    echo "</form>";



    $f -> fecha_card();
    echo "<a href = 'pagina.estabelecimento.php?id_estabelecimento=$id_estabelecimento' class = 'btn btn-primary' style = 'float: right'>VOLTAR PARA PÁGINA DE EMPRESA</a>";


               
    $f -> fecha();

    
    $f -> abre(4);
    $f -> abre_card(12);
    echo "<h3>Documentos Carregados</h3>";

    echo "<table class='table table-bordered'>";
    echo "<thead>";
      echo "<tr>";
      echo "<th scope='col'>Data do registro</th>";
      echo "<th scope='col'>Documento</th>";
      echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    $query = "select * from tb_documentos_estabelecimentos where id_estabelecimento = ".$id_estabelecimento." order by data_registro desc";
    $result=mysqli_query($link, $query);
        $num = mysqli_num_rows($result);
        for($i=0;$i<$num;$i++) 
                {
                    $row = mysqli_fetch_array($result);
                    $nome_documento =  (stripslashes($row[nome_documento]));
                    $nome_arquivo =  (stripslashes($row[nome_arquivo]));
                    $data_registro =  (stripslashes($row[data_registro]));
                    $data_print = date("d/m/Y", $data_registro);

                    echo "<tr>";
                    echo "<td>$data_print</td>";
                    echo "<td><a href = 'arquivos/$nome_arquivo' target = '_blank'>".$nome_documento."<a href = '#'></td>";
                    echo "</tr>";
       
                }
    echo "</tbody>";
    echo "</table>";
    $f -> fecha_card();
    $f -> fecha();
    
    $f -> fecha();
    
  
$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();



