@extends('layout')

@section('title')Rooms list @endsection

@section('main_content')


<section class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="py-2 text-white">Chercher des chambres disponible:</h2>
        </div>
    </div>
    <form class="row" method="POST" action="/search">
    @csrf
      <div class="col-2">
        <div class="input-group">
        <input  type="date" required class="form-control" id="date" placeholder="From" name="time_from" id="time_from" value="{{old('time_from')}}"/>
        </div>
      </div>
      <div class="col-2">
        <div class="input-group" >
                <input type="date" required class="form-control" id="date"  placeholder="To" name="time_to" id="time_to"  value="{{old('time_to')}}"/>
        </div>
      </div>
      <div class="col-2">
          <button class="btn btn-md btn-warning text-white" type="submit">Chercher</button>
      </div>
    </form>
    <div class="row mt-3">
        <div class="col-12">
            @if ($time_from != 'From' && $time_to != 'To')
            <div class="py-2 text-warning">periode de recherche <b>{{$time_from}}</b> --  to <b>{{$time_to}}</b></div>
            @elseif($time_from == 'From' && $time_to != 'To')
            <div class="py-2 text-warning">Search range: to <b>{{$time_to}}</b></div>
            @elseif($time_from != 'From' && $time_to == 'To')
            <div class="py-2 text-warning">periode de recherche <b>{{$time_from}}</b></div>
            @endif
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
                            isset($time_from) &&
                            isset($time_to) &&
                            ($time_from == 'From' && $time_to == 'To') &&
                            \Carbon\Carbon::now()->timestamp - Carbon\Carbon::createFromFormat('Y-m-d', $b['time_from'])->timestamp >=0 &&
                            \Carbon\Carbon::now()->timestamp - Carbon\Carbon::createFromFormat('Y-m-d', $b['time_to'])->timestamp <=0
                            )
                               <?php $flag=true;?>
                        @endif
                        @if (
                            isset($time_from) &&
                            isset($time_to) &&
                            ($time_from != 'From' && $time_to != 'To') &&
                            (\Carbon\Carbon::createFromFormat('Y-m-d', $time_from)->timestamp - Carbon\Carbon::createFromFormat('Y-m-d', $b['time_from'])->timestamp >=0 &&
                            \Carbon\Carbon::createFromFormat('Y-m-d', $time_to)->timestamp - Carbon\Carbon::createFromFormat('Y-m-d', $b['time_to'])->timestamp <=0
                            || 
                            \Carbon\Carbon::createFromFormat('Y-m-d', $time_to)->timestamp - Carbon\Carbon::createFromFormat('Y-m-d', $b['time_from'])->timestamp >=0 &&
                            \Carbon\Carbon::createFromFormat('Y-m-d', $time_from)->timestamp - Carbon\Carbon::createFromFormat('Y-m-d', $b['time_to'])->timestamp <=0
                            )
                            )   
                               <?php $flag=true;?>
                        @endif
                    @endforeach
                    @endif
                    @if ($flag)
                        <div class="badge bg-md bg-danger position-absolute top-0 end-0 p-2">Reservé!</div>
                    @else 
                        <div class="badge bg-md bg-success position-absolute top-0 end-0 p-2">Disponible</div>
                    @endif

                    <a href="/room/{{$room['id']}}" style="max-height:225px;overflow:hidden;background:url({{asset('public/img/'.$room['photo'])}}) center center;height: 225px; width: 100%; display: block;background-size:cover;">
                    </a>
                    <div class="card-body">
                        <p class="card-text"><a href="/room/{{$room['id']}}" class="text-dark text-decoration-none">{{$room['category']['name']}}</a></p>
                        <h2 class="card-title pricing-card-title text-end text-success ">Dt {{$room['price']}} <small class="text-muted text-secondary">/ Jour</small></h2>
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