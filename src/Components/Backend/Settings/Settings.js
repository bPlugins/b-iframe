import { __ } from '@wordpress/i18n';
import { useState, useEffect } from 'react';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TabPanel, TextControl, ToggleControl, SelectControl, Notice } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';

import { BtnGroup } from '../../../../../bpl-tools/Components';
import Dimension from '../../../../../bpl-tools/Components/Dimension/Dimension';
import BorderShadow from '../../../../../bpl-tools/Advanced/BorderShadow';
import { updateData } from '../../../../../bpl-tools/utils/functions';

import { alignments, loadTypes, sizingModes, ratioPresets } from '../../../utils/options';
import { convertSrc } from '../../../utils/convert';

const Settings = ({ attributes, setAttributes }) => {
	const { src, title, loading, sizing, ratio, elements, layout, advanced } = attributes;
	const { fullscreen } = elements || {};
	const { alignment } = layout || {};

	const [frameBlocked, setFrameBlocked] = useState(false);

	// Server-side header check: warn before the user publishes a blank box.
	useEffect(() => {
		setFrameBlocked(false);
		if (!/^https?:\/\/\S+\.\S+/.test(src || '')) return;

		const timer = setTimeout(() => {
			apiFetch({ path: `/bifrm/v1/frame-check?url=${encodeURIComponent(src)}` })
				.then((res) => setFrameBlocked(res?.embeddable === false))
				.catch(() => {});
		}, 800);
		return () => clearTimeout(timer);
	}, [src]);

	const updateAttr = (attr, val, ...props) => {
		setAttributes({ [attr]: updateData(attributes[attr], val, ...props) });
	}

	const isPresetRatio = ratioPresets.some(({ value }) => value === (ratio || '16/9'));

	return <InspectorControls>
		<TabPanel className='bPlTabPanel' tabs={[{ name: 'general', title: __('General') }, { name: 'style', title: __('Style') }]}>
			{(tab) => <>
				{tab.name === 'general' && <>
					<PanelBody className='bPlPanelBody' title={__('Iframe', 'b-iframe')}>
						<TextControl
							label={__('Source', 'b-iframe')}
							value={src}
							onChange={(val) => setAttributes({ src: convertSrc(val) })}
							help={__('YouTube, Vimeo, Dailymotion, Google Maps, Spotify, Loom and Figma page links are converted to their embeddable form automatically.', 'b-iframe')}
						/>

						{frameBlocked && <Notice status='warning' isDismissible={false}>
							{__('This site does not allow embedding — visitors would see an empty box. Try the page\'s own embed or share URL instead.', 'b-iframe')}
						</Notice>}

						<TextControl className='mt20' label={__('Title', 'b-iframe')} value={title} onChange={(val) => setAttributes({ title: val })} />

						<SelectControl className='mt20' label={__('Loading Behavior', 'b-iframe')} labelPosition='left' value={loading} options={loadTypes} onChange={(val) => setAttributes({ loading: val })} />
					</PanelBody>


					<PanelBody className='bPlPanelBody' title={__('Sizing', 'b-iframe')}>
						<BtnGroup label={__('Mode', 'b-iframe')} labelPosition='top' value={sizing || 'fixed'} onChange={val => setAttributes({ sizing: val })} options={sizingModes} />

						{sizing === 'ratio' ? <>
							<SelectControl
								className='mt20'
								label={__('Aspect ratio', 'b-iframe')}
								labelPosition='left'
								value={isPresetRatio ? (ratio || '16/9') : 'custom'}
								options={[...ratioPresets, { label: __('Custom', 'b-iframe'), value: 'custom' }]}
								onChange={(val) => setAttributes({ ratio: val === 'custom' ? '3/2' : val })}
							/>
							{!isPresetRatio && <TextControl
								className='mt20'
								label={__('Custom ratio', 'b-iframe')}
								value={ratio}
								onChange={(val) => setAttributes({ ratio: val })}
								help={__('Width/height, e.g. 3/2 or 21/9.', 'b-iframe')}
							/>}
							<p className='bPlHelp'>{__('The iframe keeps this ratio and scales with the page width. Height set on the Style tab is ignored in this mode.', 'b-iframe')}</p>
						</> : null}
					</PanelBody>


					<PanelBody className='bPlPanelBody' title={__('Elements', 'b-iframe')}>
						<ToggleControl label={__('Fullscreen Button', 'b-iframe')} checked={fullscreen} onChange={(val) => updateAttr('elements', val, 'fullscreen')} />
					</PanelBody>


					<PanelBody className='bPlPanelBody' title={__('Layout', 'b-iframe')}>
						<BtnGroup label={__('Alignment', 'b-iframe')} value={alignment} onChange={val => updateAttr('layout', val, 'alignment')} options={alignments} isIcon={true} />
					</PanelBody>
				</>}


				{tab.name === 'style' && <>
					<Dimension dimension={advanced?.dimension} onChange={val => setAttributes({ advanced: updateData(advanced, val, 'dimension') })} enabled={['width', 'height']} />

					<BorderShadow borderShadow={advanced?.borderShadow} onChange={val => setAttributes({ advanced: updateData(advanced, val, 'borderShadow') })} enabled={['normal', 'hover', 'border', 'shadow']} />
				</>}
			</>}
		</TabPanel>
	</InspectorControls>;
};
export default Settings;
