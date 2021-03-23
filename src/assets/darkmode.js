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

  // const body = document.querySelector("body")
  // var xhr = new XMLHttpRequest();

  // xhr.onreadystatechange = function() {
  //     if (xhr.readyState === 4) {
  //       if(xhr.response == true){
  //           body.classList.add("dark")
  //       }
  //       else{
  //         body.classList.remove("dark")
  //       }
  //     }
  //   }

  // xhr.open('GET', "/user/darkmode", true);
  // xhr.send('');







