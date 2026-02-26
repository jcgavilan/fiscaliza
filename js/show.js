
showPdf = function(path){
    document.getElementById("show").innerHTML = "<object data='" + path + "' type='application/pdf' style = 'width:100%; height:680px;'><embed src='" + path + "' type='application/pdf' /></object>"; 
}
showVideo = function(path){
    document.getElementById("show").innerHTML = "<div style = 'background-color: #666666; width:100%; min-height:680px; padding: 30px; text-align:center;'><p><video width='600' height='480' controls><source src='" + path + "' type='video/mp4'></video></div></p>"; 
}


function countChar(val, id_div, numero) {
    var len = val.value.length;
    if (len >= numero) {
      val.value = val.value.substring(0, numero);
    } else {
      $('#charNum' + id_div).text(numero - len);
    }
  };

function valueChanged()
    {
        if($('.coupon_question').is(":checked"))   
            $(".answer").show(600);
        else
            $(".answer").hide(600);
    }


    $(document).ready(function(){
        $('input[type="radio"]').click(function(){
            var inputValue = $(this).attr("value");
            var targetBox = $("." + inputValue);
            $(".box").not(targetBox).hide(600);
            $(targetBox).show(600);
        });
    });


    function hide_itens(numero_div)
    {
        if($('#desabilita_' + numero_div).is(":checked"))   
            $(".ativado_" + numero_div).hide();
        else
            $(".ativado_" + numero_div).show();
    }


    function hideMunicipio()
    {
        var select = document.getElementById('uf');
        var sigla_estado = select.options[select.selectedIndex].value;

        document.getElementById('estado_AC').style.display = "none";
        document.getElementById('estado_AL').style.display = "none";
        document.getElementById('estado_AM').style.display = "none";
        document.getElementById('estado_AP').style.display = "none";
        document.getElementById('estado_BA').style.display = "none";
        document.getElementById('estado_CE').style.display = "none";
        document.getElementById('estado_DF').style.display = "none";
        document.getElementById('estado_ES').style.display = "none";
        document.getElementById('estado_GO').style.display = "none";
        document.getElementById('estado_MA').style.display = "none";
        document.getElementById('estado_MG').style.display = "none";
        document.getElementById('estado_MS').style.display = "none";
        document.getElementById('estado_MT').style.display = "none";
        document.getElementById('estado_PA').style.display = "none";
        document.getElementById('estado_PB').style.display = "none";
        document.getElementById('estado_PE').style.display = "none";
        document.getElementById('estado_PI').style.display = "none";
        document.getElementById('estado_PR').style.display = "none";
        document.getElementById('estado_RJ').style.display = "none";
        document.getElementById('estado_RN').style.display = "none";
        document.getElementById('estado_RO').style.display = "none";
        document.getElementById('estado_RR').style.display = "none";
        document.getElementById('estado_RS').style.display = "none";
        document.getElementById('estado_SC').style.display = "none";
        document.getElementById('estado_SE').style.display = "none";
        document.getElementById('estado_SP').style.display = "none";
        document.getElementById('estado_TO').style.display = "none";
                /*$("#estado_1").hide();
        $("#estado_2").hide();
        $("#estado_3").hide();*/
        
        //$("#" + sigla_estado).show();
        document.getElementById(sigla_estado).style.display = "block";
    }
/*
showImagem = function(path){
    document.getElementById("show").innerHTML = "<div style = 'background-color: #666666; width:100%; min-height:680px; padding: 30px; text-align:center;'><p><img src='" + path + "' ></div></p>"; 
}

showAudio = function(path){
    var audio = new Audio(path);
    audio.play();
}


function form_busca_mulher()
{
    if($('.tipo_pesquisa').is(":checked")) {  
      $(".bloco_pessoal").hide(600);
      $(".bloco_pessoal").hide(600);
      $("#nome").prop('disabled', true);
      $("#uf").prop('disabled', true);
    }else{
      $("#nome").prop('disabled', false);
      $("#uf").prop('disabled', false);
      $(".bloco_pessoal").show(600);
    }
};
*/

function showListaAlvara()
{
    document.getElementById("div_alvara").style.display = "block";
    document.getElementById("div_licenca").style.display = "none";
    document.getElementById("div_carteira").style.display = "none";
    document.getElementById("id_alvara").required = true;
    document.getElementById("id_licenca").required = false;
    document.getElementById("id_carteira").required = false;
};


function showListaLicenca()
{
    document.getElementById("div_alvara").style.display = "none";
    document.getElementById("div_licenca").style.display = "block";
    document.getElementById("div_carteira").style.display = "none";
    document.getElementById("id_alvara").required = false;
    document.getElementById("id_licenca").required = true;
    document.getElementById("id_carteira").required = false;
};

function showListaCarteira()
{
    document.getElementById("div_alvara").style.display = "none";
    document.getElementById("div_licenca").style.display = "none";
    document.getElementById("div_carteira").style.display = "block";
    document.getElementById("id_alvara").required = false;
    document.getElementById("id_licenca").required = false;
    document.getElementById("id_carteira").required = true;
};

function showFileRequisitos(id)
{
    var i = 0;
     for (i = 0; i < 21; i++) {
        
         if (i != id) {
           $("#showFileRequisitos_" + i).hide();
         }
     }
    
    //document.getElementById("showFileRequisitos_" + id).style.display = "block";
    $("#showFileRequisitos_" + id).show();
    //alert("texto4 + " + id);
};

function form_busca_mulher()
{

    var valor = $("input[name='tipo_pesquisa']:checked").val();

 
        if (valor == "nome") {
            $("#bloco_pesquisa_nome").show();
            $("#bloco_pesquisa_cpf").hide();
            $("#q_nome").prop('disabled', false);
            $("#q_cpf").prop('disabled', true);
        }
        if (valor == "cpf") {
            $("#bloco_pesquisa_nome").hide();
            $("#bloco_pesquisa_cpf").show();
            $("#q_cpf").prop('disabled', false);
            $("#q_nome").prop('disabled', true);
        }
};


function show_tr_nao(i){
    document.getElementById('tr_' + i).style.display ='none';
  }
function show_tr_sim(i){
document.getElementById('tr_' + i).style.display = 'block';
}

function show_tr_delegado_nao(i){
    document.getElementById('tr_' + i).style.display ='none';
    document.getElementById('radio_hide_' + i).style.display = 'none';
  }

function show_tr_delegado_sim(i){
    document.getElementById('tr_' + i).style.display = 'block';
    document.getElementById('radio_hide_' + i).style.display = 'block';
    }

function show_botao()
{
    if($('#show_botao_enviar').is(":checked"))   
    document.getElementById('botao_enviar').style.display ='block';

}

function show_botao2()
{
    if($('#show_botao_enviar2').is(":checked"))   
    document.getElementById('botao_enviar').style.display ='block';
  
}


function botao_dispensa_on()
{
    //if($('#bt_dispensa_on').is(":checked"))  
   // document.getElementById("div_dispensa").style.display = "block";
    $('#div_dispensa').show(600);
};


function botao_dispensa_off()
{
   // if($('#bt_dispensa_off').is(":checked"))  
  //  document.getElementById("div_dispensa").style.display = "none";
    $('#div_dispensa').hide(600);

};

function f_pesquisa_aberta()
{
    if($('#radio_pesquisa_aberta').is(":checked"))   
    document.getElementById('div_pesquisa_geral').style.display ='block';
    document.getElementById('div_pesquisa_nome').style.display ='none';
    document.getElementById('div_pesquisa_cpf').style.display ='none';
    document.getElementById('div_pesquisa_cnpj').style.display ='none';  
}

function f_pesquisa_nome()
{
    if($('#radio_pesquisa_nome').is(":checked"))   
    document.getElementById('div_pesquisa_geral').style.display ='none';
    document.getElementById('div_pesquisa_nome').style.display ='block';
    document.getElementById('div_pesquisa_cpf').style.display ='none';
    document.getElementById('div_pesquisa_cnpj').style.display ='none';  
}

function f_pesquisa_cpf()
{
    if($('#radio_pesquisa_cpf').is(":checked"))   
    document.getElementById('div_pesquisa_geral').style.display ='none';
    document.getElementById('div_pesquisa_nome').style.display ='none';
    document.getElementById('div_pesquisa_cpf').style.display ='block';
    document.getElementById('div_pesquisa_cnpj').style.display ='none';  
}

function f_pesquisa_cnpj()
{
    if($('#radio_pesquisa_cnpj').is(":checked"))   
    document.getElementById('div_pesquisa_geral').style.display ='none';
    document.getElementById('div_pesquisa_nome').style.display ='none';
    document.getElementById('div_pesquisa_cpf').style.display ='none';
    document.getElementById('div_pesquisa_cnpj').style.display ='block';  
}


function show_carteira_aprova()
{
    if($('#show_div_carteira_aprova').is(":checked")){   

        document.getElementById('div_carteira_aprova').style.display ='block';
        document.getElementById('div_carteira_rejeita').style.display ='none';

    }
}


function show_carteira_rejeita()
{
    if($('#show_div_carteira_rejeita').is(":checked")){   

    document.getElementById('div_carteira_rejeita').style.display ='block';
    document.getElementById('div_carteira_aprova').style.display ='none';
    }
}


function pesquisa_pessoa_cnpj_funcao()
{
    if($('#pesquisa_pessoa_cnpj_radio').is(":checked"))   
    document.getElementById('pesquisa_pessoa_cnpj_div').style.display ='block';
    document.getElementById('pesquisa_pessoa_nome_div').style.display ='none';
    document.getElementById('pesquisa_pessoa_id_div').style.display ='none';
}

function pesquisa_pessoa_nome_funcao()
{
  if($('#pesquisa_pessoa_nome_radio').is(":checked")) 
  document.getElementById('pesquisa_pessoa_nome_div').style.display ='block';
  document.getElementById('pesquisa_pessoa_cnpj_div').style.display ='none';  
  document.getElementById('pesquisa_pessoa_id_div').style.display ='none';
}

function pesquisa_pessoa_id_funcao()
{
  if($('#pesquisa_pessoa_id_radio').is(":checked")) 
  document.getElementById('pesquisa_pessoa_id_div').style.display ='block';
  document.getElementById('pesquisa_pessoa_cnpj_div').style.display ='none';  
  document.getElementById('pesquisa_pessoa_nome_div').style.display ='none';
}