/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
require('./styles/app.css');

import './styles/global.scss';

// start the Stimulus application
import './bootstrap';

import * as bootstrap from 'bootstrap'; // < 02-10-2021 pas de ./

import('../node_modules/bootstrap-icons/font/bootstrap-icons.css');

// 19-09-2021 : à mettre pour ne pas avoir bootstrap is not defined dans les javascript
// https://github.com/twbs/bootstrap/discussions/31239
window.bootstrap = require("bootstrap")





