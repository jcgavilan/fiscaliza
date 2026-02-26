<?php


session_start();


header("Cache-Control: no-cache, no-store, must-revalidate, max-age=0, s-maxage=0, proxy-revalidate, private");
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
header_remove("ETag");
header_remove("Last-Modified");

// MAIS UMA TENTATIVA DE DRIBLAR O ERRO FANTASMA
// Função para gerar a string aleatória
function gerarStringAleatoria($tamanho = 2000) {
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $quantidade = strlen($caracteres);
    $resultado = '';

    for ($i = 0; $i < $tamanho; $i++) {
        $resultado .= $caracteres[random_int(0, $quantidade - 1)];
    }

    return $resultado;
}

// Gera a string e imprime como comentário HTML no topo
$stringSecreta = gerarStringAleatoria();
echo "<!-- $stringSecreta -->\n";

if(!isset($_SESSION['usuario_fis_cpf']))
{
  header('Location: https://integra.pc.sc.gov.br/login');
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

echo "<h2>Pesquisa</h2><hr>";
echo "<form action='pesquisa.php?pesquisa=1' method='post'>";

    $f -> abre(2);
    // echo "<div class='form-group'>";
    // echo "<label for='inputText3' class='col-form-label'>CNPJ</label>";
    // echo "<input  type='text' name= 'cnpj' class='form-control cnpj-inputmask' value ='' >";
    // echo "</div>";

    // INICIO DO CÓDIGO COM PESQUISA DE NOME E CNPJ

    //if ($_SESSION['usuario_fis_cpf'] == '02200965974') {
      
        echo "<div class='form-group' style = 'padding: 6px;'>";
     //   echo "<label for='inputText3' class='col-form-label'>Tipo de pesquisa: </label><br>";

        echo "<label class='custom-control custom-radio custom-control-inline'>";
            echo "<input type='radio' name='pesquisa_pessoa'   id='pesquisa_pessoa_cnpj_radio' class='custom-control-input' value='1' onchange='pesquisa_pessoa_cnpj_funcao()'";
            echo "><span class='custom-control-label'>&nbsp;CNPJ &nbsp;</span>";
        echo "</label>";

        echo "<label class='custom-control custom-radio custom-control-inline'>";
            echo "<input type='radio' name='pesquisa_pessoa' id='pesquisa_pessoa_nome_radio' class='custom-control-input' value='1' onchange='pesquisa_pessoa_nome_funcao()'";
            echo "><span class='custom-control-label'>&nbsp; Nome </span>";
        echo "</label>";

        echo "<label class='custom-control custom-radio custom-control-inline'>";
            echo "<input type='radio' name='pesquisa_pessoa' id='pesquisa_pessoa_id_radio' class='custom-control-input' value='1' onchange='pesquisa_pessoa_id_funcao()'";
            echo "><span class='custom-control-label'>&nbsp; ID </span>";
        echo "</label>";

        echo "</div>";

        echo "<div id = 'pesquisa_pessoa_cnpj_div' style = 'display: none;'>";
            $f -> f_input_mask("Número do CNPJ", "cnpj", "cnpj");
        echo "</div>";

        echo "<div id = 'pesquisa_pessoa_nome_div' style = 'display: none;'>";
            $f -> f_input("Nome da empresa", "nome", "");
        echo "</div>";

        echo "<div id = 'pesquisa_pessoa_id_div' style = 'display: none;'>";
            $f -> f_input("ID do alvara", "id_alvara_q", "");
        echo "</div>";

  //  }


    // FIM DO CÓDIGO COM PESQUISA DE NOME E CNPJ

    $f -> fecha();        


    $f -> abre(1);
        echo "<div class='form-group'>";
        echo "<label for='inputText3' class='col-form-label'>Data inicial</label>";
        echo "<input  type='text' name= 'data1' class='form-control date-inputmask' id='date-mask' value ='' >";
        echo "</div>";
        
    $f -> fecha();

    $f -> abre(1);
        echo "<div class='form-group'>";
        echo "<label for='inputText3' class='col-form-label'>Data final</label>";
        echo "<input  type='text' name= 'data2' class='form-control date-inputmask' id='date-mask' value ='' >";
        echo "</div>";

    $f -> fecha();

    $f -> abre(2);
    echo "<div class='form-group'>";
    echo "<label for='inputText3' class='col-form-label'>Tipo de Documento</label><br>";
    echo "<select class='form-control' id='id_alvara' name='id_alvara'>";
    echo "<option value='0'>todos</option>";
    $nome_parentesco = array();
    $query = "select id, nome from tb_alvaras_tipo where id_categoria in (1, 3) order by id asc";
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++)
            {
                $row = mysqli_fetch_array($result);
                $id = $row[id];
                $nome =  (stripslashes($row[nome]));
                echo "<option value='".$id."'>".$nome."</option>"; 
                $nome_parentesco[$id] =  $nome;
            }
    echo "</select></div>";
    $f -> fecha();
    $f -> abre(1);
    echo "<div class='form-group'>";
    echo "<label for='inputText3' class='col-form-label'>CNAE</label><br>";
    echo "<select class='form-control' id='id_cnae' name='id_cnae'>";
    echo "<option value='0'>todos</option>";
    $nome_parentesco = array();
    $query = "select id, cnae from tb_cnaes order by cnae asc";
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++)
            {
                $row = mysqli_fetch_array($result);
                $id = $row[id];
                $cnae =  (stripslashes($row[cnae]));
                echo "<option value='".$id."'>".$cnae."</option>"; 
            }
    echo "</select></div>";
    $f -> fecha();

     if ($_SESSION['usuario_for_status'] == "adm") {
   //  echo $query;
    
        $f -> abre(1);
        echo "<div class='form-group'>";
        echo "<label for='inputText3' class='col-form-label'>DRPs</label><br>";
        echo "<select class='form-control' id='id_drp' name='id_drp'>";
        echo "<option value=''></option>";
        $nome_parentesco = array();
        $query = "select id_ref, nome from tb_drps order by id_ref asc";
        $result=mysqli_query($link, $query);
        $num = mysqli_num_rows($result);
        for($i=0;$i<$num;$i++)
                {
                    $row = mysqli_fetch_array($result);
                    $id_ref = $row[id_ref];
                    $nome =  (stripslashes($row[nome]));
                    echo "<option value='".$id_ref."'>".$nome."</option>"; 
                }
        echo "</select></div>";
        $f -> fecha();
    }

    $f -> abre(2);
    echo "<div class='form-group' >";
    echo "<label for='inputText3' class='col-form-label'>Municipio </label><br>";
    echo "<select class='form-control' id='id_municipio'  name='id_municipio' >";
    echo "<option value=''></option>";  
    $municipio_nome = array();

    if (isset($_SESSION['usuario_fis_ibge'])) {
        $restricao = " where ibge_reduzido in (".$_SESSION['usuario_fis_ibge'].") ";
    }else{
        $restricao = '';
    }

    // sobrescreve a regra se o usuário for administrador

    if ($_SESSION['usuario_for_status'] == 'adm') {
        $restricao = '';
    }

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
     $f -> fecha();


     
echo "</form>";

$f -> fecha_card();


if(isset($_GET['pesquisa'])){
    $f -> abre_card(12);



       if ($_POST[id_alvara] != 0) {
            $id_alvara = $_POST[id_alvara];
            settype($id_alvara, 'integer');
            $str .= " AND tipo_pedido = $id_alvara ";
            //echo " Vínculo: ".$nome_parentesco[$id_vinculo]." ";
        }

        if ($_POST[id_cnae] != 0) {

            $nao_cumulativo = 1; // estabelece a regra, para não executar o comando final caso haja uma exceção.
            $id_cnae = $_POST[id_cnae];
            settype($id_cnae, 'integer');
            // na pesquisa, vamos ter que unificar os resultados para os casos de estabelecimentos que são separados pelo tamanho.

            // supermercados e hipermercados
            if ($id_cnae == 34 || $id_cnae == 35) { $str .= " AND id_atividade_ref in (34, 35) ";$nao_cumulativo=0;}
            // hotéis
            if ($id_cnae == 45 || $id_cnae == 46) { $str .= " AND id_atividade_ref in (45, 46) ";$nao_cumulativo=0;}
            // motéis
            if ($id_cnae == 47 || $id_cnae == 48) { $str .= " AND id_atividade_ref in (47, 48) ";$nao_cumulativo=0;}

            // agora é o contrário, nos casos em que o cnae não está cadastrado, porque está vinculado a um tipo de alvará

            if ($id_cnae == 54 || $id_cnae == 55 || $id_cnae == 56) { $str .= " AND tipo_pedido = 7 ";$nao_cumulativo=0;} // pirotécnico
            if ($id_cnae == 57 || $id_cnae == 58) { $str .= " AND tipo_pedido = 9 ";$nao_cumulativo=0;} // produtos químicos e explosivos
            if ($id_cnae == 59 || $id_cnae == 60) { $str .= " AND tipo_pedido = 10 ";$nao_cumulativo=0;} // produtos combustíveis
            if ($id_cnae == 61 || $id_cnae == 62) { $str .= " AND tipo_pedido = 11 ";$nao_cumulativo=0;} // gás

            if ($nao_cumulativo == 1) { // ou seja, não caiu em nenhum das exceções anteriores.
                $str .= " AND id_atividade_ref = $id_cnae ";
            }
            
            //echo " Vínculo: ".$nome_parentesco[$id_vinculo]." ";
        }

        if ($_POST[id_municipio] != 0) {
            $id_municipio = $_POST[id_municipio];
            settype($id_municipio, 'integer');
            $str .= " AND id_municipio = $id_municipio ";
       //     echo " Municipio: ".$municipio_nome[$id_municipio]." ";
        }else{
            // a condição somente se aplica se o usuário não for administrador
            if ($_SESSION['usuario_for_status'] != 'adm') {
                // no caso do policial não selecionar um municipio, o sistema vai buscar somente os que ele está autorizado a ver.
                if (isset($_SESSION['usuario_fis_ibge'])) {
                    $str .= " AND id_municipio in (".$_SESSION['usuario_fis_ibge'].") ";
                }
            }
            

        }


        if ($_POST['id_drp'] != '') {
            
            $id_drp = (int)$_POST['id_drp'];

            $query = "select ibge_reduzido from tb_municipios_ibge where numero_drp = $id_drp";
            $result=mysqli_query($link, $query);
            $num = mysqli_num_rows($result);
            for($i=0;$i<$num;$i++)
                {
                $row = mysqli_fetch_array($result);
                $ibge_reduzido = $row[ibge_reduzido];

                    if ($i == 0) {
                        $str_mun = $ibge_reduzido;
                    }else {
                        $str_mun .= ', '.$ibge_reduzido;
                    }

                }  

                $str .= " AND id_municipio in ($str_mun) ";
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
                $data_fim = mktime(0, 0, 0, $mes, $dia, $ano);
                $data2_ok = 1;
            }

            if($data1_ok == 1 && $data2_ok == 1){

                $str .= " AND data_pedido >= $data_ini and data_pedido <= $data_fim";

            }else{
                echo "<h2>Atenção, houve erro no preenchimento do período (".$_POST[data1]." a ".$_POST[data2].")</h2>";
            }
        }

        if (strlen(trim($_POST[nome])) > 1) {
            $nome  = $f->limpa_variavel($_POST['nome'],30, $purifier);

            $str .= " AND nome_estabelecimento like '%$nome%' ";

        }
        
            // se recebe CNPJ, ignora qualquer outro parâmetro preenchido e busca todos os pedidos da empresa.
            $cnpj=$_POST[cnpj];
            $cnpj = str_replace("-", "", $cnpj);
            $cnpj = str_replace("/", "", $cnpj);
            if (strlen($cnpj) == 14) {
               $str = " and cnpj = '$cnpj' ";
            
            }

            // se recebe o id do alvara, ignora todo o resto
            $id_alvara = (int)$_POST['id_alvara_q'];
            if ($id_alvara != 0) {
                $str = " AND id = $id_alvara";
            }



        echo "</p></div>";

        echo "<table class='table table-striped'>";
        echo "<thead>";
        echo "<tr>";
            echo "<th scope='col' style = 'width: 30%;'>Empresa</th>";
            echo "<th scope='col'>Data do Pedido</th>";
            echo "<th scope='col'>Data da Conclusão</th>";
            echo "<th scope='col'>Município</th>";
            echo "<th scope='col'>Status</th>";
            echo "<th scope='col'>&nbsp;</th>";
            echo "<th scope='col'>&nbsp;</th>";
            echo "<th scope='col'>Policial Resp.</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        // if (isset($_SESSION['usuario_especial_restricao'])) {
        //    $str_restricao = " AND id_municipio in(".$_SESSION['usuario_especial_restricao'].")";
        // }else{
        //     $str_restricao = '';
        // }

        if ($_SESSION['usuario_for_status'] != 'adm') {
          
            $str_restricao = " AND id_municipio in(".$_SESSION['usuario_fis_ibge'].")";
        }

        $query = "select valor_taxa, valor_vistoria from tb_cidadao_pedidos where id != 0 $str $str_restricao ORDER BY data_conclusao ASC, data_pedido DESC";
         

        //*************************trecho para DEBUG ********************************** */
        //if ($_SESSION["usuario_fis_cpf"] == '02200965974' || $_SESSION["usuario_fis_cpf"] == '08151009810') {
            //echo $query;
            // die;
        //}
        // Julio 04/12/2025
        //****************************fim DEBUG ************************************** */
        $result=mysqli_query($link, $query);
        $num = mysqli_num_rows($result);
        
        if($num == 0){
            echo "<h3> &nbsp; Sua pesquisa não teve resultados, reveja os termos pesquisados.</h3>";
        }else{
            echo "<h2> &nbsp; Sua pesquisa trouxe <span style = 'color: #000000; '>$num</span> resultado"; if($num>1){echo "s";}echo "</h2>";
           // echo "<h3> &nbsp; Resultados para a pesquisa: $num";   
            $valor_taxa = 0;
            for($i=0;$i<$num;$i++) // loop para apresentar o valor das taxas primeiro
            {
            $row = mysqli_fetch_array($result);
            $valor_taxa += ($row[valor_taxa]+$row[valor_vistoria]);
            }

            $valor_taxa_print = number_format($valor_taxa, 2, ',', '.');

            echo "<h3> &nbsp;  Valor das taxas arrecadadas nos alvarás dessa pesquisa: <span style = 'color: #000000; '>R$ $valor_taxa_print</span></h3>";
        }
     
 
        $query = "select id, nome_estabelecimento, tipo_pedido, data_pedido, data_conclusao, status, status_com_cidadao, id_policial, nome_policial, documento_final, id_municipio, historico, registro_migrado, valor_taxa from tb_cidadao_pedidos where id != 0 $str $str_restricao ORDER BY  data_pedido DESC"; //data_conclusao ASC,
        
        if ($_SESSION['usuario_fis_cpf'] == '02200965974' || $_SESSION["usuario_fis_cpf"] == '08151009810') {
            echo $query;
        }
        
        //
        $result=mysqli_query($link, $query);
        $num = mysqli_num_rows($result);
        
        for($i=0;$i<$num;$i++)
        {
          $row = mysqli_fetch_array($result);
          $id = $row[id];
          $nome_estabelecimento = stripslashes($row[nome_estabelecimento]);
          $tipo_pedido = $row[tipo_pedido];
          $registro_migrado = $row[registro_migrado];
          $data_conclusao = $row[data_conclusao];
          $data_pedido = $row[data_pedido];
          $status = $row[status];
          $status_com_cidadao = $row[status_com_cidadao];
          $id_policial = $row[id_policial];
          $nome_policial = stripslashes($row[nome_policial]);
          $documento_final = stripslashes($row[documento_final]);
          $historico = stripslashes($row[historico]);
          $id_municipio = $row[id_municipio];
          
        
          echo "<tr>";

           if ($id_policial == 0) { $link_empresa = "pedidos.lista.php"; }
           if ($id_policial != 0 && $data_conclusao == 0) {$link_empresa = "pedidos.painel2.php?id_pedido=$id";}
           if ($data_conclusao != 0) {$link_empresa = "pedidos.concluidos.php?id_pedido=$id";}


            echo "<th scope='row'><a href = 'historico.php?id_pedido=$id' target='_blank'><i class='icon-magnifier'></i></a> &nbsp;<a href = '$link_empresa' target='_blank'>$nome_estabelecimento</a> <span style = 'color: #888888;'>[$id]</span></th>";
            echo "<td>".date("d/m/Y", $data_pedido)."</td>";
            echo "<td>";
                if ($data_conclusao != 0) {
                    echo date("d/m/Y", $data_conclusao);
                }else{
                    echo "-";
                }
            echo  "</td>";
            echo "<td>".$municipio_nome[$id_municipio]."</td>";

            echo "<td>";
 
            if ($id_policial == 0 && $data_conclusao == 0) {
             //   if($registro_migrado != 0){
                echo "<a href = '$link_empresa' target='_blank' class = 'btn btn-danger' style = 'width: 100%'>AINDA NÃO RECEBIDO</a>";
             //   }
            }
  
            if ($id_policial != 0 && $data_conclusao == 0) {
                echo "<a href = '$link_empresa' target='_blank' class = 'btn btn-warning' style = 'width: 100%'>EM ATENDIMENTO";
                if ($status_com_cidadao == 1) {
                    echo "<br>[ DILIGÊNCIA DO CIDADÃO ]";
                }else{

                    if ($status == 2) {
                        echo "<br>[ HOMOLOGAÇÃO DO DELEGADO ]";
                    }

                }


                echo "</a>";
            }

            if ($data_conclusao != 0 && $status < 4) { // 4 e 5 são cancelado e vencido, respectivamente.f
                echo "<a href ='$link_empresa' target='_blank' class = 'btn btn-success' style = 'width: 100%'>CONCLUÍDO</a>";
               
            }
            // status = 4 -> cancelado
            if ( $status == 4) {
                echo "<a href ='$link_empresa' target='_blank' class = 'btn btn-dark' style = 'width: 100%'> CANCELADO</a>";
            }

            if ( $status == 5) {
                echo "<a href ='$link_empresa' target='_blank' class = 'btn btn-info' style = 'width: 100%'> VENCIDO</a>";
            }
                
            echo "</td>";

            echo "<td>";
            if ($data_conclusao != 0) {
            echo " &nbsp;<a href = 'https://sistemas.pc.sc.gov.br/cidadao/alvaras/$documento_final' target= '_blank' class = 'btn btn-primary'>VER ALVARÁ</a>";
            }
            echo "</td>";

            echo "<td>";
            if ($data_conclusao != 0) {
                if($status < 4){
                    if ($_SESSION['usuario_fis_is_delegado'] == 1 ){
                    echo " &nbsp;<a href = 'invalida.documento.php?id=$id&id_municipio=$id_municipio' target= '_blank' class = 'btn btn-dark'>INVALIDAR</a>";
                    }
                }
            }
            echo "</td>";

            echo "<td>";
            echo $nome_policial;
            echo "</td>";

          echo "</tr>"; 
      }
      echo "</table>";

        


    $f -> fecha_card();
    $f -> fecha();
}


$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();


?>

<!-- CREATE TABLE tb_unidades_policiais (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
id_municipio int not null default 0,
nome VARCHAR(160) NOT NULL default ''
) -->

<!-- CREATE TABLE tb_policiais (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
id_unidade int not null default 0,
nome VARCHAR(180) NOT NULL default '',
cpf VARCHAR(11) NOT NULL default '',
senha VARCHAR(250) NOT NULL default ''
) -->

<!-- CREATE TABLE tb_municipios_ibge (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
nome VARCHAR(160) NOT NULL default '',
ibge VARCHAR(7) NOT NULL default '',
)  -->