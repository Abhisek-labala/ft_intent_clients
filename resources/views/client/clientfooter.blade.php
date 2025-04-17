<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Include DataTables JS -->
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
<script src="{{asset('js/script.js')}}"></script>

<!-- Include SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function toggleSidebar() {
        var sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('open');
    }

    function toggleDropdown(id) {
        var dropdownContent = document.getElementById(id);
        dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
    }

    const toggleTheme = () => {
            const body = document.body;
            const icon = document.getElementById("theme-toggle");
            if (body.style.background === 'rgb(0, 0, 0)') {
                // Switch to light theme
                body.style.background = '#FFF';
                body.style.color = '#000';
                document.documentElement.style.setProperty('--background', '#FFF');
                document.documentElement.style.setProperty('--color', '#000');
                document.documentElement.style.setProperty('--primary-color', '#007bff');
                icon.innerHTML = '<i class="fas fa-moon"></i>';
                icon.className = 'settings-icon moon'; 
            } else {
                // Switch to dark theme
                body.style.background = '#000';
                body.style.color = '#fff';
                document.documentElement.style.setProperty('--background', '#000');
                document.documentElement.style.setProperty('--color', '#FFF');
                document.documentElement.style.setProperty('--primary-color', '#007bff');
                icon.innerHTML = '<i class="fas fa-sun"></i>';
                icon.className = 'settings-icon sun';
            }
        };

        document.getElementById("theme-toggle").addEventListener("click", toggleTheme);

        document.getElementById('settlement-link').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('toast-modal').style.display = 'block';
    });

    function closeToast() {
        document.getElementById('toast-modal').style.display = 'none';
    }
    
</script>

</body>

</html>