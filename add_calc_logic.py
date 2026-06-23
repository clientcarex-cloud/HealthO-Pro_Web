import re

with open('script.js', 'r') as f:
    js = f.read()

# Logic to inject
calc_logic = """
        /* ---------- Real-time Pricing Calculator ---------- */
        var currentBillingCycle = 'year';
        var recalculatePrices = function(cycle) {
            currentBillingCycle = cycle;
            document.querySelectorAll('.user-select').forEach(function(select) {
                var card = select.closest('.price-card');
                if (!card) return;
                
                var users = parseInt(select.value, 10);
                var pricePerUser = parseInt(select.getAttribute('data-price-' + cycle), 10);
                
                var subtotal = users * pricePerUser;
                var gst = Math.round(subtotal * 0.18);
                var total = subtotal + gst;
                
                var baseEl = card.querySelector('.calc-base');
                var gstEl = card.querySelector('.calc-gst');
                var totalEl = card.querySelector('.calc-total');
                
                if (baseEl) baseEl.textContent = subtotal.toLocaleString('en-IN');
                if (gstEl) gstEl.textContent = gst.toLocaleString('en-IN');
                if (totalEl) totalEl.textContent = total.toLocaleString('en-IN');
            });
        };

        document.querySelectorAll('.user-select').forEach(function(select) {
            select.addEventListener('change', function() {
                recalculatePrices(currentBillingCycle);
            });
        });

"""

# Insert calc_logic right before `var setBilling = function (cycle) {`
# And modify `setBilling` to call `recalculatePrices(cycle)`

def repl(m):
    return calc_logic + m.group(1) + "            recalculatePrices(cycle);\n"

js = re.sub(r'(        var setBilling = function \(cycle\) \{\n)', repl, js)

with open('script.js', 'w') as f:
    f.write(js)

print("Added calculator logic to script.js")
