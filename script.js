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

        /* ---------- Demo popup modal ----------
           Injected before the geo + form-binding blocks below so its
           [data-ajax] form is picked up automatically (validation, geo,
           AJAX submit all reuse the shared handlers). */
        (function () {
            var modal = document.createElement('div');
            modal.className = 'demo-modal';
            modal.setAttribute('role', 'dialog');
            modal.setAttribute('aria-modal', 'true');
            modal.setAttribute('aria-label', 'Book a free demo');
            modal.innerHTML =
                '<div class="demo-modal__dialog">'
                + '<button type="button" class="demo-modal__close" aria-label="Close">'
                + '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>'
                + '</button>'
                + '<div class="demo-modal__head"><div>'
                + '<h3 class="h-card">Book a free demo</h3>'
                + '<p>Fill in the form and our team will reach out shortly.</p>'
                + '</div></div>'
                + '<form data-ajax novalidate>'
                + '<div class="form-status"></div>'
                + '<input type="hidden" name="source" value="Demo popup">'
                + '<div class="form-row">'
                + '<div class="field"><label>Full Name <span class="req">*</span></label><input type="text" name="fullName" placeholder="Your name" required></div>'
                + '<div class="field"><label>Email <span class="req">*</span></label><input type="email" name="email" placeholder="you@example.com" required><span class="err-msg">Enter a valid email.</span></div>'
                + '</div>'
                + '<div class="form-row">'
                + '<div class="field"><label>Mobile Number <span class="req">*</span></label><input type="tel" name="phone" placeholder="+91 ..." required></div>'
                + '<div class="field"><label>Organization Type</label><select name="orgType"><option value="">Select…</option><option>Hospital</option><option>Laboratory</option><option>Clinic</option><option>Radiology Center</option><option>Other</option></select></div>'
                + '</div>'
                + '<div class="field"><label>Interested In</label><select name="interest"><option value="">Select a solution…</option><option>HIMS</option><option>LIMS</option><option>CIMS</option><option>RIS / RIMS</option><option>Multiple / Not sure</option></select></div>'
                + '<div class="field"><label>Message</label><textarea name="message" placeholder="Tell us about your requirements…"></textarea></div>'
                + '<button type="submit" class="btn btn-primary btn-block btn-lg">Request Demo</button>'
                + '<p class="form-note">By submitting, you agree to our <a href="privacy-policy" style="color:var(--cyan-dark);">Privacy Policy</a>.</p>'
                + '</form>'
                + '</div>';
            document.body.appendChild(modal);

            var form = modal.querySelector('form');
            var interestSel = form.querySelector('select[name="interest"]');
            var statusEl = form.querySelector('.form-status');
            var lastFocused = null;

            var PRODUCTS = { hims: 'HIMS', lims: 'LIMS', cims: 'CIMS', ris: 'RIS / RIMS' };
            var pagePath = (location.pathname.split('/').pop() || '').replace('.html', '');
            var pageInterest = PRODUCTS[pagePath] || '';

            var setInterest = function (val) {
                if (!val || !interestSel) return;
                for (var i = 0; i < interestSel.options.length; i++) {
                    var o = interestSel.options[i];
                    if (o.value === val || o.text === val) { interestSel.selectedIndex = i; return; }
                }
            };

            var openModal = function (interest) {
                lastFocused = document.activeElement;
                setInterest(interest);
                // Close the mobile menu if it happens to be open behind the modal
                var menu = document.getElementById('navMenu');
                var toggle = document.getElementById('navToggle');
                if (menu && menu.classList.contains('open')) {
                    menu.classList.remove('open');
                    if (toggle) toggle.classList.remove('open');
                    document.body.style.overflow = '';
                }
                modal.classList.add('open');
                document.body.classList.add('demo-open');
                var first = form.querySelector('input, select, textarea');
                if (first) setTimeout(function () { first.focus(); }, 90);
            };
            var closeModal = function () {
                modal.classList.remove('open');
                document.body.classList.remove('demo-open');
                if (lastFocused && lastFocused.focus) lastFocused.focus();
            };
            window.openDemoModal = openModal;

            modal.querySelector('.demo-modal__close').addEventListener('click', closeModal);
            modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal.classList.contains('open')) closeModal();
            });

            // Intercept every demo trigger in the capture phase so neither the
            // smooth-scroll nor the mobile-menu handlers also fire.
            var DEMO_SELECTOR = '[data-demo], .nav-cta, .mobile-cta, a[href="#demo"]';
            document.addEventListener('click', function (e) {
                var trigger = e.target.closest && e.target.closest(DEMO_SELECTOR);
                if (!trigger || modal.contains(trigger)) return;
                e.preventDefault();
                e.stopPropagation();
                openModal(trigger.getAttribute('data-demo-interest') || pageInterest);
            }, true);

            // Auto-close shortly after a successful submission
            if (statusEl) {
                new MutationObserver(function () {
                    if (statusEl.classList.contains('ok') && modal.classList.contains('open')) {
                        setTimeout(closeModal, 2600);
                    }
                }).observe(statusEl, { attributes: true, attributeFilter: ['class'] });
            }
        })();

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
        var path = location.pathname.split('/').pop() || '';
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

        /* ---------- Dynamic pricing plans (served by the secure /plans proxy) ----------
           Pricing is configured only in the SaaS admin. plans.php fetches it server-side
           (the API key never reaches the browser) and returns plans grouped by HIMS/LIMS/CIMS.
           We rebuild the same .price-card markup the calculator already binds to, so the
           billing toggle, user scaler, GST and savings keep working untouched. */
        var PLAN_CHECK_SVG = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg>';

        function planEsc(s) {
            return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
            });
        }

        function buildPlanCalc(cur) {
            return ''
                + '<div class="plan-calc" style="background:var(--bg-sec); border:1px solid var(--line); padding:16px; border-radius:8px; margin-bottom:24px; font-size:0.95rem;">'
                +   '<div style="display:flex; justify-content:space-between; margin-bottom:10px; color:var(--text-soft);"><span>Monthly Cost</span><span style="color:var(--text); font-weight:500;">' + cur + ' <span class="calc-base">0</span> <span style="font-size:0.85rem;">/ mo</span></span></div>'
                +   '<div style="background:#e8faed; border:1px solid #d1f4e0; border-radius:6px; padding:12px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); display:flex; flex-direction:column; align-items:flex-end;">'
                +     '<div style="font-weight:800; font-size:1.8rem; color:#149b82;">' + cur + '<span class="calc-billed">0</span></div>'
                +     '<div style="font-size:0.95rem; font-weight:600; margin-top:2px;"><span style="color:#8a94a6;">' + cur + '<span class="calc-billed-base">0</span></span> <span style="color:#149b82;">+ 18% GST</span></div>'
                +     '<div class="calc-savings-row" style="align-items:center; gap:6px; font-size:0.85rem; font-weight:700; color:#10b981; margin-top:8px; display:none;"><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg><span>You save ' + cur + ' <span class="calc-savings">0</span> a year</span></div>'
                +   '</div>'
                + '</div>';
        }

        function buildPlanCard(group, plan, cur) {
            var tierKey = String(plan.tier || 'plan').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
            var id = 'plan-' + group + '-' + (tierKey || 'plan');
            var py = plan.price_year, ph = plan.price_half;
            var strike = (ph != null && py != null && Number(ph) > Number(py))
                ? '<span style="font-size:1.1rem; color:var(--text-soft); text-decoration:line-through; margin-right:8px;">' + cur + planEsc(ph) + '</span>' : '';
            var featuredCls = plan.featured ? ' featured' : '';
            var btnCls = plan.featured ? 'btn-primary' : 'btn-outline';
            var signupYear = plan.signup_year || plan.signup_half || 'contact';
            var signupHalf = plan.signup_half || plan.signup_year || 'contact';
            var features = (plan.features || []).map(function (f) {
                return '<li><span class="ck">' + PLAN_CHECK_SVG + '</span><span>' + planEsc(f) + '</span></li>';
            }).join('');

            return ''
                + '<div id="' + id + '" class="price-card' + featuredCls + '">'
                +   '<div class="plan-name">' + planEsc(plan.tier) + '</div>'
                +   '<div class="plan-desc">' + planEsc(plan.desc) + '</div>'
                +   '<div class="plan-price" style="margin-bottom:10px; display:flex; flex-direction:column; align-items:flex-start;">'
                +     '<div data-year>' + strike + '<span class="cur">' + cur + '</span><span class="amt">' + planEsc(py) + '</span><span class="per">/ user / mo</span></div>'
                +     '<div data-half style="display:none;"><span class="cur">' + cur + '</span><span class="amt">' + planEsc(ph) + '</span><span class="per">/ user / mo</span></div>'
                +   '</div>'
                +   '<div class="plan-users" style="margin-top:15px; margin-bottom:15px;">'
                +     '<select class="user-select" data-base="' + planEsc(plan.base_users || 1) + '" data-price-year="' + planEsc(py) + '" data-price-half="' + planEsc(ph) + '"></select>'
                +   '</div>'
                +   buildPlanCalc(cur)
                +   '<ul class="checks">' + features + '</ul>'
                +   '<a href="' + planEsc(signupYear) + '" class="btn ' + btnCls + ' btn-block plan-signup" data-signup-year="' + planEsc(signupYear) + '" data-signup-half="' + planEsc(signupHalf) + '">⚡ Sign Up Now</a>'
                +   '<button type="button" class="plan-share" data-share="' + planEsc(signupYear) + '" data-share-year="' + planEsc(signupYear) + '" data-share-half="' + planEsc(signupHalf) + '" style="margin-top:10px; width:100%; background:none; border:none; cursor:pointer; color:var(--cyan-dark); font-weight:700; font-size:0.85rem; display:inline-flex; align-items:center; justify-content:center; gap:6px;">'
                +     '<svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg>'
                +     '<span class="plan-share-text">Share plan link</span>'
                +   '</button>'
                + '</div>';
        }

        // Visible diagnostic box: show the REAL reason a panel is empty instead of blank space.
        function planErrorHtml(title, detail) {
            return '<div style="grid-column:1/-1; padding:26px; text-align:left; border:1px solid #f1b0b7; background:#fff5f5; border-radius:12px; color:#842029;">'
                + '<p style="font-weight:800; margin:0 0 8px; font-size:1rem;">⚠ ' + planEsc(title) + '</p>'
                + (detail ? '<pre style="white-space:pre-wrap; word-break:break-word; font-size:.8rem; line-height:1.5; background:#fff; border:1px solid #f1d0d4; border-radius:8px; padding:10px 12px; margin:0 0 14px; color:#6a1a21; max-height:200px; overflow:auto;">' + planEsc(detail) + '</pre>' : '')
                + '<button type="button" class="btn btn-outline plan-retry" style="margin-right:10px;">Retry</button>'
                + '<a href="contact" class="btn btn-primary">Contact Sales</a></div>';
        }

        function renderDynamicPlans(done) {
            var mounts = document.querySelectorAll('.dynamic-plans[data-group]');
            if (!mounts.length) { if (done) done(); return; }

            mounts.forEach(function (m) {
                m.innerHTML = '<div style="grid-column:1/-1; padding:40px; text-align:center; color:var(--text-soft);">Loading plans…</div>';
            });
            var finish = function () { if (done) done(); };
            var fail = function (title, detail) {
                if (window.console && console.error) console.error('[pricing] ' + title + (detail ? '\n' + detail : ''));
                mounts.forEach(function (m) { m.innerHTML = planErrorHtml(title, detail); });
                finish();
            };

            var httpStatus = 0, ctype = '';
            fetch('plans.php?ts=' + Date.now(), { headers: { 'Accept': 'application/json' } })
                .then(function (r) {
                    httpStatus = r.status;
                    ctype = (r.headers && r.headers.get) ? (r.headers.get('content-type') || '') : '';
                    return r.text().then(function (body) { return { ok: r.ok, body: body }; });
                })
                .then(function (res) {
                    var data;
                    try { data = JSON.parse(res.body); }
                    catch (e) {
                        return fail(
                            'Pricing service did not return JSON (HTTP ' + httpStatus + ').',
                            'plans.php is likely not deployed at the site root, or the URL is being handled by the CRM / a 404 page.\n'
                            + 'Content-Type: ' + (ctype || 'n/a') + '\n'
                            + 'Response starts with:\n' + String(res.body).slice(0, 300)
                        );
                    }
                    if (!res.ok || !data || data.ok === false) {
                        return fail(
                            'Pricing service returned an error (HTTP ' + httpStatus + ').',
                            'error: ' + ((data && data.error) ? data.error : 'unknown') + '\n\n'
                            + 'If this says "Invalid credential" → the SaaS API is rejecting the key (Authorization header stripped, or token/permission mismatch).\n'
                            + 'If this says "API not configured" → the .env (API key/URL) is missing on the server.\n'
                            + 'If this says "Unable to load plans" → plans.php could not reach https://healtho.pro/saas/api/plans.'
                        );
                    }
                    var groups = data.groups || {};
                    var cur = data.currency || '₹';
                    mounts.forEach(function (m) {
                        var gk = m.getAttribute('data-group');
                        var g = groups[gk];
                        if (!g || !g.plans || !g.plans.length) {
                            m.innerHTML = planErrorHtml(
                                'No “' + String(gk).toUpperCase() + '” plans returned by the API.',
                                'The API responded OK, but no active, public package is assigned to the "' + gk + '" plan group\n'
                                + '(with a yearly + 6-month variant). Configure it in the SaaS admin → Pricing Plans.\n'
                                + 'Groups received from API: ' + (Object.keys(groups).join(', ') || '(none)')
                            );
                            return;
                        }
                        var cards = g.plans.map(function (p) { return buildPlanCard(gk, p, cur); }).join('');
                        m.innerHTML = '<div class="grid grid-3">' + cards + '</div>';
                    });
                    finish();
                })
                .catch(function (e) {
                    fail(
                        'Could not reach the pricing service.',
                        'fetch(plans.php) failed: ' + ((e && e.message) ? e.message : String(e)) + '\n'
                        + 'Possible causes: blocked by Content-Security-Policy (connect-src), a network/CORS error, or plans.php missing.'
                    );
                });
        }

        // Retry button inside the diagnostic box — reload to re-run the full pricing init cleanly.
        document.addEventListener('click', function (e) {
            var rb = e.target && e.target.closest ? e.target.closest('.plan-retry') : null;
            if (!rb) return;
            e.preventDefault();
            location.reload();
        });

        // Copy a plan's share link to the clipboard (event delegation, bound once).
        document.addEventListener('click', function (e) {
            var btn = e.target && e.target.closest ? e.target.closest('.plan-share') : null;
            if (!btn) return;
            e.preventDefault();
            var url = btn.getAttribute('data-share') || '';
            if (!url) return;
            var label = btn.querySelector('.plan-share-text');
            var flash = function () {
                if (!label) return;
                var prev = label.getAttribute('data-label') || label.textContent;
                label.setAttribute('data-label', prev);
                label.textContent = 'Link copied!';
                setTimeout(function () { label.textContent = prev; }, 1800);
            };
            var fallback = function () {
                try {
                    var ta = document.createElement('textarea');
                    ta.value = url; ta.style.position = 'fixed'; ta.style.opacity = '0';
                    document.body.appendChild(ta); ta.select();
                    document.execCommand('copy'); document.body.removeChild(ta);
                } catch (err) {}
            };
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(flash, function () { fallback(); flash(); });
            } else { fallback(); flash(); }
        });

        function initPricing() {
        /* ---------- Pricing: billing toggle ---------- */
        var billBtns = document.querySelectorAll('.billing-toggle button');

        /* ---------- Global user scaler + real-time pricing ---------- */
        var currentBillingCycle = 'year';
        var slider = document.getElementById('userSlider');
        var numInput = document.getElementById('globalUsers');
        var presetBtns = document.querySelectorAll('.user-scaler-presets button');
        var recText = document.querySelector('.user-scaler-rec-text');
        var SLIDER_MIN = 1, SLIDER_MAX = 100;
        var TIER_NAMES = { startup: 'Startup', business: 'Business', corporate: 'Corporate' };

        // Volume → recommended plan tier
        var recommendTier = function (users) {
            if (users < 10) return 'startup';
            if (users < 25) return 'business';
            return 'corporate';
        };

        // Replace each per-plan dropdown with a live user-count readout
        document.querySelectorAll('.plan-users').forEach(function (box) {
            if (box.querySelector('.plan-users-readout')) return;
            var r = document.createElement('div');
            r.className = 'plan-users-readout';
            r.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>'
                + '<span>Billed for <strong class="pu-count">0</strong> users</span>'
                + '<span class="pu-min-note" style="display:none;">min <b class="pu-min">0</b></span>';
            box.appendChild(r);
        });

        var getGlobalUsers = function () {
            var v = parseInt(numInput && numInput.value, 10);
            if (isNaN(v)) v = SLIDER_MIN;
            return Math.min(SLIDER_MAX, Math.max(SLIDER_MIN, v));
        };

        var recalculate = function () {
            var globalUsers = getGlobalUsers();
            var tier = recommendTier(globalUsers);

            document.querySelectorAll('.user-select').forEach(function (select) {
                var card = select.closest('.price-card');
                if (!card) return;

                var base = parseInt(select.getAttribute('data-base'), 10) || 1;
                var users = Math.max(globalUsers, base);
                var pricePerUser = parseInt(select.getAttribute('data-price-' + currentBillingCycle), 10);

                var subtotal = users * pricePerUser;
                var gst = Math.round(subtotal * 0.18);
                var total = subtotal + gst;
                var months = (currentBillingCycle === 'year') ? 12 : 6;
                var billedAmount = total * months;
                var billedBase = subtotal * months;

                var setTxt = function (sel, val, decimals) {
                    var el = card.querySelector(sel);
                    if (el) {
                        if (decimals) {
                            el.textContent = val.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        } else {
                            el.textContent = val.toLocaleString('en-IN');
                        }
                    }
                };
                setTxt('.calc-base', subtotal);
                setTxt('.calc-gst', gst);
                setTxt('.calc-total', total);
                var billedExact = Math.round((subtotal * months) * 1.18);
                setTxt('.calc-billed', billedExact, false);
                setTxt('.calc-billed-base', billedBase, false);

                var freqEl = card.querySelector('.calc-billed-freq');
                if (freqEl) freqEl.textContent = (currentBillingCycle === 'year') ? 'Annually' : 'Every 6 Months';

                var savingsRow = card.querySelector('.calc-savings-row');
                var savingsEl = card.querySelector('.calc-savings');
                if (currentBillingCycle === 'year') {
                    var priceHalf = parseInt(select.getAttribute('data-price-half'), 10);
                    var yearlyCostIfHalf = Math.round((users * priceHalf) * 1.18 * 12);
                    var savings = yearlyCostIfHalf - billedAmount;
                    if (savingsRow) {
                        savingsRow.style.display = 'flex';
                        if (savingsEl) savingsEl.textContent = savings.toLocaleString('en-IN');
                    }
                } else if (savingsRow) {
                    savingsRow.style.display = 'none';
                }

                // Per-plan user readout + minimum-seats note
                var cntEl = card.querySelector('.pu-count');
                if (cntEl) cntEl.textContent = users;
                var minNote = card.querySelector('.pu-min-note');
                var minEl = card.querySelector('.pu-min');
                if (minNote) {
                    if (globalUsers < base) {
                        minNote.style.display = '';
                        if (minEl) minEl.textContent = base;
                    } else {
                        minNote.style.display = 'none';
                    }
                }

                // Volume-based recommendation highlight (overrides static "Most Popular")
                var cardTier = card.id.split('-').pop();
                card.classList.remove('featured');
                card.classList.toggle('recommended', cardTier === tier);
            });

            if (recText) {
                recText.innerHTML = 'For <strong>' + globalUsers + ' user' + (globalUsers > 1 ? 's' : '')
                    + '</strong>, we recommend the <strong>' + TIER_NAMES[tier] + '</strong> plan.';
            }
        };

        var syncUsers = function (value, source) {
            value = parseInt(value, 10);
            if (isNaN(value)) value = SLIDER_MIN;
            value = Math.min(SLIDER_MAX, Math.max(SLIDER_MIN, value));
            if (numInput && source !== 'num') numInput.value = value;
            if (slider && source !== 'slider') slider.value = value;
            if (slider) slider.style.setProperty('--fill', ((value - SLIDER_MIN) / (SLIDER_MAX - SLIDER_MIN) * 100) + '%');
            presetBtns.forEach(function (b) { b.classList.toggle('active', parseInt(b.dataset.users, 10) === value); });
            recalculate();
        };

        if (slider) slider.addEventListener('input', function () { syncUsers(slider.value, 'slider'); });
        if (numInput) {
            numInput.addEventListener('input', function () { syncUsers(numInput.value, 'num'); });
            numInput.addEventListener('blur', function () { syncUsers(numInput.value, 'blur'); });
        }
        document.querySelectorAll('.us-step').forEach(function (btn) {
            btn.addEventListener('click', function () { syncUsers(getGlobalUsers() + parseInt(btn.dataset.step, 10), 'step'); });
        });
        presetBtns.forEach(function (b) {
            b.addEventListener('click', function () { syncUsers(b.dataset.users, 'preset'); });
        });

        var setBilling = function (cycle) {
            currentBillingCycle = cycle;
            billBtns.forEach(function (b) { b.classList.toggle('active', b.dataset.cycle === cycle); });
            document.querySelectorAll('[data-half]').forEach(function (el) {
                el.style.display = (cycle === 'half') ? '' : 'none';
            });
            document.querySelectorAll('[data-year]').forEach(function (el) {
                el.style.display = (cycle === 'year') ? '' : 'none';
            });
            // Point Sign Up + Share at the package matching the active billing cycle
            document.querySelectorAll('.plan-signup').forEach(function (a) {
                var u = a.getAttribute('data-signup-' + cycle);
                if (u) a.setAttribute('href', u);
            });
            document.querySelectorAll('.plan-share').forEach(function (b) {
                var u = b.getAttribute('data-share-' + cycle);
                if (u) b.setAttribute('data-share', u);
            });
            recalculate();
        };
        billBtns.forEach(function (b) {
            b.addEventListener('click', function () { setBilling(b.dataset.cycle); });
        });
        if (slider) syncUsers(getGlobalUsers(), 'init');
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
        } // end initPricing

        // Build cards from the secure proxy first, then wire up the pricing interactions.
        renderDynamicPlans(initPricing);

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

        /* ---------- Auto-detect location (city / state / country) from IP ---------- */
        (function () {
            var geoForms = document.querySelectorAll('form[data-ajax]');
            if (!geoForms.length) return;

            // Ensure each form carries hidden geo fields
            geoForms.forEach(function (form) {
                ['city', 'state', 'country'].forEach(function (name) {
                    if (!form.querySelector('input[name="' + name + '"]')) {
                        var inp = document.createElement('input');
                        inp.type = 'hidden';
                        inp.name = name;
                        form.appendChild(inp);
                    }
                });
            });

            var applyGeo = function (city, state, country) {
                geoForms.forEach(function (form) {
                    if (city) form.querySelector('input[name="city"]').value = city;
                    if (state) form.querySelector('input[name="state"]').value = state;
                    if (country) form.querySelector('input[name="country"]').value = country;
                });
            };

            // Primary provider, with a fallback — geo is best-effort and never blocks the form
            fetch('https://ipwho.is/')
                .then(function (r) { return r.json(); })
                .then(function (d) {
                    if (d && d.success !== false) {
                        applyGeo(d.city || '', d.region || '', d.country || '');
                    } else {
                        throw new Error('primary geo unavailable');
                    }
                })
                .catch(function () {
                    fetch('https://ipapi.co/json/')
                        .then(function (r) { return r.json(); })
                        .then(function (d) { if (d) applyGeo(d.city || '', d.region || '', d.country_name || d.country || ''); })
                        .catch(function () { /* geo optional — ignore */ });
                });
        })();

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
