// ===== Orders =====

document.addEventListener("DOMContentLoaded", () => {

    const editButtons = document.querySelectorAll(".edit-order-btn");

    editButtons.forEach(button => {

        button.addEventListener("click", () => {

            document.getElementById("orderId").value =
                button.dataset.id;

            document.getElementById("orderStatus").value =
                button.dataset.status;

        });

    });

    const params = new URLSearchParams(window.location.search);
    const editId = params.get("edit");

    if (editId)
    {
        const button = document.querySelector(
            `.edit-order-btn[data-id="${editId}"]`
        );

        if (button)
        {
            button.click();
        }
    }

});

// ===== Products =====

const editProductButtons = document.querySelectorAll(".edit-product-btn");

editProductButtons.forEach(button => {

    button.addEventListener("click", () => {

        document.getElementById("editProductId").value = button.dataset.id;
        document.getElementById("editName").value = button.dataset.name;
        document.getElementById("editBrand").value = button.dataset.brand;
        document.getElementById("editCategory").value = button.dataset.category;
        document.getElementById("editDescription").value = button.dataset.description;
        document.getElementById("editPrice").value = button.dataset.price;
        document.getElementById("editStock").value = button.dataset.stock;

        document.getElementById("editPreview").src =
            "/img/products/" + button.dataset.image;

    });

});

document.getElementById("editImage").addEventListener("change", function () {

    if (this.files.length > 0)
    {
        document.getElementById("editPreview").src =
            URL.createObjectURL(this.files[0]);
    }

});

// ===== Delete Product =====

const deleteButtons =
    document.querySelectorAll(".delete-product-btn");

deleteButtons.forEach(button => {

    button.addEventListener("click", () => {

        document.getElementById("deleteProductId").value =
            button.dataset.id;

        document.getElementById("deleteProductName").textContent =
            button.dataset.name;

    });

});