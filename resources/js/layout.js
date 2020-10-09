const sidebarToggle = document.querySelector('.sidebar-toggle');
const sidebarWrapper = document.getElementById('sidebar-wrapper');

let lastWidth = window.innerWidth;
window.addEventListener('resize', function (event) {
    // resize from small to wide
    if (lastWidth < 768 && window.innerWidth > lastWidth) {
        if (sidebarWrapper.classList.contains('toggled')) {
            sidebarWrapper.classList.remove('toggled');
        }
    }
    // resize from wide to small
    if (lastWidth >= 768 && window.innerWidth < lastWidth) {
        if (sidebarWrapper.classList.contains('toggled')) {
            sidebarWrapper.classList.remove('toggled');
        }
    }
    lastWidth = window.innerWidth;
});

sidebarToggle.addEventListener('click', function () {
    sidebarWrapper.classList.toggle('toggled');
});

sidebarWrapper.addEventListener('click', event => {
    const currentMenu = event.target;
    if (currentMenu.classList.contains('menu-toggle')) {
        event.stopPropagation();
        event.preventDefault();
        const hrefTarget = currentMenu.getAttribute('href');
        const submenuTarget = document.querySelector(hrefTarget);

        // toggle height
        if (submenuTarget.clientHeight > 0) {
            submenuTarget.style.height = '0';
        } else {
            const wrapper = submenuTarget.querySelector('ul');
            submenuTarget.style.height = wrapper.clientHeight + "px";
        }

        // toggle menu state
        currentMenu.classList.toggle('collapsed');
    }
});

// calculate element height that currently open (css transition can read initial height)
const openSubmenus = document.querySelectorAll('.sidebar-submenu:not(.submenu-hide)');
if (openSubmenus) {
    openSubmenus.forEach(submenuTarget => {
        const wrapper = submenuTarget.querySelector('ul');
        submenuTarget.style.height = wrapper.clientHeight + "px";
    })
}

// dropdown
window.addEventListener('click', function (event) {
    const currentDropdown = event.target;
    if (currentDropdown.classList.contains('dropdown-toggle')) {
        event.stopPropagation();
        event.preventDefault();

        currentDropdown
            .closest('.dropdown')
            .querySelector('.dropdown-menu')
            .classList.toggle("show");
    }
});

// Close the dropdown menu if the user clicks outside of it
window.addEventListener('click', function(event) {
    if (!event.target.matches('.dropdown-toggle')) {
        const dropdowns = document.querySelectorAll(".dropdown-menu");
        dropdowns.forEach(openDropdown => {
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        });
    }
});