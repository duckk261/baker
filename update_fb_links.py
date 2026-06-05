import re

files = [
    '/Applications/XAMPP/xamppfiles/htdocs/baker/views/about.php',
    '/Applications/XAMPP/xamppfiles/htdocs/baker/views/footer.php',
    '/Applications/XAMPP/xamppfiles/htdocs/baker/views/home.php'
]

fb_url = "https://web.facebook.com/profile.php?id=61584104759477"

for file in files:
    with open(file, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # We want to replace href="" when it's just before or near <i class="fab fa-facebook-f"></i>
    # Let's use a regex to capture the <a> tag that contains the facebook icon.
    
    def repl(m):
        return m.group(0).replace('href=""', f'href="{fb_url}" target="_blank"')

    # Pattern to match `<a ... href="" ...><i class="fab fa-facebook-f"></i></a>`
    # We can match `<a[^>]*href=""[^>]*>.*?<i[^>]*fa-facebook-f[^>]*>.*?</a>`
    pattern = re.compile(r'<a[^>]*href=""[^>]*>\s*<i[^>]*fa-facebook-f[^>]*>.*?</a>', re.IGNORECASE)
    
    content = pattern.sub(repl, content)

    with open(file, 'w', encoding='utf-8') as f:
        f.write(content)
        
    print(f"Updated {file}")
