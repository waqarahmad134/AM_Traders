<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\SaleReport;
use App\Models\User;
use App\Models\Stock;
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
                'password' => 'required|string|min:6',
                'usertype' => 'required|in:admin,staff,customer,supplier',
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
            $user->username = strtolower($request->firstName . '.' . $request->lastName);
            $user->email = $request->email;
            $user->contact = $request->leyka_donor_phone;
            $user->usertype = $request->usertype;
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

    public function employees(Request $request)
    {
        $client = new \GuzzleHttp\Client();
        $request = $client->get(env('BASE_URL') . '/users/all/employees');
        $response = $request->getBody()->getContents();
        $data = json_decode($response);

        // $arr = array();

        $url1 = env('BASE_URL') . '/users/all/active/roles';
        $client1 = new \GuzzleHttp\Client();
        $response1 = $client1->get($url1, [
            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
        ]);
        $response1 = $response1->getBody()->getContents();
        $data1 = json_decode($response1);
        $roles1 = $data1->Response;
        return view('admin.list_employee', ['data' => $data])->with('roles', $roles1);
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

        return view('create_invoice', compact('items', 'users'));
    }


    public function store_invoice(Request $request)
    {
        try {
            if ($request->filled('user_id') && $request->input('user_id') !== 'new_user') {
                $userId = $request->input('user_id');
                $customer = User::find($userId);
            } else {
                // Runtime user creation
                $validator = Validator::make($request->all(), [
                    'name'    => 'required|string|max:255',
                    'contact' => 'nullable|string|max:20',
                    'email'   => 'nullable|email|unique:users,email',
                ]);
            
                if ($validator->fails()) {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput()
                        ->with('error', 'Validation failed. Please check the form fields.');
                }

                $prefix = strtoupper(substr(str_replace(' ', '', $request->name), 0, 2));
                $lastCustomer = User::where('customer_id', 'like', $prefix . '%')
                    ->orderBy('customer_id', 'desc')
                    ->first();

                if ($lastCustomer) {
                    $lastNumber = (int) substr($lastCustomer->customer_id, -3);
                    $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $nextNumber = "001";
                }
            
                $customer = new User();
                $customer->name       = $request->name;
                $customer->username   = strtolower(str_replace(' ', '.', $request->name)) . rand(100,999);
                $customer->email      = $request->email;
                $customer->customer_id   = $prefix . $nextNumber;
                $customer->contact    = $request->contact;
                $customer->ntn_strn   = $request->ntn_strn;
                $customer->license_no = $request->license_no;
                $customer->address    = $request->address;
                $customer->usertype   = 'customer';
                $customer->password   = Hash::make(Str::random(8)); // auto password
                $customer->save();
            
                $userId = $customer->id;
            }
            

            $itemCode = $request->input('new_item_code') ?? $request->input('item_code');
            $itemName = $request->input('new_item_name') ?? $request->input('item_name');

            // Fallback from dropdown
            if (!$itemCode || !$itemName) {
                $selectedText = $request->input('itemInput');  
                if ($selectedText && strpos($selectedText, '-') !== false) {
                    [$itemCode, $itemName] = array_map('trim', explode('-', $selectedText, 2));
                }
            }

            $qty = (int) $request->input('qty');
            $foc = (int) $request->input('foc', 0);
            $rate = $request->input('rate');
            $amount = $request->input('amount');

            // Check stock
            $stock = Stock::where('item_code', $itemCode)->first();

            if (!$stock) {
                return redirect()->back()->with('error', "Stock not found for item code: $itemCode");
            }

            if ($stock->in_stock <= 0 || $stock->in_stock < $qty) {
                return redirect()->back()->with('error', 'Stock is insufficient or already empty.');
            }

            // Save sale report
            $sale = SaleReport::create([
                'user_id' => $userId,
                'item_code' => $itemCode,
                'item_name' => $itemName,
                'sale_qty' => $qty,
                'foc' => $foc,
                'sale_rate' => $rate,
                'amount' => $amount,
                'pack_size' => null,
            ]);

            // Update stock
            $stock->in_stock -= $qty;
            $stock->foc += $foc;
            $stock->save();

            // Get customer info
            $customer = $userId ? User::find($userId) : null;

            // Get all sale items for this invoice (optional: filter by date/time if needed)
            $saleItems = SaleReport::where('user_id', $userId)->whereDate('created_at', $sale->created_at->toDateString())->get();

            // Generate PDF
            $pdf = Pdf::loadView('invoice_pdf', [
                'invoice' => (object)[
                    'id' => $sale->id,
                    'user' => $customer,
                    'created_at' => $sale->created_at,
                    'items' => $saleItems,
                ],
                'customer' => $customer,
                'invoice_no' => 'INV-' . Str::padLeft($sale->id, 5, '0'),
                'date' => now()->format('d-m-Y'),
            ]);

            // Save PDF
            $filename = 'invoice_' . $sale->id . '.pdf';
            $pdfPath = 'invoices/' . $filename;

            Storage::makeDirectory('invoices');
            $pdf->save(storage_path('app/' . $pdfPath));

            // Save PDF path to all related sale reports for this user on that day
            SaleReport::where('user_id', $userId)
                ->whereDate('created_at', $sale->created_at->toDateString())
                ->update(['pdf_path' => $pdfPath]);

            return redirect()->back()->with('info', 'Invoice created and PDF generated.');
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
        'item_code' => 'required|string|max:50|unique:items,item_code',
        'status'    => 'required|in:active,inactive',
    ]);

    try {
        $item = new Item();
        $item->item_name = $validated['item_name'];
        $item->item_code = $validated['item_code'];
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
