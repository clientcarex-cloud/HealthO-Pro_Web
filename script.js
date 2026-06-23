/* ==========================================================================
   HealthO Pro — Site Interactions
   ========================================================================== */
(function () {
    'use strict';

    /* ---------- Preloader ---------- */
    window.addEventListener('load', function () {
        var pl = document.getElementById('preloader');
        if (pl) setTimeout(function () { pl.classList.add('done'); }, 250);
    });

    document.addEventListener('DOMContentLoaded', function () {

        /* ---------- Sticky navbar shadow ---------- */
        var navbar = document.getElementById('navbar');
        var onScroll = function () {
            if (navbar) navbar.classList.toggle('scrolled', window.scrollY > 20);
            var tt = document.getElementById('toTop');
            if (tt) tt.classList.toggle('show', window.scrollY > 500);
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();

        /* ---------- Mobile nav toggle ---------- */
        var toggle = document.getElementById('navToggle');
        var menu = document.getElementById('navMenu');
        if (toggle && menu) {
            toggle.addEventListener('click', function () {
                var open = menu.classList.toggle('open');
                toggle.classList.toggle('open', open);
                document.body.style.overflow = open ? 'hidden' : '';
            });
            menu.querySelectorAll('a').forEach(function (a) {
                a.addEventListener('click', function () {
                    if (!a.closest('.has-drop') || window.innerWidth > 860) {
                        menu.classList.remove('open');
                        toggle.classList.remove('open');
                        document.body.style.overflow = '';
                    }
                });
            });
        }

        /* ---------- Mobile dropdown expand ---------- */
        document.querySelectorAll('.nav-item.has-drop > .nav-link').forEach(function (link) {
            link.addEventListener('click', function (e) {
                if (window.innerWidth <= 860) {
                    e.preventDefault();
                    link.closest('.nav-item').classList.toggle('open');
                }
            });
        });

        /* ---------- Active nav link by current page ---------- */
        var path = location.pathname.split('/').pop() || 'index.html';
        document.querySelectorAll('.nav-link[data-page]').forEach(function (l) {
            if (l.getAttribute('data-page') === path) l.classList.add('active');
        });

        /* ---------- Scroll reveal ---------- */
        var reveals = document.querySelectorAll('.reveal');
        if ('IntersectionObserver' in window) {
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (en) {
                    if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
            reveals.forEach(function (el) { io.observe(el); });
        } else {
            reveals.forEach(function (el) { el.classList.add('in'); });
        }

        /* ---------- Animated counters ---------- */
        var counters = document.querySelectorAll('[data-count]');
        var animate = function (el) {
            var target = parseFloat(el.getAttribute('data-count'));
            var suffix = el.getAttribute('data-suffix') || '';
            var prefix = el.getAttribute('data-prefix') || '';
            var dec = (target % 1 !== 0) ? 1 : 0;
            var start = 0, dur = 1600, t0 = null;
            var step = function (ts) {
                if (!t0) t0 = ts;
                var p = Math.min((ts - t0) / dur, 1);
                var eased = 1 - Math.pow(1 - p, 3);
                var val = (start + (target - start) * eased).toFixed(dec);
                el.textContent = prefix + Number(val).toLocaleString('en-IN') + suffix;
                if (p < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        };
        if ('IntersectionObserver' in window && counters.length) {
            var cio = new IntersectionObserver(function (entries) {
                entries.forEach(function (en) {
                    if (en.isIntersecting) { animate(en.target); cio.unobserve(en.target); }
                });
            }, { threshold: 0.5 });
            counters.forEach(function (c) { cio.observe(c); });
        }

        /* ---------- Pricing: billing toggle ---------- */
        var billBtns = document.querySelectorAll('.billing-toggle button');

        /* ---------- Real-time Pricing Calculator ---------- */
        var currentBillingCycle = 'year';
        var recalculatePrices = function(cycle) {
            currentBillingCycle = cycle;
            document.querySelectorAll('.user-select').forEach(function(select) {
                var card = select.closest('.price-card');
                if (!card) return;
                
                var users = parseInt(select.value, 10);
                var pricePerUser = parseInt(select.getAttribute('data-price-' + cycle), 10);
                
                var subtotal = users * pricePerUser;
                var gst = Math.round(subtotal * 0.18);
                var total = subtotal + gst;
                
                var baseEl = card.querySelector('.calc-base');
                var gstEl = card.querySelector('.calc-gst');
                var totalEl = card.querySelector('.calc-total');
                var billedEl = card.querySelector('.calc-billed');
                var freqEl = card.querySelector('.calc-billed-freq');
                
                var months = (cycle === 'year') ? 12 : 6;
                var billedAmount = total * months;
                
                if (baseEl) baseEl.textContent = subtotal.toLocaleString('en-IN');
                if (gstEl) gstEl.textContent = gst.toLocaleString('en-IN');
                if (totalEl) totalEl.textContent = total.toLocaleString('en-IN');
                if (billedEl) billedEl.textContent = billedAmount.toLocaleString('en-IN');
                if (freqEl) freqEl.textContent = (cycle === 'year') ? 'Annually' : 'Every 6 Months';
            });
        };

        document.querySelectorAll('.user-select').forEach(function(select) {
            select.addEventListener('change', function() {
                recalculatePrices(currentBillingCycle);
            });
        });

        var setBilling = function (cycle) {
            recalculatePrices(cycle);
            billBtns.forEach(function (b) { b.classList.toggle('active', b.dataset.cycle === cycle); });
            document.querySelectorAll('[data-half]').forEach(function (el) {
                el.style.display = (cycle === 'half') ? '' : 'none';
            });
            document.querySelectorAll('[data-year]').forEach(function (el) {
                el.style.display = (cycle === 'year') ? '' : 'none';
            });
        };
        billBtns.forEach(function (b) {
            b.addEventListener('click', function () { setBilling(b.dataset.cycle); });
        });
        if (billBtns.length) setBilling('year');

        /* ---------- Pricing: product tabs ---------- */
        var prodTabs = document.querySelectorAll('.product-tab');
        prodTabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                prodTabs.forEach(function (t) { t.classList.remove('active'); });
                tab.classList.add('active');
                document.querySelectorAll('.price-panel').forEach(function (p) { p.classList.remove('active'); });
                var panel = document.getElementById('panel-' + tab.dataset.product);
                if (panel) panel.classList.add('active');
            });
        });

        /* ---------- Handle hash on load for pricing plans & tabs ---------- */
        if (window.location.hash) {
            var fullHash = window.location.hash.substring(1);
            var billingMatch = fullHash.match(/-(year|half)$/);
            if (billingMatch) {
                setBilling(billingMatch[1]);
            }
            var hash = fullHash.replace(/-(year|half)$/, '');
            var productMatch = hash.match(/-(hims|lims|cims|ris)/);
            if (productMatch) {
                var tabToActivate = document.querySelector('.product-tab[data-product="' + productMatch[1] + '"]');
                if (tabToActivate) {
                    tabToActivate.click();
                    if (hash.startsWith('plan-')) {
                        setTimeout(function() {
                            var el = document.getElementById(hash);
                            if (el) {
                                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                el.style.transition = 'box-shadow 0.3s ease';
                                var oldShadow = el.style.boxShadow;
                                el.style.boxShadow = '0 0 0 4px var(--cyan)';
                                setTimeout(function() { el.style.boxShadow = oldShadow; }, 1500);
                            }
                        }, 100);
                    }
                }
            }
        }

        /* ---------- FAQ accordion ---------- */
        document.querySelectorAll('.faq-q').forEach(function (q) {
            q.addEventListener('click', function () {
                var item = q.closest('.faq-item');
                var ans = item.querySelector('.faq-a');
                var isOpen = item.classList.contains('open');
                item.parentElement.querySelectorAll('.faq-item.open').forEach(function (o) {
                    o.classList.remove('open');
                    o.querySelector('.faq-a').style.maxHeight = null;
                });
                if (!isOpen) {
                    item.classList.add('open');
                    ans.style.maxHeight = ans.scrollHeight + 'px';
                }
            });
        });

        /* ---------- Year in footer ---------- */
        document.querySelectorAll('[data-yr]').forEach(function (el) {
            el.textContent = new Date().getFullYear();
        });

        /* ---------- Forms (AJAX to contact.php with graceful fallback) ---------- */
        document.querySelectorAll('form[data-ajax]').forEach(function (form) {
            var status = form.querySelector('.form-status');

            var showStatus = function (type, msg) {
                if (!status) return;
                status.className = 'form-status show ' + (type === 'ok' ? 'ok' : 'bad');
                status.textContent = msg;
                status.scrollIntoView({ behavior: 'smooth', block: 'center' });
            };

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                var valid = true;
                form.querySelectorAll('[required]').forEach(function (f) {
                    var field = f.closest('.field');
                    var ok = f.value.trim() !== '' && !(f.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(f.value));
                    if (field) field.classList.toggle('error', !ok);
                    if (!ok) valid = false;
                });
                if (!valid) { showStatus('bad', 'Please complete the required fields correctly.'); return; }

                var btn = form.querySelector('button[type="submit"]');
                var btnText = btn ? btn.innerHTML : '';
                if (btn) { btn.disabled = true; btn.innerHTML = 'Sending…'; }

                var data = new FormData(form);
                fetch('contact.php', { method: 'POST', body: data })
                    .then(function (r) {
                        return r.json().catch(function () {
                            throw new Error('Invalid response from server.');
                        });
                    })
                    .then(function (res) {
                        if (res.status === 'success') {
                            showStatus('ok', res.message || '✓ Thank you! Our team will get back to you within one business day.');
                            form.reset();
                        } else {
                            showStatus('bad', res.message || 'Something went wrong. Please email digicarelynx@gmail.com.');
                        }
                    })
                    .catch(function (error) {
                        showStatus('bad', 'Could not send message. ' + (error.message || 'Please email digicarelynx@gmail.com directly.'));
                    })
                    .finally(function () {
                        if (btn) { btn.disabled = false; btn.innerHTML = btnText; }
                    });
            });

            form.querySelectorAll('input, textarea, select').forEach(function (f) {
                f.addEventListener('input', function () {
                    var field = f.closest('.field');
                    if (field) field.classList.remove('error');
                });
            });
        });

        /* ---------- Smooth scroll for in-page anchors ---------- */
        document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach(function (a) {
            a.addEventListener('click', function (e) {
                var href = a.getAttribute('href');
                var targetId = href;
                var billingMatch = href.match(/-(year|half)$/);
                if (billingMatch) {
                    setBilling(billingMatch[1]);
                    targetId = href.replace(/-(year|half)$/, '');
                }
                var el = document.querySelector(targetId);
                if (el) { 
                    e.preventDefault(); 
                    var productMatch = targetId.match(/-(hims|lims|cims|ris)/);
                    if (productMatch) {
                        var tabToActivate = document.querySelector('.product-tab[data-product="' + productMatch[1] + '"]');
                        if (tabToActivate) tabToActivate.click();
                    }
                    setTimeout(function() {
                        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 50);
                    if (history.pushState) {
                        history.pushState(null, null, href);
                    }
                }
            });
        });
    });
})();
