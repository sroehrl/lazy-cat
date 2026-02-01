import { dev } from '$app/environment';

export const handle = async ({ event, resolve }) => {
    if (dev && event.url.pathname === '/.well-known/appspecific/com.chrome.devtools.json') {
        return new Response(null, { status: 404 });
    }
    return resolve(event);
};   