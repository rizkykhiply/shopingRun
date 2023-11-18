<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use PDF;
 
 
class ReportTenantController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $data = [];

        if ($startDate && $endDate) {
            $data = $this->getDataForDateRange($startDate, $endDate);
            $currentPage = Paginator::resolveCurrentPage('page');
        $perPage = 10; // Number of items per page

        $data = new LengthAwarePaginator(
            $data->forPage($currentPage, $perPage),
            $data->count(),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );
        }

        return view('reportsTenants.index', ['data' => $data]);
    }

    public function exportPDF(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $data = $this->getDataForDateRange($startDate, $endDate);

        $pdf = PDF::loadView('reportsTenants.export', ['data' => $data])->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf->download('customer_report.pdf');
    }

    private function getDataForDateRange($startDate, $endDate)
    {
        return DB::table('products as p')
        ->select('p.name as Nama', 'p.description as Ket', DB::raw('SUM(oi.price) as Total'))
        ->join('order_items as oi', 'oi.product_id', '=', 'p.id')
        ->whereBetween('oi.created_at', [$startDate, $endDate]) // Add this line for date filtering
        ->groupBy('Nama')
        ->orderByDesc('Total')
        ->get();
    }
    
    
}