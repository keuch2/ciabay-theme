/**
 * Ciabay Hero Carousel with 3D Effect and Content Carousel Synchronization
 */
jQuery(document).ready(function($) {
    
    // Initialize both carousels
    function initCarousels() {
        const heroCarousel = $('.ciabay-hero-carousel');
        const contentCarousel = $('.ciabay-content-carousel');
        
        if (heroCarousel.length === 0) return;
        
        const slides = heroCarousel.find('.carousel-slide');
        const contentSlides = contentCarousel.find('.content-slide');
        const dots = heroCarousel.find('.dot');
        const prevBtn = heroCarousel.find('.prev-btn');
        const nextBtn = heroCarousel.find('.next-btn');
        
        let currentSlide = 1; // Start with middle slide (Insumos)
        let isAnimating = false;
        let autoplayInterval;
        
        // Set initial state
        updateCarousels();
        
        // Auto-play functionality
        const autoplay = heroCarousel.data('autoplay');
        if (autoplay === 'true' || autoplay === true) {
            startAutoplay();
        }
        
        // Navigation event handlers
        nextBtn.on('click', function() {
            if (!isAnimating) {
                nextSlide();
            }
        });
        
        prevBtn.on('click', function() {
            if (!isAnimating) {
                prevSlide();
            }
        });
        
        // Dot navigation
        dots.on('click', function() {
            if (!isAnimating) {
                const targetSlide = parseInt($(this).data('slide'));
                if (targetSlide !== currentSlide) {
                    currentSlide = targetSlide;
                    updateCarousels();
                }
            }
        });
        
        // Slide click navigation (for desktop)
        slides.on('click', function() {
            if (window.innerWidth > 768 && !isAnimating) {
                const slideIndex = $(this).index();
                if (slideIndex !== currentSlide) {
                    currentSlide = slideIndex;
                    updateCarousels();
                }
            }
        });
        
        // Touch/swipe support for mobile
        let startX = 0;
        let endX = 0;
        
        heroCarousel.on('touchstart', function(e) {
            startX = e.originalEvent.touches[0].clientX;
        });
        
        heroCarousel.on('touchend', function(e) {
            endX = e.originalEvent.changedTouches[0].clientX;
            handleSwipe();
        });
        
        function handleSwipe() {
            const threshold = 50;
            const diff = startX - endX;
            
            if (Math.abs(diff) > threshold && !isAnimating) {
                if (diff > 0) {
                    nextSlide(); // Swipe left - next slide
                } else {
                    prevSlide(); // Swipe right - previous slide
                }
            }
        }
        
        // Keyboard navigation
        $(document).on('keydown', function(e) {
            if (heroCarousel.is(':visible') && !isAnimating) {
                if (e.key === 'ArrowLeft') {
                    prevSlide();
                } else if (e.key === 'ArrowRight') {
                    nextSlide();
                }
            }
        });
        
        // Pause autoplay on hover
        heroCarousel.on('mouseenter', function() {
            stopAutoplay();
        });
        
        heroCarousel.on('mouseleave', function() {
            if (autoplay === 'true' || autoplay === true) {
                startAutoplay();
            }
        });
        
        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            updateCarousels();
        }
        
        function prevSlide() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            updateCarousels();
        }
        
        function updateCarousels() {
            isAnimating = true;
            
            // Update 3D carousel slides
            slides.removeClass('active main-slide');
            slides.eq(currentSlide).addClass('active');
            
            // Special handling for main slide (Insumos)
            if (currentSlide === 1) {
                slides.eq(currentSlide).addClass('main-slide');
            }
            
            // Update content carousel slides
            contentSlides.removeClass('active');
            contentSlides.eq(currentSlide).addClass('active');
            
            // Update dots
            dots.removeClass('active');
            dots.eq(currentSlide).addClass('active');
            
            // Apply 3D transforms for desktop
            if (window.innerWidth > 768) {
                applyDesktop3DEffect();
            } else {
                applyMobileEffect();
            }
            
            // Trigger custom event for other components
            $(document).trigger('ciabayCarouselChange', [currentSlide]);
            
            // Reset animation flag
            setTimeout(function() {
                isAnimating = false;
            }, 600);
        }
        
        function applyDesktop3DEffect() {
            slides.each(function(index) {
                const slide = $(this);
                const offset = index - currentSlide;
                
                slide.css({
                    'z-index': offset === 0 ? 10 : 1,
                    'opacity': offset === 0 ? 1 : 0.7
                });
                
                if (offset === -1) {
                    // Left slide
                    slide.css({
                        'left': '10%',
                        'transform': 'rotateY(45deg) translateZ(-100px)',
                        'width': '350px',
                        'height': '450px'
                    });
                } else if (offset === 0) {
                    // Center slide
                    slide.css({
                        'left': '50%',
                        'transform': 'translateX(-50%) rotateY(0deg) translateZ(0px)',
                        'width': '400px',
                        'height': '500px'
                    });
                } else if (offset === 1) {
                    // Right slide
                    slide.css({
                        'left': 'auto',
                        'right': '10%',
                        'transform': 'rotateY(-45deg) translateZ(-100px)',
                        'width': '350px',
                        'height': '450px'
                    });
                } else {
                    // Hidden slides
                    slide.css({
                        'opacity': '0',
                        'transform': 'translateZ(-200px)'
                    });
                }
            });
        }
        
        function applyMobileEffect() {
            slides.each(function(index) {
                const slide = $(this);
                
                if (index === currentSlide) {
                    slide.css({
                        'opacity': '1',
                        'transform': 'translateX(-50%) scale(1)',
                        'z-index': '10',
                        'pointer-events': 'auto'
                    });
                } else {
                    slide.css({
                        'opacity': '0',
                        'transform': 'translateX(-50%) scale(0.8)',
                        'z-index': '1',
                        'pointer-events': 'none'
                    });
                }
            });
        }
        
        function startAutoplay() {
            stopAutoplay();
            autoplayInterval = setInterval(function() {
                if (!isAnimating) {
                    nextSlide();
                }
            }, 5000);
        }
        
        function stopAutoplay() {
            if (autoplayInterval) {
                clearInterval(autoplayInterval);
                autoplayInterval = null;
            }
        }
        
        // Handle window resize
        $(window).on('resize', function() {
            updateCarousels();
        });
        
        // Expose functions for external control
        window.ciabayCarousel = {
            goToSlide: function(slideIndex) {
                if (slideIndex >= 0 && slideIndex < slides.length && slideIndex !== currentSlide && !isAnimating) {
                    currentSlide = slideIndex;
                    updateCarousels();
                }
            },
            next: nextSlide,
            prev: prevSlide,
            getCurrentSlide: function() {
                return currentSlide;
            }
        };
    }
    
    // Video player functionality
    function initVideoPlayer() {
        $('.play-button').on('click', function() {
            const videoUrl = $(this).data('video');
            if (videoUrl) {
                // Create modal or embed video player
                openVideoModal(videoUrl);
            }
        });
    }
    
    function openVideoModal(videoUrl) {
        // Simple video modal implementation
        const modal = $('<div class="video-modal">' +
            '<div class="video-modal-content">' +
                '<span class="video-close">&times;</span>' +
                '<iframe src="' + videoUrl + '" frameborder="0" allowfullscreen></iframe>' +
            '</div>' +
        '</div>');
        
        $('body').append(modal);
        modal.fadeIn();
        
        // Close modal
        modal.find('.video-close, .video-modal').on('click', function(e) {
            if (e.target === this) {
                modal.fadeOut(function() {
                    modal.remove();
                });
            }
        });
        
        // ESC key to close
        $(document).on('keydown.videoModal', function(e) {
            if (e.key === 'Escape') {
                modal.fadeOut(function() {
                    modal.remove();
                });
                $(document).off('keydown.videoModal');
            }
        });
    }
    
    // Stats counter animation
    function initStatsCounter() {
        const statsSection = $('.ciabay-stats-section');
        if (statsSection.length === 0) return;
        
        let hasAnimated = false;
        
        function animateStats() {
            if (hasAnimated) return;
            hasAnimated = true;
            
            $('.stat-number').each(function() {
                const $this = $(this);
                const text = $this.text();
                const hasPlus = text.includes('+');
                const hasPercent = text.includes('%');
                const number = parseInt(text.replace(/[^0-9]/g, ''));
                
                $this.text('0');
                
                $({ counter: 0 }).animate({ counter: number }, {
                    duration: 2000,
                    easing: 'swing',
                    step: function() {
                        let value = Math.ceil(this.counter);
                        if (hasPlus) value = '+' + value;
                        if (hasPercent) value = value + '%';
                        $this.text(value);
                    }
                });
            });
        }
        
        // Trigger animation when section comes into view
        $(window).on('scroll', function() {
            const sectionTop = statsSection.offset().top;
            const sectionHeight = statsSection.outerHeight();
            const windowTop = $(window).scrollTop();
            const windowHeight = $(window).height();
            
            if (windowTop + windowHeight > sectionTop + sectionHeight / 2) {
                animateStats();
            }
        });
        
        // Check if already in view
        if ($(window).scrollTop() + $(window).height() > statsSection.offset().top + statsSection.outerHeight() / 2) {
            animateStats();
        }
    }
    
    // Search toggle functionality
    function initSearchToggle() {
        const $searchToggle = $('.search-toggle-btn');
        const $searchDropdown = $('.header-search-dropdown');
        const $searchInput = $searchDropdown.find('input[type="search"]');
        
        // Toggle search dropdown
        $searchToggle.off('click').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            $searchDropdown.toggleClass('active');
            
            // Focus on search input when opened
            if ($searchDropdown.hasClass('active')) {
                setTimeout(function() {
                    $searchInput.trigger('focus');
                }, 100);
            }
        });
        
        // Close search dropdown when clicking outside
        $(document).off('click.searchToggle').on('click.searchToggle', function(e) {
            if (!$(e.target).closest('.header-search-dropdown, .search-toggle-btn').length) {
                $searchDropdown.removeClass('active');
            }
        });
        
        // Close search dropdown on ESC key
        $(document).off('keydown.searchToggle').on('keydown.searchToggle', function(e) {
            if (e.key === 'Escape' && $searchDropdown.hasClass('active')) {
                e.preventDefault();
                $searchDropdown.removeClass('active');
                $searchToggle.trigger('focus');
            }
        });
        
        // Prevent clicks inside dropdown from closing it
        $searchDropdown.off('click').on('click', function(e) {
            e.stopPropagation();
        });
    }
    
    // Mobile menu functionality
    function initMobileMenu() {
        // Toggle open/close (delegated + namespaced)
        $(document)
            .off('click.mobileMenuToggle')
            .on('click.mobileMenuToggle', '.mobile-menu-toggle', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const $toggle = $(this);
                $toggle.toggleClass('active');
                $('.mobile-menu').toggleClass('active');
                
                // Animate hamburger
                if ($toggle.hasClass('active')) {
                    $toggle.find('span:nth-child(1)').css('transform', 'rotate(45deg) translate(5px, 5px)');
                    $toggle.find('span:nth-child(2)').css('opacity', '0');
                    $toggle.find('span:nth-child(3)').css('transform', 'rotate(-45deg) translate(7px, -6px)');
                } else {
                    $toggle.find('span').css({
                        'transform': 'none',
                        'opacity': '1'
                    });
                }
            });
        
        // Mobile dropdown functionality (delegated + namespaced)
        $(document)
            .off('click.mobileSubmenuToggle')
            .on('click.mobileSubmenuToggle', '.mobile-menu .menu-item-has-children > a', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const $parent = $(this).parent();
                const $submenu = $parent.find('.sub-menu').first();
                
                // Toggle current submenu
                $parent.toggleClass('active');
                $submenu.toggleClass('active');
                
                // Close siblings
                $parent.siblings('.menu-item-has-children').removeClass('active').find('.sub-menu').removeClass('active');
            });
        
        // Close menu when clicking outside (namespaced)
        $(document)
            .off('click.mobileMenuOutside')
            .on('click.mobileMenuOutside', function(e) {
                if (!$(e.target).closest('.mobile-nav, .mobile-menu, .mobile-menu-toggle').length) {
                    $('.mobile-menu-toggle').removeClass('active');
                    $('.mobile-menu').removeClass('active');
                    $('.mobile-menu .menu-item-has-children').removeClass('active');
                    $('.mobile-menu .sub-menu').removeClass('active');
                    $('.mobile-menu-toggle span').css({
                        'transform': 'none',
                        'opacity': '1'
                    });
                }
            });
        
        // Close menu on window resize (namespaced)
        $(window)
            .off('resize.mobileMenu')
            .on('resize.mobileMenu', function() {
                if ($(window).width() > 768) {
                    $('.mobile-menu-toggle').removeClass('active');
                    $('.mobile-menu').removeClass('active');
                    $('.mobile-menu .menu-item-has-children').removeClass('active');
                    $('.mobile-menu .sub-menu').removeClass('active');
                    $('.mobile-menu-toggle span').css({
                        'transform': 'none',
                        'opacity': '1'
                    });
                }
            });
    }
    
    // Initialize all components
    initCarousels(); // Updated function name
    initVideoPlayer();
    initStatsCounter();
    initSearchToggle();
    initMobileMenu();
    
    // CSS for video modal
    if ($('.video-modal-styles').length === 0) {
        $('<style class="video-modal-styles">' +
            '.video-modal {' +
                'display: none;' +
                'position: fixed;' +
                'z-index: 9999;' +
                'left: 0;' +
                'top: 0;' +
                'width: 100%;' +
                'height: 100%;' +
                'background-color: rgba(0,0,0,0.8);' +
            '}' +
            '.video-modal-content {' +
                'position: relative;' +
                'margin: 5% auto;' +
                'width: 90%;' +
                'max-width: 800px;' +
                'height: 70%;' +
            '}' +
            '.video-modal iframe {' +
                'width: 100%;' +
                'height: 100%;' +
            '}' +
            '.video-close {' +
                'position: absolute;' +
                'top: -40px;' +
                'right: 0;' +
                'color: white;' +
                'font-size: 30px;' +
                'font-weight: bold;' +
                'cursor: pointer;' +
            '}' +
            '.video-close:hover {' +
                'opacity: 0.7;' +
            '}' +
        '</style>').appendTo('head');
    }
    
    // Initialize unified carousel
    initUnifiedCarousel();
    
    /**
     * Unified Ciabay Carousel - New Implementation
     */
    function initUnifiedCarousel() {
    const carousel = $('.ciabay-unified-carousel');
    if (carousel.length === 0) return;
    
    let currentSlide = 1; // Start with INSUMOS (middle slide)
    let isAnimating = false;
    
    const desktopCards = $('.carousel-card');
    const mobileCards = $('.mobile-carousel-card');
    const contentPanels = $('.slide-content-panel');
    const mobileContentPanels = $('.mobile-slide-content');
    
    // Initialize carousel state
    updateCarouselState();
    
    // Desktop card click events
    desktopCards.on('click', function(e) {
        e.preventDefault();
        if (isAnimating) return;
        
        const targetSlide = parseInt($(this).data('slide'));
        if (targetSlide !== currentSlide) {
            currentSlide = targetSlide;
            updateCarouselState();
        }
    });
    
    // Mobile card click events
    mobileCards.on('click', function(e) {
        e.preventDefault();
        if (isAnimating) return;
        
        const targetSlide = parseInt($(this).data('slide'));
        if (targetSlide !== currentSlide) {
            currentSlide = targetSlide;
            updateCarouselState();
        }
    });
    
    // Touch/swipe support for mobile cards
    let startX = 0;
    let endX = 0;
    
    $('.mobile-card-container').on('touchstart', function(e) {
        startX = e.originalEvent.touches[0].clientX;
    });
    
    $('.mobile-card-container').on('touchend', function(e) {
        endX = e.originalEvent.changedTouches[0].clientX;
        handleSwipe();
    });
    
    function handleSwipe() {
        const threshold = 50;
        const diff = startX - endX;
        
        if (Math.abs(diff) > threshold && !isAnimating) {
            if (diff > 0) {
                // Swipe left - next slide
                currentSlide = (currentSlide + 1) % 3;
            } else {
                // Swipe right - previous slide
                currentSlide = (currentSlide - 1 + 3) % 3;
            }
            updateCarouselState();
        }
    }
    
    // Keyboard navigation
    $(document).off('keydown.unifiedCarousel').on('keydown.unifiedCarousel', function(e) {
        if (!carousel.is(':visible') || isAnimating) return;
        
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            currentSlide = (currentSlide - 1 + 3) % 3;
            updateCarouselState();
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            currentSlide = (currentSlide + 1) % 3;
            updateCarouselState();
        }
    });
    
    function updateCarouselState() {
        isAnimating = true;
        
        // Update desktop cards
        desktopCards.each(function(index) {
            const card = $(this);
            const slideIndex = parseInt(card.data('slide'));
            
            if (slideIndex === currentSlide) {
                card.addClass('active');
                updateDesktopCardPosition(card, slideIndex, true);
            } else {
                card.removeClass('active');
                updateDesktopCardPosition(card, slideIndex, false);
            }
        });
        
        // Update mobile cards
        mobileCards.each(function(index) {
            const card = $(this);
            const slideIndex = parseInt(card.data('slide'));
            
            if (slideIndex === currentSlide) {
                card.addClass('active');
            } else {
                card.removeClass('active');
            }
        });
        
        // Update content panels (desktop)
        contentPanels.each(function(index) {
            const panel = $(this);
            const slideIndex = parseInt(panel.data('slide'));
            
            if (slideIndex === currentSlide) {
                panel.addClass('active');
            } else {
                panel.removeClass('active');
            }
        });
        
        // Update mobile content panels
        mobileContentPanels.each(function(index) {
            const panel = $(this);
            const slideIndex = parseInt(panel.data('slide'));
            
            if (slideIndex === currentSlide) {
                panel.addClass('active');
            } else {
                panel.removeClass('active');
            }
        });
        
        // Trigger custom event
        $(document).trigger('ciabayUnifiedCarouselChange', [currentSlide]);
        
        // Reset animation flag
        setTimeout(function() {
            isAnimating = false;
        }, 600);
    }
    
    function updateDesktopCardPosition(card, slideIndex, isActive) {
        // Apply dynamic positioning based on current active slide
        if (isActive) {
            // Center position for active card
            card.css({
                'left': '50%',
                'right': 'auto',
                'top': '0',
                'transform': 'translateX(-50%) scale(1)',
                'z-index': '10',
                'filter': 'none',
                'opacity': '1'
            });
        } else {
            // Position inactive cards based on their relationship to active
            const offset = slideIndex - currentSlide;
            
            if (offset === -1 || (currentSlide === 0 && slideIndex === 2)) {
                // Left position
                card.css({
                    'left': '0',
                    'right': 'auto',
                    'top': '40px',
                    'transform': 'perspective(1000px) rotateY(15deg) scale(0.85)',
                    'z-index': '1',
                    'filter': 'blur(2px)',
                    'opacity': '0.7'
                });
            } else if (offset === 1 || (currentSlide === 2 && slideIndex === 0)) {
                // Right position
                card.css({
                    'left': 'auto',
                    'right': '0',
                    'top': '40px',
                    'transform': 'perspective(1000px) rotateY(-15deg) scale(0.85)',
                    'z-index': '1',
                    'filter': 'blur(2px)',
                    'opacity': '0.7'
                });
            }
        }
    }
    
    // Handle window resize
    $(window).off('resize.unifiedCarousel').on('resize.unifiedCarousel', function() {
        // Recalculate positions on resize
        setTimeout(updateCarouselState, 100);
    });
    
    // Expose API for external control
    window.ciabayUnifiedCarousel = {
        goToSlide: function(slideIndex) {
            if (slideIndex >= 0 && slideIndex < 3 && slideIndex !== currentSlide && !isAnimating) {
                currentSlide = slideIndex;
                updateCarouselState();
                return true;
            }
            return false;
        },
        getCurrentSlide: function() {
            return currentSlide;
        },
        nextSlide: function() {
            if (!isAnimating) {
                currentSlide = (currentSlide + 1) % 3;
                updateCarouselState();
                return true;
            }
            return false;
        },
        prevSlide: function() {
            if (!isAnimating) {
                currentSlide = (currentSlide - 1 + 3) % 3;
                updateCarouselState();
                return true;
            }
            return false;
        }
    };
    }
});
