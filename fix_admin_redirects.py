import re

file_path = '/Applications/XAMPP/xamppfiles/htdocs/baker/admin/index.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# We want to replace `window.location.href = 'something\" . $var . \"something';`
# with `window.location.href = 'something' . $var . 'something';` inside the echo string?
# No, we want `window.location.href = 'something$var';` because PHP interpolates inside double quotes!
# Wait, if the original was:
# echo "<script>... window.location.href='index.php?page=" . $back_page . "';</script>";
# In python it became:
# echo "<script>... window.location.href = 'index.php?page=\" . $back_page . \"';</script>";
# This is wrong. It should just be:
# echo "<script>... window.location.href = 'index.php?page=$back_page';</script>";

def repl(m):
    # m.group(0) is like `href = 'index.php?page=\" . $back_page . \"';`
    s = m.group(0)
    # just remove the `\" . ` and ` . \"`
    s = s.replace(r'\" . ', '')
    s = s.replace(r' . \"', '')
    return s

content = re.sub(r'window\.location\.href\s*=\s*\'[^\']*?\\"\s*\.\s*\$[a-zA-Z0-9_]+\s*\.\s*\\"[^\']*?\'', repl, content)

# Also fix `window.location.href = '$ref';` -> `window.location.href = \'$ref\';`
# Wait, `window.location.href = '$ref';` is valid! PHP interpolates $ref inside double quotes.
# JS gets `window.location.href = 'http...';` which is perfect.

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Fixed admin redirects.")
