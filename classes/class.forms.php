<?php


class Forms {
    
    function __construct(){} // para prevenir a duplicação do print

    function registra_log($id_procedimento, $usuario, $acao, $link){
        
        $data_agora=time();
        $query = "update tb_procedimentos set logs = CONCAT (logs, '&$data_agora;$usuario;$acao') where id_procedimento = ".$id_procedimento;
        $result = mysqli_query($link, $query);
        if(!$result){
            echo " <div class='alert alert-danger' role='alert'>";
            echo " <h4 class='alert-heading' align = 'center'>ATENÇÃO -ERRO NO CADASTRO DE LOG <br>$query<br> </h4>".mysqli_error();
            echo "</div>";
        }else{
           // echo "<hr>LOG OK<hr>";
        }

    }

    function query_insere($tb, $v){

        $k = array_keys($v);
        $str_keys = '';
        $str_values = '';
        
            for ($i=0; $i < count($k); $i++) { 
                if($i != 0)
                    {$str_keys .= ", ";}
                $str_keys .= $k[$i];
                $chave = $k[$i];
        
                if($i != 0)
                    {$str_values .= ", ";}
                $str_values .= "'".$v[$chave]."'";
            }
        
             $query = "insert into $tb ($str_keys) values ($str_values)";
             return $query;
        
        }

    function f_input_read($colunas, $titulo, $id, $retorno) {
        echo "<div class='col-xl-".$colunas." col-lg-".$colunas." col-md-".$colunas." col-sm-".$colunas." col-".$colunas."' style = 'float: left;'>";
        echo "<div class='form-group'>";
        echo "<label for='inputText3' class='col-form-label'>$titulo</label>";
        echo " <input id='$id' name='$id' type='text' class='form-control' value ='$retorno' readonly>";
        echo "</div></div>";  
        }

    function abre_form($target){
        echo "<form  enctype = 'multipart/form-data' action='".$target."' method='post'>";
    }

    function fecha_form(){
        echo "</form>";
    }
    
    function f_input($titulo, $id, $retorno) {
        ?>
         <div class="form-group">
            <label for="inputText3" class="col-form-label"><?php echo $titulo;?></label>
            <input id="<?php echo $id;?>" name="<?php echo $id;?>" type="text" class="form-control" value ="<?php echo $retorno;?>">
        </div>
        <?php 
    }

    
    function f_input_req($titulo, $id, $retorno) {
        ?>
         <div class="form-group">
            <label for="inputText3" class="col-form-label"><?php echo $titulo;?></label>
            <input id="<?php echo $id;?>" name="<?php echo $id;?>" type="text"  required="" class="form-control" value ="<?php echo $retorno;?>">
        </div>
        <?php 
    }
    
    function f_input_data($titulo, $id, $retorno) {
        ?>
         <div class="form-group">
            <label for="inputText3" class="col-form-label"><?php echo $titulo;?></label>
            <input  type="text" class="form-control date-inputmask" id="date-mask" value ="<?php echo $retorno;?>">
        </div>
        <?php 
    }
    
    function f_input_mask($titulo, $id, $mask) {
        ?>
         <div class="form-group" id = "inputmask">
            <label for="inputText3" class="col-form-label"><?php echo $titulo;?></label>
            <input  type="text"  name="<?php echo $id;?>" class="form-control <?php echo $mask;?>-inputmask" id="<?php echo $mask;?>-mask" >
        </div>
        <?php 
    }
    
    function f_input_mask_req($titulo, $id, $mask) {
        ?>
         <div class="form-group" id = "inputmask">
            <label for="inputText3" class="col-form-label"><?php echo $titulo;?></label>
            <input  type="text"  name="<?php echo $id;?>" class="form-control <?php echo $mask;?>-inputmask" required="" id="<?php echo $mask;?>-mask" >
        </div>
        <?php 
    }
    
    function f_input_retorno_mask($titulo, $id, $retorno, $mask) {
        ?>
         <div class="form-group" id = "inputmask">
            <label for="inputText3" class="col-form-label"><?php echo $titulo;?></label>
            <input  type="text"  name="<?php echo $id;?>" class="form-control <?php echo $mask;?>-inputmask" id="<?php echo $mask;?>-mask" value ="<?php echo $retorno;?>">
        </div>
        <?php 
    }
    
    function abre($numero) {
        echo "<div class='col-xl-".$numero." col-lg-".$numero." col-md-".$numero." col-sm-".$numero." col-".$numero."' style = 'float: left;'>";
    }

    function abre_duplo($numero) {
        echo "<div class='col-xl-".$numero." col-lg-".$numero." col-md-".$numero." col-sm-".$numero." col-".$numero."' style = 'float: left;'>";
        echo "<div class='col-xl-".$numero." col-lg-".$numero." col-md-".$numero." col-sm-".$numero." col-".$numero."' style = 'float: left;'>";
    }

    function abre_card($n){
        echo "<div class='col-md-".$n." grid-margin stretch-card'>";
        echo "<div class='card'>";
        echo "<div class='card-body'>";
    }

    function fecha_card(){
        echo "</div></div></div>";
    }
    
    function fecha() {
        echo "</div>";
    }

    function fecha_duplo() {
        echo "</div>";
        echo "</div>";
    }
    
   function f_select($titulo, $id, $mensagem, $municipios) {
       echo "<div class='form-group'>";
       echo "<label for='input-select'>".$titulo."</label>";
       echo "<select class='form-control' id='".$id."'>";
       echo "<option value=''>".$mensagem."</option>";
       $c = count($municipios);
       for ($i = 0; $i < $c; $i++) {
           echo "<option value='".$municipios[$i]."'>".$municipios[$i]."</option>";
       }
       echo "</select></div>";
   }
   
   function f_radio_simnao($titulo, $id) {
       ?>
         <div class="form-group">
            <label for="inputText3" class="col-form-label"><?php echo $titulo;?></label><br>
             <label class="custom-control custom-radio custom-control-inline">
                <input type="radio" name="<?php echo $id;?>" class="custom-control-input" value="1"><span class="custom-control-label">Sim</span>
            </label>
            <label class="custom-control custom-radio custom-control-inline">
                <input type="radio" name="<?php echo $id;?>" class="custom-control-input" value="0"><span class="custom-control-label">Não</span>
            </label>
        </div>
        <?php 
   }
   
   function celula_etapa($num, $pronto) {
       if ($pronto=='concluido') {
           $cor = "#009900";
       }
       if($pronto=='aberto'){
           $cor = "#808080";
       }
       if($pronto=="atual"){
       $cor = "#B8860B";
       }
       echo "<div class='col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3' style = 'background-color: $cor; padding: 4px; text-align:center; color: #ffffff; float: left; border-left: 1px solid #ffffff;'>ETAPA ";
       if ($_SESSION['seletor_responsivo'] == "mobile") {echo "<br>";}
       echo "$num</div>";
   }



   
   function f_radio_simnao_edit($titulo, $id, $opcao) {
       ?>
         <div class="form-group">
            <label for="inputText3" class="col-form-label"><?php echo $titulo;?></label><br>
             <label class="custom-control custom-radio custom-control-inline">
                <input type="radio" name="<?php echo $id;?>" class="custom-control-input" value="1" <?php if($opcao==1){echo "checked";}?>><span class="custom-control-label">Sim</span>
            </label>
            <label class="custom-control custom-radio custom-control-inline">
                <input type="radio" name="<?php echo $id;?>" class="custom-control-input" value="0" <?php if($opcao==0){echo "checked";}?>><span class="custom-control-label">Não</span>
            </label>
        </div>
        <?php 
   }
   
   function f_radio_requisitos($titulo, $id, $opcao) {
       ?>
         <div class="form-group">
            <label for="inputText3" class="col-form-label"><?php echo $titulo;?></label><br>
             <label class="custom-control custom-radio custom-control-inline">
                <input type="radio" name="<?php echo $id;?>" class="custom-control-input" value="1" <?php if($opcao==1){echo "checked";}?>><span class="custom-control-label">Sim</span>
            </label>
            <label class="custom-control custom-radio custom-control-inline">
                <input type="radio" name="<?php echo $id;?>" class="custom-control-input" value="0" <?php if($opcao==0){echo "checked";}?>><span class="custom-control-label">Não</span>
            </label>
             <label class="custom-control custom-radio custom-control-inline">
                <input type="radio" name="<?php echo $id;?>" class="custom-control-input" value="2" <?php if($opcao==2){echo "checked";}?>><span class="custom-control-label">Não se aplica</span>
            </label>
        </div>
        <?php 
   }
   
   function f_radio_unico($label, $id) {
       ?>
             <label class="custom-control custom-radio custom-control-inline">
                <input type="radio" name="<?php echo $id;?>" class="custom-control-input" value="<?php echo $label;?>"><span class="custom-control-label"><?php echo $label;?></span>
            </label>
       
        <?php 
   }
   
   function f_button($label) {
      ?> 
       <button type="submit" name="Submit" class="btn btn-primary" style = "background-color: #B8860B; margin-left: 0px; margin-top: 12px; border: 0px solid #EEE8AA;"><?php echo $label;?></button>
      <?php
   }

   function f_checkbox_check($label, $id) {
    ?> 
        <label class="custom-control custom-checkbox">
            <input type="checkbox"  name="<?php echo $id;?>" checked="" class="custom-control-input"><span class="custom-control-label"><?php echo $label;?></span>
        </label>
    <?php
    }

    function f_checkbox($label, $id) {
        ?> 
            <div style = 'padding-top: 36px;'>
                <label class="custom-control custom-checkbox" >
                    <input type="checkbox" name="<?php echo $id;?>" class="custom-control-input" ><span class="custom-control-label"><?php echo $label;?></span>
                </label>
            </div>
        <?php
        }
    
   
   function f_text($titulo, $id, $retorno) {
       ?>
         <div class="form-group">
            <label for="exampleFormControlTextarea1"><?php echo $titulo;?></label>
            <textarea class="form-control" id="<?php echo $id;?>"  name="<?php echo $id;?>"rows="3"><?php echo $retorno;?></textarea>
        </div>
        <?php 
    }
   
    function relatorio_card($dados_card) {
        ?>
       
     	<div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6" style="float:left;">
            <div class="card" style = "align:center; text-align: center;">
                <div class="card-body" style = "float:center">
                
    
                    <div class="d-inline-block" style = "text-align:center; padding-left: 9px; align:center;">
                   <h1> <i class='<?php echo $dados_card[icone];?>'></i></h1>
                        <h5 class="text-muted"><?php echo $dados_card[titulo];?></h5>
                        <h2 class="mb-0"><?php echo $dados_card[total];?></h2>
                        <?php if (condition) {
                           ?>
                            <a href = "<?php echo $dados_card[conteudo_link];?>"> <i class='fas fa-search'> ver detalhes </i></a>
                           <?php 
                        }?>
                       
                    </div>
                    
                </div>
            </div>
        </div>
       
        <?php
    }
    

    function form_procedimento(){
        include "mysql.conecta.rep.php";
        echo '<div class="row"><div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6"  >';
        echo '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"  style="float:left;">'; // só para ajustar o alinhamento dos campos
        echo "<div class='form-group'>";
        echo "<label for='inputText3' class='col-form-label'><br>Selecione o tipo de procedimento</label><br>";
        echo "<select class='form-control' id='procedimento_sigla' required='' name='procedimento_sigla'>";
        echo "<option value=''></option>";
    
        $query = "select sigla, nome from tb_procedimentos_tipo order by nome asc";
        $result = mysqli_query($link, $query);
        $num = mysqli_num_rows($result);
        for($i=0;$i<$num;$i++)
        {
            $row = mysqli_fetch_array($result);
            $sigla = $row[sigla];
            $nome = stripslashes(($row[nome]));
            echo "<option value='".$sigla."'>".$nome."</option>";
        }
    
        echo "</select></div>";
    
        echo "</div></div></div>";
    
        echo '<div class="row"><div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6"  >';
    
        ?>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4"  style="float:left;">
                <div class="form-group">
    
                <?php
                
                if($_GET[minha_unidade] == 1){
                    echo '<label for="inputText3" class="col-form-label">Unidade</label>';
                    echo '<input id="unidade" name="unidade" type="text"  required="" class="form-control" value ="'.$_SESSION['unidade'].'">';
                    
                
                }else{
                    ?>
                    <label for="inputText3" class="col-form-label">Unidade</label>
                    <input id="unidade" name="unidade" type="text"  required="" class="form-control" value ="">
                    <?php
                
                }
                
                ?>
                    
                </div>
            </div>

            <?php
            $a = time();
            $d = getdate($a);
            $ano = $d['year'];
            ?>
        
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4" style="float:left;" >
                <div class="form-group">
                    <label for="inputText3" class="col-form-label">Ano</label>
                    <input id="ano" name="ano" type="text"  required="" class="form-control" value ="<?php echo $ano;?>">
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4"  style="float:left;">
                <div class="form-group">
                    <label for="inputText3" class="col-form-label">Nº do Procedimento</label>
                    <input id="n_procedimento" name="n_procedimento" type="text"  required="" class="form-control" value ="">
                </div>
            </div>
    
        <?php
    
        echo "</div></div>";


    }


    function form_procedimento_corregedoria($cadastro){
        include "mysql.conecta.rep.php";

        if ($cadastro == "sim") {
            $campo_requerido =  "required=''";
        }else{
            $campo_requerido = "";
        }

        echo '<div class="row"><div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"  >';
        echo '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"  style="float:left;">'; // só para ajustar o alinhamento dos campos
        echo "<div class='form-group'>";
        echo "<label for='inputText3' class='col-form-label'><br>Selecione o tipo de procedimento</label><br>";
        echo "<select class='form-control' id='procedimento_sigla' $campo_requerido name='procedimento_sigla'>";
        echo "<option value=''></option>";
    
        $query = "select sigla, nome from tb_procedimentos_tipo order by nome asc";
        $result = mysqli_query($link, $query);
        $num = mysqli_num_rows($result);
        for($i=0;$i<$num;$i++)
        {
            $row = mysqli_fetch_array($result);
            $sigla = $row[sigla];
            $nome = stripslashes(($row[nome]));
            echo "<option value='".$sigla."'>$sigla - $nome</option>";
        }
    
        echo "</select></div>";
    
        echo "</div></div></div>";
    
        echo '<div class="row"><div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"  >';
    
        ?>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6"  style="float:left;">
               <?php 
               /*echo "<div class='form-group'>";
               echo "<label for='inputText3' class='col-form-label'>Indique a Unidade</label><br>";
               echo "<select class='form-control' id='id_unidade' $campo_requerido name='id_unidade'>";
               echo "<option value=''></option>";
           
               $query = "select * from tb_unidades_corregedoria where id = ".$_SESSION['unidade']; //."//order by nome asc";
               $result = mysqli_query($link, $query);
               $num = mysqli_num_rows($result);
               for($i=0;$i<$num;$i++)
               {
                   $row = mysqli_fetch_array($result);
                   $id = $row[id];
                   $nome = stripslashes(($row[nome]));
                   echo "<option value='".$id."'>".$nome."</option>";
               }
           
               echo "</select></div>";*/

               $query = "select * from tb_unidades_corregedoria where id = ".$_SESSION['unidade']; //."//order by nome asc";
               $result = mysqli_query($link, $query);
               $row = mysqli_fetch_array($result);
                $nome = stripslashes(($row[nome]));
                                 
                ?>
                <div class="form-group">
                <label for="inputText3" class="col-form-label">Unidade</label>
                <input id="" name="" type="text" class="form-control" value ="<?php echo $nome;?>" readonly>
                </div>
                    
                
            </div>

            <?php
            $a = time();
            $d = getdate($a);
            $ano = $d['year'];
            ?>
        
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3" style="float:left;" >
                <div class="form-group">
                    <label for="inputText3" class="col-form-label">Ano</label>
                    <input id="ano" name="ano" type="text"  $campo_requerido class="form-control" value ="<?php echo $ano;?>" >
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3"  style="float:left;">
                <div class="form-group">
                    <label for="inputText3" class="col-form-label">Nº do Procedimento</label>
                    <!--
                    <input id="n_procedimento" name="n_procedimento" type="text"  $campo_requerido class="form-control" value =""> -->
                    Será preenchido automaticamente
                </div>
            </div>
    
        <?php
    
        echo "</div></div>";


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


    function registra_ultimo_movimento($id_procedimento, $descritivo, $link){

        $data_atual = time();

        $query = "update tb_procedimentos set data_ultimo_movimento = $data_atual, ultimo_movimento = '$descritivo' where id_procedimento = ".$id_procedimento;
        $result = mysqli_query($link, $query);
        if(!$result)
        {   
            echo " <div class='alert alert-danger' role='alert'>";
            echo " <h4 class='alert-heading' align = 'center'>ATENÇÃO -ERRO NO CADASTRO DO ÚLTIMO MOVIMENTO<br>$query<br> </h4>";
            echo "</div>";
        }

    }

    
    
    function insere_data($nome_variavel)
    {
        $data_get = getdate();
        $ano1 = $data_get[year];
        $ano2 = $ano1-1;
        
        ?>
  <p>
    <select name="data_dia<?php echo $nome_variavel;?>" id="data_dia<?php echo $nome_variavel;?>" style = "padding: 6px; ">
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
    de
    <select name="data_mes<?php echo $nome_variavel;?>" id="data_mes<?php echo $nome_variavel;?>" style = "padding: 6px; ">
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
    de
    <select name="data_ano<?php echo $nome_variavel;?>" id="data_ano<?php echo $nome_variavel;?>" style = "padding: 6px; ">
       <?php
		  echo "<option value='$ano1'>$ano1</option>";
		echo "<option value='$ano2'>$ano2</option>";
		
	  /*for($i=$ano1;$i>=1970;$i--)
		{
		echo "<option value='$i'>$i</option>";
		}*/

		?>
    </select>
  </p>

<?php
}
    
function insere_data_boots($mensagem, $nome_variavel)
{
    $data_get = getdate();
    $ano1 = $data_get[year];
    $ano2 = $ano1-1;
    
    echo "<div class='form-group'>";
    echo "<label for='inputText3' class='col-form-label'><h3>$mensagem</h3></label><br>";
    echo "<div class='col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4' style = 'float: left'>";
    echo "<select class='form-control' id='data_dia".$nome_variavel."' name='data_dia".$nome_variavel."' style = 'padding: 6px;'>";
    
    
    ?>
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
     <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4" style = 'float: left'><select class='form-control' name="data_mes<?php echo $nome_variavel;?>" id="data_mes<?php echo $nome_variavel;?>" style = "padding: 6px; ">
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
     </div><div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4" style = 'float: left'>
    <select class='form-control' name="data_ano<?php echo $nome_variavel;?>" id="data_ano<?php echo $nome_variavel;?>" style = "padding: 6px; ">
       <?php
		  echo "<option value='$ano1'>$ano1</option>";
		echo "<option value='$ano2'>$ano2</option>";
		
	  /*for($i=$ano1;$i>=1970;$i--)
		{
		echo "<option value='$i'>$i</option>";
		}*/

		?>
    </select>
  </div>

<?php
}
    
    
    
    
}



?>