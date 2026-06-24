        </div><!-- .content-area -->
    </main>
</div><!-- .admin-wrapper -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>
<script>
// Sidebar toggle
document.getElementById('sidebarToggle').addEventListener('click', function() {
    document.querySelector('.admin-wrapper').classList.toggle('sidebar-collapsed');
});

// Summernote init
$(document).ready(function() {
    $('.tinymce-editor').summernote({
        height: 350,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        styleTags: ['p', 'h2', 'h3', 'h4', 'blockquote'],
        callbacks: {
            onInit: function() {
                $(this).parent().find('.note-editable').css({
                    'font-family': 'Poppins, sans-serif',
                    'font-size': '14px',
                    'line-height': '1.8'
                });
            }
        }
    });
});

// Auto-hide alerts
document.querySelectorAll('.alert-dismissible').forEach(function(alert) {
    setTimeout(function() { alert.style.opacity = '0'; setTimeout(function() { alert.remove(); }, 300); }, 4000);
});
</script>
</body>
</html>
