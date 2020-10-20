@extends('LuisPermisos::layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Edit User</h2>
                </div>

                <div class="card-body">
                    @include('LuisPermisos::common.message')

                    <form action="{{ route('user.update',$user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="container">
                            <div class="form-group">
                                <input type="text" class="form-control" name="name" placeholder="Nombre"
                                    autocomplete="off" value="{{ old('name',$user->name) }}">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" placeholder="example@email.com"
                                    autocomplete="off" value="{{ old('email',$user->email) }}">
                            </div>
                            <div class="form-group">

                                <select name="roles" id="roles" class="form-control">
                                    @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" @isset($user->roles[0]->name)
                                        @if ($role->name == $user->roles[0]->name)
                                        selected
                                        @endif
                                        @endisset
                                        >{{$role->name}}</option>
                                    @endforeach

                                </select>
                            </div>

                            <hr>

                            <br>
                            <a href="{{ route('user.index') }}" class="btn btn-info">Atras</a>
                            <input type="submit" class="btn btn-success" value="Modificar">


                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection