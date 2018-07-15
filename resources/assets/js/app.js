import axios from 'axios';
import Vue from 'vue';
import Feed from './components/Feed';

// Setup axios
window.Axios = axios;
window.Axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// CSRF-tokens
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.Axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// Initialize Vue
window.Vue = Vue;
Vue.component('feed', Feed);
const app = new Vue({el: '#app'});
