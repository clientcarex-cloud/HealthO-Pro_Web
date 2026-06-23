import re

with open('pricing.html', 'r') as f:
    html = f.read()

# Replace:
# <div style="display:flex; justify-content:space-between; font-size:0.9rem; color:var(--text-soft); margin-top:6px;">
# with:
# <div style="display:flex; justify-content:space-between; font-size:0.95rem; font-weight:700; color:var(--navy); margin-top:8px; padding:6px 8px; background:rgba(15, 59, 116, 0.05); border-radius:4px;">

old_str = r'<div style="display:flex; justify-content:space-between; font-size:0.9rem; color:var\(--text-soft\); margin-top:6px;">\n               <span>Total Billed'

new_str = r'<div style="display:flex; justify-content:space-between; font-size:0.95rem; font-weight:700; color:var(--navy, #0f3b74); margin-top:8px; padding:6px 8px; background:rgba(15, 59, 116, 0.05); border-radius:4px;">\n               <span>Total Billed'

html = re.sub(old_str, new_str, html)

with open('pricing.html', 'w') as f:
    f.write(html)
