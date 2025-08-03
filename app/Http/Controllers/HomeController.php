<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;



class HomeController extends Controller
{
    public function home()
    {
        $users = User::all();     
        return view('dashboard', ['users' => $users]);
    }

    public function add_front(Request $request)
    {
        $data = array(
            'about_us' => $request->about_us,
            'heading1' => $request->heading1,
            'heading2' => $request->heading2,
        );
        $url = env('BASE_URL') . '/appsettings/update';

        $client = new \GuzzleHttp\Client();
        $response = $client->post($url, [
            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            'body'    => json_encode($data)
        ]);

        $data = $response->getBody()->getContents();
        $data = json_decode($data);
        return $data;
    }


    public function settings_mobile()
    {
        $client = new \GuzzleHttp\Client();
        $request = $client->get(env('BASE_URL') . '/appsettings/getall');
        $response = $request->getBody()->getContents();
        $data = json_decode($response);
        // dd($data);
        return view('front-end-settings/settings_mobile', ['data' => $data->Response]);
    }


    public function mails()
    {
        $client = new \GuzzleHttp\Client();
        $request = $client->get(env('BASE_URL') . '/appsettings/getallmessages');
        $response = $request->getBody()->getContents();
        $data = json_decode($response);
        return view('mails')->with('data', $data->Response);
    }

    public function mails_read()
    {
        $client = new \GuzzleHttp\Client();
        $request = $client->get(env('BASE_URL') . '/appsettings/getrepliedmessages');
        $response = $request->getBody()->getContents();
        $data = json_decode($response);
        return view('mails_read')->with('data', $data->Response);
    }

    public function settings()
    {
        $client1 = new \GuzzleHttp\Client();
        $request1 = $client1->get(env('BASE_URL') . '/appsettings/get/web');
        $response1 = $request1->getBody()->getContents();
        $data1 = json_decode($response1);
        return view('front-end-settings/settings')->with('data1', $data1->Response);
    }



    public function site_settings(Request $request)
    {
        $url = env('BASE_URL') . "/appsettings/update/web";
        $data = [
            'id' => $request->id,
            'value' => $request->value
        ];

        $client = new \GuzzleHttp\Client();
        $response = $client->PUT($url, [
            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            'body'    => json_encode($data)
        ]);
        $response = $response->getBody()->getContents();
        $data = json_decode($response);
        return $data;
    }

    public function settings_update(Request $request)
    {

        $url = env('BASE_URL') . "/appsettings/update/app/";
        $data = [
            'id' => $request->id,
            'newValue' => $request->text
        ];
        $client = new \GuzzleHttp\Client();
        $response = $client->PUT($url, [
            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            'body'    => json_encode($data)
        ]);
        $response = $response->getBody()->getContents();
        $data = json_decode($response);
        return $data;
    }


    public function settings_update1(Request $request)
    {
        // return $request->all();
        $url = env('BASE_URL') . "/appsettings/update/app/";
        $data = [
            'id' => $request->id,
            'newValue' => $request->text
        ];
        $client = new \GuzzleHttp\Client();
        $response = $client->PUT($url, [
            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            'body'    => json_encode($data)
        ]);
        $response = $response->getBody()->getContents();
        $data = json_decode($response);
        return $data;
    }
    // public function settings_update(Request $request)
    // {
    //     $url = env('BASE_URL')."/appsettings/addDM";
    //     $data = [
    //         'id' => $request->id,
    //         'value' => $request->value
    //         ];

    //     $client = new \GuzzleHttp\Client();
    //     $response = $client->PUT($url, [
    //         'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
    //         'body'    => json_encode($data)
    //     ]);
    //     $response= $response->getBody()->getContents();
    //     $data=json_decode($response);
    //     return $data;
    // }

    public function banners()
    {
        $client = new \GuzzleHttp\Client();
        $request = $client->get(env('BASE_URL') . '/appsettings/banners');
        $response = $request->getBody()->getContents();
        $data = json_decode($response);
        return view('front-end-settings/banners', ['data' => $data->Response]);
    }

    public function delete_banner($id)
    {

        $client = new \GuzzleHttp\Client();
        $request = $client->put(env('BASE_URL') . '/appsettings/delete_banner/' . $id);
        $response = $request->getBody()->getContents();

        $data = json_decode($response);
        if ($data->ResponseCode == "0") {
            return redirect()->back()->with("error", $data->ResponseMessage);
        } else {
            return redirect()->back()->with("warning", $data->ResponseMessage);
        }
    }

    public function restricted_items()
    {
        $client = new \GuzzleHttp\Client();
        $request = $client->get(env('BASE_URL') . '/restricted/getall/admin');
        $response = $request->getBody()->getContents();
        $data = json_decode($response);
        return view('front-end-settings/restricted_items', ['data' => $data->Response]);
    }

    public function restricted_delete($id)
    {
        $client = new \GuzzleHttp\Client();
        $request = $client->put(env('BASE_URL') . '/restricted/block/' . $id);
        $response = $request->getBody()->getContents();
        $data = json_decode($response);
        session()->flash('error', 'Restricted Items Deleted!');
        return redirect()->back();
    }

    public function add_restricted_items(Request $request)
    {

        $image_path = $request->image->getPathname();
        $image_mime =  $request->image->getmimeType();
        $image_org  =  $request->image->getClientOriginalName();

        $url = env('BASE_URL') . "/restricted/add";

        $client = new \GuzzleHttp\Client();
        $response = $client->post(url($url), [

            'multipart' => [
                [
                    'name'     => 'image',
                    'filename' => $image_org,
                    'Mime-Type' => $image_mime,
                    'contents' => fopen($image_path, 'r'),

                ],
                [
                    'name'     => 'name',
                    'contents' => $request->name
                ],

            ]
        ]);

        $response = $response->getBody()->getContents();
        $data = json_decode($response);
        return $data;
    }
}
