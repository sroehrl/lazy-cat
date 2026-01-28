export type ResidentStatus = 'available' | 'pending' | 'adopted';
export type ResidentGender = 'male' | 'female';

export interface Resident {
    id?: number;
    name: string;
    gender: ResidentGender;
    breed?: string;
    color?: string;
    description: string;
    image?: string;
    status: ResidentStatus;
    bornAt?: string;
    createdAt?: string;
    updatedAt?: string;
}
