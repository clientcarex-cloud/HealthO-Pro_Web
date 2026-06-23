import re

with open('styles.css', 'r') as f:
    css = f.read()

# Typography Scale
scale = {
    'var(--text-xs)': 0.75,
    'var(--text-sm)': 0.875,
    'var(--text-base)': 1.0,
    'var(--text-lg)': 1.125,
    'var(--text-xl)': 1.25,
    'var(--text-2xl)': 1.5,
    'var(--text-3xl)': 1.875,
    'var(--text-4xl)': 2.25,
    'var(--text-5xl)': 3.0,
    'var(--text-6xl)': 3.75
}

def get_closest_var(val):
    return min(scale.keys(), key=lambda k: abs(scale[k] - val))

def replace_font_size(match):
    num_str = match.group(1)
    if num_str.startswith('.'):
        num_str = '0' + num_str
    
    val = float(num_str)
    
    if val >= 4.0: return 'font-size: var(--text-6xl)'
    if val >= 2.6: return 'font-size: var(--text-5xl)'
    if val >= 2.2: return 'font-size: var(--text-4xl)'
    if val >= 1.7: return 'font-size: var(--text-3xl)'
    if val >= 1.4: return 'font-size: var(--text-2xl)'
    if val >= 1.2: return 'font-size: var(--text-xl)'
    if val >= 1.05: return 'font-size: var(--text-lg)'
    if val >= 0.92: return 'font-size: var(--text-base)'
    if val >= 0.8: return 'font-size: var(--text-sm)'
    return 'font-size: var(--text-xs)'

# Find font-size: Xrem or X.Xrem
new_css = re.sub(r'font-size:\s*([0-9]*\.[0-9]+|[0-9]+)rem', replace_font_size, css)

with open('styles.css', 'w') as f:
    f.write(new_css)

