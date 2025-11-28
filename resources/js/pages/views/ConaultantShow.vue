<template>
  <div class="p-6 max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold">Détails : {{ consultant.name }} {{ consultant.last_name }}</h1>
      <div class="flex gap-2">
        <Link :href="route('consultants.edit', consultant.uuid)" class="px-3 py-1 bg-yellow-500 text-white rounded">Modifier</Link>
        <Link :href="route('consultants.index')" class="px-3 py-1 bg-gray-200 rounded">Retour</Link>
      </div>
    </div>

    <div class="grid grid-cols-2 gap-4 bg-white p-4 rounded shadow">
      <div><strong>ResNo</strong><div>{{ consultant.resno }}</div></div>
      <div><strong>Nom</strong><div>{{ consultant.name }} {{ consultant.last_name }}</div></div>
      <div><strong>Genre</strong><div>{{ consultant.gender }}</div></div>
      <div><strong>Nationalité</strong><div>{{ consultant.nationality }}</div></div>
      <div><strong>Pays naissance</strong><div>{{ consultant.country_of_birth }}</div></div>
      <div><strong>Ville</strong><div>{{ consultant.town_city }}</div></div>
      <div><strong>Date from</strong><div>{{ consultant.date_from }}</div></div>
      <div><strong>Date to</strong><div>{{ consultant.date_to }}</div></div>
      <div><strong>Position</strong><div>{{ consultant.position }}</div></div>
      <div><strong>Resource type</strong><div>{{ consultant.resource_type }}</div></div>
      <div><strong>Job level</strong><div>{{ consultant.job_level }}</div></div>
      <div><strong>Supervisor</strong><div>{{ consultant.supervisor_position }} — {{ consultant.supervisor }}</div></div>
      <div><strong>Cost center / Dept</strong><div>{{ consultant.costc }} / {{ consultant.department }}</div></div>
      <div><strong>Original hire date</strong><div>{{ consultant.original_hire_date }}</div></div>
      <div><strong>Seniority</strong><div>{{ consultant.seniority }}</div></div>
      <div><strong>Birthdate</strong><div>{{ consultant.birthdate }}</div></div>
      <div><strong>Nationality at birth</strong><div>{{ consultant.nationality_at_birth }}</div></div>
      <div><strong>Marital status</strong><div>{{ consultant.marital_status }}</div></div>
      <div><strong>Seconded</strong><div>{{ consultant.seconded_personnel ? 'Oui' : 'Non' }}</div></div>
      <div><strong>Shared working</strong><div>{{ consultant.shared_working_arrangement ? 'Oui' : 'Non' }}</div></div>
      <div><strong>Root / Division / Group</strong><div>{{ consultant.root }} / {{ consultant.division }} / {{ consultant.group }}</div></div>
      <div><strong>CG Unit / Sub unit</strong><div>{{ consultant.cg_unit }} / {{ consultant.sub_unit }}</div></div>
      <div><strong>BG level</strong><div>{{ consultant.bg_level }}</div></div>
      <div><strong>CGIAR workforce group</strong><div>{{ consultant.cgiar_workforce_group }}</div></div>
      <div><strong>Duty post classification</strong><div>{{ consultant.dutypost_classification }}</div></div>
      <div><strong>Education level</strong><div>{{ consultant.education_level }}</div></div>
      <div><strong>Host center</strong><div>{{ consultant.host_center }}</div></div>
      <div><strong>Hosted</strong><div>{{ consultant.hosted_personnel ? 'Oui' : 'Non' }}</div></div>
      <div><strong>Hosted/Seconded</strong><div>{{ consultant.hosted_seconded_personnel ? 'Oui' : 'Non' }}</div></div>
      <div><strong>Secondary nationality</strong><div>{{ consultant.secondary_nationality }}</div></div>
    </div>

    <h2 class="text-lg font-semibold mt-6 mb-2">Coordonnées bancaires</h2>
    <div v-if="consultant.bank_details.length" class="space-y-2">
      <div v-for="b in consultant.bank_details" :key="b.id" class="p-3 bg-white rounded shadow">
        <div><strong>Banque:</strong> {{ b.bank_name }}</div>
        <div><strong>IBAN:</strong> {{ b.iban }}</div>
        <div><strong>SWIFT:</strong> {{ b.swift_code }}</div>
      </div>
    </div>
    <div v-else class="text-gray-500">Aucune coordonnée bancaire</div>
  </div>
</template>

<script setup>
const props = defineProps({ consultant: Object });
</script>
