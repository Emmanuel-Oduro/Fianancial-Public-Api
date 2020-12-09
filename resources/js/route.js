import Vue from 'vue'

import VueRouter from 'vue-router'

import Login from './components/LoginComponents.vue'
import Register from './components/RegisterComponents.vue'
import Home from './components/HomeComponents.vue'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/login',
            component: Login,
            meta: {
                forVisitor: true
            }
        },
        {
            path: '/Register',
            component: Register,
            meta: {
                forVisitor: true
            }
        },
        {
            path: '/home',
            component: Home,
            meta: {
                forAuth: true
            }
        }
    ],
});

export default router
