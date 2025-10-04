# Installation & Setup Guide

## Quick Installation

### Option 1: Upload via WordPress Admin

1. Download the plugin as a ZIP file
2. Log in to your WordPress admin panel
3. Navigate to **Plugins → Add New**
4. Click **Upload Plugin**
5. Choose the ZIP file and click **Install Now**
6. Click **Activate Plugin**

### Option 2: Manual Installation via FTP

1. Download and unzip the plugin files
2. Upload the entire `infinite-brand-scroll` folder to `/wp-content/plugins/`
3. Log in to WordPress admin
4. Go to **Plugins** and activate "Infinite Brand Scroll"

### Option 3: Git Clone (for developers)

```bash
cd /path/to/wordpress/wp-content/plugins/
git clone https://github.com/therealjohndough/infinite-brand-scroll.git
```

Then activate via WordPress admin.

## Initial Setup

### Step 1: Access Settings

After activation, go to **Settings → Brand Scroll** in the WordPress admin menu.

### Step 2: Configure General Settings

In the **General** tab:

1. **Animation Speed**: Set how fast the carousel scrolls (in milliseconds)
   - Default: 5000ms (5 seconds)
   - Range: 1000ms - 20000ms

2. **Pause on Hover**: Check to pause animation when hovering with mouse
   - Recommended: ✅ Enabled

3. **Enable 3D Effects**: Check to use Three.js 3D rendering
   - Recommended: ✅ Enabled (disable if performance is an issue)

Click **Save Changes**.

### Step 3: Add Your Brands

Switch to the **Brands** tab:

1. Click **Add Brand**
2. Fill in the details:
   - **Brand Title**: Name of the brand/client
   - **Tagline**: Short description or slogan
   - **Banner Image**: Main image (1200x675px recommended)
   - **Logo**: Optional logo image
3. Use **Select Image** buttons to choose from Media Library
4. Click **Save Brand**
5. Repeat for each brand you want to display

**Tip**: Add at least 3-5 brands for best visual effect.

### Step 4: Display the Carousel

Choose your preferred method:

#### Method A: Shortcode (Easiest)

Add to any post or page:
```
[infinite_brand_scroll]
```

#### Method B: Gutenberg Block

1. Edit a page with Block Editor
2. Click (+) to add a block
3. Search for "Infinite Brand Scroll"
4. Add the block
5. Configure settings in the right sidebar

#### Method C: Widget

1. Go to **Appearance → Widgets**
2. Find "Infinite Brand Scroll" widget
3. Drag to desired widget area (sidebar, footer, etc.)
4. Configure title and height
5. Save

#### Method D: PHP Template (Advanced)

Add to theme files:
```php
<?php
if ( function_exists( 'infinite_brand_scroll_init' ) ) {
    echo do_shortcode( '[infinite_brand_scroll]' );
}
?>
```

## Customization

### Shortcode Parameters

Customize behavior with parameters:

```
[infinite_brand_scroll speed="3000" height="500" pause_on_hover="true" enable_3d="true"]
```

**Available Parameters:**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `speed` | integer | 5000 | Animation speed in ms |
| `height` | integer | 600 | Container height in px |
| `pause_on_hover` | boolean | true | Pause on mouse hover |
| `enable_3d` | boolean | true | Use 3D rendering |

**Examples:**

Fast carousel:
```
[infinite_brand_scroll speed="2000"]
```

Tall container with continuous animation:
```
[infinite_brand_scroll height="800" pause_on_hover="false"]
```

Performance mode (no 3D):
```
[infinite_brand_scroll enable_3d="false"]
```

### CSS Customization

Add to your theme's Additional CSS or custom stylesheet:

```css
/* Change background color */
.infinite-brand-scroll-container {
    background: #1a1a1a !important;
}

/* Adjust loading text color */
.ibs-loading {
    color: #00ff00 !important;
}

/* Custom focus outline */
.infinite-brand-scroll-container:focus {
    outline: 3px solid #ff6600 !important;
}
```

## Troubleshooting

### Issue: Carousel not displaying

**Solutions:**
1. Verify brands are added in Settings → Brand Scroll → Brands
2. Check that images are uploaded and URLs are valid
3. Clear browser cache and page cache
4. Check browser console for JavaScript errors

### Issue: Images not loading

**Solutions:**
1. Verify image URLs in brand settings
2. Check file permissions on WordPress uploads folder
3. Ensure images are in correct format (JPG, PNG)
4. Try re-uploading images via Media Library

### Issue: Poor performance

**Solutions:**
1. Reduce animation speed (higher number = slower)
2. Disable 3D effects: `enable_3d="false"`
3. Optimize image file sizes (compress before uploading)
4. Reduce number of brands displayed
5. Check for conflicting plugins

### Issue: Not responsive on mobile

**Solutions:**
1. Clear mobile cache
2. Check theme's viewport meta tag
3. Verify no custom CSS is overriding responsive styles
4. Test with a default WordPress theme

### Issue: Accessibility concerns

**Solutions:**
1. Ensure alt text is set for all images
2. Test with screen reader (NVDA, JAWS)
3. Verify keyboard navigation works (Tab, Enter)
4. Check contrast ratios meet WCAG standards

## Browser Compatibility

### Supported Browsers

- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+
- ✅ Opera 76+

### Not Supported

- ❌ Internet Explorer (any version)
- ❌ Very old mobile browsers (pre-2018)

**Reason**: Plugin uses modern ES6 JavaScript and WebGL features.

## Performance Optimization Tips

1. **Image Optimization**
   - Use 1200x675px images (16:9 aspect ratio)
   - Compress images (aim for <200KB per image)
   - Use JPG for photos, PNG for logos with transparency

2. **Limit Brand Count**
   - 5-10 brands is optimal
   - More than 20 may impact performance

3. **Enable Caching**
   - Use a caching plugin (WP Super Cache, W3 Total Cache)
   - Enable browser caching

4. **CDN Usage**
   - The plugin automatically uses CDN for Three.js and GSAP
   - Consider CDN for your images too

5. **Disable 3D on Mobile**
   - Use conditional shortcode based on device
   - Or disable globally for better mobile performance

## Security Notes

This plugin follows WordPress security best practices:

- ✅ All inputs sanitized
- ✅ All outputs escaped
- ✅ Nonce verification on forms
- ✅ Capability checks
- ✅ No SQL injection vulnerabilities
- ✅ CSRF protection

**Safe to use on production sites.**

## Multisite Compatibility

The plugin is fully multisite compatible:

- Can be Network Activated or activated per-site
- Each site has independent settings
- Uninstall cleans up all sites

## Getting Help

### Documentation
- Main README: [README.md](README.md)
- Audit Report: [AUDIT.md](AUDIT.md)
- WordPress.org readme: [readme.txt](readme.txt)

### Support
- GitHub Issues: https://github.com/therealjohndough/infinite-brand-scroll/issues
- WordPress.org Support: (coming soon)

### Contributing
- See [README.md](README.md) for contribution guidelines
- Pull requests welcome!

## Uninstallation

### Safe Removal

1. Go to **Plugins**
2. Deactivate "Infinite Brand Scroll"
3. Click **Delete**

The plugin will automatically:
- Remove all stored settings
- Clean up database options
- Delete uploaded files (if configured)

**Note**: Brand images in Media Library are NOT deleted (they may be used elsewhere).

## Next Steps

- ✅ Add more brands to your carousel
- ✅ Customize colors and styling with CSS
- ✅ Test on different devices and browsers
- ✅ Add to multiple pages with different configurations
- ✅ Share your feedback and suggestions

---

**Need more help?** Open an issue on GitHub or contact the developer.
