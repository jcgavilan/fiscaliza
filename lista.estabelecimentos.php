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

$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

$f -> abre(12);

    $f -> abre_card(12);
    echo "<h2>Busca de Estabelecimentos</h2><hr><br>";
    $f -> abre(12);
    $f -> abre_form("lista.estabelecimentos.php?buscar=1");

      $f -> abre(2);
      echo "<div style = 'display: table; padding-top:10px; width:100%;'>";
        $f -> f_input_mask("CNPJ", "cnpj", "cnpj");
        $f -> fecha(); $f -> fecha(); // para fechar o div de ajuste de altura

      $f -> abre(4);
      echo "<div style = 'display: table; padding-top:10px; width:100%;'>";
        $f -> f_input("Nome do Estabelecimento", "nome_estabelecimento", "");
      $f -> fecha(); $f -> fecha(); // para fechar o div de ajuste de altura

      $f -> abre(2);
      echo "<div class='form-group'>";
      echo "<label for='inputText3' class='col-form-label'>Ramos de Atividade</label><br>";
      echo "<select class='form-control' id='id_ramo_atividade' name='id_ramo_atividade'>";
      echo "<option value='0'></option>";
      $nome_ramo_atividade = array();
      $query = "select * from tb_ramos_atividade order by nome asc";
      $result=mysqli_query($link, $query);
      $num = mysqli_num_rows($result);
      for($i=0;$i<$num;$i++) // foi incluindo a contagem até 8 para ir somente até o superior/cursando no caso de criança ou adolescente
              {
                  $row = mysqli_fetch_array($result);
                  $id = $row[id];
                  $nome =  (stripslashes($row[nome]));
                  echo "<option value='".$id."'";
                  echo ">".$nome."</option>";  
                  $nome_ramo_atividade[$id] = $nome;
              }
      echo "</select></div>";
      $f -> fecha();

      $f -> abre(2);
      echo "<div class='form-group'>";
      echo "<label for='inputText3' class='col-form-label'>Bairros</label><br>";
      echo "<select class='form-control' id='endereco_bairro'  name='endereco_bairro'>";
      echo "<option value=''></option>";
      $query = "select distinct endereco_bairro from tb_estabelecimentos order by endereco_bairro asc";
      $result=mysqli_query($link, $query);
      $num = mysqli_num_rows($result);
      for($i=0;$i<$num;$i++) // foi incluindo a contagem até 8 para ir somente até o superior/cursando no caso de criança ou adolescente
              {
                  $row = mysqli_fetch_array($result);
                  $id = $row[id];
                  $endereco_bairro =  (stripslashes($row[endereco_bairro]));
                  echo "<option value='".$endereco_bairro."'";
                  echo ">".$endereco_bairro."</option>";         
              }
      echo "</select></div>";
      $f -> fecha();
      
      $f -> abre(2);
        echo "<div style = 'display: table; padding-top:34px'>";
        $f->f_button("BUSCAR");
      $f -> fecha(); $f -> fecha();

    echo "</form>";
    $f -> fecha();


$f -> fecha();


    // REALIZA A BUSCA

    if(isset($_GET['buscar']))
    {
      $f -> abre(12);
        $cnpj  = $f->limpa_variavel($_POST['cnpj'], 90, $purifier);
        $cnpj = preg_replace("/[^0-9]/", "",$cnpj);
 
        $query_prov = '';

        if(strlen($cnpj) == 14)
        {
          // faz a pesquisa pelo CNPJ
          $query_prov .= " AND cnpj = '$cnpj' ";
        
        }else{
          // faz a pesquisa por outros termos
          $nome_estabelecimento  = $f->limpa_variavel($_POST['nome_estabelecimento'], 90, $purifier);

          if(strlen($nome_estabelecimento) > 2){
            $query_prov .= " AND nome_estabelecimento like '%$nome_estabelecimento%' ";
          }

          $id_ramo_atividade = $_POST[id_ramo_atividade];
          settype($id_ramo_atividade, 'integer');
          if($id_ramo_atividade != 0){
            $query_prov .= " AND id_ramo_atividade = $id_ramo_atividade ";
          }

          $endereco_bairro  = $f->limpa_variavel($_POST['endereco_bairro'], 90, $purifier);

          if(strlen($endereco_bairro) > 2){
            $query_prov .= " AND endereco_bairro like '%$endereco_bairro%' ";
          }
          
        }





          $query = "select id, nome_estabelecimento, endereco_bairro, id_ramo_atividade, razao_social from tb_estabelecimentos where id!=0  $query_prov order by nome_estabelecimento asc";
          $result=mysqli_query($link, $query);
          $num = mysqli_num_rows($result);
          if($num == 0){
              echo " <div class='alert alert-danger' role='alert'>";
              echo " <h4 class='alert-heading' align = 'center'>Pesquisa não retornou resultados</h4>";
              echo "</div>";
          }else{
            echo "<table class='table table-bordered'>";
            echo "<thead>";
              echo "<tr>";
              echo "<th scope='col'>Estabelecimento</th>";  
              echo "<th scope='col'>Razão Social</th>";
              echo "<th scope='col'>Ramo de Atividade</th>";
              echo "<th scope='col'>Bairro</th>";
              echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
          }
          for($i=0;$i<$num;$i++) 
              {
                  $row = mysqli_fetch_array($result);
                  $id_estabelecimento = $row[id];
                  $nome_estabelecimento =  (stripslashes($row[nome_estabelecimento]));
                  $endereco_bairro =  (stripslashes($row[endereco_bairro]));
                  $id_ramo_atividade =  (stripslashes($row[id_ramo_atividade]));
                  $razao_social =  (stripslashes($row[razao_social]));
                
                  echo "<tr>";
                  echo "<th scope='row'><a href = 'pagina.estabelecimento.php?id_estabelecimento=$id_estabelecimento' target = '_blank'> <i class='icon-magnifier'></i> $nome_estabelecimento</a></th>";
                  echo "<td>$razao_social</td>";
                  echo "<td>".$nome_ramo_atividade[$id_ramo_atividade]."</td>";
                  echo "<td>$endereco_bairro</td>";
                  echo "</tr>";
                        
              }
              echo "</tbody>";
          echo "</table>";
          
    }

$f -> fecha_card();
$f -> fecha();


    $footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();




?>