
document.getElementById("expande_menu").style['display'] = "none";

function recolhe_menu(){
    //var a = document.getElementById("menu_principal");
    //a.style("margin-left")=-100px;
    document.getElementById("menu_principal").style['margin-left'] = "-210px";
    document.getElementById("div_total").style['margin-left'] = "-210px";
    document.getElementById("div_total_prev").style['width'] = "95%";  
    document.getElementById("recolhe_menu").style['display'] = "none";
    document.getElementById("expande_menu").style['display'] = "block";     
}

function expande_menu(){
    //var a = document.getElementById("menu_principal");
    //a.style("margin-left")=-100px;
    document.getElementById("menu_principal").style['margin-left'] = "0px";
    document.getElementById("div_total").style['margin-left'] = "0px";
    document.getElementById("div_total_prev").style['width'] = "";  
    document.getElementById("recolhe_menu").style['display'] = "block";
    document.getElementById("expande_menu").style['display'] = "none";     
}