import re

with open('pricing.html', 'r') as f:
    html = f.read()

# Fix any lingering broken comments
html = html.replace('     LIMS PANEL ===== -->', '    <!-- ===== LIMS PANEL ===== -->')
html = html.replace('     CIMS PANEL ===== -->', '    <!-- ===== CIMS PANEL ===== -->')
html = html.replace('     RIS PANEL ===== -->', '    <!-- ===== RIS PANEL ===== -->')

# Let's fix the anchor tags in the plan cards
# 1. remove existing anchor tags from plan names if any to start clean
html = re.sub(r'<div class="plan-name"><a[^>]+>([^<]+)</a></div>', r'<div class="plan-name">\1</div>', html)
# 2. ensure IDs are correct
# For HIMS:
html = re.sub(r'(<div id=")plan-[a-z]+(-startup" class="price-card">)(\s*<div class="plan-name">)(Startup)(</div>)', r'\1plan-hims\2\3<a href="#plan-hims-\4" style="color:inherit;text-decoration:none;">\4</a>\5', html, count=1)
html = re.sub(r'(<div id=")plan-[a-z]+(-business" class="price-card featured">)(\s*<div class="plan-name">)(Business)(</div>)', r'\1plan-hims\2\3<a href="#plan-hims-\4" style="color:inherit;text-decoration:none;">\4</a>\5', html, count=1)
html = re.sub(r'(<div id=")plan-[a-z]+(-enterprise" class="price-card">)(\s*<div class="plan-name">)(Enterprise)(</div>)', r'\1plan-hims\2\3<a href="#plan-hims-\4" style="color:inherit;text-decoration:none;">\4</a>\5', html, count=1)

# Now write a simpler approach: iterate over the file lines and fix contextually
lines = html.split('\n')
current_panel = None
for i in range(len(lines)):
    if 'id="panel-' in lines[i]:
        m = re.search(r'id="panel-([a-z]+)"', lines[i])
        if m:
            current_panel = m.group(1)
            
    if current_panel and 'class="price-card' in lines[i]:
        # Fix the ID of the card
        lines[i] = re.sub(r'id="plan-[a-z]+-([a-z]+)"', rf'id="plan-{current_panel}-\1"', lines[i])
        
    if current_panel and '<div class="plan-name">' in lines[i]:
        # If it has an anchor, strip it
        lines[i] = re.sub(r'<a[^>]+>([^<]+)</a>', r'\1', lines[i])
        # Add anchor back correctly based on current panel and card type
        # The card type is the text inside plan-name
        m = re.search(r'<div class="plan-name">([^<]+)</div>', lines[i])
        if m:
            plan_type = m.group(1).lower()
            lines[i] = re.sub(r'<div class="plan-name">([^<]+)</div>', rf'<div class="plan-name"><a href="#plan-{current_panel}-{plan_type}" style="color:inherit;text-decoration:none;">\1</a></div>', lines[i])

html = '\n'.join(lines)

with open('pricing.html', 'w') as f:
    f.write(html)

print("Fixed pricing HTML")
