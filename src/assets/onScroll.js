var body = document.querySelector('html');
var button = document.getElementById("return-to-top");

window.onscroll = function(){
    var y = body.scrollTop;
    if(y > 0)
    {
        button.classList.remove("hidden");

    }
    else{
        button.classList.add("hidden");
    }

};

var toTop = function () {
    window.scrollTo({top: 0, behavior: 'smooth'});
};

button.addEventListener("click", toTop);