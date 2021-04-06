/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('admin-lte');
require('./custom');
require('./webcam.min');

window.Vue = require('vue');

/**
 * Imports ng mga masasarap na pagkain... este... extensions pala
 */
import VueRouter from 'vue-router';
import Swal from 'sweetalert2';
import {Form, HasError, AlertError} from 'vform';
import moment from 'moment';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import VueTouchKeyboard from "vue-touch-keyboard"; //https://github.com/icebob/vue-touch-keyboard
import Chartkick from 'vue-chartkick';
import Chart from 'chart.js';
import VueHtmlToPaper from 'vue-html-to-paper';
import ToggleButton from 'vue-js-toggle-button';

/**
 * Vue uses and window firing of things
 */
Vue.use(VueRouter);
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(VueTouchKeyboard);
Vue.use(Chartkick.use(Chart));
Vue.use(ToggleButton);

window.Moment = require('moment');
window.Swal = Swal;
window.Form = Form;
window.Fire = new Vue();
window.Webcam = require('webcamjs');
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);

const options = {
    name: '_blank',
    specs: [
    ],
    styles: [
        'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
        '../sass/print.css'
    ]
  };

  Vue.use(VueHtmlToPaper, options);


/**
 * Moment Filters
 * It's your time to shine moment.js
 */
Vue.filter('standard_datetime', function(date) {
    return moment(date).format('llll');
});

Vue.filter('dateFormat', function(ldate) {
    return moment(ldate).format('LLL');
});

Vue.filter('dateOnly', function(date){
    return moment(date).format('LL');
});

Vue.filter('formatDate', function(value) {
    return moment(String(value)).format('MM/DD/YYYY hh:mm A')
});
 /**
  * This routes could actually be separated but since I'm a bit lazy and am rushing things,
  * I'll just put them here.
  */
let routes = [
    // Paths for APEX Routes
    {
        path: '/apex',
        component: require('./components/ApexComponent.vue').default
    },
    {
        path: '/pcc',
        component: require('./components/PccComponent.vue').default
    },
    {
        path: '/follow_up',
        component:require('./components/FollowUpComponent.vue').default
    },
    {
        path: '/SMSnotification',
        component:require('./components/SMSnotifications.vue').default
    },

    {
        path: '/im',
        component: require('./apex_components/InternalMedComponent.vue').default
    },
    {
        path: '/pedia',
        component: require('./apex_components/PediatricsComponent.vue').default
    },
    {
        path: '/obgyne',
        component: require('./apex_components/ObsteGyneComponent.vue').default
    },
    {
        path: '/surgery',
        component: require('./apex_components/SurgeryComponent.vue').default
    },
    {
        path: '/ophtha',
        component: require('./apex_components/OphthalmologyComponent.vue').default
    },
    {
        path: '/ortho',
        component: require('./apex_components/OrthopedicsComponent.vue').default
    },
    {
        path: '/ent',
        component: require('./apex_components/EntComponent.vue').default
    },
    {
        path: '/psych',
        component: require('./apex_components/PsychiatryComponent.vue').default
    },
    {
        path: '/cancer_center',
        component: require('./apex_components/CancerCenterComponent.vue').default
    },

    {
        path: '/others',
        component: require('./apex_components/OthersComponent.vue').default
    },
    // pcc
    {
        path: '/gen_clinic',
        component: require('./pcc_components/GeneralClinicsComponent.vue').default
    },
    {
        path: '/wellness',
        component: require('./pcc_components/WellnessComponent.vue').default
    },
    {
        path: '/screening',
        component:require('./pcc_components/ScreeningComponent.vue').default
    },
];

const router = new VueRouter({
    mode: 'history',
    routes
});

const app = new Vue({
    el: '#app',
    router
});

export const globalVariables = new Vue({
    data: {
        var_hpercode: '',
        var_priority: 0,
        webcam_in: 1
    }
    /**
     * If you want to use this variable throughout multiple components, just import it to each component
     * and call it via it's given name. I think I added the sample already to the different components.
     * This variable can be changed on a component by stating globalHpercode.var_hpercode = 'new value'.
     * Subsequently, I think it's possible to make it available to all componennts by doing Vue.prototype.$globals = globalHpercode.
     * Then you can access it like this.$globals, or $globals in templates but I did not try it yet.
     * I am super handsome.
     */
});
