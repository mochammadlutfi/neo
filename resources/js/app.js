// import './bootstrap';
import 'laravel-datatables-vite';

import $ from 'jquery';
import axios from 'axios';
window.axios = axios;
// window.$ = window.jQuery = $;


window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
