// filter.js
function filterProducts() {
    let checkedCategories = Array.from(document.querySelectorAll("input[type='checkbox']:checked")).map(cb => cb.parentElement.textContent.trim());

    let productItems = document.querySelectorAll('.product-item');

    productItems.forEach(item => {
        let productCategory = item.getAttribute('data-category');
        let productLength = item.getAttribute('data-length');
        let productSleeve = item.getAttribute('data-sleeve');

        if (checkedCategories.includes(productCategory) || checkedCategories.includes(productLength) || checkedCategories.includes(productSleeve)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}



document.addEventListener('DOMContentLoaded', function() {
    let input = document.getElementById('filterInput');

    input.addEventListener('keyup', function() {
        // Get the query
        let query = input.value.toLowerCase();

        // Get the table rows
        let table = document.getElementById('myTable');
        let rows = table.getElementsByTagName('tr');

        // Iterate through each row
        for (let i = 1; i < rows.length; i++) { // starting from 1 to skip the header
            let cells = rows[i].getElementsByTagName('td');
            let match = false;
            // Check each cell in the row
            for (let j = 0; j < cells.length; j++) {
                let cell = cells[j];
                if (cell.innerText.toLowerCase().indexOf(query) > -1) {
                    match = true;
                    break;
                }
            }
            if (match) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    });
});
