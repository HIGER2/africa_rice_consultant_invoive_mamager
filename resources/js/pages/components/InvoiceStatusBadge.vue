<script lang="ts" setup>
const props = defineProps({
  status: { type: String, required: true }
})

// Définir les couleurs et labels selon le statut
const statusMap: Record<string, { label: string; color: string }> = {
  pending_supervisor: { label: "En attente Supervisor", color: "bg-yellow-200 text-yellow-800" },
  pending_hr: { label: "En attente RH", color: "bg-yellow-200 text-yellow-800" },
  pending_finance: { label: "En attente Finance", color: "bg-yellow-200 text-yellow-800" },
  approved: { label: "Approuvée", color: "bg-green-200 text-green-800" },
  rejected: { label: "Rejetée", color: "bg-red-200 text-red-800" }
}

// Récupérer le label et couleur selon le statut
const current = statusMap[props.status] ?? { label: "Inconnu", color: "bg-gray-200 text-gray-800" }
</script>

<template>
  <span :class="['px-4 py-2 text-[12px] rounded-full font-semibold', current.color]">
    {{ current.label }}
  </span>
</template>

<style scoped>
/* Optionnel: ajoute un petit effet hover */
span {
  transition: all 0.2s ease;
}
</style>
