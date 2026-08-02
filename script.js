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

        /* ---------- Sticky pricing control bar: condense once it docks under the nav ---------- */
        var pricingBar = document.getElementById('pricingBar');
        if (pricingBar) {
            var navH = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--nav-h'), 10) || 76;
            var syncBar = function () {
                pricingBar.classList.toggle('stuck', pricingBar.getBoundingClientRect().top <= navH + 1);
            };
            window.addEventListener('scroll', syncBar, { passive: true });
            window.addEventListener('resize', syncBar);
            syncBar();
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

        /* ---------- Sale offers (SaaS admin ▸ SaaS ▸ Sale Offer) ----------
           An offer is a live, time-boxed discount. plans.php already returns the
           discounted per-user price in price_* and the untouched list price in list_*,
           so the calculator needs no special casing — the offer layer only adds the
           urgency furniture: banner, countdowns, ribbons and the strike-through. */
        var OFFERS = {
            list: [],          // banner-eligible offers, strongest first
            skewMs: 0,         // server clock - browser clock, so countdowns can't be faked/skewed
            reloading: false
        };

        // Accent palette per theme name (mirrors the choices in the SaaS admin). Every piece
        // of offer furniture — banner gradient, card border, ribbon, coupon, savings row —
        // is derived from ONE entry, so an offer never mixes two accent colours.
        //   g1/g2 = banner gradient · solid = ribbon & borders · tint/ink = soft chips
        var OFFER_THEMES = {
            amber:  { g1: '#F59E0B', g2: '#EA580C', solid: '#D97706', tint: '#FEF3C7', ink: '#92400E', rgb: '217,119,6' },
            red:    { g1: '#EF4444', g2: '#B91C1C', solid: '#DC2626', tint: '#FEE2E2', ink: '#B91C1C', rgb: '220,38,38' },
            green:  { g1: '#10B981', g2: '#047857', solid: '#059669', tint: '#D1FAE5', ink: '#047857', rgb: '5,150,105' },
            cyan:   { g1: '#00B4D8', g2: '#0369A1', solid: '#0284C7', tint: '#E0F2FE', ink: '#075985', rgb: '2,132,199' },
            navy:   { g1: '#1E3D7B', g2: '#0C1F45', solid: '#1E3D7B', tint: '#E7ECF7', ink: '#122B5C', rgb: '30,61,123' },
            purple: { g1: '#7C3AED', g2: '#4C1D95', solid: '#7C3AED', tint: '#EDE9FE', ink: '#5B21B6', rgb: '124,58,237' }
        };

        function offerTheme(name) { return OFFER_THEMES[name] || OFFER_THEMES.amber; }

        // The CSS custom properties every offer element reads.
        function offerThemeVars(t) {
            return '--of-accent:' + t.solid + '; --of-tint:' + t.tint + '; --of-ink:' + t.ink
                + '; --of-glow:rgba(' + t.rgb + ',.28); --of-line:rgba(' + t.rgb + ',.35);';
        }

        function offerNow() { return Date.now() + OFFERS.skewMs; }

        // Remaining time as parts, or null once the offer has ended.
        function offerRemaining(endsTs) {
            var ms = (Number(endsTs) * 1000) - offerNow();
            if (!(ms > 0)) return null;
            return {
                d: Math.floor(ms / 86400000),
                h: Math.floor(ms / 3600000) % 24,
                m: Math.floor(ms / 60000) % 60,
                s: Math.floor(ms / 1000) % 60
            };
        }

        function pad2(n) { return (n < 10 ? '0' : '') + n; }

        // The offer that should headline a given product tab: group-specific beats
        // site-wide, then the admin's priority order (plans.php sorts by priority).
        function offerForGroup(groupKey) {
            var scoped = null, global = null;
            OFFERS.list.forEach(function (o) {
                var keys = o.group_keys || [];
                if (keys.length) {
                    if (!scoped && keys.indexOf(groupKey) !== -1) scoped = o;
                } else if (!global) {
                    global = o;
                }
            });
            return scoped || global;
        }

        var COUPON_SVG = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 12a2 2 0 0 1 2-2V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v3a2 2 0 0 1 0 4v3a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-3a2 2 0 0 1-2-2Z"/><path d="M13 5v14" stroke-dasharray="2 3"/></svg>';
        var CLOCK_SVG = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>';
        var FIRE_SVG = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 2s5 4.5 5 9a5 5 0 0 1-10 0c0-1.6.6-2.9 1.3-3.9C8.9 8.6 9.6 9.4 10 10c0-2.7 2-6.4 2-8Z"/><path d="M7 17a5 5 0 0 0 10 0"/></svg>';

        function buildCountdown(endsTs, dark) {
            if (!endsTs) return '';
            return ''
                + '<div class="offer-countdown" data-offer-ends="' + planEsc(endsTs) + '">'
                +   '<span class="offer-countdown-lbl">Ends in</span>'
                +   '<div class="oc-unit"><b data-oc="d">00</b><span>Days</span></div>'
                +   '<div class="oc-unit"><b data-oc="h">00</b><span>Hrs</span></div>'
                +   '<div class="oc-unit"><b data-oc="m">00</b><span>Min</span></div>'
                +   '<div class="oc-unit oc-sec"><b data-oc="s">00</b><span>Sec</span></div>'
                + '</div>';
        }

        function buildOfferBanner(offer) {
            var t = offerTheme(offer.theme);
            var style = '--ob-1:' + t.g1 + '; --ob-2:' + t.g2 + '; --ob-ink:' + t.ink + ';';

            // Scarcity meter. The caption stays short because the urgency note beside it
            // already carries the message — no "12 slots left" twice.
            var meter = '';
            if (offer.seats_total && offer.seats_left !== null && offer.seats_left !== undefined) {
                var total = Number(offer.seats_total), left = Math.max(0, Number(offer.seats_left));
                var taken = total > 0 ? Math.max(0, Math.min(100, Math.round(((total - left) / total) * 100))) : 0;
                meter = '<div class="offer-meter">'
                    + '<div class="offer-meter-head"><span>' + taken + '% claimed</span><span>' + left + ' of ' + total + ' left</span></div>'
                    + '<div class="offer-meter-bar"><div class="offer-meter-fill" style="width:' + taken + '%;"></div></div>'
                    + '</div>';
            }

            var note = offer.urgency_note
                ? '<div class="offer-note">' + FIRE_SVG + '<span>' + planEsc(offer.urgency_note) + '</span></div>' : '';

            var coupon = offer.coupon_code
                ? '<button type="button" class="offer-coupon offer-copy" data-code="' + planEsc(offer.coupon_code) + '">'
                    + COUPON_SVG + '<span class="offer-coupon-lbl">Code</span>'
                    + '<span class="offer-copy-text">' + planEsc(offer.coupon_code) + '</span></button>' : '';

            var cta = offer.cta_text
                ? '<a class="offer-cta" href="' + planEsc(offer.cta_url || 'contact') + '">' + planEsc(offer.cta_text) + '</a>' : '';

            var countdown = (offer.show_countdown && offer.ends_ts) ? buildCountdown(offer.ends_ts) : '';

            return ''
                + '<div class="offer-banner" style="' + style + '" data-offer-id="' + planEsc(offer.id) + '">'
                +   '<div class="offer-main">'
                +     '<div class="offer-tags">'
                +       '<span class="offer-badge"><span class="offer-dot"></span>' + planEsc(offer.badge || 'Limited Time') + '</span>'
                +       (offer.discount_label ? '<span class="offer-save">' + planEsc(offer.discount_label) + '</span>' : '')
                +     '</div>'
                +     (offer.headline ? '<div class="offer-headline">' + planEsc(offer.headline) + '</div>' : '')
                +     (offer.subtext ? '<div class="offer-sub">' + planEsc(offer.subtext) + '</div>' : '')
                +     ((note || meter) ? '<div class="offer-facts">' + note + meter + '</div>' : '')
                +   '</div>'
                +   '<div class="offer-side">'
                +     (countdown ? '<div class="offer-side-row">' + countdown + '</div>' : '')
                +     ((coupon || cta) ? '<div class="offer-side-row">' + coupon + cta + '</div>' : '')
                +   '</div>'
                + '</div>';
        }

        // Show the banner belonging to the product tab currently in view.
        function renderOfferBanner(groupKey) {
            var mount = document.getElementById('offerBanner');
            if (!mount) return;
            var offer = OFFERS.list.length ? offerForGroup(groupKey) : null;
            if (!offer || (offer.ends_ts && !offerRemaining(offer.ends_ts))) {
                mount.innerHTML = '';
                return;
            }
            mount.innerHTML = buildOfferBanner(offer);
            tickOfferCountdowns();
        }

        // One timer drives every countdown on the page (banner + cards).
        function tickOfferCountdowns() {
            var expired = false;

            document.querySelectorAll('.offer-countdown[data-offer-ends]').forEach(function (el) {
                var r = offerRemaining(el.getAttribute('data-offer-ends'));
                if (!r) { expired = true; return; }
                var set = function (k, v) {
                    var n = el.querySelector('[data-oc="' + k + '"]');
                    if (n) n.textContent = v;
                };
                set('d', pad2(r.d)); set('h', pad2(r.h)); set('m', pad2(r.m)); set('s', pad2(r.s));
            });

            document.querySelectorAll('.plan-offer-ends[data-offer-ends]').forEach(function (el) {
                var r = offerRemaining(el.getAttribute('data-offer-ends'));
                var txt = el.querySelector('b');
                if (!r) { expired = true; if (txt) txt.textContent = 'ended'; return; }
                if (!txt) return;
                txt.textContent = r.d > 0
                    ? (r.d + 'd ' + pad2(r.h) + 'h ' + pad2(r.m) + 'm')
                    : (pad2(r.h) + ':' + pad2(r.m) + ':' + pad2(r.s));
            });

            // An offer that runs out while the page is open must not keep selling:
            // pull fresh pricing once (list prices come back automatically).
            if (expired && !OFFERS.reloading) {
                OFFERS.reloading = true;
                setTimeout(function () { location.reload(); }, 1500);
            }
        }
        setInterval(tickOfferCountdowns, 1000);

        // Copy a coupon code (banner chip and per-card chip share this handler).
        document.addEventListener('click', function (e) {
            var btn = e.target && e.target.closest ? e.target.closest('.offer-copy') : null;
            if (!btn) return;
            e.preventDefault();
            var code = btn.getAttribute('data-code') || '';
            var label = btn.querySelector('.offer-copy-text');
            var flash = function () {
                if (!label) return;
                var prev = label.getAttribute('data-code-label') || label.textContent;
                label.setAttribute('data-code-label', prev);
                label.textContent = 'Copied!';
                setTimeout(function () { label.textContent = prev; }, 1600);
            };
            var fallback = function () {
                try {
                    var ta = document.createElement('textarea');
                    ta.value = code; ta.style.position = 'fixed'; ta.style.opacity = '0';
                    document.body.appendChild(ta); ta.select();
                    document.execCommand('copy'); document.body.removeChild(ta);
                } catch (err) {}
            };
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(code).then(flash, function () { fallback(); flash(); });
            } else { fallback(); flash(); }
        });

        // Prices are rendered with Indian digit grouping so the headline figure and the
        // struck-through list price read as one set (₹12,000 next to ₹9,000, not ₹9000).
        function planNum(v) {
            var n = Number(v);
            return isNaN(n) ? planEsc(v) : planEsc(n.toLocaleString('en-IN'));
        }

        function buildPlanCalc(cur) {
            return ''
                + '<div class="plan-calc" style="background:var(--bg-sec); border:1px solid var(--line); padding:16px; border-radius:8px; margin-bottom:24px; font-size:0.95rem;">'
                +   '<div style="display:flex; justify-content:space-between; margin-bottom:6px; color:var(--text-soft);"><span>Per User &times; <span class="calc-users">1</span></span><span style="color:var(--text); font-weight:500;">' + cur + ' <span class="calc-per-user">0</span> <span style="font-size:0.85rem;">/ user / mo</span></span></div>'
                +   '<div style="display:flex; justify-content:space-between; margin-bottom:10px; color:var(--text-soft);"><span>Monthly Cost</span><span style="color:var(--text); font-weight:500;">' + cur + ' <span class="calc-base">0</span> <span style="font-size:0.85rem;">/ mo</span></span></div>'
                +   '<div class="calc-offer-row"><span class="calc-offer-label">Sale discount</span><span>&minus; ' + cur + '<span class="calc-offer-saving">0</span></span></div>'
                +   '<div style="background:#e8faed; border:1px solid #d1f4e0; border-radius:6px; padding:12px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); display:flex; flex-direction:column; align-items:flex-end;">'
                +     '<div style="font-weight:800; font-size:1.8rem; color:#149b82;">' + cur + '<span class="calc-billed">0</span></div>'
                +     '<div style="font-size:0.95rem; font-weight:600; margin-top:2px;"><span style="color:#8a94a6;">' + cur + '<span class="calc-billed-base">0</span></span> <span style="color:#149b82;">+ 18% GST</span></div>'
                +     '<div class="calc-savings-row" style="align-items:flex-start; gap:7px; width:100%; text-align:left; border-top:1px dashed rgba(20,155,130,.3); padding-top:9px; font-size:0.85rem; font-weight:700; color:#10b981; margin-top:10px; display:none;"><svg viewBox="0 0 24 24" width="14" height="14" style="margin-top:2px; flex:none;" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg><span>You save ' + cur + ' <span class="calc-savings">0</span> a year<span class="calc-savings-note"></span></span></div>'
                +   '</div>'
                + '</div>';
        }

        function buildPlanCard(group, plan, cur) {
            var tierKey = String(plan.tier || 'plan').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
            var id = 'plan-' + group + '-' + (tierKey || 'plan');
            var py = plan.price_year, ph = plan.price_half, pq = plan.price_quarter;
            var offer = plan.offer || null;

            // A cycle is "on sale" when the SaaS discounted its per-user price.
            var listOf = { year: plan.list_year, half: plan.list_half, quarter: plan.list_quarter };
            var priceOf = { year: py, half: ph, quarter: pq };
            var onSale = {};
            ['year', 'half', 'quarter'].forEach(function (c) {
                onSale[c] = !!(offer && listOf[c] != null && priceOf[c] != null && Number(listOf[c]) > Number(priceOf[c]));
            });

            // Struck-through reference price. During a sale it is this cycle's own list
            // price; otherwise it falls back to the highest (shortest-cycle) rate, which is
            // how the page has always shown the yearly saving.
            var strikeFor = function (cycle) {
                if (onSale[cycle]) {
                    var pct = Math.round((1 - Number(priceOf[cycle]) / Number(listOf[cycle])) * 100);
                    return '<span class="plan-was">' + cur + planNum(listOf[cycle]) + '</span>'
                        + (pct > 0 ? '<span class="plan-off" data-plan-off>Save ' + pct + '%</span>' : '');
                }
                if (cycle !== 'year') return '';
                var origForYear = (pq != null) ? pq : ph;
                return (origForYear != null && py != null && Number(origForYear) > Number(py))
                    ? '<span style="font-size:1.1rem; color:var(--text-soft); text-decoration:line-through; margin-right:8px;">' + cur + planNum(origForYear) + '</span>' : '';
            };
            var strike = strikeFor('year');

            var offerStyle = offer ? offerThemeVars(offerTheme(offer.theme)) : '';
            var offerTag = offer
                ? '<span class="plan-offer-tag">' + planEsc(offer.discount_label || 'Sale') + '</span>' : '';
            var offerEnds = (offer && offer.ends_ts)
                ? '<div class="plan-offer-ends" data-offer-ends="' + planEsc(offer.ends_ts) + '">' + CLOCK_SVG
                    + '<span>Offer ends in <b>—</b></span></div>' : '';
            var offerCoupon = (offer && offer.coupon_code)
                ? '<button type="button" class="plan-coupon offer-copy" data-code="' + planEsc(offer.coupon_code) + '">'
                    + COUPON_SVG + '<span>Use code <span class="offer-copy-text">' + planEsc(offer.coupon_code) + '</span></span></button>' : '';
            var featuredCls = plan.featured ? ' featured' : '';
            var btnCls = plan.featured ? 'btn-primary' : 'btn-outline';
            var signupYear = plan.signup_year || plan.signup_half || plan.signup_quarter || 'contact';
            var signupHalf = plan.signup_half || plan.signup_year || plan.signup_quarter || 'contact';
            var signupQuarter = plan.signup_quarter || plan.signup_half || plan.signup_year || 'contact';
            // Features may be plain strings (legacy) or objects {name, desc} from the
            // improved plans API. Show the namesake name; attach the description as a
            // tooltip when present. Order and visibility are already handled by the API.
            var features = (plan.features || []).map(function (f) {
                var name = (f && typeof f === 'object') ? (f.name || '') : f;
                var desc = (f && typeof f === 'object') ? (f.desc || '') : '';
                var titleAttr = desc ? ' title="' + planEsc(desc) + '"' : '';
                return '<li' + titleAttr + '><span class="ck">' + PLAN_CHECK_SVG + '</span><span>' + planEsc(name) + '</span></li>';
            }).join('');

            return ''
                + '<div id="' + id + '" class="price-card' + featuredCls + '"'
                +   (offerStyle ? ' style="' + offerStyle + '"' : '') + '>'
                +   offerTag
                +   '<div class="plan-name">' + planEsc(plan.tier) + '</div>'
                +   '<div class="plan-desc">' + planEsc(plan.desc) + '</div>'
                +   '<div class="plan-price" style="display:flex; flex-direction:column; align-items:flex-start;">'
                +     '<div data-year>' + strike + '<span class="cur">' + cur + '</span><span class="amt">' + planNum(py) + '</span><span class="per">/ user / yr</span></div>'
                +     '<div data-half style="display:none;">' + strikeFor('half') + '<span class="cur">' + cur + '</span><span class="amt">' + planNum(ph) + '</span><span class="per">/ user / 6 mo</span></div>'
                +     '<div data-quarter style="display:none;">' + strikeFor('quarter') + '<span class="cur">' + cur + '</span><span class="amt">' + planNum(pq) + '</span><span class="per">/ user / 4 mo</span></div>'
                +   '</div>'
                +   offerEnds
                +   '<div class="plan-users">'
                +     '<select class="user-select" data-base="' + planEsc(plan.base_users || 1) + '" data-price-year="' + planEsc(py) + '" data-price-half="' + planEsc(ph) + '" data-price-quarter="' + planEsc(pq) + '"'
                +       ' data-list-year="' + planEsc(plan.list_year != null ? plan.list_year : py) + '"'
                +       ' data-list-half="' + planEsc(plan.list_half != null ? plan.list_half : ph) + '"'
                +       ' data-list-quarter="' + planEsc(plan.list_quarter != null ? plan.list_quarter : pq) + '"'
                +       ' data-native-year="' + (plan.native_year ? 1 : 0) + '" data-native-half="' + (plan.native_half ? 1 : 0) + '" data-native-quarter="' + (plan.native_quarter ? 1 : 0) + '"'
                +       ' data-sale-year="' + (onSale.year ? 1 : 0) + '" data-sale-half="' + (onSale.half ? 1 : 0) + '" data-sale-quarter="' + (onSale.quarter ? 1 : 0) + '"></select>'
                +   '</div>'
                +   buildPlanCalc(cur)
                +   offerCoupon
                +   '<ul class="checks">' + features + '</ul>'
                +   '<a href="' + planEsc(signupYear) + '" class="btn ' + btnCls + ' btn-block plan-signup" data-signup-year="' + planEsc(signupYear) + '" data-signup-half="' + planEsc(signupHalf) + '" data-signup-quarter="' + planEsc(signupQuarter) + '">⚡ Sign Up Now</a>'
                +   '<button type="button" class="plan-share" data-share="' + planEsc(signupYear) + '" data-share-year="' + planEsc(signupYear) + '" data-share-half="' + planEsc(signupHalf) + '" data-share-quarter="' + planEsc(signupQuarter) + '" style="margin-top:10px; width:100%; background:none; border:none; cursor:pointer; color:var(--cyan-dark); font-weight:700; font-size:0.85rem; display:inline-flex; align-items:center; justify-content:center; gap:6px;">'
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

                    // Sale offers: trust the SERVER clock for every countdown.
                    OFFERS.list = (data.offers || []).filter(function (o) {
                        return !o.ends_ts || (Number(o.ends_ts) * 1000) > (data.now_ts ? Number(data.now_ts) * 1000 : Date.now());
                    });
                    OFFERS.skewMs = data.now_ts ? (Number(data.now_ts) * 1000 - Date.now()) : 0;

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
        var SLIDER_MIN = 5, SLIDER_MAX = 100;
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

                // Sale offer: the per-user price is already discounted, so the saving is
                // simply the gap to the list price for the active cycle.
                var listPerUser = parseInt(select.getAttribute('data-list-' + currentBillingCycle), 10);
                var onSale = select.getAttribute('data-sale-' + currentBillingCycle) === '1';
                card.classList.toggle('has-offer', onSale);
                if (onSale && !isNaN(listPerUser)) {
                    var offerSaving = (listPerUser - pricePerUser) * users;
                    var savingEl2 = card.querySelector('.calc-offer-saving');
                    if (savingEl2) savingEl2.textContent = Math.round(offerSaving).toLocaleString('en-IN');
                    var offerLabel = card.querySelector('.calc-offer-label');
                    var tagEl = card.querySelector('.plan-offer-tag');
                    if (offerLabel && tagEl) offerLabel.textContent = tagEl.textContent;
                }

                // pricePerUser is the price per user for the WHOLE billing cycle.
                var CYCLE_MONTHS = { year: 12, half: 6, quarter: 4 };
                var months = CYCLE_MONTHS[currentBillingCycle] || 12;
                var cycleBase = users * pricePerUser;          // pre-GST, billed once per cycle
                var gst = Math.round(cycleBase * 0.18);
                var cycleTotal = cycleBase + gst;              // incl GST, billed once per cycle
                var monthly = cycleBase / months;              // monthly-equivalent (pre-GST)

                var setTxt = function (sel, val) {
                    var el = card.querySelector(sel);
                    if (el) el.textContent = val.toLocaleString('en-IN');
                };
                setTxt('.calc-users', users);
                setTxt('.calc-per-user', Math.round(pricePerUser / months));
                setTxt('.calc-base', Math.round(monthly));
                setTxt('.calc-gst', gst);
                setTxt('.calc-total', Math.round(cycleTotal));
                // Billed amounts are shown as whole rupees — cycleTotal already rounds the GST
                setTxt('.calc-billed', cycleTotal);
                setTxt('.calc-billed-base', cycleBase);

                var freqEl = card.querySelector('.calc-billed-freq');
                var CYCLE_FREQ = { year: 'Annually', half: 'Every 6 Months', quarter: 'Every 4 Months' };
                if (freqEl) freqEl.textContent = CYCLE_FREQ[currentBillingCycle] || 'Annually';

                // "You save X a year" — the real, total annual saving versus the worst deal
                // this plan offers: the dearest annualised LIST rate among the billing cycles
                // the plan actually sells (a cycle copied from another one is not a real
                // alternative, so it is skipped). That single baseline covers both sources of
                // saving — a longer billing cycle and a running sale — so the row keeps
                // working for plans that have no separate quarterly package.
                var savingsRow = card.querySelector('.calc-savings-row');
                var savingsEl = card.querySelector('.calc-savings');
                var savingsNote = card.querySelector('.calc-savings-note');

                var baselineAnnual = 0;
                ['quarter', 'half', 'year'].forEach(function (c) {
                    if (select.getAttribute('data-native-' + c) !== '1') return;
                    var listC = parseInt(select.getAttribute('data-list-' + c), 10);
                    if (isNaN(listC)) return;
                    var annual = listC * (12 / (CYCLE_MONTHS[c] || 12));
                    if (annual > baselineAnnual) baselineAnnual = annual;
                });

                var currentAnnual = pricePerUser * (12 / months);
                var savings = Math.round((baselineAnnual - currentAnnual) * users * 1.18);

                if (savingsRow) {
                    if (baselineAnnual > 0 && savings > 0) {
                        savingsRow.style.display = 'flex';
                        if (savingsEl) savingsEl.textContent = savings.toLocaleString('en-IN');
                        if (savingsNote) savingsNote.textContent = onSale ? ' with this offer' : '';
                    } else {
                        savingsRow.style.display = 'none';
                    }
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

        /* ---------- "Save X%" badges on the billing toggle ----------
           Computed from the plans actually on screen instead of a hardcoded number: for
           each cycle, compare its annualised LIST price against the dearest alternative
           cycle the same plan really sells. The badge disappears when there is nothing
           genuine to claim. */
        var CYCLE_MONTHS_STATIC = { year: 12, half: 6, quarter: 4 };

        var updateCycleBadges = function () {
            var panel = document.querySelector('.price-panel.active');
            var selects = panel ? panel.querySelectorAll('.user-select') : [];
            if (!selects.length) return; // no plans on this tab (e.g. RIS) — leave as-is

            ['year', 'half', 'quarter'].forEach(function (cycle) {
                var btn = document.querySelector('.billing-toggle button[data-cycle="' + cycle + '"]');
                if (!btn) return;
                var badge = btn.querySelector('.save-badge');

                var pcts = [];
                selects.forEach(function (sel) {
                    if (sel.getAttribute('data-native-' + cycle) !== '1') return;
                    var mine = parseInt(sel.getAttribute('data-list-' + cycle), 10);
                    if (isNaN(mine)) return;
                    mine = mine * (12 / CYCLE_MONTHS_STATIC[cycle]);

                    var baseline = 0;
                    ['quarter', 'half', 'year'].forEach(function (c) {
                        if (c === cycle || sel.getAttribute('data-native-' + c) !== '1') return;
                        var lp = parseInt(sel.getAttribute('data-list-' + c), 10);
                        if (isNaN(lp)) return;
                        var annual = lp * (12 / CYCLE_MONTHS_STATIC[c]);
                        if (annual > baseline) baseline = annual;
                    });

                    if (!baseline) return;
                    var pct = Math.round((1 - mine / baseline) * 100);
                    if (pct > 0) pcts.push(pct);
                });

                if (!pcts.length) {
                    if (badge) badge.remove();
                    return;
                }
                var min = Math.min.apply(null, pcts), max = Math.max.apply(null, pcts);
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'save-badge';
                    btn.appendChild(badge);
                }
                badge.textContent = (min === max ? 'Save ' : 'Save up to ') + max + '%';
            });
        };

        var setBilling = function (cycle) {
            currentBillingCycle = cycle;
            billBtns.forEach(function (b) { b.classList.toggle('active', b.dataset.cycle === cycle); });
            ['year', 'half', 'quarter'].forEach(function (c) {
                document.querySelectorAll('[data-' + c + ']').forEach(function (el) {
                    el.style.display = (cycle === c) ? '' : 'none';
                });
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
                renderOfferBanner(tab.dataset.product);
                updateCycleBadges();
            });
        });

        // Banner + cycle badges for the tab that is active on load.
        var activeTab = document.querySelector('.product-tab.active');
        renderOfferBanner(activeTab ? activeTab.dataset.product : 'hims');
        updateCycleBadges();

        /* ---------- Handle hash on load for pricing plans & tabs ---------- */
        if (window.location.hash) {
            var fullHash = window.location.hash.substring(1);
            var billingMatch = fullHash.match(/-(year|half|quarter)$/);
            if (billingMatch) {
                setBilling(billingMatch[1]);
            }
            var hash = fullHash.replace(/-(year|half|quarter)$/, '');
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
                var billingMatch = href.match(/-(year|half|quarter)$/);
                if (billingMatch) {
                    setBilling(billingMatch[1]);
                    targetId = href.replace(/-(year|half|quarter)$/, '');
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
