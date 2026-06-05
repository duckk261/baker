import os
import re

def replace_in_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # 1. Handle `onclick='return confirm(\"MSG\");'`
    # We replace it with `onclick='confirmAction(event, this.href, \"MSG\");'`
    def repl_onclick_return_double(m):
        msg = m.group(1)
        return f"onclick='confirmAction(event, this.href, \"{msg}\");'"

    content = re.sub(r"onclick='return confirm\(\\\"(.*?)\\\"\);'", repl_onclick_return_double, content)

    # 2. Handle `onclick=\"return confirm('MSG');\"`
    def repl_onclick_return_single(m):
        msg = m.group(1)
        return f"onclick=\\\"confirmAction(event, this.href, '{msg}');\\\""

    content = re.sub(r"onclick=\\\"return confirm\('(.*?)'\);\\\"", repl_onclick_return_single, content)

    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"Fixed: {filepath}")

replace_in_file('/Applications/XAMPP/xamppfiles/htdocs/baker/admin/views/order_list.php')
replace_in_file('/Applications/XAMPP/xamppfiles/htdocs/baker/admin/views/product_list.php')
