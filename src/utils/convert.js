/**
 * Rewrites well-known page URLs to their embeddable form.
 * Mirrors includes/Converter.php — keep the two in sync.
 */

export const convertSrc = (url) => {
	url = (url || '').trim();
	if (!/^https?:\/\//i.test(url)) return url;

	let u;
	try { u = new URL(url); } catch (e) { return url; }

	const host = u.hostname.toLowerCase().replace(/^www\./, '');
	const path = u.pathname;
	let m;

	// YouTube
	if (host === 'youtube.com' || host === 'm.youtube.com' || host === 'youtube-nocookie.com') {
		if (path.startsWith('/embed/')) return url;
		let id = '';
		if (path === '/watch' && u.searchParams.get('v')) id = u.searchParams.get('v');
		else if ((m = path.match(/^\/(shorts|live)\/([\w-]{6,})/))) id = m[2];
		if (id) {
			const t = parseInt(u.searchParams.get('t'), 10);
			return `https://www.youtube-nocookie.com/embed/${encodeURIComponent(id)}${t > 0 ? `?start=${t}` : ''}`;
		}
		return url;
	}
	if (host === 'youtu.be' && (m = path.match(/^\/([\w-]{6,})/))) {
		return `https://www.youtube-nocookie.com/embed/${encodeURIComponent(m[1])}`;
	}

	// Vimeo
	if (host === 'vimeo.com' && (m = path.match(/^\/(\d+)(?:\/([0-9a-f]+))?$/))) {
		return `https://player.vimeo.com/video/${m[1]}${m[2] ? `?h=${m[2]}` : ''}`;
	}

	// Dailymotion
	if (host === 'dailymotion.com' && (m = path.match(/^\/video\/(\w+)/))) {
		return `https://www.dailymotion.com/embed/video/${m[1]}`;
	}
	if (host === 'dai.ly' && (m = path.match(/^\/(\w+)/))) {
		return `https://www.dailymotion.com/embed/video/${m[1]}`;
	}

	// Spotify
	if (host === 'open.spotify.com' && (m = path.match(/^\/(track|album|playlist|episode|show|artist)\/(\w+)/))) {
		return `https://open.spotify.com/embed/${m[1]}/${m[2]}`;
	}

	// Loom
	if (host === 'loom.com' && (m = path.match(/^\/share\/([0-9a-f]+)/))) {
		return `https://www.loom.com/embed/${m[1]}`;
	}

	// Figma
	if (host === 'figma.com' && /^\/(file|design|proto|board)\//.test(path)) {
		return `https://www.figma.com/embed?embed_host=b-iframe&url=${encodeURIComponent(url)}`;
	}

	// Google Maps
	if (host === 'google.com' && path.startsWith('/maps')) {
		if (path.startsWith('/maps/embed')) return url;
		let q = '';
		if ((m = path.match(/\/maps\/place\/([^/]+)/))) q = decodeURIComponent(m[1].replace(/\+/g, ' '));
		else if ((m = path.match(/\/maps\/@(-?[\d.]+),(-?[\d.]+)/))) q = `${m[1]},${m[2]}`;
		else if (u.searchParams.get('q')) q = u.searchParams.get('q');
		return `https://www.google.com/maps?q=${encodeURIComponent(q)}&output=embed`;
	}

	return url;
};
