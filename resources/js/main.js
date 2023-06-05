// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue';
// import BootstrapVue from 'bootstrap-vue';
// import VueTouch from 'vue-touch';
// import {
//     ClientTable
// } from 'vue-tables-2';
// import VueTextareaAutosize from 'vue-textarea-autosize';
// import {
//     VueMaskDirective
// } from 'v-mask';
import VeeValidate from 'vee-validate';
// import VueFormWizard from 'vue-form-wizard';
import axios from 'axios';
import Toasted from 'vue-toasted';
// import VCalendar from 'v-calendar';
// import VueApexCharts from 'vue-apexcharts';
// import CKEditor from '@ckeditor/ckeditor5-vue';
// import bFormSlider from 'vue-bootstrap-slider';
// import {
//     component as VueCodeHighlight
// } from 'vue-code-highlight';
import helpers from './helpers/helper';

import store from './store';
import router from './Routes';
import App from './App';
import helper from './helpers/helper';
import layoutMixin from './mixins/layout';
import permissions from './mixins/permissions';
import {
    AuthMixin
} from './mixins/auth';
import config from './config';
import Widget from './components/Widget/Widget';
import moment from 'moment-timezone';
import VueLodash from 'vue-lodash';

import filters from './filters';

import IdleVue from 'idle-vue'
// import Pusher from "pusher-js"

// import Clipboard from 'v-clipboard'
// Vue.use(Clipboard)

axios.defaults.baseURL = config.baseURLApi;
// axios.defaults.headers.common['Access-Control-Allow-Origin'] = "*";
// axios.defaults.headers.common['Access-Control-Allow-Credentials'] = true;
axios.defaults.headers.common['Content-Type'] = "application/json";
// axios.defaults.headers.common['X-Requested-With'] = "XMLHttpRequest";

const token = localStorage.getItem('token');
if (token) {
    axios.defaults.headers.common['Authorization'] = "Bearer " + token;
}

// axios.defaults.withCredentials = true;
// axios.defaults.withCredentials = true

Vue.prototype.$axios = axios

// Vue.use(BootstrapVue);
// Vue.use(VCalendar, {
//     firstDayOfWeek: 2
// });
// Vue.use(VueTouch);
Vue.use(require('vue-moment'), {
    moment,
});
Vue.use(VeeValidate, {
    events: 'input|change|blur',
});
// Vue.use(VueFormWizard);

// Vue.component('vue-code-highlight', VueCodeHighlight);
Vue.component('Widget', Widget);
// Vue.use(bFormSlider);
// Vue.use(ClientTable, {
//     theme: 'bootstrap4'
// });
// Vue.use(VueTextareaAutosize);
// Vue.use(CKEditor);
// Vue.component('apexchart', VueApexCharts);
// Vue.directive('mask', VueMaskDirective);

Vue.use(VueLodash) // options is optional

Vue.mixin(permissions);
Vue.mixin(layoutMixin);
Vue.mixin(AuthMixin);
Vue.use(Toasted, {
    duration: 10000
});

Vue.use(filters);
Vue.use(helpers);


Vue.config.devtools = true;

Vue.mixin({
    data: function () {
        return {
            get datetime_format() {
                return "LLL"
            },
            get date_format() {
                return "LL"
            },
            get date_timezone() {
                return moment.tz.guess();
            }
        }
    }
})

Vue.config.productionTip = false;



export const globalStore = new Vue({
    data: {
        organization_id: 0,
        user_id: 0,
        user: null,
        level: 1,
        organization: null
    }
})

export const serverBus = new Vue();

Vue.use(IdleVue, {
    eventEmitter: serverBus,
    store,
    idleTime: 3600000, // 3600000 seconds,
    startAtIdle: true
});

/* eslint-disable no-new */
const app = new Vue({
    el: '#app',
    store,
    router,
    render: h => h(App),
});


