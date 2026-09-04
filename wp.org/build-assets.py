#!/usr/bin/env python3
"""
Generates the wordpress.org artwork for iFrame (slug: b-iframe).

bPlugins house style (matches the other plugins' listing art):
  - brand tile: #146EF5 -> #0B3FA8 gradient, square corners, no frame
  - accents: #FF7A00 orange, #070127 navy, white elements on the tile
  - banner: light gradient, tile with drop shadow on the left,
    Space Grotesk headline on the right, dark bPlugins logo bottom-right
  - the icon carries a subtle CSS loop, frozen under prefers-reduced-motion;
    its rest state equals the static PNG derivatives.

Run from this folder:  python3 build-assets.py
Outputs: icon.svg (256x256), banner.svg (1544x500)
"""
import base64
import os

HERE = os.path.dirname(os.path.abspath(__file__))
b64 = lambda p: base64.b64encode(open(p, 'rb').read()).decode()
BOLD = b64(os.path.join(HERE, 'src-fonts/space-grotesk-bold.ttf'))
MEDIUM = b64(os.path.join(HERE, 'src-fonts/space-grotesk-medium.ttf'))
LOGO_B64 = b64(os.path.join(HERE, 'src-logo-dark.svg'))

BLUE, BLUE_D, ORANGE, NAVY, SUB = '#146EF5', '#0B3FA8', '#FF7A00', '#070127', '#485781'

# Icon: a browser window; inside it a 16:9 embed area with code chevrons and
# an orange resize handle — embed + responsive + markup in one mark.
ICON_STYLE = f'''
      svg {{ --dur: 4.6s; }}
      .chev-l {{ animation: nudge-l var(--dur) ease-in-out infinite; }}
      .chev-r {{ animation: nudge-r var(--dur) ease-in-out infinite; }}
      .handle {{ transform-box: fill-box; transform-origin: 50% 50%; animation: pulse var(--dur) ease-in-out infinite; }}
      .embed {{ transform-box: fill-box; transform-origin: 50% 50%; animation: flex var(--dur) ease-in-out infinite; }}
      @keyframes nudge-l {{ 0%, 100% {{ transform: translateX(0); }} 30% {{ transform: translateX(-6px); }} 60% {{ transform: translateX(0); }} }}
      @keyframes nudge-r {{ 0%, 100% {{ transform: translateX(0); }} 30% {{ transform: translateX(6px); }} 60% {{ transform: translateX(0); }} }}
      @keyframes pulse {{ 0%, 100% {{ transform: scale(1); }} 30% {{ transform: scale(1.25); }} 55% {{ transform: scale(1); }} }}
      @keyframes flex {{ 0%, 100% {{ transform: scaleX(1); }} 30% {{ transform: scaleX(1.06); }} 60% {{ transform: scaleX(1); }} }}
      @media (prefers-reduced-motion: reduce) {{ * {{ animation: none !important; }} }}'''

ICON_BODY = f'''
      <!-- browser chrome -->
      <rect x="28" y="36" width="200" height="184" fill="#ffffff"/>
      <rect x="28" y="36" width="200" height="34" fill="{NAVY}"/>
      <circle cx="46" cy="53" r="6" fill="{ORANGE}"/>
      <circle cx="66" cy="53" r="6" fill="#ffffff" fill-opacity="0.55"/>
      <circle cx="86" cy="53" r="6" fill="#ffffff" fill-opacity="0.3"/>
      <rect x="104" y="45" width="112" height="16" fill="#ffffff" fill-opacity="0.18"/>

      <!-- embedded frame, 16:9, kept centred -->
      <g class="embed">
        <rect x="52" y="88" width="152" height="86" fill="{BLUE}"/>
        <path class="chev-l" d="M96 114 L80 131 L96 148" fill="none" stroke="#ffffff" stroke-width="10" stroke-linecap="square"/>
        <path class="chev-r" d="M160 114 L176 131 L160 148" fill="none" stroke="#ffffff" stroke-width="10" stroke-linecap="square"/>
        <rect x="122" y="126" width="12" height="12" fill="#ffffff" transform="rotate(20 128 132)"/>
      </g>

      <!-- resize handle: the responsive corner -->
      <rect class="handle" x="192" y="162" width="24" height="24" fill="{ORANGE}"/>

      <!-- content lines under the embed -->
      <rect x="52" y="192" width="152" height="10" fill="{NAVY}" fill-opacity="0.18"/>
      <rect x="52" y="207" width="96" height="10" fill="{NAVY}" fill-opacity="0.1"/>'''

def icon_svg():
    return f'''<svg xmlns="http://www.w3.org/2000/svg" width="256" height="256" viewBox="0 0 256 256">
  <!--
    iFrame (b-iframe) — plugin icon.
    A browser window holding a responsive 16:9 embed with markup chevrons
    and an orange resize handle.
    bPlugins brand: {BLUE} blue, {ORANGE} orange, {NAVY} navy.
    Rest state (0% / 100%) is identical to the static PNG derivatives.
  -->
  <defs>
    <style>{ICON_STYLE}
    </style>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="{BLUE}"/>
      <stop offset="1" stop-color="{BLUE_D}"/>
    </linearGradient>
  </defs>

  <rect width="256" height="256" fill="url(#bg)"/>
{ICON_BODY}
</svg>
'''

def banner_svg():
    pill = 'IFRAME — RESPONSIVE EMBEDS'
    pill_w = int(len(pill) * 16.4 + 20)
    pill_cx = 560 + pill_w / 2
    return f'''<svg xmlns="http://www.w3.org/2000/svg" width="1544" height="500" viewBox="0 0 1544 500">
  <!--
    iFrame (b-iframe) — wordpress.org banner (1544x500).
    bPlugins brand: Space Grotesk, {BLUE} blue, {ORANGE} orange, {NAVY} navy.
  -->
  <defs>
    <style>
      @font-face {{
        font-family: "Space Grotesk";
        font-weight: 700;
        src: url(data:font/ttf;base64,{BOLD}) format("truetype");
      }}
      @font-face {{
        font-family: "Space Grotesk";
        font-weight: 500;
        src: url(data:font/ttf;base64,{MEDIUM}) format("truetype");
      }}
      .sg {{ font-family: "Space Grotesk", "Inter", system-ui, sans-serif; }}
    </style>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="#FFFFFF"/>
      <stop offset="0.55" stop-color="#F2F7FF"/>
      <stop offset="1" stop-color="#E2ECFD"/>
    </linearGradient>
    <radialGradient id="glow" cx="0.5" cy="0.5" r="0.5">
      <stop offset="0" stop-color="{BLUE}" stop-opacity="0.14"/>
      <stop offset="1" stop-color="{BLUE}" stop-opacity="0"/>
    </radialGradient>
    <linearGradient id="tile" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="{BLUE}"/>
      <stop offset="1" stop-color="{BLUE_D}"/>
    </linearGradient>
    <filter id="shadow" x="-25%" y="-25%" width="150%" height="150%">
      <feDropShadow dx="0" dy="20" stdDeviation="22" flood-color="{BLUE_D}" flood-opacity="0.28"/>
    </filter>
  </defs>

  <rect width="1544" height="500" fill="url(#bg)"/>
  <circle cx="300" cy="250" r="300" fill="url(#glow)"/>

  <g fill="{BLUE}" fill-opacity="0.06">
    <circle cx="1400" cy="60" r="110"/>
    <circle cx="1470" cy="430" r="150"/>
  </g>
  <g fill="{ORANGE}" fill-opacity="0.07">
    <circle cx="1230" cy="450" r="60"/>
  </g>

  <!-- ICON ZONE -->
  <g transform="translate(140,90)" filter="url(#shadow)">
    <rect width="320" height="320" fill="url(#tile)"/>
    <g transform="scale(1.25)">{ICON_BODY}
    </g>
  </g>

  <!-- MESSAGE ZONE -->
  <g class="sg">
    <rect x="560" y="100" width="{pill_w}" height="46" fill="{BLUE}" fill-opacity="0.10"/>
    <text x="{pill_cx}" y="130" text-anchor="middle" font-size="20" font-weight="700" letter-spacing="3" fill="{BLUE_D}">{pill}</text>

    <text x="560" y="240" font-size="88" font-weight="700" fill="{NAVY}" letter-spacing="-2">Embed anything.</text>
    <text x="560" y="328" font-size="88" font-weight="700" fill="{NAVY}" letter-spacing="-2">Fit everywhere.</text>

    <rect x="562" y="366" width="120" height="6" fill="{ORANGE}"/>
    <text x="562" y="406" font-size="28" font-weight="500" fill="{SUB}">Videos, maps, sites &amp; docs in responsive iframes — block or shortcode</text>
  </g>

  <image x="1294" y="418" width="210" height="42" href="data:image/svg+xml;base64,{LOGO_B64}"/>
</svg>
'''

if __name__ == '__main__':
    open(os.path.join(HERE, 'icon.svg'), 'w').write(icon_svg())
    open(os.path.join(HERE, 'banner.svg'), 'w').write(banner_svg())
    print('wrote icon.svg, banner.svg')
