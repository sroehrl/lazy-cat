
export const formatCurrency = (value: number, currency: string) => {
    let format = ['en-US', 'USD']
    if(currency === 'EUR'){
        format = ['de-DE', 'EUR']
    }
    return new Intl.NumberFormat(format[0], {
        style: 'currency',
        currency: format[1],
    }).format(value);
}