=== Infinite Brand Scroll ===
Contributors: casestudylabs
Tags: carousel, brands, scroll, logos, infinite-scroll
Requires at least: 5.8
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display an infinite scrolling 3D carousel of brand logos using Three.js and GSAP.

== Description ==

Infinite Brand Scroll creates stunning 3D brand showcases with smooth scrolling animations. Perfect for displaying partner logos, client portfolios, or brand galleries.

**Features:**

* 🎨 Beautiful 3D effects powered by Three.js
* 🔄 Infinite scrolling carousel animation
* 📱 Fully responsive and mobile-friendly
* ♿ WCAG 2.1 compliant with full accessibility support
* 🎛️ Easy-to-use admin interface
* 🧩 Multiple integration methods (shortcode, widget, Gutenberg block)
* ⚡ Optimized performance with conditional asset loading
* 🎚️ Customizable animation speed and behavior
* 🖱️ Pause on hover functionality
* 🌐 Translation ready (i18n)
* 🔒 Security-focused with proper sanitization and nonce verification

**Integration Methods:**

1. **Shortcode**: `[infinite_brand_scroll]`
2. **Widget**: Add via Appearance → Widgets
3. **Gutenberg Block**: Search for "Infinite Brand Scroll"
4. **PHP**: `<?php echo do_shortcode('[infinite_brand_scroll]'); ?>`

**Shortcode Parameters:**

* `speed` - Animation speed in milliseconds (default: 5000)
* `pause_on_hover` - Pause on hover: true/false (default: true)
* `enable_3d` - Enable 3D effects: true/false (default: true)
* `height` - Container height in pixels (default: 600)

Example: `[infinite_brand_scroll speed="3000" height="500"]`

**Accessibility Features:**

* Full keyboard navigation support
* Screen reader compatible with ARIA labels
* Respects prefers-reduced-motion user settings
* Semantic HTML structure
* Proper focus management

**Performance:**

* Conditional asset loading (only loads when used)
* Uses CDN for Three.js and GSAP libraries
* Optimized rendering with requestAnimationFrame
* Proper cleanup and memory management

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/infinite-brand-scroll/`
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to Settings → Brand Scroll to configure
4. Add your brand logos and information
5. Use the shortcode `[infinite_brand_scroll]` in any post or page

== Frequently Asked Questions ==

= Can I use this with any WordPress theme? =

Yes! Infinite Brand Scroll is designed to work with any properly-coded WordPress theme.

= Does this work with page builders? =

Yes, you can use the shortcode in any page builder that supports WordPress shortcodes.

= Is this plugin accessible? =

Absolutely! The plugin follows WCAG 2.1 guidelines and includes full keyboard navigation, ARIA labels, and respects user motion preferences.

= Can I disable the 3D effects? =

Yes, you can disable 3D effects in the settings or via shortcode parameter `enable_3d="false"`.

= Will this slow down my site? =

No. Assets are only loaded on pages where the carousel is actually used, ensuring optimal performance.

= Is it compatible with multisite? =

Yes, the plugin is fully multisite compatible.

= Can I customize the appearance? =

Yes, you can use custom CSS to style the container and elements. Developer hooks are also available for advanced customization.

== Screenshots ==

1. Admin settings page - General tab
2. Admin settings page - Brands management
3. Frontend carousel display
4. Gutenberg block in editor
5. Widget configuration

== Changelog ==

= 1.0.0 =
* Initial release
* 3D scrolling carousel with Three.js
* GSAP ScrollTrigger integration
* Shortcode support
* Widget support
* Gutenberg block support
* Admin settings page
* Full accessibility support
* Multisite compatibility
* Security hardening (nonces, sanitization, escaping)
* Internationalization ready

== Upgrade Notice ==

= 1.0.0 =
Initial release of Infinite Brand Scroll.

== Developer Hooks ==

**Filters:**

* `ibs_default_options` - Modify default plugin options
* `ibs_shortcode_attributes` - Filter shortcode attributes
* `ibs_brand_data` - Modify brand data before output
* `ibs_script_data` - Modify localized script data

**Actions:**

* `ibs_before_render` - Fired before carousel renders
* `ibs_after_render` - Fired after carousel renders
* `ibs_settings_saved` - Fired when settings are saved

== Privacy Policy ==

This plugin does not collect, store, or share any user data. All brand images and data are stored locally in your WordPress installation.

== Support ==

For support, feature requests, or bug reports, please visit:
https://github.com/therealjohndough/infinite-brand-scroll

== Credits ==

* Three.js - https://threejs.org/
* GSAP - https://greensock.com/
* Created by Case Study Labs
