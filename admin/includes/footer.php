    </div><!-- End Admin Main -->

    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const adminSidebar = document.getElementById('adminSidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if(sidebarToggle){
            sidebarToggle.addEventListener('click', () => {
                adminSidebar.classList.toggle('active');
                sidebarOverlay.classList.toggle('active');
            });
        }
        
        if(sidebarOverlay){
            sidebarOverlay.addEventListener('click', () => {
                adminSidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            });
        }
    </script>
</body>
</html>
