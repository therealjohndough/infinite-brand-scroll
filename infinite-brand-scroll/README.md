# 🧬 Infinite Brand Scroll Demo

**Note:** This directory contains the original standalone HTML/JS demo. 

For the WordPress plugin version, see the parent directory which contains the full WordPress plugin implementation with:
- WordPress plugin structure
- Admin settings interface
- Security features (nonces, sanitization, escaping)
- Accessibility support (WCAG 2.1)
- Multiple integration methods (shortcode, widget, Gutenberg block)
- Performance optimizations

## 🔧 Using the Standalone Demo

1. Open `index.html` in a browser (or run via a local server for image loading).
2. Place your banner images in the `/assets/` folder.
3. Edit `projects.json` to update project data (title, tagline, image, logo).

## 🖼 Asset Requirements

- Image files: 1200x675 JPG/PNG recommended (placed in `/assets/`)
- Logos: Not used in demo, but placeholder included

## 🚀 Tech Stack

- [Three.js](https://threejs.org/)
- [GSAP + ScrollTrigger](https://greensock.com/scrolltrigger/)

## ✨ Customize

- Adjust Z-distance in `main.js` for faster/slower scroll depth
- Swap text styling in `main.js` canvas draw
- Add fog, lighting, or shaders via Three.js scene

---
Created by Case Study Labs
