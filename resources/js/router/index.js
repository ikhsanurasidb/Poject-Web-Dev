import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '../views/HomeView.vue';
import AboutView from '../views/AboutView.vue';
import CreateView from '../views/CreateView.vue';

const routes = [
    {
        path: '/',
        name: 'Home',
        component: HomeView
    },
    {
        path: '/about',
        name: 'About',
        component: AboutView
    },
    {
        path: '/create',
        name: 'Create',
        component: CreateView
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
