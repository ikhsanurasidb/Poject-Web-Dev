<template>
  <div
    v-if="showModal"
    class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center"
  >
    <Card class="max-w-md">
      <template #header>
        <slot name="title" />
        <Button
          class="ml-auto"
          variant="icon"
          @click="$emit('close-modal')"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </Button>
      </template>
      <slot name="content" />
    </Card>
  </div>
</template>

<script>
import { Button } from '../components/ui/button';
import { Card } from '../components/ui/card';

export default {
  components: { Card, Button },
  props: {
    showModal: {
      type: Boolean,
      required: true,
    },
  },
  emits: ['close-modal'],
};
</script>