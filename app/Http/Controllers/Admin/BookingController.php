<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Admin\BookingRequest;
use Symfony\Component\HttpFoundation\Response;
use Validator;
use Carbon\Carbon;
use DateTime;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('booking_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bookings = booking::all();
        return view('admin.bookings.index', compact('bookings'));
    }

     /**
     * Show the form for creating new Booking.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        abort_if(Gate::denies('booking_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customers = Customer::get()->pluck('full_name', 'id');
        $rooms = Room::get()->pluck('id', 'id');
        $roomId = $request->get('room_id');
        $timeFrom = $request->get('time_from');
        $timeTo = $request->get('time_to');

    return view('admin.bookings.create', compact('customers', 'rooms', 'roomId', 'timeFrom', 'timeTo'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('booking_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $rules = [
            'customer_id' => 'required',
            'room_id' => 'required',
            'time_from' => 'required|date_format:Y-m-d',
            'time_to' => 'required|date_format:Y-m-d|after:time_from',
            ];

        $validate =  Validator::make($request->all(), $rules, []);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->messages())->withInput($request->all());
        }

        $existingBooking = Booking::betweenDates($request->time_from, $request->time_to, $request->room_id)->first();

        if ($existingBooking) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'not availeble' => ['Chambre reservé pour la date selectionné'],
            ]);
            throw $error;
         }
        
        $bookin = new booking();
        $bookin->customer_id = $request->customer_id;
        $bookin->room_id = $request->room_id;
        $bookin->time_from =$request->time_from;
        $bookin->time_to = $request->time_to;
       $bookin->save();

        
       return redirect()->route('admin.bookings.index')->with([
            'message' => 'Creé avec success !',
            'alert-type' => 'success'
        ]);
    }

     /**
     * Display Booking.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        abort_if(Gate::denies('booking_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking $booking)
    {
        abort_if(Gate::denies('booking_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customers = Customer::get()->pluck('full_name', 'id');
        $rooms = Room::get()->pluck('room_number', 'id');

        return view('admin.bookings.edit', compact('booking', 'customers', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BookingRequest $request, Booking $booking)
    {
        abort_if(Gate::denies('booking_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $booking->update($request->validated());

        return redirect()->route('admin.bookings.index')->with([
            'message' => 'modifié avec success !',
            'alert-type' => 'info'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        abort_if(Gate::denies('booking_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $booking->delete();

        return redirect()->route('admin.bookings.index')->with([
            'message' => 'supprimé avec success !',
            'alert-type' => 'danger'
        ]);
    }

        /**
     * Delete all selected Permission at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('booking_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Booking::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }
}
