import os
import re

dir_path = '/Applications/XAMPP/xamppfiles/htdocs/baker'

def replace_in_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    original_content = content

    def fix_replacer(m):
        full_echo = m.group(0)
        # We need to change `echo '...'` back to `echo "..."`
        # and inside the string, change double quotes to escaped double quotes `\"`
        
        # Extract the inner content between `echo '` and `';`
        inner = m.group(1)
        
        # Replace unescaped double quotes with `\"`
        inner_fixed = inner.replace('"', '\\"')
        # Unescape single quotes where they were used for URL bounds like `window.location.href = '...'`
        # But wait, python script already output: window.location.href = 'index.php?page=" . $back_page . "';
        # We need it to be: window.location.href = 'index.php?page=' . $back_page . ''; OR just use double quotes for the echo.
        # Actually, let's just do a simpler fix for the specific broken lines.
        return f'echo "{inner_fixed}";'

    # Catch the previously generated `echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>...';`
    pattern = re.compile(r"echo\s+'(<script src=\"https://cdn\.jsdelivr\.net/npm/sweetalert2@11\"></script>.*?</script>)';", re.DOTALL)
    
    content = pattern.sub(fix_replacer, content)

    if content != original_content:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Fixed: {filepath}")

for root, dirs, files in os.walk(dir_path):
    for file in files:
        if file.endswith('.php'):
            replace_in_file(os.path.join(root, file))
