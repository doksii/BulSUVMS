function toggleSidebar() {
    
    var sidebar = document.getElementById("sidebar");
    var content = document.getElementById("content");
    if (sidebar.classList.contains("hidden")) {
        sidebar.classList.remove("hidden");
        content.classList.remove("full");
    } else {
        sidebar.classList.add("hidden");
        content.classList.add("full");
    }
}

document.getElementById("students-menu").addEventListener("click", function() {
    var submenu = this.querySelector(".submenu");
    if (submenu.style.display === "block") {
        submenu.style.display = "none";
    } else {
        submenu.style.display = "block";
    }
});
