/* ==========================================================================
   PRICING BROCHURE — vector PDF
   Draws the A4 sheet straight into a PDF with jsPDF: every price, rule and
   label is real vector text, so the file stays sharp at any zoom, the prices
   can be selected and searched, and the sign-up links are clickable. A canvas
   snapshot of the page could do none of that — it ships pixels, and pixels are
   what made the first version of this sheet look soft in print.

   The layout mirrors the pricing cards on screen rather than the DOM: the
   caller hands over plain data (see download()), this file owns the geometry.
   All measurements are millimetres on A4; type sizes stay in points to match
   the design the site's CSS already uses.

   Fonts: js/vendor/pjs-fonts.js carries Plus Jakarta Sans 400/700/800 as
   base64 TrueType. To regenerate, pull the TTFs Google Fonts serves for
   `family=Plus+Jakarta+Sans:wght@400;700;800` and base64 them into that file
   under the keys normal / bold / extrabold.
   ========================================================================== */
(function (root) {
    'use strict';

    var PT = 25.4 / 72;             // points → mm
    var PAGE = { w: 210, h: 297 };
    var M = { top: 11, side: 10, bottom: 12 };
    var COL = { x: M.side, w: PAGE.w - M.side * 2 };

    var C = {
        cyan: '#00B4D8', navy: '#0B2545', ink: '#0F172A',
        body: '#475569', mid: '#64748B', soft: '#8092A8', faint: '#94A3B8',
        line: '#DBE3EC', hair: '#E6ECF3', dash: '#C7D3E0',
        wash: '#F1F8FC', green: '#0F7A66', mint: '#10B981',
        link: '#0096B7', slate: '#334155', white: '#FFFFFF'
    };

    var FONT = 'PJS';
    var R = 'normal', B = 'bold', X = 'extrabold';

    /* ---------- one-time asset loading ---------- */

    var assets = null;
    function load() {
        if (assets) return assets;
        assets = Promise.all([
            need('/js/vendor/jspdf.umd.min.js', function () { return root.jspdf && root.jspdf.jsPDF; }),
            need('/js/vendor/pjs-fonts.js', function () { return root.HealthOBrochureFonts; }),
            dataUrl('/assets/images/logo.png')
        ]).then(function (out) {
            return { jsPDF: root.jspdf.jsPDF, fonts: root.HealthOBrochureFonts, logo: out[2] };
        }).catch(function (err) {
            assets = null;                       // let a later click try again
            throw err;
        });
        return assets;
    }

    function need(src, test) {
        if (test()) return Promise.resolve();
        return new Promise(function (resolve, reject) {
            var s = document.createElement('script');
            s.src = src;
            s.onload = function () { test() ? resolve() : reject(new Error(src + ' loaded empty')); };
            s.onerror = function () { reject(new Error(src + ' failed')); };
            document.head.appendChild(s);
        });
    }

    function dataUrl(src) {
        return fetch(src).then(function (r) { return r.blob(); }).then(function (b) {
            return new Promise(function (resolve, reject) {
                var fr = new FileReader();
                fr.onload = function () { resolve(fr.result); };
                fr.onerror = reject;
                fr.readAsDataURL(b);
            });
        });
    }

    /* ---------- drawing helpers ---------- */

    function Sheet(doc) {
        this.d = doc;
    }

    // Type is set in points, the size the design is drawn at; jsPDF keeps its
    // own unit (mm) for coordinates, so the two never have to be reconciled.
    Sheet.prototype.font = function (style, size, colour) {
        this.d.setFont(FONT, style);
        this.d.setFontSize(size);
        if (colour) this.d.setTextColor(colour);
        return this;
    };

    // y is the text baseline. Every caller works in baselines so that mixed
    // sizes on one line (a big price next to a small unit) sit correctly.
    Sheet.prototype.text = function (str, x, y, opts) {
        opts = opts || {};
        if (opts.spacing) this.d.setCharSpace(opts.spacing);
        this.d.text(String(str), x, y, opts.align ? { align: opts.align } : undefined);
        if (opts.spacing) this.d.setCharSpace(0);
        return this;
    };

    Sheet.prototype.width = function (str) { return this.d.getTextWidth(String(str)); };

    Sheet.prototype.rule = function (x1, y, x2, colour, pt, dashed) {
        this.d.setDrawColor(colour);
        this.d.setLineWidth(pt * PT);
        if (dashed) this.d.setLineDashPattern([0.7, 0.7], 0);
        this.d.line(x1, y, x2, y);
        if (dashed) this.d.setLineDashPattern([], 0);
        return this;
    };

    Sheet.prototype.box = function (x, y, w, h, r, fill, stroke, pt) {
        var d = this.d;
        if (fill) d.setFillColor(fill);
        if (stroke) { d.setDrawColor(stroke); d.setLineWidth((pt || 0.6) * PT); }
        var mode = fill && stroke ? 'FD' : (fill ? 'F' : 'S');
        r ? d.roundedRect(x, y, w, h, r, r, mode) : d.rect(x, y, w, h, mode);
        return this;
    };

    // A pill with the label centred in it — used for the badge and the CTA.
    Sheet.prototype.pill = function (label, x, y, w, h, bg, fg, size, style, spacing) {
        this.box(x, y, w, h, h / 2, bg, null);
        this.font(style, size, fg);
        var baseline = y + h / 2 + size * PT * 0.36;
        var cx = x + w / 2;
        if (spacing) {
            // jsPDF pads the trailing character too, so centre on the real ink.
            this.d.setCharSpace(spacing);
            var wide = this.d.getTextWidth(label) + spacing * (String(label).length - 1);
            this.d.text(String(label), cx - wide / 2, baseline);
            this.d.setCharSpace(0);
        } else {
            this.d.text(String(label), cx, baseline, { align: 'center' });
        }
        return this;
    };

    Sheet.prototype.strike = function (str, x, y, size, colour) {
        this.text(str, x, y);
        this.rule(x, y - size * PT * 0.28, x + this.width(str), colour, 0.55);
        return this;
    };

    // The feature bullet. Plus Jakarta Sans has no ✓ glyph, and a substitute
    // font for one character would be a second embed — so it is drawn.
    Sheet.prototype.tick = function (x, y, size, colour) {
        var d = this.d, s = size * PT;
        d.setDrawColor(colour);
        d.setLineWidth(0.28);
        d.setLineCap('round');
        d.setLineJoin('round');
        d.lines([[s * 0.28, s * 0.3], [s * 0.55, -s * 0.62]], x, y - s * 0.3);
        d.setLineCap('butt');
        return this;
    };

    Sheet.prototype.link = function (x, y, w, h, url) {
        if (url) this.d.link(x, y, w, h, { url: url });
        return this;
    };

    /* ---------- the sheet ---------- */

    function render(doc, data, logo) {
        var s = new Sheet(doc);
        var y = M.top;

        y = header(s, data, logo, y);
        y = meta(s, data, y);
        y = plans(s, data, y);
        footer(s, data, y);
    }

    function header(s, data, logo, y) {
        var logoW = 42, logoH = logoW * 218 / 1024;   // the mark's own aspect
        var right = M.side + COL.w;

        var kick = 7, prod = 17, long = 7.5;
        var rightH = kick * PT * 1.3 + prod * PT * 1.1 + (data.productLong ? long * PT * 1.35 : 0);
        var base = y + Math.max(logoH, rightH);       // both blocks sit on one line

        s.d.addImage(logo, 'PNG', M.side, base - logoH, logoW, logoH, undefined, 'FAST');

        var ly = base - (data.productLong ? long * PT * 1.35 : 0) - prod * PT * 0.28;
        s.font(X, kick, C.cyan)
            .text('PLANS & PRICING', right, ly - prod * PT * 1.02, { align: 'right', spacing: kick * PT * 0.12 });
        s.font(X, prod, C.navy).text(data.product, right, ly, { align: 'right' });
        if (data.productLong) {
            s.font(R, long, C.mid).text(data.productLong, right, base - long * PT * 0.2, { align: 'right' });
        }

        var ruleY = base + 4;
        s.rule(M.side, ruleY, right, C.cyan, 2.5);
        return ruleY + 3.5;
    }

    function meta(s, data, y) {
        var size = 8, padY = 2.5, padX = 4;
        var h = size * PT * 1.35 + padY * 2;
        s.box(M.side, y, COL.w, h, 2, C.wash, null);

        var baseline = y + h / 2 + size * PT * 0.36;
        var x = M.side + padX;
        var items = [
            ['Team size ', data.users + (data.users === '1' ? ' user' : ' users')],
            ['Billing ', data.cycleLabel],
            ['Prepared ', data.today]
        ];
        items.forEach(function (it) {
            s.font(R, size, C.body).text(it[0], x, baseline);
            x += s.width(it[0]);
            s.font(B, size, C.navy).text(it[1], x, baseline);
            x += s.width(it[1]) + 6;
        });
        return y + h + 5;
    }

    /* The cards are measured before anything is drawn: every column is given the
       tallest card's height so the sign-up buttons land on one baseline, exactly
       as the flex layout does on screen. */
    function plans(s, data, y) {
        var n = data.plans.length;
        if (!n) return y;

        var gap = 4;
        var w = (COL.w - gap * (n - 1)) / n;
        var room = PAGE.h - M.bottom - y - footerHeight(s, data) - 6;

        var laid = data.plans.map(function (p) { return measure(s, p, w, data); });
        var natural = Math.max.apply(null, laid.map(function (l) { return l.height; }));

        // Long feature lists are trimmed rather than pushed onto a second page:
        // a quote that runs to one sheet is the point of the brochure.
        if (natural > room) {
            laid = data.plans.map(function (p) { return measure(s, p, w, data, room); });
            natural = Math.max.apply(null, laid.map(function (l) { return l.height; }));
        }
        var h = Math.min(natural, room);

        laid.forEach(function (l, i) {
            card(s, l, M.side + i * (w + gap), y, w, h);
        });
        return y + h + 6;
    }

    // Walks a card's content once to find its height, keeping the list of pieces
    // so the draw pass can lay them down without measuring twice.
    function measure(s, p, w, data, cap) {
        var pad = 4, inner = w - pad * 2;
        var h = pad;
        var rows = [];

        if (p.recommended) { rows.push({ t: 'badge' }); h += 4.2 + 1.5; }

        s.font(X, 12);
        rows.push({ t: 'name' }); h += 12 * PT * 1.15;

        if (p.was) { rows.push({ t: 'was' }); h += 8.5 * PT * 1.25; }

        var amount = data.currency + p.amount;
        var big = fit(s, amount, X, 19, inner - s.font(R, 7).width(' / user / month'));
        rows.push({ t: 'rate', size: big }); h += big * PT * 1.1 + 3;

        var payL = payLayout(s, p, data, inner);
        var perRow = sumRow(s, p.users + ' users × ' + data.currency + p.perUser + ' each',
                            data.currency + p.base + ' / month', inner, 7.5, 1.35);
        var gstRow = sumRow(s, '+ 18% GST', data.currency + p.withGst + ' in total', inner, 6.8, 1.4);
        rows.push({ t: 'sum', pay: payL, per: perRow, gst: gstRow });
        h += perRow.height + 2 + payL.height + gstRow.height;
        if (p.savings) { rows.push({ t: 'save' }); h += 2 + 7.5 * PT * 1.3; }

        var feats = p.features || [];
        var shown = feats.length;
        if (shown) {
            s.font(R, 7.5);
            var wraps = feats.map(function (f) { return s.d.splitTextToSize(f.text, inner - 4).length; });
            var listH = function (k) {
                var rules = k < feats.length ? 7 * PT * 1.35 + 1.4 : 0;    // the "+ N more" line
                return wraps.slice(0, k).reduce(function (a, b) { return a + b; }, 0) * 7.5 * PT * 1.35 + k * 1.4 + rules;
            };
            if (cap) {
                var spare = cap - (h + 3 + ctaHeight() + pad);
                while (shown > 0 && listH(shown) > spare) shown--;
            }
            h += 3 + listH(shown);
        }
        rows.push({ t: 'feats', shown: shown, total: feats.length });

        h += 3 + ctaHeight() + pad;
        return { plan: p, rows: rows, height: h, inner: inner, pad: pad, data: data };
    }

    function ctaHeight() { return 8 * PT * 1.1 + 4; }

    // A label and its value share one line until they cannot. Four plans leave a
    // ~44mm column, which is where "10 users × ₹12,499 each" and its total start
    // to meet in the middle — so the pair shrinks a little, then stacks.
    function sumRow(s, left, right, inner, start, lead) {
        var size = start;
        var over = function () { return s.font(R, size).width(left) + s.font(B, size).width(right) + 2 > inner; };
        while (size > start - 1.1 && over()) size -= 0.25;
        var stacked = over();
        return {
            left: left, right: right, size: size, stacked: stacked,
            height: (stacked ? 2 : 1) * size * PT * lead + 1.2
        };
    }

    // The plan name rides in the button, so a long one has to be reined in
    // before it runs past the card edge.
    function ctaLabel(s, name, inner) {
        var room = inner - 6, size = 8;
        var label = 'Sign up for ' + name + '  →';
        while (size > 6.5 && s.font(X, size).width(label) > room) size -= 0.25;
        if (s.font(X, size).width(label) > room) {
            label = 'Sign up  →';
            size = 8;
            while (size > 6 && s.font(X, size).width(label) > room) size -= 0.25;
        }
        return { label: label, size: size };
    }

    // Shared by the measure and draw passes so a wrapped "You pay once" label
    // reserves the same space it later occupies.
    function payLayout(s, p, data, inner) {
        var pay = data.currency + p.pay;
        var payW = s.font(X, 11).width(pay);
        s.font(X, 7.5);
        var lines = s.d.splitTextToSize('You pay once · ' + p.months + ' months', Math.max(inner - payW - 2, inner * 0.4));
        return { pay: pay, width: payW, lines: lines, height: (lines.length - 1) * 7.5 * PT * 1.2 + 11 * PT * 1.25 };
    }

    // Shrinks a headline until it fits its column — a four-plan grid leaves a
    // narrow card, and a wrapped price reads as a mistake.
    function fit(s, str, style, size, room) {
        while (size > 11 && s.font(style, size).width(str) > room) size -= 0.5;
        return size;
    }

    function card(s, l, x, y, w, h) {
        var p = l.plan, data = l.data, pad = l.pad, inner = l.inner;
        var rec = p.recommended;

        s.box(x, y, w, h, 2.5, C.white, rec ? C.cyan : C.line, rec ? 1.4 : 0.6);

        var cx = x + pad, cy = y + pad;

        if (rec) {
            var label = 'RECOMMENDED FOR YOU', size = 6.5, sp = size * PT * 0.06;
            var pw = s.font(X, size).width(label) + sp * label.length + 5;
            s.pill(label, cx, cy, Math.min(pw, inner), 4.2, C.cyan, C.white, size, X, sp);
            cy += 4.2 + 1.5;
        }

        s.font(X, 12, C.navy).text(p.name, cx, cy + 12 * PT * 0.86);
        cy += 12 * PT * 1.15;

        if (p.was) {
            s.font(R, 8.5, C.faint).strike(p.was, cx, cy + 8.5 * PT * 0.86, 8.5, C.faint);
            cy += 8.5 * PT * 1.25;
        }

        var rate = l.rows.filter(function (r) { return r.t === 'rate'; })[0];
        var amount = data.currency + p.amount;
        var rateBase = cy + rate.size * PT * 0.82;
        s.font(X, rate.size, C.navy).text(amount, cx, rateBase);
        var aw = s.width(amount);
        s.font(R, 7, C.mid).text(' / user / month', cx + aw, rateBase);
        cy += rate.size * PT * 1.1 + 3;

        // Summary — the visitor's own arithmetic, right-aligned like the card.
        var rx = x + w - pad;
        var t = 7.5;
        var sum = l.rows.filter(function (r) { return r.t === 'sum'; })[0];

        var pair = function (row, leftColour, rightColour, leftStyle, rightStyle, lead) {
            var base = cy + row.size * PT * 0.86;
            s.font(leftStyle, row.size, leftColour).text(row.left, cx, base);
            if (row.stacked) base += row.size * PT * lead;
            s.font(rightStyle, row.size, rightColour).text(row.right, rx, base, { align: 'right' });
            cy += row.height;
        };

        pair(sum.per, C.body, C.navy, R, B, 1.35);

        s.rule(cx, cy, rx, C.dash, 0.5, true);
        cy += 2;

        // The total is the number the visitor came for, so it keeps the first
        // baseline and the label wraps underneath it rather than pushing it down.
        var payL = sum.pay;
        var payBase = cy + 11 * PT * 0.82;
        s.font(X, 11, C.green).text(payL.pay, rx, payBase, { align: 'right' });
        s.font(X, t, C.green);
        payL.lines.forEach(function (ln, i) { s.text(ln, cx, payBase + i * t * PT * 1.2); });
        cy += payL.height;

        pair(sum.gst, C.soft, C.soft, R, R, 1.4);

        if (p.savings) {
            cy += 2;
            s.font(X, t, C.mint).text('You save ' + data.currency + p.savings + ' a year', cx, cy + t * PT * 0.86);
            cy += t * PT * 1.3;
        }

        var feats = l.rows.filter(function (r) { return r.t === 'feats'; })[0];
        if (feats && feats.shown) {
            cy += 3;
            s.rule(cx, cy - 1.5, rx, C.hair, 0.5);
            var f = 7.5;
            (p.features || []).slice(0, feats.shown).forEach(function (item) {
                s.font(item.hi ? X : R, f, item.hi ? C.navy : C.slate);
                var lines = s.d.splitTextToSize(item.text, inner - 4);
                lines.forEach(function (ln, i) {
                    var base = cy + f * PT * 0.86 + i * f * PT * 1.35;
                    if (!i) s.tick(cx, base, f, C.cyan);
                    s.text(ln, cx + 4, base);
                });
                cy += lines.length * f * PT * 1.35 + 1.4;
            });
            if (feats.shown < feats.total) {
                s.font(R, 7, C.soft).text('+ ' + (feats.total - feats.shown) + ' more', cx + 4, cy + 7 * PT * 0.86);
                cy += 7 * PT * 1.35 + 1.4;
            }
        }

        // The call to action is pinned to the card's foot, not to the content.
        var ch = ctaHeight(), cyBtn = y + h - pad - ch;
        var cta = ctaLabel(s, p.name, inner);
        s.pill(cta.label, cx, cyBtn, inner, ch, C.navy, C.white, cta.size, X);
        s.link(cx, cyBtn, inner, ch, p.signup);
    }

    function footerLinks(data) {
        var host = String(data.site || '').replace(/^https?:\/\//, '');
        var out = [{ label: host + '/pricing', url: data.site + '/pricing' }];
        if (data.phone) out.push({ label: data.phone, url: 'tel:' + data.phone.replace(/\s+/g, '') });
        if (data.email) out.push({ label: data.email, url: 'mailto:' + data.email });
        if (data.wa) out.push({ label: 'WhatsApp us', url: data.wa });
        return out;
    }

    function footerNote(s, data) {
        var host = String(data.site || '').replace(/^https?:\/\//, '');
        s.font(R, 6.8);
        return s.d.splitTextToSize(
            'Every figure above is quoted for ' + data.users + ' users on ' + data.cycleLower +
            ' billing. Amounts are exclusive of 18% GST unless stated. Prices are live from ' +
            host + ' on ' + data.today + ' and may change — open the link above for the current rate.',
            COL.w
        );
    }

    function footerHeight(s, data) {
        return 3 + 8 * PT * 1.35 + 2.5 + footerNote(s, data).length * 6.8 * PT * 1.5;
    }

    function footer(s, data, y) {
        s.rule(M.side, y, M.side + COL.w, C.line, 0.6);
        y += 3;

        var size = 8, base = y + size * PT * 0.86, x = M.side;
        s.font(B, size, C.link);
        footerLinks(data).forEach(function (it) {
            var w = s.width(it.label);
            s.text(it.label, x, base);
            s.link(x, base - size * PT * 0.86, w, size * PT * 1.2, it.url);
            x += w + 5;
        });
        y += size * PT * 1.35 + 2.5;

        var note = 6.8;
        s.font(R, note, C.soft);
        footerNote(s, data).forEach(function (ln, i) {
            s.text(ln, M.side, y + note * PT * 0.86 + i * note * PT * 1.5);
        });
    }

    /* ---------- public entry point ---------- */

    function fileName(data) {
        var slug = String(data.product || 'Pricing').replace(/[^\w]+/g, '-').replace(/^-|-$/g, '');
        var d = new Date();
        var stamp = d.getFullYear() + '-' + ('0' + (d.getMonth() + 1)).slice(-2) + '-' + ('0' + d.getDate()).slice(-2);
        return 'HealthO-Pro-' + slug + '-Pricing-' + stamp + '.pdf';
    }

    function build(data) {
        return load().then(function (a) {
            var doc = new a.jsPDF({ unit: 'mm', format: 'a4', orientation: 'portrait', compress: true });

            doc.addFileToVFS('PJS-Regular.ttf', a.fonts.normal);
            doc.addFont('PJS-Regular.ttf', FONT, R);
            doc.addFileToVFS('PJS-Bold.ttf', a.fonts.bold);
            doc.addFont('PJS-Bold.ttf', FONT, B);
            doc.addFileToVFS('PJS-ExtraBold.ttf', a.fonts.extrabold);
            doc.addFont('PJS-ExtraBold.ttf', FONT, X);

            doc.setProperties({
                title: 'HealthO Pro ' + data.product + ' — Plans & Pricing',
                subject: 'Quoted for ' + data.users + ' users on ' + data.cycleLower + ' billing',
                author: 'HealthO Pro',
                creator: 'healtho.pro'
            });

            render(doc, data, a.logo);
            return doc;
        });
    }

    root.HealthOBrochure = {
        download: function (data) {
            return build(data).then(function (doc) { doc.save(fileName(data)); });
        },
        // Handy from the console when tuning the layout: returns a blob URL.
        preview: function (data) {
            return build(data).then(function (doc) { return doc.output('bloburl'); });
        }
    };
}(window));
