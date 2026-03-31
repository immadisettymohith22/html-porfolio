(function() {
    // Immediate execution to prevent flash
    const savedTheme = localStorage.getItem('theme') || 'dark';
    if (savedTheme === 'light') {
        document.documentElement.classList.add('light-theme');
    } else {
        document.documentElement.classList.remove('light-theme');
    }

    // After DOM loaded, set up the toggle button
    window.addEventListener('DOMContentLoaded', () => {
        // Also apply to body for consistency if needed by CSS
        if (savedTheme === 'light') {
            document.body.classList.add('light-theme');
        }

        const toggleBtn = document.getElementById('theme-toggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                const isLight = document.documentElement.classList.toggle('light-theme');
                document.body.classList.toggle('light-theme');
                const theme = isLight ? 'light' : 'dark';
                localStorage.setItem('theme', theme);
            });
        }
    });
})();
