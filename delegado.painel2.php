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



if(!isset($_SESSION["usuario_fis_cpf"]))
{
  echo "entrou aqui";// julio
  header('Location: https://integra.pc.sc.gov.br/');
  die; //julio
  exit();
}



if ($_SESSION['usuario_fis_is_delegado'] == 1 || $_SESSION['usuario_fis_cpf'] == '02200965974' || $_SESSION['usuario_fis_cpf'] == '08151009810') { // QUE HARDCODE MAIS FEIO, MAS TEM QUE TESTAR NÉ

  // header('Location: https://integra.pc.sc.gov.br/');
  // exit();

// echo "entrou if";
// die;

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




  $f -> abre(6);
  echo "<h1>Painel de Homologação de Carteiras e Alvarás:</h1>";
  $f -> fecha();
  $f -> abre(6);
  echo "<div style = 'display: block; width: 100%; text-align:right;  '>";

  // CÓDIGO GPT PARA TENTAR EVITAR O CASH
  $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  $string = '';
  for ($i = 0; $i < 6; $i++) {
      $string .= $chars[rand(0, strlen($chars) - 1)];
  }
  echo "<div style='color: #F5F5F5; font-size: 6PX;'>$string</div>";
  // FIM DO CÓDIGO GPT

  //echo "<a href = '#' class = 'btn btn-primary'><i class='fa-solid fa-video'></i> VÍDEO DE ORIENTAÇÃO PARA ESTA PÁGINA</a>";

  ?>
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#videoModal" style = 'float: right; '>
  VÍDEO DE ORIENTAÇÃO PARA ESTA PÁGINA
  </button><br>
  </h2>
  <div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel" aria-hidden="true" style = 'width: 100%' >
    <div class="modal-dialog" role="document" style = 'width: 100%'>
      <div class="modal-content" style = 'width: 100%'>
        <div class="modal-header" style = 'width: 100%'>
          <h3 class="modal-title" id="videoModalLabel"></h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style = "width: 100%; height: 400px; background-color: #FFFFFF; overflow-y: scroll;padding-top:8px;">
        <iframe width="440" height="320" src="https://www.youtube.com/embed/ozIpKDkJsls" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
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
  $f -> fecha();
  echo "</div>";
  //$f -> abre_card(12);

  $f -> abre_card(12);



  $query = "select nome, ibge_reduzido from tb_municipios_ibge  order by nome asc";
  $result=mysqli_query($link, $query);
  $num = mysqli_num_rows($result);
  for($i=0;$i<$num;$i++)
      {
        $row = mysqli_fetch_array($result);
        $id = $row[ibge_reduzido];
        $nome =  (stripslashes($row[nome]));
        $municipio_nome[$id] = $nome;
      }       

  if (!isset($_GET['id_pedido'])) {


          // NA PÁGINA INICIAL, TAMBÉM BUSCA AS CARTEIRAS QUE ESTÃO PENDENTES DE APROVAÇÃO DO DELEGADO
      if(isset($_SESSION['usuario_fis_carteiras_auth']) && ($_SESSION['usuario_fis_carteiras_auth'] == $_SESSION['usuario_fis_cpf']) ) {

      $query = "select id, nome, data_expedicao, tipo, arquivo, policial_nome, id_municipio from tb_carteiras where delegado_aprov = 0 ";
      $result=mysqli_query($link, $query);
      $num = mysqli_num_rows($result);
      if($num == 0) {
          echo "<br><br><h2>Sem carteiras aguardando aprovação</h2>";
      }else{
          echo "<br><br><h2>Lista de Carteiras aguardando aprovação <span style = 'color: #cc0000;'>($num)</span></h2>";
          echo "<br><table class='table table-striped'>";
          echo "<thead>";
          echo "<tr>";
              echo "<th scope='col'>Nome do portador</th>";
              echo "<th scope='col'>Data de expedição</th>";
              echo "<th scope='col'>Tipo</th>";
              echo "<th scope='col'>Municipio</th>";
              echo "<th scope='col'>Atendente</th>";
              echo "<th scope='col'>VER PAINEL DA CARTEIRA</th>";
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
        //   echo "<td><a href = 'carteiras/$arquivo' target = '_blank' class = 'btn btn-primary' style = 'width:100%;'>Link Carteira</a></td>";
            echo "<td><a href = 'carteira.painel.php?id=$id&data_expedicao=$data_expedicao' target = '_blank' class = 'btn btn-primary' style = 'width:100%;'>Ver painel</a></td>";
            echo "</tr>";
          }        

          echo "</table>";
          // FIM DA APRESENTAÇÃO DE CARTEIRAS
        }


      $nome_documento = array();
      $query = "select id, nome from tb_alvaras_tipo";
      $result = mysqli_query($link, $query);
      $num = mysqli_num_rows($result);
      for($i=0;$i<$num;$i++)
      {
          $row = mysqli_fetch_array($result);
          $id = $row[id];
          $nome = stripslashes($row[nome]);
          $nome_documento[$id] = $nome;
      }
      
      if ($_SESSION['usuario_for_status'] == "normal") { // para habilitar o delegado da gerência visualiar todos para homologar
        $q_fim = "and id_municipio in (".$_SESSION['usuario_fis_ibge'].")";
      }else{
        $q_fim = "ORDER BY  CASE WHEN id_municipio = 420540 THEN 0 ELSE 1 END, id_municipio ASC;"; // busca primeiro florianópolis, depois o resto
      }

      $query = "select id, ultima_movimentacao, nome_estabelecimento, cnpj, data_pedido, id_municipio, tipo_pedido from tb_cidadao_pedidos where status = 2 and data_conclusao = 0 $q_fim"; // ainda não foram recebidos.
      $result=mysqli_query($link, $query);
      $num = mysqli_num_rows($result);
      echo "<br><br><h2>Lista de Alvarás aguardando homologação <span style = 'color: #cc0000;'>($num)</span></h2>";

      if($num > 0) {
        echo "<table class='table'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th scope='col'>Estabelecimento</th>";
        //echo "<th scope='col'>CNPJ</th>";
        echo "<th scope='col'>Data do pedido</th>";
        echo "<th scope='col'>Municipio</th>";
        echo "<th scope='col'>Data da Análise</th>";
        echo "<th scope='col'>Tipo de Documento</th>";
        echo "<th scope='col'> </th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";


        for($i=0;$i<$num;$i++) 
        {
            $a =  str_shuffle($base);
            $str = substr($a, 0, 40);
            $row = mysqli_fetch_array($result);
            $id_pedido = $row[id];
            $data_pedido = $row[data_pedido];
            $id_municipio = $row[id_municipio];

            if ($_SESSION['usuario_for_status'] == "adm") {

              if ( $id_municipio == 420540) {
                $bg = "style = 'background-color: #ffffff;'";
              }else{
                $bg = "style = 'background-color: #f5f5f5;'";
                }
            }


            $ultima_movimentacao = $row[ultima_movimentacao];
            $tipo_pedido = $row[tipo_pedido];
            $nome_estabelecimento= (stripslashes($row[nome_estabelecimento]));
            $cnpj= (stripslashes($row[cnpj]));

            echo "<tr>";
            echo "<th scope='row' $bg>$nome_estabelecimento <span style = 'color: #888888;'>[$id_pedido]</span></th>";
            echo "<td $bg>".$municipio_nome[$id_municipio]."</td>";
            echo "<td $bg>".date("d/m/y", $data_pedido)."</td>";
            echo "<td $bg>".date("d/m/y", $ultima_movimentacao)."</td>";
            echo "<td $bg>".$nome_documento[$tipo_pedido]."</td>";
            echo "<th scope='row' $bg><a HREF = 'delegado.painel2.php?id_pedido=$id_pedido' class = 'btn btn-primary'>VER SOLICITAÇÃO</a></th>";

            echo "</tr>";

        }

        echo "</tbody>";
        echo "</table>";
    }

  
      
  }else{

  $id_pedido = (int)$_GET['id_pedido'];


  // POR CONTA DO ERRO FANTASMA, ESTOU TENTANDO INSERIR UMA ALEATORIEDADE PARA IMPEDIR O TRAVAMENTO DA PÁGINA POR CONTA DO CACHE

  $string_aleatoria = str_shuffle("ABCEDEFGHIJKLMEN");

  $query = "update tb_cidadao_pedidos set string_aleatoria = '$string_aleatoria' where id = ".$id_pedido;
  $result=mysqli_query($link, $query);


  $query = "select * from tb_cidadao_pedidos where id = ".$id_pedido;
      $result=mysqli_query($link, $query);

      $a =  str_shuffle($base);
      $str = substr($a, 0, 40);
      $row = mysqli_fetch_array($result);
      $id_pedido = $row[id];
      $string_aleatoria = $row[string_aleatoria];
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
      $pagina_resumo= (stripslashes($row[pagina_resumo]));
      $comentarios_policiais= (stripslashes($row[comentarios_policiais]));
      
      // $f->f_input_read(3, "Nome do Estabelecimento:", "", "$nome_estabelecimento");
      // $f->f_input_read(3, "Razão Social:", "", "$razao_social");
      // $f->f_input_read(2, "CNPJ:", "", "$cnpj");

      // $query2 = "select nome from tb_ramos_atividade where id = ".$id_ramo_atividade;
      // $result2 = mysqli_query($link, $query2);
      // $row2 = mysqli_fetch_array($result2);
      // $nome_ramo_atividade = (stripslashes($row2[nome]));
      // $f->f_input_read(2, "Ramo de Atividade:", "", "$nome_ramo_atividade");
      // $f->f_input_read(2, "Nome do Proprietário:", "", "$nome_proprietario");

      // $f->f_input_read(3, "Endereço:", "", "$endereco_rua, $endereco_numero - $endereco_bairro");
      // $f->f_input_read(1, "CEP:", "", "$endereco_cep");

      // $query2 = "select nome from tb_municipios_ibge where ibge = ".$id_municipio;
      // $result2 = mysqli_query($link, $query2);
      // $row2 = mysqli_fetch_array($result2);
      // $nome_municipio = (stripslashes($row2[nome]));        
      // $f->f_input_read(2, "Municipio:", "", "$nome_municipio");

      // $f->f_input_read(2, "Telefone Fixo:", "", "$telefone_fixo");
      // $f->f_input_read(2, "Telefone Celular:", "", "$telefone_celular");
      // $f->f_input_read(2, "Email:", "", "$email");

  


  //$f -> fecha_card();

  $query = "Select nome from tb_alvaras_tipo where id = ".$tipo_pedido;
  $result=mysqli_query($link, $query);
  $row = mysqli_fetch_array($result);
  $nome_tipo=  stripslashes($row[nome]);

  echo "<h2>$nome_tipo</h2>";


  $x = $cnpj;

  //echo "<h2 style = 'color: #999999;'>Documentos do Solicitante: $nome_estabelecimento / CNPJ $cnpj  ";
  echo "<h2 style = 'color: #666666; padding-right:16px;'>";

  echo "Documentos do Solicitante: $nome_estabelecimento / CNPJ: ".$x[0].$x[1]."-".$x[2].$x[3].$x[4]."-".$x[5].$x[6].$x[7]."/".$x[8].$x[9].$x[10].$x[11]."-".$x[12].$x[13]."<span style = 'color: #ffffff'>$string_aleatoria</span> </h2>";
   
  echo "<div style = 'display: block; width: 100%; text-align: right; padding: 16px; margin-bottom: 16px!important;'>";
  ?>
   <a href = 'delegado.painel2.reserva.php?id_pedido=<?php echo $id_pedido;?>' style = ' margin: 0px 10px;' class = 'btn btn-danger' title ='EM CASO DE ERRO DA PÁGINA, CLIQUE AQUI PARA CONTINUAR O PROCEDIMENTO EM OUTRA.'><i class='icon-direction'></i></a>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style = ' '>
  VER TODOS OS DADOS DA EMPRESA
  </button>
  <!-- Button trigger modal -->
  <a href = 'gera.resumo.empresa.print.php?id_pedido=<?php echo $id_pedido;?>&data_pedido=<?php echo $data_pedido;?>' style = ' margin: 0px 5px;' class = 'btn btn-primary'><i class='icon-printer'></i></a>
  <?php

if($status < 2){
  echo "<a href = 'pedidos.cancelar.php?id_pedido=$id_pedido&data_pedido=$data_pedido' style = ' margin-right: 5px;' class = 'btn btn-danger'><i class='icon-close'></i> CANCELAR</a>";
  }

  ?>

  <br></div><!-- fecha o div para os botões acima do painel de documentos -->


  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="exampleModalLabel">Dados do Solicitante</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style = "width: 100%; height: 500px; background-color: #FFFFFF; overflow-y: scroll;padding-top:8px;">
    <?php
    
    $query2 = "select resumo from tb_empresa_resumo where id_pedido = $id_pedido LIMIT 1";
    $result2=mysqli_query($link, $query2);
    $row2 = mysqli_fetch_array($result2);
    $pagina_resumo = stripslashes($row2[resumo]);
    echo $pagina_resumo;
    

    ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">FECHAR</button>

        </div>
      </div>
    </div>
  </div>
  <?php

  // PARA CONTROLE DOS ARQUIVOS, É MAIS INTERESSANTE TER EM UMA TABELA PROVISÓRIA, QUE VAI TER OS CAMPOS EXCLUÍDOS COM A FINALIZAÇÃO DO PROCESSO

  // VERIFICA SE JÁ EXISTE O CADASTRO DOS ARQUIVOS PARA ESTE PEDIDO
  $f->abre(4);

  /*$query = "select id from tb_cidadao_arquivos where id_pedido = $id_pedido";
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
  }*/

  $doc_nomes = array();
  $query = "select id, nome from tb_documentos_tipo";
  $result=mysqli_query($link, $query);
  $num = mysqli_num_rows($result);
  for($i=0;$i<$num;$i++) 
      {
          $row = mysqli_fetch_array($result);
          $id = $row['id'];
          $doc_nomes[$id] =  (stripslashes($row[nome]));
      }

  //$f -> abre_form("gera.alvara.prev.cidadao.php");  
  $f -> abre_form("delegado.painel.concluir.php");  
  echo "<input type='hidden' name='id_pedido' value='$id_pedido'>";  
  echo "<div style = 'display: block; width: 100%; height: 680px; overflow-y: scroll;'>";
      echo "<table class='table table-bordered'>";
      echo "<thead>";
        echo "<tr>";

        echo "<th scope='col'>DOCUMENTOS FORNECIDOS PELO CIDADÃO</th>";
        echo "<th scope='col'>Aprovação</th>";
        echo "</tr>";
      echo "</thead>";
      echo "<tbody style = 'border: 0px;'>";


    
      $query = "select * from tb_cidadao_arquivos where id_pedido = ".$id_pedido." and aprovado = 'S'";
      $result=mysqli_query($link, $query);
      $num = mysqli_num_rows($result);
      for($i=0;$i<$num;$i++) 
          {
              $row = mysqli_fetch_array($result);
              $nome_documento =  (stripslashes($row[nome]));
              $nome_arquivo =  trim(stripslashes($row[arquivo]));
              $id_documento_tipo =  (stripslashes($row[id_documento_tipo]));
              $aprovado =  (stripslashes($row[aprovado]));
              $data_analise =  (stripslashes($row[data_analise]));
              $id_policial =  (stripslashes($row[id_policial]));
              $nome_policial_aprovacao =  (stripslashes($row[nome_policial_aprovacao]));

              if($i == 0){
                  $arquivo_inicial = $nome_arquivo;
              }

              echo "<tr>"; 
                  echo "<td>";
                  echo "<a href = '#' onClick='showPdf(\"arquivos_cidadao_prev/$nome_arquivo\");' style = 'font-size:12px;' data-toggle='tooltip' >".$doc_nomes[$id_documento_tipo]." </a><BR>";

                  echo '<label for="inputText3" class="col-form-label"></label>';
                  echo '<label class="custom-control custom-radio custom-control-inline" style = "display:none;" id="radio_hide_'.$i.'">';
                  echo "<input type='radio' name='aprovado_$i' class='custom-control-input' value='1' onclick='show_tr_delegado_nao($i);' checked><span class='custom-control-label' style = 'font-size:10px;'><p>Manter aprovação</p></span>";
                  echo "</label>";
                  echo '<label class="custom-control custom-radio custom-control-inline">';
                  echo "<input type='radio' name='aprovado_$i' class='custom-control-input' value='0'  onclick='show_tr_delegado_sim($i);' ><span class='custom-control-label'><p>REVOGAR APROVAÇÃO</p></span>";
                  echo "</label>";

                  echo "</td>";
                  echo "<td>";
                  echo "<p><span  style = 'font-size: 11px;'>Aprovado por ".$nome_policial_aprovacao." em ".date("d/m/Y H:i", $data_analise)."</span></p>";
                  echo "</td>";
            /*      echo "<td>";
                  if ($aprovado == 'A') {
                      echo '<div class="form-group form-check-inline">';
                      echo '<label for="inputText3" class="col-form-label"></label>';
                      echo '<label class="custom-control custom-radio custom-control-inline">';
                      echo "<input type='radio' name='aprovado_$i' class='custom-control-input' value='1' onclick='show_tr_nao($i);' checked><span class='custom-control-label' style = 'font-size:10px;'><p>Sim</p></span>";
                      echo "</label>";
                      echo '<label class="custom-control custom-radio custom-control-inline">';
                      echo "<input type='radio' name='aprovado_$i' class='custom-control-input' value='0'  onclick='show_tr_sim($i);' ><span class='custom-control-label'><p>Não</p></span>";
                      echo "</label>";
                      echo "</div>";
                  }

                  if ($aprovado == 'S') {
                    echo "<h2><SPAN style = 'color: #008000;'>APROVADO</SPAN></h2>";
                  }
                  echo "</td>";
                  echo "</tr>";
  */
                  echo "<tr style = 'display:none;' id = 'tr_$i'>";
                  echo "<td colspan= '2'>";
                  echo '<div class="form-group">';
                  echo "<label for='exampleFormControlTextarea1'><p>Justificativa da negativa (orientação ao cidadão)</p></label>";
                  echo "<textarea class='form-control' id='justificativa_$i'  name='justificativa_$i' rows='3'></textarea>";
                  echo "</div>";
                  echo "</td>"; 
                  echo "</tr>";

          }


          echo "</tbody>";
              echo "</table>";
              echo "</div>";

              //echo  "<br><h4><input id='show_botao_enviar' type='checkbox' name='show_botao_enviar' value='1' onchange='show_botao()'/> Confirmo que analisei todos os documentos</h4>";

              if (strlen($comentarios_policiais) > 1) {
                echo "<b>Comentários de análises policiais</b><br>$comentarios_policiais";
              }
              echo "<div id = 'botao_enviar' style = 'display:TABLE; PAdding-top: 36px;'>";
              $f->f_button("CONCLUIR");
              echo "</div>";

              echo "</form>";


              $f -> fecha();

    // $f -> abre(8);

      echo "<div id = 'show' class='col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8' style = 'float: right;'>";

      echo "<object data='arquivos_cidadao_prev/$arquivo_inicial' type='application/pdf' style = 'width:100%; height:680px;'><embed src='arquivos_cidadao_prev/$arquivo_inicial' type='application/pdf' /></object>";

      $f -> fecha();
      
  }

//3rtyt8fe4sj3jh8dytailz6cerxrixugjuom9f3l.pdf*Contrato Social; 3rtyt8fe4sj3jh8dytailz6cerxrixugjuom9f3l.pdf*Alvará de Funcionamento emitido pela Prefeitura Local; 3rtyt8fe4sj3jh8dytailz6cerxrixugjuom9f3l.pdf*Autorização expedida pela Vigilância Sanitária, ou protocolo; 

} // fim do IF DELEGADo

$f -> fecha_card();

$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();

?>