<?php

namespace App\Http\Controllers;

use App\Models\SaleReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleRecordController extends Controller
{
    public function index()
    {
        $saleReports = SaleReport::with('user')->latest()->get();
        // dd($saleReports);
        return view('sale_record', compact('saleReports'));
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
