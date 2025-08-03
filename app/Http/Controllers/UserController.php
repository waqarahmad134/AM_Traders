<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        return $request->all();
        $items = Item::all();
        $users = User::where('userType', '!=', 'admin')->get();

        return view('create_invoice', compact('items', 'users'));
    }
}
