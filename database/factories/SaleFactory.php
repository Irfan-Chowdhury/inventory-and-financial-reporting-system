<?php

namespace Database\Factories;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition(): array
    {
        $quantitySold = fake()->numberBetween(1, 10);
        $unitPrice = fake()->randomFloat(2, 100, 1000);
        $discount = fake()->randomFloat(2, 0, 100);
        $vatPercent = 5;

        $grossAmount = $quantitySold * $unitPrice;
        $netAfterDiscount = $grossAmount - $discount;
        $vatAmount = $netAfterDiscount * ($vatPercent / 100);
        $totalPayable = $netAfterDiscount + $vatAmount;
        $paidAmount = fake()->randomFloat(2, 0, $totalPayable);
        $dueAmount = $totalPayable - $paidAmount;

        return [
            'invoice_number' => 'INV-' . now()->format('Ymd') . '-' . Str::upper(Str::random(4)),
            'date' => now(),
            'total_amount' => $grossAmount,
            'discount' => $discount,
            'vat_percentage' => $vatPercent,
            'vat_amount' => $vatAmount,
            'paid_amount' => $paidAmount,
            'due_amount' => $dueAmount,
        ];
    }
}
