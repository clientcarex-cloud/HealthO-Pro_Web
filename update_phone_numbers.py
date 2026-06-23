import os
import glob

# Mapping
replacements = [
    ("9700710055", "TMP_PHONE_A"),
    ("9700730044", "TMP_PHONE_B"),
    ("97007 10055", "TMP_PHONE_C"),
    ("97007 30044", "TMP_PHONE_D"),
    ("-10055", "TMP_PHONE_E"),
    ("-30044", "TMP_PHONE_F"),
]

final_replacements = [
    ("TMP_PHONE_A", "9700730044"),
    ("TMP_PHONE_B", "9700710055"),
    ("TMP_PHONE_C", "97007 30044"),
    ("TMP_PHONE_D", "97007 10055"),
    ("TMP_PHONE_E", "-30044"),
    ("TMP_PHONE_F", "-10055"),
]

for filepath in glob.glob("*.html"):
    with open(filepath, 'r') as file:
        content = file.read()
    
    for old, new in replacements:
        content = content.replace(old, new)
        
    for old, new in final_replacements:
        content = content.replace(old, new)
        
    with open(filepath, 'w') as file:
        file.write(content)

print("Phone numbers updated.")
