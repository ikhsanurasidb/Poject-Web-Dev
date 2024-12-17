<template>
    <div
        class="max-w-sm rounded-lg h-[50%] overflow-hidden shadow-lg flex flex-col justify-between"
    >
        <img
            class="max-h-[50%] object-cover"
            :src="recipe.image_url"
            :alt="recipe.name"
        />
        <!-- Updated to use image_url -->
        <div class="p-5 grid grid-flow-col place-items-end place- gap-2 w-full">
            <div class="place-self-start">
                <div class="font-bold text-xl mb-2">{{ recipe.name }}</div>
                <!-- <p class="text-gray-700 text-base">{{ recipe.description }}</p> -->
                 <p>{{ dummyDescription }}</p>
                <!-- <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    Laborum ipsa facilis vitae nemo blanditiis! Doloremque,
                    exercitationem? Numquam harum libero omnis laudantium
                    nostrum explicabo magni, fugiat repudiandae iste. Est, ipsam
                    iusto.
                </p> -->
            </div>
            <div class="">
                <button
                    class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded"
                    @click="$emit('click', recipe.id)"
                >
                    View Recipe
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        recipe: {
            type: Object,
            required: true,
        },
    },
    emits: ["click"],
    computed: {
        truncatedDescription() {
            const maxLength = 100; // Set the maximum length for the description
            if (this.recipe.description.length > maxLength) {
                return this.recipe.description.substring(0, maxLength) + "...";
            }
            return this.recipe.description;
        },
    },
};

const dummyDescription =
    "Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum ipsa facilis vitae nemo blanditiis! Doloremque, exercitationem? Numquam harum libero omnis laudantium nostrum explicabo magni, fugiat repudiandae iste. Est, ipsam iusto.";
</script>
