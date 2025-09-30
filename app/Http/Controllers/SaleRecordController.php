<?php

namespace App\Http\Controllers;

use App\Models\SaleReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SaleRecordController extends Controller
{
    // public function index(Request $request)
    // {
    //     $query = SaleReport::with('user')->latest();

    //     if ($request->filled('start_date') && $request->filled('end_date')) {
    //         $query->whereBetween('created_at', [
    //             $request->start_date . " 00:00:00",
    //             $request->end_date . " 23:59:59"
    //         ]);
    //     }

    //     $saleReports = $query->get();

    //     return view('sale_record', compact('saleReports'));
    // }

    public function index(Request $request)
{
    $query = SaleReport::with('user');

    // Date filter
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('created_at', [
            $request->start_date . " 00:00:00",
            $request->end_date . " 23:59:59"
        ]);
    }

    if ($request->filled('employee_id')) {
        $query->where('employee_id', $request->employee_id);
    }
    $saleReports = $query->latest()->get();
    $employees = User::where('usertype', 'employee')->get();
    return view('sale_record', compact('saleReports', 'employees'));
}


    public function store(Request $request)
    {
        $request->validate([
            'item_code'   => 'required|string|max:255',
            'item_name'   => 'required|string|max:255',
            'pack_size'   => 'nullable|string|max:255',
            'sale_qty'    => 'required|integer|min:1',
            'foc'         => 'nullable|integer|min:0',
            'sale_rate'   => 'required|numeric|min:0',
            'amount'      => 'required|numeric|min:0',
        ]);

        SaleReport::create([
            'user_id'     => Auth::id(),
            'item_code'   => $request->item_code,
            'item_name'   => $request->item_name,
            'pack_size'   => $request->pack_size,
            'sale_qty'    => $request->sale_qty,
            'foc'         => $request->foc ?? 0,
            'sale_rate'   => $request->sale_rate,
            'amount'      => $request->amount,
        ]);

        return redirect()->route('sale_reports.index')->with('success', 'Sale record added successfully.');
    }

    public function destroy($id)
    {
        $sale = SaleReport::findOrFail($id);
        $sale->delete();

        return redirect()->route('sale_reports.index')->with('success', 'Sale record deleted successfully.');
    }



}
