import Cookies from 'js-cookie'
import $ from 'jquery'

const body = document.querySelector("body")
var xhr = new XMLHttpRequest();

xhr.onreadystatechange = function() {
    if (xhr.readyState === 4) {
      if(xhr.response == true){
          body.classList.add("dark")
      }
      else{
        body.classList.remove("dark")
      }
    }
  }

xhr.open('GET', "/user/darkmode", true);
xhr.send('');



