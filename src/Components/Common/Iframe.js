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

	// YouTube refuses to play inside the editor's null-origin canvas
	// (error 153), so preview it as a thumbnail; it plays on the live site.
	const ytId = embedSrc.match(/youtube(?:-nocookie)?\.com\/embed\/([\w-]+)/)?.[1];
	if (ytId) {
		return <div className={prefix} style={{ ...ratioStyle, position: 'relative', background: '#000' }}>
			<img
				src={`https://i.ytimg.com/vi/${ytId}/hqdefault.jpg`}
				alt={title}
				style={{ width: '100%', height: '100%', objectFit: 'cover', opacity: 0.85, display: 'block' }}
			/>
			<span style={{ position: 'absolute', top: '50%', left: '50%', transform: 'translate(-50%,-50%)', width: 64, height: 45, background: 'rgba(0,0,0,.75)', borderRadius: 10, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
				<span style={{ width: 0, height: 0, borderStyle: 'solid', borderWidth: '9px 0 9px 16px', borderColor: 'transparent transparent transparent #fff', marginLeft: 3 }}></span>
			</span>
			<span style={{ position: 'absolute', left: 8, bottom: 8, padding: '3px 8px', background: 'rgba(0,0,0,.75)', color: '#fff', fontSize: 11 }}>
				YouTube preview — plays on the live site
			</span>
		</div>;
	}

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
