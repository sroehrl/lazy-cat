import {PUBLIC_API_PATH} from "$env/static/public";

interface fetchObject {
    method:string,
    headers:any,
    body?:any,
    withCredentials?:boolean
}

const getOptions = (method:string, token:string|undefined, payload = null) => {
    const obj: fetchObject = {
        method: method.toUpperCase(),
        headers: {
            'Authorization': 'bearer ' + token,
            'Content-Type': 'application/json'
        }
    }
    if(token){
        obj.withCredentials = true;
        // obj.credentials = 'include'
    }

    if(payload){
        obj.body = JSON.stringify(payload)
    }
    return obj;
}


export default function (token: string|undefined) {
    const call = async (method: string, url:string, postData:any|null = null) => {
        let fetchCall;
        try{
            fetchCall = await fetch(PUBLIC_API_PATH + url, getOptions(method, token, postData))

        } catch (e: any) {
            console.log('error!!!!!!!!!')
            throw Error(e)
        }

        if(fetchCall.status >= 200 && fetchCall.status <= 299){
            return await fetchCall.json();
        } else if([400,401,403,404].includes(fetchCall.status)){
            const data = await fetchCall.json()
            throw Error(data?.message ?? data?.error)
        } else {
            throw Error(fetchCall.statusText)
        }


    }
    return {
        get: async (url: string) => call('get', url),
        post: async (url: string, postData = {}) => call('post', url, postData),
        put: async (url: string, postData = {}) => call('put', url, postData),
        delete: async (url: string) => call('delete', url),
        captureFormData:  async (request: Request) => {
            const data: FormData = await request.formData();
            const response: any = {};
            data.forEach((value, key) => {
                response[key] = value;
            })
            return response
        }
    }
}