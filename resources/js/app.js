import './bootstrap';

import mask from '@alpinejs/mask'
 
import Alpine from 'alpinejs';


import 'bootstrap'; //******
import 'bootstrap/dist/css/bootstrap.min.css'; //******


window.Alpine = Alpine;

Alpine.plugin(mask)

Alpine.start();
