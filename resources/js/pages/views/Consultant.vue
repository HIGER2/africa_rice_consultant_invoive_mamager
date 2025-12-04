
<script setup>
import { Link, router } from '@inertiajs/vue3'
import TableComponent from '../components/TableComponent.vue';
import AdminLayout from '../../layouts/AdminLayout.vue';
import { exportToExcel } from '../../composables';
import { ref, watch } from 'vue';

const props = defineProps({
  consultants: Object,
  urls: Object,
  filters: Object,
})
const search = ref(props.filters.search);

const columns = [
  { label: "ResNo", key: "resno" },
  { label: "Name", key: "name" },
  { label: "Lste name", key: "last_name" },
  { label: "Genre", key: "gender" },
  { label: "Nationalité", key: "nationality" },
  { label: "Pays de naissance", key: "country_of_birth" },
  { label: "Ville", key: "town_city" },
  { label: "Date début", key: "date_from" },
  { label: "Date fin", key: "date_to" },
  { label: "Poste", key: "position" },
  { label: "Type de ressource", key: "resource_type" },
  { label: "Niveau de poste", key: "job_level" },

  { label: "supervisor resno", key: "supervisor_resno" },
  { label: "supervisor", key: "supervisor" },
  { label: "Poste superviseur", key: "supervisor_position" },


  { label: "Cost Center", key: "costc" },
  { label: "Département", key: "department" },
  { label: "Dutypost", key: "dutypost" },
  { label: "Date embauche", key: "original_hire_date" },
  { label: "Ancienneté", key: "seniority" },
  { label: "Date naissance", key: "birthdate" },
  { label: "Nationalité à la naissance", key: "nationality_at_birth" },
  { label: "Statut marital", key: "marital_status" },
  { label: "Personnel détaché", key: "seconded_personnel" },
  { label: "Partage travail", key: "shared_working_arrangement" },
  { label: "Root", key: "root" },
  { label: "Division", key: "division" },
  { label: "Groupe", key: "group" },
  { label: "CG Unit", key: "cg_unit" },
  { label: "Sub-unit", key: "sub_unit" },
  { label: "BG Level", key: "bg_level" },
  { label: "CGIAR Workforce Group", key: "cgiar_workforce_group" },
  { label: "Dutypost Classification", key: "dutypost_classification" },
  { label: "Niveau éducation", key: "education_level" },
  { label: "Host Center", key: "host_center" },
  { label: "Hosted Personnel", key: "hosted_personnel" },
  { label: "Hosted/Seconded Personnel", key: "hosted_seconded_personnel" },
  { label: "Nationalité secondaire", key: "secondary_nationality" },
  // { label: "Banque", key: "bank_name" },
  // { label: "IBAN", key: "iban" },
  // { label: "SWIFT", key: "swift_code" },
  // { label: "", key: "actions" },
];

function remove(uuid) {
  if (!confirm('Confirmer la suppression ?')) return;
  router.delete(`/consultants/${uuid}`)
}

function goTo(page) {
  router.get(`${urls.index}?page=${page}`)
}



const exportCSV=(columns,data)=>{
  // console.log(columns);
  exportToExcel(columns.filter(item => 
    item.key !== 'actions'
),data)
}

let timeOut =null

watch([search], () => {
 timeOut = setTimeout(() => {
    router.get(props.urls.index, {
    search: search.value
      }, {
        preserveState: true,
        preserveScroll: true,
        replace: true, // Évite d'empiler l'historique
        onFinish:()=>{
          clearTimeout(timeOut)
        }
      })
 }, 400);
})
</script>

<template>
<div>
 <AdminLayout>
  
 <div class="p-6 max-w-full mx-auto space-y-6">

    <!-- Header + bouton Ajouter -->
    <div class="mb-4">
      <h1 class="text-2xl font-bold mb-6">Consultants</h1>

      <div class="flex items-end justify-between w-full">
        <div>
          <label class="block text-sm font-medium">Search</label>
          <input 
            type="search" 
            v-model="search" 
            class="input input-bordered w-64" 
            placeholder="Search  consultant..."
          />
        </div>
      <div class="flex items-center gap-4">
          <button 
        @click="exportCSV(columns,consultants.data)"
        class="btn ">
          Export <i class="uil uil-export"></i>
        </button>
      <Link
        :href="urls.create"
        class="btn btn-soft "
      >
        New consultant
          <i class="uil uil-plus-circle"></i>
      </Link>
      </div>
      </div>
    </div>

    <!-- Tableau -->
     <TableComponent :columns="columns" :data="consultants.data">
      <template #seconded_personnel="{ row }">
        <span>{{ row.seconded_personnel ? 'Oui' : 'Non' }}</span>
      </template>
      <template #shared_working_arrangement="{ row }">
        <span>{{ row.shared_working_arrangement ? 'Oui' : 'Non' }}</span>
      </template>
      <template #hosted_personnel="{ row }">
        <span>{{ row.hosted_personnel ? 'Oui' : 'Non' }}</span>
      </template>
      <template #hosted_seconded_personnel="{ row }">
        <span>{{ row.hosted_seconded_personnel ? 'Oui' : 'Non' }}</span>
      </template>
      <template #actions="{ row }">
        <div class="flex gap-2">
          <!-- <Link
            :href="`/consultants/${row.uuid}`"
            class="btn btn-sm btn-info"
          >
            Voir
          </Link> -->
          <Link
              :href="`/consultants/invoice/${row.uuid}`"
              class="btn btn-sm "
            >
              Invoice
              <i class="uil uil-eye"></i>
            </Link>
          <Link
            :href="`/consultants/${row.uuid}/edit`"
            class="btn btn-sm "
          >
            Edit
          </Link>
          <!-- <button
            @click="remove(row.uuid)"
            class="btn btn-sm btn-error"
          >
            Supprimer
          </button> -->
        </div>
      </template>
    </TableComponent>
    <!-- <div class="overflow-x-auto bg-white rounded shadow">
      <table class="table w-full">
        <thead>
          <tr>
            <th v-for="col in columns" :key="col.key">{{ col.label }}</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="c in consultants?.data" :key="c.uuid">
            <td v-for="col in columns" :key="col.key">
              {{ c[col.key] ?? '—' }}
            </td>
            <td class="flex gap-2">
              <Link
                :href="`/consultants/${c.uuid}`"
                class="btn btn-sm btn-info"
              >
                Voir
              </Link>
              <Link
                :href="`/consultants/${c.uuid}/edit`"
                class="btn btn-sm btn-warning"
              >
                Modifier
              </Link>
              <button
                @click="remove(c.uuid)"
                class="btn btn-sm btn-error"
              >
                Supprimer
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div> -->

    <!-- <div class="mt-4 flex justify-end gap-2">
      <button
        v-if="consultants.prev_page_url"
        @click="goTo(consultants.current_page - 1)"
        class="btn btn-sm"
      >
        Préc.
      </button>
      <span class="px-3 py-1 border rounded bg-gray-200">{{ consultants.current_page }}</span>
      <button
        v-if="consultants.next_page_url"
        @click="goTo(consultants.current_page + 1)"
        class="btn btn-sm"
      >
        Suiv.
      </button>
    </div> -->
  </div>
 </AdminLayout>
</div>
</template>

