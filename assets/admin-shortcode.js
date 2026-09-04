/**
 * Copies a [b-iframe id=…] shortcode from the post-list column.
 */
window.copyBPlAdminShortcode = function (postID) {
	var copyText = document.querySelector('#bPlAdminShortcode-' + postID + ' input');
	var tooltip = document.querySelector('#bPlAdminShortcode-' + postID + ' .tooltip');

	var done = function () {
		if (tooltip) {
			tooltip.textContent = 'Copied Successfully!';
		}
	};

	copyText.select();
	copyText.setSelectionRange(0, 99999);

	if (navigator.clipboard && navigator.clipboard.writeText) {
		navigator.clipboard.writeText(copyText.value).then(done, function () {
			document.execCommand('copy');
			done();
		});
	} else {
		document.execCommand('copy');
		done();
	}
};
