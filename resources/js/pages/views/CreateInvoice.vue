<script setup>
import { Form, router,useForm  } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import AdminLayout from '../../layouts/AdminLayout.vue'

const props = defineProps({
  consultant: Object,
  bank: Object,
  errors: Object,
  submitUrl: String,
})

// ----------------------
// BLUEPRINTS
// ----------------------

const consultantFields = [
  { label: 'Resource Number', model: 'resno', type: 'text', readonly: false ,required: true},
  { label: 'Last Name', model: 'last_name', type: 'text', readonly: false ,required: true},
  { label: 'First Name', model: 'name', type: 'text', readonly: false ,required: true},
   { label: 'Contract From', model: 'date_from', type: 'date', required: true },
  { label: 'Contract To', model: 'date_to', type: 'date', required: true },
  { label: 'Email', model: 'email', type: 'text', readonly: false ,required: false},
  { label: 'Phone', model: 'phone', type: 'text', readonly: false ,required: false},
   { label: 'Institution', model: 'institution', type: 'select', placeholder: '', required: true, options: [
    { value: 'AfricaRice', label: 'AfricaRice' },
    { value: 'CYMMIT', label: 'CYMMIT' },
  ] },
  { label: 'Job Title', model: 'position', type: 'text', readonly: true ,required: true},
//   { label: 'Supervisor', model: 'supervisor', type: 'text', readonly: false },
]

const bankFields = [
  { label: 'Bank Name', model: 'bank_name', type: 'text', readonly: false,required: true },
  { label: 'IBAN OR RIB', model: 'iban', type: 'text', readonly: false ,required: true},
  { label: 'SWIFT Code', model: 'swift_code', type: 'text', readonly: false,required: true },
]

const invoiceFields = [
  { label: 'Location', model: 'location', type: 'text', required: true },
 
  { label: 'Monthly Fees', model: 'honoraires_mensuel', type: 'number', required: false },
  { label: 'Days Worked', model: 'jours_travailles', type: 'number', required: true },
  { label: ' Worked From Date', model: 'date_from', type: 'date', required: true },
  { label: 'Worked To Date', model: 'date_to', type: 'date', required: true },
  { label: 'Amount to Pay', model: 'honoraires_a_payer', type: 'number', required: true },
]

// ----------------------
// FORM INITIALIZATION
// ----------------------

const processing = ref(false);
const form = useForm({
  consultant: {
    resno: props.consultant?.resno ?? '',
    name: props.consultant?.name ?? '',
    last_name: props.consultant?.last_name ?? '',
    email: props.consultant?.email ?? '',
    phone: props.consultant?.phone ?? '',
    institution: props.consultant?.institution ?? '',
    position: props.consultant?.position ?? '',
    date_from: props.consultant?.date_from ?? '',
    date_to: props.consultant?.date_to ?? '',
  },

  bank: {
    bank_name: props.consultant?.bank_details?.bank_name ?? '',
    iban: props.consultant?.bank_details?.iban ?? '',
    swift_code: props.consultant?.bank_details?.swift_code ?? '',
  },

  invoice: {
    location: '',
    honoraires_mensuel: '',
    jours_travailles: '',
    date_from:  '',
    date_to:  '',
    honoraires_a_payer: '',
    is_forfaitaire_contract: 0,
    forfaitaire_amount: '',
    rapport_activite_required: 1,
    rapport_activite_file: null,  // ← Laissez null pour les fichiers
    clearance_required: 0,
    clearance_file: null,
  }
})

const upload = (e, key) => {
  form.invoice[key] = e.target.files[0]
}
const handleSubmit = () => {
  if (!confirm('Are you sure you want to continue?')) {
    return;
  }
    console.log('Form data:', form.data()); // ← Voyez exactement ce qui sera envoyé
    processing.value = true
    form.post('create', {
      forceFormData: true,
      onSuccess: (data) => {
        alert("Invoice created successfully")
        console.log('Invoice created successfully', data);
      },
      onError: (errors) => {
        console.log("Les erreurs retournées :", errors);
      },
      onFinish:()=>{
        processing.value = false

      }
    })
}


// Cancel and return
const cancel = () => {
  router.visit('/');
};

</script>

<template>
    <div>
    <AdminLayout>
      <form
        @submit.prevent="handleSubmit"
      >
          <div class="p-6 space-y-6 max-w-3xl mx-auto">
        <!-- <pre>{{ consultant }}</pre> -->
        <!-- ============================= -->
        <!-- SECTION 1 – CONSULTANT INFO -->
        <!-- ============================= -->
        <h1 class="text-2xl font-bold"> New Invoice</h1>
          <!-- <pre> {{ form.errors }}</pre> -->
        <div class="card bg-base-100 border-gray-200 border ">
          <div class="card-body">
            <h2 class="card-title text-lg font-bold">Consultant Information</h2>
            <p class="text-sm opacity-70 mb-4">These fields come from the consultant profile.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div v-for="f in consultantFields" :key="f.model" class="form-control flex flex-col">
                <label class="label">
                  <span class="label-text">{{ f.label }}</span>
                </label>
                <select
                    v-if="f.type === 'select'"
                    v-model="form.consultant[f.model]"
                    :required="f.required"
                    class="select select-bordered w-full"
                  >
                    <option value="" disabled>Select...</option>
                    <option v-for="opt in f.options" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                  </select>
                <input
                v-else
                :placeholder="f.label"
                  :type="f.type"
                  class="input "
                  :required="f.required"
                  v-model="form.consultant[f.model]"
                />
                <p v-if="form.errors[`consultant.${f.model}`]" class="text-error text-[11px]">
                  {{ form.errors[`consultant.${f.model}`] }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- ============================= -->
        <!-- SECTION 2 – BANK DETAILS -->
        <!-- ============================= -->
        <div class="card bg-base-100 border-gray-200 border">
          <div class="card-body">
            <h2 class="card-title text-lg font-bold">Bank Details</h2>
            <p class="text-sm opacity-70 mb-4">Also readonly — but will be updated on save.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div v-for="f in bankFields" :key="f.model" class="form-control">
                <label class="label">
                  <span class="label-text">{{ f.label }}</span>
                </label>
                <input
                  :type="f.type"
                  class="input "
                  :placeholder="f.label"
                  v-model="form.bank[f.model]"
                />
                <p v-if="form.errors[`bank.${f.model}`]" class="text-error text-[11px]">
                  {{ form.errors[`bank.${f.model}`] }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- ============================= -->
        <!-- SECTION 3 – INVOICE DETAILS -->
        <!-- ============================= -->
        <div class="card bg-base-100 border-gray-200 border">
          <div class="card-body">
            <h2 class="card-title text-lg font-bold">Invoice Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div v-for="f in invoiceFields" :key="f.model" class="form-control">
                <label class="label">
                  <span class="label-text">
                    {{ f.label }}
                    <span v-if="f.required" class="text-error">*</span>
                  </span>
                </label>
                <input
                  :type="f.type"
                  v-model="form.invoice[f.model]"
                  class="input input-bordered"
                  :placeholder="f.label"
                  :required="f.required"
                />
                <p v-if="form.errors[`invoice.${f.model}`]" class="text-error text-[11px]">
                  {{ form.errors[`invoice.${f.model}`] }}
                </p>
              </div>
            </div>

            <!-- CONTRACT TYPE -->
            <div class="mt-6">
              <p class="font-semibold mb-2">Forfaitaire Contract?</p>
              <div class="flex items-center gap-6">
                <label class="flex items-center gap-2">
                  <input type="radio" value="1" v-model="form.invoice.is_forfaitaire_contract" class="radio" />
                  <span>Yes</span>
                </label>
                <label class="flex items-center gap-2">
                  <input type="radio" value="0" v-model="form.invoice.is_forfaitaire_contract" class="radio" />
                  <span>No</span>
                </label>
              </div>

              <div v-if="form.invoice.is_forfaitaire_contract == 1" class="mt-4 form-control w-full md:w-1/3">
                <label class="label"><span class="label-text">Forfaitaire Amount</span></label>
                <input type="number" class="input input-bordered" v-model="form.invoice.forfaitaire_amount" />
                <p v-if="form.errors[`invoice.forfaitaire_amount`]" class="text-error text-[11px]">
                  {{ form.errors[`invoice.forfaitaire_amount`] }}
                </p>
              </div>
            </div>

          </div>
        </div>

        <!-- ============================= -->
        <!-- SECTION 4 – DOCUMENTS -->
        <!-- ============================= -->
        <div class="card bg-base-100 border-gray-200 border">
          <div class="card-body">
            <h2 class="card-title text-lg font-bold">Required Documents</h2>

            <!-- RAPPORT ACTIVITE -->
            <div class="mt-4">
              <p class="font-semibold mb-2">Activity Report</p>
              <div class="flex items-center gap-6">
                <label class="flex items-center gap-2">
                  <input type="radio" value="1" v-model="form.invoice.rapport_activite_required" class="radio" />
                  <span>Yes</span>
                </label>
                <label class="flex items-center gap-2">
                  <input type="radio" value="0" v-model="form.invoice.rapport_activite_required" class="radio" />
                  <span>No</span>
                </label>
              </div>

              <div v-if="form.invoice.rapport_activite_required == 1" class="mt-3">
                <input type="file" accept=".pdf,.doc,.docx" class="file-input file-input-bordered w-full" @change="e => upload(e, 'rapport_activite_file')" />
                <p v-if="form.errors[`invoice.rapport_activite_file`]" class="text-error text-[11px]">
                  {{ form.errors[`invoice.rapport_activite_file`] }}
                </p>
              </div>
            </div>

            <!-- CLEARANCE -->
            <div class="mt-6">
              <p class="font-semibold mb-2">Clearance Form</p>
              <div class="flex items-center gap-6">
                <label class="flex items-center gap-2">
                  <input type="radio" value="1" v-model="form.invoice.clearance_required" class="radio" />
                  <span>Yes</span>
                </label>
                <label class="flex items-center gap-2">
                  <input type="radio" value="0" v-model="form.invoice.clearance_required" class="radio" />
                  <span>No</span>
                </label>
              </div>

              <div v-if="form.invoice.clearance_required == 1" class="mt-3">
                <input type="file" accept=".pdf,.doc,.docx" class="file-input file-input-bordered w-full" @change="e => upload(e, 'clearance_file')" />
                <p v-if="form.errors[`invoice.clearance_file`]" class="text-error text-[11px]">
                  {{ form.errors[`invoice.clearance_file`] }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- SUBMIT -->
        <div class="flex justify-end gap-4 items-center">
          <button
              type="button"
              @click="cancel"
              class="btn btn-outline"
            >
              Cancel
            </button>
          <button :disabled="processing"
           type="submit" class="btn bg-primarys text-white border disabled:opacity-50">
          {{ processing ? 'Processing...' : 'Submit Invoice' }}
          </button>
        </div>

      </div>
      </form>
    </AdminLayout>
    </div>
</template>
