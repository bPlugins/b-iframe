/**
 * ShortCode Generator editor: shows this post's [b-iframe id=…] shortcode
 * between the title and the block canvas, with one-click copy.
 *
 * The post editor canvas may live in an iframe (apiVersion 3 blocks), and it
 * remounts on view changes, so injection is re-checked on an interval.
 */
(function (wp) {
	'use strict';

	var BAR_ID = 'bifrmEditorShortcode';

	function canvasDocument() {
		var frame = document.querySelector('iframe[name="editor-canvas"]');
		if (frame && frame.contentDocument && frame.contentDocument.body) {
			return frame.contentDocument;
		}
		return document;
	}

	function copyText(text, onDone) {
		if (navigator.clipboard && navigator.clipboard.writeText) {
			navigator.clipboard.writeText(text).then(onDone, function () {
				fallbackCopy(text, onDone);
			});
		} else {
			fallbackCopy(text, onDone);
		}
	}

	function fallbackCopy(text, onDone) {
		var area = document.createElement('textarea');
		area.value = text;
		document.body.appendChild(area);
		area.select();
		try { document.execCommand('copy'); } catch (e) { /* ignore */ }
		document.body.removeChild(area);
		onDone();
	}

	function inject() {
		var select = wp.data.select('core/editor');
		if (!select || select.getCurrentPostType() !== 'b-iframe') {
			return;
		}
		var postId = select.getCurrentPostId();
		if (!postId) {
			return;
		}

		var doc = canvasDocument();
		if (doc.getElementById(BAR_ID)) {
			return;
		}
		var title = doc.querySelector('.editor-post-title, .edit-post-visual-editor__post-title-wrapper');
		if (!title) {
			return;
		}

		var shortcode = '[b-iframe id="' + postId + '"]';
		var isPublished = 'publish' === select.getCurrentPostAttribute('status');

		var bar = doc.createElement('div');
		bar.id = BAR_ID;
		bar.setAttribute('style', 'display:flex;align-items:center;gap:10px;flex-wrap:wrap;max-width:var(--wp--style--global--content-size,640px);box-sizing:border-box;margin:8px auto 24px;padding:10px 14px;background:#f0f6fc;border:1px solid #c5d9ed;font-size:13px;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;');

		var label = doc.createElement('span');
		label.textContent = 'Shortcode:';
		label.setAttribute('style', 'font-weight:600;color:#1d2327;');

		var input = doc.createElement('input');
		input.type = 'text';
		input.readOnly = true;
		input.value = shortcode;
		input.setAttribute('style', 'flex:0 1 220px;min-width:170px;padding:4px 8px;border:1px solid #8c8f94;background:#fff;color:#1d2327;font:12px/1.6 Menlo,Consolas,monospace;cursor:pointer;');

		var btn = doc.createElement('button');
		btn.type = 'button';
		btn.textContent = 'Copy';
		btn.setAttribute('style', 'padding:4px 14px;border:1px solid #2271b1;background:#2271b1;color:#fff;font-size:12px;font-weight:600;cursor:pointer;');

		var done = function () {
			btn.textContent = 'Copied';
			input.select();
			setTimeout(function () { btn.textContent = 'Copy'; }, 2000);
		};
		btn.addEventListener('click', function () { copyText(shortcode, done); });
		input.addEventListener('click', function () { input.select(); copyText(shortcode, done); });

		bar.appendChild(label);
		bar.appendChild(input);
		bar.appendChild(btn);

		if (!isPublished) {
			var hint = doc.createElement('span');
			hint.textContent = 'Publish this iframe to use it on the site.';
			hint.setAttribute('style', 'color:#646970;');
			bar.appendChild(hint);
		}

		title.insertAdjacentElement('afterend', bar);
	}

	wp.domReady(function () {
		inject();
		// The canvas iframe (re)mounts asynchronously; keep it present.
		setInterval(inject, 1000);
	});
})(window.wp);
