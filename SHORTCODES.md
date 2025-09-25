# Ciabay Divi Shortcodes

This document explains how to use the custom shortcodes created for the Ciabay website.

## Available Shortcodes

### 1. Unified Carousel (NEW)
```
[ciabay_carousel height="600px" main_title="HOY EL CAMPO EXIGE MÁS" main_subtitle="MÁS ROBUSTEZ, MÁS TECNOLOGÍA, MÁS PRECISIÓN, MÁS EFICIENCIA." main_question="¿VOS ESTÁS LISTO?"]
```

**Parameters:**
- `height` (optional): Height of the carousel (default: 600px)
- `main_title` (optional): Main header title
- `main_subtitle` (optional): Subtitle text
- `main_question` (optional): Call to action question

**Features:**
- **Desktop Layout**: Content panel on left, cards on right (exactly like design image 1)
- **Mobile Layout**: Single card on top, content below (exactly like design image 2)
- **No navigation elements** (arrows/dots) - click/touch cards to navigate
- **Blur effect** on inactive cards
- **Touch/swipe support** on mobile
- **Keyboard navigation** (arrow keys)
- **Responsive design** with separate desktop/mobile layouts
- **Performance optimized** with CSS transforms and blur filters

### 2. Legacy Hero Carousel (DEPRECATED)
```
[ciabay_hero_carousel height="500px" autoplay="true"]
```
*Note: This shortcode is deprecated. Use [ciabay_carousel] instead.*

### 2. Main Content Header
```
[ciabay_main_header title1="HOY EL CAMPO EXIGE MÁS" title2="MÁS ROBUSTEZ, MÁS TECNOLOGÍA, MÁS PRECISIÓN, MÁS EFICIENCIA." title3="¿VOS ESTÁS LISTO?"]
```

**Parameters:**
- `title1` (optional): Main title
- `title2` (optional): Subtitle
- `title3` (optional): Call to action text

### 3. Video Section
```
[ciabay_video_section video_url="https://youtube.com/embed/VIDEO_ID" thumbnail="/path/to/thumbnail.jpg" description="Única empresa paraguaya que te ofrece todo lo que el productor necesita."]
```

**Parameters:**
- `video_url`: URL to the video (YouTube embed URL recommended)
- `thumbnail`: Path to video thumbnail image
- `description` (optional): Text below the video

### 4. Stats Section
```
[ciabay_stats title="CONFIANZA RESPALDADA POR RESULTADOS"]
```

**Parameters:**
- `title` (optional): Section title

**Features:**
- Animated counters
- Responsive grid layout
- Scroll-triggered animation

## How to Use in Divi

1. **In Divi Builder:**
   - Add a "Code" module
   - Paste the shortcode in the content area
   - Save and preview

2. **In Text Module:**
   - Add a "Text" module
   - Switch to Text tab
   - Insert the shortcode
   - Save and preview

3. **Full Homepage Example:**
```
[ciabay_hero_carousel]

[ciabay_main_header]

[ciabay_video_section video_url="https://youtube.com/embed/YOUR_VIDEO_ID" thumbnail="/wp-content/themes/ciabay-divi/assets/images/video-thumbnail.jpg"]

[ciabay_stats]
```

## Required Images

Make sure to add these images to `/wp-content/themes/ciabay-divi/assets/images/`:

- `servicios.jpg` - Services slide image
- `insumos.jpg` - Main slide image (agricultural inputs)
- `maquinas.jpg` - Machines slide image
- `video-thumbnail.jpg` - Video thumbnail
- `ciabay-logo.png` - Company logo

## Customization

### CSS Customization
Edit `/wp-content/themes/ciabay-divi/css/custom.css` to modify styles.

### JavaScript Customization
Edit `/wp-content/themes/ciabay-divi/js/carousel.js` to modify functionality.

### Colors
Main brand colors used:
- Primary Blue: #1e3c72
- Secondary Blue: #2a5298
- Orange: #ff6b35
- Light Gray: #f8f9fa

## Mobile Responsiveness

All shortcodes are fully responsive:
- **Desktop (>768px)**: 3D carousel effect, full layout
- **Tablet (768px)**: Adapted layout
- **Mobile (<768px)**: Single column, touch-friendly

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- iOS Safari
- Android Chrome

## Performance

- CSS and JS files are minified for production
- Images should be optimized (WebP format recommended)
- Lazy loading supported for images
- Smooth animations with CSS transforms
