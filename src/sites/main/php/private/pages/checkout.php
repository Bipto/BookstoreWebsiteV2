<?php

require_once '/var/www/shared/api.php';

$email = $_SESSION['email'] ?? '';
$email = urlencode($email);

$url = "/cart?email={$email}";

$response = API::get($url);

if (!$response->hasError()) {
    $data = $response->getData();
    $cartData = $data['cart'] ?? [];
}

?>

<form action="payment.php" method="post" id="checkoutForm">

    <h1>Checkout</h1>

    <section class="step">
        <h2>Review Order</h2>
        <hr>

        <?php

        $total = 0;

        foreach ($cartData as $item) {
            $cartItemId = $item['cart_item_id'] ?? -1;
            $quantity = $item['quantity'] ?? 0;
            $quantityDisplay = $quantity > 1 ? "- x{$quantity}" : "";
            $price = ($item['price'] ?? 0) * $quantity;
            $formattedPrice = number_format($price, 2);
            $priceDisplay = "(£{$formattedPrice})";
            $imagePath = '/img/' . urlencode($item['image_path']) ?? '';

            echo "
                <div class='checkout-item'>
                    <img src='{$imagePath}' class='checkout-image'>

                    <div class='checkout-info'>
                        <h3>{$item['title']} - {$item['author']}</h3>
                        {$quantityDisplay}
                        {$priceDisplay}
                    </div>
                </div>
                <hr>
            ";


            $total += $price;
        }

        echo "Order total: £{$total}<br>";

        ?>

        <button type="button" class="nextButton">Next</button>
    </section>

    <!-- Step 1 -->
    <section class="step">
        <h2>Contact Details</h2>
        <hr>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <button type="button" class="backButton">Back</button>
        <button type="button" class="nextButton">Next</button>
    </section>

    <!-- Step 2 -->
    <section class="step">
        <h2>Shipping Information</h2>
        <hr>

        <label for="houseNum">House Number:</label>
        <input type="text" id="houseNum" name="houseNum" required>

        <label for="street">Street:</label>
        <input type="text" id="street" name="street" required>

        <label for="town">Town/City:</label>
        <input type="text" id="town" name="town" required>

        <label for="postCode">Postcode:</label>
        <input type="text" id="postCode" name="postCode" required>

        <button type="button" class="backButton">Back</button>
        <button type="button" class="nextButton">Next</button>
    </section>

    <!-- Step 3 -->
    <section class="step">
        <h2>Payment</h2>
        <hr>

        <!-- Payment fields would go here -->

        <button type="button" class="backButton">Back</button>
        <button type="button" class="nextButton">Next</button>
    </section>

    <!-- Step 4 -->
    <section class="step">
        <h2>Review Order</h2>
        <hr>

        <p>Check your details before completing your order.</p>

        <button type="button" class="backButton">Back</button>
        <button type="submit">Place Order</button>
    </section>

</form>

<script>
    const steps = document.querySelectorAll(".step");
    const nextButtons = document.querySelectorAll(".nextButton");
    const backButtons = document.querySelectorAll(".backButton");

    let currentStep = 0;

    function validateStep() {
        const inputs = steps[currentStep].querySelectorAll("input");

        for (const input of inputs) {
            if (!input.checkValidity()) {
                input.reportValidity();
                return false;
            }
        }

        return true;
    }


    function showStep(step) {
        steps.forEach((section, index) => {
            section.style.display = index === step ? "block" : "none";
        });
    }

    nextButtons.forEach(button => {
        button.addEventListener("click", () => {
            if (!validateStep()) {
                return;
            }

            if (currentStep < steps.length - 1) {
                currentStep++;
                showStep(currentStep);
            }
        });
    });


    backButtons.forEach(button => {
        button.addEventListener("click", () => {
            if (currentStep > 0) {
                currentStep--;
                showStep(currentStep);
            }
        });
    });

    showStep(currentStep);
</script>