<template>
  <div>
    <label class="block text-sm font-medium mb-1">{{ label }}</label>

    <input
      :type="type"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      class="w-full border rounded p-2"
    />

    <p v-if="error" class="text-red-600 text-sm mt-1">{{ error }}</p>
  </div>
</template>

<script setup>
defineProps({
  modelValue: [String, Number],
  label: String,
  type: {
    type: String,
    default: "text",
  },
  error: {
    type: String,
    default: null,
  }
});

defineEmits(["update:modelValue"]);
</script>
