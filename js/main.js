/* ========================================
   Maxi Wheelchair Cabs - Main JavaScript
   ======================================== */

document.addEventListener('DOMContentLoaded', function () {

    // --- Preloader ---
    const preloader = document.querySelector('.preloader');
    if (preloader) {
        window.addEventListener('load', function () {
            setTimeout(function () {
                preloader.classList.add('hidden');
            }, 500);
        });
        setTimeout(function () {
            preloader.classList.add('hidden');
        }, 3000);
    }

    // --- Sticky Header ---
    const header = document.querySelector('.header');
    window.addEventListener('scroll', function () {
        if (window.scrollY > 80) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // --- Mobile Navigation ---
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');
    const navOverlay = document.querySelector('.nav-overlay');

    if (navToggle) {
        navToggle.addEventListener('click', function () {
            this.classList.toggle('active');
            navMenu.classList.toggle('open');
            if (navOverlay) navOverlay.classList.toggle('active');
            document.body.style.overflow = navMenu.classList.contains('open') ? 'hidden' : '';
        });
    }

    if (navOverlay) {
        navOverlay.addEventListener('click', function () {
            navToggle.classList.remove('active');
            navMenu.classList.remove('open');
            navOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    }

    document.querySelectorAll('.nav-menu a').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth <= 768) {
                navToggle.classList.remove('active');
                navMenu.classList.remove('open');
                if (navOverlay) navOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });

    // --- Mobile Dropdown Toggle ---
    document.querySelectorAll('.has-dropdown > a').forEach(function (link) {
        link.addEventListener('click', function (e) {
            if (window.innerWidth <= 768) {
                e.preventDefault();
                this.parentElement.classList.toggle('open');
            }
        });
    });

    // --- Scroll Reveal Animation ---
    function revealOnScroll() {
        var reveals = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
        var windowHeight = window.innerHeight;

        reveals.forEach(function (el) {
            var elementTop = el.getBoundingClientRect().top;
            var revealPoint = 120;

            if (elementTop < windowHeight - revealPoint) {
                el.classList.add('active');
            }
        });
    }

    window.addEventListener('scroll', revealOnScroll);
    revealOnScroll();

    // --- Counter Animation ---
    function animateCounters() {
        var counters = document.querySelectorAll('.counter');

        counters.forEach(function (counter) {
            if (counter.dataset.animated) return;

            var rect = counter.getBoundingClientRect();
            if (rect.top >= window.innerHeight || rect.bottom <= 0) return;

            counter.dataset.animated = 'true';
            var target = parseInt(counter.getAttribute('data-target'));
            var suffix = counter.getAttribute('data-suffix') || '';
            var duration = 2000;
            var step = target / (duration / 16);
            var current = 0;

            var timer = setInterval(function () {
                current += step;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                counter.textContent = Math.floor(current).toLocaleString() + suffix;
            }, 16);
        });
    }

    window.addEventListener('scroll', animateCounters);
    animateCounters();

    // --- Scroll to Top ---
    var scrollTopBtn = document.querySelector('.scroll-top');

    if (scrollTopBtn) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 400) {
                scrollTopBtn.classList.add('visible');
            } else {
                scrollTopBtn.classList.remove('visible');
            }
        });

        scrollTopBtn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // --- Smooth Scroll for Anchor Links ---
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            var targetId = this.getAttribute('href');
            if (targetId === '#') return;

            var targetEl = document.querySelector(targetId);
            if (targetEl) {
                e.preventDefault();
                var headerHeight = document.querySelector('.header').offsetHeight;
                var targetPosition = targetEl.getBoundingClientRect().top + window.scrollY - headerHeight - 20;
                window.scrollTo({ top: targetPosition, behavior: 'smooth' });
            }
        });
    });

    // --- Typing Effect for Hero (optional enhancement) ---
    var typingEl = document.querySelector('.hero-typing');
    if (typingEl) {
        var words = JSON.parse(typingEl.getAttribute('data-words'));
        var wordIndex = 0;
        var charIndex = 0;
        var isDeleting = false;

        function typeEffect() {
            var currentWord = words[wordIndex];

            if (isDeleting) {
                typingEl.textContent = currentWord.substring(0, charIndex - 1);
                charIndex--;
            } else {
                typingEl.textContent = currentWord.substring(0, charIndex + 1);
                charIndex++;
            }

            var speed = isDeleting ? 50 : 100;

            if (!isDeleting && charIndex === currentWord.length) {
                speed = 2000;
                isDeleting = true;
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                wordIndex = (wordIndex + 1) % words.length;
                speed = 400;
            }

            setTimeout(typeEffect, speed);
        }

        typeEffect();
    }

    // --- Parallax Effect on Hero ---
    var heroBg = document.querySelector('.hero-bg img');
    if (heroBg) {
        window.addEventListener('scroll', function () {
            var scrolled = window.scrollY;
            if (scrolled < window.innerHeight) {
                heroBg.style.transform = 'translateY(' + (scrolled * 0.3) + 'px)';
            }
        });
    }

    // --- Hero Booking Form AJAX ---
    var heroForm = document.getElementById('heroBookingForm');
    if (heroForm) {
        heroForm.addEventListener('submit', function (e) {
            e.preventDefault();

            var btn = document.getElementById('heroFormBtn');
            var msgDiv = document.getElementById('heroFormMsg');
            var originalText = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            msgDiv.style.display = 'none';

            var formData = new FormData(heroForm);

            fetch('ajax-booking.php', {
                method: 'POST',
                body: formData
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                msgDiv.style.display = 'block';
                if (data.success) {
                    msgDiv.className = 'hf-message success';
                    msgDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
                    heroForm.reset();
                    heroForm.style.display = 'none';
                } else {
                    msgDiv.className = 'hf-message error';
                    msgDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + data.message;
                }
                btn.disabled = false;
                btn.innerHTML = originalText;
            })
            .catch(function () {
                msgDiv.style.display = 'block';
                msgDiv.className = 'hf-message error';
                msgDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Something went wrong. Please call us directly.';
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        });
    }

    // --- Booking Modal ---
    var modal = document.getElementById('bookingModal');
    var closeBtn = document.getElementById('closeBookingModal');
    var serviceField = document.getElementById('modalServiceField');

    if (modal) {
        document.querySelectorAll('.open-booking-modal').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var serviceName = this.getAttribute('data-service') || '';
                if (serviceField) serviceField.value = serviceName;
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        });

        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            });
        }

        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        });

        // Modal form AJAX
        var modalForm = document.getElementById('modalBookingForm');
        if (modalForm) {
            modalForm.addEventListener('submit', function (e) {
                e.preventDefault();
                var btn = document.getElementById('modalFormBtn');
                var msgDiv = document.getElementById('modalFormMsg');
                var originalText = btn.innerHTML;

                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
                msgDiv.style.display = 'none';

                fetch('ajax-booking.php', {
                    method: 'POST',
                    body: new FormData(modalForm)
                })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    msgDiv.style.display = 'block';
                    if (data.success) {
                        msgDiv.className = 'hf-message success';
                        msgDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
                        modalForm.reset();
                        modalForm.style.display = 'none';
                    } else {
                        msgDiv.className = 'hf-message error';
                        msgDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + data.message;
                    }
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                })
                .catch(function () {
                    msgDiv.style.display = 'block';
                    msgDiv.className = 'hf-message error';
                    msgDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Something went wrong. Please call us directly.';
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
            });
        }
    }

});
