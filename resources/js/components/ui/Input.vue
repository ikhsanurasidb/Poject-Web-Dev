<template>
    <div>
      <input
        :type="type"
        :placeholder="placeholder"
        :value="modelValue"
        class="border rounded p-2 w-full"
        @input="updateValue"
        :class="{'border-red-500': error}"
        aria-label="Input"
      />
      <p v-if="error" class="text-red-500 text-sm mt-1">{{ error }}</p>
    </div>
</template>
  
<script setup>
import { defineEmits, defineProps } from 'vue';
  
  const props = defineProps({
    modelValue: {
      type: String,
      default: '',
    },
    type: {
      type: String,
      default: 'text',
    },
    placeholder: {
      type: String,
      default: '',
    },
    error: {
      type: String,
      default: '',
    },
  });
  
  const emit = defineEmits(['update:modelValue']);
  
  // Emit the updated value when the input changes
  const updateValue = (event) => {
    emit('update:modelValue', event.target.value);
  };
  </script>
  
  <style scoped>
  /* Add any additional styles here */
  </style>