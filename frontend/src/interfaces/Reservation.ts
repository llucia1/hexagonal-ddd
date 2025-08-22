export interface Space {
  uuid: string;
  name: string;
}

export interface Slot {
  uuid?: string;
  date: string;
  Hour: number;
  status: 'free' | 'reserved';
}