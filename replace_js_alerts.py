import os
import re

dir_path = '/Applications/XAMPP/xamppfiles/htdocs/baker/views'

def replace_in_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    original_content = content

    def repl_onclick(m):
        msg = m.group(1).replace("'", "\\'")
        url = m.group(2)
        return f'onclick="Swal.fire({{title: \'Thông báo\', text: \'{msg}\', confirmButtonColor: \'#c4a16b\', icon: \'info\'}}).then(() => {{ window.location.href=\'{url}\'; }});"'

    content = re.sub(r'onclick="alert\(\'([^\']*)\'\);\s*window\.location\.href=\'([^\']*)\';"', repl_onclick, content)

    # 3. Handle JS alert with button onclick without location href
    def repl_onclick_noback(m):
        msg = m.group(1).replace("'", "\\'")
        return f'onclick="Swal.fire({{title: \'Thông báo\', text: \'{msg}\', confirmButtonColor: \'#c4a16b\', icon: \'info\'}});"'
    
    content = re.sub(r'onclick="alert\(\'([^\']*)\'\);"', repl_onclick_noback, content)

    # 2. Handle JS alert inside script tags
    def repl_js_alert(m):
        expr = m.group(1).strip()
        return f"Swal.fire({{title: 'Thông báo', text: {expr}, confirmButtonColor: '#c4a16b', icon: 'info'}});"
        
    # use lookbehind for boundary that is not a word character to avoid something like window.alert
    content = re.sub(r'\balert\((.*?)\);', repl_js_alert, content)

    if content != original_content:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Fixed JS: {filepath}")

for root, dirs, files in os.walk(dir_path):
    for file in files:
        if file.endswith('.php') or file.endswith('.js') or file.endswith('.html'):
            replace_in_file(os.path.join(root, file))

