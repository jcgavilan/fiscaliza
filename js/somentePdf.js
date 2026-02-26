function somentePdf(i) {
    document.getElementById('teste_' + i).style.backgroundColor="#1aac1a";
    document.getElementById('teste_' + i).style.color="#ffffff";
    document.getElementById('teste_' + i).style.padding="12px";

    var fileElement = document.getElementById("arquivo_" + i);
    var fileExtension = "";
    if (fileElement.value.lastIndexOf(".") > 0) {
        fileExtension = fileElement.value.substring(fileElement.value.lastIndexOf(".") + 1, fileElement.value.length);
    }
    if (fileExtension.toLowerCase() == "pdf") {
        return true;
    }
    else {
      //  alert("Somente arquivos em formato PDF podem ser carregados");
        document.getElementById("arquivo_" + i).value='';
        return false;
    }   
}

function show_justificativa(i) {
    document.getElementById('holder_' + i).style.backgroundColor="#c3f6c3";
    //document.getElementById('holder_' + i).style.color="#ffffff";
    //document.getElementById('holder_' + i).style.padding="12px";
    
    document.getElementById('hide_j_' + i).style.display="table";

    var fileElement = document.getElementById("arquivo_" + i);
    var fileExtension = "";
    if (fileElement.value.lastIndexOf(".") > 0) {
        fileExtension = fileElement.value.substring(fileElement.value.lastIndexOf(".") + 1, fileElement.value.length);
    }
    if (fileExtension.toLowerCase() == "pdf") {
        return true;
    }
    else {
        alert("Somente arquivos em formato PDF podem ser carregados");
        document.getElementById("arquivo_" + i).value='';
        return false;
    }   
   
}
