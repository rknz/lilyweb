/* Lily Interiors — Front-end Behavior, Interactive Components & Project Details Controls */
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    // ============================================================
    // 0. Theme Switcher (Light / Dark Mode)
    // ============================================================
    var themeToggleBtns = document.querySelectorAll('#theme-toggle, [data-theme-toggle-drawer], [data-theme-toggle], .theme-toggle-btn');
    
    function toggleTheme() {
        var currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
        var nextTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-theme', nextTheme);
        try {
            localStorage.setItem('lily_theme', nextTheme);
            document.cookie = "LILY_THEME=" + nextTheme + ";path=/;max-age=" + (86400 * 365);
        } catch(e) {}
    }

    themeToggleBtns.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            toggleTheme();
        });
    });

    // ============================================================
    // 0.1 Language Switcher (Cookie persistence + pill toggle)
    // ============================================================
    document.querySelectorAll('.lang-switcher-pill').forEach(function(pill) {
        pill.addEventListener('click', function(e) {
            var href = pill.getAttribute('href') || '';
            var match = href.match(/[?&]lang=(\w+)/);
            if (match && match[1]) {
                document.cookie = "LILY_LANG=" + match[1] + ";path=/;max-age=" + (86400 * 365);
            }
        });
    });

    // ============================================================
    // 0.2 Performance & Cookie Notice Banner
    // ============================================================
    var cookieBanner = document.getElementById('cookie-notice');
    if (cookieBanner) {
        var isAck = false;
        try {
            isAck = localStorage.getItem('lily_cookie_ack') === '1';
        } catch(e) {}

        if (!isAck) {
            setTimeout(function() {
                cookieBanner.classList.add('is-visible');
            }, 1200);
        }

        var acceptBtn = document.getElementById('cookie-accept-btn');
        var closeBtn = document.getElementById('cookie-close-btn');

        function dismissCookie() {
            cookieBanner.classList.remove('is-visible');
            try {
                localStorage.setItem('lily_cookie_ack', '1');
            } catch(e) {}
        }

        if (acceptBtn) acceptBtn.addEventListener('click', dismissCookie);
        if (closeBtn) closeBtn.addEventListener('click', dismissCookie);
    }

    // ============================================================
    // 1. Luxury Mobile Navigation Drawer (Bulletproof Controller)
    // ============================================================
    var navToggle = document.querySelector('[data-nav-toggle]');
    var mobileDrawer = document.getElementById('mobile-drawer');

    function openMobileDrawer() {
        if (!mobileDrawer) return;
        mobileDrawer.classList.add('is-open');
        mobileDrawer.setAttribute('aria-hidden', 'false');
        document.body.classList.add('drawer-open');
        document.body.style.overflow = 'hidden';
        if (navToggle) {
            navToggle.classList.add('is-active');
            navToggle.setAttribute('aria-expanded', 'true');
        }
    }

    function closeMobileDrawer() {
        if (!mobileDrawer) return;
        mobileDrawer.classList.remove('is-open');
        mobileDrawer.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('drawer-open');
        document.body.style.overflow = '';
        if (navToggle) {
            navToggle.classList.remove('is-active');
            navToggle.setAttribute('aria-expanded', 'false');
        }
    }

    function toggleMobileDrawer() {
        if (!mobileDrawer) return;
        if (mobileDrawer.classList.contains('is-open')) {
            closeMobileDrawer();
        } else {
            openMobileDrawer();
        }
    }

    if (navToggle) {
        navToggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            toggleMobileDrawer();
        });
    }

    // Global listener for all close triggers (close button, backdrop, links)
    document.addEventListener('click', function (e) {
        var closeTrigger = e.target.closest('[data-nav-close]');
        if (closeTrigger) {
            closeMobileDrawer();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && mobileDrawer && mobileDrawer.classList.contains('is-open')) {
            closeMobileDrawer();
        }
    });

    // ============================================================
    // 2. Interactive Hero Slider / Carousel (Continuous Seamless Loop)
    // ============================================================
    var heroCarousel = document.querySelector('[data-hero-carousel]');
    if (heroCarousel) {
        var stepperBtns = heroCarousel.querySelectorAll('.stepper-btn');
        var slideCopies = heroCarousel.querySelectorAll('[data-slide-copy]');
        var slideVisuals = heroCarousel.querySelectorAll('[data-slide-visual]');
        var totalSlides = Math.min(stepperBtns.length, slideCopies.length, slideVisuals.length);
        if (totalSlides === 0) {
            totalSlides = stepperBtns.length || slideCopies.length || 1;
        }
        var currentSlide = 0;
        var prevSlide = null;
        var slideDuration = 4200; // Exact, uniform 4.2 seconds for each slide
        var slideTimer = null;

        // Instant background image preloader for all slides
        slideVisuals.forEach(function(vis) {
            var img = vis.querySelector('.hero-main-img');
            if (img && img.src) {
                var pre = new Image();
                pre.src = img.src;
            }
        });

        // Fail-safe image fallback
        heroCarousel.querySelectorAll('.hero-main-img').forEach(function(img) {
            img.addEventListener('error', function() {
                if (this.src.indexOf('hero-living-room.webp') === -1 && this.src.indexOf('hero-living-room.jpg') === -1) {
                    this.src = '/assets/img/hero-living-room.webp';
                }
            });
        });

        function resetAndStartProgress(activeBtn) {
            stepperBtns.forEach(function(btn) {
                var oldProg = btn.querySelector('.stepper-progress');
                if (oldProg) oldProg.remove();
            });

            if (activeBtn) {
                var progress = document.createElement('div');
                progress.className = 'stepper-progress';
                var fill = document.createElement('div');
                fill.className = 'stepper-fill';
                progress.appendChild(fill);
                activeBtn.appendChild(progress);

                fill.style.transition = 'none';
                fill.style.transform = 'scaleY(0)';
                requestAnimationFrame(function () {
                    requestAnimationFrame(function() {
                        fill.style.transition = 'transform ' + slideDuration + 'ms linear';
                        fill.style.transform = 'scaleY(1)';
                    });
                });
            }
        }

        function goToSlide(index) {
            if (totalSlides <= 0) return;
            index = ((index % totalSlides) + totalSlides) % totalSlides;

            prevSlide = currentSlide;
            currentSlide = index;

            stepperBtns.forEach(function (btn, i) {
                if (i === currentSlide) {
                    btn.classList.add('is-active');
                    btn.setAttribute('aria-selected', 'true');
                    resetAndStartProgress(btn);
                } else {
                    btn.classList.remove('is-active');
                    btn.setAttribute('aria-selected', 'false');
                }
            });

            slideCopies.forEach(function (copy, i) {
                if (i === currentSlide) {
                    copy.classList.add('is-active');
                } else {
                    copy.classList.remove('is-active');
                }
            });

            slideVisuals.forEach(function (vis, i) {
                vis.classList.remove('is-prev');
                if (i === currentSlide) {
                    vis.classList.add('is-active');
                } else if (i === prevSlide) {
                    vis.classList.add('is-prev');
                    vis.classList.remove('is-active');
                } else {
                    vis.classList.remove('is-active');
                }
            });

            // Schedule next slide with absolute precision
            scheduleNextAutoSlide();
        }

        function scheduleNextAutoSlide() {
            if (slideTimer) {
                clearTimeout(slideTimer);
                slideTimer = null;
            }
            slideTimer = setTimeout(function() {
                var nextIndex = (currentSlide + 1) % totalSlides;
                goToSlide(nextIndex);
            }, slideDuration);
        }

        // Stepper manual click handlers
        stepperBtns.forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                var idx = parseInt(this.getAttribute('data-slide-index'), 10);
                if (!isNaN(idx) && idx !== currentSlide) {
                    goToSlide(idx);
                }
            });
        });

        // Initialize from slide 0 immediately on DOM ready
        goToSlide(0);

        // Page Visibility API
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                if (slideTimer) clearTimeout(slideTimer);
            } else {
                scheduleNextAutoSlide();
            }
        });

        // Touch swipe gestures for mobile
        var touchStartX = 0;
        var touchEndX = 0;
        heroCarousel.addEventListener('touchstart', function (e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        heroCarousel.addEventListener('touchend', function (e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, { passive: true });

        function handleSwipe() {
            var diff = touchStartX - touchEndX;
            if (Math.abs(diff) > 40) {
                if (diff > 0) {
                    // Swipe left -> Next slide
                    goToSlide((currentSlide + 1) % totalSlides);
                } else {
                    // Swipe right -> Previous slide
                    goToSlide((currentSlide - 1 + totalSlides) % totalSlides);
                }
            }
        }
    }

    // ============================================================
    // 3. Portfolio Category Filtering
    // ============================================================
    var filterPills = document.querySelectorAll('.filter-pill');
    var projectCards = document.querySelectorAll('.project-card');

    if (filterPills.length > 0 && projectCards.length > 0) {
        filterPills.forEach(function (pill) {
            pill.addEventListener('click', function () {
                var filter = this.getAttribute('data-filter');

                filterPills.forEach(function (p) { p.classList.remove('is-active'); });
                this.classList.add('is-active');

                projectCards.forEach(function (card) {
                    var cardCat = card.getAttribute('data-category');
                    if (filter === 'all' || cardCat === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    }

    // ============================================================
    // 4. See More Projects Toggle
    // ============================================================
    var seeMoreBtn = document.getElementById('see-more-btn');
    if (seeMoreBtn) {
        seeMoreBtn.addEventListener('click', function () {
            var extraCards = document.querySelectorAll('.project-card.card-extra');
            extraCards.forEach(function (card) {
                card.classList.add('is-shown');
            });
            seeMoreBtn.style.display = 'none';
        });
    }

    // ============================================================
    // 5. Project Details Page: Interactive Gallery Slider & Thumbnails
    // ============================================================
    var mainImage = document.getElementById('pd-main-image');
    var thumbBtns = document.querySelectorAll('.pd-thumb-item');
    var prevBtn = document.getElementById('pd-prev-btn');
    var nextBtn = document.getElementById('pd-next-btn');

    if (mainImage && thumbBtns.length > 0) {
        var currentThumbIdx = 0;
        var totalThumbs = thumbBtns.length;

        function setMainImage(index) {
            if (index < 0) index = totalThumbs - 1;
            if (index >= totalThumbs) index = 0;
            currentThumbIdx = index;

            var targetThumb = thumbBtns[currentThumbIdx];
            if (!targetThumb) return;
            var newSrc = targetThumb.getAttribute('data-src');

            mainImage.style.opacity = '0.3';
            setTimeout(function () {
                mainImage.src = newSrc;
                mainImage.style.opacity = '1';
            }, 120);

            thumbBtns.forEach(function (btn, i) {
                if (i === currentThumbIdx) {
                    btn.classList.add('is-active');
                } else {
                    btn.classList.remove('is-active');
                }
            });
        }

        thumbBtns.forEach(function (thumb, i) {
            thumb.addEventListener('click', function () {
                setMainImage(i);
            });
        });

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                setMainImage(currentThumbIdx - 1);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                setMainImage(currentThumbIdx + 1);
            });
        }
    }

    // ============================================================
    // 6. Project Details Page: Interactive Tabbed Navigation
    // ============================================================
    var tabBtns = document.querySelectorAll('.pd-tab-btn');
    var tabPanels = document.querySelectorAll('.pd-tab-panel');

    if (tabBtns.length > 0 && tabPanels.length > 0) {
        tabBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var target = this.getAttribute('data-tab-target');

                tabBtns.forEach(function (b) { b.classList.remove('is-active'); });
                this.classList.add('is-active');

                tabPanels.forEach(function (panel) {
                    if (panel.id === 'tab-panel-' + target) {
                        panel.classList.add('is-active');
                    } else {
                        panel.classList.remove('is-active');
                    }
                });
            });
        });
    }

    // ============================================================
    // 7. Scroll Reveal Animation Trigger
    // ============================================================
    var revealElements = document.querySelectorAll('[data-reveal]');
    if ('IntersectionObserver' in window) {
        var revealObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-revealed');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        revealElements.forEach(function (el) {
            revealObserver.observe(el);
        });
    } else {
        revealElements.forEach(function (el) {
            el.classList.add('is-revealed');
        });
    }

    // ============================================================
    // 8. Luxury Floating Back-to-Top (Scroll Reveal & Smooth Scroll)
    // ============================================================
    var backToTopBtn = document.getElementById('back-to-top-btn');
    if (backToTopBtn) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 280) {
                backToTopBtn.classList.add('is-visible');
            } else {
                backToTopBtn.classList.remove('is-visible');
            }
        }, { passive: true });

        backToTopBtn.addEventListener('click', function (e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ============================================================
    // 9. Modern Luxury Testimonials Slider / Carousel Controller
    // ============================================================
    var tCarousel = document.querySelector('[data-testimonials-carousel]');
    if (tCarousel) {
        var tTrack = tCarousel.querySelector('[data-t-track]');
        var tSlides = tCarousel.querySelectorAll('[data-t-slide]');
        var tPrevBtn = tCarousel.querySelector('[data-t-prev]');
        var tNextBtn = tCarousel.querySelector('[data-t-next]');
        var tDots = document.querySelectorAll('[data-t-dot]');
        var totalSlides = tSlides.length;
        var currentIndex = 0;
        var autoSlideTimer = null;
        var autoSlideDelay = 5200;

        function getVisibleCount() {
            var w = window.innerWidth;
            if (w <= 680) return 1;
            if (w <= 1100) return 2;
            return 3;
        }

        function getMaxIndex() {
            var visible = getVisibleCount();
            return Math.max(0, totalSlides - visible);
        }

        function updateCarousel(animate) {
            var maxIdx = getMaxIndex();
            if (currentIndex > maxIdx) currentIndex = maxIdx;
            if (currentIndex < 0) currentIndex = 0;

            if (tTrack && tSlides.length > 0) {
                var slideWidth = tSlides[0].getBoundingClientRect().width;
                var gap = window.innerWidth <= 680 ? 16 : 24;
                var offset = currentIndex * (slideWidth + gap);

                if (!animate) {
                    tTrack.style.transition = 'none';
                } else {
                    tTrack.style.transition = 'transform 0.55s cubic-bezier(0.16, 1, 0.3, 1)';
                }
                tTrack.style.transform = 'translateX(-' + offset + 'px)';
            }

            // Apply center-featured (larger middle card) classes
            var visible = getVisibleCount();
            tSlides.forEach(function (slide, i) {
                slide.classList.remove('is-current', 'is-prev', 'is-next');
                if (visible === 3) {
                    if (i === currentIndex + 1 && i < totalSlides) {
                        slide.classList.add('is-current');
                    } else if (i === currentIndex && i < totalSlides) {
                        slide.classList.add('is-prev');
                    } else if (i === currentIndex + 2 && i < totalSlides) {
                        slide.classList.add('is-next');
                    }
                } else if (visible === 2) {
                    if (i === currentIndex + 1 && i < totalSlides) {
                        slide.classList.add('is-current');
                    } else if (i === currentIndex && i < totalSlides) {
                        slide.classList.add('is-prev');
                    }
                } else {
                    slide.classList.add('is-current');
                }
            });

            // Update dots
            tDots.forEach(function (dot, i) {
                if (i === currentIndex) {
                    dot.classList.add('is-active');
                    dot.setAttribute('aria-selected', 'true');
                } else {
                    dot.classList.remove('is-active');
                    dot.setAttribute('aria-selected', 'false');
                }
            });

            // Update arrow states
            if (tPrevBtn) {
                tPrevBtn.disabled = (currentIndex === 0);
            }
            if (tNextBtn) {
                tNextBtn.disabled = (currentIndex >= maxIdx);
            }
        }

        function goTo(index) {
            var maxIdx = getMaxIndex();
            if (index < 0) index = 0;
            if (index > maxIdx) index = 0;
            currentIndex = index;
            updateCarousel(true);
        }

        function nextTestimonial() {
            var maxIdx = getMaxIndex();
            if (currentIndex >= maxIdx) {
                goTo(0);
            } else {
                goTo(currentIndex + 1);
            }
        }

        function prevTestimonial() {
            if (currentIndex <= 0) {
                goTo(getMaxIndex());
            } else {
                goTo(currentIndex - 1);
            }
        }

        function startTimer() {
            stopTimer();
            autoSlideTimer = setInterval(nextTestimonial, autoSlideDelay);
        }

        function stopTimer() {
            if (autoSlideTimer) {
                clearInterval(autoSlideTimer);
                autoSlideTimer = null;
            }
        }

        if (tNextBtn) {
            tNextBtn.addEventListener('click', function () {
                nextTestimonial();
                startTimer();
            });
        }

        if (tPrevBtn) {
            tPrevBtn.addEventListener('click', function () {
                prevTestimonial();
                startTimer();
            });
        }

        tDots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                var idx = parseInt(this.getAttribute('data-t-dot'), 10);
                if (!isNaN(idx)) {
                    goTo(idx);
                    startTimer();
                }
            });
        });

        // Touch swipe support for mobile
        var touchStartX = 0;
        var touchEndX = 0;
        tCarousel.addEventListener('touchstart', function (e) {
            stopTimer();
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        tCarousel.addEventListener('touchend', function (e) {
            touchEndX = e.changedTouches[0].screenX;
            var diff = touchStartX - touchEndX;
            if (Math.abs(diff) > 40) {
                if (diff > 0) {
                    nextTestimonial();
                } else {
                    prevTestimonial();
                }
            }
            startTimer();
        }, { passive: true });

        tCarousel.addEventListener('mouseenter', stopTimer);
        tCarousel.addEventListener('mouseleave', startTimer);

        window.addEventListener('resize', function () {
            updateCarousel(false);
        }, { passive: true });

        // Initial setup
        setTimeout(function () {
            updateCarousel(false);
        }, 50);
        startTimer();
    }

    // ============================================================
    // 10. Modern Luxury Custom Cursor & Physics
    // ============================================================
    var cursorWrapper = document.getElementById('custom-cursor');
    if (cursorWrapper && window.matchMedia('(pointer: fine) and (min-width: 992px)').matches) {
        var cursorDot = cursorWrapper.querySelector('.cursor-dot');
        var cursorRing = cursorWrapper.querySelector('.cursor-ring');

        var mouseX = -100;
        var mouseY = -100;
        var ringX = -100;
        var ringY = -100;
        var dotX = -100;
        var dotY = -100;
        var isCursorVisible = false;

        document.addEventListener('mousemove', function (e) {
            mouseX = e.clientX;
            mouseY = e.clientY;

            if (!isCursorVisible) {
                isCursorVisible = true;
                cursorWrapper.classList.add('is-active');
                ringX = mouseX;
                ringY = mouseY;
                dotX = mouseX;
                dotY = mouseY;
            }
        });

        document.addEventListener('mouseleave', function () {
            isCursorVisible = false;
            cursorWrapper.classList.remove('is-active');
        });

        document.addEventListener('mousedown', function () {
            document.body.classList.add('cursor-click');
        });

        document.addEventListener('mouseup', function () {
            document.body.classList.remove('cursor-click');
        });

        // Interactive hover target detection
        var interactiveSelectors = 'a, button, input, textarea, select, [role="button"], .service-card, .project-card, .testimonial-card, .stat-card, .process-node, .filter-pill, .stepper-btn, .theme-toggle-btn, .fa-btn';
        
        document.addEventListener('mouseover', function (e) {
            if (e.target.closest(interactiveSelectors)) {
                document.body.classList.add('cursor-hover');
            }
        });

        document.addEventListener('mouseout', function (e) {
            if (e.target.closest(interactiveSelectors)) {
                document.body.classList.remove('cursor-hover');
            }
        });

        // Smooth Lerp Rendering Loop
        function renderCursor() {
            if (isCursorVisible) {
                dotX += (mouseX - dotX) * 0.75;
                dotY += (mouseY - dotY) * 0.75;
                if (cursorDot) {
                    cursorDot.style.left = dotX + 'px';
                    cursorDot.style.top = dotY + 'px';
                }

                ringX += (mouseX - ringX) * 0.18;
                ringY += (mouseY - ringY) * 0.18;
                if (cursorRing) {
                    cursorRing.style.left = ringX + 'px';
                    cursorRing.style.top = ringY + 'px';
                }
            }
            requestAnimationFrame(renderCursor);
        }
        requestAnimationFrame(renderCursor);
    }

    // ============================================================
    // 11. Smooth Section Anchor Scrolling with Header Compensation
    // ============================================================
    document.querySelectorAll('a[href^="#"], a[href^="/#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            var href = this.getAttribute('href');
            var hash = href.startsWith('/#') ? href.substring(1) : href;
            if (hash === '#' || !hash.startsWith('#')) return;

            var targetEl = document.querySelector(hash);
            if (targetEl && (window.location.pathname === '/' || window.location.pathname === '' || href.startsWith('#'))) {
                e.preventDefault();
                var headerOffset = 75;
                var elementPosition = targetEl.getBoundingClientRect().top;
                var offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });

                if (history.pushState) {
                    history.pushState(null, null, hash);
                }
            }
        });
    });

    // ============================================================
    // 12. Project Details: Image Lightbox & Logo Watermark Modal
    // ============================================================
    var pdMainImgTrigger = document.getElementById('pd-main-img-trigger');
    var pdLightbox = document.getElementById('pd-lightbox');
    var pdLightboxImg = document.getElementById('pd-lightbox-image');
    var pdLightboxClose = document.getElementById('pd-lightbox-close');
    var pdLightboxBackdrop = document.getElementById('pd-lightbox-backdrop');
    var pdLightboxPrev = document.getElementById('pd-lightbox-prev');
    var pdLightboxNext = document.getElementById('pd-lightbox-next');
    var pdLightboxCounter = document.getElementById('pd-lightbox-counter');
    var pdThumbs = document.querySelectorAll('#pd-thumbs .pd-thumb-item');

    if (pdMainImgTrigger && pdLightbox && pdLightboxImg) {
        var currentGalleryIndex = 0;
        var galleryList = [];

        pdThumbs.forEach(function (thumb) {
            var src = thumb.getAttribute('data-src');
            if (src) galleryList.push(src);
        });

        if (galleryList.length === 0) {
            var mainImg = document.getElementById('pd-main-image');
            if (mainImg) galleryList.push(mainImg.src);
        }

        function updateLightboxImage(index) {
            if (index < 0) index = galleryList.length - 1;
            if (index >= galleryList.length) index = 0;
            currentGalleryIndex = index;
            pdLightboxImg.src = galleryList[currentGalleryIndex];
            if (pdLightboxCounter) {
                pdLightboxCounter.textContent = (currentGalleryIndex + 1) + ' / ' + galleryList.length;
            }
        }

        pdMainImgTrigger.addEventListener('click', function () {
            var mainImg = document.getElementById('pd-main-image');
            var currentSrc = mainImg ? mainImg.getAttribute('src') : '';
            var foundIdx = galleryList.indexOf(currentSrc);
            if (foundIdx === -1) foundIdx = 0;
            updateLightboxImage(foundIdx);
            pdLightbox.classList.add('is-active');
            pdLightbox.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        });

        function closeLightbox() {
            pdLightbox.classList.remove('is-active');
            pdLightbox.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        if (pdLightboxClose) pdLightboxClose.addEventListener('click', closeLightbox);
        if (pdLightboxBackdrop) pdLightboxBackdrop.addEventListener('click', closeLightbox);

        if (pdLightboxPrev) {
            pdLightboxPrev.addEventListener('click', function (e) {
                e.stopPropagation();
                updateLightboxImage(currentGalleryIndex - 1);
            });
        }

        if (pdLightboxNext) {
            pdLightboxNext.addEventListener('click', function (e) {
                e.stopPropagation();
                updateLightboxImage(currentGalleryIndex + 1);
            });
        }

        document.addEventListener('keydown', function (e) {
            if (!pdLightbox.classList.contains('is-active')) return;
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') updateLightboxImage(currentGalleryIndex - 1);
            if (e.key === 'ArrowRight') updateLightboxImage(currentGalleryIndex + 1);
        });
    }

    // ============================================================
    // 13. Dynamic Homepage Navigation Scrollspy & Active Section Sync
    // ============================================================
    var isHome = window.location.pathname === '/' || window.location.pathname === '' || window.location.pathname.endsWith('/public/') || window.location.pathname.endsWith('/index.php');
    if (isHome) {
        var navLinksList = document.querySelectorAll('.main-nav a, .mobile-drawer-nav a');
        var sectionMap = [
            { id: 'hero', navKey: 'home' },
            { id: 'about', navKey: 'home' },
            { id: 'services', navKey: 'services' },
            { id: 'process', navKey: 'services' },
            { id: 'portfolio', navKey: 'portfolio' },
            { id: 'testimonials', navKey: 'portfolio' },
            { id: 'contact', navKey: 'home' }
        ];

        function setActiveMenu(activeKey) {
            navLinksList.forEach(function (link) {
                var href = link.getAttribute('href') || '';
                var isMatch = false;
                if (activeKey === 'home' && (href === '/' || href === '/#hero' || href === '#hero')) {
                    isMatch = true;
                } else if (activeKey === 'services' && (href === '/#services' || href === '#services')) {
                    isMatch = true;
                } else if (activeKey === 'portfolio' && (href === '/#portfolio' || href === '#portfolio')) {
                    isMatch = true;
                } else if (activeKey === 'projects' && href === '/projects') {
                    isMatch = true;
                } else if (activeKey === 'faq' && href === '/faq') {
                    isMatch = true;
                }

                if (isMatch) {
                    link.classList.add('is-active');
                } else {
                    link.classList.remove('is-active');
                }
            });
        }

        if ('IntersectionObserver' in window) {
            var scrollspyObserver = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        var targetId = entry.target.id;
                        for (var i = 0; i < sectionMap.length; i++) {
                            if (sectionMap[i].id === targetId) {
                                setActiveMenu(sectionMap[i].navKey);
                                break;
                            }
                        }
                    }
                });
            }, {
                root: null,
                rootMargin: '-20% 0px -60% 0px',
                threshold: 0
            });

            sectionMap.forEach(function (s) {
                var elem = document.getElementById(s.id);
                if (elem) {
                    scrollspyObserver.observe(elem);
                }
            });
        }

        // Direct click event instant selection
        navLinksList.forEach(function (link) {
            link.addEventListener('click', function () {
                var href = this.getAttribute('href') || '';
                if (href === '/#services' || href === '#services') setActiveMenu('services');
                else if (href === '/#portfolio' || href === '#portfolio') setActiveMenu('portfolio');
                else if (href === '/' || href === '/#hero' || href === '#hero') setActiveMenu('home');
            });
        });
    }

    // ============================================================
    // 14. Animated Stats Number Counter with Bengali Numeral Support
    // ============================================================
    var counterElements = document.querySelectorAll('[data-counter]');
    if (counterElements.length > 0 && 'IntersectionObserver' in window) {
        var bengaliDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

        function toBengaliNumber(num) {
            return String(num).replace(/\d/g, function (digit) {
                return bengaliDigits[parseInt(digit, 10)];
            });
        }

        var isBengali = document.documentElement.classList.contains('is-bengali') || document.documentElement.getAttribute('lang') === 'bn';

        var counterObserver = new IntersectionObserver(function (entries, observer) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var el = entry.target;
                    var targetVal = parseInt(el.getAttribute('data-target'), 10) || 0;
                    var suffix = el.getAttribute('data-suffix') || '';
                    var numSpan = el.querySelector('.counter-num') || el;
                    var duration = 1800; // ms
                    var startTime = null;

                    function animateCount(timestamp) {
                        if (!startTime) startTime = timestamp;
                        var progress = Math.min((timestamp - startTime) / duration, 1);
                        // Ease out cubic
                        var easeProgress = 1 - Math.pow(1 - progress, 3);
                        var currentVal = Math.floor(easeProgress * targetVal);

                        var displayVal = isBengali ? toBengaliNumber(currentVal) : String(currentVal);
                        numSpan.textContent = displayVal + suffix;

                        if (progress < 1) {
                            requestAnimationFrame(animateCount);
                        } else {
                            var finalVal = isBengali ? toBengaliNumber(targetVal) : String(targetVal);
                            numSpan.textContent = finalVal + suffix;
                        }
                    }

                    requestAnimationFrame(animateCount);
                    observer.unobserve(el);
                }
            });
        }, {
            threshold: 0.2
        });

        counterElements.forEach(function (el) {
            counterObserver.observe(el);
        });
    }
});

/* ============ Quote Modal ============ */
(function () {
    var modal = document.getElementById('quote-modal');
    var closeBtn = document.getElementById('quote-modal-close');
    var triggers = document.querySelectorAll('[data-quote-open]');

    function openModal() {
        if (!modal) return;
        modal.hidden = false;
        modal.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }
    function closeModal() {
        if (!modal) return;
        modal.classList.add('is-open-off');
        modal.classList.remove('is-open');
        setTimeout(function () {
            modal.hidden = true;
            modal.classList.remove('is-open-off');
        }, 200);
        document.body.style.overflow = '';
    }

    triggers.forEach(function (t) {
        t.addEventListener('click', function (e) {
            e.preventDefault();
            openModal();
        });
    });

    if (closeBtn) closeBtn.addEventListener('click', function (e) {
        e.preventDefault();
        closeModal();
    });

    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.hidden) closeModal();
        });
    }
})();

/* ============ Notification Toast ============ */
(function () {
    var toast = document.getElementById('notification-toast');
    var closeBtn = document.getElementById('toast-close-btn');
    if (!toast) return;

    function hideToast() {
        toast.style.transition = 'opacity 0.3s ease';
        toast.style.opacity = '0';
        setTimeout(function () { toast.remove(); }, 300);
    }

    if (closeBtn) closeBtn.addEventListener('click', hideToast);
    setTimeout(hideToast, 6000);
})();
