@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
   

    <!-- Content Row -->
        <div class="card">
            <div class="card-header py-3 d-flex">
                <h6 class="m-0 font-weight-bold text-primary">
                   Reservation
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" cellspacing="0" width="100%">
                        <tr>
                            <th>Client</th>
                            <td>{{ $booking->customer->full_name }}</td>
                        </tr>
                        <tr>
                            <th>chambre</th>
                            <td>{{ $booking->room->room_number }}</td>
                        </tr>
                        <tr>
                            <th>prix</th>
                            <td>{{ $booking->room->price }}</td>
                        </tr>
                        <tr>
                            <th>De</th>
                            <td>{{ $booking->time_from }}</td>
                        </tr>
                        <tr>
                            <th>A</th>
                            <td>{{ $booking->time_to }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    <!-- Content Row -->

</div>
@endsection