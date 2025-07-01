<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\post;
use function Pest\Laravel\get;

// uses(RefreshDatabase::class);
beforeEach(function () {
    $this->userAuthenticated();
});


test('a product can be created successfully', function () {
    $data = [
        'name' => 'LL2 DELL 540',
        'purchase_price' => 100,
        'sell_price' => 150,
        'opening_stock' => 50,
        'current_stock' => 40,
    ];

    $response = post(route('products.store'), $data);

    $response->assertRedirect(route('products.index'));
    $this->assertDatabaseHas('products', ['name' => 'LL2 DELL 540']);
});


test('product creation fails without a name', function () {
    $data = [
        'name' => '',
        'purchase_price' => 100,
        'sell_price' => 150,
        'opening_stock' => 50,
        'current_stock' => 40,
    ];

    $response = post(route('products.store'), $data);

    $response->assertSessionHasErrors(['name']);
});


test('product creation fails if sell_price is less than purchase_price', function () {
    $data = [
        'name' => 'Invalid Product',
        'purchase_price' => 200,
        'sell_price' => 150,
        'opening_stock' => 50,
        'current_stock' => 40,
    ];

    $response = post(route('products.store'), $data);

    $response->assertSessionHasErrors(['sell_price']);
});


test('product creation fails if current_stock exceeds opening_stock', function () {
    $data = [
        'name' => 'Invalid Stock',
        'purchase_price' => 100,
        'sell_price' => 150,
        'opening_stock' => 30,
        'current_stock' => 40,
    ];

    $response = post(route('products.store'), $data);

    $response->assertSessionHasErrors(['current_stock']);
});


test('product list page loads', function () {
    $response = get(route('products.index'));

    $response->assertStatus(200);
});


test('edit form loads for existing product', function () {
    $product = Product::factory()->create();

    $response = get(route('products.edit', $product));

    $response->assertStatus(200);
    $response->assertSee($product->name);
});


test('product can be deleted', function () {
    $product = Product::factory()->create();

    $response = $this->delete(route('products.destroy', $product));

    $response->assertRedirect();
    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);
});



