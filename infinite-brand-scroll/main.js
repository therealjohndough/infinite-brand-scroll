import * as THREE from 'https://cdn.skypack.dev/three';
import { gsap } from 'https://cdn.skypack.dev/gsap';
import { ScrollTrigger } from 'https://cdn.skypack.dev/gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(75, window.innerWidth/window.innerHeight, 0.1, 1000);
const renderer = new THREE.WebGLRenderer({ alpha: true });
renderer.setSize(window.innerWidth, window.innerHeight);
document.body.appendChild(renderer.domElement);
camera.position.z = 0;

fetch('projects.json')
  .then(res => res.json())
  .then(projects => {
    projects.forEach((proj, i) => {
      const texLoader = new THREE.TextureLoader();
      const texture = texLoader.load('assets/' + proj.image);
      const material = new THREE.MeshBasicMaterial({ map: texture });
      const plane = new THREE.Mesh(new THREE.PlaneGeometry(6, 3.375), material);
      plane.position.z = -i * 15;
      scene.add(plane);

      const canvas = document.createElement('canvas');
      canvas.width = 1024;
      canvas.height = 256;
      const ctx = canvas.getContext('2d');
      ctx.font = 'bold 60px sans-serif';
      ctx.fillStyle = 'white';
      ctx.fillText(proj.tagline, 50, 150);
      const textTexture = new THREE.CanvasTexture(canvas);
      const textMat = new THREE.MeshBasicMaterial({ map: textTexture, transparent: true });
      const textPlane = new THREE.Mesh(new THREE.PlaneGeometry(4, 1), textMat);
      textPlane.position.set(0, -2.5, -i * 15);
      scene.add(textPlane);
    });

    ScrollTrigger.create({
      trigger: "#scroll-container",
      start: "top top",
      end: "+=5000",
      scrub: true,
      onUpdate: (self) => {
        camera.position.z = -self.progress * (projects.length * 15);
      }
    });
  });

function animate() {
  requestAnimationFrame(animate);
  renderer.render(scene, camera);
}
animate();
