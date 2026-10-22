"""
Cover banners for the city guides (/blog/{product}-{city}).

Builds assets/images/blog/{slug}.webp (1200x672), {slug}-sm.webp (640 wide)
and {slug}.jpg (for og:image) in the same style as the feature-guide covers:
logo, product pill, "Best ... in {City}", brand underline, a city pin badge,
and an illustration borrowed from a related feature cover.

Run from the site root after adding a city to partials/city-data.php:
    python3 .tools/city-covers.py            # only missing covers
    python3 .tools/city-covers.py --all      # rebuild every cover
    python3 .tools/city-covers.py hospital-management-software-pune   # one cover
Needs Pillow (pip install pillow) and PHP on the PATH.
"""
import json
import os
import subprocess
import sys

from PIL import Image, ImageDraw, ImageFilter, ImageFont

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
BLOG = os.path.join(ROOT, 'assets', 'images', 'blog')
FONT = os.path.join(ROOT, '.tools', 'PlusJakartaSans.ttf')
LOGO = os.path.join(ROOT, 'assets', 'images', 'logo.png')

W, H = 1200, 672
BG = (244, 247, 252)
NAVY = (18, 43, 92)
INK = (15, 27, 51)
CYAN = (0, 180, 216)
CYAN_DARK = (0, 150, 183)
GREEN = (46, 204, 113)
TEXT = (71, 85, 105)

# Illustrations per product, taken from related feature covers; cities rotate through them.
ART = {
    'hims': ['nursing-station', 'rooms', 'surgeries', 'medical-records', 'dashboard', 'mis-reports'],
    'lims': ['lis-integration', 'phlebotomist', 'home-sample-management', 'auto-ai-driven-qc', 'tests-master', 'referral-lab'],
    'cims': ['appointment-booking', 'prescription', 'token-system', 'follow-ups-for-appointments', 'patient-app', 'case-taking'],
    'ris':  ['pndt-form', 'transcriptor', 'dr-authorization', 'tats', 'medical-records'],
}
PILL = {
    'hims': 'HOSPITAL MANAGEMENT · HIMS',
    'lims': 'PATHOLOGY LAB · LIMS',
    'cims': 'CLINIC MANAGEMENT · CIMS',
    'ris':  'ULTRASOUND & RADIOLOGY · RIS',
}
TAGLINE = {
    'hims': 'OPD, IPD, wards, pharmacy, lab and TPA billing in one cloud system.',
    'lims': 'Analyzer interfacing, QC, home collection and reports on WhatsApp.',
    'cims': 'Online booking, tokens, e-prescriptions and WhatsApp reminders.',
    'ris':  'Measurements from your ultrasound machine straight into the report.',
}


def font(size, weight='Bold'):
    f = ImageFont.truetype(FONT, size)
    f.set_variation_by_name(weight)
    return f


def wrap(draw, words, f, width):
    lines, line = [], ''
    for w in words:
        test = (line + ' ' + w).strip()
        if draw.textlength(test, font=f) <= width or not line:
            line = test
        else:
            lines.append(line)
            line = w
    if line:
        lines.append(line)
    return lines


def pages():
    php = (
        "require 'partials/city-data.php';"
        "$o=[];foreach(city_pages() as $s=>$x){$c=CITIES[$x['city']];"
        "$o[]=['slug'=>$s,'product'=>$x['product'],'city'=>$x['city'],'name'=>$c['name'],'state'=>$c['state'],"
        "'keyword'=>CITY_PRODUCTS[$x['product']]['keyword'],'i'=>array_search($x['city'],array_keys(CITIES))];}"
        "echo json_encode($o);"
    )
    return json.loads(subprocess.check_output(['php', '-r', php], cwd=ROOT))


def pin_badge(canvas, x, y, label):
    """White pill with a map pin and the city — sits over the illustration."""
    d = ImageDraw.Draw(canvas)
    f = font(26, 'Bold')
    tw = d.textlength(label, font=f)
    w, h = int(tw) + 92, 62
    # Soft shadow
    shadow = Image.new('RGBA', (w + 40, h + 40), (0, 0, 0, 0))
    ImageDraw.Draw(shadow).rounded_rectangle((20, 26, w + 20, h + 26), radius=h // 2, fill=(18, 43, 92, 70))
    canvas.alpha_composite(shadow.filter(ImageFilter.GaussianBlur(10)), (x - 20, y - 20))
    d.rounded_rectangle((x, y, x + w, y + h), radius=h // 2, fill=(255, 255, 255, 255))
    # Pin: circle head + point, brand green with a white dot
    cx, cy = x + 40, y + 27
    d.polygon([(cx - 12, cy + 4), (cx + 12, cy + 4), (cx, cy + 24)], fill=GREEN)
    d.ellipse((cx - 14, cy - 14, cx + 14, cy + 14), fill=GREEN)
    d.ellipse((cx - 5, cy - 5, cx + 5, cy + 5), fill=(255, 255, 255))
    d.text((x + 66, y + h // 2), label, font=f, fill=NAVY, anchor='lm')


def cover(p):
    canvas = Image.new('RGBA', (W, H), BG + (255,))

    # Illustration: right side of a related feature cover, faded in from the left.
    art_slug = ART[p['product']][p['i'] % len(ART[p['product']])]
    art = Image.open(os.path.join(BLOG, art_slug + '.jpg')).convert('RGBA').resize((W, H))
    mask = Image.new('L', (W, H), 0)
    md = ImageDraw.Draw(mask)
    start, end = 540, 780
    for xx in range(start, W):
        md.line([(xx, 0), (xx, H)], fill=min(255, int(255 * (xx - start) / (end - start))))
    canvas.paste(art, (0, 0), mask)

    d = ImageDraw.Draw(canvas)
    left = 56

    # Logo
    logo = Image.open(LOGO).convert('RGBA')
    lw = 250
    logo = logo.resize((lw, int(logo.height * lw / logo.width)), Image.LANCZOS)
    canvas.alpha_composite(logo, (left, 44))

    # Product pill
    pf = font(17, 'Bold')
    pill = PILL[p['product']]
    pw = d.textlength(pill, font=pf) + 36
    y = 176
    d.rounded_rectangle((left, y, left + pw, y + 38), radius=19, fill=(214, 242, 249))
    d.text((left + 18, y + 19), pill, font=pf, fill=CYAN_DARK, anchor='lm')

    # Title: "Best <keyword> in" in navy, the city in cyan on its own line.
    y += 62
    tf = font(50, 'ExtraBold')
    for line in wrap(d, ('Best ' + p['keyword'] + ' in').split(), tf, 560):
        d.text((left, y), line, font=tf, fill=INK)
        y += 62
    cf = font(66 if len(p['name']) <= 11 else 56, 'ExtraBold')
    d.text((left, y - 4), p['name'], font=cf, fill=CYAN)
    y += 86

    # Brand underline (cyan to green)
    bar = Image.new('RGBA', (64, 5))
    for xx in range(64):
        t = xx / 63
        ImageDraw.Draw(bar).line([(xx, 0), (xx, 5)], fill=tuple(int(CYAN[k] + (GREEN[k] - CYAN[k]) * t) for k in range(3)) + (255,))
    canvas.alpha_composite(bar, (left, y))
    y += 24

    # Tagline
    sf = font(23, 'Medium')
    for line in wrap(d, TAGLINE[p['product']].split(), sf, 520):
        d.text((left, y), line, font=sf, fill=TEXT)
        y += 34

    # City pin badge over the illustration
    label = p['name'] if p['state'] in p['name'] else p['name'] + ', ' + p['state']
    pin_badge(canvas, 640, H - 104, label)

    out = canvas.convert('RGB')
    base = os.path.join(BLOG, p['slug'])
    out.save(base + '.webp', 'WEBP', quality=82, method=6)
    out.save(base + '.jpg', 'JPEG', quality=84, optimize=True, progressive=True)
    out.resize((640, int(H * 640 / W)), Image.LANCZOS).save(base + '-sm.webp', 'WEBP', quality=80, method=6)


def main():
    args = sys.argv[1:]
    todo = pages()
    if args and args[0] != '--all':
        todo = [p for p in todo if p['slug'] in args]
    elif not args:
        todo = [p for p in todo if not os.path.exists(os.path.join(BLOG, p['slug'] + '.webp'))]
    for p in todo:
        cover(p)
        print('made', p['slug'])
    print(len(todo), 'cover(s)')


if __name__ == '__main__':
    main()
