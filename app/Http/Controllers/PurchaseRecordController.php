<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Stock;
use App\Models\PurchaseRecord;
use Illuminate\Http\Request;

class PurchaseRecordController extends Controller
{
    public function index()
    {
        $purchaseRecords = PurchaseRecord::all();
        $items = Item::all();
        return view('purchase_record', compact('purchaseRecords', 'items'));
    }

    public function create()
    {
        $items = Item::all();
        return view('purchase_records.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'pack_qty' => 'required|numeric|min:1',
            'purchase_rate' => 'required|numeric|min:0',
            'purchase_qty' => 'required|numeric|min:1',
            'sale_rate' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);

        $item = Item::findOrFail($request->item_id);
        PurchaseRecord::create([
            'item_id' => $request->item_id,
            'pack_qty' => $request->pack_qty,
            'purchase_rate' => $request->purchase_rate,
            'purchase_qty' => $request->purchase_qty,
            'sale_rate' => $request->sale_rate,
            'remarks' => $request->remarks,
        ]);

        $stock = Stock::where('item_code', $item->item_code)
            ->where('item', $item->item_name)
            ->first();

        if ($stock) {
            $stock->purchase_qty += $request->purchase_qty;
            $stock->in_stock += $request->purchase_qty;
            $stock->save();
        } else {
            Stock::create([
                'item_code' => $item->item_code,
                'item' => $item->item_name,
                'purchase_qty' => $request->purchase_qty,
                'sale_qty' => 0,
                'foc' => 0,
                'in_stock' => $request->purchase_qty,
                'supplier_id' => null,
            ]);
        }

        return redirect()->route('purchase_record.index')->with('success', 'Purchase record added successfully.');
    }

    public function edit($id)
    {
        $record = PurchaseRecord::findOrFail($id);
        $items = Item::all();
        return view('purchase_records.edit', compact('record', 'items'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'pack_qty' => 'required|numeric|min:1',
            'purchase_rate' => 'required|numeric|min:0',
            'purchase_qty' => 'required|numeric|min:1',
            'sale_rate' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $record = PurchaseRecord::findOrFail($id);
        $record->update([
            'item_id' => $request->item_id,
            'date' => $request->date,
            'pack_qty' => $request->pack_qty,
            'purchase_rate' => $request->purchase_rate,
            'purchase_qty' => $request->purchase_qty,
            'sale_rate' => $request->sale_rate,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('purchase-records.index')->with('success', 'Purchase record updated successfully.');
    }

    public function destroy($id)
    {
        PurchaseRecord::findOrFail($id)->delete();
        return redirect()->route('purchase-records.index')->with('success', 'Purchase record deleted successfully.');
    }
}
