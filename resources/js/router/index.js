import AboutView from "@/views/AboutView.vue";
import LoginView from "@/views/Auth/LoginView.vue";
import RegisterView from "@/views/Auth/RegisterView.vue";
import HomeView from "@/views/HomeView.vue";
import RecipeDetails from "@/views/RecipeDetails.vue";
import CreateView from "@/views/CreateView.vue";
import UpdateView from "@/views/UpdateView.vue";
import { createRouter, createWebHistory } from "vue-router";
import auth from "@/middleware/auth";

const routes = [
    {
        path: "/",
        name: "home",
        component: HomeView,
        meta: { requiresAuth: true },
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
        path: "/about",
        name: "About",
        component: AboutView,
        meta: { requiresAuth: true },
    },
    {
        path: "/recipe/:id",
        name: "recipe",
        component: RecipeDetails,
        props: true,
    },
    {
        path: "/create",
        name: "Create",
        component: CreateView,
    },
    {
        path: "/recipe/:id/edit",
        name: "Update",
        component: UpdateView,
        props: true,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    auth({ to, next });
});

export default router;
