let hamMenuIcon = document.getElementById("ham-menu");
let navBar = document.getElementById("nav-bar");
let navLinks = navBar.querySelectorAll("li");

hamMenuIcon.addEventListener("click", () => {
    navBar.classList.toggle("active");
    hamMenuIcon.classList.toggle("fa-times");
});
navLinks.forEach((navLinks) => {
    navLinks.addEventListener("click", () => {
        navBar.classList.remove("active");
        hamMenuIcon.classList.toggle("fa-times");
    });
});

function display_message(type, message) {
    const jsmsg = document.getElementById('jsmsg');
    let div_class = "";
    if (type != '') {
        switch (type) {
            case 'success':
                div_class = 'alert alert-success';
                break;

            case 'error':
                div_class = 'alert alert-warning';
                break;
        }

        jsmsg.innerText = message;
        jsmsg.className = div_class;
        jsmsg.style.display = "block";
    }
}