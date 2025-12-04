<template>

  <div class="overflow-x-auto    border-base-content/5 bg-base-100">
  <table class="table">
    <!-- head -->
    <thead class="bg-zinc-50   text-gray-500">
      <tr>
        <!-- <th>#</th> -->
          <th v-for="col in columns" :key="col.key" class="font-bold">{{ col.label }}</th>
          <th
          v-if="slots.actions"
          scope="col"
          class=" bg-zinc-50   text-gray-500 sticky right-0  font-bold  "
        >
          Actions
        </th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(row, rowIndex) in data" :key="rowIndex" 
      class=" group text-nowrap font-medium text-gray-700 text-sm cursor-pointer">
          <!-- <th>{{ rowIndex + 1 }}</th> -->
          <td v-for="col in columns" :key="col.key" class="group-hover:bg-zinc-50">
            
            <slot :name="col.key" :row="row">
              {{ row[col.key] }}
            </slot>
          
          </td>
          <td
            v-if="slots.actions"
            class="sticky  right-0 group-hover:bg-zinc-50 bg-white"
          >
            <slot name="actions" :row="row" />
          </td>
        </tr>
    </tbody>
  </table>
</div>


  <!-- <div class="overflow-x-auto">
    <table class="table w-full">
      <thead class="">
        <tr>
          <th>#</th>
          <th v-for="col in columns" :key="col.key">{{ col.label }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(row, rowIndex) in data" :key="rowIndex" class="hover:bg-base-300">
          <th>{{ rowIndex + 1 }}</th>
          <td v-for="col in columns" :key="col.key">
            <slot :name="col.key" :row="row">
              {{ row[col.key] }}
            </slot>
          </td>
        </tr>
      </tbody>
    </table>
  </div> -->
</template>

<script setup>
import { useSlots } from 'vue';

defineProps({
  columns: {
    type: Array,
    default: () => [
      { key: 'name', label: 'Name' },
      { key: 'job', label: 'Job' },
      { key: 'favoriteColor', label: 'Favorite Color' }
    ]
  },
  data: {
    type: Array,
    default: () => [
      { name: 'Cy Ganderton', job: 'Quality Control Specialist', favoriteColor: 'Blue' },
      { name: 'Hart Hagerty', job: 'Desktop Support Technician', favoriteColor: 'Purple' },
      { name: 'Brice Swyre', job: 'Tax Accountant', favoriteColor: 'Red' }
    ]
  }
})

const slots = useSlots();

</script>
