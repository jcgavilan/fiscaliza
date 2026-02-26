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
include "mysql.conecta.rep.php";
include "classes/class.html.php";
$f = new Forms();

require_once 'htmlpurifier/HTMLPurifier.standalone.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);



$header=new Header_adm_WEB(); 
$a=new Menu_adm($link);
$nome_pagina= "Bem-Vindo";
$a=new Abre_titulo();
$a->titulo_pagina($nome_pagina);
$f -> abre_card(12);
echo "<h1>Atendimento ao Cidadão - Solicitação Interna de Alvará</h1><hr><br>";
$f->abre(12);


$n_bombas = (int)$_POST[n_bombas];

$f -> abre_form("formulario.interno.cidadao.requerimento.php?code=".$_GET[code]);

echo "<h3 class='policia'>Informe a quantidade de produtos vendidos por bomba de combustível</h3>";

echo "<table class='table table-striped'>";
  echo "<thead>";
    echo "<tr>";
      echo "<th scope='col' style = 'width:200px'><span class='policia'>Número da Bomba</span> </th>";
      echo "<th scope='col'><span class='policia'>Quantidade de produtos na bomba</span></th>";
    echo "</tr>";
  echo "</thead>";
  echo "<tbody>";

  for ($i=1; $i <= $n_bombas; $i++) { 
    echo "<tr>";
        echo "<th scope='row' style = 'width:150px;'><span class='policia'>Bomba nº $i</span></th>";
        echo "<td>";
       // $f->f_input_coluna(1, "", "bomba_$i", "");


        echo "<input id='produtos_bomba_$i' name='produtos_bomba_$i' type='text' class='form-control' value ='' style = 'width:50px;'>";

        echo "</td>";
    echo "</tr>";
  }
  echo "<input type='hidden' name='n_bombas' value='$n_bombas'>";
  echo " </tbody>  </table>";
  echo "<div style = 'display:block; width: 100%; padding: 48px 0; '>";
  $f->f_button("SALVAR E PROSSEGUIR");
  echo "</form></div>";
$f->fecha();
     

    ?> 
    
    <!--  -------------------  F I M   D O    C O N T E U D O  -------------------------------------->
    </div>
    <script src="../fiscaliza/assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="../fiscaliza/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    
    <script src="../fiscaliza/assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <script src="../fiscaliza/assets/libs/js/main-js.js"></script>
    <!--<script src="assets/vendor/inputmask/js/jquery.inputmask.bundle.js"></script> -->
    <script src="../fiscaliza/assets/vendor/parsley/parsley.js"></script>
    <script src="../fiscaliza/formularios.js"></script>
    <!--<script src="js/show.js"></script>-->
    <script src="../fiscaliza/js/jquery.inputmask.bundle.js"></script>
    <script src="../fiscaliza/js/normas_corregedoria.js"></script>
    <script src="../fiscaliza/js_dinamico/corregedores_tramitacao.js"></script>
    <script src="../fiscaliza/js/show.js"></script>
    <script src="../fiscaliza/js/somentePdf.js"></script>
    
    <script>
    // esse código existe porque o required não está funcionando direto nos select.
    
    document.getElementById("id_ramo_atividade").required = true;
    document.getElementById("id_municipio").required = true;
    
    </script>
    
    <script>
    $(function(e) {
        "use strict";
        $(".cc-inputmask").inputmask("999-999-999-99"),
        $(".cnpj-inputmask").inputmask("99-999-999/9999-99"),
        $(".date-inputmask").inputmask("99/99/9999"),
        $(".cpf-inputmask").inputmask("999.999.999-99"),
        $(".horario-inputmask").inputmask("99:99"),
        $(".cep-inputmask").inputmask("99.999-999"),
        $(".telefone_fixo-inputmask").inputmask("(99) 9999-9999"),
        $(".telefone_celular-inputmask").inputmask("(99) 99999-9999"),
            $(".phone-inputmask").inputmask("(999) 999-9999"),
            $(".international-inputmask").inputmask("+9(999)999-9999"),
            $(".xphone-inputmask").inputmask("(999) 999-9999 / x999999"),
            $(".purchase-inputmask").inputmask("aaaa 9999-****"),
            $(".cc-inputmask").inputmask("9999 9999 9999 9999"),
            $(".ssn-inputmask").inputmask("999-99-9999"),
            $(".isbn-inputmask").inputmask("999-99-999-9999-9"),
            $(".currency-inputmask").inputmask("$9999"),
            $(".percentage-inputmask").inputmask("99%"),
            $(".decimal-inputmask").inputmask({
                alias: "decimal",
                radixPoint: "."
            }),
    
            $(".email-inputmask").inputmask({
                mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[*{2,6}][*{1,2}].*{1,}[.*{2,6}][.*{1,2}]",
                greedy: !1,
                onBeforePaste: function(n, a) {
                    return (e = e.toLowerCase()).replace("mailto:", "")
                },
                definitions: {
                    "*": {
                        validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~/-]",
                        cardinality: 1,
                        casing: "lower"
                    }
                }
            })
    });
    </script>
    
    <!-- </body>
    
    </html> -->
    
    
    <?php
    
    $f -> fecha_card();
    $footer=new Footer_adm_WEB();
    $footer->Footer_adm_WEB();
    
?>