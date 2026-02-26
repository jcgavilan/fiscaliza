<?php




function f_input_read($colunas, $titulo, $id, $retorno) {
    echo "<div class='col-xl-".$colunas." col-lg-".$colunas." col-md-".$colunas." col-sm-".$colunas." col-".$colunas."' style = 'float: left;'>";
    ?>
     <div class="form-group">
        <label for="inputText3" class="col-form-label"><?php echo $titulo;?></label>
        <input id="<?php echo $id;?>" name="<?php echo $id;?>" type="text" class="form-control" value ="<?php echo $retorno;?>" readonly>
    </div>
    <?php 
    echo "</div>";
}

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

$nome_pagina= "Bem-Vindo";
$a=new Abre_titulo();
$a->titulo_pagina($nome_pagina);

$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

$f -> abre(12);

$f -> abre(8);
    $f -> abre_card(12);

    $id_estabelecimento = $_GET[id_estabelecimento];
    settype($id_estabelecimento, 'integer');

    $f -> abre(10);
        echo "<h2>Página do Estabelecimento</h2><hr><br>";
    $f -> fecha();
    $f -> abre(2);
        echo "<a href = 'gera.alvara.lista.php?id_estabelecimento=$id_estabelecimento' class = 'btn btn-primary'>GERAR ALVARÁ</a>";
    $f -> fecha();

    $query = "select * from tb_estabelecimentos where id = $id_estabelecimento";
    $result=mysqli_query($link, $query);
    // $num = mysqli_num_rows($result);
    // for($i=0;$i<$num;$i++) 
    //         {
                $row = mysqli_fetch_array($result);
                $id = $row[id];
                $nome_estabelecimento =  (stripslashes($row[nome_estabelecimento]));
                $razao_social =  (stripslashes($row[razao_social]));
                $id_ramo_atividade =  (stripslashes($row[id_ramo_atividade]));
                $cnpj =  (stripslashes($row[cnpj]));
                $endereco_rua =  (stripslashes($row[endereco_rua]));
                $endereco_numero =  (stripslashes($row[endereco_numero]));
                $endereco_bairro =  (stripslashes($row[endereco_bairro]));
                $endereco_cep =  (stripslashes($row[endereco_cep]));
                $id_municipio =  (stripslashes($row[id_municipio]));
                $telefone_fixo =  (stripslashes($row[telefone_fixo]));
                $telefone_celular =  (stripslashes($row[telefone_celular]));
                $email =  (stripslashes($row[email]));
                $nome_proprietario =  (stripslashes($row[nome_proprietario]));
                $idade_permitida =  (stripslashes($row[idade_permitida]));  

               f_input_read(6, "Nome Fantasia", "nome_estabelecimento", "$nome_estabelecimento");
               f_input_read(6, "Razão Social", "razao_social", "$razao_social");
               f_input_read(6, "CNPJ", "cnpj", "$cnpj");
               //busca o nome da atividade
                $query2 = "select * from tb_ramos_atividade where id = ".$id_ramo_atividade;
                $result2=mysqli_query($link, $query2);    
                $row2 = mysqli_fetch_array($result2);
                $nome_atividade =  (stripslashes($row2[nome]));

               f_input_read(6, "Ramo de Atividade", "ramo_atividade", "$nome_atividade");
               f_input_read(6, "Rua", "rua", "$endereco_rua");
               f_input_read(2, "Numero", "numero", "$endereco_numero");
               f_input_read(2, "Bairro", "bairro", "$endereco_bairro");
               f_input_read(2, "CEP", "cep", "$endereco_cep");
              
               $query2 = "select * from tb_municipios_nacional where id = ".$id_municipio;
               $result2=mysqli_query($link, $query2);    
               $row2 = mysqli_fetch_array($result2);
               $nome_municipio =  (stripslashes($row2[cidade]));

               f_input_read(4, "Município", "municipio", "$nome_municipio");
               f_input_read(4, "Telefone Fixo", "telefone", "$telefone_fixo");
               f_input_read(4, "Telefone Celular", "celular", "$telefone_celular");

               f_input_read(4, "Proprietário", "proprietario", "$nome_proprietario");
               f_input_read(4, "Email", "email", "$email");
               f_input_read(4, "Idade Permitida", "idade_permitida", "$idade_permitida");
               //echo $nome_estabelecimento;

               $f -> abre(12);
                echo "<h3 style = 'margin-top: 30px;'>Alvarás Emitidos</h3><hr>";

    echo "<table class='table table-bordered'>";
  echo "<thead>";
    echo "<tr>";
    echo "<th scope='col'>#</th>";  
    echo "<th scope='col'>Data do registro</th>";
    echo "<th scope='col'>Documento</th>";
    echo "<th scope='col'>Operador</th>";
    echo "</tr>";
  echo "</thead>";
  echo "<tbody>";

        $alvaras_nome = array();
        $query = "select id, nome from tb_alvaras_tipo";
        $result=mysqli_query($link, $query);
        $num = mysqli_num_rows($result);
        for($i=0;$i<$num;$i++) 
                {
                    $row = mysqli_fetch_array($result);
                    $id = $row[id];
                    $alvaras_nome[$id] = (stripslashes($row[nome]));
                }

        $query = "select * from tb_alvaras_gerados where id_estabelecimento = ".$id_estabelecimento." order by data_registro asc";
        $result=mysqli_query($link, $query);
        $num = mysqli_num_rows($result);
        for($i=0;$i<$num;$i++) 
                {
                    $row = mysqli_fetch_array($result);
                    $id = $row[id];
                    $id_policial =  (stripslashes($row[id_policial]));
                    $id_alvara =  (stripslashes($row[id_alvara]));
                    $arquivo_pdf =  (stripslashes($row[arquivo_pdf]));
                    $data_registro = $row[data_registro];
                    $data_print = date("d/m/Y", $data_registro);


                    echo "<tr>";
                    echo "<th scope='row'><a href = 'alvaras/$arquivo_pdf' target = '_blank'><i class='icon-doc'></i></a></th>";
                    echo "<td>$data_print</td>";
                    echo "<td><a href = 'alvaras/$arquivo_pdf' target = '_blank'>".$alvaras_nome[$id_alvara]."<a href = '#'></td>";
                    echo "<td>-</td>";
                    echo "</tr>";
                }

        echo "</tbody>";
        echo "</table>";


            $f -> fecha_card();
               
            $f -> fecha();

            echo "</div>";
            
            $f -> abre(4);
                $f -> abre_card(12);
                    $f -> abre(6);
                    echo "<h3>Documentos PDF</h3>";
                    
                    $f -> fecha();
                    $f -> abre(6);
                        echo "<a href = 'painel.documentos.php?id_estabelecimento=$id_estabelecimento' style = 'float: right;'><i class='icon-docs'></i> &nbsp;Painel de Documentos</a>";
                    $f -> fecha();

                    $f -> abre(12);
                    // LEITURA DOS DOCUMENTOS
                    echo "<br>";

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
                    echo "<br>";
                    $f -> fecha();

                    $f -> abre(12);
                    echo "<a href = 'form.carrega.arquivos.php?id_estabelecimento=$id_estabelecimento' class = 'btn btn-primary' style = 'width: 100%'>Carregar Documentos PDF</a>";
                    $f -> fecha();

                $f -> fecha_card();

            $f -> fecha();
            
            $f -> fecha();
            
          
    $footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();




?>