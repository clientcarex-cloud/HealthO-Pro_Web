import re

with open('pricing.html', 'r') as f:
    lines = f.readlines()

new_lines = []
for i, line in enumerate(lines):
    # 1. Check if the line is the first <li> of a group, and if there's no <ul> before it
    if '<li><svg' in line or '<li><span' in line:
        # Check previous line
        prev_line = lines[i-1] if i > 0 else ""
        if '</div>' in prev_line and '<ul' not in prev_line:
            # We are probably missing the <ul>
            new_lines.append('          <ul class="checks">\n')

    # 2. Fix the <li> to have proper span.ck and span for text
    # Original might be: <li><svg ...>...</svg>Text</li>
    # We want: <li><span class="ck"><svg ...>...</svg></span><span>Text</span></li>
    
    if re.search(r'<li>\s*<svg', line):
        # Find the end of the svg
        svg_end = line.find('</svg>') + 6
        if svg_end > 5:
            svg_part = line[line.find('<svg'):svg_end]
            text_part = line[svg_end:line.find('</li>')]
            # if text_part is not wrapped in span (or if it is, we'll see)
            if not text_part.startswith('<span'):
                line = line[:line.find('<svg')] + f'<span class="ck">{svg_part}</span><span>{text_part}</span></li>\n'
                
    new_lines.append(line)

with open('pricing.html', 'w') as f:
    f.writelines(new_lines)

print("Fixed pricing.html lists")
