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
	() => import('./nodes/21')
];

export const server_loads = [0,2];

export const dictionary = {
		"/": [~5],
		"/(protected)/administration": [6,[2,3]],
		"/(protected)/administration/calendar": [~7,[2,3]],
		"/(protected)/administration/hours": [~8,[2,3]],
		"/(protected)/administration/members": [~9,[2,3]],
		"/(protected)/administration/plans": [~10,[2,3]],
		"/(protected)/administration/prices": [11,[2,3]],
		"/(protected)/administration/residents": [~12,[2,3]],
		"/auth": [~14],
		"/auth/forgot-password": [~15],
		"/auth/register": [16],
		"/calendar": [~17],
		"/checkout": [18],
		"/coffee": [19],
		"/docs": [20],
		"/(protected)/member": [13,[2,4]],
		"/plans": [21]
	};

export const hooks = {
	handleError: (({ error }) => { console.error(error) }),

	reroute: (() => {})
};

export { default as root } from '../root.svelte';