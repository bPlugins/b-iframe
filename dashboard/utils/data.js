const slug = 'b-iframe';

const frameIcon = (<svg stroke='#000' fill='#000' strokeWidth='0' viewBox='0 0 24 24' height='1em' width='1em' xmlns='http://www.w3.org/2000/svg'><path fillRule='evenodd' clipRule='evenodd' d='M1 6C1 4.34315 2.34315 3 4 3H20C21.6569 3 23 4.34315 23 6V18C23 19.6569 21.6569 21 20 21H4C2.34315 21 1 19.6569 1 18V6ZM4 5H20C20.5523 5 21 5.44772 21 6V8H3V6C3 5.44772 3.44772 5 4 5ZM3 10V18C3 18.5523 3.44772 19 4 19H20C20.5523 19 21 18.5523 21 18V10H3Z' fill='currentColor'></path><path d='M9.5 13L7.5 15L9.5 17' stroke='currentColor' strokeWidth='1.5' fill='none' strokeLinecap='round'></path><path d='M14.5 13L16.5 15L14.5 17' stroke='currentColor' strokeWidth='1.5' fill='none' strokeLinecap='round'></path></svg>);

export const dashboardInfo = (info) => {
	const { version, adminUrl, pluginUrl, deleteDataOnUninstall, uninstallNonce } = info;

	return {
		name: 'iFrame',
		displayName: 'iFrame - Responsive Iframe Embedding for WordPress',
		description:
			'iFrame embeds any URL — a website, video, map, playlist or design file — in a responsive iframe. Paste a normal page link and it is converted to the embeddable form automatically, keep any aspect ratio at any screen size, and reuse the same iframe anywhere with a generated shortcode. Server-side rendered, so embeds are visible to search engines and load without layout shift.',
		slug,
		version,

		// Free plugin: hide the upgrade button in the header.
		isPremium: true,
		hasPro: false,
		displayOurPlugins: true,
		media: {
			logo: `https://ps.w.org/${slug}/assets/icon-128x128.png`,
			banner: `https://ps.w.org/${slug}/assets/banner-772x250.png`,
			thumbnail: `${pluginUrl}assets/welcome-banner.svg`,
			video: 'https://youtu.be/Hfm94aHAbYQ',
			isYoutube: true,
		},
		pages: {
			org: `https://wordpress.org/plugins/${slug}/`,
			landing: `https://bplugins.com/products/${slug}/`,
			docs: `https://bplugins.com/docs/${slug}/`,
		},

		adminUrl,
		deleteDataOnUninstall,
		uninstallNonce,
		startButton: {
			label: 'Start Now',
			url: `${adminUrl}post-new.php?post_type=b-iframe`
		}
	}
}

export const welcomeInfo = (adminUrl = '') => ({
	gettingStarted: {
		tabs: [
			{
				key: 'gutenberg',
				label: 'Gutenberg',
				steps: [
					{
						num: 1,
						title: 'Add the Block',
						body: 'Open the block editor. Click <strong>+</strong> or type <strong>/iFrame</strong>.',
						link: { url: `${adminUrl}post-new.php`, label: 'Open Editor' },
					},
					{
						num: 2,
						title: 'Paste a URL',
						body: 'Any URL works. Page links from <strong>YouTube, Vimeo, Dailymotion, Google Maps, Spotify, Loom and Figma</strong> are converted to their embeddable form automatically.',
					},
					{
						num: 3,
						title: 'Pick a Sizing Mode',
						body: '<strong>Aspect ratio</strong> (16:9, 4:3, 1:1, 9:16, 21:9 or custom) scales with the page; <strong>Fixed height</strong> stays put.',
					},
					{
						num: 4,
						title: 'Style & Publish',
						body: 'Add borders and shadows on the Style tab, then publish. The editor warns you if a site refuses to be embedded.',
					},
				],
			},
			{
				key: 'shortcode',
				label: 'Shortcode',
				steps: [
					{
						num: 1,
						title: 'Create a Reusable Iframe',
						body: 'Go to <strong>iFrame</strong> in your admin menu and click <strong>Add New ShortCode</strong>.',
						link: { url: `${adminUrl}post-new.php?post_type=b-iframe`, label: 'Add New' },
					},
					{
						num: 2,
						title: 'Build & Publish',
						body: 'Configure the iframe with the full block editor and <strong>Publish</strong>.',
					},
					{
						num: 3,
						title: 'Copy the Shortcode',
						body: 'Go to <strong>iFrame -> ShortCode Generator</strong> and copy the shortcode (e.g. <code>[b-iframe id="123"]</code>).',
						link: { url: `${adminUrl}edit.php?post_type=b-iframe`, label: 'ShortCode Generator' },
					},
					{
						num: 4,
						title: 'Paste & Display',
						body: 'Paste it into any post, page or widget. Prefer a one-off? Use <code>[iframe src="https://…" ratio="16:9"]</code> directly.',
					},
				],
			},
			{
				key: 'elementor',
				label: 'Elementor',
				steps: [
					{
						num: 1,
						title: 'Create a Reusable Iframe',
						body: 'Go to <strong>iFrame -> Add New ShortCode</strong> to build and publish, then copy its shortcode.',
						link: { url: `${adminUrl}post-new.php?post_type=b-iframe`, label: 'Add New' },
					},
					{
						num: 2,
						title: 'Edit with Elementor',
						body: 'Open any post or page in the <strong>Elementor</strong> editor.',
					},
					{
						num: 3,
						title: 'Add Shortcode Widget',
						body: 'Search for the <strong>Shortcode</strong> widget in the Elementor elements panel and drag it into your layout.',
					},
					{
						num: 4,
						title: 'Paste Shortcode',
						body: 'Paste your shortcode (e.g., <code>[b-iframe id="123"]</code>) into the widget input field and save.',
					},
				],
			},
			{
				key: 'php',
				label: 'PHP',
				steps: [
					{
						num: 1,
						title: 'Get the ID',
						body: 'Go to <strong>iFrame -> ShortCode Generator</strong> and note the <strong>ID</strong> you want to embed.',
						link: { url: `${adminUrl}edit.php?post_type=b-iframe`, label: 'ShortCode Generator' },
					},
					{
						num: 2,
						title: 'Copy PHP Function',
						body: "Copy the WordPress <code>do_shortcode</code> function: <pre><code>&lt;?php echo do_shortcode('[b-iframe id=\"YOUR_ID\"]'); ?&gt;</code></pre>",
					},
					{
						num: 3,
						title: 'Insert in Template',
						body: 'Open your theme or template files (e.g., <code>single.php</code>, <code>page.php</code>) in an editor.',
					},
					{
						num: 4,
						title: 'Replace ID & Save',
						body: 'Paste the code into your PHP file and replace <code>YOUR_ID</code> with the actual ID.',
					},
				],
			},
		],
	},

	changelogs: [
		{
			version: '1.1.0 - 4 September, 2026',
			type: 'new',
			list: [
				'<strong>New:</strong> Aspect-ratio sizing mode — the iframe keeps its ratio and scales with the page width.',
				'<strong>New:</strong> Page links from YouTube, Vimeo, Dailymotion, Google Maps, Spotify, Loom and Figma are converted to their embeddable form automatically.',
				'<strong>New:</strong> ShortCode Generator — build reusable iframes and embed them anywhere with <code>[b-iframe id="123"]</code>.',
				'<strong>New:</strong> The editor warns when a site\'s headers forbid embedding.',
				'<strong>Update:</strong> The iframe is rendered server-side — indexable, no layout shift, ~90% smaller frontend script.',
				'<strong>Fix:</strong> The [iframe] shortcode was never loaded; allowfullscreen was always on.',
			]
		},
		{
			version: '1.0.0',
			type: 'new',
			list: [
				'<strong>New:</strong> Initial release — iFrame block with title, loading behavior, fullscreen button, layout and border/shadow controls.'
			]
		},
	],
	changelogsLimit: 6,
	changelogsReadMoreLabel: 'View More Changelogs',
});

export const demoInfo = {
	allInOneLabel: 'More about iFrame',
	allInOneLink: `https://bplugins.com/products/${slug}/`,
	demos: [
		{
			title: 'YouTube video',
			description: 'A watch link converted to the privacy-enhanced player, 16:9.',
			url: 'https://www.youtube-nocookie.com/embed/Hfm94aHAbYQ',
			icon: frameIcon,
			type: 'iframe'
		},
		{
			title: 'Google Map',
			description: 'A place link converted to an embeddable map.',
			url: 'https://www.google.com/maps?q=Dhaka&output=embed',
			icon: frameIcon,
			type: 'iframe'
		},
		{
			title: 'Spotify track',
			description: 'An open.spotify.com link converted to the embed player.',
			url: 'https://open.spotify.com/embed/track/4uLU6hMCjMI75M1A2tKUQC',
			icon: frameIcon,
			type: 'iframe'
		},
		{
			title: 'Any website',
			description: 'Sites that allow framing embed as-is.',
			url: 'https://www.wikipedia.org',
			icon: frameIcon,
			type: 'iframe'
		},
	]
}
