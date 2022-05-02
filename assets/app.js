import './styles/app.css';

// start the Stimulus application
import './bootstrap';

axios.defaults.baseURL = process.env.VUE_APP_API_BASE_URL;

console.log(process.env.VUE_APP_INFO);

import { createApp } from 'vue'
import { createRouter, createWebHistory, createWebHashHistory } from 'vue-router'

import Home from './views/Home'
import PageNotFound from './error/PageNotFound'

const routes = [
    { path: '/', component: Home },
    { path: '/:pathMatch(.*)*', component: PageNotFound }
]


const router = createRouter({
    history: createWebHistory(),
    routes,
})

const app = createApp({})

app.use(router)

app.mount('#app')