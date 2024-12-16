import { defineStore } from "pinia";

export const useAuthStore = defineStore("authStore", {
  state: () => {
    return {
      user: null,
      errors: {},
    };
  },
  actions: {
    async getUser() {
      if (localStorage.getItem("token")) {
        const res = await fetch("/api/user", {
          headers: {
            authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        });
        const data = await res.json();
        if (res.ok) {
          this.user = data;
        }
      }
    },
    async login(formData) {
      const res = await fetch(`/api/login`, {
          method: "POST",
          headers: {
              "Content-Type": "application/json",
          },
          body: JSON.stringify(formData),
      });
  
      const data = await res.json();
      if (!res.ok) {
          this.errors = data.errors || { general: "Login failed" };
      } else {
          this.errors = {};
          localStorage.setItem("token", data.token);
          this.user = data.user; // Adjusted to match the new response structure
          this.router.push({ name: "home" });
      }
    },
  
    async register(formData) {
      const res = await fetch(`/api/register`, {
          method: "POST",
          headers: {
              "Content-Type": "application/json",
          },
          body: JSON.stringify(formData),
      });
  
      const data = await res.json();
      if (!res.ok) {
          this.errors = data.errors || { general: "Registration failed" };
      } else {
          this.errors = {};
          localStorage.setItem("token", data.token);
          this.user = data.user; // Adjusted to match the new response structure
          this.router.push({ name: "home" });
      }
    },
    async logout() {
      const res = await fetch(`http://127.0.0.1:8000/api/logout`, {
        method: 'POST',
        headers: {
          authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      });

      const data = await res.json();
      console.log(data);

      if (res.ok) {
        this.user = null;
        this.errors = {};
        localStorage.removeItem("token");
        this.router.push({ name: "home" });
      }
    },
  },
});