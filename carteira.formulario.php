<?php

session_start();

if( (!isset($_SESSION["usuario_fis_cpf"])) || ($_SESSION['usuario_fis_carteiras_auth'] != $_SESSION['usuario_fis_cpf']))
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

switch ($tipo) {
    case 'blaster':
        $page_form  = "carteira.form.blaster.php";
        $titulo_pagina = "Carteira de Blaster";
        $id_tipo = 17; // ID NA TABELA TB_ALVARAS_TIPO
        $link_youtube = '0SiTTs1Zgvo';
        break;
    
    case 'aposentado':
        $page_form  = "carteira.form.aposentado.php";
        $titulo_pagina = "Licença de Porte de Arma para Policial Aposentado";
        $id_tipo = 18; // ID NA TABELA TB_ALVARAS_TIPO
        $link_youtube = 'OscLTugIo8M';
        break;

    case 'arma_particular':
        $page_form  = "carteira.form.armaparticular.php";
        $titulo_pagina = "Autorização para uso de arma particular em serviço";
        $id_tipo = 19; // ID NA TABELA TB_ALVARAS_TIPO
        $link_youtube = 'EDsuuE-R8gI';
        break;
}


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
      <iframe width="440" height="320" src="https://www.youtube.com/embed/<?php echo $link_youtube;?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
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

echo "<h1>Requerimento - $titulo_pagina </h1>";

$f -> abre_form("carteira.recebe.dados.php?tipo=$tipo");
include $page_form;
$f->abre(12);
echo "<br><br><button type='submit' class='btn btn-primary'>SALVAR E PROSSEGUIR</button>";
echo "</form>";
$f->fecha();
$f -> fecha_card();
$f -> fecha();

$footer=new Footer_adm_WEB();
$footer->Footer_adm_WEB();


?>

