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
            <h1 class="h3 mb-0 text-gray-800">{{ __('create customer') }}</h1>
                <div class="ml-auto">
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-primary">
                        <span class="text">Retour</span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.customers.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="first_name">{{ __('First Name') }}</label>
                        <input type="text" class="form-control" id="first_name" placeholder="{{ __('first name') }}" name="first_name" value="{{ old('first_name') }}" />
                    </div>
                    <div class="form-group">
                        <label for="last_name">{{ __('Last Name') }}</label>
                        <input type="text" class="form-control" id="last_name" placeholder="{{ __('last name') }}" name="last_name" value="{{ old('last_name') }}" />
                    </div>
                    <div class="form-group">
                        <label for="email">{{ __('Email') }}</label>
                        <input type="email" class="form-control" id="email" placeholder="{{ __('email') }}" name="email" value="{{ old('email') }}" />
                    </div>
                    <div class="form-group">
                        <label for="country">{{ __('Country') }}</label>
                        <select class="form-control" name="country_id" id="country">
                            @foreach($countries as $id => $country)
                                <option value="{{ $id }}">{{ $country }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="phone">{{ __('Phone') }}</label>
                        <input type="number" class="form-control" id="phone" placeholder="{{ __('phone') }}" name="phone" value="{{ old('phone') }}" />
                    </div>
                    <div class="form-group">
                        <label for="address">{{ __('Address') }}</label>
                        <textarea class="form-control" name="address" id="address" placeholder="address" cols="30" rows="10">{{ old('address') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Enregistrer</button>
                </form>
            </div>
        </div>
    

    <!-- Content Row -->

</div>
@endsection