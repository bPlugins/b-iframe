=== iFrame – Embed Any Website Content Without the Hassle ===
Contributors: bplugins, abuhayat, charlescormier
Donate link: https://gum.co/wpdonate/
Tags: block, iframe, website embed, embed, url embed
Requires at least: 6.5
Tested up to: 7.1
Stable tag: 1.1.0
Requires PHP: 7.4
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

iFrame is a powerful WordPress Gutenberg block plugin that revolutionizes the way you embed iframes

== Description ==

iFrame is a powerful WordPress Gutenberg block plugin that revolutionizes the way you embed iframes. With features like customizable titles, versatile loading behaviors, full-screen activation, and precise layout controls, iFrame offers an intuitive and dynamic solution for integrating iframes into your content. Elevate the visual appeal of your iframes with additional design options, allowing you to apply borders and shadows effortlessly.

= Features =
- **Responsive sizing**: Keep an aspect ratio (16:9, 4:3, 1:1, 9:16, 21:9 or custom) so the iframe scales with the page, or set a fixed height.
- **Smart URL conversion**: Paste a normal YouTube, Vimeo, Dailymotion, Google Maps, Spotify, Loom or Figma page link — it is converted to the embeddable form automatically (YouTube uses the privacy-enhanced youtube-nocookie.com player).
- **Server-side rendering**: The iframe is real HTML — visible to search engines and visitors without JavaScript, with no layout shift.
- **Embed check**: The editor warns you when a site refuses to be embedded, before your visitors see an empty box.
- **ShortCode Generator**: Build reusable iframes in the block editor and place them anywhere with [b-iframe id="123"].
- **Title**: Specify the title for the iframe.
- **Loading Behavior**: Configure the loading behavior.
- **Full Screen**: Activate Full Screen to enable the iframe in full-screen mode.
- **Layout**: Define the width and height for the iframe.
- **Design**: Apply borders and shadows to enhance the iframe's visual appearance.


= How to use =
- First, install the iFrame plugin
- Add the Iframe block from the block category called 'Widgets' in the Gutenberg editor.
- You can change block settings from the right-side settings sidebar.
- Enjoy!

* For installation help click on Installation Tab.


= Feedback =
- Did you like this plugin? Dislike it? Do you have a feature request? [Please share your feedback with us](mailto:support@bplugins.com 'Send feedback')


### Check out the Parent Plugin of this plugin-

[**B Blocks**](https://bblockswp.com) – A blocks collection and page building tool for Gutenberg.


### Check out our other WordPress Plugins-

[**Html5 Video Player**](https://bplugins.com/products/html5-video-player/) – Display videos as single and playlist in multiple skins.

[**PDF Poster**](https://bplugins.com/products/pdf-poster/) – Display/Embed PDF files with different styles.

[**Html5 Audio Player**](https://bplugins.com/products/html5-audio-player/) – Listen audios with awesome visuals.

[**StreamCast**](https://bplugins.com/products/streamcast-radio-player/) – Customizable radio player with different skins.

[**3D Viewer**](https://bplugins.com/products/3d-viewer/) – Embed 3D models and 3D products with interaction.

[**Advanced Post Block**](https://bplugins.com/products/advanced-post-block/) – Show posts and custom posts in different layouts.


= ShortCode =
Use the following ShortCode to Embed Iframe.
<pre>
	[iframe src='https://bplugins.com' title='bPlugins Website' width='100%' height='800px' loading='auto' border_width='2px' border_style='solid' border_color='#146EF5' border_radius='8px']
</pre>


== External services ==

This plugin uses the Freemius SDK for optional usage tracking and update notifications. No data is sent unless you explicitly opt in on the consent screen shown after activation — choosing Skip keeps the plugin fully functional with no data shared. When you opt in, basic WordPress environment information and your email are sent to Freemius. Freemius terms: https://freemius.com/terms/ — privacy policy: https://freemius.com/privacy/

== Installation ==

= From Gutenberg Editor: =
1. Go to the WordPress Block/Gutenberg Editor
2. Search For **iFrame**
3. Click on the **iFrame** to add the block

= Download & Upload: =
1. Download the **iFrame** plugin (*.zip file*)
2. In your admin area, go to the Plugins menu and click on **Add New**
3. Click on **Upload Plugin** and choose the **`b-iframe.zip`** file and click on **Install Now**
4. Activate the plugin and Enjoy!

= Manually: =
1. Download and upload the **iFrame** plugin to the **`/wp-content/plugins/`** directory
2. Activate the plugin through the Plugins menu in WordPress


== Frequently Asked Questions ==

= Is iFrame free? =

Yes, iFrame is a free Gutenberg block plugin.

= Does it work with any WordPress theme? =

Yes, it will work with any standard WordPress theme.

= Can I change block settings? =

Yes, you can change block settings from the Gutenberg block editor's right sidebar.

= How many times can I reuse a block? =

You can use unlimited times as you want.

= Where can I get support? =

You can post your questions on the [support forum here](https://wordpress.org/support/plugin/b-iframe/)


== Screenshots ==

1. iFrame
2. Settings


= Why did my pasted link change? =

Links to known providers are rewritten to the URL those providers require for embedding (for example youtube.com/watch links become youtube-nocookie.com/embed links). Unrecognised links are left untouched.

= Why does the editor say a site does not allow embedding? =

That site sends an X-Frame-Options or Content-Security-Policy header that forbids being shown inside other pages. Browsers enforce it, so visitors would see an empty box. Use the site's own embed or share URL if it offers one.

== Changelog ==

= 1.1.0 =
* New: Optional usage tracking via Freemius — asked once, only active if you opt in.
* New: Aspect-ratio sizing mode — the iframe keeps its ratio and scales with the page width.
* New: Page links from YouTube, Vimeo, Dailymotion, Google Maps, Spotify, Loom and Figma are converted to their embeddable form automatically (block and shortcode).
* New: Correct iframe permissions (autoplay, fullscreen, picture-in-picture) and referrer policy per provider.
* New: The editor warns when a site's headers forbid embedding.
* Improved: The iframe is now rendered server-side — visible without JavaScript, indexable, no layout shift; the frontend script is ~90% smaller.
* New: ShortCode Generator — build reusable iframes in the block editor (iFrame menu) and embed them anywhere with [b-iframe id="123"]; edit once, updates everywhere.
* New: Help & Demos page with a live example and full shortcode reference.
* Fixed: The [iframe] shortcode was never loaded and did not work.
* Fixed: allowfullscreen was emitted in a form browsers treat as enabled even when turned off.


= 1.0.0 =
* Initial Release


== Source Code ==

The complete, un-minified source code for this plugin's JavaScript and CSS is included in the `src/` directory of the plugin, and is also available on GitHub:
[**iFrame on GitHub**](https://github.com/bPlugins/b-iframe)

= Build instructions =

The compiled files in `build/` are generated from `src/` using [@wordpress/scripts](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/) (webpack). To build them yourself:

1. Install [Node.js](https://nodejs.org/) (18+) and npm.
2. From the plugin directory, run `npm install` to install the build dependencies (see `package.json`).
3. Run `npm run build` to compile `src/` into the minified files in `build/`.
4. Run `npm start` instead for a development build with file watching.

The build tooling is configured in `package.json` and `webpack.config.js`. The full development setup, including the linting configuration, is available in the GitHub repository linked above.


== Third-Party Libraries ==

This plugin bundles the following third-party JavaScript/PHP libraries.

= bpl-tools =
* Source / GitHub: https://github.com/bPlugins/bpl-tools
* License: GPL-2.0-or-later – https://www.gnu.org/licenses/gpl-2.0.html
* Purpose: Shared utility library providing admin dashboard components and common Gutenberg editor controls.
* External Services: The library may connect to bPlugins, WordPress.org, and Freemius services for product data and checkout functionality. See full details: https://github.com/bPlugins/bpl-tools#external-requests--why-they-are-made