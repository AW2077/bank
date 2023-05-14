var modalPrzelew = document.getElementById('Przelew');
var modalKredyt = document.getElementById('Kredyt');
var modalRegister = document.getElementById('Register');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modalKredyt) {modalKredyt.style.display = "none";}
    if (event.target == modalPrzelew) { modalPrzelew.style.display = "none";}
    if (event.target == modalRegister) { modalRegister.style.display = "none";}
}