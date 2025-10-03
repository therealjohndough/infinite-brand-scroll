# 🧬 Infinite Brand Scroll Demo

A scroll-through 3D brand experience built with Three.js + GSAP + ScrollTrigger.

## 🔧 How to Use

1. Unzip and open `index.html` in a browser (or run via a local server for image loading).
2. Place your banner images in the `/assets/` folder.
3. Edit `projects.json` to update project data (title, tagline, image, logo).

## 🖼 Asset Requirements

- Image files: 1200x675 JPG/PNG recommended (placed in `/assets/`)
- Logos: Not used in demo, but placeholder included

## 📁 Folder Structure

```
.
├── index.html
├── style.css
├── main.js
├── projects.json
├── assets/
│   ├── lionsmane-banner.jpg
│   ├── skyworld-banner.jpg
│   └── atc-banner.jpg
```

## 🚀 Tech Stack

- [Three.js](https://threejs.org/)
- [GSAP + ScrollTrigger](https://greensock.com/scrolltrigger/)

## ✨ Customize

- Adjust Z-distance in `main.js` for faster/slower scroll depth
- Swap text styling in `main.js` canvas draw
- Add fog, lighting, or shaders via Three.js scene

---
Created by Case Study Labs
