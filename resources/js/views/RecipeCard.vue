<template>
    <Card class="w-[350px] h-[400px] flex flex-col">
      <CardHeader class="p-0">
        <img
          class="w-full h-48 object-cover rounded-t-lg"
          :src="recipe.image_url"
          :alt="recipe.name"
        />
      </CardHeader>
      <CardContent class="flex-grow p-4 flex flex-col justify-between">
        <div>
          <div class="flex justify-between items-start mb-2">
            <CardTitle class="text-xl">{{ recipe.name }}</CardTitle>
            <DropdownMenu>
              <DropdownMenuTrigger asChild>
                <Button variant="ghost" class="h-8 w-8 p-0">
                  <span class="sr-only">Open menu</span>
                  <MoreVerticalIcon class="h-4 w-4" />
                </Button>
              </DropdownMenuTrigger>
              <DropdownMenuContent align="end">
                <DropdownMenuItem @click="$emit('edit', recipe.id)">
                  <PencilIcon class="mr-2 h-4 w-4" />
                  <span>Edit</span>
                </DropdownMenuItem>
                <DropdownMenuItem @click="$emit('delete', recipe.id)">
                  <TrashIcon class="mr-2 h-4 w-4" />
                  <span>Delete</span>
                </DropdownMenuItem>
              </DropdownMenuContent>
            </DropdownMenu>
          </div>
          <p class="text-muted-foreground line-clamp-3">{{ recipe.description }}</p>
        </div>
        <Button class="w-full mt-4" @click="$emit('click', recipe.id)">
          View Recipe
        </Button>
      </CardContent>
    </Card>
</template>
  
<script setup>
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { MoreVerticalIcon, PencilIcon, TrashIcon } from 'lucide-vue-next';
  
defineProps({
  recipe: {
    type: Object,
    required: true,
  },
})
  
defineEmits(['click', 'edit', 'delete'])
</script>