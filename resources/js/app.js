/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// 各画面のコンポーネント
Vue.component('example-component', require('./components/ExampleComponent.vue').default);

Vue.component('conference-component', require('./components/ConferenceComponent.vue').default);
Vue.component('home-component', require('./components/HomeComponent.vue').default);
Vue.component('mypage-component', require('./components/MypageComponent.vue').default);
Vue.component('company-component', require('./components/CompanyComponent.vue').default);

// カレンダー
import { Datetime } from 'vue-datetime';
Vue.component('datetime', Datetime);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
