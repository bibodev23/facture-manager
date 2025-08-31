document.addEventListener('turbo:load', () => {
    const burger = document.querySelector('.burger');
    const sidebar = document.querySelector('#sidebar');
    const close = document.querySelector('.close');
    const sidebarLinks = document.querySelectorAll('#sidebar ul li a');

    sidebarLinks.forEach(link => {
        link.addEventListener('click', () => {
            sidebar.classList.remove('active');
        });
    });
    burger.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });
    close.addEventListener('click', () => {
        sidebar.classList.remove('active');
    });
});