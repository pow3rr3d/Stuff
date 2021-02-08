//Automatique login

var admin = document.querySelector(".admin")
var adminLogin = admin.querySelector(".login").innerHTML
var adminPassword = admin.querySelector(".mdp").innerHTML.trim()

var user = document.querySelector(".user")
var userLogin = user.querySelector(".login").innerHTML
var userPassword = user.querySelector(".mdp").innerHTML.trim()

admin.addEventListener("click", function(){
    document.querySelector("#inputEmail").value = adminLogin
    document.querySelector("#inputPassword").value = adminPassword
});

user.addEventListener("click", function(){
    document.querySelector("#inputEmail").value = userLogin
    document.querySelector("#inputPassword").value = userPassword
});




