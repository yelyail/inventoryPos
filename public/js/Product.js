function filterTable() {
    // Get input values
    let input = document.getElementById("searchInput").value.toLowerCase();
    let filter = document.getElementById("filterSelect").value;
    let table = document.getElementById("dataTable");
    let rows = table.getElementsByTagName("tr");

    let hasVisibleRows = false; // To check if there are any visible rows

    for (let i = 1; i < rows.length; i++) { // Start from 1 to skip the header row
        let cells = rows[i].getElementsByTagName("td");
        let showRow = false;

        // Check if the search input is empty, show all rows
        if (input === "") {
            showRow = true; // Show all rows if the input is empty
        } else {
            // Adjust column indices based on your table structure
            if (filter === "category_name" && cells[1]) { // Category column
                showRow = cells[1].innerText.toLowerCase().includes(input);
            } else if (filter === "brand_name" && cells[2]) { // Brand column
                showRow = cells[2].innerText.toLowerCase().includes(input);
            } else if (filter === "product_name" && cells[3]) { // Product Name column
                showRow = cells[3].innerText.toLowerCase().includes(input);
            }
        }

        // Update the row display based on the filtering
        rows[i].style.display = showRow ? "" : "none";
        if (showRow) hasVisibleRows = true; // If any row matches, set the flag
    }

    // Display a message if no rows are visible
    let noResultsRow = document.getElementById("noResultsRow");
    if (!hasVisibleRows) {
        if (!noResultsRow) {
            noResultsRow = document.createElement("tr");
            noResultsRow.id = "noResultsRow";
            noResultsRow.innerHTML = `<td colspan="5" style="text-align: center;">No matching results found.</td>`;
            table.querySelector("tbody").appendChild(noResultsRow);
        }
    } else if (noResultsRow) {
        // Remove the "No results" row if it exists and there are matching results
        noResultsRow.remove();
    }
}


//Full screen the picture
function openModalpic(imageSrc, description) {
    document.getElementById('fullImage').src = imageSrc;
    document.getElementById('imageDescription').innerText = description;
    document.getElementById('imageModal').showModal();
}

function closeModalpic() {
    document.getElementById('imageModal').close();
}

//Update
function showUpdateModal(productId, category, brand, productName, Description) {
    document.getElementById('categoryup' + productId).value = category;
    document.getElementById('brandNameup' + productId).value = brand;
    document.getElementById('productNameup' + productId).value = productName;
    document.getElementById('disup' + productId).value = Description;
    document.getElementById('Updatemodal' + productId).showModal();
}

function openModalreport(imageSrc, dateArrivalString, dateInspectionString) {
    // Set the image source in the modal
    document.getElementById('reportimg').src = imageSrc;
    // Open the modal
    document.getElementById('reportModal').showModal();
}

