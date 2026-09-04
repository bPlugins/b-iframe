/**
 * Frontend enhancement only — the iframe itself is server-rendered.
 * Adds the advanced (border/shadow/dimension) CSS and the fullscreen button.
 */
import './style.scss';
import { generateCSS } from '../../bpl-tools/Advanced/generateCSS';
import { isValidCSS } from '../../bpl-tools/utils/getCSS';

const expandSVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M32 32C14.3 32 0 46.3 0 64v96c0 17.7 14.3 32 32 32s32-14.3 32-32V96h64c17.7 0 32-14.3 32-32s-14.3-32-32-32H32zM64 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7 14.3 32 32 32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H64V352zM320 32c-17.7 0-32 14.3-32 32s14.3 32 32 32h64v64c0 17.7 14.3 32 32 32s32-14.3 32-32V64c0-17.7-14.3-32-32-32h-96zM448 352c0-17.7-14.3-32-32-32s-32 14.3-32 32v64h-64c-17.7 0-32 14.3-32 32s14.3 32 32 32h96c17.7 0 32-14.3 32-32v-96z"/></svg>';
const compressSVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M160 64c0-17.7-14.3-32-32-32s-32 14.3-32 32v64H32c-17.7 0-32 14.3-32 32s14.3 32 32 32h96c17.7 0 32-14.3 32-32V64zM32 320c-17.7 0-32 14.3-32 32s14.3 32 32 32h64v64c0 17.7 14.3 32 32 32s32-14.3 32-32v-96c0-17.7-14.3-32-32-32H32zM352 64c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7 14.3 32 32 32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H352V64zM320 320c-17.7 0-32 14.3-32 32v96c0 17.7 14.3 32 32 32s32-14.3 32-32V384h64c17.7 0 32-14.3 32-32s-14.3-32-32-32h-96z"/></svg>';

document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('.wp-block-bifrm-iframe').forEach((el) => {
		let attributes = {};
		try { attributes = JSON.parse(el.dataset.attributes || '{}'); } catch (e) { /* leave SSR as-is */ }
		el.removeAttribute('data-attributes');

		const { src, advanced, layout, elements } = attributes;
		const frameWrap = el.querySelector(':scope > .bIframe');
		if (!src || !frameWrap) return;

		// Advanced style (border, shadow, responsive dimension). The SSR
		// <style> sits after this one in the DOM, so ratio rules keep winning.
		const style = document.createElement('style');
		style.textContent = `
			${generateCSS(el.id, advanced)}
			#${el.id}{ ${isValidCSS('text-align', layout?.alignment)} }
		`;
		el.prepend(style);

		// Fullscreen button (YouTube's player ships its own).
		const fullscreen = elements?.fullscreen ?? true;
		if (!fullscreen || src.includes('/embed/')) return;

		const btn = document.createElement('button');
		btn.type = 'button';
		btn.className = 'fullScreenBtn';
		btn.setAttribute('aria-label', 'Toggle fullscreen');
		btn.innerHTML = expandSVG;

		btn.addEventListener('click', () => {
			if (document.fullscreenElement) {
				document.exitFullscreen();
			} else {
				frameWrap.requestFullscreen();
			}
		});
		document.addEventListener('fullscreenchange', () => {
			btn.innerHTML = document.fullscreenElement === frameWrap ? compressSVG : expandSVG;
		});

		frameWrap.appendChild(btn);
	});
});
