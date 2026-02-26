
document.addEventListener("DOMContentLoaded", function() { 
 document.getElementById("comboA").onchange = function(){
    var value = document.getElementById("comboA").value;
    var texto = '';
    if(value==1){ texto = '<b>Art. 121. Matar alguem:</b><br>Pena - reclusão, de seis a vinte anos.<br>Caso de diminuição de pena<br>§ 1º Se o agente comete o crime impelido por motivo de relevante valor social ou moral, ou sob o domínio de violenta emoção, logo em seguida a injusta provocação da vítima, o juiz pode reduzir a pena de um sexto a um terço.';}
    if(value==2){ texto = '<b>Homicídio qualificado</b><br>§ 2° Se o homicídio é cometido:<br>I - mediante paga ou promessa de recompensa, ou por outro motivo torpe;<br>II - por motivo futil;<br>III - com emprego de veneno, fogo, explosivo, asfixia, tortura ou outro meio insidioso ou cruel, ou de que possa resultar perigo comum;<br>IV - à traição, de emboscada, ou mediante dissimulação ou outro recurso que dificulte ou torne impossivel a defesa do ofendido;<br>V - para assegurar a execução, a ocultação, a impunidade ou vantagem de outro crime:<br>Pena - reclusão, de doze a trinta anos.';}
    if(value==3){ texto = '<b>Feminicídio</b><br> VI - contra a mulher por razões da condição de sexo feminino:<br>VII – contra autoridade ou agente descrito nos arts. 142 e 144 da Constituição Federal, integrantes do sistema prisional e da Força Nacional de Segurança Pública, no exercício da função ou em decorrência dela, ou contra seu cônjuge, companheiro ou parente consanguíneo até terceiro grau, em razão dessa condição:<br>Pena - reclusão, de doze a trinta anos.<br>§ 2o-A Considera-se que há razões de condição de sexo feminino quando o crime envolve:<br>I - violência doméstica e familiar;<br>II - menosprezo ou discriminação à condição de mulher.';}

    
   
    
    $("#texto_norma").html(texto);

 };
});
