<?php

class Forms {
    
    function __construct(){} // para prevenir a duplicação do print

    function f_input_read($colunas, $titulo, $id, $retorno) {
        echo "<div class='col-xl-".$colunas." col-lg-".$colunas." col-md-".$colunas." col-sm-".$colunas." col-".$colunas."' style = 'float: left;'>";
        echo "<div class='form-group'>";
        echo "<label for='inputText3' class='col-form-label'>$titulo</label>";
        echo " <input id='$id' name='$id' type='text' class='form-control' value ='$retorno' readonly>";
        echo "</div></div>";  
        }
        
    function f_input_coluna($colunas, $titulo, $id, $retorno) {

        if ($colunas > 9) {
            $colunas_xl = $colunas; $colunas_lg = $colunas; $colunas_md = $colunas;  $colunas_sm = $colunas; $colunas_col = $colunas;
        }

        if ($colunas <= 9 && $colunas >= 6) {
            $colunas_xl = $colunas; $colunas_lg = $colunas; $colunas_md = 12;  $colunas_sm = 12; $colunas_col = 12;
        }

        if ($colunas <= 5 && $colunas >= 3) {
            $colunas_xl = $colunas; $colunas_lg = $colunas; $colunas_md = 6;  $colunas_sm = 12; $colunas_col = 12;
        }

        if ($colunas < 3) {
            $colunas_xl = $colunas; $colunas_lg = $colunas; $colunas_md = 4;  $colunas_sm = 6; $colunas_col = 6;
        }

        
        echo "<div class='col-xl-".$colunas_xl." col-lg-".$colunas_lg." col-md-".$colunas_md." col-sm-".$colunas_sm." col-".$colunas_col."' style = 'float: left; padding-right:10px;'>";
        ?>
         <div class="form-group">
            <label for="inputText3" class="col-form-label"><span class = 'policia'><?php echo $titulo;?></span></label>
            <input id="<?php echo $id;?>" name="<?php echo $id;?>" type="text" class="form-control" value ="<?php echo $retorno;?>" required=''>
        </div>
        <?php 
        echo "</div>";
    }

    function f_input_coluna_mask($colunas, $titulo, $id, $retorno, $mask) {
        if ($colunas > 9) {
            $colunas_xl = $colunas; $colunas_lg = $colunas; $colunas_md = $colunas;  $colunas_sm = $colunas; $colunas_col = $colunas;
        }

        if ($colunas <= 9 && $colunas >= 6) {
            $colunas_xl = $colunas; $colunas_lg = $colunas; $colunas_md = 12;  $colunas_sm = 12; $colunas_col = 12;
        }

        if ($colunas <= 5 && $colunas >= 3) {
            $colunas_xl = $colunas; $colunas_lg = $colunas; $colunas_md = 6;  $colunas_sm = 12; $colunas_col = 12;
        }

        if ($colunas < 3) {
            $colunas_xl = $colunas; $colunas_lg = $colunas; $colunas_md = 4;  $colunas_sm = 6; $colunas_col = 6;
        }

        
        echo "<div class='col-xl-".$colunas_xl." col-lg-".$colunas_lg." col-md-".$colunas_md." col-sm-".$colunas_sm." col-".$colunas_col."' style = 'float: left; padding-right:10px;'>";
        ?>
         <div class="form-group" id = "inputmask">
            <label for="inputText3" class="col-form-label"><span class = 'policia'><?php echo $titulo;?></span></label>
            <input id="<?php echo $id;?>" name="<?php echo $id;?>" type="text" class="form-control <?php echo $mask;?>-inputmask" id="<?php echo $mask;?>-mask"  value = '<?php echo $retorno;?>' required=''>
        </div>
        <?php 
        echo "</div>";
    }

    function f_input_coluna_mask_nao_obrigatorio($colunas, $titulo, $id, $retorno, $mask) {
        if ($colunas > 9) {
            $colunas_xl = $colunas; $colunas_lg = $colunas; $colunas_md = $colunas;  $colunas_sm = $colunas; $colunas_col = $colunas;
        }

        if ($colunas <= 9 && $colunas >= 6) {
            $colunas_xl = $colunas; $colunas_lg = $colunas; $colunas_md = 12;  $colunas_sm = 12; $colunas_col = 12;
        }

        if ($colunas <= 5 && $colunas >= 3) {
            $colunas_xl = $colunas; $colunas_lg = $colunas; $colunas_md = 6;  $colunas_sm = 12; $colunas_col = 12;
        }

        if ($colunas < 3) {
            $colunas_xl = $colunas; $colunas_lg = $colunas; $colunas_md = 4;  $colunas_sm = 6; $colunas_col = 6;
        }

        
        echo "<div class='col-xl-".$colunas_xl." col-lg-".$colunas_lg." col-md-".$colunas_md." col-sm-".$colunas_sm." col-".$colunas_col."' style = 'float: left; padding-right:10px;'>";
        ?>
         <div class="form-group" id = "inputmask">
            <label for="inputText3" class="col-form-label"><span class = 'policia'><?php echo $titulo;?></span></label>
            <input id="<?php echo $id;?>" name="<?php echo $id;?>" type="text" class="form-control <?php echo $mask;?>-inputmask" id="<?php echo $mask;?>-mask"  value = '<?php echo $retorno;?>' >
        </div>
        <?php 
        echo "</div>";
    }

    function f_text_contador_minimo($titulo, $id, $retorno, $id_contador, $n_caracteres, $n_minimo) {
        ?>
          <div class="form-group">
             <label for="exampleFormControlTextarea1"><?php echo $titulo;?></label>
             <textarea class="form-control" id="<?php echo $id;?>"  name="<?php echo $id;?>"rows="8" onkeyup="countChar(this, <?php echo $id_contador;?>, <?php echo $n_caracteres;?>)" minlength="<?php echo $n_minimo;?>" required = ''><?php echo $retorno;?></textarea>
             <div style = 'font-size:10px; width:200px; '><div id="charNum<?php echo $id_contador;?>" style = 'width:30px; float:left;'>0</div> <div style = 'float:left; '> Caracteres (máx: <?php echo $n_caracteres;?>)</div></div>
         </div>
         <?php 
     }

    function limpa_variavel($variavel, $limite, $purifier){
        $variavel = addslashes(trim($variavel));
        $variavel = filter_var($variavel, FILTER_SANITIZE_STRING);
        if($limite != 0){
            $limite--; // faço a redução de um dígito porque, como o índice começa com 0 zero, e informo o mesmo do BD, sempre terá um a mais, o que invalida a função.
            $variavel = substr($variavel, 0, $limite);
        }
        $variavel = $purifier->purify($variavel);
        return $variavel;
    }

    function f_radio_simnao_edit($titulo, $id, $opcao) {
        ?>
          <div class="form-group">
             <label for="inputText3" class="col-form-label"><span class='policia'><?php echo $titulo;?></span></label> &nbsp;
              <label class="custom-control custom-radio custom-control-inline">
                 <input type="radio" name="<?php echo $id;?>" class="custom-control-input" value="1" <?php if($opcao==1){echo "checked";}?>><span class="custom-control-label"> <span class = 'policia'>Sim</span>  &nbsp;</span>
             </label>
             <label class="custom-control custom-radio custom-control-inline">
                 <input type="radio" name="<?php echo $id;?>" class="custom-control-input" value="0" <?php if($opcao==0){echo "checked";}?>><span class="custom-control-label"> <span class = 'policia'>Não</span></span>
             </label>
         </div>
         <?php 
    }

    function f_input($titulo, $id, $retorno) {
        ?>

        <div class="form-group">
            <label for="exampleInputUsername1"><?php echo $titulo;?></label>
            <input type="text" class="form-control" id="<?php echo $id;?>" name="<?php echo $id;?>" placeholder="" value="<?php echo $retorno;?>"´autocomplete="off">
        </div>
        <?php 
    }

    function f_input_noplaceholder($titulo, $id, $retorno) {
        ?>

        <div class="form-group">
            <label for="exampleInputUsername1"><?php echo $titulo;?></label>
            <input type="text" class="form-control" id="<?php echo $id;?>" name="<?php echo $id;?>" placeholder="" value="<?php echo $retorno;?>">
        </div>
        <?php 
    }

    function f_input_required($titulo, $id, $retorno) {
        ?>

        <div class="form-group">
            <label for="exampleInputUsername1"><?php echo $titulo;?></label>
            <input type="text" class="form-control" id="<?php echo $id;?>" name="<?php echo $id;?>" placeholder="<?php echo $titulo;?>" value="<?php echo $retorno;?>" required="">
        </div>
        <?php 
    }

    function f_input_mask($titulo, $id, $mask) {
        ?>
         <div class="form-group" id = "inputmask">
         <label for="exampleInputUsername1"><?php echo $titulo;?></label>
            <input  type="text"  name="<?php echo $id;?>" class="form-control <?php echo $mask;?>-inputmask" id="<?php echo $mask;?>-mask" >
        </div>
        <?php 
    }

    function f_input_mask_required($titulo, $id, $mask) {
        ?>
         <div class="form-group" id = "inputmask">
         <label for="exampleInputUsername1"><?php echo $titulo;?></label>
            <input  type="text"  name="<?php echo $id;?>" class="form-control <?php echo $mask;?>-inputmask" id="<?php echo $mask;?>-mask" required="">
        </div>
        <?php 
    }

    function f_input_mask_retorno($titulo, $id, $mask, $retorno) {
        ?>
         <div class="form-group" id = "inputmask">
         <label for="exampleInputUsername1"><?php echo $titulo;?></label>
            <input  type="text"  name="<?php echo $id;?>" class="form-control <?php echo $mask;?>-inputmask" id="<?php echo $mask;?>-mask" value = "<?php echo $retorno;?>">
        </div>
        <?php 
    }

    function f_area($titulo, $id, $retorno){
        ?>
         <div class="form-group ">
                <label for="exampleInputUsername1"><span class = 'policia'><?php echo $titulo;?></span></label>
                <textarea class="form-control" id="<?php echo $id;?>"  name="<?php echo $id;?>" rows="10" style = "min-height:180px;"><?php echo $retorno;?></textarea>
            
        </div>
        <?php
    }

    function msg($texto, $tipo){
        echo " <div class='alert alert-$tipo' role='alert'>";
        echo " <h4 class='alert-heading' align = 'center'>$texto</h4>";
        echo "</div>";   
    }


    function f_arquivo($id){
        echo " <div class='form-group '>";
        echo "<input type='file' name='$id' id='$id' style='display: none;'>";
        echo "<label for='$id' style = 'background-color: #fdfdff; border: 1px solid #e4e6fc; border-radius: 5px; color: #6c7989; cursor: pointer; margin: 0px; padding: 10px 20px'>Selecionar Imagem</label>";
        echo "</div>";
    }

    function f_arquivo_text($id, $texto){
        echo " <div class='form-group '>";
        echo "<input type='file' name='$id' id='$id' style='display: none;'>";
        echo "<label for='$id' style = 'background-color: #fdfdff; border: 1px solid #e4e6fc; border-radius: 5px; color: #6c7989; cursor: pointer; margin: 0px; padding: 10px 20px'>$texto</label>";
        echo "</div>";
    }

    function f_arquivo_text2($id, $texto){


        echo "<p>$texto</p>";
        echo "<input type='file' name='$id' id='$id' class='filestyle'>";
        echo "<div style = 'width: 100%; height: 20px;'>&nbsp</div>";
    }

    function f_arquivo_teste($id, $texto){
        echo " <div class='form-group '>";
        echo "<input type='file' name='file' id='file'  />";
        //echo "<input type='file' name='file' id='file' class='inputfile' data-multiple-caption=\"{count} files selected\" multiple />";
      //  echo "<label for='file'>$texto</label>";
        echo "</div>";
    }

    function abre_form($target){
        echo "<form  enctype = 'multipart/form-data' action='".$target."' method='post' enctype='multipart/form-data'>";
    }

    function fecha_form(){
        echo "</form>";
    }


    function abre($numero){
        if ($numero > 9) {
            $numero_xl = $numero; $numero_lg = $numero; $numero_md = $numero;  $numero_sm = $numero; $numero_col = $numero;
        }

        if ($numero <= 9 && $numero >= 6) {
            $numero_xl = $numero; $numero_lg = $numero; $numero_md = 12;  $numero_sm = 12; $numero_col = 12;
        }

        if ($numero <= 5 && $numero >= 3) {
            $numero_xl = $numero; $numero_lg = $numero; $numero_md = 6;  $numero_sm = 12; $numero_col = 12;
        }

        if ($numero < 3) {
            $numero_xl = $numero; $numero_lg = $numero; $numero_md = 4;  $numero_sm = 6; $numero_col = 6;
        }


        echo "<div class='col-xl-".$numero_xl." col-lg-".$numero_lg." col-md-".$numero_md." col-sm-".$numero_sm." col-".$numero_col."' style = 'float: left;'>";
    }

    function abre_r($n){

        switch ($n) {
            case '3':
                echo "<div class='col-12 col-md-6 col-lg-3' style = 'float:left;'>";
                break;
            case '4':
                echo "<div class='col-12 col-md-6 col-lg-4' style = 'float:left;'>";
                break;
            case '6':
                echo "<div class='col-12 col-md-6 col-lg-6' style = 'float:left;'>";
                break;
            default:
            echo "<div class='col-md-".$n."' style = 'float:left;'>";
                break;
        }

        
    }

    function fecha(){
        echo "</div>";
    }

    function abre_card($n){
        echo "<div class='col-md-".$n." grid-margin stretch-card'>";
        echo "<div class='card'>";
        echo "<div class='card-body'>";
    }

    function fecha_card(){
        echo "</div></div></div>";
    }

    function f_button($label){
        echo "<button type='submit' class='btn btn-primary '>$label</button>";
    }

    function f_radio($titulo, $variavel, $posicao, $pos_checked){
        echo "<div class='form-check'>";
        echo "<p>$titulo</p>";
        for ($i=0; $i < count($posicao); $i++) { 
            echo "<div class='form-check'>";
            echo "<label class='form-check-label'>";
            echo "<input type='radio' class='form-check-input'  name='".$variavel."' id='".$posicao[$i]['id']."' value='".$posicao[$i]['id']."'";
            if($posicao[$i]['id']==$pos_checked){echo "checked";}
            echo ">".$posicao[$i]['nome']."</label>";
            echo "</div>";
            //name='$variavel'
        }
        echo "</div>";
    }


    function f_area_editor($titulo, $id, $retorno){
        ?>
         <div class="form-group row">
            <label for="cono1"
                class="col-sm-3 text-end control-label col-form-label"><?php echo $titulo;?></label>
            <div class="col-sm-9">
            <div id="editor" style="height: 300px; background-color:#ffffff;">
                <textarea class="form-control" id="<?php echo $id;?>"  name="<?php echo $id;?>">
                    
                     <?php echo $retorno;?>
                    </textarea> </div>
                </div>
            </div>

        <?php
        
    }


    function f_gera_nome($arquivo_name){

        $ext = strtolower(pathinfo($arquivo_name, PATHINFO_EXTENSION));
	
        $nome_final = basename($arquivo_name);
        $nome_final = strtolower($nome_final);
    
        $nome_final = str_replace("drop ", "", $nome_final);
        $nome_final = str_replace(" table ", "", $nome_final);
        $nome_final = str_replace(" from ", "f.rom", $nome_final);
        $nome_final = str_replace(";", "_", $nome_final);
        $nome_final = str_replace("#", "_", $nome_final);
        $nome_final = addslashes(strip_tags($nome_final));
        $nome_final = preg_replace("[ÃÀÁÄÂãàáäâ]", "a", $nome_final);
        $nome_final = preg_replace("[ÉÈÊËéèêë]", "e", $nome_final);
        $nome_final = preg_replace("[ÍÌÏÎíìïî]", "i", $nome_final);
        $nome_final = preg_replace("[ÕÔÓÒÖõôóòö]", "o", $nome_final);
        $nome_final = preg_replace("[ÚÙÛÜúùûü]", "u", $nome_final);
        $nome_final = preg_replace("[Çç]", "c", $nome_final);
        $nome_final = str_replace(" ", "_", $nome_final);
        $nome_final = preg_replace("[|\/*:<>$%&!?]", "", $nome_final);
        $nome_final = preg_replace("[#{}()$%&@?!²°³ºª]", "", $nome_final);
        $nome_final = substr($nome_final, 0, 220);
        $str_base = "abcdefghijklmnopqrstuvxywz";
        $str_meio = "";

        for($i=0;$i<6;$i++)
        {
            $str_prov = str_shuffle($str_base);
            $str_meio .= substr($str_prov, 0, 1);
        }

        $nome_final .= $str_meio.".".$ext;
        return $nome_final;
    }

    function insere_data($nome_variavel, $label)
    {
       echo "<div class='col-12 col-sm-12 col-md-12 col-lg-12'  style = 'float: left;'>";
        $data_get = getdate();
        $ano1 = $data_get[year];
        $ano2 = $ano1-1;
        echo "";
        echo "<div class='form-group'>";
        echo "<label for='inputText3' class='col-form-label'>$label</label><br>";
        ?>
    <div class='col-4 col-sm-4 col-md-4 col-lg-4'  style = 'float: left;'>
    <select name="data_dia<?php echo $nome_variavel;?>" id="data_dia<?php echo $nome_variavel;?>" class='form-control' > 
      <option value="0">dia</option>
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
      <option value="5">5</option>
      <option value="6">6</option>
      <option value="7">7</option>
      <option value="8">8</option>
      <option value="9">9</option>
      <option value="10">10</option>
      <option value="11">11</option>
      <option value="12">12</option>
      <option value="13">13</option>
      <option value="14">14</option>
      <option value="15">15</option>
      <option value="16">16</option>
      <option value="17">17</option>
      <option value="18">18</option>
      <option value="19">19</option>
      <option value="20">20</option>
      <option value="21">21</option>
      <option value="22">22</option>
      <option value="23">23</option>
      <option value="24">24</option>
      <option value="25">25</option>
      <option value="26">26</option>
      <option value="27">27</option>
      <option value="28">28</option>
      <option value="29">29</option>
      <option value="30">30</option>
      <option value="31">31</option>
    </select>
    </div>
    <div class='col-4 col-sm-4 col-md-4 col-lg-4'  style = 'float: left;'>
    <select name="data_mes<?php echo $nome_variavel;?>" id="data_mes<?php echo $nome_variavel;?>" class='form-control'  >
    <!--  <option value="0">m&ecirc;s</option>
      <option value="january">janeiro</option>
      <option value="february">fevereiro</option>
      <option value="march">mar&ccedil;o</option>
      <option value="april">abril</option>
      <option value="may">maio</option>
      <option value="june">junho</option>
      <option value="july">julho</option>
      <option value="august">agosto</option>
      <option value="september">setembro</option>
      <option value="october">outubro</option>
      <option value="november">novembro</option>
      <option value="december">dezembro</option>  -->
      <option value="0">m&ecirc;s</option>
      <option value="1">janeiro</option>
      <option value="2">fevereiro</option>
      <option value="3">mar&ccedil;o</option>
      <option value="4">abril</option>
      <option value="5">maio</option>
      <option value="6">junho</option>
      <option value="7">julho</option>
      <option value="8">agosto</option>
      <option value="9">setembro</option>
      <option value="10">outubro</option>
      <option value="11">novembro</option>
      <option value="12">dezembro</option>
    </select>
    </div>
    <div class='col-4 col-sm-4 col-md-4 col-lg-4'  style = 'float: left;'>
    <select name="data_ano<?php echo $nome_variavel;?>" id="data_ano<?php echo $nome_variavel;?>" class='form-control'  >
       <?php
		  echo "<option value='$ano1'>$ano1</option>";
		echo "<option value='$ano2'>$ano2</option>";
		
	  /*for($i=$ano1;$i>=1970;$i--)
		{
		echo "<option value='$i'>$i</option>";
		}*/

		?>
    </select>
    </div></div></div>

<?php
}

    



}


?>