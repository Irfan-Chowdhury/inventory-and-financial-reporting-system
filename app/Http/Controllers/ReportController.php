<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use App\Services\ReportService;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.pages.reports.index');
    }

    public function getReportData(Request $request, ReportService $reportService)
    {
        $validated = $reportService->validateRequest($request);

        $sales = $reportService->getSalesData($validated['start_date'], $validated['end_date']);
        $totalExpenses = $reportService->calculateTotalExpenses($sales);
        $netProfit = $reportService->calculateNetProfit($sales, $totalExpenses);

        $summaryReport = $reportService->buildSummary($sales, $totalExpenses, $netProfit);

        $detailsReport = [];
        if ($validated['report_type'] === 'detailed') {
            $detailsReport = $reportService->buildDetailedReport($sales);
        }

        return view('admin.pages.reports.index', [
            'startDate'     => $validated['start_date'],
            'endDate'       => $validated['end_date'],
            'reportType'    => $validated['report_type'],
            'summaryReport' => $summaryReport,
            'detailsReport' => $detailsReport,
        ]);
    }
}
