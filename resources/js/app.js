/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Router from "./route.js"

import VueResouce from 'vue-resource'
Vue.use(VueResouce)

import Auth from "./packages/auth/auth.js"
import Vue from "vue";
Vue.use(Auth)

Vue.http.options.root = 'http://localhost:8080'
Vue.http.headers.common['Authorazation'] = 'Bearer' + Vue.auth.getToken()

Router.beforeEach(
    (to, from, next) => {
        if (to.matched.some(record => record.meta.forVisitor)) {
            if (Vue.auth.isAuthenticated()) {
                next({
                    path: '/home'
                })

            } else next()
 
        }else if (to.matched.some(record => record.meta.forAuth)) {
            if (! Vue.auth.isAuthenticated()) {
                next({
                    path: '/login'
                })

            } else next()
 
        }
        
        else next()
    }
)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);

Vue.component(
    'all-users',
    require('./components/UsersComponents.vue').default
);

Vue.component(
    'login-component',
    require('./components/LoginComponents.vue').default
);


// Vue.component(
//     'home',
//     require('./components/HomeComponents.vue').default
// );




const app = new Vue({
    el: '#app',
    // component: {}
    router: Router
});

// export default router

