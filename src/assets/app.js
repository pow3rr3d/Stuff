/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import 'bootstrap';
import $ from 'jquery';

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';

import SelectPure from "select-pure";

// start the Stimulus application
import './bootstrap';

//import modal search
import {open as search} from './Search';

search();


//A2lix
import a2lix_lib from '@a2lix/symfony-collection/src/a2lix_sf_collection';

a2lix_lib.sfCollection.init({
    collectionsSelector: 'form div[data-prototype]',
    manageRemoveEntry: true,
    lang: {
        add: 'Add',
        remove: 'Remove'
    }
})

//Console.log Style
console.log('%c Stuff V0.3', 'color:green; background: #222; font-size: 24px;')

//Select Multiple Chose.js
var input = document.querySelector(".chosen-select")
if (input !== null) {
    input.classList.add("hidden")

    var div = document.createElement("div");
    var options = Array.from(input.options)
    var selected = []
    var choices = []


    options.map(function (element) {
            choices.push(
                {
                    label: element.label,
                    value: element.value
                }
            )
        }
    )

    options.map(function (element) {
            if (element.selected === true) {
                selected.push(element.value)
            }
        }
    )

    div.classList.add("new-select")
    div.name = input.name
    input.parentNode.appendChild(div)

//
    var instance = new SelectPure(".new-select", {
        placeholder: false,
        options: choices,
        multiple: true,
        autocomplete: true,
        value: selected,
        icon: "fa fa-times", // uses Font Awesome
        inlineIcon: false, // custom cross icon for multiple select.
        classNames: {
            select: "select-pure__select",
            dropdownShown: "select-pure__select--opened",
            multiselect: "select-pure__select--multiple",
            label: "select-pure__label",
            placeholder: "select-pure__placeholder",
            dropdown: "select-pure__options",
            option: "select-pure__option",
            autocompleteInput: "select-pure__autocomplete",
            selectedLabel: "select-pure__selected-label",
            selectedOption: "select-pure__option--selected",
            placeholderHidden: "select-pure__placeholder--hidden",
            optionHidden: "select-pure__option--hidden",
        },
        onChange: function (el) {
            for (var i = 0; i < options.length; i++) {
                options[i].selected = false;
            }
            el.forEach(function (data) {
                options.find(c => c.value == data).selected = true
            })
        }
    })
}

