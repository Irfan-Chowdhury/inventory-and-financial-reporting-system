<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Sale;

class ReportService
{

    public function validateRequest(Request $request): array
    {
        return $request->validate([
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'report_type'  => 'nullable|in:summary,detailed',
        ]);
    }

    public function getSalesData(string $startDate, string $endDate)
    {
        return Sale::with('saleItems.product')
            ->whereBetween('date', [$startDate, $endDate])
            ->get();
    }

    public function calculateTotalExpenses($sales): float
    {
        $totalExpenses = 0;
        foreach ($sales as $sale) {
            foreach ($sale->saleItems as $saleItem) {
                $totalExpenses += $saleItem->quantity * ($saleItem->product ? $saleItem->product->purchase_price : 0);
            }
        }

        return $totalExpenses;
    }

    public function calculateNetProfit($sales, float $expenses): float
    {
        return $sales->sum('total_amount') - $expenses - $sales->sum('discount');
    }

    public function buildSummary($sales, float $expenses, float $netProfit): object
    {
        return (object) [
            'totalSales'     => number_format((float)$sales->sum('total_amount'),2),
            'totalDiscount'  => number_format((float)$sales->sum('discount'),2),
            'totalVat'       => number_format((float)$sales->sum('vat_amount'),2),
            'totalExpenses'  => number_format((float)$expenses,2),
            'netProfit'      => number_format((float)$netProfit,2),
        ];
    }


    public function buildDetailedReport($sales)
    {
        return $sales->map(function ($sale) {
            $expenses = $sale->saleItems->sum(function ($item) {
                return $item->quantity * ($item->product ? $item->product->purchase_price : 0);
            });

            return (object) [
                'date'          => $sale->date->format('Y-m-d'),
                'invoiceNumber' => $sale->invoice_number,
                'sales'         => number_format((float)$sale->total_amount, 2),
                'discount'      => number_format((float)$sale->discount, 2),
                'vat'           => number_format((float)$sale->vat_amount, 2),
                'expenses'      => number_format($expenses, 2),
                'profit'        => number_format((float)$sale->total_amount - $expenses - $sale->discount, 2),
            ];
        });
    }
}