//SearchBar Modal display
import $ from 'jquery'

export function open() {
    let modal = document.querySelector("#modalSearch")

    document.addEventListener("keydown", function (e) {
        if (e.ctrlKey && e.shiftKey) {
            function modalDisplay() {
                $(modal).modal('show')
            }

            modalDisplay()
        }
    })
}

//Research

var xhr = new XMLHttpRequest()
var input = document.querySelector("#searchInput")
var div = document.querySelector("#searchResult")

input.addEventListener("change", function (event) {
    div.innerHTML = "                        " +
        "<h3 id=\"userTitle\" class=\"hidden\">User</h3>\n" +
        "                        <ul id=\"user\">\n" +
        "\n" +
        "                        </ul>\n" +
        "                        <h3 id=\"productTitle\" class=\"hidden\">Product</h3>\n" +
        "                        <ul id=\"product\">\n" +
        "\n" +
        "                        </ul>\n" +
        "                        <h3 id=\"categoryTitle\" class=\"hidden\">Category</h3>\n" +
        "                        <ul id=\"category\">\n" +
        "\n" +
        "                        </ul>"
    div.classList.add("hidden")
    document.getElementById("user").classList.add("hidden")
    document.getElementById("user").innerHTML = ''
    document.querySelector("#userTitle").classList.add("hidden")
    document.getElementById("product").innerHTML = ''
    document.getElementById("product").classList.add("hidden")
    document.querySelector("#productTitle").classList.add("hidden")
    document.getElementById("category").innerHTML = ''
    document.getElementById("category").classList.add("hidden")
    document.querySelector("#categoryTitle").classList.add("hidden")
    xhr.open("POST", "/search/index")
    xhr.responseType = 'text'
    xhr.setRequestHeader('Content-Type', 'application/json')
    xhr.send(JSON.stringify({
        search: event.target.value
    }))
    xhr.onreadystatechange = function () {
        if (xhr.readyState == XMLHttpRequest.DONE) {
            var results = $.parseJSON(xhr.responseText)
            if (typeof results !== "string") {
                div.classList.remove("hidden")
                for (var items in results) {
                    if (results[items].user) {
                        document.querySelector("#userTitle").classList.remove("hidden")
                        document.getElementById("user").classList.remove("hidden")
                        var x = document.createElement("li")
                        var t = document.createTextNode("[" + results[items].user.id + "]" + " " + results[items].user.name + " " + results[items].user.surname)
                        var a = document.createElement("a")
                        if (results[0].role.value[0] === "ROLE_ADMIN"){
                            a.setAttribute('href',  "/user/"+results[items].user.id)

                        }
                        else{
                            a.setAttribute('href',  "/account/"+results[items].user.id)
                        }
                        a.appendChild(x)
                        x.appendChild(t)
                        document.getElementById("user").appendChild(a)
                    }
                    if (results[items].product) {
                        document.querySelector("#productTitle").classList.remove("hidden")
                        document.getElementById("product").classList.remove("hidden")
                        var a = document.createElement("a")
                        a.setAttribute('href',  "/stuff/"+results[items].product.id)
                        var x = document.createElement("li")
                        var t = document.createTextNode("[" + results[items].product.id + "]" + " " + results[items].product.name + " - " + results[items].product.description)
                        a.appendChild(x)
                        x.appendChild(t)
                        document.getElementById("product").appendChild(a)
                    }
                    if (results[items].category) {
                        document.querySelector("#categoryTitle").classList.remove("hidden")
                        document.getElementById("category").classList.remove("hidden")
                        var x = document.createElement("li")
                        var t = document.createTextNode("[" + results[items].category.id + "]" + " " + results[items].category.name)
                        var a = document.createElement("a")
                        if (results[0].role.value[0] === "ROLE_ADMIN"){
                            a.setAttribute('href',  "/category/"+results[items].category.id)
                            a.appendChild(x)
                            x.appendChild(t)
                            document.getElementById("category").appendChild(a)
                        }
                        else{
                            x.appendChild(t)
                            document.getElementById("category").appendChild(t)
                        }

                    }
                }
            } else {
                div.classList.remove("hidden")
                div.innerHTML = results
            }
        }
    }
})
