<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::orderBy('created_at', 'desc')->get();
        $suppliers = User::where('userType', 'supplier')->get(); 
        return view('stock', compact('stocks','suppliers'));
    }

    public function create()
    {
        return view('stock.create');
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'stock_type' => 'required|in:fuel,mobil_oil',
                'stock_item_id' => 'required|integer',
                'supplier_id' => 'required|exists:users,id',
                'quantity' => 'required|numeric|min:1',
                'total_amount' => 'required|numeric|min:0',
                'paid_amount' => 'required|numeric|min:0|lte:total_amount',
            ]);

            $remaining_amount = $request->total_amount - $request->paid_amount;

            // if ($request->stock_type === 'fuel') {
            //     $stockType = App\Models\Fuel::class;
            // } elseif ($request->stock_type === 'mobil_oil') {
            //     $stockType = App\Models\MobilOil::class;
            // }

            Stock::create([
                'stock_type' => $request->stock_type,
                'stock_item_id' => $request->stock_item_id,
                'supplier_id' => $request->supplier_id,
                'quantity' => $request->quantity,
                'sale_price' => $request->sale_price,
                'total_amount' => $request->total_amount,
                'paid_amount' => $request->paid_amount,
                'remaining_amount' => $remaining_amount,
            ]);

            if ($request->stock_type === 'mobil_oil') {
                $mobilOil = MobilOil::find($request->stock_item_id);
                $mobilOil->inventory += $request->quantity;
                $mobilOil->sale_price = $request->sale_price;
                $mobilOil->save();
            }

            if ($request->stock_type === 'fuel') {
                $fuel = Fuel::find($request->stock_item_id);
                $fuel->liters += $request->quantity;
                $fuel->price = $request->sale_price;
                $fuel->save();
            }

            return redirect()->route('stock.index')->with('success', 'Stock added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add stock: ' . $e->getMessage());
        }
    }


    public function edit($id)
    {
        $stock = Stock::findOrFail($id);
        $suppliers = User::all();
        $fuelTypes = FuelType::all();
        $mobilOils = MobilOil::all();

        return view('stock.edit', compact('stock', 'suppliers', 'fuelTypes', 'mobilOils'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'stock_type' => 'required|in:fuel,mobil_oil',
            'stock_name_id' => 'required|integer',
            'supplier_id' => 'required|exists:users,id',
            'quantity' => 'required|integer|min:1',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0|lte:total_amount',
        ]);

        $stock = Stock::findOrFail($id);
        $remaining_amount = $request->total_amount - $request->paid_amount;

        $stock->update([
            'stock_type' => $request->stock_type,
            'stock_name_id' => $request->stock_name_id,
            'supplier_id' => $request->supplier_id,
            'quantity' => $request->quantity,
            'total_amount' => $request->total_amount,
            'paid_amount' => $request->paid_amount,
            'remaining_amount' => $remaining_amount,
        ]);

        return redirect()->route('stocks.index')->with('success', 'Stock updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $stock = Stock::findOrFail($id);
            $stock->delete();
            return redirect()->route('stocks.index')->with('success', 'Stock deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('stocks.index')->with('error', 'Error deleting stock: ' . $e->getMessage());
        }
    }
}
