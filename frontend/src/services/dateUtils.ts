import type { Slot } from '@/interfaces/Reservation';


export const formatDateToBackend = (date: string): string => {
  const [year, month, day] = date.split('-');
  return `${day}/${month}/${year}`;
};
export const formatDateFromBackend = (date: string): string => {
  const [day, month, year] = date.split('/');
  return `${year}-${month}-${day}`;
};


export const generateEmptySlots = (date: string): Slot[] => {
  // 📌 estos valores en el futuro se traerán del backend
  const hoursMax = 13;    // número de franjas
  const init = 9;         // hora inicial
  const increment = 1;    // salto entre franjas
  return Array.from({ length: hoursMax }, (_, i) => ({
    date: formatDateToBackend(date),
    Hour: init + i * increment,
    status: 'free'
  }));
};
