<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use PDF;
 
 
class ReportController extends Controller
{
    public function index(Request $request)
    {
        
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $searchCustomer = $request->input('search_cus');
        $data = [];

        if ($startDate && $endDate) {
            $data = $this->getDataForDateRange($startDate, $endDate, $searchCustomer);
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

        return view('reports.index', ['data' => $data]);
    }

    public function exportPDF(Request $request)
    {
        
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $searchCustomer = $request->input('search_cus');

        $data = $this->getDataForDateRange($startDate, $endDate, $searchCustomer);

        $pdf = PDF::loadView('reports.export', ['data' => $data])->setOptions(['defaultFont' => 'sans-serif']);

        return $pdf->download('customer_report.pdf');
    }

    private function getDataForDateRange($startDate, $endDate, $searchCustomer)
    {
            return DB::table('customers as c')
                    ->select('c.id as id', 'c.nama as Nama', DB::raw('SUM(p.amount) as Total'), DB::raw('SUM(p.poin) as Poin'), DB::raw('MAX(p.created_at) as tanggal'))
                    ->join('orders as o', 'c.id', '=', 'o.customer_id')
                    ->join('payments as p', 'p.order_id', '=', 'o.id')
                    ->whereDate('p.created_at', '>=', $startDate)
                    ->whereDate('p.created_at', '<=', $endDate)
                    ->where('c.nama', 'LIKE', '%' . $searchCustomer . '%')
                    ->groupBy('o.customer_id')
                    ->get();
    }
    public function showDetail($customerId) {
        $customerDetails = DB::table('order_items as oi')
                            ->select('c.id as id','c.nama as Nama', 'c.nik as Nik', 'c.alamat as Alamat', 'c.hp as hp', 'c.rek as rek', 'p.name as nameProduct', 'oi.price as price', 'oi.created_at as tanggal')
                            ->join('orders as o', 'oi.order_id', '=', 'o.id')
                            ->join('customers as c', 'o.customer_id', '=', 'c.id')
                            ->join('products as p', 'oi.product_id', '=', 'p.id')
                            ->where('c.id', $customerId)
                            ->get();
        if (request()->has('pdf')) {
            $pdf = PDF::loadView('reports.reportsDetail', ['customerDetails' => $customerDetails])->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->stream('customer_details.pdf');
        }
        $report = $customerId;
        return view('reports.detail', [
            'customerDetails' => $customerDetails,
            'customerId' => $customerId,
            'report' => $report, 
        ]);
    }
    // public function getReportTenant(){
    //     $reportTenant = DB::table()
    // }
    
}