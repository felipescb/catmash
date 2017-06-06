import Vue from 'vue';
import axios from 'axios';
import 'bootstrap-sass';
import $ from 'jquery';


axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');

Vue.prototype.axios = axios;
const app = new Vue({
    el: '#app',

    components: {
    }
});
