import { defineStore } from "pinia";

export const useAuthStore = defineStore("auth", {
    state: () => ({
        token: null,
        name: null,
        email: null,
    }),
    actions: {
        setToken(token) {
            this.token = token;
        },
        setName(name) {
            this.name = name;
        },
        setEmail(email) {
            this.email = email;
        },
        clearAuth() {
            this.token = null;
            this.name = null;
            this.email = null;
        },
    },
    persist: true, // This will persist the store in localStorage
});
