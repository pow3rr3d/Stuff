//SearchBar Modal display
import $ from 'jquery'

// vars
var a = document.querySelector("#helpers-btn")
var modal = document.querySelector("#modalHelpers")

export function helpers() {
    a.addEventListener("click", function (e) {
        $(modal).modal('show')
    })
}

