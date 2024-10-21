document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('searchInput').addEventListener('keyup', filterTable);
});

function filterTable() {
    let input = document.getElementById('searchInput');
    let filter = input.value.toLowerCase();
    let table = document.querySelector('.custom-table');
    let tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        let td = tr[i].getElementsByTagName('td');
        let found = false;

        // Loop through all table cells (td) to search across multiple columns
        for (let j = 0; j < td.length; j++) {
            if (td[j] && td[j].textContent.toLowerCase().indexOf(filter) > -1) {
                found = true;
                break;
            }
        }

        tr[i].style.display = found ? '' : 'none';
    }
}
