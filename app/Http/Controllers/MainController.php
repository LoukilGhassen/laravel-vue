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
class MainController extends Controller {


    public function roomslist() {

        $roomslist = Room::with('category')->paginate(6);
        $time_from='From';
        $time_to='To';
        return view('rooms', compact('roomslist'));
/*
        return view('rooms', [
            'roomslist' => RoomResource::collection(Room::with("booking","category")->paginate(6)),
            'time_from' => 'From',
            'time_to' => 'To',
        ]); */
    }


    public function search(Request $request) {
        $time_from = 'From';
        $time_to = 'To';
        if (
            preg_match("/(\d+)\/(\d+)\/(\d+)/", $request->get('time_from'), $m1) &&
            preg_match("/(\d+)\/(\d+)\/(\d+)/", $request->get('time_to'), $m2)
        ) {
            $time_from =  $request->get('time_from');
            $time_to =  $request->get('time_to');
            $y = $m1[3];
            $m = $m1[1];
            $d = $m1[2];
            $stime_from = "$y-$m-$d";
            $y = $m2[3];
            $m = $m2[1];
            $d = $m2[2];
            $stime_to = "$y-$m-$d";
            $sroom = Room::distinct()->select('rooms.id', 'rooms.name', 'photo', 'price')->leftjoin('booking', 'booking.room_id', '=', 'rooms.id')->whereNull('booking.time_from')
                ->orwhere(
                    function ($query) use ($stime_from, $stime_to) {
                        $query->where('booking.time_to', '<', "'$stime_to'")->where('booking.time_from', '>', "'$stime_from'");
                    }
                )->paginate(6);

            // $query = str_replace(array('?'), array('%s'), $sroom->toSql());
            // $query = vsprintf($query, $sroom->getBindings());
            // dd($query);
            // dd($sroom->count());
        } elseif (
            preg_match("/(\d+)\/(\d+)\/(\d+)/", $request->get('time_from'), $m1) &&
            !preg_match("/(\d+)\/(\d+)\/(\d+)/", $request->get('time_to'), $m2)
        ) {
            $time_from =  $request->get('time_from');
            $y = $m1[3];
            $m = $m1[1];
            $d = $m1[2];
            $stime_from = "$y-$m-$d";
            $sroom = Room::distinct()->leftjoin('booking', 'booking.room_id', '=', 'rooms.id')->whereNull('booking.time_from')
                ->orwhere(
                    function ($query) use ($stime_from) {
                        $query->where('booking.time_from', '>', "'$stime_from'");
                    }
                )->paginate(6);
        } elseif (
            !preg_match("/(\d+)\/(\d+)\/(\d+)/", $request->get('time_from'), $m1) &&
            preg_match("/(\d+)\/(\d+)\/(\d+)/", $request->get('time_to'), $m2)
        ) {
            $time_to =  $request->get('time_to');
            $y = $m2[3];
            $m = $m2[1];
            $d = $m2[2];
            $stime_to = "$y-$m-$d";
            $sroom = Room::distinct()->leftjoin('booking', 'booking.room_id', '=', 'rooms.id')->whereNull('booking.time_from')
                ->orwhere(
                    function ($query) use ($stime_to) {
                        $query->where('booking.time_to', '<', "'$stime_to'");
                    }
                )->paginate(6);
        } else {
            $sroom = Room::with("booking")->paginate(6);
        }

        return view('rooms', [
            'roomslist' =>  $sroom, // RoomResource::collection($sroom),
            'time_from' => $time_from,
            'time_to' => $time_to,
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
      /*  if (
            preg_match("/(\d+)\/(\d+)\/(\d+)/", $request->get('time_from'), $m1) &&
            preg_match("/(\d+)\/(\d+)\/(\d+)/", $request->get('time_to'), $m2)
        ) {
            $y = $m1[3];
            $m = $m1[1];
            $d = $m1[2];
            $stime_from = "$y-$m-$d";
            $y = $m2[3];
            $m = $m2[1];
            $d = $m2[2];
            $stime_to = "$y-$m-$d";

            $sroom = Bookings::where('room_id', $request->get('room_id'))->where(
                function ($query) use ($stime_from, $stime_to) {
                    $query
                        ->orwhere(
                            function ($query) use ($stime_from, $stime_to) {
                                $query
                                    ->where('time_to', '>=', $stime_from)
                                    ->where('time_to', '<=',  $stime_to);
                            }
                        )
                        ->orwhere(
                            function ($query) use ($stime_from, $stime_to) {
                                $query
                                    ->where('time_from', '>=', $stime_from)
                                    ->where('time_from', '<=',  $stime_to);
                            }
                        );
                }
            );

            $date1 = Carbon::createFromFormat('Y-m-d', $stime_from);
            $date2 = Carbon::createFromFormat('Y-m-d', $stime_to);
            echo($date1);
            dd($date1);
            if ($sroom->get()->count() > 0) {
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    'not availeble' => ['This room has been booked for the dates you have selected'],
                ]);
                throw $error;
            } elseif ($date1->gt($date2)) {
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    'date error' => ['Date TO is less than date FROM'],
                ]);
                throw $error;
            }
        } else {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'dates' => ['Dates From or  To not set'],
            ]);
        }
*/

        $existingBooking = Booking::betweenDates($request->time_from, $request->time_to, $request->room_id)->first();

        if ($existingBooking) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'not availeble' => ['This room has been booked for the dates you have selected'],
            ]);
            throw $error;
    }

        $bookin = new booking();
        $bookin->customer_id = $customer_id;
        $bookin->room_id = $request->room_id;
        $bookin->time_from =  $request->time_from;
        $bookin->time_to = $request->time_to;
        $bookin->save(); 
        return view('bookin', []); 
    }
}
