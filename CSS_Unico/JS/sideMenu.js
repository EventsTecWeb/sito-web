document.addEventListener('DOMContentLoaded', (event) => {
    document.querySelector('.bottoneHamburger').addEventListener('click', function() {
        document.getElementById('sideMenu').style.width = '250px';
        document.getElementById('overlay').style.width = '100%';
    });

    document.querySelector('.closebtn').addEventListener('click', function() {
        document.getElementById('sideMenu').style.width = '0';
        document.getElementById('overlay').style.width = '0';
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            document.getElementById('sideMenu').style.width = '0';
            document.getElementById('overlay').style.width = '0';
        }
    });
});