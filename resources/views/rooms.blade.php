@extends('layout')

@section('title')Rooms list @endsection

@section('main_content')

<section class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="py-2 text-white">Look for free rooms:</h2>
        </div>
    </div>

  </section>

<div class="album py-3">
    <div class="container">
        <div class="row">                  
            <div class="col-12">
            </div>
        </div>        
        <div class="row">
            @foreach($roomslist as $room)            
            <div class="col-md-4">
                <div class="card mb-4 box-shadow position-relative">
                    {{-- @if (

                            isset($room['booking'][0]['time_from']) &&
                            isset($room['booking'][0]['time_to']) )
                        {{$room['booking'][0]['time_from']}} -- {{$room['booking'][0]['time_to']}}
                    @endif --}}
                    <?php $flag=false;?>
                    @if(
                      isset($room['booking'])
                    )
                    @foreach ($room['booking'] as $b)
                        @if (
                            isset($b['time_from']) &&
                            isset($b['time_to']) &&
                            \Carbon\Carbon::now()->timestamp - Carbon\Carbon::createFromFormat('Y-m-d', $b['time_from'])->timestamp >=0 &&
                            \Carbon\Carbon::now()->timestamp - Carbon\Carbon::createFromFormat('Y-m-d', $b['time_to'])->timestamp <=0
                            )
                               <?php $flag=true;?>
                        @endif
                    @endforeach
                    @endif
                    @if ($flag)
                        <div class="badge bg-md bg-danger position-absolute top-0 end-0 p-2">Booked!</div>
                    @else 
                        <div class="badge bg-md bg-success position-absolute top-0 end-0 p-2">Available</div>
                    @endif

                    <a href="/room/{{$room['id']}}" style="max-height:225px;overflow:hidden;background:url({{asset('public/img/'.$room['photo'])}}) center center;height: 225px; width: 100%; display: block;background-size:cover;">
                    </a>
                    <div class="card-body">
                        <p class="card-text"><a href="/room/{{$room['id']}}" class="text-dark text-decoration-none">{{$room['room_number']}}</a></p>
                        <h2 class="card-title pricing-card-title text-end text-success ">${{$room['category']['name']}} <small class="text-muted text-secondary">/ day</small></h2>
                        <div class="d-flex justify-content-left align-items-center">
                            <div class="btn-group">
                                <a type="button" class="btn btn-md btn-secondary text-white" href="/room/{{$room['id']}}">Details</a>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach            
        </div>
        <div class="row">                  
            <div class="col-12">
            </div>
        </div>
    </div>
</div>

@endsection