import re

with open('pricing.html', 'r') as f:
    html = f.read()

old_block = r'''   <!-- Monthly Equivalent -->
   <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px dashed var\(--line\); padding-top:12px; margin-bottom:16px;">
       <span style="font-weight:600; color:var\(--navy\);">Monthly Equivalent</span>
       <span style="font-weight:700; font-size:1\.1rem; color:var\(--navy\);">₹ <span class="calc-total">0</span> <span style="font-size:0\.85rem; font-weight:500; color:var\(--text-soft\);">/ mo</span></span>
   </div>'''

new_block = '''   <!-- Monthly Equivalent -->
   <div style="display:flex; justify-content:space-between; margin-bottom:16px; color:var(--text-soft);">
       <span>Monthly Equivalent</span>
       <span style="color:var(--text); font-weight:500;">₹ <span class="calc-total">0</span> <span style="font-size:0.85rem;">/ mo</span></span>
   </div>'''

html = re.sub(old_block, new_block, html)

with open('pricing.html', 'w') as f:
    f.write(html)

print("Tweaked monthly equivalent styling")
