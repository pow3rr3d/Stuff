/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import 'bootstrap';

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

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

console.log('%c Stuff V0.2', 'color:green; background: #222; font-size: 24px;')
