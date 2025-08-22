import type { Space, Slot } from '@/interfaces/Reservation';
import { formatDateToBackend } from './dateUtils';

const baseURL = 'http://localhost:8000/api/v1';

export const fetchSpaces = async (): Promise<Space[]> => {
  const res = await fetch(`${baseURL}/space`);
  if (!res.ok) throw new Error('Error al obtener espacios');
  return res.json();
};

export const fetchAvailability = async (spaceUuid: string, date: string): Promise<Slot[]> => {
  const formattedDate = formatDateToBackend(date);

  const res = await fetch(
    `${baseURL}/reservation/space/${spaceUuid}/vailability?date=${encodeURIComponent(formattedDate)}`
  );
  if (!res.ok) throw new Error('Error al obtener disponibilidad');
  return res.json();
};

export const reserveSlots = async (spaceUuid: string, date: string, slots: Slot[]) => {
  const payload = {
    spaceUuid,
    date: formatDateToBackend(date),
    slots: slots.map(({ Hour, status }) => ({ hour: Hour, status })),
  };

  const res = await fetch(`${baseURL}/reservation`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(payload),
  });

  if (!res.ok) throw new Error('Error al reservar');
  return res.json();
};
