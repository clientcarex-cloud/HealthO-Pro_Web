import re

with open('pricing.html', 'r') as f:
    html = f.read()

lines = html.split('\n')
current_panel = None
current_plan = None

for i in range(len(lines)):
    # Track current panel
    if 'id="panel-' in lines[i]:
        m = re.search(r'id="panel-([a-z]+)"', lines[i])
        if m:
            current_panel = m.group(1)
            current_plan = None
            
    # Track current plan
    if current_panel and 'class="price-card' in lines[i]:
        m = re.search(r'id="plan-[a-z]+-([a-z]+)"', lines[i])
        if m:
            current_plan = m.group(1)
            
    # Update price amounts with anchor tags
    if current_panel and current_plan and '<div class="plan-price">' in lines[i]:
        base_id = f"plan-{current_panel}-{current_plan}"
        
        # Remove existing anchors if we're running this multiple times
        lines[i] = re.sub(r'<a href="#[^"]+" style="color:inherit;text-decoration:none;">([^<]+)</a>', r'\1', lines[i])
        
        # Inject new anchors for data-year
        lines[i] = re.sub(
            r'(<span class="amt"[^>]*data-year[^>]*>)(.*?)(</span>)',
            rf'\1<a href="#{base_id}-year" style="color:inherit;text-decoration:none;">\2</a>\3',
            lines[i]
        )
        # Inject new anchors for data-half
        lines[i] = re.sub(
            r'(<span class="amt"[^>]*data-half[^>]*>)(.*?)(</span>)',
            rf'\1<a href="#{base_id}-half" style="color:inherit;text-decoration:none;">\2</a>\3',
            lines[i]
        )

html = '\n'.join(lines)

with open('pricing.html', 'w') as f:
    f.write(html)

print("Added price links to pricing.html")
