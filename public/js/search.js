function filterTable() {
    let input = document.getElementById('searchInput');
    let filter = input.value.toLowerCase();
    let table = document.querySelector('.custom-table');
    let tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        let td = tr[i].getElementsByTagName('td');
        let found = false;

        for (let j = 0; j < td.length; j++) {
            if (td[j] && td[j].textContent.toLowerCase().indexOf(filter) > -1) {
                found = true;
                break;
            }
        }

        tr[i].style.display = found ? '' : 'none';
    }
}


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
            // Date filtering
            let tdDate = td[5];  
            if (tdDate) {
                let rowDate = new Date(tdDate.textContent.trim());
                if ((fromDate && rowDate < fromDate) || (toDate && rowDate > toDate)) {
                    showRow = false;
                }
            }
            
            tr[i].style.display = showRow ? '' : 'none';
        }
    }
    function generateInventoryReport() {
        let fromDate = document.getElementById('from_date').value;
        let toDate = document.getElementById('to_date').value;

        if (fromDate && toDate) {
            let params = new URLSearchParams({
                from_date: fromDate,
                to_date: toDate
            });
            window.location.href = "{{ route('inventoryReportPrint') }}?from_date=" + fromDate + "&to_date=" + toDate + "&download=true";
        } else {
            alert("Please select a valid date range before generating the report.");
        }
    }
    document.getElementById('plus-button').addEventListener('click', generateInventoryReport);
});