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
            <h1 class="h3 mb-0 text-gray-800">Creer categorie</h1>
                <div class="ml-auto">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-primary">
                        <span class="text">Retour</span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" class="form-control" id="name" placeholder="{{ __('name') }}" name="name" value="{{ old('name') }}" />
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Enregistrer</button>
                </form>
            </div>
        </div>
    

    <!-- Content Row -->

</div>
@endsection