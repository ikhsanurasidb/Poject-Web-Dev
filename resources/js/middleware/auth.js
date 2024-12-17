import { useAuthStore } from "@/stores/auth";
import { storeToRefs } from "pinia";

export default function auth({ to, next }) {
    const authStore = useAuthStore();
    const { token } = storeToRefs(authStore);

    // Daftar route yang tidak perlu autentikasi
    const publicPages = ["/login", "/register"];
    const authRequired = !publicPages.includes(to.path);

    // Jika halaman membutuhkan auth dan tidak ada token
    if (authRequired && !token.value) {
        return next("/login");
    }

    next();
}
