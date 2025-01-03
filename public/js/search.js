document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('searchInput').addEventListener('keyup', filterTable);
    document.getElementById('filter-button').addEventListener('click', filterTable);

    function filterTable() {
        let table = document.querySelector('.cstm-table');
        let searchInput = document.getElementById('searchInput').value.toLowerCase();
        let fromDate = document.getElementById('from_date').value ? new Date(document.getElementById('from_date').value) : null;
        let toDate = document.getElementById('to_date').value ? new Date(document.getElementById('to_date').value) : null;
        let tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            let td = tr[i].getElementsByTagName('td');
            let showRow = true;

            // Search input filtering
            if ((td[0] && td[0].textContent.toLowerCase().indexOf(searchInput) === -1) &&
                (td[1] && td[1].textContent.toLowerCase().indexOf(searchInput) === -1)) {
                showRow = false;
            }

            if (showRow && (fromDate || toDate)) {
                let dateMatch = false;
                if (td[5]) {
                    let rowDateText = td[5].textContent.trim();
                    let rowDate = new Date(rowDateText);

                    if (!isNaN(rowDate.getTime())) {
                        if ((!fromDate || rowDate >= fromDate) && (!toDate || rowDate <= toDate)) {
                            dateMatch = true;
                        }
                    }
                }
                if (!dateMatch && td[10]) {
                    let rowDateText = td[10].textContent.trim();
                    let rowDate = new Date(rowDateText);

                    if (!isNaN(rowDate.getTime())) {
                        if ((!fromDate || rowDate >= fromDate) && (!toDate || rowDate <= toDate)) {
                            dateMatch = true;
                        }
                    }
                }
                showRow = dateMatch;
            }

            // Apply visibility
            tr[i].style.display = showRow ? '' : 'none';
        }
    }
});