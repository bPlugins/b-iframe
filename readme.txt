=== B Iframe ===
Contributors: bplugins, abuhayat, charlescormier
Donate link: https://gum.co/wpdonate/
Tags: block, iframe, website embed, embed, url embed
Requires at least: 6.5
Tested up to: 7.0
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

B Iframe is a powerful WordPress Gutenberg block plugin that revolutionizes the way you embed iframes

== Description ==

B Iframe is a powerful WordPress Gutenberg block plugin that revolutionizes the way you embed iframes. With features like customizable titles, versatile loading behaviors, full-screen activation, and precise layout controls, B Iframe offers an intuitive and dynamic solution for integrating iframes into your content. Elevate the visual appeal of your iframes with additional design options, allowing you to apply borders and shadows effortlessly.

= Features =
- **Title**: Specify the title for the iframe.
- **Loading Behavior**: Configure the loading behavior.
- **Full Screen**: Activate Full Screen to enable the iframe in full-screen mode.
- **Layout**: Define the width and height for the iframe.
- **Design**: Apply borders and shadows to enhance the iframe's visual appearance.


= How to use =
- First, install the B Iframe plugin
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


== Installation ==

= From Gutenberg Editor: =
1. Go to the WordPress Block/Gutenberg Editor
2. Search For **B Iframe**
3. Click on the **B Iframe** to add the block

= Download & Upload: =
1. Download the **B Iframe** plugin (*.zip file*)
2. In your admin area, go to the Plugins menu and click on **Add New**
3. Click on **Upload Plugin** and choose the **`b-iframe.zip`** file and click on **Install Now**
4. Activate the plugin and Enjoy!

= Manually: =
1. Download and upload the **B Iframe** plugin to the **`/wp-content/plugins/`** directory
2. Activate the plugin through the Plugins menu in WordPress


== Frequently Asked Questions ==

= Is B Iframe free? =

Yes, B Iframe is a free Gutenberg block plugin.

= Does it work with any WordPress theme? =

Yes, it will work with any standard WordPress theme.

= Can I change block settings? =

Yes, you can change block settings from the Gutenberg block editor's right sidebar.

= How many times can I reuse a block? =

You can use unlimited times as you want.

= Where can I get support? =

You can post your questions on the [support forum here](https://wordpress.org/support/plugin/b-iframe/)


== Screenshots ==

1. B Iframe
2. Settings


== Changelog ==

= 1.0.0 =
* Initial Release


== Source Code ==

The complete, un-minified source code for this plugin's JavaScript and CSS is included in the `src/` directory of the plugin, and is also available on GitHub:
[**B Iframe on GitHub**](https://github.com/bPlugins/b-iframe)

= Build instructions =

The compiled files in `build/` are generated from `src/` using [@wordpress/scripts](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/) (webpack). To build them yourself:

1. Install [Node.js](https://nodejs.org/) (18+) and npm.
2. From the plugin directory, run `npm install` to install the build dependencies (see `package.json`).
3. Run `npm run build` to compile `src/` into the minified files in `build/`.
4. Run `npm start` instead for a development build with file watching.

The build tooling is configured in `package.json`, `webpack.config.js`, and `.eslintrc.js`.


== Third-Party Libraries ==

This plugin bundles the following third-party JavaScript/PHP libraries.

= bpl-tools =
* Source / GitHub: https://github.com/bPlugins/bpl-tools
* License: GPL-2.0-or-later – https://www.gnu.org/licenses/gpl-2.0.html
* Purpose: Shared utility library providing admin dashboard components and common Gutenberg editor controls.
* External Services: The library may connect to bPlugins, WordPress.org, and Freemius services for product data and checkout functionality. See full details: https://github.com/bPlugins/bpl-tools#external-requests--why-they-are-made