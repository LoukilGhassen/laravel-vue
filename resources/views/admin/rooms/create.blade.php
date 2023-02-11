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
            <h1 class="h3 mb-0 text-gray-800">Creer chambre</h1>
                <div class="ml-auto">
                    <a href="{{ route('admin.rooms.index') }}" class="btn btn-primary">
                        <span class="text">Retour</span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="room_number">Numero chambre</label>
                        <input type="text" class="form-control" id="room_number" placeholder="Numero chambre" name="room_number" value="{{ old('room_number') }}" />
                    </div>
                    <div class="form-group">
                        <label for="price">Prix</label>
                        <input type="number" class="form-control" id="price" placeholder="Prix" name="price" value="{{ old('price') }}" />
                    </div>
                    <div class="form-group">
                        <label for="capacity">Capacité</label>
                        <input type="number" class="form-control" id="capacity" placeholder="capacité" name="capacity" value="{{ old('capacity') }}" />
                    </div>
                    <div class="form-group">
                        <label for="category">Categoré</label>
                        <select class="form-control" name="category_id" id="category">
                            @foreach($categories as $id => $category)
                                <option value="{{ $id }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="floor">Etage</label>
                        <input type="number" class="form-control" id="floor" placeholder="Etage" name="floor" value="{{ old('floor') }}" />
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" id="descriptioin" placeholder="description" cols="30" rows="10">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="photo">photo</label>
                        <input type="file" class="form-control" name="photo" placeholder="photo du chambre">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Enregistrer</button>
                </form>
            </div>
        </div>
    

    <!-- Content Row -->

</div>
@endsection