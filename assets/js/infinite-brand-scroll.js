/**
 * Infinite Brand Scroll - Main Frontend Script
 * 
 * @package InfiniteBrandScroll
 */

import * as THREE from 'https://cdn.skypack.dev/three';
import { gsap } from 'https://cdn.skypack.dev/gsap';
import { ScrollTrigger } from 'https://cdn.skypack.dev/gsap/ScrollTrigger';

// Register GSAP ScrollTrigger plugin.
gsap.registerPlugin(ScrollTrigger);

/**
 * Initialize the infinite brand scroll.
 */
class InfiniteBrandScroll {
	constructor(container, brands, options) {
		this.container = container;
		this.brands = brands;
		this.options = options;
		this.scene = null;
		this.camera = null;
		this.renderer = null;
		this.planes = [];
		this.animationFrame = null;
		this.scrollTrigger = null;
		
		this.init();
	}

	/**
	 * Initialize the scene.
	 */
	init() {
		// Hide loading message.
		const loading = this.container.querySelector('.ibs-loading');
		if (loading) {
			loading.setAttribute('aria-busy', 'false');
		}

		// Create scene.
		this.scene = new THREE.Scene();
		
		// Get container dimensions.
		const width = this.container.offsetWidth;
		const height = this.container.offsetHeight;
		
		// Create camera.
		this.camera = new THREE.PerspectiveCamera(75, width / height, 0.1, 1000);
		this.camera.position.z = 0;
		
		// Create renderer.
		this.renderer = new THREE.WebGLRenderer({ 
			alpha: true,
			antialias: true 
		});
		this.renderer.setSize(width, height);
		this.renderer.setPixelRatio(window.devicePixelRatio);
		this.container.appendChild(this.renderer.domElement);
		
		// Add resize handler.
		window.addEventListener('resize', () => this.handleResize());
		
		// Create brand planes.
		this.createBrandPlanes();
		
		// Setup scroll trigger.
		this.setupScrollTrigger();
		
		// Start animation loop.
		this.animate();
		
		// Remove loading message.
		if (loading) {
			loading.remove();
		}
	}

	/**
	 * Create 3D planes for each brand.
	 */
	createBrandPlanes() {
		this.brands.forEach((brand, i) => {
			// Load brand image.
			const textureLoader = new THREE.TextureLoader();
			
			// Set CORS for texture loading.
			textureLoader.crossOrigin = 'anonymous';
			
			textureLoader.load(
				brand.image,
				(texture) => {
					// Create plane for brand image.
					const material = new THREE.MeshBasicMaterial({ map: texture });
					const plane = new THREE.Mesh(new THREE.PlaneGeometry(6, 3.375), material);
					plane.position.z = -i * 15;
					this.scene.add(plane);
					this.planes.push(plane);

					// Create text plane for tagline.
					if (brand.tagline) {
						const canvas = document.createElement('canvas');
						canvas.width = 1024;
						canvas.height = 256;
						const ctx = canvas.getContext('2d');
						ctx.font = 'bold 60px sans-serif';
						ctx.fillStyle = 'white';
						ctx.textAlign = 'center';
						ctx.fillText(brand.tagline, 512, 150);
						
						const textTexture = new THREE.CanvasTexture(canvas);
						const textMat = new THREE.MeshBasicMaterial({ 
							map: textTexture, 
							transparent: true 
						});
						const textPlane = new THREE.Mesh(new THREE.PlaneGeometry(4, 1), textMat);
						textPlane.position.set(0, -2.5, -i * 15);
						this.scene.add(textPlane);
					}
				},
				undefined,
				(error) => {
					console.error('Error loading texture:', error);
				}
			);
		});
	}

	/**
	 * Setup scroll trigger animation.
	 */
	setupScrollTrigger() {
		const scrollDistance = this.options.animationSpeed || 5000;
		
		this.scrollTrigger = ScrollTrigger.create({
			trigger: this.container,
			start: 'top top',
			end: `+=${scrollDistance}`,
			scrub: true,
			onUpdate: (self) => {
				this.camera.position.z = -self.progress * (this.brands.length * 15);
			}
		});

		// Pause on hover if enabled.
		if (this.options.pauseOnHover) {
			this.container.addEventListener('mouseenter', () => {
				if (this.scrollTrigger) {
					this.scrollTrigger.disable();
				}
			});

			this.container.addEventListener('mouseleave', () => {
				if (this.scrollTrigger) {
					this.scrollTrigger.enable();
				}
			});
		}
	}

	/**
	 * Animation loop.
	 */
	animate() {
		this.animationFrame = requestAnimationFrame(() => this.animate());
		this.renderer.render(this.scene, this.camera);
	}

	/**
	 * Handle window resize.
	 */
	handleResize() {
		const width = this.container.offsetWidth;
		const height = this.container.offsetHeight;
		
		this.camera.aspect = width / height;
		this.camera.updateProjectionMatrix();
		this.renderer.setSize(width, height);
	}

	/**
	 * Destroy the instance and clean up.
	 */
	destroy() {
		// Cancel animation frame.
		if (this.animationFrame) {
			cancelAnimationFrame(this.animationFrame);
		}

		// Kill scroll trigger.
		if (this.scrollTrigger) {
			this.scrollTrigger.kill();
		}

		// Dispose Three.js objects.
		if (this.renderer) {
			this.renderer.dispose();
			if (this.renderer.domElement && this.renderer.domElement.parentNode) {
				this.renderer.domElement.parentNode.removeChild(this.renderer.domElement);
			}
		}

		// Clean up scene.
		if (this.scene) {
			this.scene.traverse((object) => {
				if (object.geometry) {
					object.geometry.dispose();
				}
				if (object.material) {
					if (object.material.map) {
						object.material.map.dispose();
					}
					object.material.dispose();
				}
			});
		}
	}
}

/**
 * Initialize all brand scroll instances on the page.
 */
document.addEventListener('DOMContentLoaded', () => {
	const containers = document.querySelectorAll('.infinite-brand-scroll-container');
	
	containers.forEach((container) => {
		try {
			const brands = JSON.parse(container.getAttribute('data-brands') || '[]');
			const options = JSON.parse(container.getAttribute('data-options') || '{}');
			
			if (brands.length > 0 && options.enable3D !== false) {
				new InfiniteBrandScroll(container, brands, options);
			} else if (brands.length === 0) {
				container.innerHTML = '<p style="text-align: center; padding: 20px;">' + 
					'No brands configured. Please add brands in the plugin settings.' +
					'</p>';
			}
		} catch (error) {
			console.error('Error initializing Infinite Brand Scroll:', error);
			container.innerHTML = '<p style="text-align: center; padding: 20px; color: #dc3232;">' + 
				'Error loading brand carousel.' +
				'</p>';
		}
	});
});

// Support for reduced motion preference.
if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
	// Disable animations for users who prefer reduced motion.
	document.documentElement.style.setProperty('--ibs-animation-speed', '0s');
}
