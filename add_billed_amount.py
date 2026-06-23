import re

with open('pricing.html', 'r') as f:
    html = f.read()

# Instead of matching a giant block, we match just the end of the total row and the closing div of plan-calc
replacement = r'''               <span>₹ <span class="calc-total">0</span> / mo</span>
           </div>
           <div style="display:flex; justify-content:space-between; font-size:0.9rem; color:var(--text-soft); margin-top:6px;">
               <span>Total Billed <span class="calc-billed-freq">Annually</span></span>
               <span>₹ <span class="calc-billed">0</span></span>
           </div>
        </div>'''

html = re.sub(
    r'               <span>₹ <span class="calc-total">0</span> / mo</span>\n           </div>\n        </div>',
    replacement,
    html
)

with open('pricing.html', 'w') as f:
    f.write(html)
