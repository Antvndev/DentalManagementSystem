// JSScripts/TabSwitcher.js
function openTab(evt, tabName) {
    // Hide all tab contents
    const tabContent = document.getElementsByClassName("Tab-Content");
    for (let i = 0; i < tabContent.length; i++) {
        tabContent[i].style.display = "none";
    }

    // Remove "active" class from all tab links
    const tabLinks = document.getElementsByClassName("Tab-Link");
    for (let i = 0; i < tabLinks.length; i++) {
        tabLinks[i].classList.remove("active");
    }

    // Show current tab + mark as active
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.classList.add("active");
}

// Open default tab on load
document.addEventListener("DOMContentLoaded", function () {
    const firstTab = document.querySelector(".Tab-Link");
    if (firstTab) firstTab.click();
});
