import { useEffect } from 'react';
import { Outlet, Link, useLocation } from 'react-router-dom';

import Header from '../../../bpl-tools/Admin/Header';

const navigation = [
	{ name: 'Welcome', href: '/welcome' },
	{ name: 'Demos', href: '/demos' },
	{ name: 'Settings', href: '/settings' }
];

const Layout = (props) => {

	const location = useLocation();

	// The shared Overview card points "View Demos" at the external landing
	// page; send it to this dashboard's Demos tab instead.
	useEffect(() => {
		const btn = document.querySelector('.bPlDashboard a.secondaryBtn');
		if (btn) {
			btn.setAttribute('href', '#/demos');
			btn.removeAttribute('target');
			btn.removeAttribute('rel');
		}
	});

	return <div className='bPlDashboard'>
		<Header {...props}>
			<nav className='bPlDashboardNav'>
				{navigation?.map((item, index) => <Link
					key={index}
					to={item.href}
					className={`navLink ${location.pathname === item.href ? 'active' : ''}`}
				>
					{item.name}
				</Link>)}
			</nav>
		</Header>

		<main className='bPlDashboardMain'>
			<Outlet />
		</main>
	</div>
}
export default Layout;
