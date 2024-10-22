function toggleDropdown(dropdownId) {
    const dropdowns = document.querySelectorAll('ul[id^="dropdown"]');
        
        // Loop through dropdowns and hide them
        dropdowns.forEach(dropdown => {
            if (dropdown.id !== dropdownId) {
                dropdown.classList.add('hidden'); // Hide others
            }
        });
        
        // Toggle the clicked dropdown
        const dropdown = document.getElementById(dropdownId);
        dropdown.classList.toggle('hidden'); // Show/hide the selected one
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