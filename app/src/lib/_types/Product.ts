export interface ProductAddon{
    id: number;
    name: string;
    description: string;
    addonPrice: number;
}

export interface ProductSize{
    id: number;
    size: string;
    price: number;
}
export interface Product{
    id: number;
    name: string;
    description: string;
    imageLocation: string;
    price: number;
    type: string;
    customization?: string;
    paws?: number;
    addons: ProductAddon[];
    sizes: ProductSize[]
}