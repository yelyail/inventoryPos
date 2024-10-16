function filterTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const filter = document.getElementById("filterSelect").value;

    // For Suppliers
    const supplierRows = document.querySelectorAll("#supplierTable tbody tr");
    supplierRows.forEach(row => {
        const supplierName = row.cells[0].textContent.toLowerCase(); // Adjust the index if needed
        const supplierMatch = supplierName.includes(input);
        row.style.display = (filter === "supplier_name" && supplierMatch) ? "" : (filter === "all" && supplierMatch) ? "" : "none";
    });

    // For Technicians
    const technicianRows = document.querySelectorAll("#technicianTable tbody tr");
    technicianRows.forEach(row => {
        const technicianName = row.cells[0].textContent.toLowerCase(); // Adjust the index if needed
        const technicianMatch = technicianName.includes(input);
        row.style.display = (filter === "technician_name" && technicianMatch) ? "" : (filter === "all" && technicianMatch) ? "" : "none";
    });
}




function updateSupplier(id, name, contact) {
    document.getElementById('supplier_id_up').value = id;
    document.getElementById('suppliernameup').value = name;
    document.getElementById('supplierconup').value = contact; // Populate contact field
    document.getElementById('UpdateSupplier').showModal();
}

function updateTechnician(id, name, position) {
    document.getElementById('technician_id_up').value = id;
    document.getElementById('techniciannameup').value = name;
    document.getElementById('position_level_up').value = position; // Populate position level
    document.getElementById('UpdateTechnician').showModal();
}