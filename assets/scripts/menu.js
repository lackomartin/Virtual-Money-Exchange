function hamburgerMenu() {
    const icon = document.querySelector('.responsive-menu');
    const close = document.querySelector('.close-menu');

    icon.addEventListener('click', function() {
        document.querySelector('.responsive-menu-container').classList.add('show');
    });

    close.addEventListener('click', function() {
        document.querySelector('.responsive-menu-container').classList.remove('show');
    });
}

hamburgerMenu();
