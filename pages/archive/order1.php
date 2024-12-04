<?php
function calculateDeliveryDate($purchaseDate)
{
    // Convert purchase date to DateTime object
    $date = new DateTime($purchaseDate);
    $dayOfWeek = $date->format('N'); // 1 = Monday, ..., 7 = Sunday

    // If the day is Saturday
    if ($dayOfWeek == 6) {
        $date->modify('+1 day');
    }

    // Add 3 weekdays
    $deliveryDays = 0;
    while ($deliveryDays < 3) {
        $date->modify('+1 day');
        if ($date->format('N') < 6) { // Only count weekdays (1-5)
            $deliveryDays++;
        }
    }

    return $date->format('l, F j, Y'); // Return formatted date
}

// Test Cases
$testDates = [
    '2024-11-30', // Saturday
    '2024-12-01', // Sunday
    '2024-12-02', // Monday
    '2024-12-05', // Th
    '2024-12-06', // Friday
    '2024-12-07', // Saturday
];

foreach ($testDates as $purchaseDate) {
    echo "Purchase Date: $purchaseDate => Estimated Delivery Date: " . calculateDeliveryDate($purchaseDate) . "<br>";
}
