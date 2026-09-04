import { __ } from '@wordpress/i18n';

export const loadTypes = [
	{ label: __('Auto', 'b-iframe'), value: 'auto' },
	{ label: __('Lazy', 'b-iframe'), value: 'lazy' },
	{ label: __('Eager', 'b-iframe'), value: 'eager' }
];

export const sizingModes = [
	{ label: __('Fixed height', 'b-iframe'), value: 'fixed' },
	{ label: __('Aspect ratio', 'b-iframe'), value: 'ratio' }
];

export const ratioPresets = [
	{ label: __('16:9 — video', 'b-iframe'), value: '16/9' },
	{ label: __('4:3 — classic', 'b-iframe'), value: '4/3' },
	{ label: __('1:1 — square', 'b-iframe'), value: '1/1' },
	{ label: __('9:16 — vertical', 'b-iframe'), value: '9/16' },
	{ label: __('21:9 — cinematic', 'b-iframe'), value: '21/9' }
];

export const alignments = [
	{ label: __('Left', 'b-iframe'), value: 'left', icon: 'editor-alignleft' },
	{ label: __('Center', 'b-iframe'), value: 'center', icon: 'editor-aligncenter' },
	{ label: __('Right', 'b-iframe'), value: 'right', icon: 'editor-alignright' }
];