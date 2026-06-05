import os
import re

dir_path = '/Applications/XAMPP/xamppfiles/htdocs/baker'

def replace_in_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    original_content = content

    # 1. Handle `onclick="return confirm('MSG');"` inside `<a>` tags
    # We replace `onclick="return confirm('MSG');"` with `onclick="confirmAction(event, this.href, 'MSG');"`
    def repl_onclick_return(m):
        quote = m.group(1) # ' or "
        msg = m.group(2)
        return f'onclick="confirmAction(event, this.href, {quote}{msg}{quote});"'

    content = re.sub(r'onclick=[\'"]return confirm\(([\'"])(.*?)\1\);?[\'"]', repl_onclick_return, content)

    # 2. Handle `onclick="if(confirm('MSG')) window.location.href='URL';"`
    def repl_onclick_if(m):
        msg = m.group(1)
        url = m.group(2)
        # Using confirmAction helper with the static url
        return f'onclick="confirmAction(event, \'{url}\', \'{msg}\');"'

    content = re.sub(r'onclick=[\'"]if\(\s*confirm\([\'"]([^\'"]+)[\'"]\)\s*\)\s*window\.location\.href=[\'"]([^\'"]+)[\'"];?[\'"]', repl_onclick_if, content)
    
    # 3. Handle `onclick='return confirm("MSG");'` (different quotes)
    def repl_onclick_return_double(m):
        quote = m.group(1) # ' or "
        msg = m.group(2)
        # wait, the first regex covers both: r'onclick=[\'"]return confirm\(([\'"])(.*?)\1\);?[\'"]'
        # let's be careful about escaped quotes.
        return m.group(0)

    # 4. Handle pure JS: `if(!confirm('MSG')) return;`
    # This requires converting the function to be promise-based or wrapping the rest.
    # We only have this in views/cart.php for `removeCartAJAX(productId)`.
    # I will handle JS files manually if there are only a few.

    if content != original_content:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Fixed confirm: {filepath}")

for root, dirs, files in os.walk(dir_path):
    for file in files:
        if file.endswith('.php') or file.endswith('.js') or file.endswith('.html'):
            replace_in_file(os.path.join(root, file))
