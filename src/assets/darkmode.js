const body = document.querySelector("body")
const btn = document.querySelector("#darkswitch")
const user = document.getElementById("userId").value;
var xhr = new XMLHttpRequest();

if(body.classList.contains("dark")){
  btn.checked = true
}
else{
  btn.checked = false
}

btn.addEventListener("click", function () {
  if (btn.checked === true) {

    xhr.open("POST", "/preferences/darkmode");
    xhr.responseType = 'text';
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({
      id: user,
      darkmode: true
    }));
    body.classList.add("dark")

  }
  else {
    xhr.open("POST", "/preferences/darkmode");
    xhr.responseType = 'text';
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({
      id: user,
      darkmode: false
    }));
    body.classList.remove("dark")
  }

})






