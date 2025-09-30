<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Role;
use App\Models\SaleReport;
use App\Models\UserPayment;
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

        $roles = Role::all();

        return view('admin.employees', [
            'data' => $data,
            'roles' => $roles
        ]);
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
            $user->role_id = $request->role_id;
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
            'password' => 'required',
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
        $purchaseRecord = PurchaseRecord::orderBy('created_at', 'desc')->get();
        return view('create_invoice', compact('items', 'users', 'stocks', 'saleReports' , 'purchaseRecord'));
    }


    public function role_store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        Role::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Role added successfully!');
    }


    // public function store_invoice(Request $request)
    // {
    //     try {
    //         // ---------------- Step 1: Validate invoice date ----------------
    //         $validator = Validator::make($request->all(), [
    //             'invoice_date' => 'required|date',
    //         ]);

    //         if ($validator->fails()) {
    //             return redirect()->back()
    //                 ->withErrors($validator)
    //                 ->withInput()
    //                 ->with('error', 'Please select a valid invoice date.');
    //         }

    //         // ---------------- Step 2: Handle customer ----------------
    //         if ($request->filled('user_id') && $request->input('user_id') !== 'new_user') {
    //             $userId = $request->input('user_id');
    //             $customer = User::find($userId);
    //         } else {
    //             // Handle new customer creation if needed
    //         }

    //         $invoiceDate = $request->input('invoice_date', now()->format('Y-m-d'));

    //         // ---------------- Step 3: Determine invoice number ----------------
    //         $invoiceNumber = $request->filled('invoice_number')
    //             ? $request->input('invoice_number')
    //             : (SaleReport::whereNotNull('invoice_number')->max('invoice_number') + 1 ?? 330);

    //         $saleItems = [];
    //         $totalTax = 0;
    //         $totalAmount = 0;
    //         $previousBalance = $request->input('previous_balance', 0);

    //         // ---------------- Step 4: Handle EDIT invoice ----------------
    //         if ($request->filled('invoice_number')) {
    //             $oldSales = SaleReport::where('invoice_number', $invoiceNumber)->get();
    //             foreach ($oldSales as $oldSale) {
    //                 // Restore stock for the exact batch used
    //                 $stock = Stock::where('item', $oldSale->item_name)
    //                     ->where('batch_code', $oldSale->batch_code)
    //                     ->where('expiry', $oldSale->expiry)
    //                     ->first();
    //                 if ($stock) {
    //                     $stock->in_stock += $oldSale->sale_qty;
    //                     $stock->foc -= $oldSale->foc;
    //                     $stock->save();
    //                 }
    //                 $oldSale->delete();
    //             }
    //         }

    //         // ---------------- Step 5: Process items ----------------
    //         foreach ($request->items as $rawItem) {
    //             $itemData = is_string($rawItem) ? json_decode($rawItem, true) : $rawItem;
    //             if (!$itemData) continue;

    //             $itemName = $itemData['itemName'];
    //             $qty      = (int) $itemData['qty'];
    //             $foc      = (int) $itemData['foc'];
    //             $rate     = $itemData['rate'];
    //             $tax      = $itemData['tax'];

    //             $item = Item::where('item_name', $itemName)->first();
    //             if (!$item) {
    //                 return redirect()->back()->with('error', "Item not found: $itemName");
    //             }

    //             // Get available stocks for this item in FIFO order (oldest first)
    //             $stocks = Stock::where('item', $itemName)
    //                 ->where('in_stock', '>', 0)
    //                 ->orderBy('created_at', 'asc')
    //                 ->get();

    //             $totalInStock = $stocks->sum('in_stock');
    //             if ($totalInStock < $qty) {
    //                 return redirect()->back()->with('error', "Insufficient stock for item: $itemName. Available: $totalInStock");
    //             }

    //             $remainingQty = $qty;

    //             // Consume stocks batch by batch
    //             foreach ($stocks as $stock) {
    //                 if ($remainingQty <= 0) break;

    //                 $deductQty = min($stock->in_stock, $remainingQty);

    //                 // Create sale report for this batch
    //                 $sale = SaleReport::create([
    //                     'user_id'        => $userId,
    //                     'employee_id'    => $request->employee_id,
    //                     'invoice_number' => $invoiceNumber,
    //                     'item_name'      => $itemName,
    //                     'sale_qty'       => $deductQty,
    //                     'foc'            => $foc, // optional: adjust per batch if needed
    //                     'sale_rate'      => $rate,
    //                     'amount'         => $deductQty * $rate,
    //                     'tax'            => $deductQty * $tax,
    //                     'sub_total'      => ($deductQty * $rate) + ($deductQty * $tax),
    //                     'batch_code'     => $stock->batch_code,
    //                     'expiry'         => $stock->expiry,
    //                     'pack_size'      => null,
    //                 ]);

    //                 // Deduct from stock
    //                 $stock->in_stock -= $deductQty;
    //                 $stock->foc += $foc; // optional
    //                 $stock->save();

    //                 $saleItems[] = $sale;
    //                 $totalTax += $deductQty * $tax;
    //                 $totalAmount += $deductQty * $rate;

    //                 $remainingQty -= $deductQty;
    //             }
    //         }

    //         // ---------------- Step 6: Generate PDF ----------------
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

    //         foreach ($saleItems as $sale) {
    //             $sale->update(['pdf_path' => $pdfPath]);
    //         }

    //         return redirect()->back()->with('info', $request->has('invoice_number')
    //             ? 'Invoice updated successfully.'
    //             : 'Invoice created and PDF generated.');

    //     } catch (\Exception $e) {
    //         Log::error('Invoice Error: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    //     }
    // }

    public function store_invoice(Request $request)
    {
        try {
            // ---------------- Step 1: Validate invoice date ----------------
            $validator = Validator::make($request->all(), [
                'invoice_date' => 'required|date',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please select a valid invoice date.');
            }

            // ---------------- Step 2: Handle customer ----------------
            if ($request->filled('user_id') && $request->input('user_id') !== 'new_user') {
                $userId = $request->input('user_id');
                $customer = User::find($userId);
            } else {
                // Handle new customer creation if needed
            }

            $invoiceDate = $request->input('invoice_date', now()->format('Y-m-d'));

            // ---------------- Step 3: Determine invoice number ----------------
            $invoiceNumber = $request->filled('invoice_number')
                ? $request->input('invoice_number')
                : (SaleReport::whereNotNull('invoice_number')->max('invoice_number') + 1 ?? 330);

            $saleItems = [];
            $totalTax = 0;
            $totalAmount = 0;
            $previousBalance = $request->input('previous_balance', 0);

            // ---------------- Step 4: Handle EDIT invoice ----------------
            $oldSaleBatches = []; // Keep track of batch codes from old sales
            if ($request->filled('invoice_number')) {
                $oldSales = SaleReport::where('invoice_number', $invoiceNumber)->get();

                foreach ($oldSales as $oldSale) {
                    // Store old batch info
                    $oldSaleBatches[] = [
                        'batch_code' => $oldSale->batch_code,
                        'expiry' => $oldSale->expiry,
                        'sale_qty' => $oldSale->sale_qty,
                        'foc' => $oldSale->foc
                    ];

                    // Restore stock for this exact batch
                    $stock = Stock::where('item', $oldSale->item_name)
                        ->where('batch_code', $oldSale->batch_code)
                        ->where('expiry', $oldSale->expiry)
                        ->first();
                    if ($stock) {
                        $stock->in_stock += $oldSale->sale_qty;
                        $stock->foc -= $oldSale->foc;
                        $stock->save();
                    }

                    $oldSale->delete();
                }
            }

            // ---------------- Step 5: Process items ----------------
            foreach ($request->items as $rawItem) {
                $itemData = is_string($rawItem) ? json_decode($rawItem, true) : $rawItem;
                if (!$itemData) continue;

                $itemName = $itemData['itemName'];
                $qty      = (int) $itemData['qty'];
                $foc      = (int) $itemData['foc'];
                $rate     = $itemData['rate'];
                $tax      = $itemData['tax'];

                $item = Item::where('item_name', $itemName)->first();
                if (!$item) {
                    return redirect()->back()->with('error', "Item not found: $itemName");
                }

                $remainingQty = $qty;

                // ---------------- Step 5a: Use old batches first (edit case) ----------------
                foreach ($oldSaleBatches as $index => $batch) {
                    if ($remainingQty <= 0) break;
                    if ($batch['batch_code'] && $batch['sale_qty'] > 0) {
                        $deductQty = min($remainingQty, $batch['sale_qty']);

                        $stock = Stock::where('item', $itemName)
                            ->where('batch_code', $batch['batch_code'])
                            ->where('expiry', $batch['expiry'])
                            ->first();

                        if ($stock) {
                            $sale = SaleReport::create([
                                'user_id'        => $userId,
                                'employee_id'    => $request->employee_id,
                                'invoice_number' => $invoiceNumber,
                                'item_name'      => $itemName,
                                'sale_qty'       => $deductQty,
                                'foc'            => $foc,
                                'sale_rate'      => $rate,
                                'amount'         => $deductQty * $rate,
                                'tax'            => $deductQty * $tax,
                                'sub_total'      => ($deductQty * $rate) + ($deductQty * $tax),
                                'batch_code'     => $stock->batch_code,
                                'expiry'         => $stock->expiry,
                                'pack_size'      => null,
                            ]);

                            $stock->in_stock -= $deductQty;
                            $stock->foc += $foc;
                            $stock->save();

                            $saleItems[] = $sale;
                            $totalTax += $deductQty * $tax;
                            $totalAmount += $deductQty * $rate;

                            $batch['sale_qty'] -= $deductQty;
                            $remainingQty -= $deductQty;

                            $oldSaleBatches[$index] = $batch; // update qty
                        }
                    }
                }

                // ---------------- Step 5b: Use new FIFO stocks if remaining ----------------
                if ($remainingQty > 0) {
                    $stocks = Stock::where('item', $itemName)
                        ->where('in_stock', '>', 0)
                        ->orderBy('expiry', 'asc') // earliest expiry first
                        ->get();

                    foreach ($stocks as $stock) {
                        if ($remainingQty <= 0) break;

                        $deductQty = min($stock->in_stock, $remainingQty);

                        $sale = SaleReport::create([
                            'user_id'        => $userId,
                            'employee_id'    => $request->employee_id,
                            'invoice_number' => $invoiceNumber,
                            'item_name'      => $itemName,
                            'sale_qty'       => $deductQty,
                            'foc'            => $foc,
                            'sale_rate'      => $rate,
                            'amount'         => $deductQty * $rate,
                            'tax'            => $deductQty * $tax,
                            'sub_total'      => ($deductQty * $rate) + ($deductQty * $tax),
                            'batch_code'     => $stock->batch_code,
                            'expiry'         => $stock->expiry,
                            'pack_size'      => null,
                        ]);

                        $stock->in_stock -= $deductQty;
                        $stock->foc += $foc;
                        $stock->save();

                        $saleItems[] = $sale;
                        $totalTax += $deductQty * $tax;
                        $totalAmount += $deductQty * $rate;

                        $remainingQty -= $deductQty;
                    }
                }

                if ($remainingQty > 0) {
                    return redirect()->back()->with('error', "Insufficient stock for item: $itemName after batch allocation.");
                }
            }

                        // ---------------- Step 5c: Save UserPayment Record ----------------
            // We save the payment record if an amount was paid or if we are tracking the due amount

            // Calculate Total Invoice Value
            $subTotalInvoice = array_sum(array_column($saleItems, 'sub_total')); // Grand total of all sale item sub_totals

            $discount = $request->input('discount', 0); 
            $amountPaid = $request->input('amount_paid', 0);
            
            // Grand Total Due = (Item Subtotals + Previous Balance - Discount)
            $grandTotalDue = $subTotalInvoice + $previousBalance - $discount;

            if ($subTotalInvoice > 0) {
                UserPayment::create([
                    'user_id' => $userId,
                    'employee_id' => $request->employee_id,
                    'sub_total' => $subTotalInvoice,
                    'invoice_number' => $customer->name . '-' . $invoiceNumber,

                ]);
            }
            // If amount paid is less than grandTotalDue, you might save a 'pending' or 'partial' payment record.


            // ---------------- Step 6: Generate PDF ----------------
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

    public function payments(Request $request)
    {
        $query = UserPayment::query();
    
        if ($request->filled('start_date')) {
            $query->whereDate('paid_at', '>=', $request->start_date);
        }
    
        if ($request->filled('end_date')) {
            $query->whereDate('paid_at', '<=', $request->end_date);
        }
    
        $data = $query->with('employee')->orderBy('paid_at', 'desc')->get();
    
        // Count Paid / Unpaid
        $paidCount = $data->where('status', 'paid')->count();
        $unpaidCount = $data->where('status', 'unpaid')->count();
    
        // Sum Paid / Unpaid Amount
        $paidTotal = $data->where('status', 'paid')->sum('sub_total');
        $unpaidTotal = $data->where('status', 'unpaid')->sum('sub_total');
    
        return view('payments', compact('data', 'paidCount', 'unpaidCount', 'paidTotal', 'unpaidTotal'));
    }
    

    

    public function payments_update(Request $request)
    {
        // Log the incoming request for debugging
        Log::info('Payment update request:', $request->all());

        try {
            // Validate the request
            $request->validate([
                'payment_id' => 'required|exists:user_payments,id',
                'payment_method' => 'nullable|string|max:255',
                'status' => 'required|in:paid,unpaid',
                'paid_at' => 'nullable|date',
            ]);

            // Find the payment record
            $payment = UserPayment::findOrFail($request->payment_id);

            // Log the current payment state before update
            Log::info('Current Payment Record:', $payment->toArray());

            // Update only allowed fields
            $payment->update([
                'payment_method' => $request->payment_method,
                'status' => $request->status,
                'paid_at' => $request->paid_at,
            ]);

            // Log after successful update
            Log::info('Payment updated successfully:', $payment->toArray());

            return redirect()->route('payments')->with('info', 'Payment updated successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error:', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Payment update failed:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Something went wrong while updating payment.');
        }
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
