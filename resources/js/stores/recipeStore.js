import { defineStore } from "pinia";
import axios from "axios";
import { useAuthStore } from "./auth";

const APP_URL = import.meta.env.VITE_APP_URL;
// console.log("APP_URL:", APP_URL);

export const useRecipeStore = defineStore("recipe", {
    state: () => ({
        recipes: [],
        loading: false,
        error: null,
        recipeDetails: null,
    }),
    actions: {
        async fetchRecipes(page, search) {
            this.loading = true;
            this.error = null;
            try {
                const response = await axios({
                    method: "GET",
                    url: `${APP_URL}/api/recipes?page=${page}&search=${search}`,
                    // url: `http://localhost:8000/api/recipes?page=${page}&search=${search}`,
                    headers: {
                        Authorization: `Bearer ${useAuthStore().token}`,
                    },
                });

                console.log(response.data);

                this.recipes = response.data.data; // Sesuaikan dengan struktur data API Anda
                return response.data; // Kembalikan data untuk penggunaan lebih lanjut
            } catch (error) {
                console.error("Error fetching recipes:", error.response.status);
                if (error.response.status === 401) {
                    console.error("Unauthorized");
                    throw { status: 401 };
                }
            } finally {
                this.loading = false;
            }
        },

        async fetchRecipeById(id) {
            this.loading = true;
            this.error = null;
            try {
                const response = await fetch(
                    `${APP_URL}/api/recipes/${id}`,
                    // `http://localhost:8000/api/recipes/${id}`,
                    {
                        method: "GET",
                        headers: {
                            Accept: "application/json",
                            Authorization: `Bearer ${useAuthStore().token}`,
                        },
                    }
                );

                if (!response.ok) {
                    const errorDetails = await response.json();
                    throw new Error(
                        errorDetails.message ||
                            `Error fetching recipe: ${response.status}`
                    );
                }

                const data = await response.json();
                this.recipeDetails = data; // Simpan detail resep
                return data; // Kembalikan data untuk pemrosesan lebih lanjut
            } catch (error) {
                this.error = error.message || "An unknown error occurred";
                console.error("Error fetching recipe details:", error);
            } finally {
                this.loading = false;
            }
        },
    },
});
