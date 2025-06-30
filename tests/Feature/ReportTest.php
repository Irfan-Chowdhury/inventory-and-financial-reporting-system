<?php

// test('example', function () {
//     $response = $this->get('/');

//     $response->assertStatus(200);
// });

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Http\Controllers\ReportController;

// beforeEach(function () {
//     // Create product
//     $product = $this->product = Product::create([
//         'name'           => 'Test Product',
//         'purchase_price' => 100,
//         'sell_price'     => 200,
//         'opening_stock'  => 50,
//         'current_stock'  => 40, // 50 - 10 sold
//     ]);


//     // 2️⃣ Sale Info
    // $quantitySold = 10;
    // $unitPrice    = $product->sell_price;       // 200
    // $discount     = 50;
    // $vatPercent   = 5;
    // $grossAmount  = $quantitySold * $unitPrice; // 2000
    // $netAfterDiscount = $grossAmount - $discount;
    // $vatAmount    = $netAfterDiscount * ($vatPercent / 100); // 97.5
    // $totalPayable = $netAfterDiscount + $vatAmount; // 2047.5
    // $paidAmount   = 1000;
    // $dueAmount    = $totalPayable - $paidAmount; // 1047.5


//     // 3️⃣ Create Sale
//     $sale = Sale::create([
//         'date'           => now(),
//         'invoice_number' => 'INV-' . strtoupper(Str::random(6)),
//         'total_amount'   => $grossAmount,
//         'discount'       => $discount,
//         'vat_percentage' => $vatPercent,
//         'vat_amount'     => $vatAmount,
//         'paid_amount'    => $paidAmount,
//         'due_amount'     => $dueAmount,
//     ]);

//     // 4️⃣ Create Sale Item
//     SaleItem::create([
//         'sale_id'     => $sale->id,
//         'product_id'  => $product->id,
//         'quantity'    => $quantitySold,
//         'unit_price'  => $unitPrice,
//     ]);
// });



beforeEach(function () {
    $this->product = Product::create([
        'name' => 'Test Product',
        'purchase_price' => 100,
        'sell_price' => 200,
        'opening_stock'  => 50,
        'current_stock'  => 40, // 50 - 10 sold
    ]);

    $quantitySold = 10;
    $unitPrice    = 200;
    $discount     = 50;
    $vatPercent   = 5;
    $grossAmount  = $quantitySold * $unitPrice; // 2000
    $netAfterDiscount = $grossAmount - $discount;
    $vatAmount    = $netAfterDiscount * ($vatPercent / 100); // 97.5
    $totalPayable = $netAfterDiscount + $vatAmount; // 2047.5
    $paidAmount   = 1000;
    $dueAmount    = $totalPayable - $paidAmount; // 1047.5



    // Create sale
    $this->sale = Sale::create([
        'date' => now(),
        'invoice_number' => 'INV-' . strtoupper(Str::random(6)),
        'total_amount' => 2000,
        'discount' => 100,
        'vat_amount' => 95,
        'vat_percentage' => 5,
        'paid_amount'    => $paidAmount,
        'due_amount'     => $dueAmount,

    ]);

    // Create sale item
    SaleItem::create([
        'sale_id' => $this->sale->id,
        'product_id' => $this->product->id,
        'quantity' => 10,
        'unit_price'  => $unitPrice,
    ]);
});

test('it calculates total expenses correctly', function () {
    $controller = new ReportController();
    $sales = Sale::with('saleItems.product')->get();

    // $expenses = $this->callPrivate($controller, 'calculateTotalExpenses', [$sales]);
    $this->withoutExceptionHandling();

    $expenses = $controller->calculateTotalExpenses($sales);
    expect($expenses)->toBe(960925.0); // 10 * 100
});
