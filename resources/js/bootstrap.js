import Vue from "vue";
import VueRouter from "vue-router";
import { BootstrapVue, IconsPlugin } from "bootstrap-vue";

import Home from "./components/Home";
import About from "./components/About";
import Login from "./components/Login";
import Task from "./components/Task";

import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue/dist/bootstrap-vue.css";
const nameToken = "@auth";

window._ = require("lodash");

window.axios = require("axios");
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.interceptors.request.use(
    config => {
        let token = window.localStorage.getItem(nameToken);
        if (token) {
            config.headers["Authorization"] = `Bearer ${token}`;
        }
        return config;
    },
    error => {
        config.headers["Authorization"] = null;
        window.localStorage.removeItem(nameToken);
        return Promise.reject(error);
    }
);

window.Vue = Vue;

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(VueRouter);

const routes = [
    {
        name: "home",
        path: "/",
        component: Home
    },
    {
        name: "about",
        path: "/about",
        component: About
    },
    {
        name: "login",
        path: "/login",
        component: Login
    },
    {
        name: "task",
        path: "/task",
        component: Task
    }
];

const router = new VueRouter({
    routes
});

router.beforeEach((to, from, next) => {
    const isAuthenticated = window.localStorage.getItem(nameToken);
    if (to.name !== "login" && !isAuthenticated) {
        next({ name: "login" });
    } else {
        next();
    }
});

const app = new Vue({
    el: "#app",
    router
}).$mount("#app");

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
