import { HashRouter as Router, Routes, Route, Navigate } from 'react-router-dom';

import Welcome from '../../bpl-tools/Admin/Welcome';
import Demos from '../../bpl-tools/Admin/Demos';
import OurPlugins from '../../bpl-tools/Admin/OurPlugins';
import Settings from '../../bpl-tools/Admin/Settings';

import Layout from './Layout/Layout';
import { demoInfo, welcomeInfo } from './utils/data';

const App = (props) => {
	const { adminUrl } = props;

	return <Router>
		<Routes>
			<Route path='/' element={<Layout {...props} />}>
				<Route index element={<Welcome {...props} {...welcomeInfo(adminUrl)} />} />

				<Route path='welcome' element={<Welcome {...props} {...welcomeInfo(adminUrl)} />} />

				<Route path='demos' element={<Demos demoInfo={demoInfo} {...props} />} />

				<Route path='our-plugins' element={<OurPlugins {...props} />} />

				<Route path='settings' element={<Settings {...props} ajaxAction='bifrmSaveUninstallOption' cleanupItems={[
					'All reusable iframes from the ShortCode Generator',
					'Plugin settings and options',
				]} />} />

				<Route path='*' element={<Navigate to='/welcome' replace />} />
			</Route>
		</Routes>
	</Router>
}
export default App;
