import AboutView from '@/views/AboutView.vue';
import LoginView from "@/views/Auth/LoginView.vue";
import RegisterView from "@/views/Auth/RegisterView.vue";
import HomeView from '@/views/HomeView.vue';
import RecipeDetails from '@/views/RecipeDetails.vue';
import CreateView from '@/views/CreateView.vue';
import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {
        path: '/',
        name: 'home',
        component: HomeView
    },
    {
        path: "/register",
        name: "register",
        component: RegisterView,
        meta: { guest: true },
    },
    {
        path: "/login",
        name: "login",
        component: LoginView,
        meta: { guest: true },
    },
    {
        path: '/about',
        name: 'About',
        component: AboutView
    },
    {
        path: '/recipe/:id',
        name: 'recipe',
        component: RecipeDetails,
        props: true
    },
    {
        path: '/create',
        name: 'Create',
        component: CreateView
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
