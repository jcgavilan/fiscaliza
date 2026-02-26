function Check_armanaoletal() {
    if (document.getElementById('arma_nao_letal_s').checked) {
        document.getElementById('arma_nao_letal_USO').style.display = 'block';
    } 
    else {
        document.getElementById('arma_nao_letal_USO').style.display = 'none';
   } 
}

function Check_forcafisica() {
    if (document.getElementById('forcafisica_s').checked) {
        document.getElementById('forca_fisica_USO').style.display = 'block';
    } 
    else {
        document.getElementById('forca_fisica_USO').style.display = 'none';
   } 
}

function Check_armaletal() {
    if (document.getElementById('armaletal_s').checked) {
        document.getElementById('armaletal_USO').style.display = 'block';
    } 
    else {
        document.getElementById('armaletal_USO').style.display = 'none';
   } 
}