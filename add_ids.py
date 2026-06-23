import re

with open('pricing.html', 'r') as f:
    html = f.read()

# Add ids to tabs
html = re.sub(r'(<button )(class="product-tab[^"]*"\s+data-product="([^"]+)")', r'\1id="tab-\3" \2', html)

# Add ids to price cards
def add_card_id(panel_name, card_name, html):
    # Find the panel
    panel_pattern = r'(id="panel-' + panel_name + r'".*?)<!-- ====='
    def repl(m):
        panel_content = m.group(1)
        # Find the card with the specific name
        card_pattern = r'(<div )(class="price-card[^"]*">)(\s*<div class="plan-name">' + card_name + r'</div>)'
        new_panel_content = re.sub(card_pattern, r'\1id="plan-' + panel_name + '-' + card_name.lower() + r'" \2\3', panel_content, flags=re.IGNORECASE)
        return new_panel_content
    return re.sub(panel_pattern, repl, html, flags=re.DOTALL)

html = add_card_id('hims', 'Startup', html)
html = add_card_id('hims', 'Business', html)
html = add_card_id('hims', 'Enterprise', html)

html = add_card_id('lims', 'Startup', html)
html = add_card_id('lims', 'Business', html)
html = add_card_id('lims', 'Enterprise', html)

html = add_card_id('cims', 'Startup', html)
html = add_card_id('cims', 'Business', html)
html = add_card_id('cims', 'Enterprise', html)

# RIS has payg
html = re.sub(r'(<div )(class="payg">)', r'\1id="plan-ris-payg" \2', html)

with open('pricing.html', 'w') as f:
    f.write(html)

print("Added IDs to pricing.html")
