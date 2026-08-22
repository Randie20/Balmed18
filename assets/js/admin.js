document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-confirm]').forEach((element) => {
        element.addEventListener('click', (event) => {
            const message = element.getAttribute('data-confirm') || 'Are you sure?';

            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });
});


document.addEventListener('DOMContentLoaded', function () {
    const body = document.body;
    const sidebarToggle = document.getElementById('adminSidebarToggle');
    const mobileToggle = document.getElementById('adminMobileToggle');
    const overlay = document.getElementById('adminSidebarOverlay');

    if (sidebarToggle) {
        const collapsed = localStorage.getItem('balmedAdminSidebar') === 'collapsed';

        if (collapsed && window.innerWidth > 900) {
            body.classList.add('admin-sidebar-collapsed');
            sidebarToggle.setAttribute('aria-expanded', 'false');
        }

        sidebarToggle.addEventListener('click', function () {
            if (window.innerWidth <= 900) {
                body.classList.toggle('admin-sidebar-mobile-open');
                return;
            }

            body.classList.toggle('admin-sidebar-collapsed');

            const isCollapsed =
                body.classList.contains('admin-sidebar-collapsed');

            sidebarToggle.setAttribute(
                'aria-expanded',
                String(!isCollapsed)
            );

            localStorage.setItem(
                'balmedAdminSidebar',
                isCollapsed ? 'collapsed' : 'expanded'
            );
        });
    }

    if (mobileToggle) {
        mobileToggle.addEventListener('click', function () {
            body.classList.toggle('admin-sidebar-mobile-open');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function () {
            body.classList.remove('admin-sidebar-mobile-open');
        });
    }

    window.addEventListener('resize', function () {
        if (window.innerWidth > 900) {
            body.classList.remove('admin-sidebar-mobile-open');
        }
    });
});
