// ===== Orders =====

document.addEventListener("DOMContentLoaded", () => {

    const editButtons =
        document.querySelectorAll(".edit-order-btn");

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

    // ===== Products =====

    const editProductButtons =
        document.querySelectorAll(".edit-product-btn");

    editProductButtons.forEach(button => {

        button.addEventListener("click", () => {

            document.getElementById("editProductId").value =
                button.dataset.id;

            document.getElementById("editName").value =
                button.dataset.name;

            document.getElementById("editBrand").value =
                button.dataset.brand;

            document.getElementById("editCategory").value =
                button.dataset.category;

            document.getElementById("editDescription").value =
                button.dataset.description;

            document.getElementById("editPrice").value =
                button.dataset.price;

            document.getElementById("editStock").value =
                button.dataset.stock;

            document.getElementById("editPreview").src =
                "/img/products/" + button.dataset.image;

        });

    });

    const editImage =
        document.getElementById("editImage");

    if (editImage)
    {
        editImage.addEventListener("change", function () {

            if (this.files.length > 0)
            {
                document.getElementById("editPreview").src =
                    URL.createObjectURL(this.files[0]);
            }

        });
    }

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

    // ===== Delete Category =====

    const deleteCategoryButtons =
        document.querySelectorAll(".delete-category-btn");

    deleteCategoryButtons.forEach(button => {

        button.addEventListener("click", () => {

            document.getElementById("deleteCategoryId").value =
                button.dataset.id;

            document.getElementById("deleteCategoryName").textContent =
                button.dataset.name;

            const count = Number(button.dataset.count);

            document.getElementById("deleteCategoryCount").textContent =
                count;

            const warning =
                document.getElementById("deleteCategoryWarning");

            const empty =
                document.getElementById("deleteCategoryEmpty");

            if (count === 0)
            {
                warning.classList.add("d-none");
                empty.classList.remove("d-none");
            }
            else
            {
                warning.classList.remove("d-none");
                empty.classList.add("d-none");
            }

        });

    });

    // ===== Edit User =====

    const editUserButtons =
        document.querySelectorAll(".edit-user-btn");

    editUserButtons.forEach(button => {

        button.addEventListener("click", () => {

            document.getElementById("editUserId").value =
                button.dataset.id;

            document.getElementById("editUserName").value =
                button.dataset.name;

            document.getElementById("editUserEmail").value =
                button.dataset.email;

        });

    });

    // ===== Delete User =====

    const deleteUserButtons =
        document.querySelectorAll(".delete-user-btn");

    deleteUserButtons.forEach(button => {

        button.addEventListener("click", () => {

            document.getElementById("deleteUserId").value =
                button.dataset.id;

            document.getElementById("deleteUserName").textContent =
                button.dataset.name;

        });

    });

});