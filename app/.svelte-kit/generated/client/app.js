export { matchers } from './matchers.js';

export const nodes = [
	() => import('./nodes/0'),
	() => import('./nodes/1'),
	() => import('./nodes/2'),
	() => import('./nodes/3'),
	() => import('./nodes/4'),
	() => import('./nodes/5'),
	() => import('./nodes/6'),
	() => import('./nodes/7'),
	() => import('./nodes/8'),
	() => import('./nodes/9'),
	() => import('./nodes/10'),
	() => import('./nodes/11'),
	() => import('./nodes/12'),
	() => import('./nodes/13'),
	() => import('./nodes/14'),
	() => import('./nodes/15'),
	() => import('./nodes/16'),
	() => import('./nodes/17'),
	() => import('./nodes/18'),
	() => import('./nodes/19'),
	() => import('./nodes/20'),
	() => import('./nodes/21'),
	() => import('./nodes/22'),
	() => import('./nodes/23'),
	() => import('./nodes/24'),
	() => import('./nodes/25'),
	() => import('./nodes/26')
];

export const server_loads = [0,2,4];

export const dictionary = {
		"/": [~6],
		"/(protected)/administration": [7,[2,3]],
		"/(protected)/administration/calendar": [~8,[2,3]],
		"/(protected)/administration/hours": [~9,[2,3]],
		"/(protected)/administration/members": [~10,[2,3]],
		"/(protected)/administration/plans": [11,[2,3,4]],
		"/(protected)/administration/plans/[id]": [12,[2,3,4]],
		"/(protected)/administration/prices": [13,[2,3]],
		"/(protected)/administration/residents": [~14,[2,3]],
		"/adoption-info": [16],
		"/auth": [~17],
		"/auth/forgot-password": [~18],
		"/auth/register": [19],
		"/calendar": [~20],
		"/cats": [~21],
		"/checkout": [22],
		"/coffee": [23],
		"/docs": [24],
		"/(protected)/member": [15,[2,5]],
		"/plans": [25],
		"/rules": [26]
	};

export const hooks = {
	handleError: (({ error }) => { console.error(error) }),

	reroute: (() => {})
};

export { default as root } from '../root.svelte';