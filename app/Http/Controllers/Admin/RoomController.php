<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use App\Models\Booking;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Admin\RoomRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('room_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $rooms = Room::all();

        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('room_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::get()->pluck('name', 'id');

        return view('admin.rooms.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoomRequest $request)
    {
        abort_if(Gate::denies('room_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
       

        $inputs=$request->all();
        if($photo=$request->file("photo")){
        $newfile=strtotime(date("Y-m-d")).".".$photo->getClientOriginalExtension();
        $photo->move('public/img/',$newfile);
        $inputs['photo']=$newfile;
        }

        Room::create($inputs);

        return redirect()->route('admin.rooms.index')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
    }

     /**
     * Display Room.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room)
    {
        abort_if(Gate::denies('room_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bookings = Booking::where('room_id', $room->id)->get();

        return view('admin.rooms.show', compact('room', 'bookings'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        abort_if(Gate::denies('room_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::get()->pluck('name', 'id');

        return view('admin.rooms.edit', compact('room', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoomRequest $request, Room $room)
    {
        abort_if(Gate::denies('room_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $inputs=$request->all();
        if($photo=$request->file("photo")){
        $newfile=strtotime(date("Y-m-d-s")).".".$photo->getClientOriginalExtension();
        $photo->move('public/img/',$newfile);
        $inputs['photo']=$newfile;
        if($request->oldphoto != "no-image.jpg" && file_exists('public/img/'.$request->oldphoto)){
            unlink('public/img/'.$request->oldphoto);
        }
        unset($inputs['oldphoto']);
        }

        $room->update($inputs);

        return redirect()->route('admin.rooms.index')->with([
            'message' => 'successfully updated !',
            'alert-type' => 'info'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        abort_if(Gate::denies('room_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if($room->photo != 'no-image.jpg' && file_exists('public/img/'.$room->photo )){
            unlink('public/img/'.$room->photo);
        }
        $room->delete();
        
        return redirect()->route('admin.rooms.index')->with([
            'message' => 'successfully deleted !',
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
        abort_if(Gate::denies('room_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Room::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }
}
