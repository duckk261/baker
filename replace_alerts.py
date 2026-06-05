import os
import re

dir_path = '/Applications/XAMPP/xamppfiles/htdocs/baker'

def replace_in_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    original_content = content

    # Helper function for PHP echo alerts
    def php_replacer(m):
        msg = m.group(2)
        action = m.group(3).strip()
        
        # Escape single quotes in the message if they aren't already
        msg = msg.replace("'", "\\'")
        msg = msg.replace("\\\\'", "\\'") # avoid double escape
        
        # Determine the action: href, back, or nothing
        action_js = ""
        if "window.location.href" in action:
            url_match = re.search(r"window\.location\.href\s*=\s*(['\"])(.*?)\1", action)
            if url_match:
                url = url_match.group(2)
                # Handle PHP variables inside the URL string (e.g. 'index.php?page=' . $back_page)
                # This is tricky because the regex might capture raw PHP string concatenation.
                # Actually, the original string might look like: window.location.href='index.php?page=" . $back_page . "';
                action_js = f"window.location.href = '{url}';"
            else:
                action_js = action
        elif "window.history.back()" in action:
            action_js = "window.history.back();"
        else:
            action_js = action

        # Create the replacement string
        swal_html = (
            "<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script>"
            "<style>body { font-family: sans-serif; }</style>"
            "<script>"
            "document.addEventListener(\"DOMContentLoaded\", function() {"
            f"Swal.fire({{title: \"Thông báo\", text: \"{msg}\", confirmButtonColor: \"#c4a16b\", icon: \"info\"}}).then((result) => {{ {action_js} }});"
            "});"
            "</script>"
        )
        
        # Since the original is an echo, we just echo the swal_html
        return f"echo '{swal_html}';"

    # Regex to catch: echo "<script>alert('...'); window.location.href='...';</script>";
    # Be very careful about quotes and PHP string concatenation.
    # Pattern: echo [\"\']<script>alert\(([\"\'])(.*?)\1\);(.*?)</script>[\"\'];
    pattern = re.compile(r"echo\s+[\"']<script>alert\((['\"])(.*?)\1\);(.*?)</script>[\"'];", re.IGNORECASE | re.DOTALL)
    
    content = pattern.sub(php_replacer, content)

    if content != original_content:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Updated: {filepath}")

for root, dirs, files in os.walk(dir_path):
    for file in files:
        if file.endswith('.php'):
            replace_in_file(os.path.join(root, file))

