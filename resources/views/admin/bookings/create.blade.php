@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

<!-- Content Row -->
        <div class="card shadow">
            <div class="card-header py-3 d-flex">
            <h1 class="h3 mb-0 text-gray-800">creer Reservation</h1>
                <div class="ml-auto">
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-primary">
                        <span class="text">Retour</span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.bookings.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="customer">{{ __('Customer') }}</label>
                        <select class="form-control" name="customer_id" id="customer">
                            @foreach($customers as $id => $customer)
                                <option value="{{ $id }}">{{ $customer }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="room">{{ __('Room') }}</label>
                        <select class="form-control" name="room_id" id="room">
                            @foreach($rooms as $id => $room)
                                <option {{ $roomId == $id ? 'selected' : null }} value="{{ $id }}">{{ $room }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="time_from">A partir</label>
                        <input type="date" class="form-control " id="time_from" name="time_from" value="{{ old('time_from', $timeFrom) }}">
                    </div>
                    <div class="form-group">
                        <label for="time_to">Jusqu'a</label>
                        <input type="date" class="form-control" id="time_to" name="time_to" value="{{ old('time_to', $timeTo) }}">
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Enregistrer</button>
                </form>
            </div>
        </div>
    

    <!-- Content Row -->

</div>
@endsection

@push('style-alt')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endpush

@push('script-alt')
<script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script>
        $('.datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD HH:mm',
            locale: 'en',
            sideBySide: true,
            icons: {
            up: 'fas fa-chevron-up',
            down: 'fas fa-chevron-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right'
            },
            stepping: 10
        });
    </script>
@endpush