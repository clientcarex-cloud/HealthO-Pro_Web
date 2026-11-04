/* ==========================================================================
   MESSAGING CREDITS PRICING — vector PDF
   The healtho.pro pricing brochure (Websites/HealthO Pro_Web/js/brochure.js),
   adapted for the Recharge Credits plans: same A4 sheet, Plus Jakarta Sans,
   cyan/navy palette and card anatomy, but one section per channel with the
   plans laid out three to a row and flowing onto further pages as needed.

   Two identical copies, keep them in step:
     - CRM:     modules/sms_wa_email/assets/pricing_pdf/credits_brochure.js
                (caller: views/manage.php → downloadPricingPdf; assets sit
                next to the file)
     - Website: js/credits-brochure.js (caller: add-on-services.php; assets
                passed as { jspdf, fonts, logo } URLs)
   The caller hands over plain, already-formatted data; this file owns the
   geometry. Measurements are millimetres on A4, type sizes are points.
   ========================================================================== */
(function (root) {
    'use strict';

    var PT = 25.4 / 72;             // points → mm
    var PAGE = { w: 210, h: 297 };
    var M = { top: 11, side: 10, bottom: 12 };
    var COL = { x: M.side, w: PAGE.w - M.side * 2 };
    var COLS = 3, GAP = 4;
    var PAGE_FOOT = 5;              // room kept for the "Page x of y" line

    var C = {
        cyan: '#00B4D8', navy: '#0B2545', ink: '#0F172A',
        body: '#475569', mid: '#64748B', soft: '#8092A8', faint: '#94A3B8',
        line: '#DBE3EC', hair: '#E6ECF3', dash: '#C7D3E0',
        wash: '#F1F8FC', green: '#0F7A66', mint: '#10B981',
        link: '#0096B7', slate: '#334155', white: '#FFFFFF',
        tint: '#FFF7ED', tintLine: '#FDBA74', orange: '#C2410C'
    };

    var FONT = 'PJS';
    var R = 'normal', B = 'bold', X = 'extrabold';

    /* ---------- one-time asset loading ---------- */

    var assets = null;
    // `base` is either one folder holding all three files (the CRM copy) or
    // { jspdf, fonts, logo } with a URL each (the website keeps them apart).
    function load(base) {
        if (assets) return assets;
        var src = typeof base === 'object' && base ? base : {
            jspdf: base + 'jspdf.umd.min.js',
            fonts: base + 'pjs-fonts.js',
            logo: base + 'healtho-pro-logo.png'
        };
        assets = Promise.all([
            need(src.jspdf, function () { return root.jspdf && root.jspdf.jsPDF; }),
            need(src.fonts, function () { return root.HealthOBrochureFonts; }),
            dataUrl(src.logo)
        ]).then(function (out) {
            return { jsPDF: root.jspdf.jsPDF, fonts: root.HealthOBrochureFonts, logo: out[2] };
        }).catch(function (err) {
            assets = null;                       // let a later click try again
            throw err;
        });
        return assets;
    }

    // The admin may carry an AMD loader; a UMD bundle would then register with
    // it instead of setting window.jspdf, so hide define() while it loads.
    function need(src, test) {
        if (test()) return Promise.resolve();
        return new Promise(function (resolve, reject) {
            var amd = root.define;
            root.define = undefined;
            var done = function () { root.define = amd; };
            var s = document.createElement('script');
            s.src = src;
            s.onload = function () { done(); test() ? resolve() : reject(new Error(src + ' loaded empty')); };
            s.onerror = function () { done(); reject(new Error(src + ' failed')); };
            document.head.appendChild(s);
        });
    }

    function dataUrl(src) {
        return fetch(src).then(function (r) {
            if (!r.ok) throw new Error(src + ' ' + r.status);
            return r.blob();
        }).then(function (b) {
            return new Promise(function (resolve, reject) {
                var fr = new FileReader();
                fr.onload = function () { resolve(fr.result); };
                fr.onerror = reject;
                fr.readAsDataURL(b);
            });
        });
    }

    /* ---------- drawing helpers ---------- */

    // With `dry` set, every drawing call is skipped but fonts and widths still
    // work — that is how a card is measured with the same code that draws it.
    function Sheet(doc) {
        this.d = doc;
        this.dry = false;
    }

    Sheet.prototype.font = function (style, size, colour) {
        this.d.setFont(FONT, style);
        this.d.setFontSize(size);
        if (colour) this.d.setTextColor(colour);
        return this;
    };

    // y is the text baseline.
    Sheet.prototype.text = function (str, x, y, opts) {
        if (this.dry) return this;
        opts = opts || {};
        if (opts.spacing) this.d.setCharSpace(opts.spacing);
        this.d.text(String(str), x, y, opts.align ? { align: opts.align } : undefined);
        if (opts.spacing) this.d.setCharSpace(0);
        return this;
    };

    Sheet.prototype.width = function (str) { return this.d.getTextWidth(String(str)); };

    Sheet.prototype.rule = function (x1, y, x2, colour, pt, dashed) {
        if (this.dry) return this;
        this.d.setDrawColor(colour);
        this.d.setLineWidth(pt * PT);
        if (dashed) this.d.setLineDashPattern([0.7, 0.7], 0);
        this.d.line(x1, y, x2, y);
        if (dashed) this.d.setLineDashPattern([], 0);
        return this;
    };

    Sheet.prototype.box = function (x, y, w, h, r, fill, stroke, pt) {
        if (this.dry) return this;
        var d = this.d;
        if (fill) d.setFillColor(fill);
        if (stroke) { d.setDrawColor(stroke); d.setLineWidth((pt || 0.6) * PT); }
        var mode = fill && stroke ? 'FD' : (fill ? 'F' : 'S');
        r ? d.roundedRect(x, y, w, h, r, r, mode) : d.rect(x, y, w, h, mode);
        return this;
    };

    Sheet.prototype.pill = function (label, x, y, w, h, bg, fg, size, style, spacing) {
        if (this.dry) return this;
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

    // Plus Jakarta Sans has no ✓ glyph, so the bullet is drawn.
    Sheet.prototype.tick = function (x, y, size, colour) {
        if (this.dry) return this;
        var d = this.d, s = size * PT;
        d.setDrawColor(colour);
        d.setLineWidth(0.28);
        d.setLineCap('round');
        d.setLineJoin('round');
        d.lines([[s * 0.28, s * 0.3], [s * 0.55, -s * 0.62]], x, y - s * 0.3);
        d.setLineCap('butt');
        return this;
    };

    Sheet.prototype.dot = function (x, y, r, colour) {
        if (this.dry) return this;
        this.d.setFillColor(colour);
        this.d.circle(x, y, r, 'F');
        return this;
    };

    Sheet.prototype.link = function (x, y, w, h, url) {
        if (!this.dry && url) this.d.link(x, y, w, h, { url: url });
        return this;
    };

    /* ---------- the sheet ---------- */

    function render(doc, data, logo) {
        var s = new Sheet(doc);
        var y = M.top;

        y = header(s, data, logo, y);
        y = meta(s, data, y);
        if (data.guide) y = guide(s, data.guide, y);
        y = sections(s, data, y);

        var fh = footerHeight(s, data);
        if (y + fh > PAGE.h - M.bottom - PAGE_FOOT) { doc.addPage(); y = M.top; }
        footer(s, data, y + 1);

        pageNumbers(s, data);
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
        var limit = M.side + COL.w - padX;
        (data.meta || []).forEach(function (it) {
            var value = String(it[1]);
            var lw = s.font(R, size).width(it[0]);
            // A long account name is clipped rather than run past the strip.
            while (value.length > 4 && x + lw + s.font(B, size).width(value) > limit) {
                value = value.slice(0, -2).replace(/\s+$/, '') + '…';
            }
            s.font(R, size, C.body).text(it[0], x, baseline);
            x += lw;
            s.font(B, size, C.navy).text(value, x, baseline);
            x += s.width(value) + 6;
        });
        return y + h + 6;
    }

    function lines(s, str, style, size, w) {
        s.font(style, size);
        return s.d.splitTextToSize(String(str || ''), w);
    }

    /* "How to choose" box under the meta strip: three numbered steps, then the
       two message types side by side in plain words. Measured dry first. */
    function guide(s, g, y) {
        var dry = s.dry;
        s.dry = true;
        var h = guideBody(s, g, 0);
        s.dry = dry;
        s.box(M.side, y, COL.w, h, 2.5, C.wash, null);
        guideBody(s, g, y);
        return y + h + 6;
    }

    function guideBody(s, g, y) {
        var pad = 4, x0 = M.side + pad, inner = COL.w - pad * 2;
        var cy = y + pad;

        s.font(X, 10.5, C.navy).text(g.title, x0, cy + 10.5 * PT * 0.86);
        cy += 10.5 * PT * 1.25 + 2;

        // Steps
        var steps = g.steps || [], sg = 4;
        var sw = (inner - sg * (steps.length - 1)) / steps.length;
        var stepH = 0;
        steps.forEach(function (st, i) {
            var sx = x0 + i * (sw + sg), r = 2.4;
            s.dot(sx + r, cy + r, r, C.cyan);
            s.font(X, 7.5, C.white).text(String(i + 1), sx + r, cy + r + 7.5 * PT * 0.34, { align: 'center' });
            var tx = sx + r * 2 + 2, tw = sw - r * 2 - 2;
            var tl = lines(s, st.title, X, 8.5, tw);
            s.font(X, 8.5, C.navy);
            tl.forEach(function (ln, j) { s.text(ln, tx, cy + 8.5 * PT * 0.86 + j * 8.5 * PT * 1.2); });
            var ty = cy + tl.length * 8.5 * PT * 1.2 + 0.6;
            var dl = lines(s, st.text, R, 7, tw);
            s.font(R, 7, C.body);
            dl.forEach(function (ln, j) { s.text(ln, tx, ty + 7 * PT * 0.86 + j * 7 * PT * 1.35); });
            stepH = Math.max(stepH, ty + dl.length * 7 * PT * 1.35 - cy);
        });
        cy += stepH + 4;

        // Message types
        var types = g.types || [];
        if (types.length) {
            var tg = 4, tw2 = (inner - tg * (types.length - 1)) / types.length;
            var boxH = Math.max.apply(null, types.map(function (t) { return typeBox(s, t, 0, 0, tw2, true); }));
            types.forEach(function (t, i) { typeBox(s, t, x0 + i * (tw2 + tg), cy, tw2, false, boxH); });
            cy += boxH;
        }
        return cy + pad - y;
    }

    function typeBox(s, t, x, y, w, measure, h) {
        var dry = s.dry;
        if (measure) s.dry = true;
        var pad = 3.5, inner = w - pad * 2 - 1.5, cx = x + pad + 1.5, cy = y + pad;
        if (!measure) {
            s.box(x, y, w, h, 2, C.white, C.line, 0.6);
            s.box(x, y, 1.5, h, 0, t.colour, null);
        }
        var ts = 6.8, sp = ts * PT * 0.08;
        var pw = s.font(X, ts).width(t.tag) + sp * t.tag.length + 5;
        s.pill(t.tag, cx, cy, pw, 4.2, t.colour, C.white, ts, X, sp);
        s.font(X, 8.5, C.navy).text(t.title, cx + pw + 2.5, cy + 4.2 / 2 + 8.5 * PT * 0.34);
        cy += 4.2 + 2;
        (t.lines || []).forEach(function (item) {
            var ll = lines(s, item.text, item.bold ? B : R, 7.2, inner - 4);
            s.font(item.bold ? B : R, 7.2, item.bold ? C.navy : C.slate);
            ll.forEach(function (ln, j) {
                var base = cy + 7.2 * PT * 0.86 + j * 7.2 * PT * 1.35;
                if (!j) s.tick(cx, base, 7.2, t.colour);
                s.text(ln, cx + 4, base);
            });
            cy += ll.length * 7.2 * PT * 1.35 + 1;
        });
        var out = cy + pad - 1 - y;
        s.dry = dry;
        return out;
    }

    /* A channel's plans split by message type: a coloured tag and a one-line
       meaning above that type's cards. */
    function partHeight(s, part) {
        var dry = s.dry; s.dry = true;
        var h = partHead(s, part, 0);
        s.dry = dry;
        return h;
    }

    function partHead(s, part, y) {
        var ts = 6.8, sp = ts * PT * 0.08, ph = 4.4;
        var pw = s.font(X, ts).width(part.tag) + sp * part.tag.length + 5;
        s.pill(part.tag, M.side, y, pw, ph, part.colour, C.white, ts, X, sp);
        var dx = M.side + pw + 2.5;
        var dl = lines(s, part.desc, R, 7.5, M.side + COL.w - dx);
        s.font(R, 7.5, C.body);
        dl.forEach(function (ln, j) { s.text(ln, dx, y + ph / 2 + 7.5 * PT * 0.34 + j * 7.5 * PT * 1.3); });
        var h = Math.max(ph, ph / 2 + 7.5 * PT * 0.5 + (dl.length - 1) * 7.5 * PT * 1.3 + 7.5 * PT * 0.3);
        return h + 2.5;
    }

    /* One section per channel. Each row of cards is measured first so the
       whole row shares the tallest card's height (the buttons line up), and a
       row that would cross the page foot moves to a fresh page — together
       with its section heading and type heading when it opens them. */
    function sections(s, data, y) {
        var w = (COL.w - GAP * (COLS - 1)) / COLS;
        var bottom = PAGE.h - M.bottom - PAGE_FOOT;

        (data.groups || []).forEach(function (g, gi) {
            var parts = g.parts && g.parts.length ? g.parts : [{ plans: g.plans }];
            var before = g.setup ? 6 : 3;
            parts.forEach(function (part, pi) {
                for (var r = 0; r * COLS < part.plans.length; r++) {
                    var row = part.plans.slice(r * COLS, r * COLS + COLS);
                    var h = Math.max.apply(null, row.map(function (p) { return card(s, p, 0, 0, w); }));
                    var first = r === 0 && pi === 0;
                    var lead = (first ? (gi ? before : 0) + sectionHeight() : 0)
                        + (r === 0 && part.tag ? (pi ? 2 : 0) + partHeight(s, part) : 0);

                    if (y + lead + h > bottom && y > M.top) { s.d.addPage(); y = M.top; }
                    if (first) {
                        if (gi && y > M.top) y += before;
                        y = section(s, g, y);
                    }
                    if (r === 0 && part.tag) {
                        if (pi && y > M.top) y += 2;
                        y += partHead(s, part, y);
                    }
                    row.forEach(function (p, i) { card(s, p, M.side + i * (w + GAP), y, w, h); });
                    y += h + GAP;
                }
            });
        });
        return y + 2;
    }

    function sectionHeight() { return 12 * PT * 1.15 + 5; }

    function section(s, g, y) {
        var size = 12, base = y + size * PT * 0.86;
        s.dot(M.side + 1.6, base - size * PT * 0.32, 1.6, g.colour || C.cyan);
        s.font(X, size, C.navy).text(g.label, M.side + 5, base);
        if (g.count) s.font(R, 8, C.mid).text(g.count, M.side + COL.w, base, { align: 'right' });
        var ry = y + size * PT * 1.15 + 1.5;
        s.rule(M.side, ry, M.side + COL.w, C.hair, 0.6);
        return ry + 3.5;
    }

    // Draws one card, or — with h omitted — only measures it and returns the
    // height it needs. The CTA is pinned to the card's foot, so cards in a row
    // that share a height keep their buttons on one line.
    function card(s, p, x, y, w, h) {
        var dry = h === undefined;
        s.dry = dry;

        var pad = 4, inner = w - pad * 2;
        var cx = x + pad, rx = x + w - pad, cy = y + pad;
        var best = !!p.best;

        s.box(x, y, w, h || 0, 2.5, p.tint ? C.tint : C.white, best ? C.cyan : (p.tint ? C.tintLine : C.line), best ? 1.4 : 0.6);

        if (best) {
            var label = 'BEST VALUE', bs = 6.5, sp = bs * PT * 0.06;
            var pw = s.font(X, bs).width(label) + sp * label.length + 5;
            s.pill(label, cx, cy, Math.min(pw, inner), 4.2, C.cyan, C.white, bs, X, sp);
            cy += 4.2 + 1.5;
        }

        if (p.kicker) {
            var ks = 6.5;
            s.font(X, ks, p.tint ? C.orange : C.cyan).text(String(p.kicker).toUpperCase(), cx, cy + ks * PT * 0.86, { spacing: ks * PT * 0.08 });
            cy += ks * PT * 1.45;
        }

        s.font(X, 12, C.navy);
        var nameLines = s.d.splitTextToSize(String(p.name), inner);
        nameLines.forEach(function (ln, i) { s.text(ln, cx, cy + 12 * PT * 0.86 + i * 12 * PT * 1.15); });
        cy += nameLines.length * 12 * PT * 1.15;

        if (p.was) {
            s.font(R, 8.5, C.faint).strike(p.was, cx, cy + 8.5 * PT * 0.86, 8.5, C.faint);
            cy += 8.5 * PT * 1.25;
        }

        var unit = p.unit ? ' ' + p.unit : '';
        var size = fit(s, p.amount, X, 19, inner - s.font(R, 7).width(unit));
        var rateBase = cy + size * PT * 0.82;
        s.font(X, size, C.navy).text(p.amount, cx, rateBase);
        var aw = s.width(p.amount);
        s.font(R, 7, C.mid).text(unit, cx + aw, rateBase);
        cy += size * PT * 1.1 + 3;

        cy = pair(s, p.sumLeft, p.sumRight, cx, rx, cy, inner, 7.5, C.body, C.navy, R, B, 1.35);

        s.rule(cx, cy, rx, C.dash, 0.5, true);
        cy += 2;

        // The amount payable keeps the first baseline; its label wraps beneath.
        s.font(X, 11);
        var payW = s.width(p.pay);
        s.font(X, 7.5);
        var payLines = s.d.splitTextToSize(String(p.payLabel), Math.max(inner - payW - 2, inner * 0.4));
        var payBase = cy + 11 * PT * 0.82;
        s.font(X, 11, C.green).text(p.pay, rx, payBase, { align: 'right' });
        s.font(X, 7.5, C.green);
        payLines.forEach(function (ln, i) { s.text(ln, cx, payBase + i * 7.5 * PT * 1.2); });
        cy += (payLines.length - 1) * 7.5 * PT * 1.2 + 11 * PT * 1.25;

        if (p.taxLeft) {
            cy = pair(s, p.taxLeft, p.taxRight, cx, rx, cy, inner, 6.8, C.soft, C.soft, R, R, 1.4);
        }

        if (p.savings) {
            cy += 2;
            s.font(X, 7.5, C.mint).text(p.savings, cx, cy + 7.5 * PT * 0.86);
            cy += 7.5 * PT * 1.3;
        }

        var feats = p.features || [];
        if (feats.length) {
            cy += 3;
            s.rule(cx, cy - 1.5, rx, C.hair, 0.5);
            var f = 7.5;
            feats.forEach(function (item) {
                s.font(item.hi ? X : R, f, item.hi ? C.navy : C.slate);
                var lines = s.d.splitTextToSize(String(item.text), inner - 4);
                lines.forEach(function (ln, i) {
                    var base = cy + f * PT * 0.86 + i * f * PT * 1.35;
                    if (!i) s.tick(cx, base, f, C.cyan);
                    s.text(ln, cx + 4, base);
                });
                cy += lines.length * f * PT * 1.35 + 1.4;
            });
        }

        var ch = ctaHeight();
        if (p.cta) {
            cy += 3;
            if (!dry) {
                var by = y + h - pad - ch;
                var cs = 8;
                while (cs > 6.5 && s.font(X, cs).width(p.cta) > inner - 6) cs -= 0.25;
                s.pill(p.cta, cx, by, inner, ch, C.navy, C.white, cs, X);
                s.link(cx, by, inner, ch, p.link);
            }
            cy += ch;
        }
        cy += pad;

        s.dry = false;
        return cy - y;
    }

    function ctaHeight() { return 8 * PT * 1.1 + 4; }

    // A label and its value share one line until they cannot: the pair shrinks
    // a little, then stacks. Returns the y below the row.
    function pair(s, left, right, cx, rx, cy, inner, start, lc, rc, ls, rs, lead) {
        left = String(left || '');
        right = String(right || '');
        var size = start;
        var over = function () { return s.font(ls, size).width(left) + s.font(rs, size).width(right) + 2 > inner; };
        while (size > start - 1.1 && over()) size -= 0.25;
        var stacked = over();
        var base = cy + size * PT * 0.86;
        s.font(ls, size, lc).text(left, cx, base);
        if (stacked) base += size * PT * lead;
        s.font(rs, size, rc).text(right, rx, base, { align: 'right' });
        return cy + (stacked ? 2 : 1) * size * PT * lead + 1.2;
    }

    // Shrinks a headline until it fits its column — a wrapped price reads as a mistake.
    function fit(s, str, style, size, room) {
        while (size > 11 && s.font(style, size).width(str) > room) size -= 0.5;
        return size;
    }

    function footerLinks(data) {
        var host = String(data.site || '').replace(/^https?:\/\//, '');
        var out = [];
        if (host) out.push({ label: host, url: data.site });
        if (data.phone) out.push({ label: data.phone, url: 'tel:' + data.phone.replace(/\s+/g, '') });
        if (data.email) out.push({ label: data.email, url: 'mailto:' + data.email });
        if (data.wa) out.push({ label: 'WhatsApp us', url: data.wa });
        return out;
    }

    function footerNote(s, data) {
        s.font(R, 6.8);
        return s.d.splitTextToSize(String(data.note || ''), COL.w);
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

    // A quiet running line on every page, so a printed sheet that comes loose
    // still says what it is and when it was prepared.
    function pageNumbers(s, data) {
        var n = s.d.getNumberOfPages();
        var base = PAGE.h - M.bottom + 4;
        for (var i = 1; i <= n; i++) {
            s.d.setPage(i);
            s.font(R, 6.5, C.faint);
            s.text('HealthO Pro  ·  ' + data.product + ' pricing  ·  Prepared ' + data.prepared, M.side, base);
            s.text('Page ' + i + ' of ' + n, M.side + COL.w, base, { align: 'right' });
        }
    }

    /* ---------- public entry point ---------- */

    function fileName(data) {
        var slug = String(data.product || 'Pricing').replace(/[^\w]+/g, '-').replace(/^-|-$/g, '');
        var d = new Date();
        var two = function (v) { return ('0' + v).slice(-2); };
        var stamp = d.getFullYear() + '-' + two(d.getMonth() + 1) + '-' + two(d.getDate()) + '-' + two(d.getHours()) + two(d.getMinutes());
        return 'HealthO-Pro-' + slug + '-Pricing-' + stamp + '.pdf';
    }

    function build(data) {
        return load(data.assets).then(function (a) {
            var doc = new a.jsPDF({ unit: 'mm', format: 'a4', orientation: 'portrait', compress: true });

            doc.addFileToVFS('PJS-Regular.ttf', a.fonts.normal);
            doc.addFont('PJS-Regular.ttf', FONT, R);
            doc.addFileToVFS('PJS-Bold.ttf', a.fonts.bold);
            doc.addFont('PJS-Bold.ttf', FONT, B);
            doc.addFileToVFS('PJS-ExtraBold.ttf', a.fonts.extrabold);
            doc.addFont('PJS-ExtraBold.ttf', FONT, X);

            doc.setProperties({
                title: 'HealthO Pro ' + data.product + ' — Plans & Pricing',
                subject: 'Prepared ' + data.prepared,
                author: 'HealthO Pro',
                creator: 'healtho.pro'
            });

            render(doc, data, a.logo);
            return doc;
        });
    }

    root.HealthOCreditsBrochure = {
        download: function (data) {
            return build(data).then(function (doc) { doc.save(fileName(data)); });
        },
        // Handy from the console when tuning the layout: returns a blob URL.
        preview: function (data) {
            return build(data).then(function (doc) { return doc.output('bloburl'); });
        }
    };
}(window));
