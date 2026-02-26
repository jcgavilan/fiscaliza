<?php
header("Cache-Control: no-cache, no-store, must-revalidate, max-age=0, s-maxage=0, proxy-revalidate, private");
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
header_remove("ETag");
header_remove("Last-Modified");
session_start();

if(!isset($_SESSION["usuario_fis_cpf"]))
{
  header('Location: https://integra.pc.sc.gov.br/');
  exit();
}

include "classes/classe.forms.php";
$f = new Forms();
include "mysql.conecta.rep.php";
include "classes/class.html.php";
$header=new Header_adm_WEB(); 

$f = new Forms();

$a=new Menu_adm($link);
$nome_pagina= "Bem-Vindo";
$a=new Abre_titulo();
$a->titulo_pagina($nome_pagina);

$f -> abre_card(12);

echo "<h1>Atendimento ao Cidadão - Solicitação Interna de Alvará!</h1><hr><br>";

    $f -> abre_form("formulario.interno.cidadao.php");

    $f->abre(12);

    echo "<h3>Alvará Periódico</h3>";
    
    $query = "select * from tb_alvaras_tipo where id_categoria = 1";
    $result=mysqli_query($link, $query);
    $num = mysqli_num_rows($result);
    for($i=0;$i<$num;$i++) 
            {
                $row = mysqli_fetch_array($result);
                $id = $row[id];
                $nome =  (stripslashes($row[nome]));


                echo "<br><label class='custom-control custom-radio custom-control-inline'>";
                echo "<input type='radio' name='id_alvara' class='custom-control-input' value='$id'><span class='custom-control-label'> $nome  &nbsp;</span>";
                echo "</label>";

                // echo "<div class='form-check' style = 'padding-top:6px;>";
                // echo "<label class='form-check-label'>";
                // echo "<input type='radio' class='form-check-input'  name='id_alvara' id='id_alvara' value='$id'>$nome</label>";
                // echo "</div>";
     
            }

        echo "<br><br><h3>Produtos ou Atos Controlados</h3>";

        $query = "select * from tb_alvaras_tipo where id_categoria = 3";
        $result=mysqli_query($link, $query);
        $num = mysqli_num_rows($result);
        for($i=0;$i<$num;$i++) 
                {
                    $row = mysqli_fetch_array($result);
                    $id = $row[id];
                    $nome =  (stripslashes($row[nome]));
    
    
                    echo "<br><label class='custom-control custom-radio custom-control-inline'>";
                    echo "<input type='radio' name='id_alvara' class='custom-control-input' value='$id'><span class='custom-control-label'> $nome  &nbsp;</span>";
                    echo "</label>";
    
                    // echo "<div class='form-check' style = 'padding-top:6px;>";
                    // echo "<label class='form-check-label'>";
                    // echo "<input type='radio' class='form-check-input'  name='id_alvara' id='id_alvara' value='$id'>$nome</label>";
                    // echo "</div>";
         
                }

      

        echo "<br></br>";

        $f->f_button("SALVAR E PROSSEGUIR");

        echo "</form><br><br>";
        
    $f->fecha();

?> 
<!--  -------------------  F I M   D O    C O N T E U D O  -------------------------------------->
</div>


<script>
// esse código existe porque o required não está funcionando direto nos select.

document.getElementById("id_ramo_atividade").required = true;
document.getElementById("id_municipio").required = true;

</script>


<?php
$f -> fecha_card();
$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();

?>


