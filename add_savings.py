import re

with open('pricing.html', 'r') as f:
    html = f.read()

# We need to append the savings row right after the billed amount div
old_regex = r'(<div style="display:flex; justify-content:space-between; align-items:center; gap:8px; font-size:0\.95rem; font-weight:700; color:var\(--navy, #0f3b74\); margin-top:8px; padding:8px 10px; background:rgba\(15, 59, 116, 0\.05\); border-radius:4px; line-height:1\.3;">\n               <span>Total Billed <span class="calc-billed-freq">[^<]+</span></span>\n               <span style="white-space:nowrap; flex-shrink:0;">₹ <span class="calc-billed">[^<]+</span></span>\n           </div>)'

replacement = r'''\1
           <div class="calc-savings-row" style="display:flex; justify-content:space-between; align-items:center; gap:8px; font-size:0.85rem; font-weight:700; color:#10b981; margin-top:6px; padding:0 10px; display:none;">
               <span>You Save</span>
               <span style="white-space:nowrap; flex-shrink:0;">₹ <span class="calc-savings">0</span> / yr</span>
           </div>'''

html = re.sub(old_regex, replacement, html)

with open('pricing.html', 'w') as f:
    f.write(html)

print("Added savings row HTML")
