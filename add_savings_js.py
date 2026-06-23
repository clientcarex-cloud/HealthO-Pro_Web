import re

with open('script.js', 'r') as f:
    js = f.read()

replacement = r'''                var baseEl = card.querySelector('.calc-base');
                var gstEl = card.querySelector('.calc-gst');
                var totalEl = card.querySelector('.calc-total');
                var billedEl = card.querySelector('.calc-billed');
                var freqEl = card.querySelector('.calc-billed-freq');
                var savingsRow = card.querySelector('.calc-savings-row');
                var savingsEl = card.querySelector('.calc-savings');
                
                var months = (cycle === 'year') ? 12 : 6;
                var billedAmount = total * months;
                
                if (baseEl) baseEl.textContent = subtotal.toLocaleString('en-IN');
                if (gstEl) gstEl.textContent = gst.toLocaleString('en-IN');
                if (totalEl) totalEl.textContent = total.toLocaleString('en-IN');
                if (billedEl) billedEl.textContent = billedAmount.toLocaleString('en-IN');
                if (freqEl) freqEl.textContent = (cycle === 'year') ? 'Annually' : 'Every 6 Months';
                
                if (cycle === 'year') {
                    var priceHalf = parseInt(select.getAttribute('data-price-half'), 10);
                    var totalIfHalf = (users * priceHalf) * 1.18;
                    var yearlyCostIfHalf = Math.round(totalIfHalf * 12);
                    var savings = yearlyCostIfHalf - billedAmount;
                    
                    if (savingsRow) {
                        savingsRow.style.display = 'flex';
                        if (savingsEl) savingsEl.textContent = savings.toLocaleString('en-IN');
                    }
                } else {
                    if (savingsRow) savingsRow.style.display = 'none';
                }'''

js = re.sub(
    r'                var baseEl = card\.querySelector\(\'\.calc-base\'\);.*?(?=            \}\);)',
    replacement,
    js,
    flags=re.DOTALL
)

with open('script.js', 'w') as f:
    f.write(js)

print("Added JS for savings calculation")
