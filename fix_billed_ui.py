import re

with open('pricing.html', 'r') as f:
    html = f.read()

# Current string:
# <div style="display:flex; justify-content:space-between; font-size:0.95rem; font-weight:700; color:var(--navy, #0f3b74); margin-top:8px; padding:6px 8px; background:rgba(15, 59, 116, 0.05); border-radius:4px;">
#                <span>Total Billed <span class="calc-billed-freq">Annually</span></span>
#                <span>₹ <span class="calc-billed">0</span></span>
#            </div>

old_regex = r'<div style="display:flex; justify-content:space-between; font-size:0\.95rem; font-weight:700; color:var\(--navy, #0f3b74\); margin-top:8px; padding:6px 8px; background:rgba\(15, 59, 116, 0\.05\); border-radius:4px;">\n               <span>Total Billed <span class="calc-billed-freq">([^<]+)</span></span>\n               <span>₹ <span class="calc-billed">([^<]+)</span></span>\n           </div>'

def replace_fn(m):
    freq = m.group(1)
    amt = m.group(2)
    return f'''<div style="display:flex; justify-content:space-between; align-items:center; gap:8px; font-size:0.95rem; font-weight:700; color:var(--navy, #0f3b74); margin-top:8px; padding:8px 10px; background:rgba(15, 59, 116, 0.05); border-radius:4px; line-height:1.3;">
               <span>Total Billed <span class="calc-billed-freq">{freq}</span></span>
               <span style="white-space:nowrap; flex-shrink:0;">₹ <span class="calc-billed">{amt}</span></span>
           </div>'''

html = re.sub(old_regex, replace_fn, html)

with open('pricing.html', 'w') as f:
    f.write(html)

print("Fixed UI for billed amount")
