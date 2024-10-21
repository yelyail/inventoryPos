function toggleDropdown(dropdownId) {
    document.getElementById(dropdownId).classList.toggle("hidden");
}

window.onclick = function(event) {
    if (!event.target.matches('.block')) {
        var dropdowns = document.querySelectorAll('.absolute');
        dropdowns.forEach(function(dropdown) {
            dropdown.classList.add('hidden');
        });
    }
}
function toggleDD(myDropMenu) {
    document.getElementById(myDropMenu).classList.toggle("invisible");
}
window.onclick = function(event) {
    if (!event.target.matches('.drop-button')) {
        var dropdowns = document.getElementsByClassName("dropdownlist");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (!openDropdown.classList.contains('invisible')) {
                openDropdown.classList.add('invisible');
            }
        }
    }
}