<template>
  <form class="space-y-10 p-6" @submit.prevent="submit">
    <!-- Section: Personal Information -->
    <section class="p-6 bg-base-200 rounded-2xl space-y-4 shadow">
      <h2 class="text-xl font-semibold">Personal Information</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <input v-model="form.last_name" type="text" placeholder="Last Name" class="input input-bordered w-full" />
        <input v-model="form.first_name" type="text" placeholder="First Name" class="input input-bordered w-full" />
        <input v-model="form.phone" type="text" placeholder="Phone Number" class="input input-bordered w-full" />
        <input v-model="form.institution" type="text" placeholder="Institution" class="input input-bordered w-full" />
        <input v-model="form.position" type="text" placeholder="Job Title" class="input input-bordered w-full" />
        <input v-model="form.supervisor" type="text" placeholder="Supervisor" class="input input-bordered w-full" />
      </div>
    </section>

    <!-- Section: Bank Information -->
    <section class="p-6 bg-base-200 rounded-2xl space-y-4 shadow">
      <h2 class="text-xl font-semibold">Bank Information</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <input v-model="form.bank_name" type="text" placeholder="Bank Name" class="input input-bordered w-full" />
        <input v-model="form.iban" type="text" placeholder="IBAN" class="input input-bordered w-full" />
        <input v-model="form.swift_code" type="text" placeholder="SWIFT Code" class="input input-bordered w-full" />
      </div>
    </section>

    <!-- Section: Contract Information -->
    <section class="p-6 bg-base-200 rounded-2xl space-y-4 shadow">
      <h2 class="text-xl font-semibold">Contract Information</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <input v-model="form.location" type="text" placeholder="Location" class="input input-bordered w-full" />
        <input v-model="form.contract_period" type="text" placeholder="Contract Period" class="input input-bordered w-full" />

        <div class="form-control">
          <label class="label">Forfait Contract?</label>
          <div class="flex items-center gap-4">
            <label class="cursor-pointer flex items-center gap-2">
              <input type="radio" value="yes" v-model="form.forfait" class="radio" /> Yes
            </label>
            <label class="cursor-pointer flex items-center gap-2">
              <input type="radio" value="no" v-model="form.forfait" class="radio" /> No
            </label>
          </div>
        </div>

        <input v-if="form.forfait === 'yes'" v-model="form.forfait_amount" type="number" placeholder="Forfait Amount" class="input input-bordered w-full" />

        <input v-model="form.monthly_fees" type="number" placeholder="Monthly Fees" class="input input-bordered w-full" />
        <input v-model="form.worked_days" type="number" placeholder="Number of Days Worked" class="input input-bordered w-full" />

        <input v-model="form.period_start" type="date" class="input input-bordered w-full" />
        <input v-model="form.period_end" type="date" class="input input-bordered w-full" />

        <input v-model="form.amount_to_pay" type="number" placeholder="Amount to Pay" class="input input-bordered w-full" />
      </div>
    </section>

    <!-- Section: Documents -->
    <section class="p-6 bg-base-200 rounded-2xl space-y-4 shadow">
      <h2 class="text-xl font-semibold">Documents</h2>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="form-control">
          <label class="label">Activity Report</label>
          <select v-model="form.activity_report" class="select select-bordered w-full">
            <option value="yes">Yes</option>
            <option value="no">No</option>
          </select>
        </div>

        <div v-if="form.activity_report === 'yes'">
          <input type="file" @change="e => handleFile(e, 'activity_report_file')" class="file-input file-input-bordered w-full" />
        </div>

        <div class="form-control">
          <label class="label">Clearance Form</label>
          <select v-model="form.clearance_form" class="select select-bordered w-full">
            <option value="yes">Yes</option>
            <option value="no">No</option>
          </select>
        </div>

        <div v-if="form.clearance_form === 'yes'">
          <input type="file" @change="e => handleFile(e, 'clearance_form_file')" class="file-input file-input-bordered w-full" />
        </div>
      </div>
    </section>

    <!-- Submit -->
    <div class="flex justify-end">
      <button class="btn btn-primary">Submit Invoice</button>
    </div>
  </form>
</template>

<script setup>
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'

const form = reactive({
  last_name: '',
  first_name: '',
  phone: '',
  institution: '',
  position: '',
  supervisor: '',

  bank_name: '',
  iban: '',
  swift_code: '',

  location: '',
  contract_period: '',
  forfait: 'no',
  forfait_amount: '',

  monthly_fees: '',
  worked_days: '',
  period_start: '',
  period_end: '',
  amount_to_pay: '',

  activity_report: 'no',
  clearance_form: 'no',
  activity_report_file: null,
  clearance_form_file: null,
})

function handleFile(event, key) {
  form[key] = event.target.files[0]
}

function submit() {
  router.post('/consultant/invoice/store', form)
}
</script>
