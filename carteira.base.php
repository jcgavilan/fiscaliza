<?php



header("Cache-Control: no-cache, no-store, must-revalidate, max-age=0, s-maxage=0, proxy-revalidate, private");
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
header_remove("ETag");
header_remove("Last-Modified");
session_start();

if( (!isset($_SESSION["usuario_fis_cpf"]))) //  || ($_SESSION['usuario_fis_carteiras_auth'] != $_SESSION['usuario_fis_cpf'])
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

$tipo = $_GET['tipo'];

$f -> abre(12);
$f -> abre_card(12);

if(isset($_SESSION['usuario_fis_carteiras_auth']) && ($_SESSION['usuario_fis_carteiras_auth'] == $_SESSION['usuario_fis_cpf']) ) {

echo "<div style = 'display: block; width: 100%; text-align:right; padding-right: 30px; '>";

//echo "<a href = '#' class = 'btn btn-primary'><i class='fa-solid fa-video'></i> VÍDEO DE ORIENTAÇÃO PARA ESTA PÁGINA</a>";

?>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style = 'float: right; '>
VÍDEO DE ORIENTAÇÃO PARA ESTA PÁGINA
</button><br>
</h2>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style = 'width: 100%' >
  <div class="modal-dialog" role="document" style = 'width: 100%'>
    <div class="modal-content" style = 'width: 100%'>
      <div class="modal-header" style = 'width: 100%'>
        <h3 class="modal-title" id="exampleModalLabel"></h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style = "width: 100%; height: 400px; background-color: #FFFFFF; overflow-y: scroll;padding-top:8px;">
      <iframe width="440" height="320" src="https://www.youtube.com/embed/bR93qLWtnw8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">FECHAR</button>

      </div>
    </div>
  </div>
</div>
<br>
<?php
echo "</div>";

echo "<h1>Geração de carteiras</h1><hr><br>";

// $f->abre(12);

// echo "<h2></h2>";

// $f -> fecha();

//$f->abre(12);

    $f->abre(4);
        echo "<a href = 'carteira.formulario.php?tipo=blaster' style = 'width:100%' class = 'btn btn-primary'>CARTEIRA DE BLÁSTER</a>";
    $f -> fecha();

    $f->abre(4);
        echo "<a href = 'carteira.formulario.php?tipo=arma_particular' style = 'width:100%' class = 'btn btn-primary'>USO DE ARMA PARTICULAR</a>";
    $f -> fecha();

    $f->abre(4);
        echo "<a href = 'carteira.formulario.php?tipo=aposentado' style = 'width:100%' class = 'btn btn-primary'>ARMA PARA POLICIAL APOSENTADO</a>";
    $f -> fecha();

//$f -> fecha();

$f->abre(12);

    echo "<BR><HR><BR><h2>Pesquisa de Carteiras Geradas</h2>";

    //$f -> abre_form("carteira.base.php?pesquisar=1");

    echo "<div class='form-group'>";
          //  echo "<label for='inputText3' class='col-form-label'>Tipos de Pesquisa: </label><br>";

             echo "<label class='custom-control custom-radio custom-control-inline'>";
                echo "<input type='radio' name='pesquisa_tipo' class='custom-control-input' id = 'radio_pesquisa_aberta' value='pesquisa_aberta' onchange='f_pesquisa_aberta()'><span class='custom-control-label'>Critérios abertos</span>";
            echo "</label>";

            echo "<label class='custom-control custom-radio custom-control-inline'>";
            echo "<input type='radio' name='pesquisa_tipo' class='custom-control-input'  id = 'radio_pesquisa_nome' onchange='f_pesquisa_nome()' value='nome'><span class='custom-control-label'>Nome do Portador ou Empresa</span>";
            echo "</label>";

            echo "<label class='custom-control custom-radio custom-control-inline'>";
            echo "<input type='radio' name='pesquisa_tipo' class='custom-control-input' id = 'radio_pesquisa_cpf' onchange='f_pesquisa_cpf()' value='cpf'><span class='custom-control-label'>CPF</span>";
            echo "</label>";

            echo "<label class='custom-control custom-radio custom-control-inline'>";
            echo "<input type='radio' name='pesquisa_tipo' class='custom-control-input'  id = 'radio_pesquisa_cnpj' value='cnpj' onchange='f_pesquisa_cnpj()'><span class='custom-control-label'>CNPJ</span>";
            echo "</label>";

        echo "</div>";

    echo "<div id = 'div_pesquisa_nome' style = 'display: none; width: 100%;'>";
        $f->abre(12);
            echo "<form action='carteira.base.php?pesquisa=nome' method='post'>";
            $f -> abre(4);
           // $f -> f_input("Nome ou Sobrenome do Portador ou da empresa (blaster)", "nome", "");
            echo "<div class='form-group'>";
                echo "<label for='exampleInputUsername1'>Nome ou Sobrenome do Portador ou da empresa (blaster)</label>";
                echo "<input type='text' class='form-control' id='nome' name='nome' placeholder='' value='' autocomplete='off'>";
            echo "</div>";
            $f->fecha();
            $f -> abre(2);
                echo "<DIV style = 'display: block; padding-top: 25px;'>";
                $f-> f_button("PESQUISAR");
                ECHO "</DIV>";
                echo "</form>";
            $f -> fecha();
            $f->fecha();
    echo "</div>";

    
    echo "<div id = 'div_pesquisa_cpf' style = 'display: none; width: 100%;'>";
        $f->abre(12);
            echo "<form action='carteira.base.php?pesquisa=cpf' method='post'>";
            $f -> abre(2);
            $f -> f_input_mask("CPF do Portador", "cpf", "cpf");
            $f->fecha();
            $f -> abre(2);
                echo "<DIV style = 'display: block; padding-top: 25px;'>";
                $f-> f_button("PESQUISAR");
                ECHO "</DIV>";
                echo "</form>";
            $f -> fecha();
            $f->fecha();
    echo "</div>";

    echo "<div id = 'div_pesquisa_cnpj' style = 'display: none; width: 100%;'>";
    $f->abre(12);
        echo "<form action='carteira.base.php?pesquisa=cnpj' method='post'>";
        $f -> abre(3);
        $f -> f_input_mask("CNPJ da empresa (Blaster, somente)", "cnpj", "cnpj");
        $f->fecha();
        $f -> abre(2);
            echo "<DIV style = 'display: block; padding-top: 25px;'>";
            $f-> f_button("PESQUISAR");
            ECHO "</DIV>";
            echo "</form>";
        $f -> fecha();
        $f->fecha();
echo "</div>";

echo "<div id = 'div_pesquisa_geral' style = 'display: none; width: 100%;'>";
    $f->abre(12);
    echo "<form action='carteira.base.php?pesquisa=geral' method='post'>";
    // $f -> abre(2);
    // echo "<div class='form-group'>";
    // echo "<label for='inputText3' class='col-form-label'>Nome do portador</label>";
    // echo "<input  type='text' name= 'nome' class='form-control' value ='' >";
    // echo "</div>";

    // $f -> fecha();        


    $f -> abre(2);
        echo "<div class='form-group'>";
        echo "<label for='inputText3' class='col-form-label'>Data inicial</label>";
        echo "<input  type='text' name= 'data1' class='form-control date-inputmask' id='date-mask' value ='' >";
        echo "</div>";
        
    $f -> fecha();

    $f -> abre(2);
        echo "<div class='form-group'>";
        echo "<label for='inputText3' class='col-form-label'>Data final</label>";
        echo "<input  type='text' name= 'data2' class='form-control date-inputmask' id='date-mask' value ='' >";
        echo "</div>";

    $f -> fecha();

    $f -> abre(2);
    echo "<div class='form-group'>";
    echo "<label for='inputText3' class='col-form-label'>Tipo de Carteira</label><br>";
    echo "<select class='form-control' id='tipo' name='tipo'>";
    echo "<option value=''>todos</option>";
    echo "<option value='blaster'>Bláster</option>";
    echo "<option value='arma_particular'>Uso de arma particular</option>";
    echo "<option value='aposentado'>Policial aposentado</option>";
    echo "</select></div>";
    $f -> fecha();

    $f -> abre(2);
    echo "<div class='form-group' >";
    echo "<label for='inputText3' class='col-form-label'>Municipio </label><br>";
    echo "<select class='form-control' id='id_municipio'  name='id_municipio' >";
    echo "<option value=''></option>";  
    $municipio_nome = array();

    // if (isset($_SESSION['usuario_especial_restricao'])) {
    //     $restricao = " where ibge_reduzido in (".$_SESSION['usuario_especial_restricao'].") ";
    // }else{
    //     $restricao = '';
    // }

    $query = "select nome, ibge_reduzido from tb_municipios_ibge $restricao order by nome asc";
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++)
        {
          $row = mysqli_fetch_array($result);
          $id = $row[ibge_reduzido];
          $nome =  (stripslashes($row[nome]));
          echo "<option value='$id'>".$nome."</option>";  
          $municipio_nome[$id] = $nome;
        }        

    echo "</select></DIV>";
    $f -> fecha();

     $f -> abre(2);
     echo "<DIV style = 'display: block; padding-top: 35px;'>";
     $f-> f_button("PESQUISAR");
     ECHO "</DIV>";
     echo "</form>";
     $f -> fecha();

     $f->fecha(); // fecha a pesquisa geral.
     echo "</div>"; // final da div que vai ter o display block/none


     


    // echo "<br><br><button type='submit' class='btn btn-primary'>PESQUISAR</button>";
    // echo "</form>";

$f -> fecha();


if ($_GET['pesquisa']) {

    $f -> abre(12);


    $pesquisa_valida = 0;
    
    if($_GET['pesquisa'] == 'geral')
        {
            if ($_POST[tipo] != '') {
                $tipo  = $f->limpa_variavel($_POST['tipo'], 20, $purifier);
                $str .= " AND tipo = '$tipo' ";
                $pesquisa_valida = 1;
                //echo " Vínculo: ".$nome_parentesco[$id_vinculo]." ";
            }

            if ($_POST[id_municipio] != 0) {
                $id_municipio = $_POST[id_municipio];
                settype($id_municipio, 'integer');
                $str .= " AND id_municipio = $id_municipio ";
                $pesquisa_valida = 1;
        //     echo " Municipio: ".$municipio_nome[$id_municipio]." ";
            }


            if($_POST[data1] != '' && $_POST[data2] != ''){

                // recebe a pesquisa por intervalo de datas, e confere se as datas estão preenchidas corretamente


                $data1 = $_POST[data1];
                $d = explode("/", $data1);
                $dia = $d[0]; 
                settype($dia, 'integer');
                $mes = $d[1];
                settype($mes, 'integer');
                $ano = $d[2];
                settype($ano, 'integer');

                if( ($dia >= 1 && $dia <= 31) && ($mes >= 1 && $mes <= 12) && ($ano > 2000 && $ano < 2032) ){
                    $data_ini = mktime(0, 0, 0, $mes, $dia, $ano);
                    $data1_ok = 1;
                }

                $data2 = $_POST[data2];
                $d = explode("/", $data2);
                $dia = $d[0]; 
                settype($dia, 'integer');
                $mes = $d[1];
                settype($mes, 'integer');
                $ano = $d[2];
                settype($ano, 'integer');

                if( ($dia >= 1 && $dia <= 31) && ($mes >= 1 && $mes <= 12) && ($ano > 2000 && $ano < 2032) ){
                    $data_fim = mktime(23, 59, 59, $mes, $dia, $ano);
                    $data2_ok = 1;
                }

                if($data1_ok == 1 && $data2_ok == 1){

                    $str .= " AND data_expedicao >= $data_ini and data_expedicao <= $data_fim";
                    $pesquisa_valida = 1;
                }else{
                    echo "<h2>Atenção, houve erro no preenchimento do período (".$_POST[data1]." a ".$_POST[data2].")</h2>";
                }
            }

        }
    
        if($_GET['pesquisa'] == 'nome')
        {
            $nome  = $f->limpa_variavel($_POST['nome'], 30, $purifier);
            if (strlen($nome) > 3) {
            $str = " and nome like '%$nome%' OR blaster_empresa_nome like '%$nome%' ";
            $pesquisa_valida = 1;
            }else{
                echo "<h2>Preencha o nome com 3 letras ou mais.</h2>";
            }
         }

         if($_GET['pesquisa'] == 'cpf')
        {
            $cpf  = $f->limpa_variavel($_POST['cpf'], 30, $purifier);

            $cpf  = str_replace(".", "", $cpf);
            $cpf  = str_replace("-", "", $cpf);

            if (strlen($cpf) == 11) {
            $str = " and cpf = '$cpf' ";
            $pesquisa_valida = 1;
            }else{
                echo "<h2>CPF não está no formato correto.</h2>";
            }
         }

         if($_GET['pesquisa'] == 'cnpj')
        {
            $cnpj  = $f->limpa_variavel($_POST['cnpj'], 30, $purifier);

            $cnpj  = str_replace(".", "", $cnpj);
            $cnpj  = str_replace("-", "", $cnpj);
            $cnpj  = str_replace("/", "", $cnpj);

            if (strlen($cnpj) == 14) {
            $str = " and blaster_empresa_cnpj = '$cnpj' ";
            $pesquisa_valida = 1;
            }else{
                echo "<h2>CNPJ não está no formato correto.</h2>";
            }
         }

    if ($pesquisa_valida == 0) {
        $str = ' AND id == 0'; // para prevenir a pesquisa aberta, se não vierem campos preenchidos
    }
    
    $query = "select id, nome, data_expedicao, tipo, arquivo, policial_nome, id_municipio, delegado_aprov from tb_carteiras where id!=0 $str ORDER BY ID DESC"; // delegado_aprov = 1
    if ($_SESSION['usuario_fis_cpf'] == '02200965974' ) {
    //    echo $query;
    }

    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    if($num == 0) {
        echo "<h1>Sem resultados para sua pesquisa</h1>";
    }else{
        echo "<h2>Sua pesquisa retornou <span style = 'color: #000000;'>$num</span> resultados</h2>";
        echo "<br><table class='table table-striped'>";
        echo "<thead>";
        echo "<tr>";
            echo "<th scope='col'>Nome</th>";
            echo "<th scope='col'>Data de expedição</th>";
            echo "<th scope='col'>Tipo</th>";
            echo "<th scope='col'>Municipio</th>";
            echo "<th scope='col'>Atendente</th>";
            echo "<th scope='col'>Carteira</th>";
            echo "<th scope='col'>Painel de Documentos</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
    }
    for($i=0;$i<$num;$i++)
        {
          $row = mysqli_fetch_array($result);
          $id = $row[id];
          $nome =  (stripslashes($row[nome]));
          $data_expedicao = $row[data_expedicao];
          $tipo =  (stripslashes($row[tipo]));
          $arquivo =  (stripslashes($row[arquivo]));
          $policial_nome =  (stripslashes($row[policial_nome]));
          $id_municipio = $row[id_municipio];
          $delegado_aprov = $row[delegado_aprov];

          


            switch ($tipo) {
                case 'blaster':
                    $tipo_print = 'Bláster';
                break;

                case 'arma_particular':
                    $tipo_print = 'Uso de Arma Particular';
                break;

                case 'aposentado':
                    $tipo_print = 'Aposentado';
                break;
            }


          echo "<tr>";

          echo "<td>$nome</td>";
          echo "<td>".date("d/m/Y", $data_expedicao)."</td>";
          echo "<td>$tipo_print</td>";
          echo "<td>".$municipio_nome[$id_municipio]."</td>";
          echo "<td>".$policial_nome."</td>";
          echo "<td>";
          if ($delegado_aprov == 1) {
            echo "<a href = 'carteiras/$arquivo' target = '_blank' class = 'btn btn-primary' style = 'width:100%;'>Link Carteira</a>";
          }else{
            echo "-";
          }
          
          
          echo "</td>";
          echo "<td><a href = 'carteira.painel.php?id=$id&data_expedicao=$data_expedicao' target = '_blank' class = 'btn btn-primary' style = 'width:100%;'>Ver painel</a></td>";
          echo "</tr>";
        }        

        echo "</table>";

    $f -> fecha();

}


}


$f -> fecha_card();
$f -> fecha();

$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();


?>

