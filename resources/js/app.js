require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';

import App from './components/App'
// import Home from "./components/Home"
// import Label from './components/Label'
// import LabelsList from './components/LabelsList'
// import Search from './components/Search'
// import Add from './components/Add'

Vue.use(VueRouter);

const router = new VueRouter({
        mode: 'history',
        routes: [
            {
                path: '/',
                name: 'home',
                component: () => import('./components/Home.vue')
            },
            {
                path: '/labels/:label',
                name: 'label',
                component: () => import('./components/Label.vue')
            },
            {
                path: '/labels/edit/:label',
                name: 'labeledit',
                component: () => import('./components/LabelItem.vue')
            },
            {
                path: '/labels',
                name: 'labels',
                component: () => import('./components/LabelsList.vue')
            },
            {
                path: '/bookmarks/:search',
                name: 'search',
                component: () => import('./components/Search.vue')
            },
            {
                path: '/add',
                name: 'add',
                component: () => import('./components/Add.vue')
            },
            {
                path: '/login',
                name: 'login',
                component: () => import('./components/Login.vue')
              },
        ]
});

// Vue.config.devtools=false
Vue.config.productionTip = false

const app = new Vue({
        el: '#app',
        components: {App},
        router
});