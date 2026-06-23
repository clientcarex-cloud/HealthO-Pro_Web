import re

with open('pricing.html', 'r') as f:
    html = f.read()

# Define data
pricing_data = {
    'hims': {
        'startup': {'base': 5, 'year': 899, 'half': 1124},
        'business': {'base': 10, 'year': 1099, 'half': 1374},
        'corporate': {'base': 10, 'year': 1800, 'half': 2250}
    },
    'lims': {
        'startup': {'base': 5, 'year': 399, 'half': 499},
        'business': {'base': 10, 'year': 699, 'half': 874},
        'corporate': {'base': 10, 'year': 899, 'half': 1124}
    },
    'cims': {
        'startup': {'base': 5, 'year': 399, 'half': 499},
        'business': {'base': 10, 'year': 699, 'half': 874},
        'corporate': {'base': 10, 'year': 899, 'half': 1124}
    }
}

# 1. Rename Enterprise to Corporate
html = html.replace('>Enterprise</a>', '>Corporate</a>')
html = html.replace('id="plan-hims-enterprise"', 'id="plan-hims-corporate"')
html = html.replace('id="plan-lims-enterprise"', 'id="plan-lims-corporate"')
html = html.replace('id="plan-cims-enterprise"', 'id="plan-cims-corporate"')
html = html.replace('href="#plan-hims-enterprise"', 'href="#plan-hims-corporate"')
html = html.replace('href="#plan-lims-enterprise"', 'href="#plan-lims-corporate"')
html = html.replace('href="#plan-cims-enterprise"', 'href="#plan-cims-corporate"')

def generate_user_options(base):
    opts = f'<option value="{base}">{base} Users (Base)</option>'
    for i in range(base + 1, 21):
        opts += f'<option value="{i}">{i} Users</option>'
    for i in range(25, 55, 5):
        opts += f'<option value="{i}">{i} Users</option>'
    return opts

# 2. Replace the price sections
lines = html.split('\n')
current_panel = None
current_plan = None
new_lines = []

skip_mode = False

for line in lines:
    if 'id="panel-' in line:
        m = re.search(r'id="panel-([a-z]+)"', line)
        if m:
            current_panel = m.group(1)
            current_plan = None
            
    if current_panel and 'class="price-card' in line:
        m = re.search(r'id="plan-[a-z]+-([a-z]+)"', line)
        if m:
            current_plan = m.group(1)
            
    if skip_mode:
        if '<div class="plan-note">' in line:
            # We skip the plan-note too!
            continue
        elif '<ul class="checks">' in line or '<p' in line or '<div class="plan-desc">' in line:
            # Reached next block
            skip_mode = False
            new_lines.append(line)
        continue

    if current_panel and current_plan and '<div class="plan-price">' in line and current_panel in pricing_data and current_plan in pricing_data[current_panel]:
        data = pricing_data[current_panel][current_plan]
        base = data['base']
        year = data['year']
        half = data['half']
        
        base_id = f"plan-{current_panel}-{current_plan}"
        
        # Build new pricing block
        new_price_block = f"""        <div class="plan-price" style="margin-bottom:10px; display:flex; flex-direction:column; align-items:flex-start;">
          <div data-year>
            <span style="font-size:1.1rem; color:var(--text-soft); text-decoration:line-through; margin-right:8px;">₹{half}</span>
            <span class="cur">₹</span><span class="amt"><a href="#{base_id}-year" style="color:inherit;text-decoration:none;">{year}</a></span><span class="per">/ user / mo</span>
          </div>
          <div data-half style="display:none;">
            <span class="cur">₹</span><span class="amt"><a href="#{base_id}-half" style="color:inherit;text-decoration:none;">{half}</a></span><span class="per">/ user / mo</span>
          </div>
        </div>
        <div class="plan-users" style="margin-top:15px; margin-bottom:15px;">
          <select class="user-select" style="width:100%; padding:10px; border-radius:6px; border:1px solid var(--line); font-size:1rem; background:var(--bg); color:var(--text);" data-base="{base}" data-price-year="{year}" data-price-half="{half}">
            {generate_user_options(base)}
          </select>
        </div>
        <div class="plan-calc" style="background:var(--bg-sec); padding:15px; border-radius:8px; margin-bottom:20px; font-size:0.95rem;">
           <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
               <span>Subtotal</span>
               <span>₹ <span class="calc-base">0</span> / mo</span>
           </div>
           <div style="display:flex; justify-content:space-between; margin-bottom:8px; color:var(--text-soft);">
               <span>18% GST</span>
               <span>₹ <span class="calc-gst">0</span></span>
           </div>
           <div style="display:flex; justify-content:space-between; font-weight:700; font-size:1.1rem; border-top:1px solid var(--line); padding-top:8px; margin-top:8px;">
               <span>Total</span>
               <span>₹ <span class="calc-total">0</span> / mo</span>
           </div>
        </div>"""
        
        new_lines.extend(new_price_block.split('\n'))
        skip_mode = True  # Skip the old price note lines
    else:
        new_lines.append(line)

html = '\n'.join(new_lines)

with open('pricing.html', 'w') as f:
    f.write(html)

print("Updated pricing.html with dynamic calculators")
