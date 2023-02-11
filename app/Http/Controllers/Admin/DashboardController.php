<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(){
        $roomCount = Room::count();
        $bookingCount = Booking::count();
        $customerCount = Customer::count();
        $userCount = User::count();

        return view('admin.dashboard', [
            'roomCount' => $roomCount,
            'bookingCount' => $bookingCount,
            'customerCount' => $customerCount,
            'userCount' => $userCount
        ]);

    }
}
