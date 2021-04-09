//SearchBar Modal display
import $ from 'jquery';

export function open() {
    let modal = document.querySelector("#modalSearch")

    document.addEventListener("keydown", function (e) {
        if (e.ctrlKey && e.shiftKey) {
            function modalDisplay() {
                $(modal).modal('show')
            }
            modalDisplay()
        }
    });
}

//Research

var xhr = new XMLHttpRequest();
var input = document.querySelector("#searchInput");

input.addEventListener("change", function (event){
    xhr.open("POST", "/search/index");
    xhr.responseType = 'text';
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify({
        search: event.target.value
    }));
    xhr.onreadystatechange = function() {
        if (xhr.readyState == XMLHttpRequest.DONE) {
            var foo = $.parseJSON(xhr.responseText);
            console.log(foo)
            input.value = ""
        }
    }
})
