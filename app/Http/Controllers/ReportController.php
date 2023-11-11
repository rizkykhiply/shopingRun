<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
 
 
class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $data = [];

        if ($startDate && $endDate) {
            $data = $this->getDataForDateRange($startDate, $endDate);
        }

        return view('reports.index', ['data' => $data]);
    }

    public function exportPDF(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $data = $this->getDataForDateRange($startDate, $endDate);

        $pdf = PDF::loadView('reports.export', ['data' => $data])->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf->download('customer_report.pdf');
    }

    private function getDataForDateRange($startDate, $endDate)
    {
        return DB::table('customers as c')
            ->select('c.nama as Nama', DB::raw('SUM(p.amount) as Total'), DB::raw('SUM(p.poin) as Poin'), DB::raw('MAX(p.created_at) as tanggal'))
            ->join('orders as o', 'c.id', '=', 'o.customer_id')
            ->join('payments as p', 'p.order_id', '=', 'o.id')
            ->whereBetween('p.created_at', [$startDate, $endDate])
            ->groupBy('c.id')
            ->get();
    }
}