<script lang="ts" setup>
import AdminLayout from '../../layouts/AdminLayout.vue'
import InvoiceStatusBadge from '../components/InvoiceStatusBadge.vue'
import InvoiceValidate from '../components/InvoiceValidate.vue'

const props = defineProps({
  invoice: { type: Object, required: true },
  auth:Object
})

const data = props.invoice.data

const stages = [
  {
    name: 'Supervisor',
    keys: [
      'supervisor_period_validated',
      'supervisor_report_validated',
      'supervisor_budget_validated'
    ]
  },
  {
    name: 'HR',
    keys: [
      'hr_period_validated',
      'hr_report_validated',
      'hr_clearance_validated'
    ]
  },
  {
    name: 'Finance',
    keys: [
      'finance_period_validated',
      'finance_report_validated',
      // 'finance_budget_validated',
      'finance_clearance_validated',
      'finance_contract_validated'
    ]
  }
]

const statusColors = {
  pending_supervisor: 'bg-yellow-200 text-yellow-800',
  pending_hr: 'bg-yellow-200 text-yellow-800',
  pending_finance: 'bg-yellow-200 text-yellow-800',
  approved: 'bg-green-200 text-green-800',
  rejected: 'bg-red-200 text-red-800'
}

let stateStatus = (status,role)=>{
  if (status=='pending_supervisor' && role==='supervisor') return true
  if (status=='pending_hr' && role==='hr') return true
  if (status=='pending_finance' && role==='finance') return true
  if (role==='admin') return true

  return false
}
</script>

<template>
<div>
    <AdminLayout>
      <div class="max-w-3xl mx-auto p-6 bg-white rounded border border-gray-200 space-y-6">
    <!-- Facture Header -->
            <div class="flex justify-between items-center mb-4">
              <h1 class="text-3xl font-bold">Facture #{{ data.invoice_number }}</h1>
              <InvoiceStatusBadge :status="data.status" />
              <!-- <span :class="['px-4 py-2 rounded-full font-semibold', statusColors[data.status]]">
                {{ data.status.replace('_', ' ').toUpperCase() }}
              </span> -->
            </div>

            <!-- Facture Info -->
            <section class="grid grid-cols-1 md:grid-cols-2 gap-4 border-b pb-4">
              <div><span class="font-semibold">Location:</span> {{ data.location }}</div>
              <div><span class="font-semibold">Contract Period:</span> {{ data.contract_period_from }} → {{ data.contract_period_to }}</div>
              <div><span class="font-semibold">Period Dates:</span> {{ data.date_from }} → {{ data.date_to }}</div>
              <div><span class="font-semibold">Honoraires Mensuel:</span> {{ data.honoraires_mensuel }}</div>
              <div><span class="font-semibold">Jours Travaillés:</span> {{ data.jours_travailles }}</div>
              <div><span class="font-semibold">Honoraires à payer:</span> {{ data.honoraires_a_payer }}</div>
              <div><span class="font-semibold">Forfaitaire:</span> {{ data.is_forfaitaire_contract }} ({{ data.forfaitaire_amount }})</div>
              <div><span class="font-semibold">Rapport Activité:</span> {{ data.rapport_activite_required }}</div>
              <div><span class="font-semibold">Clearance:</span> {{ data.clearance_required }}</div>
            </section>

            <!-- Consultant Info -->
            <section class="grid grid-cols-1 md:grid-cols-2 gap-4 border-b pb-4">
              <div><span class="font-semibold">ResNo:</span> {{ data.resno }}</div>
              <div><span class="font-semibold">Name:</span> {{ data.name }} {{ data.last_name }}</div>
              <div><span class="font-semibold">Email:</span> {{ data.email }}</div>
              <div><span class="font-semibold">Phone:</span> {{ data.phone }}</div>
              <div><span class="font-semibold">Position:</span> {{ data.position }}</div>
              <div><span class="font-semibold">Department:</span> {{ data.department }}</div>
              <div><span class="font-semibold">Bank:</span> {{ data.bank_name }}</div>
              <div><span class="font-semibold">IBAN:</span> {{ data.iban }}</div>
              <div><span class="font-semibold">SWIFT:</span> {{ data.swift_code }}</div>
            </section>

            <!-- Documents -->
            <section class="border-b pb-4">
              <h2 class="font-semibold text-lg mb-2">Documents</h2>
              <div class="flex flex-col gap-2">
                <a
                  v-if="data.rapport_activite_file"
                  :href="data.rapport_activite_file"
                  target="_blank"
                  class="text-blue-600 hover:underline"
                >Rapport Activité</a>
                <a
                  v-if="data.clearance_file"
                  :href="data.clearance_file"
                  target="_blank"
                  class="text-blue-600 hover:underline"
                >Clearance</a>
              </div>
            </section>

            <!-- Validation Status -->
            <section>
              <h2 class="font-semibold text-lg mb-2">State Validation</h2>
              <!-- <pre>{{ data.validations }}</pre> -->
              <div class="space-y-4">
                <div v-for="stage in stages" :key="stage.name" class="space-y-1">
                  <h3 class="font-semibold text-gray-700">{{ stage.name }}</h3>
                  <div class="flex flex-wrap gap-2">
                    <div
                      v-for="key in stage.keys"
                      :key="key"
                      class="flex items-center gap-1 px-3 py-1 border rounded-full text-[12px] font-medium"
                      :class="data.validations[key] ? ' text-green-800' : ' text-red-800'"
                    >
                      <span>
                        <i v-if=" data.validations[key]" class="uil uil-check"></i>
                        <i v-else class="uil uil-times"></i>
                      </span>
                      <span>{{ key.replace(stage.name.toLowerCase()+'_', '').replace(/_/g, ' ') }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </section>
            
        </div>
          <template v-if="(auth?.user.role!=='consultant' && stateStatus(data.status,auth?.user.role)) && (data.status !=='approved')">
              <InvoiceValidate :userRole="auth?.user.role"  :invoice="data"/>
          </template>
    </AdminLayout>
</div>
</template>

<style scoped>
/* Plus lisible sur mobile */
</style>
