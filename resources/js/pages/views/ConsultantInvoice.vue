
<script setup>
import { Link, router } from '@inertiajs/vue3'
import TableComponent from '../components/TableComponent.vue';
import AdminLayout from '../../layouts/AdminLayout.vue';
import { ref, watch } from 'vue';
import InvoiceStatusBadge from '../components/InvoiceStatusBadge.vue';
import { exportToExcel } from '../../composables';

const props = defineProps({
  consultant: Object,
  urls: Object,
  filters: Object,
})

// const from = ref(props.filters.from);
// const to = ref(props.filters.to);
// const search = ref(props.filters.search);

const columns = [
  // { label: "ID", key: "id" },
  // { label: "UUID", key: "uuid" },
  // { label: "Consultant ID", key: "consultant_id" },
  { label: "Invoice Number", key: "invoice_number" },
  //  { label: "ResNo", key: "resno" },
  // { label: "Name", key: "name" },
  // { label: "Last Name", key: "last_name" },
  { label: "Amount to Pay", key: "honoraires_a_payer" },
  { label: "Status", key: "status" },
  { label: "Location", key: "location" },
  { label: "Contract From", key: "contract_period_from" },
  { label: "Contract To", key: "contract_period_to" },
  { label: "Is Forfaitaire Contract", key: "is_forfaitaire_contract" },
  { label: "Forfaitaire Amount", key: "forfaitaire_amount" },
  { label: "Monthly Fee", key: "honoraires_mensuel" },
  { label: "Days Worked", key: "jours_travailles" },
  { label: "From Date", key: "date_from" },
  { label: "To Date", key: "date_to" },
  
  // { label: "Rapport Activité Required", key: "rapport_activite_required" },
  { label: "Rapport Activité File", key: "rapport_activite_file" },
  // { label: "Clearance Required", key: "clearance_required" },
  { label: "Clearance File", key: "clearance_file" },
 
  // { label: "Email", key: "email" },
  // { label: "Phone", key: "phone" },
  // { label: "Position", key: "position" },
  // { label: "Department", key: "department" },
  { label: "Bank Name", key: "bank_name" },
  { label: "IBAN", key: "iban" },
  { label: "SWIFT Code", key: "swift_code" },

  { label: "Supervisor Period Validated", key: "supervisor_period_validated" },
  { label: "Supervisor Report Validated", key: "supervisor_report_validated" },
  { label: "Supervisor Budget Validated", key: "supervisor_budget_validated" },
  
  { label: "HR Period Validated", key: "hr_period_validated" },
  { label: "HR Report Validated", key: "hr_report_validated" },
  { label: "HR clearance Validated", key: "hr_clearance_validated" },

  { label: "Finance Period Validated", key: "finance_period_validated" },
  { label: "Finance Report Validated", key: "finance_report_validated" },
  { label: "Finance Clearance Validated", key: "finance_clearance_validated" },
  { label: "Finance Contract Validated", key: "finance_contract_validated" },

  // { label: "actions", key: "actions" },
];

// watch([from, to, search], () => {
//   router.get(props.urls.index, {
//     from: from.value,
//     to: to.value,
//     search: search.value
//   }, {
//     preserveState: true,
//     preserveScroll: true,
//     replace: true, // Évite d'empiler l'historique
//   })
// })

const clearFilters = () => {
  // from.value = ""
  // to.value = ""
  // search.value = ""
  // // console.log('====================================');
  // // console.log(props.urls.index);
  // // console.log('====================================');
  // router.get(props.urls.index, {}, {
  //   preserveState: false,
  //   preserveScroll: true,
  //   replace: true,
  // })
    router.visit('/');
}

// const applyFilters = () => {
//   router.get(props.urls.index, {
//     from: from.value,
//     to: to.value,
//     search: search.value,
//   }, {
//     preserveState: true,
//     preserveScroll: true,
//   });
// };

function remove(uuid) {
  if (!confirm('Confirmer la suppression ?')) return;
  router.delete(`/consultants/${uuid}`)
}

function goTo(page) {
  router.get(`${urls.index}?page=${page}`)
}

const selectedDate = ref("");

function updateDate(event) {
  selectedDate.value = event.target.value;
}


const exportCSV=(columns,data)=>{
  // console.log(columns);
  exportToExcel(columns.filter(item => 
    item.key !== 'actions' && 
    item.key !== 'clearance_file' && 
    item.key !== 'rapport_activite_file'
),data,'invoice.xlsx')
}
</script>

<template>
<div>
    <AdminLayout v-slot="{user}">
      <div class="p-6 max-w-full mx-auto space-y-6">

      <!-- Header + bouton Ajouter -->
      <!-- <pre>{{ consultant?.data?.invoices }}</pre> -->
          <!-- Consultant Info -->
          <section class="grid grid-cols-1 print:grid-cols-2 print:gap-4 md:grid-cols-2 gap-4  pb-4">
            <h1 class="text-2xl font-bold mb-5">Consultant</h1>
            <div><span class="font-semibold">ResNo:</span> {{ consultant?.data.resno }}</div>
            <div><span class="font-semibold">Name:</span> {{ consultant?.data.name }} {{ consultant?.data.last_name }}</div>
            <div><span class="font-semibold">Contract Period:</span> {{ consultant?.data.contract_period_from }} → {{ consultant?.data.contract_period_to }}</div>
            <div><span class="font-semibold">Email:</span> {{ consultant?.data.email }}</div>
            <div><span class="font-semibold">Phone:</span> {{ consultant?.data.phone }}</div>
            <div><span class="font-semibold">Position:</span> {{ consultant?.data.position }}</div>
            <div><span class="font-semibold">Department:</span> {{ consultant?.data.department }}</div>
            <!-- <div><span class="font-semibold">Bank:</span> {{ consultant?.data.bank_name }}</div>
            <div><span class="font-semibold">IBAN OR RIB:</span> {{ consultant?.data.iban }}</div>
            <div><span class="font-semibold">SWIFT:</span> {{ consultant?.data.swift_code }}</div> -->
          </section>
          <div class=" mb-4">
            <h1 class="text-2xl font-bold mb-5">Invoice</h1>
              <div class="flex justify-between items-center mb-4">

              <!-- Date filters -->
                <!-- <div class="flex gap-4 items-end">
                  <div>
                    <label class="block text-sm font-medium">From</label>
                    <input 
                      type="date" 
                      v-model="from" 
                      class="input input-bordered" 
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium">To</label>
                    <input 
                      type="date" 
                      v-model="to" 
                      class="input input-bordered" 
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium">Search</label>
                    <input 
                      type="search" 
                      v-model="search" 
                      class="input input-bordered w-64" 
                      placeholder="Search invoice or consultant..."
                    />
                  </div>

                  <button @click="clearFilters" class="btn btn-soft">
                    Clear
                  </button>

                </div> -->


              <!-- Search + Add -->
              <div class="flex gap-3 items-center">

                <button 
                @click="exportCSV(columns,consultant?.data?.invoices)"
                class="btn ">
                  Export <i class="uil uil-export"></i>
                </button>
                <!-- {{ user.role }} -->
                <template v-if="user.role=='consultant' || user.role=='admin'">
                    
                 <!-- <Link :href="props.urls.create" class="btn btn-soft border">
                 New Invoice
                 <i class="uil uil-plus-circle"></i>
                </Link> -->
                </template>
              </div>

            </div>

            <!-- Button filtrer -->
            <!-- <div class="mb-4">
              <button @click="applyFilters" class="btn btn-info">Filtrer</button>
            </div> -->
              <!-- <div class="flex  justify-between w-full">
                
                <div class="flex gap-2 items-center">
                   
                <div class="flex items-center gap-2">
                  <label for="invoiceDate" class="mr-2 text-sm font-medium text-gray-600">From</label>
                  <input
                  type="date"
                  id="myDatepicker"
                  v-model="selectedDate"
                  @change="updateDate"
                  placeholder="Select Date"
                  class="input input-bordered  w-full max-w-xs"
                />
                </div>
                <div class="flex items-center gap-2">
                  <label for="invoiceDate" class="mr-2 text-sm font-medium text-gray-600">To</label>
                  <input
                  type="date"
                  id="myDatepicker"
                  v-model="selectedDate"
                  @change="updateDate"
                  placeholder="Select Date"
                  class="input input-bordered  w-full max-w-xs"
                />
                </div>
                </div>
                <div class="flex gap-2 items-center">
                    <input
                    type="search"
                    id="myDatepicker"
                    placeholder="Search Invoices by reference..."
                    class="input input-bordered w-full max-w-xs"
                  />
                  <Link
                    :href="urls.create"
                    class="btn btn-primary"
                  >
                    Ajouter une facture
                  </Link>
                </div>

              </div> -->
          </div>

        <!-- Tableau -->
          <TableComponent :columns="columns" :data="consultant?.data?.invoices">
            <template #actions="{ row }">
              <div class="flex gap-2">
                <Link
                  :href="`/invoices/${row.uuid}`"
                  class="btn btn-sm "
                >
                  View
                  <i class="uil uil-eye"></i>
                </Link>
              </div>
            </template>
            <template #honoraires_a_payer="{ row }">
              <span class="text-blue-700 font-black">{{ Number(row.honoraires_a_payer) }}</span>
            </template>
            <template #status="{ row }">
              <InvoiceStatusBadge :status="row.status" />
            </template>
            <template #rapport_activite_file="{ row }">
              <div class="flex gap-2">
                <a
                v-if="row.rapport_activite_file"
                  :href="`${row.rapport_activite_file}`"
                  class="  text-blue-600 underline"
                  :download="row?.rapport_activite_file?.split('/').pop()"
                >
                  download
                  <i class="uil uil-download-alt text-[15px]"></i>
                </a>
                <span v-else>N/A</span>
              </div>
            </template>

            <template #clearance_file="{ row }">
              <div class="flex gap-2">
                <a
                 v-if="row?.clearance_file"
                  :href="`${row.clearance_file}`"
                  class=" text-blue-600 underline"
                  :download="row?.clearance_file?.split('/').pop()"
                >
                  download
                  <i class="uil uil-download-alt text-[15px]"></i>
                </a>
                <span v-else>N/A</span>
              </div>
            </template>
          </TableComponent>
      </div>
    </AdminLayout>
</div>
</template>

