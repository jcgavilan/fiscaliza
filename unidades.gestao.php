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

$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

$f -> abre(12);


$f -> abre_card(12);

  echo "<h2>GESTÃO DE UNIDADES</h2>";

// realiza os dados para inclusão

if (isset($_GET['vincular'])) {
    $ibge_reduzido = (int)$_GET[ibge_reduzido];
    $ibge_microrregional = (int)$_POST[ibge_microrregional];

    $query = "update tb_municipios_ibge set ibge_microrregional = '$ibge_microrregional' where ibge_reduzido = '$ibge_reduzido'";
    $result = mysqli_query($link, $query);
    if(!$result)
    {   
        echo " <div class='alert alert-danger' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL VINCULAR A UNIDADE<br>$query<br> </h4>".mysqli_error($link);
        echo "</div>";
    }else{
        echo " <div class='alert alert-info' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>UNIDADE VINCULADA COM SUCESSO</h4>";
        echo "</div>";  
    }
}

if (isset($_GET['desvincular'])) {
    $ibge_reduzido = (int)$_GET[ibge_reduzido];

    $query = "update tb_municipios_ibge set ibge_microrregional = ibge_reduzido where ibge_reduzido = '$ibge_reduzido'";
    $result = mysqli_query($link, $query);
    if(!$result)
    {   
        echo " <div class='alert alert-danger' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>NÃO FOI POSSÍVEL DESVINCULAR A UNIDADE<br>$query<br> </h4>".mysqli_error($link);
        echo "</div>";
    }else{
        echo " <div class='alert alert-info' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>UNIDADE DESVINCULADA COM SUCESSO</h4>";
        echo "</div>";  
    }
}

if (isset($_GET['vincular_prev'])) {
    $ibge_reduzido = (int)$_GET[ibge_reduzido];

    echo "<h3>Vinculação de Unidades</h3>";
    $f -> abre_form("unidades.gestao.php?vincular=1&ibge_reduzido=$ibge_reduzido");
    echo "<div class='form-group'>";
    echo "<label for='inputText3' class='col-form-label'>Indique a unidade a que deseja vincular:</label><br>";
    echo "<select class='form-control' id='ibge_microrregional' required='' name='ibge_microrregional'>";
    echo "<option value='0'></option>";
    $query = "select * from tb_municipios_ibge order by nome asc";
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++) 
        {
            $row = mysqli_fetch_array($result);
            $ibge_reduzido = $row[ibge_reduzido];
            $nome =  (stripslashes($row[nome]));
            echo "<option value='".$ibge_reduzido."'";
            echo ">".$nome."</option>";         
        }
    echo "</select></div>";
    $f->f_button("Vincular");
    echo "</form>";

    echo "<br><br><hr><br></br>";
}



  $vinculo = array();
  $unidade_vinculante = array();
  $nome_geral = array();
  $query = "select nome, ibge_reduzido, ibge_microrregional from tb_municipios_ibge";
  $result=mysqli_query($link, $query);
  $num = mysqli_num_rows($result);
  for($i=0;$i<$num;$i++) 
  {
      $row = mysqli_fetch_array($result);
      $nome= (stripslashes($row[nome]));
      $ibge_reduzido = $row[ibge_reduzido];
      $ibge_microrregional = $row[ibge_microrregional];
      $nome_geral[$ibge_reduzido] = $nome;
      
      if ($ibge_microrregional != $ibge_reduzido) {
        $vinculo[$ibge_microrregional][] = $ibge_reduzido;
        $unidade_vinculante[$ibge_reduzido] = $ibge_microrregional;
      }
  }

  echo "<table class='table table-striped'> <thead>";
  echo "<tr>";
  echo "<th scope='col'> </th>";
  echo "<th scope='col'> </th>";
  echo "<th scope='col' width='200px;'> </th>";
  echo "</tr>";
  echo "</thead><tbody>";

  $query = "select ibge_reduzido, ibge_microrregional from tb_municipios_ibge order by nome asc";
  $result=mysqli_query($link, $query);
  $num = mysqli_num_rows($result);
  for($i=0;$i<$num;$i++) 
  {
      $row = mysqli_fetch_array($result);
      $ibge_reduzido = $row[ibge_reduzido];
      $ibge_microrregional = $row[ibge_microrregional];
    echo "<tr>";
   echo "<td>".$nome_geral[$ibge_reduzido];
    // busca unidades vinculadas a esta, se houver
    $nucleo  = 0;
    if (isset($vinculo[$ibge_reduzido])) {
        echo "<ul>";
        for ($i=0; $i < count($vinculo[$ibge_reduzido]); $i++) { 
            $id_ref = $vinculo[$ibge_reduzido][$i];
            echo "<li>".$nome_geral[$id_ref]."</li>";
            $nucleo=1;
        }
        echo "</ul>";
    }
    echo "</td>";

      // verifica se essa unidade está vinculada à outra.
   
      if (isset($unidade_vinculante[$ibge_reduzido])) {
        $id_vinculo = $unidade_vinculante[$ibge_reduzido];
        echo "<td>Vinculado à ".$nome_geral[$id_vinculo]."</td>";
        echo "<td><a href = 'unidades.gestao.php?desvincular=1&ibge_reduzido=$ibge_reduzido' class = 'btn btn-primary' style = 'margin-right:0px;'>desvincular</a></td>";
      }else{
        echo "<td> </td>";
       
       if($nucleo ==0 ){
        echo "<td><a href = 'unidades.gestao.php?vincular_prev=1&ibge_reduzido=$ibge_reduzido' class = 'btn btn-primary'>Vincular a uma unidade</a></td>";
        }else{
            echo "<td>A unidade não pode ser vinculada a outra, por conter outras unidades</td>";
        }
      }

      echo "</tr>";
  }
  echo "</tbody>   </table>";







$f -> fecha_card();


$f -> fecha();


$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();


?>
