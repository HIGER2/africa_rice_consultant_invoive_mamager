<template>
 <div>
   <AdminLayout>
    <div class=" py-8">
      <!-- <pre> {{ consultant }}</pre> -->
      <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-4xl font-bold text-gray-900 mb-2">
            {{ isEdit ? 'Edit Consultant' : 'Add Consultant' }}
          </h1>
          <p class="text-gray-600">{{ isEdit ? 'Update consultant information' : 'Create a new consultant profile' }}</p>
        </div>

        <!-- Form -->
        <form @submit.prevent="submitForm" class="space-y-8">
          <!-- <pre>{{ page.props }}</pre> -->
          <!-- Personal Information -->
          <div class="card bg-white border border-gray-200">
            <div class="card-body">
              <h2 class="card-title text-lg font-bold mb-4">Personal Information</h2>
              <div class="grid grid-cols-1 items-center md:grid-cols-2 lg:grid-cols-3 gap-4">
                <template v-for="field in personalFields" :key="field.model">
                  <div class="form-control w-full">
                    <label class="label">
                      <span class="label-text font-medium">
                        {{ field.label }}
                        <span v-if="field.required" class="text-error">*</span>
                      </span>
                    </label>
                    <select
                      v-if="field.type === 'select'"
                      :value="consultantForm[field.model]"
                      @change="consultantForm[field.model] = $event.target.value"
                      :required="field.required"
                      class="select select-bordered w-full"
                      :class="{ 'input-error': errors[field.model] }"
                    >
                      <option value="" disabled>Select...</option>
                      <option v-for="opt in field.options" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                    </select>
                    <input
                      v-else-if="field.type !== 'checkbox'"
                      :type="field.type"
                      :value="consultantForm[field.model]"
                      @input="consultantForm[field.model] = $event.target.value"
                      :placeholder="field.placeholder || ''"
                      :required="field.required && field.type !== 'checkbox'"
                      class="input input-bordered w-full"
                      :class="{ 'input-error': errors[field.model] }"
                    />
                    <input
                      v-else
                      type="checkbox"
                      :checked="consultantForm[field.model]"
                      @change="consultantForm[field.model] = $event.target.checked"
                      class="checkbox checkbox-primary"
                    />
                    <label v-if="errors[field.model]" class="label">
                      <span class="label-text-alt text-error">{{ errors[field.model] }}</span>
                    </label>
                  </div>
                </template>
              </div>
            </div>
          </div>

          <!-- Professional Information -->
          <div class="card bg-white border border-gray-200">
            <div class="card-body">
              <h2 class="card-title text-lg font-bold mb-4">Professional Information</h2>
              <div class="grid grid-cols-1 items-center md:grid-cols-2 lg:grid-cols-3 gap-4">
                <template v-for="field in professionalFields" :key="field.model">
                  <div class="form-control w-full">
                    <label class="label">
                      <span class="label-text font-medium">
                        {{ field.label }}
                        <span v-if="field.required" class="text-error">*</span>
                      </span>
                    </label>
                    <select
                      v-if="field.type === 'select'"
                      :value="consultantForm[field.model]"
                      @change="consultantForm[field.model] = $event.target.value"
                      :required="field.required && field.type !== 'checkbox'"
                      class="select select-bordered w-full"
                      :class="{ 'input-error': errors[field.model] }"
                    >
                      <option value="" disabled>Select...</option>
                      <option v-for="opt in field.options" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                    </select>
                    <input
                      v-else-if="field.type !== 'checkbox'"
                      :type="field.type"
                      :value="consultantForm[field.model]"
                      @input="consultantForm[field.model] = $event.target.value"
                      :placeholder="field.placeholder || ''"
                      :required="field.required && field.type !== 'checkbox'"
                      :min="field.min"
                      :step="field.step"
                      class="input input-bordered w-full"
                      :class="{ 'input-error': errors[field.model] }"
                    />
                    <input
                      v-else
                      type="checkbox"
                      :checked="consultantForm[field.model]"
                      @change="consultantForm[field.model] = $event.target.checked"
                      class="checkbox checkbox-primary"
                    />
                    <label v-if="errors[field.model]" class="label">
                      <span class="label-text-alt text-error">{{ errors[field.model] }}</span>
                    </label>
                  </div>
                </template>
              </div>
            </div>
          </div>

          <!-- Supervisor -->
          <div class="card bg-white border border-gray-200">
            <div class="card-body">
              <h2 class="card-title text-lg font-bold mb-4">Supervisor</h2>
              <div class="grid grid-cols-1 items-center md:grid-cols-2 lg:grid-cols-3 gap-4">
                <template v-for="field in supervisorFields" :key="field.model">
                  <div class="form-control w-full">
                    <label class="label">
                      <span class="label-text font-medium">
                        {{ field.label }}
                        <span v-if="field.required" class="text-error">*</span>
                      </span>
                    </label>
                    <!-- {{ supervisorForm[field.model] }} -->
                    <select
                      v-if="field.type === 'select'"
                      :value="consultantForm[field.model]"
                      @change="consultantForm[field.model] = $event.target.value"
                      :required="field.required && field.type !== 'checkbox'"
                      class="select select-bordered w-full"
                      :class="{ 'input-error': errors[field.model] }"
                    >
                      <option value="" disabled>Select...</option>
                      <option v-for="opt in field.options" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                    </select>
                    
                    <input
                      v-else-if="field.type !== 'checkbox'"
                      :type="field.type"
                      :value="supervisorForm[field.model]"
                      @input="supervisorForm[field.model] = $event.target.value"
                      :placeholder="field.placeholder || ''"
                      :required="field.required && field.type !== 'checkbox'"
                      :min="field.min"
                      :step="field.step"
                      class="input input-bordered w-full"
                      :class="{ 'input-error': errors[field.model] }"
                    />
                    
                    <input
                      v-else
                      type="checkbox"
                      :checked="consultantForm[field.model]"
                      @change="consultantForm[field.model] = $event.target.checked"
                      class="checkbox checkbox-primary"
                    />
                    <label v-if="errors[field.model]" class="label">
                      <span class="label-text-alt text-error">{{ errors[field.model] }}</span>
                    </label>
                  </div>
                </template>
              </div>
            </div>
          </div>

          <!-- Organizational Structure -->
          <div class="card bg-white border border-gray-200">
            <div class="card-body">
              <h2 class="card-title text-lg font-bold mb-4">Organizational Structure</h2>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <template v-for="field in organizationFields" :key="field.model">
                  <div class="form-control w-full">
                    <label class="label">
                      <span class="label-text font-medium">
                        {{ field.label }}
                        <span v-if="field.required" class="text-error">*</span>
                      </span>
                    </label>
                    <select
                      v-if="field.type === 'select'"
                      :value="consultantForm[field.model]"
                      @change="consultantForm[field.model] = $event.target.value"
                      :required="field.required && field.type !== 'checkbox'"
                      class="select select-bordered w-full"
                      :class="{ 'input-error': errors[field.model] }"
                    >
                      <option value="" disabled>Select...</option>
                      <option v-for="opt in field.options" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                    </select>
                    <input
                      v-else-if="field.type !== 'checkbox'"
                      :type="field.type"
                      :value="consultantForm[field.model]"
                      @input="consultantForm[field.model] = $event.target.value"
                      :placeholder="field.placeholder || ''"
                      :required="field.required && field.type !== 'checkbox'"
                      class="input input-bordered w-full"
                      :class="{ 'input-error': errors[field.model] }"
                    />
                    <label v-if="errors[field.model]" class="label">
                      <span class="label-text-alt text-error">{{ errors[field.model] }}</span>
                    </label>
                  </div>
                </template>
              </div>
            </div>
          </div>

          <!-- Hosting Information -->
          <div class="card bg-white border border-gray-200">
            <div class="card-body">
              <h2 class="card-title text-lg font-bold mb-4">Hosting Information</h2>
              <div class="grid grid-cols-1 items-center md:grid-cols-2 lg:grid-cols-3 gap-4">
                <template v-for="field in hostingFields" :key="field.model">
                  <div class="form-control w-full">
                    <label class="label">
                      <span class="label-text font-medium">
                        {{ field.label }}
                        <span v-if="field.required" class="text-error">*</span>
                      </span>
                    </label>
                    <select
                      v-if="field.type === 'select'"
                      :value="consultantForm[field.model]"
                      @change="consultantForm[field.model] = $event.target.value"
                      :required="field.required && field.type !== 'checkbox'"
                      class="select select-bordered w-full"
                      :class="{ 'input-error': errors[field.model] }"
                    >
                      <option value="" disabled>Select...</option>
                      <option v-for="opt in field.options" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                    </select>
                    <input
                      v-else-if="field.type !== 'checkbox'"
                      :type="field.type"
                      :value="consultantForm[field.model]"
                      @input="consultantForm[field.model] = $event.target.value"
                      :placeholder="field.placeholder || ''"
                      :required="field.required && field.type !== 'checkbox'"
                      class="input input-bordered w-full"
                      :class="{ 'input-error': errors[field.model] }"
                    />
                    <input
                      v-else
                      type="checkbox"
                      :checked="consultantForm[field.model]"
                      @change="consultantForm[field.model] = $event.target.checked"
                      class="checkbox checkbox-primary"
                    />
                    <label v-if="errors[field.model]" class="label">
                      <span class="label-text-alt text-error">{{ errors[field.model] }}</span>
                    </label>
                  </div>
                </template>
              </div>
            </div>
          </div>

          <!-- Bank Details -->
          <div class="card bg-white border border-gray-200">
            <div class="card-body">
              <h2 class="card-title text-lg font-bold mb-4">Bank Details</h2>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-control w-full">
                  <label class="label">
                    <span class="label-text font-medium">Bank Name <span class="text-error">*</span></span>
                  </label>
                  <input
                    type="text"
                    :value="bankForm.bank_name"
                    @input="bankForm.bank_name = $event.target.value"
                    placeholder="Enter bank name"
                    :required="true"
                    class="input input-bordered w-full"
                    :class="{ 'input-error': errors.bank_name }"
                  />
                  <label v-if="errors.bank_name" class="label">
                    <span class="label-text-alt text-error">{{ errors.bank_name }}</span>
                  </label>
                </div>
                <div class="form-control w-full">
                  <label class="label">
                    <span class="label-text font-medium">IBAN <span class="text-error">*</span></span>
                  </label>
                  <input
                    type="text"
                    :value="bankForm.iban"
                    @input="bankForm.iban = $event.target.value"
                    placeholder="Enter IBAN"
                    :required="true"
                    class="input input-bordered w-full"
                    :class="{ 'input-error': errors.iban }"
                  />
                  <label v-if="errors.iban" class="label">
                    <span class="label-text-alt text-error">{{ errors.iban }}</span>
                  </label>
                </div>
                <div class="form-control w-full">
                  <label class="label">
                    <span class="label-text font-medium">SWIFT Code <span class="text-error">*</span></span>
                  </label>
                  <input
                    type="text"
                    :value="bankForm.swift_code"
                    @input="bankForm.swift_code = $event.target.value"
                    placeholder="Enter SWIFT code"
                    :required="true"
                    class="input input-bordered w-full"
                    :class="{ 'input-error': errors.swift_code }"
                  />
                  <label v-if="errors.swift_code" class="label">
                    <span class="label-text-alt text-error">{{ errors.swift_code }}</span>
                  </label>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex gap-4 justify-end">
            <button
              type="button"
              @click="cancel"
              class="btn btn-outline"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="processing"
              class="btn btn-primary gap-2"
            >
              <span v-if="processing" class="loading loading-spinner loading-sm"></span>
              <span>{{ isEdit ? 'Update' : 'Create' }}</span>
            </button>
          </div>
        </form>
      </div>
  </div>
   </AdminLayout>
 </div>
</template>

<script setup>
import { ref, reactive, watch, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import AdminLayout from '../../layouts/AdminLayout.vue';

const props = defineProps({
  consultant: {
    type: Object,
    default: () => ({}),
  },
  isEdit: Boolean,
  actionUrl: String,
  method: { type: String, default: 'post' },
  cancelUrl: { type: String, default: '/' },
});

const page = usePage();
const processing = ref(false);
let errors = reactive({});
const consultant =props.consultant.data
// Watch pour mettre à jour les erreurs depuis Laravel
watch(
  () => page.props.errors,
  (newErrors) => {
    Object.assign(errors, newErrors);
  },
  { deep: true }
);

// Initialisation des données du consultant
const getConsultantInitialData = () => ({
  resno: consultant?.resno ?? '',
  name: consultant?.name ?? '',
  last_name: consultant?.last_name ?? '',
  // phone: consultant?.phone ?? '',
  email: consultant?.email ?? '',
  email_cgiar: consultant?.email_cgiar ?? '',
  gender: consultant?.gender ?? '',
  nationality: consultant?.nationality ?? '',
  country_of_birth: consultant?.country_of_birth ?? '',
  town_city: consultant?.town_city ?? '',
  date_from: consultant?.date_from ?? '',
  date_to: consultant?.date_to ?? '',
  birthdate: consultant?.birthdate ?? '',
  nationality_at_birth: consultant?.nationality_at_birth ?? '',
  marital_status: consultant?.marital_status ?? '',
  secondary_nationality: consultant?.secondary_nationality ?? '',
  position: consultant?.position ?? '',
  resource_type: consultant?.resource_type ?? '',
  job_level: consultant?.job_level ?? '',
  // supervisor_email: consultant?.supervisor_email ?? 'm.akpoffo@cgiar.org',
  costc: consultant?.costc ?? '',
  department: consultant?.department ?? '',
  dutypost: consultant?.dutypost ?? '',
  original_hire_date: consultant?.original_hire_date ?? '',
  seniority: consultant?.seniority ?? '',
  education_level: consultant?.education_level ?? '',
  seconded_personnel: consultant?.seconded_personnel ?? false,
  shared_working_arrangement: consultant?.shared_working_arrangement ?? false,
  root: consultant?.root ?? '',
  division: consultant?.division ?? '',
  group: consultant?.group ?? '',
  cg_unit: consultant?.cg_unit ?? '',
  sub_unit: consultant?.sub_unit ?? '',
  bg_level: consultant?.bg_level ?? '',
  cgiar_workforce_group: consultant?.cgiar_workforce_group ?? '',
  dutypost_classification: consultant?.dutypost_classification ?? '',
  host_center: consultant?.host_center ?? '',
  hosted_personnel: consultant?.hosted_personnel ?? false,
  hosted_seconded_personnel: consultant?.hosted_seconded_personnel ?? false,
});

// Initialisation des données bancaires
const getBankInitialData = () => ({
  bank_name: consultant?.bank_details?.bank_name ?? '',
  iban: consultant?.bank_details?.iban ?? '',
  swift_code: consultant?.bank_details?.swift_code ?? '',
});


// Initialisation des données bancaires
const getSupervisorInitialData = () => ({
  resno: consultant?.supervisor?.resno ?? '',
  name: consultant?.supervisor?.name ?? '',
  last_name: consultant?.supervisor?.last_name ?? '',
  email: consultant?.supervisor?.email ?? '',
  position: consultant?.supervisor?.position ?? '',
});

// Formes réactives
const consultantForm = reactive(getConsultantInitialData());
const bankForm = reactive(getBankInitialData());
const supervisorForm = reactive(getSupervisorInitialData());

const personalFields = [
  { label: 'Resource Number', model: 'resno', type: 'text', placeholder: 'Enter resource number', required: true },
  { label: 'First Name', model: 'name', type: 'text', placeholder: 'Enter first name', required: true },
  { label: 'Last Name', model: 'last_name', type: 'text', placeholder: 'Enter last name', required: true },
  // { label: 'Phone', model: 'phone', type: 'text', placeholder: 'Enter phone number', required: true },
  { label: 'Email', model: 'email', type: 'email', placeholder: 'Enter email', required: true },
  { label: 'Gender', model: 'gender', type: 'select', placeholder: '', required: true, options: [
    { value: 'Male', label: 'Male' },
    { value: 'Female', label: 'Female' },
    { value: 'Other', label: 'Other' },
  ] },
  { label: 'Birth Date', model: 'birthdate', type: 'date', placeholder: '', required: true },
  { label: 'Nationality', model: 'nationality', type: 'text', placeholder: 'Enter nationality', required: true },
  { label: 'Nationality at Birth', model: 'nationality_at_birth', type: 'text', placeholder: 'Enter nationality', required: true },
  { label: 'Secondary Nationality', model: 'secondary_nationality', type: 'text', placeholder: 'Enter nationality', required: true },
  { label: 'Country of Birth', model: 'country_of_birth', type: 'text', placeholder: 'Enter country', required: true },
  { label: 'City/Town', model: 'town_city', type: 'text', placeholder: 'Enter city/town', required: true },
  { label: 'Marital Status', model: 'marital_status', type: 'text', placeholder: 'e.g., Single, Married', required: true },
];

const professionalFields = [
  { label: 'Position', model: 'position', type: 'text', placeholder: 'Enter position', required: true },
  { label: 'Email cgiar', model: 'email_cgiar', type: 'email', placeholder: 'Enter CGIAR email', required: false },
  { label: 'Resource Type', model: 'resource_type', type: 'text', placeholder: 'Enter resource type', required: true },
  { label: 'Job Level', model: 'job_level', type: 'text', placeholder: 'Enter job level', required: true },
  { label: 'Cost Centre', model: 'costc', type: 'text', placeholder: 'Enter cost centre', required: true },
  { label: 'Department', model: 'department', type: 'text', placeholder: 'Enter department', required: true },
  { label: 'Duty Post', model: 'dutypost', type: 'text', placeholder: 'Enter duty post', required: true },
  { label: 'Duty Post Classification', model: 'dutypost_classification', type: 'text', placeholder: 'Enter classification', required: true },
  { label: 'Original Hire Date', model: 'original_hire_date', type: 'date', placeholder: '', required: true },
  { label: 'Seniority (Years)', model: 'seniority', type: 'number', placeholder: 'Enter seniority (in years)', required: true, min: 0, step: 1 },
  { label: 'Education Level', model: 'education_level', type: 'text', placeholder: 'e.g., Bachelor, Master', required: true },
  { label: 'Date From', model: 'date_from', type: 'date', placeholder: '', required: true },
  { label: 'Date To', model: 'date_to', type: 'date', placeholder: '', required: true },
  { label: 'Seconded Personnel', model: 'seconded_personnel', type: 'checkbox', placeholder: '', required: true },
  { label: 'Shared Working Arrangement', model: 'shared_working_arrangement', type: 'checkbox', placeholder: '', required: true },
];

const organizationFields = [
  { label: 'Root', model: 'root', type: 'text', placeholder: 'Enter root', required: true },
  { label: 'Division', model: 'division', type: 'text', placeholder: 'Enter division', required: true },
  { label: 'Group', model: 'group', type: 'text', placeholder: 'Enter group', required: true },
  { label: 'CG Unit', model: 'cg_unit', type: 'text', placeholder: 'Enter CG unit', required: true },
  { label: 'Sub Unit', model: 'sub_unit', type: 'text', placeholder: 'Enter sub unit', required: true },
  { label: 'BG Level', model: 'bg_level', type: 'text', placeholder: 'Enter BG level', required: true },
  { label: 'CGIAR Workforce Group', model: 'cgiar_workforce_group', type: 'text', placeholder: 'Enter workforce group', required: true },
];

const supervisorFields = [
  { label: 'Resno', model: 'resno', type: 'text', placeholder: 'Enter supervisor resno', required: true },
  { label: 'Name', model: 'name', type: 'text', placeholder: 'Enter supervisor name', required: true },
  { label: 'Last name', model: 'last_name', type: 'text', placeholder: 'Enter supervisor last name', required: true },
  { label: 'Supervisor Email', model: 'email', type: 'email', placeholder: 'Enter supervisor email', required: true },
  { label: 'Position', model: 'position', type: 'text', placeholder: 'Enter supervisor position', required: true },
];

const hostingFields = [
  { label: 'Host Centre', model: 'host_center', type: 'text', placeholder: 'Enter host centre', required: true },
  { label: 'Hosted Personnel', model: 'hosted_personnel', type: 'checkbox', placeholder: '', required: true },
  { label: 'Hosted Seconded Personnel', model: 'hosted_seconded_personnel', type: 'checkbox', placeholder: '', required: true },
];

// Form submission
const submitForm = async () => {
  processing.value = true;
  
  try {
    // Merge both forms into a single flat object
    const payload = {
      ...consultantForm,
      ...bankForm,
      supervisor: { ...supervisorForm },
    };

    // Coerce numeric fields as expected
    if (payload.seniority !== undefined && payload.seniority !== null && payload.seniority !== '') {
      const n = parseInt(payload.seniority, 10);
      payload.seniority = Number.isNaN(n) ? 0 : Math.max(0, n);
    } else {
      payload.seniority = 0;
    }

    // Determine action and method
    const action = props.actionUrl || (props.isEdit ? `consultants/${props.consultant?.uuid}` : 'consultants');
    const method = props.isEdit ? 'put' : 'post';

    errors={}
    // Submit form
    if (method === 'put') {
      await router.put(action, payload);
    } else {
      await router.post(action, payload);
    }
  } catch (error) {
    console.error('Error during submission:', error);
    // Errors will be updated via watch on page.props.errors
  } finally {
    processing.value = false;
  }
};

// Cancel and return
const cancel = () => {
  router.visit(props.cancelUrl || '/consultants');
};
</script>
