<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\v;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Booking;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
class MainController extends Controller {


    public function roomslist() {

        $roomslist = Room::with('category')->paginate(20);
        $time_from='From';
        $time_to='To';
        return view('rooms', compact('roomslist','time_from','time_to'));

    }


    public function search(Request $request) {
        
        $rules = [
            'time_from' => 'required|date_format:Y-m-d',
            'time_to' => 'required|date_format:Y-m-d|after:time_from',
        ];

        $validate =  Validator::make($request->all(), $rules, []);

        $timeFrom = $request->input('time_from');
        $timeTo = $request->input('time_to');
        $availableRooms = Room::whereDoesntHave('booking', function ($query) use ($timeFrom, $timeTo) {
            $query->betweenDates($timeFrom, $timeTo,null);
        })->get();


        return view('rooms', [
            'roomslist' =>  $availableRooms, // RoomResource::collection($sroom),
            'time_from' => $timeFrom,
            'time_to' => $timeTo,
        ]);
    }

    public function oneroom($id, Request $request) {
        $phone_codes = [];
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/phone_countries_codes.json')) {
            try {
                $phone_codes = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/phone_countries_codes.json'), true);
            } catch (Exeption $e) {
                echo  $e->getMessage(), "\n";
            }
        }


        if ($request->input('name') != null)
            $name = $request->input('name');
        else $name = '';
        $countries = Country::get()->pluck('name', 'id');

        return view('oneroom', [
            'room' =>  Room::where('id', $id)->with("booking")->paginate(1),
            'phone_codes' => $phone_codes,
            'name' => $name,
            'countries' => $countries
        ]);
    }

    public function bookin(Request $request) {

        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'phone' => 'required|numeric',
            'email' => 'required|email',
            'country_id' => 'required',
            'room_id' => 'required',
            'time_from' => 'required|date_format:Y-m-d',
            'time_to' => 'required|date_format:Y-m-d|after:time_from',
        ];

        $validate =  Validator::make($request->all(), $rules, []);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->messages())->withInput($request->all());
        }

        $email = $request->email;
        $data = [
            'first_name' => $request->first_name,
            'last_name' =>  $request->last_name,
            'email' => $email,
            'address' =>  $request->address,
            'phone' =>  $request->phone,
            'country_id' =>  $request->country_id,
        ];
        
        $customer = Customer::where('email', $email)->first();
        
        if ($customer) {
            $customer_id = $customer->id;
        } else {
            $new_customer = Customer::create($data);
            $customer_id = $new_customer->id;
        }
     

        $existingBooking = Booking::betweenDates($request->time_from, $request->time_to, $request->room_id)->first();

        if ($existingBooking) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'not availeble' => ['Chambre reservé pour la date selectionné'],
            ]);
            throw $error;
    }

        $bookin = new booking();
        $bookin->customer_id = $customer_id;
        $bookin->room_id = $request->room_id;
        $bookin->time_from =  $request->time_from;
        $bookin->time_to = $request->time_to;
        $bookin->save(); 
        Mail::to($email)->send(new TestMail());
        return view('bookin', []); 
    }
}
