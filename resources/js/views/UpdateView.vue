<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRoute } from "vue-router";
import Button from "@/components/ui/button/Button.vue";
import Input from "@/components/ui/input/Input.vue";
import Label from "@/components/ui/label/Label.vue";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import Switch from "@/components/ui/switch/Switch.vue";
import Textarea from "@/components/ui/textarea/Textarea.vue";
import {
    ImageIcon,
    PlusIcon,
    TrashIcon,
    // DotsVerticalIcon,
} from "lucide-vue-next";
import { useAuthStore } from "@/stores/auth";

//? route way
const route = useRoute();
const recipeId = route.params.id;

//? props way
// const props = defineProps({
//     id: {
//         type: String,
//         required: true
//     }
// });

const previewImage = ref(null);
const recipe = ref({
    name: "",
    servings: "",
    description: "",
    duration: 30,
    ingredients: [],
    directions: [],
    // rating: 0, // Added rating property
});

onMounted(async () => {
    console.log("Received id:", recipeId);
    try {
        const response = await axios.get(`/api/recipes/${recipeId}`, {
            headers: {
                Authorization: `Bearer ${useAuthStore().token}`,
            },
        });
        recipe.value = response.data;
        if (recipe.value.image_url) {
            previewImage.value = recipe.value.image_url;
        }
    } catch (error) {
        console.error("Error fetching recipe:", error);
    }
});

const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImage.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const addIngredient = () => {
    recipe.value.ingredients.push({
        quantity: "",
        unit: "pcs",
        description: "",
    });
};

const removeIngredient = (index) => {
    recipe.value.ingredients.splice(index, 1);
};

const addDirection = () => {
    recipe.value.directions.push({
        description: "",
    });
};

const removeDirection = (index) => {
    recipe.value.directions.splice(index, 1);
};

const handleUpdate = async () => {
    try {
        const formData = new FormData();
        formData.append("name", recipe.value.name);
        formData.append("servings", recipe.value.servings);
        formData.append("duration", recipe.value.duration);
        formData.append("description", recipe.value.description);
        // formData.append("rating", recipe.value.rating || 0);
        // formData.append("created_by", useAuthStore().email);
        formData.append("_method", "PATCH"); // Add this line to simulate PATCH request

        if (previewImage.value && !previewImage.value.startsWith("http")) {
            const response = await fetch(previewImage.value);
            const blob = await response.blob();
            formData.append("image", blob, "recipe_image.jpg");
        }

        recipe.value.ingredients.forEach((ingredient, index) => {
            formData.append(
                `ingredients[${index}][quantity]`,
                ingredient.quantity
            );
            formData.append(
                `ingredients[${index}][unit]`,
                ingredient.unit || ""
            );
            formData.append(
                `ingredients[${index}][description]`,
                ingredient.description
            );
        });

        recipe.value.directions.forEach((direction, index) => {
            formData.append(
                `directions[${index}][description]`,
                direction.description
            );
        });

        // Log the form data
        for (let pair of formData.entries()) {
            console.log(`${pair[0]}: ${pair[1]}`);
        }

        const response = await axios.post(
            `/api/recipes/${recipeId}`,
            formData,
            {
                headers: {
                    Authorization: `Bearer ${useAuthStore().token}`,
                },
            }
        );

        console.log("Recipe updated successfully:", response.data);
    } catch (error) {
        console.error("Error updating recipe:", error);
    }
};
</script>

<template>
    <div class="container mx-auto p-6 max-w-6xl">
        <header class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-semibold">Update recipe</h1>
            <Button @click="handleUpdate" variant="default">
                Update recipe
            </Button>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-6">
                <h2 class="text-lg font-medium text-gray-700">
                    Recipe General Information
                </h2>

                <div
                    class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center"
                >
                    <div class="space-y-2">
                        <div class="flex justify-center">
                            <ImageIcon
                                v-if="!previewImage"
                                class="h-12 w-12 text-gray-400"
                            />
                            <img
                                v-else
                                :src="previewImage"
                                class="max-h-48 rounded-lg"
                                alt="Recipe preview"
                            />
                        </div>
                        <div class="flex justify-center">
                            <input
                                type="file"
                                ref="fileInput"
                                class="hidden"
                                accept="image/png,image/jpeg"
                                @change="handleImageUpload"
                            />
                            <Button
                                variant="outline"
                                @click="$refs.fileInput.click()"
                            >
                                Upload Photo
                            </Button>
                        </div>
                        <p class="text-sm text-gray-500">
                            PNG or JPEG (max. 10MB)
                        </p>
                    </div>
                </div>

                <div class="space-y-2">
                    <Label for="recipe-name">Recipe name</Label>
                    <Input
                        id="recipe-name"
                        v-model="recipe.name"
                        placeholder="eg: Savory Stuffed Bell Peppers"
                    />
                </div>

                <div class="space-y-2">
                    <Label for="recipe-description">Recipe description</Label>
                    <Input
                        id="recipe-description"
                        v-model="recipe.description"
                        placeholder="eg: a delicious and healthy meal, perfect for lunch or dinner"
                    />
                </div>

                <div class="space-y-2">
                    <Label for="servings">Number of serving</Label>
                    <div class="flex items-center gap-2">
                        <Input
                            id="servings"
                            v-model="recipe.servings"
                            placeholder="eg: 4 or 3-5"
                        />
                        <span class="text-gray-500">person</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <Label for="duration">Cook duration</Label>
                    <div class="flex items-center gap-2">
                        <Input
                            id="duration"
                            v-model="recipe.duration"
                            type="number"
                        />
                        <span class="text-gray-500">minute</span>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <h2 class="text-lg font-medium text-gray-700">Recipe Detail</h2>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-medium">Ingredients</h3>
                    </div>

                    <div
                        v-for="(ingredient, index) in recipe.ingredients"
                        :key="index"
                        class="space-y-4"
                    >
                        <div class="flex items-start gap-2">
                            <div class="flex-none">
                                <span
                                    class="inline-flex items-center justify-center w-6 h-6"
                                >
                                    <DotsVerticalIcon
                                        class="h-4 w-4 text-gray-400"
                                    />
                                </span>
                            </div>

                            <div class="flex gap-2">
                                <Input
                                    v-model="ingredient.quantity"
                                    class=""
                                    type="number"
                                />
                                <Select v-model="ingredient.unit">
                                    <SelectTrigger class="">
                                        <SelectValue
                                            placeholder="Select the unit"
                                        />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectLabel>Volume</SelectLabel>
                                            <SelectItem value="-">-</SelectItem>
                                            <SelectItem value="pcs"
                                                >pcs</SelectItem
                                            >
                                            <SelectItem value="cup"
                                                >cup</SelectItem
                                            >
                                            <SelectItem value="oz"
                                                >oz</SelectItem
                                            >
                                            <SelectItem value="tbsp"
                                                >tbsp</SelectItem
                                            >
                                            <SelectItem value="tsp"
                                                >tsp</SelectItem
                                            >
                                            <SelectItem value="gr"
                                                >gr</SelectItem
                                            >
                                            <SelectItem value="kg"
                                                >kg</SelectItem
                                            >
                                            <SelectItem value="ml"
                                                >ml</SelectItem
                                            >
                                            <SelectItem value="liter"
                                                >liter</SelectItem
                                            >
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <Input
                                    v-model="ingredient.description"
                                    class=""
                                    placeholder="eg: Milk"
                                />
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    class="p-4"
                                    @click="removeIngredient(index)"
                                >
                                    <TrashIcon class="h-4 w-4" />
                                </Button>
                            </div>
                        </div>
                    </div>

                    <Button
                        variant="outline"
                        class="w-full"
                        @click="addIngredient"
                    >
                        <PlusIcon class="h-4 w-4 mr-2" />
                        Add ingredients
                    </Button>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-medium">Directions | Step by step</h3>
                    </div>

                    <div
                        v-for="(direction, index) in recipe.directions"
                        :key="index"
                        class="space-y-4"
                    >
                        <div class="flex items-start gap-2">
                            <div class="flex-none">
                                <span
                                    class="inline-flex items-center justify-center w-6 h-6 bg-gray-100 rounded-full text-sm"
                                >
                                    {{ String(index + 1).padStart(2, "0") }}
                                </span>
                            </div>

                            <div class="flex-1 flex gap-2">
                                <Textarea
                                    v-model="direction.description"
                                    :placeholder="`eg: Step ${
                                        index + 1
                                    } instructions...`"
                                    rows="3"
                                    class="flex-1"
                                />
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    @click="removeDirection(index)"
                                >
                                    <TrashIcon class="h-4 w-4" />
                                </Button>
                            </div>
                        </div>
                    </div>

                    <Button
                        variant="outline"
                        class="w-full"
                        @click="addDirection"
                    >
                        <PlusIcon class="h-4 w-4 mr-2" />
                        Add directions
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>
