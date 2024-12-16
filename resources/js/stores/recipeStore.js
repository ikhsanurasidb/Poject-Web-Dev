import { defineStore } from "pinia";
import axios from "axios";
import { useAuthStore } from "./auth";

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
                    url: `http://localhost:8000/api/recipes?page=${page}&search=${search}`,
                    headers: {
                        Authorization: `Bearer ${useAuthStore().token}`,
                    },
                });

                console.log(response);

                // Ambil data dari response
                this.recipes = response.data.data; // Sesuaikan dengan struktur data API Anda
                return response.data; // Kembalikan data untuk penggunaan lebih lanjut
            } catch (error) {
                this.error = error.message || "Failed to fetch recipes.";
                console.error("Error fetching recipes:", error);
            } finally {
                this.loading = false;
            }
        },

        async fetchRecipeById(id) {
            this.loading = true;
            this.error = null;
            try {
                const response = await fetch(
                    `http://localhost:8000/api/recipes/${id}`,
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
