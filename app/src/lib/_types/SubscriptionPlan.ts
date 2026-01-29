import type LenkradDate from "./LenkradDate";

export interface SubscriptionPlan {
    id?: number;
    name: string;
    description: string;
    pricePerMonth: number;
    visitationDiscount: number;
    pawsPerMonth: number;
    weekdays: boolean;
    weekends: boolean;
    guest: boolean;
    createdAt: LenkradDate;
    updatedAt: LenkradDate;
}