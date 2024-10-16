function filterTable() {
    const filterValue = document.getElementById('filterSelect').value; // Get the selected filter value
    const searchValue = document.getElementById('searchInput').value.toLowerCase(); // Get the search input
    const rows = document.querySelectorAll('#dataTable tbody tr'); // Select all rows in the table body

    rows.forEach(row => {
        const serialNumber = row.cells[1].textContent.toLowerCase(); // Serial Number
        const model = row.cells[4].textContent.toLowerCase(); // Model
        const dateChecked = row.cells[6].textContent.trim(); // Date Checked value

        console.log('Row data:', { serialNumber, model, dateChecked }); // Debug log

        // Check if the row has the "Approve" button (if date_checked is not null)
        const hasApproveButton = dateChecked !== 'N/A' && dateChecked !== ''; // Ensure both N/A and empty are considered

        let showRow = false; // Initialize a flag to determine if the row should be shown

        // Apply search filter on serial number and model
        if (serialNumber.includes(searchValue) || model.includes(searchValue)) {
            // Apply dropdown filter
            if (filterValue === "All") {
                showRow = true; // Show all rows if search matches
            } else if (filterValue === "For Approval" && hasApproveButton) {
                showRow = true; // Show rows for approval if search matches
            } else if (filterValue === "For Inspection" && !hasApproveButton) {
                showRow = true; // Show rows for inspection if search matches
            }
        }

        // Show or hide the row based on combined search and filter criteria
        row.style.display = showRow ? '' : 'none'; // Show or hide the row
    });
}


function showpendingmodal(Inventory_ID, supplierId, serialNumber, dateArrived, dateInspected) {
    // Populate fields
    document.getElementById('suid' + Inventory_ID).value = supplierId;
    document.getElementById('senum' + Inventory_ID).value = serialNumber;
    document.getElementById('drive' + Inventory_ID).value = dateArrived;
    document.getElementById('dinsp' + Inventory_ID).value = dateInspected;

    // Open the modal
    document.getElementById('modaleditpending' + Inventory_ID).showModal();
}