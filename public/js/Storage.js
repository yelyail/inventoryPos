function filterTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const filter = document.getElementById("filterSelect").value;

    // For Brands
    const brandRows = document.querySelectorAll("#dataTable tbody tr");
    brandRows.forEach(row => {
        const brandName = row.cells[0].textContent.toLowerCase();
        const brandMatch = brandName.includes(input);
        row.style.display = (filter === "brand_name" && brandMatch) ? "" : (filter === "all" && brandMatch) ? "" : "none";
    });

    // For Categories
    const categoryRows = document.querySelectorAll("#categoryTable tbody tr");
    categoryRows.forEach(row => {
        const categoryName = row.cells[0].textContent.toLowerCase();
        const categoryMatch = categoryName.includes(input);
        row.style.display = (filter === "category_name" && categoryMatch) ? "" : (filter === "all" && categoryMatch) ? "" : "none";
    });
}


//Update
function updaterB(brandid, brandname) {
    // Populate the form fields with the selected brand data
    document.getElementById('brandnameup').value = brandname; // Set the brand name input
    document.getElementById('brand_id_up').value = brandid;   // Set the hidden brand ID input
    document.getElementById('Updatebrand').showModal();    // Show the modal

}


function updateCategory(categoryId, categoryName) {
    document.getElementById('categorynameup').value = categoryName; // Set the category name
    document.getElementById('category_id_up').value = categoryId; // Set the category ID
    document.getElementById('UpdateCategory').showModal(); // Show the modal
}
