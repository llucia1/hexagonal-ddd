<template>
  <div>
    <h1>Reservas</h1>

    <div>
      <label>Espacio</label>
      <select v-model="selectedSpace" @change="loadAvailability">
        <option disabled value="">Selecciona un espacio</option>
        <option v-for="space in spaces" :key="space.uuid" :value="space.uuid">
          {{ space.name }}
        </option>
      </select>

      <label>Fecha</label>
      <input type="date" v-model="selectedDate" @change="loadAvailability" />
    </div>

    <div v-if="slots.length" class="grid">
      <div
        v-for="slot in slots"
        :key="slot.Hour"
        :class="['slot', slot.status, isSelected(slot) ? 'selected' : '']"
        @click="toggleSelect(slot)"
      >
        {{ slot.Hour }}:00
      </div>
    </div>

    <button
      v-if="selectedSlots.size > 0"
      @click="reserveSelected"
      class="reserve-btn"
    >
      Reservar selección
    </button>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { fetchSpaces, fetchAvailability, reserveSlots } from '@/services/apiService';
import { generateEmptySlots } from '@/services/dateUtils';
import type { Space, Slot } from '@/interfaces/Reservation';

const spaces = ref<Space[]>([]);
const slots = ref<Slot[]>([]);
const selectedSpace = ref<string>('');
const selectedDate = ref<string>('');
const selectedSlots = ref<Set<number>>(new Set()); // guardar las horas seleccionadas

onMounted(async () => {
  spaces.value = await fetchSpaces();
});

const loadAvailability = async () => {
  if (!selectedSpace.value || !selectedDate.value) return;

  try {
    const fetchedSlots = await fetchAvailability(selectedSpace.value, selectedDate.value);

    if (fetchedSlots.length === 0) {
      slots.value = generateEmptySlots(selectedDate.value);
    } else {
      slots.value = fetchedSlots;
    }

    selectedSlots.value.clear();
  } catch (e) {
    alert(e);
  }
};

const toggleSelect = (slot: Slot) => {
  if (slot.status === 'reserved') return;

  if (selectedSlots.value.has(slot.Hour)) {
    selectedSlots.value.delete(slot.Hour);
  } else {
    selectedSlots.value.add(slot.Hour);
  }
};

const isSelected = (slot: Slot) => {
  return selectedSlots.value.has(slot.Hour);
};

const reserveSelected = async () => {
  const confirmReserve = confirm(
    `¿Reservar las horas: ${[...selectedSlots.value].join(', ')}?`
  );
  if (!confirmReserve) return;

  const updatedSlots = slots.value.map(s => {
    if (selectedSlots.value.has(s.Hour)) {
      return { ...s, status: 'reserved' };
    }
    return s;
  });

  try {
    await reserveSlots(selectedSpace.value, selectedDate.value, updatedSlots);
    await loadAvailability();
  } catch (e) {
    alert(e);
  }
};
</script>

<style scoped>
.grid {
  display: grid;
  grid-template-columns: repeat(4, 80px);
  gap: 8px;
  margin-top: 20px;
}

.slot {
  padding: 10px;
  text-align: center;
  cursor: pointer;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.slot.free {
  background-color: #e0ffe0;
}

.slot.reserved {
  background-color: #ffcccc;
  cursor: not-allowed;
}

.slot.selected {
  border: 2px solid #007bff;
  background-color: #cce5ff;
}

.reserve-btn {
  margin-top: 20px;
  padding: 10px 20px;
}
</style>
