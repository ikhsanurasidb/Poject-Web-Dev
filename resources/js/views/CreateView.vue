<script setup>
import Button from "@/components/ui/button/Button.vue";
import Input from "@/components/ui/input/Input.vue";
import Label from "@/components/ui/label/Label.vue";
import axios from "axios";
import { ref } from "vue";
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
import { useToast } from "@/components/ui/toast";
import { ImageIcon, PlusIcon, TrashIcon } from "lucide-vue-next";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";

const previewImage = ref(null);
const recipe = ref({
    name: "",
    servings: "",
    description: "",
    duration: 30,
    ingredients: [],
    directions: [],
});

const router = useRouter();
const { toast } = useToast();

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
        name: "",
        hasSpecificSize: false,
    });
};

const removeIngredient = (index) => {
    recipe.value.ingredients.splice(index, 1);
};

const addDirection = () => {
    recipe.value.directions.push({
        text: "",
        image: null,
    });
};

const removeDirection = (index) => {
    recipe.value.directions.splice(index, 1);
};

const handleDirectionImageUpload = (event, index) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            recipe.value.directions[index].image = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const handlePublish = async () => {
    try {
        const formData = new FormData();
        formData.append("name", recipe.value.name);
        formData.append("servings", recipe.value.servings);
        formData.append("duration", recipe.value.duration);
        formData.append("rating", 0);
        formData.append("description", recipe.value.description);
        formData.append("created_by", useAuthStore().email);

        if (previewImage.value) {
            const response = await fetch(previewImage.value);
            const blob = await response.blob();
            formData.append("image", blob, "recipe_image.jpg");
        }

        recipe.value.ingredients.forEach((ingredient, index) => {
            formData.append(
                `ingredients[${index}][quantity]`,
                ingredient.quantity
            );
            formData.append(`ingredients[${index}][unit]`, ingredient.unit);
            formData.append(
                `ingredients[${index}][description]`,
                ingredient.name
            );
        });

        // Tunggu semua proses upload gambar directions selesai
        await Promise.all(
            recipe.value.directions.map(async (direction, index) => {
                formData.append(
                    `directions[${index}][description]`,
                    direction.text
                );
                if (direction.image) {
                    const response = await fetch(direction.image);
                    const blob = await response.blob();
                    formData.append(
                        `directions[${index}][image]`,
                        blob,
                        `direction_image_${index}.jpg`
                    );
                }
            })
        );

        // Debug untuk melihat isi formData
        for (let pair of formData.entries()) {
            console.log(`${pair[0]}: ${pair[1]}`);
        }

        const response = await axios.post("/api/recipes", formData, {
            headers: {
                Authorization: `Bearer ${useAuthStore().token}`,
                "Content-Type": "multipart/form-data",
            },
        });

        console.log("Recipe published successfully:", response.data);
        toast({
            title: "Recipe published!",
            description: response.data,
        });
        router.push("/");
    } catch (error) {
        console.error("Error publishing recipe:", error);
        toast({
            title: "Oops! Something went wrong",
            description:
                `${error.message}, have you input all fields?` ||
                "Failed to publish the recipe. Please try again.",
            variant: "destructive",
        });
    }
};
</script>

<template>
    <div class="container max-w-6xl p-6 mx-auto">
        <header class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-semibold">Create new recipe</h1>
            <Button @click="handlePublish" variant="default">
                Publish recipe
            </Button>
        </header>

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
            <div class="space-y-6">
                <h2 class="text-lg font-medium text-gray-700">
                    Recipe General Information
                </h2>

                <div
                    class="p-8 text-center border-2 border-gray-300 border-dashed rounded-lg"
                >
                    <div class="space-y-2">
                        <div class="flex justify-center">
                            <ImageIcon
                                v-if="!previewImage"
                                class="w-12 h-12 text-gray-400"
                            />
                            <img
                                v-else
                                :src="previewImage"
                                class="rounded-lg max-h-48"
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
                    <Label for="recipe-name">Recipe description</Label>
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
                                        class="w-4 h-4 text-gray-400"
                                    />
                                </span>
                            </div>

                            <div class="flex gap-2">
                                <Input
                                    v-model="ingredient.quantity"
                                    type="number"
                                />
                                <Select v-model="ingredient.unit">
                                    <SelectTrigger>
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
                                    v-model="ingredient.name"
                                    placeholder="eg: Milk"
                                />
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    class="p-4"
                                    @click="removeIngredient(index)"
                                >
                                    <TrashIcon class="w-4 h-4" />
                                </Button>
                            </div>
                        </div>

                        <div class="ml-8">
                            <div class="flex items-center gap-2">
                                <Switch v-model="ingredient.hasSpecificSize" />
                                <span class="text-sm text-gray-600"
                                    >Detailed specific size</span
                                >
                            </div>
                        </div>
                    </div>

                    <Button
                        variant="outline"
                        class="w-full"
                        @click="addIngredient"
                    >
                        <PlusIcon class="w-4 h-4 mr-2" />
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
                                    class="inline-flex items-center justify-center w-6 h-6 text-sm bg-gray-100 rounded-full"
                                >
                                    {{ String(index + 1).padStart(2, "0") }}
                                </span>
                            </div>

                            <div class="flex flex-col flex-1 gap-2">
                                <Textarea
                                    v-model="direction.text"
                                    :placeholder="`eg: Step ${
                                        index + 1
                                    } instructions...`"
                                    rows="3"
                                    class="flex-1"
                                />
                                <div class="flex items-center gap-2">
                                    <input
                                        type="file"
                                        :ref="`directionFileInput${index}`"
                                        class="hidden"
                                        accept="image/png,image/jpeg"
                                        @change="
                                            (event) =>
                                                handleDirectionImageUpload(
                                                    event,
                                                    index
                                                )
                                        "
                                    />
                                    <Button
                                        variant="outline"
                                        @click="
                                            $refs[
                                                `directionFileInput${index}`
                                            ][0].click()
                                        "
                                    >
                                        {{
                                            direction.image
                                                ? "Change Image"
                                                : "Add Image"
                                        }}
                                    </Button>
                                    <img
                                        v-if="direction.image"
                                        :src="direction.image"
                                        class="object-cover w-12 h-12 rounded"
                                    />
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        @click="removeDirection(index)"
                                    >
                                        <TrashIcon class="w-4 h-4" />
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <Button
                        variant="outline"
                        class="w-full"
                        @click="addDirection"
                    >
                        <PlusIcon class="w-4 h-4 mr-2" />
                        Add directions
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>
