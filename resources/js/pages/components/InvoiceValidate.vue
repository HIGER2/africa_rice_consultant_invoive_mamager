<script lang="ts" setup>
import { ref, computed, reactive } from 'vue';
import { defineProps } from 'vue';
import { router } from '@inertiajs/vue3'

const props=defineProps({
  invoice: Object, // facture avec invoiceValidation
  userRole: String // rôle connecté
});


let role = props.userRole
let processing = ref(false)
// let role:string = "supervisor"

const validation = reactive({
    service: role,
    supervisor:{
        supervisor_period_validated: props.invoice.validations?.supervisor_period_validated ?? false,
        supervisor_report_validated:props.invoice.validations?.supervisor_report_validated ?? false,
        supervisor_budget_validated:props.invoice.validations?.supervisor_budget_validated ?? false,
    },
  hr:{
    hr_period_validated: props.invoice.validations?.hr_period_validated ?? false,
    hr_report_validated: props.invoice.validations?.hr_report_validated ?? false,
    hr_clearance_validated: props.invoice.validations?.hr_clearance_validated ?? false,
  },
  finance:{
    finance_period_validated: props.invoice.validations?.finance_period_validated ?? false,
    finance_report_validated: props.invoice.validations?.finance_report_validated ?? false,
    finance_clearance_validated: props.invoice.validations?.finance_clearance_validated ?? false,
    finance_contract_validated: props.invoice.validations?.finance_contract_validated ?? false,
  }
});



// Définir les champs visibles par service
const serviceFields = {
    supervisor:['supervisor_period_validated', 'supervisor_report_validated', 'supervisor_budget_validated'],
    hr:['hr_period_validated', 'hr_report_validated', 'hr_clearance_validated'],
    finance: ['finance_period_validated', 'finance_report_validated', 'finance_clearance_validated', 'finance_contract_validated'],
}

// computed(() => {
//   switch (role) {
//     case 'supervisor':
//       return ['supervisor_period_validated', 'supervisor_report_validated', 'supervisor_budget_validated'];
//     case 'hr':
//       return ['hr_period_validated', 'hr_report_validated', 'hr_clearance_validated'];
//     case 'finance':
//       return ['finance_period_validated', 'finance_report_validated', 'finance_clearance_validated', 'finance_contract_validated'];
//     default:
//       return [];
//   }
// });

const submitValidation = (role: 'supervisor' | 'hr' | 'finance') => {
    // Construire le payload uniquement pour le rôle courant
    const payload = {
        [role]:{...validation[role]},  // les champs du rôle courant
        service: role          // le service courant
    };
    processing.value =true
    router.post(`/invoices/${props?.invoice.uuid}/validate`,payload,{
        onSuccess: (page) => {
                console.log('Validation envoyée avec succès', page);
                router.visit(`/invoices/${props?.invoice.uuid}`)
                
            },
            onError: (errors) => {
                console.log('Erreurs retournées :', errors);
            },
            onFinish:()=>{
                processing.value = false
            }
    })
};

// const submitValidation = () => {
//     //  console.log('Form data:', form.data()); // ← Voyez exactement ce qui sera envoyé
//     // validation.validation['service'] = role;      // s’assurer que le service est correct
//     validation.validation = validation[role]; 

//     console.log('====================================');
//     console.log(validation.data());
//     console.log('====================================');

//     return
//     validation.post('/invoices/${props.invoice.uuid}/validate', {
//       onSuccess: (data) => {
//         console.log('Invoice created successfully', data);
//       },
//       onError: (errors) => {
//         console.log("Les erreurs retournées :", errors);
//       }
//     })
// };
</script>

<template>
  <div class="no-print max-w-3xl mx-auto  mt-4 border border-gray-200 space-y-6 p-4 bg-white shadow rounded">
    <h2 class="font-semibold text-lg mb-2">Validation {{ role }}</h2>
    <!-- <pre>{{ invoice.validations }}</pre> -->
    <div v-for="field in serviceFields[role]" :key="field" class="flex items-center mb-2">
      <input type="checkbox" v-model="validation[role][field]" :id="field" class="mr-2"/>
      <label :for="field" class="capitalize">{{ field.replace('_', ' ') }}</label>
    </div>
    <button
    :disabled="processing"
    @click="submitValidation(role)" class="mt-3 cursor-pointer bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
      {{ processing ? 'Processing...':'Validate & submit '}}
    </button>
  </div>
</template>

<style scoped>
.card {
  max-width: 500px;
  margin: auto;
}
</style>
