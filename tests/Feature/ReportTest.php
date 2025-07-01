<?php


use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Http\Controllers\ReportController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\ReportService;


uses(RefreshDatabase::class);

beforeEach(function () {
    $this->userAuthenticated();
});


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
    $reportService = new ReportService();
    $sales = Sale::with('saleItems.product')->get();

    // $expenses = $this->callPrivate($controller, 'calculateTotalExpenses', [$sales]);
    // $this->withoutExceptionHandling();

    $expenses = $reportService->calculateTotalExpenses($sales);
    expect($expenses)->toBe(1000.0);
});

test('it calculates net profit correctly', function () {
    $reportService = new ReportService();
    $sales = Sale::with('saleItems.product')->get();
    $expenses = 1000;

    $profit = $reportService->calculateNetProfit($sales, $expenses);

    expect($profit)->toBe(900.0); // 2000 - 1000 - 100
});


test('it builds summary report correctly', function () {
    $reportService = new ReportService();
    $sales = Sale::with('saleItems.product')->get();
    $expenses = 1000;
    $profit = 900;

    $summary = $reportService->buildSummary($sales, $expenses, $profit);

    expect($summary->totalSales)->toBe('2,000.00');
    expect($summary->totalDiscount)->toBe('100.00');
    expect($summary->totalVat)->toBe('95.00');
    expect($summary->totalExpenses)->toBe('1,000.00');
    expect($summary->netProfit)->toBe('900.00');
});



test('it builds detailed report correctly', function () {
    $reportService = new ReportService();
    $sales = Sale::with('saleItems.product')->get();

    $details = $reportService->buildDetailedReport($sales);

    expect($details)->toHaveCount(1);
    expect($details[0]->expenses)->toBe('1,000.00');
    expect($details[0]->profit)->toBe('900.00');
});
