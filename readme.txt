=== ALD Business Tools Hub ===
Contributors: hossainmdawlad
Tags: business tools, currency converter, qr generator, invoice generator, seo tools, image tools, profit calculator
Requires at least: 6.0
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPL-3.0-or-later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

A collection of free online business tools for your visitors — currency converter, profit calculator, QR generator, meta tag generator, invoice generator, image compressor, and more.

== Description ==

ALD Business Tools Hub adds a suite of helpful business tools to your WordPress site. Each tool is embedded via shortcode and works entirely in the browser (no page reloads).

**Included Tools:**

* **Currency Converter** — Live exchange rates for 17+ currencies (USD, EUR, GBP, BDT, INR, etc.)
* **Profit Margin Calculator** — Calculate profit, margin, markup, and breakeven
* **QR Code Generator** — Generate and download QR codes for any URL or text
* **Meta Tag Generator** — Generate SEO meta tags + Open Graph + Twitter Cards with live preview
* **Invoice Generator** — Create professional invoices and download as PDF
* **Color Palette Generator** — Generate complementary, triadic, analogic palettes from a base color
* **Image Compressor** — Compress images in-browser before upload
* **Social Media Image Resizer** — Resize images to platform-specific dimensions (Facebook, Instagram, Twitter, LinkedIn, YouTube, Pinterest)
* **UTM Link Builder** — Create tracked campaign URLs with QR codes and history

**Shortcodes:**

* `[bth_tool slug="currency-converter"]` — Embed a single tool
* `[bth_tools_grid category="finance" count="6" columns="3"]` — Display a grid of tools

**Features:**

* All tools work in-browser (no server load for calculations)
* Currency converter uses free Open-Meteo / ExchangeRate-API
* Image tools use Canvas API (no external services)
* Responsive design
* Dark mode compatible (inherits theme CSS variables)
* Tool enable/disable via settings
* Custom post type for managing tools
* 6 default tool categories

== Installation ==

1. Upload the `ald-business-tools-hub` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to **Tools Hub → Add New** to create tools
4. Select the tool type in the Tool Settings meta box
5. Use shortcodes to embed tools in any page or post

== Frequently Asked Questions ==

= Do I need an API key? =
No. The currency converter works without an API key using the free ExchangeRate-API tier. An optional API key can be added in Settings for higher rate limits.

= Can I add my own custom tools? =
Yes. Select "Custom" as the tool type and use the content editor to add any HTML/JS tool.

= Are the tools mobile-friendly? =
Yes. All tools are fully responsive.

== Changelog ==

= 1.0.0 =
* Initial release
