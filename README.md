# Infinite Brand Scroll - WordPress Plugin

[![WordPress Version](https://img.shields.io/badge/WordPress-5.8%2B-blue.svg)](https://wordpress.org/)
[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-GPLv2-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

A professional WordPress plugin for displaying stunning 3D brand carousels with infinite scrolling animations. Built with Three.js and GSAP ScrollTrigger.

## ✨ Features

- 🎨 **Beautiful 3D Effects** - Powered by Three.js for stunning visual experiences
- 🔄 **Infinite Scrolling** - Smooth, seamless carousel animation
- 📱 **Fully Responsive** - Works perfectly on all devices and screen sizes
- ♿ **WCAG 2.1 Compliant** - Full accessibility support with ARIA labels
- 🧩 **Multiple Integration Methods** - Shortcode, Widget, Gutenberg Block, PHP
- ⚡ **Performance Optimized** - Conditional asset loading, memory cleanup
- 🛡️ **Security Hardened** - Nonces, sanitization, escaping, capability checks
- 🌐 **Translation Ready** - i18n support with text domain
- 🎛️ **Easy Admin Interface** - Intuitive settings page with brand management
- 🔧 **Highly Customizable** - Configurable speed, height, pause on hover

## 📦 Installation

### From GitHub

1. Download or clone this repository
2. Upload to `/wp-content/plugins/infinite-brand-scroll/`
3. Activate through the WordPress 'Plugins' menu
4. Configure at Settings → Brand Scroll

### From WordPress.org (Coming Soon)

1. Go to Plugins → Add New
2. Search for "Infinite Brand Scroll"
3. Click Install → Activate

## 🚀 Quick Start

### 1. Add Brands

Go to **Settings → Brand Scroll → Brands** tab and add your brand information:
- Brand Title
- Tagline
- Banner Image (1200x675px recommended)
- Logo (optional)

### 2. Display on Your Site

**Method 1: Shortcode** (works everywhere)
```
[infinite_brand_scroll]
```

**Method 2: Gutenberg Block** (in block editor)
- Click (+) to add block
- Search for "Infinite Brand Scroll"
- Configure settings in sidebar

**Method 3: Widget** (in sidebars/footer)
- Go to Appearance → Widgets
- Add "Infinite Brand Scroll" widget

**Method 4: PHP** (in theme files)
```php
<?php echo do_shortcode('[infinite_brand_scroll]'); ?>
```

## 🎨 Customization

### Shortcode Parameters

```
[infinite_brand_scroll speed="3000" height="500" pause_on_hover="true" enable_3d="true"]
```

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `speed` | number | 5000 | Animation speed in milliseconds |
| `height` | number | 600 | Container height in pixels |
| `pause_on_hover` | boolean | true | Pause animation on mouse hover |
| `enable_3d` | boolean | true | Enable 3D rendering effects |

### Examples

**Fast carousel with custom height:**
```
[infinite_brand_scroll speed="2000" height="400"]
```

**Disable pause on hover:**
```
[infinite_brand_scroll pause_on_hover="false"]
```

**Disable 3D for better performance:**
```
[infinite_brand_scroll enable_3d="false"]
```

## 🏗️ File Structure

```
infinite-brand-scroll/
├── infinite-brand-scroll.php      # Main plugin file
├── uninstall.php                  # Cleanup on uninstall
├── readme.txt                     # WordPress.org readme
├── AUDIT.md                       # Comprehensive audit report
├── includes/                      # Core plugin classes
│   ├── class-ibs-assets.php      # Asset management
│   ├── class-ibs-shortcode.php   # Shortcode handler
│   ├── class-ibs-widget.php      # Widget class
│   ├── class-ibs-settings.php    # Admin settings
│   └── class-ibs-block.php       # Gutenberg block
├── assets/
│   ├── js/
│   │   ├── infinite-brand-scroll.js   # Frontend script
│   │   ├── admin.js                   # Admin script
│   │   └── block.js                   # Gutenberg block
│   └── css/
│       ├── infinite-brand-scroll.css  # Frontend styles
│       ├── admin.css                  # Admin styles
│       └── block-editor.css           # Block editor styles
├── languages/                     # Translation files
└── infinite-brand-scroll/         # Original demo (standalone)
```

## 🔒 Security Features

- ✅ ABSPATH checks on all PHP files
- ✅ Nonce verification for AJAX and forms
- ✅ Capability checks (`current_user_can`)
- ✅ Input sanitization (`sanitize_text_field`, `esc_url_raw`)
- ✅ Output escaping (`esc_html`, `esc_attr`, `esc_url`)
- ✅ No SQL queries (uses Options API)

## ♿ Accessibility Features

- ✅ ARIA labels and roles
- ✅ Keyboard navigation support
- ✅ Screen reader compatible
- ✅ Respects `prefers-reduced-motion`
- ✅ Focus indicators
- ✅ Semantic HTML structure

## ⚡ Performance Optimizations

- ✅ Conditional asset loading (only when used)
- ✅ CDN for external libraries (Three.js, GSAP)
- ✅ `requestAnimationFrame()` for smooth animations
- ✅ Proper memory cleanup and disposal
- ✅ Optimized resize handling
- ✅ No render-blocking scripts

## 🌐 Browser Support

- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Edge (latest)
- ⚠️ IE11 (not supported - requires modern ES6 features)

## 🧪 Testing

The plugin has been audited for:
- WordPress Coding Standards
- Security vulnerabilities
- Accessibility (WCAG 2.1)
- Performance bottlenecks
- Cross-browser compatibility

See [AUDIT.md](AUDIT.md) for the complete audit report.

## 📝 Developer Documentation

### Hooks & Filters

**Filters** (coming in v1.1):
```php
apply_filters( 'ibs_default_options', $options );
apply_filters( 'ibs_brand_data', $brands );
apply_filters( 'ibs_script_data', $script_data );
```

**Actions** (coming in v1.1):
```php
do_action( 'ibs_before_render', $brands );
do_action( 'ibs_after_render', $brands );
```

### Class Structure

All classes use the singleton pattern:
```php
$instance = IBS_Assets::get_instance();
```

## 🤝 Contributing

Contributions are welcome! Please:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📋 Requirements

- **WordPress**: 5.8 or higher
- **PHP**: 7.4 or higher
- **JavaScript**: ES6-compatible browser

## 🐛 Known Issues

- Three.js requires modern browsers (no IE11 support)
- Large images may affect initial load time
- Best viewed on devices with WebGL support

## 📜 License

This plugin is licensed under the GPLv2 or later.

```
Copyright (C) 2024 Case Study Labs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## 👨‍💻 Author

**Case Study Labs**
- GitHub: [@therealjohndough](https://github.com/therealjohndough)
- Plugin URI: https://github.com/therealjohndough/infinite-brand-scroll

## 🙏 Credits

- [Three.js](https://threejs.org/) - 3D JavaScript library
- [GSAP](https://greensock.com/) - Animation library
- [WordPress](https://wordpress.org/) - CMS platform

## 📊 Changelog

### Version 1.0.0 (2024)
- Initial release
- 3D scrolling carousel with Three.js
- GSAP ScrollTrigger integration
- Shortcode support with parameters
- Widget support (legacy and block-based)
- Gutenberg block support
- Admin settings page with brand management
- Full accessibility support (WCAG 2.1)
- Security hardening
- Internationalization (i18n) ready
- Multisite compatibility
- Performance optimizations

## 🔮 Roadmap

### Version 1.1 (Planned)
- [ ] Developer hooks (filters and actions)
- [ ] Local library fallback option
- [ ] Import/export settings
- [ ] Animation presets
- [ ] Custom post type for brands

### Version 1.2 (Planned)
- [ ] Template override system
- [ ] REST API endpoints
- [ ] Multiple carousel instances
- [ ] Color customization
- [ ] Advanced animation controls

---

**Made with ❤️ for the WordPress community**
