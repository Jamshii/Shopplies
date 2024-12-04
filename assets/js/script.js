// Show Add Product Form
document.addEventListener("DOMContentLoaded", function () {
    const showFormButton = document.getElementById("showAddProductForm");
    const addProductForm = document.getElementById("addProductForm");
    const cancelFormButton = document.getElementById("cancelAddProductForm");

    if (showFormButton && addProductForm && cancelFormButton) {
        showFormButton.addEventListener("click", function () {
            addProductForm.style.display = "block";
            showFormButton.style.display = "none";
        });

        cancelFormButton.addEventListener("click", function () {
            addProductForm.style.display = "none";
            showFormButton.style.display = "inline-block";
        });
    }
});

// Update Quantity Logic and AJAX
$(document).on("change", ".quantity-input", function () {
    const cartId = $(this).data("cart-id");
    let quantity = parseInt($(this).val(), 10);
    const price = parseFloat($(this).data("price"));

    // Ensure quantity is at least 1
    if (quantity < 1) {
        quantity = 1;
        $(this).val(quantity);
    }

    // Calculate new subtotal
    const newSubtotal = quantity * price;
    $(this)
        .closest("tr")
        .find(".subtotal")
        .text(`₱${newSubtotal.toFixed(2)}`);

    // Update total
    let total = 0;
    $(".quantity-input").each(function () {
        const rowQuantity = parseInt($(this).val(), 10);
        const rowPrice = parseFloat($(this).data("price"));
        total += rowQuantity * rowPrice;
    });
    $("#total").text(`${total.toFixed(2)}`);

    // Update database via AJAX
    $.ajax({
        url: "update_cart.php",
        method: "POST",
        data: { cart_id: cartId, quantity: quantity },
        success: function (response) {
            console.log("Update Response:", response);
        },
        error: function (xhr, status, error) {
            console.error("Error updating cart:", error);
        },
    });
});
