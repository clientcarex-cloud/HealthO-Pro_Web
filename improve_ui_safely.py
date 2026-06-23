import re

with open('pricing.html', 'r') as f:
    html = f.read()

new_calc_block = '''<div class="plan-calc" style="background:var(--bg-sec); border:1px solid var(--line); padding:16px; border-radius:8px; margin-bottom:24px; font-size:0.95rem;">
   <!-- Breakdown -->
   <div style="display:flex; justify-content:space-between; margin-bottom:10px; color:var(--text-soft);">
       <span>Subtotal</span>
       <span style="color:var(--text); font-weight:500;">₹ <span class="calc-base">0</span> <span style="font-size:0.85rem;">/ mo</span></span>
   </div>
   <div style="display:flex; justify-content:space-between; margin-bottom:12px; color:var(--text-soft);">
       <span>GST (18%)</span>
       <span style="color:var(--text); font-weight:500;">₹ <span class="calc-gst">0</span> <span style="font-size:0.85rem;">/ mo</span></span>
   </div>
   
   <!-- Monthly Equivalent -->
   <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px dashed var(--line); padding-top:12px; margin-bottom:16px;">
       <span style="font-weight:600; color:var(--navy);">Monthly Equivalent</span>
       <span style="font-weight:700; font-size:1.1rem; color:var(--navy);">₹ <span class="calc-total">0</span> <span style="font-size:0.85rem; font-weight:500; color:var(--text-soft);">/ mo</span></span>
   </div>

   <!-- Commitment Box -->
   <div style="background:var(--bg); border:1px solid var(--line); border-radius:6px; padding:12px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
       <div style="display:flex; justify-content:space-between; align-items:center;">
           <span style="font-size:0.9rem; font-weight:600; color:var(--navy);">Billed <span class="calc-billed-freq">Annually</span></span>
           <span style="font-weight:800; font-size:1.15rem; color:var(--primary);">₹ <span class="calc-billed">0</span></span>
       </div>
       <div class="calc-savings-row" style="display:flex; align-items:center; gap:6px; font-size:0.85rem; font-weight:700; color:#10b981; margin-top:8px; display:none;">
           <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
           <span>You save ₹ <span class="calc-savings">0</span> a year</span>
       </div>
   </div>
</div>'''

# Match the old block accurately
pattern = r'<div class="plan-calc" style="background:var\(--bg-sec\); padding:15px; border-radius:8px; margin-bottom:20px; font-size:0\.95rem;">.*?<span class="calc-billed">0</span></span>\n\s*</div>\n\s*</div>'

html = re.sub(pattern, new_calc_block, html, flags=re.DOTALL)

with open('pricing.html', 'w') as f:
    f.write(html)

print("Safely replaced calc blocks")
