function openTab(tabName) {
    var i, x;
    x = document.getElementsByClassName("tab");
    for (i = 0; i < x.length; i++) {
      x[i].style.display = "none";
    }
    document.getElementById(tabName).style.display = "block";
  } //na razie nic nie używa tej funkcji, ale może będzie, więc na razie nie usuwam

var modalPrzelew = document.getElementById('Przelew');
var modalKredyt = document.getElementById('Kredyt');
var modalRegister = document.getElementById('Register');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modalKredyt) {modalKredyt.style.display = "none";}
    if (event.target == modalPrzelew) { modalPrzelew.style.display = "none";}
    if (event.target == modalRegister) { modalRegister.style.display = "none";}
}