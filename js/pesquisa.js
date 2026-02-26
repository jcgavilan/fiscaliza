

function apresenta_inclusao(id_elemento){
   
    $(".apresenta_inclusao_c").show();
    $(".apresenta_formulario_c").hide();

    $("#div_gatilho_" + id_elemento).hide();
    $("#div_formulario_" + id_elemento).show(600);
}

function mostra_icones(){
    $("#icones_adiconar").show(300);
    $("#h1_expande").hide();
    $("#h1_recolhe").show();
}

function guarda_icones(){
    $("#icones_adiconar").hide(300);
    $("#h1_expande").show();
    $("#h1_recolhe").hide();
}






