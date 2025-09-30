<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\SaleReport;
use App\Models\User;
use App\Models\Stock;
use App\Models\PurchaseRecord;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use Cookie;

class UserController extends Controller
{
    public function delete_user($id)
    {
        $client = new \GuzzleHttp\Client();
        $url = env('BASE_URL') . '/users/deladmin/' . $id;
        $request = $client->delete($url);
        session()->flash('error', 'User Deleted Successfully!');
        return redirect()->route('list_users');
    }

    public function list_users()
    {
        $data = User::where('usertype', 'customer')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.list_users', ['data' => $data]);
    }

    public function employees()
    {
        $data = User::where('usertype', 'employee')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.employees', ['data' => $data]);
    }

    public function customers()
    {
        $data = User::where('usertype', 'customer')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.customers', ['data' => $data]);
    }

    public function customers_balance()
    {
        $data = User::where('usertype', 'customer')
            ->whereHas('transactions')
            ->with('transactions')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.customers_balance', ['data' => $data]);
    }

    public function staffs()
    {
        $data = User::where('usertype', 'staff')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.staffs', ['data' => $data]);
    }

    public function staffs_balance()
    {
        $data = User::where('usertype', 'staff')
            ->whereHas('transactions')
            ->orderBy('created_at', 'desc')
            ->with('transactions')
            ->get();
        return view('admin.staff_balance', ['data' => $data]);
    }

    public function suppliers()
    {
        $data = User::where('usertype', 'supplier')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.suppliers', ['data' => $data]);
    }

    public function list_admin()
    {
        $data = User::where('usertype', 'admin')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.list_admin', ['data' => $data]);
    }

    public function update_status($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('danger', 'User not found.');
        }

        // Toggle status
        $user->status = $user->status == 1 ? 0 : 1;
        $user->save();

        $statusText = $user->status == 1 ? 'activated' : 'blocked';

        return redirect()->back()->with('success', "User has been {$statusText} successfully.");
    }

    public function add_user(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'email' => 'nullable|email|unique:users,email',
                'leyka_donor_phone' => 'nullable|string|max:20',
                'usertype' => 'required|in:admin,staff,customer,supplier,employee',
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Validation failed. Please check the form fields.');
            }

            $user = new User();
            $user->name = $request->firstName . ' ' . $request->lastName;
            $user->email = $request->email;
            $user->contact = $request->leyka_donor_phone;
            $user->usertype = $request->usertype;
            $user->address = $request->address;
            $user->area = $request->area;
            $user->password = Hash::make($request->password);
            $user->customer_id = $request->customer_id;
            $user->ntn_strn = $request->ntn_strn;
            $user->license_no = $request->license_no;
            $user->save();

            return redirect()->back()->with('success', 'User added successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    public function edit_user($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        return view('admin.edit_user', ['user' => $user]);
    }

    public function update_user(Request $request, $id)
    {
        try {
            $user = User::find($id);
            
            if (!$user) {
                return redirect()->back()->with('error', 'User not found.');
            }

            $validator = Validator::make($request->all(), [
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'email' => 'nullable|email|unique:users,email,' . $id,
                'leyka_donor_phone' => 'nullable|string|max:20',
                'usertype' => 'required|in:admin,staff,customer,supplier',
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Validation failed. Please check the form fields.');
            }

            // Update user data
            $user->name = $request->firstName . ' ' . $request->lastName;
            $user->email = $request->email;
            $user->contact = $request->leyka_donor_phone;
            $user->usertype = $request->usertype;
            $user->address = $request->address;
            $user->area = $request->area;
            $user->customer_id = $request->customer_id;
            $user->ntn_strn = $request->ntn_strn;
            $user->license_no = $request->license_no;
            
            // Only update password if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            
            $user->save();

            return redirect()->back()->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }

    public function add_employee(Request $request)
    {
        $phoneNum = '+' . $request->countrycode . ' ' . $request->leyka_donor_phone;
        $data = array(
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'phoneNum' => $phoneNum,
            'password' => $request->password,
            'status' => true
        );
        $url = env('BASE_URL') . '/users/add/employee';
        $client = new \GuzzleHttp\Client();
        $response = $client->post($url, [
            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            'body' => json_encode($data)
        ]);
        $response = $response->getBody()->getContents();
        $data = json_decode($response);
        if ($data->ResponseCode == 0) {
            return redirect()->route('employees')->with('error', $data->errors);
        } else {
            return redirect()->route('employees')->with('info', 'Employee Added Sucessfully');
        }
    }

    public function add_admin(Request $request)
    {
        $phoneNum = '+' . $request->countrycode . ' ' . $request->leyka_donor_phone;
        $data = array(
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'phoneNum' => $phoneNum,
            'password' => $request->password,
            'status' => true
        );

        $url = env('BASE_URL') . '/users/add';
        $client = new \GuzzleHttp\Client();
        $response = $client->post($url, [
            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            'body' => json_encode($data)
        ]);
        $response = $response->getBody()->getContents();
        $data = json_decode($response);

        if ($data->ResponseCode == 0) {
            return redirect()->route('list_admin')->with('error', $data->errors);
        } else {
            return redirect()->route('list_admin')->with('info', 'Admin Added Sucessfully');
        }
    }

    public function delete_admin($id)
    {
        $client = new \GuzzleHttp\Client();
        $url = env('BASE_URL') . '/users/deladmin/' . $id;
        $request = $client->delete($url);
        session()->flash('error', 'Deleted Successfully!');
        return redirect()->back();
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('homess');
        } else {
            return back()->with('error', 'Invalid credentials');
        }
    }

    public function change_password_post(Request $request)
    {
        $data = array(
            'oldPassword' => $request->old,
            'newPassword' => $request->neww,
        );
        $token = session()->get('token');
        $url = env('BASE_URL') . '/users/updatepassword';
        $client = new \GuzzleHttp\Client();
        $response = $client->put($url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'accessToken' => $token,
            ],
            'body' => json_encode($data)
        ]);
        return $response;
        $response = $response->getBody()->getContents();
        $data = json_decode($response);
        return $data->Response;
    }

    public function loginget()
    {
        return view('auth/login');
    }

    public function register(Request $request)
    {
        $data = array(
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'phoneNum' => $request->phoneNum,
            'password' => $request->password,
            'status' => true
        );
        $url = env('BASE_URL') . '/users/add';
        $client = new \GuzzleHttp\Client();
        $response = $client->post($url, [
            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            'body' => json_encode($data)
        ]);
        $response = $response->getBody()->getContents();
        $data = json_decode($response);
        return $data;
    }

    public function profile()
    {
        $data = Auth::user();
        return view('profile', ['data' => $data]);
    }

    public function update_profile(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Validate the incoming request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'leyka_donor_phone' => 'required|string|max:15',
            ]);

            // Update the user's profile
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'contact' => $validated['leyka_donor_phone'],
            ]);

            // Redirect back with success message
            return redirect()->route('profile')->with('info', 'Profile updated successfully');
        } else {
            return redirect()->route('login')->with('error', 'Please log in to update your profile.');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function create_invoice()
    {
        $items = Item::all();
        $users = User::where('userType', '!=', 'admin')->get();
        $stocks = Stock::all();
        $saleReports = SaleReport::orderBy('created_at', 'desc')->get();
        return view('create_invoice', compact('items', 'users', 'stocks', 'saleReports'));
    }


    // public function store_invoice(Request $request)
    // {
    //     try {
    //         // ✅ Step 1: Validate invoice date
    //         $validator = Validator::make($request->all(), [
    //             'invoice_date' => 'required|date',
    //         ]);

    //         if ($validator->fails()) {
    //             return redirect()->back()
    //                 ->withErrors($validator)
    //                 ->withInput()
    //                 ->with('error', 'Please select a valid invoice date.');
    //         }

    //         // ✅ Step 2: Handle customer (existing or new)
    //         if ($request->filled('user_id') && $request->input('user_id') !== 'new_user') {
    //             $userId = $request->input('user_id');
    //             $customer = User::find($userId);
    //         } else {
    //             $validator = Validator::make($request->all(), [
    //                 'name'    => 'required|string|max:255',
    //                 'contact' => 'nullable|string|max:20',
    //                 'email'   => 'nullable|email|unique:users,email',
    //             ]);

    //             if ($validator->fails()) {
    //                 return redirect()->back()
    //                     ->withErrors($validator)
    //                     ->withInput()
    //                     ->with('error', 'Validation failed. Please check the form fields.');
    //             }

    //             $prefix = strtoupper(substr(str_replace(' ', '', $request->name), 0, 2));
    //             $lastCustomer = User::where('customer_id', 'like', $prefix . '%')
    //                 ->orderBy('customer_id', 'desc')
    //                 ->first();

    //             $nextNumber = $lastCustomer
    //                 ? str_pad(((int) substr($lastCustomer->customer_id, -3)) + 1, 3, '0', STR_PAD_LEFT)
    //                 : "001";

    //             $customer = new User();
    //             $customer->name       = $request->name;
    //             $customer->email      = $request->email;
    //             $customer->customer_id   = $prefix . $nextNumber;
    //             $customer->contact    = $request->contact;
    //             $customer->ntn_strn   = $request->ntn_strn;
    //             $customer->license_no = $request->license_no;
    //             $customer->address    = $request->address;
    //             $customer->usertype   = 'customer';
    //             $customer->password   = Hash::make(Str::random(8));
    //             $customer->save();

    //             $userId = $customer->id;
    //         }

    //         // ✅ Step 3: Get invoice date
    //         $invoiceDate = $request->input('invoice_date', now()->format('Y-m-d'));
            
    //         // ✅ Step 4: Generate permanent invoice number starting from 330
    //         // Get the highest invoice number from all sale reports
    //         $maxInvoiceNumber = SaleReport::whereNotNull('invoice_number')
    //             ->max('invoice_number');
            
    //         // Start from 330 if no invoices exist, otherwise increment from highest
    //         $invoiceNumber = $maxInvoiceNumber ? $maxInvoiceNumber + 1 : 330;

    //         // ✅ Step 5: Loop through items
    //         $saleItems = [];
    //         $totalTax = 0;
    //         $totalAmount = 0;
    //         $previousBalance = $request->input('previous_balance', 0);

    //         foreach ($request->items as $rawItem) {
    //             $itemData = json_decode($rawItem, true);

    //             if (!$itemData) {
    //                 continue;
    //             }

    //             $itemName = $itemData['itemName'];
    //             $qty      = (int) $itemData['qty'];
    //             $foc      = (int) $itemData['foc'];
    //             $rate     = $itemData['rate'];
    //             $amount   = $itemData['amount'];
    //             $tax      = $itemData['tax'];
    //             $prevBal  = $itemData['prevBalance'];

    //             // ✅ Find Item by name instead of code
    //             $item = Item::where('item_name', $itemName)->first();

    //             if (!$item) {
    //                 return redirect()->back()->with('error', "Item not found: $itemName");
    //             }

    //             // ✅ Get latest purchase record
    //             $purchaseRecord = PurchaseRecord::where('item_id', $item->id)
    //                 ->latest()
    //                 ->first();

    //             $batch_code = $purchaseRecord->batch_code ?? null;
    //             $expiry = $purchaseRecord->expiry ?? null;

    //             // ✅ Check stock by name
    //             $stock = Stock::where('item', $itemName)->first();
    //             if (!$stock || $stock->in_stock < $qty) {
    //                 return redirect()->back()->with('error', "Insufficient stock for item: $itemName");
    //             }

    //             // ✅ Save sale report (no more item_code)
    //             $sale = SaleReport::create([
    //                 'user_id'    => $userId,
    //                 'invoice_number' => $invoiceNumber,
    //                 'item_name'  => $itemName,
    //                 'sale_qty'   => $qty,
    //                 'foc'        => $foc,
    //                 'sale_rate'  => $rate,
    //                 'amount'     => $amount,
    //                 'tax'        => $tax,
    //                 'sub_total'  => $amount + $tax,
    //                 'batch_code' => $batch_code,
    //                 'expiry'     => $expiry,
    //                 'pack_size'  => null,
    //             ]);

    //             // ✅ Update stock
    //             $stock->in_stock -= $qty;
    //             $stock->foc += $foc;
    //             $stock->save();

    //             $saleItems[] = $sale;
    //             $totalTax += $tax;
    //             $totalAmount += $amount;
    //         }

    //         // ✅ Step 6: Generate PDF
    //         $pdf = Pdf::loadView('invoice_pdf', [
    //             'invoice' => (object)[
    //                 'id' => $saleItems[0]->id ?? 0,
    //                 'user' => $customer,
    //                 'tax' => $totalTax,
    //                 'previous_balance' => $previousBalance,
    //                 'created_at' => \Carbon\Carbon::parse($invoiceDate),
    //                 'items' => collect($saleItems),
    //             ],
    //             'customer' => $customer,
    //             'invoice_no' => 'INV-' . Str::padLeft($invoiceNumber, 5, '0'),
    //             'date' => \Carbon\Carbon::parse($invoiceDate)->format('d-m-Y'),
    //         ]);

    //         $filename = $customer->name . '-' . $invoiceNumber . '.pdf';
    //         $pdfPath = 'invoices/' . $filename;

    //         Storage::makeDirectory('invoices');
    //         $pdf->save(storage_path('app/' . $pdfPath));

    //         // ✅ Update PDF path for all items
    //         foreach ($saleItems as $sale) {
    //             $sale->update(['pdf_path' => $pdfPath]);
    //         }

    //         return redirect()->back()->with('info', 'Invoice created and PDF generated.');
    //     } catch (\Exception $e) {
    //         Log::error('Invoice Error: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    //     }
    // }

    public function store_invoice(Request $request)
    {
        try {
            // ✅ Step 1: Validate invoice date
            $validator = Validator::make($request->all(), [
                'invoice_date' => 'required|date',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please select a valid invoice date.');
            }

            // ✅ Step 2: Handle customer (existing or new)
            if ($request->filled('user_id') && $request->input('user_id') !== 'new_user') {
                $userId = $request->input('user_id');
                $customer = User::find($userId);
            } else {
                // ... (your same customer creation logic here)
            }

            $invoiceDate = $request->input('invoice_date', now()->format('Y-m-d'));

            // ✅ Step 3: Check if this is EDIT or CREATE
            $invoiceNumber = null;
            $saleItems = [];
            $totalTax = 0;
            $totalAmount = 0;
            $previousBalance = $request->input('previous_balance', 0);

            if ($request->has('invoice_number')) {
                // -------------------- EDIT CASE --------------------
                $invoiceNumber = $request->input('invoice_number');

                // fetch old sales of this invoice
                $oldSales = SaleReport::where('invoice_number', $invoiceNumber)->get();

                foreach ($oldSales as $oldSale) {
                    // restore stock before deleting
                    $stock = Stock::where('item', $oldSale->item_name)->first();
                    if ($stock) {
                        $stock->in_stock += $oldSale->sale_qty;
                        $stock->foc -= $oldSale->foc;
                        $stock->save();
                    }
                    $oldSale->delete();
                }
            } else {
                // -------------------- CREATE CASE --------------------
                $maxInvoiceNumber = SaleReport::whereNotNull('invoice_number')
                    ->max('invoice_number');
                $invoiceNumber = $maxInvoiceNumber ? $maxInvoiceNumber + 1 : 330;
            }

            // ✅ Step 4: Loop through items (same logic for create/edit)
            foreach ($request->items as $rawItem) {
                $itemData = json_decode($rawItem, true);
                if (!$itemData) continue;

                $itemName = $itemData['itemName'];
                $qty      = (int) $itemData['qty'];
                $foc      = (int) $itemData['foc'];
                $rate     = $itemData['rate'];
                $amount   = $itemData['amount'];
                $tax      = $itemData['tax'];
                $prevBal  = $itemData['prevBalance'];

                $item = Item::where('item_name', $itemName)->first();
                if (!$item) {
                    return redirect()->back()->with('error', "Item not found: $itemName");
                }

                $purchaseRecord = PurchaseRecord::where('item_id', $item->id)->latest()->first();
                $batch_code = $purchaseRecord->batch_code ?? null;
                $expiry = $purchaseRecord->expiry ?? null;

                $stock = Stock::where('item', $itemName)->first();
                if (!$stock || $stock->in_stock < $qty) {
                    return redirect()->back()->with('error', "Insufficient stock for item: $itemName");
                }

                // Save new sale record
                $sale = SaleReport::create([
                    'user_id'    => $userId,
                    'invoice_number' => $invoiceNumber,
                    'item_name'  => $itemName,
                    'sale_qty'   => $qty,
                    'foc'        => $foc,
                    'sale_rate'  => $rate,
                    'amount'     => $amount,
                    'tax'        => $tax,
                    'sub_total'  => $amount + $tax,
                    'batch_code' => $batch_code,
                    'expiry'     => $expiry,
                    'pack_size'  => null,
                ]);

                // Update stock
                $stock->in_stock -= $qty;
                $stock->foc += $foc;
                $stock->save();

                $saleItems[] = $sale;
                $totalTax += $tax;
                $totalAmount += $amount;
            }

            // ✅ Step 5: Generate PDF
            $pdf = Pdf::loadView('invoice_pdf', [
                'invoice' => (object)[
                    'id' => $saleItems[0]->id ?? 0,
                    'user' => $customer,
                    'tax' => $totalTax,
                    'previous_balance' => $previousBalance,
                    'created_at' => \Carbon\Carbon::parse($invoiceDate),
                    'items' => collect($saleItems),
                ],
                'customer' => $customer,
                'invoice_no' => 'INV-' . Str::padLeft($invoiceNumber, 5, '0'),
                'date' => \Carbon\Carbon::parse($invoiceDate)->format('d-m-Y'),
            ]);

            $filename = $customer->name . '-' . $invoiceNumber . '.pdf';
            $pdfPath = 'invoices/' . $filename;

            Storage::makeDirectory('invoices');
            $pdf->save(storage_path('app/' . $pdfPath));

            foreach ($saleItems as $sale) {
                $sale->update(['pdf_path' => $pdfPath]);
            }

            return redirect()->back()->with('info', $request->has('invoice_number')
                ? 'Invoice updated successfully.'
                : 'Invoice created and PDF generated.');
        } catch (\Exception $e) {
            Log::error('Invoice Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }


    public function items()
    {
        $data = Item::all();
        return view('items', ['items' => $data]);
    }

    public function store_items(Request $request)
{
    // ✅ Validate input
    $validated = $request->validate([
        'item_name' => 'required|string|max:255',
        'status'    => 'required|in:active,inactive',
    ]);

    try {
        $item = new Item();
        $item->item_name = $validated['item_name'];
        $item->status    = $validated['status'];
        $item->save();

        return redirect()->route('items')
                         ->with('info', 'Item added successfully');

    } catch (\Exception $e) {
        // Log error for debugging
        \Log::error('Item store failed: '.$e->getMessage());

        return redirect()->back()
                         ->withInput()
                         ->with('error', 'Failed to add item. Please try again.');
    }
}

}
