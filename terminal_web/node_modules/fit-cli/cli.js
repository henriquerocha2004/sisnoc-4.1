#!/usr/bin/env node
const path = require('path');
const isDev = process.argv.find ((item) => item === '-l');

let	fit = isDev ?
	path.join (process.cwd(), '..', 'fit-core') :
	path.join (process.cwd(), 'node_modules', 'fit-core');

try {
	require.resolve (fit);

	let core = require(fit);

	core.config.init()
		.then(() => {
			return core.install();
		})
		.then(() => {
			return core.loader();
		})
		.catch((e) => {
			console.log(e);
		});
}
catch (e) {
	console.log('CAN NOT RUN');
	console.log('Make sure you install '+ (isDev ? 'Dev' : 'Local') +' \''+ fit +'\'.');
}
