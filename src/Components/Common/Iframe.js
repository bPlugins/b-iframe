import { useState } from 'react';

import { compressIcon, expandIcon } from '../../utils/icons';
import { prefix } from '../../utils/data';
import { convertSrc } from '../../utils/convert';

const Iframe = ({ attributes, id }) => {
	const { src, title, loading, elements, sizing, ratio } = attributes;
	const { fullscreen } = elements || {};

	const embedSrc = convertSrc(src);
	const isRatio = sizing === 'ratio';
	const ratioStyle = isRatio ? { aspectRatio: (ratio || '16/9').replace(':', '/'), height: 'auto', width: '100%' } : {};

	const [isNowFull, setIsNowFull] = useState(false);

	const onFullScreen = () => {
		const element = document.querySelector(`#${id} .${prefix}`);

		if (document.fullscreenElement) {
			setIsNowFull(false);
			document.exitFullscreen();
		} else {
			setIsNowFull(true);
			element.requestFullscreen();
		}
	};

	return <div className={prefix} style={ratioStyle}>
		<iframe
			src={embedSrc}
			title={title}
			width='100%'
			height='100%'
			loading={loading === 'auto' ? undefined : loading}
			referrerPolicy='strict-origin-when-cross-origin'
			allowFullScreen={!!fullscreen}
		></iframe>

		{(fullscreen && !embedSrc.includes('/embed/')) && <button onClick={onFullScreen} className='fullScreenBtn'>
			{isNowFull ? compressIcon : expandIcon}
		</button>}
	</div>
}
export default Iframe;
