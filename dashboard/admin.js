import React from 'react-dom';
import '../../bpl-tools/Admin/style.scss';
import './style.scss';
import App from './App';
import { dashboardInfo } from './utils/data';

document.addEventListener('DOMContentLoaded', () => {
	const adminEl = document.getElementById('bifrmDashboard');
	let info = {};

	try {
		if (adminEl?.dataset?.info !== undefined || adminEl?.dataset?.info !== '') {
			info = JSON.parse(adminEl?.dataset?.info);
		}
	} catch {
		console.warn('Not Info');
	}

	React.createRoot(adminEl).render(<App {...dashboardInfo(info)} />);

	adminEl.removeAttribute('data-info');
});
