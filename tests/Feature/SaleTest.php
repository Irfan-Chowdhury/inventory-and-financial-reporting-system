<?php

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Services\AccountingService;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

// uses(\Illuminate\Foundation\Testing\RefreshDatabase::class)
//     ->beforeEach(function () {
//         $this->withoutMiddleware();
//     });

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->userAuthenticated();

    $this->product = Product::create([
        'name' => 'Test Product ',
        'purchase_price' => 100,
        'sell_price' => 200,
        'opening_stock' => 50,
        'current_stock' => 50,
    ]);

    $this->accountingService = Mockery::mock(AccountingService::class);
    $this->app->instance(AccountingService::class, $this->accountingService);
});

test('sale can be created and stock updates', function () {
    $this->accountingService->shouldReceive('recordSaleTransaction')->once();

    $response = $this->post(route('sales.store'), [
        'sale_date' => now()->format('Y-m-d'),
        'discount' => 50,
        'vat_percent' => 5,
        'vat_amount' => 97.5,
        'total_amount' => 2047.5,
        'paid_amount' => 1000,
        'due_amount' => 1047.5,
        'products' => [
            [
                'product_id' => $this->product->id,
                'quantity' => 10,
                'unit_price' => 200,
            ],
        ]
    ]);

    $response->assertRedirect(route('sales.index'));
    expect(Sale::count())->toBe(1)
        ->and(SaleItem::count())->toBe(1)
        ->and(Product::first()->current_stock)->toBe(40);
});


test('sale can be deleted', function () {
    $sale = Sale::factory()->create();

    $response = $this->delete(route('sales.destroy', $sale->id));

    $response->assertRedirect();
    $this->assertDatabaseMissing('sales', ['id' => $sale->id]);
});


test('sale fails if stock is insufficient', function () {
    $this->product->update(['current_stock' => 5]);

    $response = $this->post(route('sales.store'), [
        'sale_date' => now()->format('Y-m-d'),
        'discount' => 0,
        'vat_percent' => 0,
        'vat_amount' => 0,
        'total_amount' => 1000,
        'paid_amount' => 1000,
        'due_amount' => 0,
        'products' => [
            [
                'product_id' => $this->product->id,
                'quantity' => 10, // More than available
                'unit_price' => 100,
            ],
        ]
    ]);

    $response->assertSessionHasErrors(); // error handled by exception
    $this->assertDatabaseCount('sales', 1);
});


test('sales index page shows up', function () {
    $response = $this->get(route('sales.index'));
    $response->assertOk();
    $response->assertViewIs('admin.pages.sales.index');
});
