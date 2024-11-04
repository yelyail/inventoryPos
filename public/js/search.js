document.addEventListener('DOMContentLoaded', function() {
    const salesReportPrintUrl = "{{ route('salesReportPrint') }}"; // Ensure the URL is set

    document.getElementById('searchInput').addEventListener('keyup', filterTable);
    document.getElementById('filter-button').addEventListener('click', filterTable);
    
    // Add event listener for report generation button
    document.getElementById('sales-button').addEventListener('click', generateSalesReport);

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
    function generateSalesReport() {
        let fromDate = document.getElementById('from_date').value;
        let toDate = document.getElementById('to_date').value;

        if (fromDate && toDate) {
            let params = new URLSearchParams({
                from_date: fromDate,
                to_date: toDate,
                download: true 
            });

            fetch("{{ route('salesReportPrint') }}?" + params.toString())
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            if (data.error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'No Records Found',
                                    text: data.error,
                                    confirmButtonText: 'Okay'
                                });
                            }
                        });
                    } else {
                        window.location.href = "{{ route('salesReportPrint') }}?" + params.toString();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while generating the report. Please try again later.',
                        confirmButtonText: 'Okay'
                    });
                });
        } else {
            // Show SweetAlert for invalid date range
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Date Range',
                text: 'Please select a valid date range before generating the report.',
                confirmButtonText: 'Okay'
            });
        }
    }
});


function generateInventoryReport() {
    let fromDate = document.getElementById('from_date').value;
    let toDate = document.getElementById('to_date').value;

    if (fromDate && toDate) {
        let params = new URLSearchParams({
            from_date: fromDate,
            to_date: toDate
        });
        let reportUrl = "{{ route('inventoryReportPrint') }}?" + params.toString();
        window.location.href = reportUrl;
    } else {
        alert("Please select a valid date range before generating the report.");
    }
}
