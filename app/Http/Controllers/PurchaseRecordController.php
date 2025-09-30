<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\PurchaseRecord;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

        // Create purchase record
        PurchaseRecord::create([
            'item_id' => $request->item_id,
            'pack_qty' => $request->pack_qty,
            'purchase_rate' => $request->purchase_rate,
            'purchase_qty' => $request->purchase_qty,
            'sale_rate' => $request->sale_rate,
            'remarks' => $request->remarks,
            'batch_code' => $request->batch_code,
            'expiry' => $request->expiry,
        ]);

        // Update or create stock for this item + batch + expiry
        $stock = Stock::where('item_id', $item->id)
            ->where('batch_code', $request->batch_code)
            ->where('expiry', $request->expiry)
            ->first();

        if ($stock) {
            $stock->purchase_qty += $request->purchase_qty;
            $stock->in_stock += $request->purchase_qty;
            $stock->save();
        } else {
            Stock::create([
                'item' => $item->item_name,
                'item_id' => $item->id,
                'batch_code' => $request->batch_code,
                'expiry' => $request->expiry,
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
        $record = PurchaseRecord::with('item')->findOrFail($id);
        $items = Item::all();
        return response()->json([
            'record' => $record,
            'items' => $items
        ]);
    }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'item_id' => 'required|exists:items,id',
    //         'pack_qty' => 'required|numeric|min:1',
    //         'purchase_rate' => 'required|numeric|min:0',
    //         'purchase_qty' => 'required|numeric|min:1',
    //         'sale_rate' => 'required|numeric|min:0',
    //         'remarks' => 'nullable|string',
    //     ]);

    //     $record = PurchaseRecord::findOrFail($id);

    //     // ✅ Save old values BEFORE update
    //     $oldItem = $record->item;
    //     $oldPurchaseQty = $record->purchase_qty;

    //     // ✅ Update the record
    //     $record->update([
    //         'item_id' => $request->item_id,
    //         'pack_qty' => $request->pack_qty,
    //         'purchase_rate' => $request->purchase_rate,
    //         'purchase_qty' => $request->purchase_qty,
    //         'sale_rate' => $request->sale_rate,
    //         'batch_code' => $request->batch_code,
    //         'expiry' => $request->expiry,
    //         'remarks' => $request->remarks,
    //     ]);

    //     $newItem = Item::findOrFail($request->item_id);

    //     // ✅ Revert old stock
    //     $oldStock = Stock::where('item', $oldItem->item_name)->first();
    //     if ($oldStock) {
    //         $oldStock->purchase_qty -= $oldPurchaseQty;
    //         $oldStock->in_stock -= $oldPurchaseQty;
    //         $oldStock->save();
    //     }

    //     // ✅ Add new stock
    //     $newStock = Stock::where('item', $newItem->item_name)->first();
    //     if ($newStock) {
    //         $newStock->purchase_qty += $request->purchase_qty;
    //         $newStock->in_stock += $request->purchase_qty;
    //         $newStock->save();
    //     } else {
    //         Stock::create([
    //             'item' => $newItem->item_name,
    //             'purchase_qty' => $request->purchase_qty,
    //             'sale_qty' => 0,
    //             'foc' => 0,
    //             'in_stock' => $request->purchase_qty,
    //             'supplier_id' => null,
    //         ]);
    //     }

    //     return redirect()->route('purchase_record.index')->with('success', 'Purchase record updated successfully.');
    // }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'item_id' => 'required|exists:items,id',
    //         'pack_qty' => 'required|numeric|min:1',
    //         'purchase_rate' => 'required|numeric|min:0',
    //         'purchase_qty' => 'required|numeric|min:1',
    //         'sale_rate' => 'required|numeric|min:0',
    //         'remarks' => 'nullable|string',
    //         'created_at' => 'nullable|date',  // ✅ allow user to send created_at
    //     ]);

    //     $record = PurchaseRecord::findOrFail($id);

    //     // Save old values BEFORE update
    //     $oldItem = $record->item;
    //     $oldPurchaseQty = $record->purchase_qty;

    //     // Update purchase record
    //     $record->update([
    //         'item_id' => $request->item_id,
    //         'pack_qty' => $request->pack_qty,
    //         'purchase_rate' => $request->purchase_rate,
    //         'purchase_qty' => $request->purchase_qty,
    //         'sale_rate' => $request->sale_rate,
    //         'batch_code' => $request->batch_code,
    //         'expiry' => $request->expiry,
    //         'remarks' => $request->remarks,
    //         'created_at' => $request->created_at ?? $record->created_at,  // ✅ keep old if not sent
    //     ]);

    //     $newItem = Item::findOrFail($request->item_id);

    //     // Revert old stock
    //     $oldStock = Stock::where('item', $oldItem->item_name)->first();
    //     if ($oldStock) {
    //         $oldStock->purchase_qty -= $oldPurchaseQty;
    //         $oldStock->in_stock -= $oldPurchaseQty;
    //         $oldStock->save();
    //     }

    //     // Add to new stock
    //     $newStock = Stock::where('item', $newItem->item_name)->first();
    //     if ($newStock) {
    //         $newStock->purchase_qty += $request->purchase_qty;
    //         $newStock->in_stock += $request->purchase_qty;
    //         if ($request->created_at) {
    //             $newStock->created_at = $request->created_at;  // ✅ overwrite created_at
    //         }
    //         $newStock->save();
    //     } else {
    //         Stock::create([
    //             'item' => $newItem->item_name,
    //             'purchase_qty' => $request->purchase_qty,
    //             'sale_qty' => 0,
    //             'foc' => 0,
    //             'in_stock' => $request->purchase_qty,
    //             'supplier_id' => null,
    //             'created_at' => $request->created_at ?? now(),  // ✅ set created_at
    //         ]);
    //     }

    //     return redirect()->route('purchase_record.index')->with('success', 'Purchase record updated successfully.');
    // }

    public function update(Request $request, $id)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'pack_qty' => 'required|numeric|min:1',
            'purchase_rate' => 'required|numeric|min:0',
            'purchase_qty' => 'required|numeric|min:1',
            'sale_rate' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
            'created_at' => 'nullable|date',
            // Note: batch_code and expiry are used even if not required in validation
        ]);
    
        $record = PurchaseRecord::findOrFail($id);
    
        // 1. Capture OLD Key Values (Item ID, Batch, Expiry) and Quantity
        $oldItemId = $record->item_id;
        $oldPurchaseQty = $record->purchase_qty;
        $oldBatchCode = $record->batch_code;
        $oldExpiry = $record->expiry;
    
        // Determine the NEW batch/expiry codes for the update/stock lookup
        $newBatchCode = $request->batch_code ?? $oldBatchCode;
        $newExpiry = $request->expiry ?? $oldExpiry;
        $newPurchaseQty = $request->purchase_qty;
        $newItemId = $request->item_id;
    
        // 2. Update the Purchase Record
        $record->update([
            'item_id' => $newItemId,
            'pack_qty' => $request->pack_qty,
            'purchase_rate' => $request->purchase_rate,
            'purchase_qty' => $newPurchaseQty,
            'sale_rate' => $request->sale_rate,
            'batch_code' => $newBatchCode,
            'expiry' => $newExpiry,
            'remarks' => $request->remarks,
            'created_at' => $request->created_at ?? $record->created_at,
        ]);
    
        // --- Stock Management Logic ---
    
        // Scenario A: Item/Batch/Expiry DID NOT change (only quantity changed)
        if ($oldItemId === $newItemId && $oldBatchCode === $newBatchCode && $oldExpiry === $newExpiry) {
            
            $qtyDifference = $newPurchaseQty - $oldPurchaseQty;
            
            if ($qtyDifference !== 0) {
                $stock = Stock::where('item_id', $newItemId)
                    ->where('batch_code', $newBatchCode)
                    ->where('expiry', $newExpiry)
                    ->first();
    
                // Stock MUST exist here because it existed before the update
                if ($stock) {
                    $stock->purchase_qty += $qtyDifference;
                    $stock->in_stock += $qtyDifference;
                    $stock->save();
                }
            }
            
        } 
        // Scenario B: Item/Batch/Expiry DID change (full removal and full addition required)
        else {
            // Step 3: Revert stock from the OLD Item/Batch/Expiry combination
            $oldStock = Stock::where('item_id', $oldItemId)
                ->where('batch_code', $oldBatchCode)
                ->where('expiry', $oldExpiry)
                ->first();
    
            if ($oldStock) {
                $oldStock->purchase_qty -= $oldPurchaseQty;
                $oldStock->in_stock -= $oldPurchaseQty;
                $oldStock->save();
                
                // Clean up old stock record if empty
                if ($oldStock->purchase_qty <= 0 && $oldStock->sale_qty <= 0) {
                    $oldStock->delete();
                }
            }
    
            // Step 4: Add stock to the NEW Item/Batch/Expiry combination
            $newStock = Stock::where('item_id', $newItemId)
                ->where('batch_code', $newBatchCode)
                ->where('expiry', $newExpiry)
                ->first();
            
            $newItem = Item::findOrFail($newItemId); // Get Item details
    
            if ($newStock) {
                $newStock->purchase_qty += $newPurchaseQty;
                $newStock->in_stock += $newPurchaseQty;
                $newStock->item = $newItem->item_name; // Keep item name fresh
                $newStock->save();
            } else {
                // Create new stock record (matching store logic)
                Stock::create([
                    'item' => $newItem->item_name,
                    'item_id' => $newItemId,
                    'batch_code' => $newBatchCode,
                    'expiry' => $newExpiry,
                    'purchase_qty' => $newPurchaseQty,
                    'sale_qty' => 0,
                    'foc' => 0,
                    'in_stock' => $newPurchaseQty,
                    'supplier_id' => null,
                    'created_at' => $request->created_at ?? now(),
                ]);
            }
        }
        // End of Stock Management Logic
    
        return redirect()->route('purchase_record.index')->with('success', 'Purchase record updated successfully.');
    }

    public function destroy($id)
    {
        PurchaseRecord::findOrFail($id)->delete();
        return redirect()->route('purchase-records.index')->with('success', 'Purchase record deleted successfully.');
    }
}
