@extends('layout')

@section('title'){{$room[0]['name']}} @endsection

@section('main_content')

<section class="container  bg-light">
    <div class="row featurette py-3">
        <div class="col-md-7 order-md-2">
          <h2 class="featurette-heading">{{$room[0]['name']}}</span></h2>
          <hr />
          <div class="lead"><span style="font-size: 21px;font-weight: 400;">L'étage:</span>  <span class="text-warning fw-bold">{!! $room[0]['floor'] !!}</span></div>
          <div class="lead"><span style="font-size: 21px;font-weight: 400;">Capacité:</span>  <span class="text-warning fw-bold">{!! $room[0]['capacity'] !!}</span></div>
          <hr />
          <div class="lead" style="display: flex; justify-content: space-between; align-items: center;"><span style="font-size: 21px;font-weight: 400;">Prix:</span><div class="card-title pricing-card-title text-end text-success h1"><span class="text-success text-lg fw-bold">${{$room[0]['price']}}</div></div>
          <hr />
          <div class="lead"><span style="font-size: 21px;font-weight: 400;">Description:</span><p class="lead">{{$room[0]['description']}}</p></div>
        </div>
        <div class="col-md-5 order-md-1">
          <img class="featurette-image img-fluid mx-auto" data-src="holder.js/500x500/auto" alt="500x500" src="{{asset('public/img/'.$room[0]['photo'])}}" data-holder-rendered="true" style="width: 500px; height: auto;">
        </div>
      </div>
</section>
<section class="container py-5 bg-light" id="bookingform">
    <div class="row pt-3">
        <div class="col-12 text-center">
            <h2 class="text-success">Qu'attendez-vous ? Réservez ! </h2>
        </div>
    </div>
    @if ($errors->any())
    <div class="row py-3">
        <div class="col-12">
            <div class="alert alert-danger pt-3 pb-1  mt-3 mp-2">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif
    <form  method="POST" style="background-color: rgb(0 0 0 / 20%)!important;" class=" pb-3" action="/bookin">
    @csrf
    <input type="hidden" name="room_id" value="{{$room[0]['id']}}"/>
    <div class="row pt-3">
        <div class="col-3"></div>
        <div class="col-6 py-2"><input  type="text" class="form-control"  placeholder="Prénom" name="first_name"  id="first_name" value="{{old('first_name')}}"/></div>
    </div>   
    <div class="row pt-3">
        <div class="col-3"></div>
        <div class="col-6 py-2"><input  type="text" class="form-control"  placeholder="Nom" name="last_name"  id="last_name" value="{{old('last_name')}}"/></div>
    </div>    
    <div class="row pt-3">
        <div class="col-3"></div>
        <div class="col-6 py-2"><input type="text" class="form-control"  placeholder="Email" name="email"  id="email" value="{{old('email')}}"/></div>
    </div>
    <div class="row pt-3">
        <div class="col-3"></div>
        <div class="col-6 py-2">
            <select class="form-control" name="country_id" id="country">
                @foreach($countries as $id => $country)
                    <option value="{{ $id }}">{{ $country }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row py-3">
        <div class="col-3"></div>
        <div class="col-6 py-2">
            <input type="text" class="form-control"  placeholder="Numéro de téléphone" name="phone" id="phone" value="{{old('phone')}}"/>
        </div>        
    </div>
    <div class="row py-3">
            <div class="col-3"></div>
            <div class="col-6 py-2">
                <textarea class="form-control" name="address" id="address" placeholder="Adresse" cols="30" rows="10">{{ old('address') }}</textarea>
            </div>
        </div>

    <div class="row py-3">
        <div class="col-3"></div>
        <div class="col-2">
            <div class="input-group date" id="datepicker1">
                <input autocomplete="off" type="text" class="form-control" id="date" placeholder="De" name="time_from" id="time_from" value="{{old('time_from')}}"/>
                <span class="input-group-append">
                <span class="input-group-text bg-light d-block" placeholder="to">
                    <i class="fa fa-calendar"></i>
                </span>
                </span>
            </div>
        </div>
        <div class="col-2">
            <div class="input-group date" id="datepicker2">
                <input autocomplete="off" type="text" class="form-control" id="date"  placeholder="À" name="time_to" id="time_to"  value="{{old('time_to')}}"/>
                <span class="input-group-append">
                <span class="input-group-text bg-light d-block">
                    <i class="fa fa-calendar"></i>
                </span>
                </span>
            </div>
        </div>
    </div>
    <div class="row pt-3">
        <div class="col-3"></div>
        <div class="col-2">
            <button class="btn btn-md btn-warning text-dark" type="submit">BookIn</button>
        </div>
    </div>          
  </form>
</section>
@endsection