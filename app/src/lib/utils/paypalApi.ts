import {PRIVATE_PAYPAL_CLIENTID, PRIVATE_PAYPAL_SECRET} from "$env/static/private";

const call = async (method: string, url:string, postData:any|null = null) => {
    const call = await fetch('https://api-m.sandbox.paypal.com/v1' + url, {
        method,
        headers: {
            'Authorization': 'bearer ' + PayPalApi.token,
            'Content-Type': 'application/json',
            'Prefer': 'return=representation'
        },
        body: postData ? JSON.stringify(postData) : undefined
    })
    if(call.status >= 200 && call.status <= 299){
        return await call.json();
    } else {
        throw Error(call.statusText)
    }

}

const PayPalApi = {
    token: '',
    auth: async () => {
        const call = await fetch('https://api-m.sandbox.paypal.com/v1/oauth2/token?grant_type=client_credentials', {
            method: 'POST',
            headers: {
                ContentType: 'application/x-www-form-urlencoded',
                Accept: 'application/json',
                Authorization: 'Basic ' + btoa(PRIVATE_PAYPAL_CLIENTID + ':'+ PRIVATE_PAYPAL_SECRET)
            }
        })
        const tokenData = await call.json();
        console.log({tokenData})
        PayPalApi.token = tokenData.access_token;
    },
    get: async (url: string)=> call('get', url),
    patch: async (url: string, payload: any)=> call('patch', url, payload),
    post: async (url: string, payload: any)=> call('get', url, payload),
}
export default PayPalApi