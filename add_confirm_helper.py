import re

def add_helper(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    helper = """
<script>
function confirmAction(event, url, message) {
    event.preventDefault();
    Swal.fire({
        title: 'Xác nhận',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#c4a16b',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Đồng ý',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}
</script>
"""
    if "function confirmAction(" not in content:
        content = content.replace("</head>", helper + "</head>")
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Added helper to {filepath}")

add_helper('/Applications/XAMPP/xamppfiles/htdocs/baker/views/header.php')
add_helper('/Applications/XAMPP/xamppfiles/htdocs/baker/admin/index.php')
