@extends('LuisPermisos::layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>List User</h2>
                </div>

                <div class="card-body">

                    <br><br>
                    @include('LuisPermisos::common.message')

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th colspan="3"></th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($users as $user)
                            <tr>
                                <th scope="row">{{ $user->id }}</th>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @isset($user->roles[0]->name)
                                    {{ $user->roles[0]->name }}
                                    @endisset
                                </td>




                                <td>
                                    @can('view',[$user, ['user.show','userown.show']])
                                    <a href="{{ route('user.show',$user->id) }}" class="btn btn-sm btn-warning">Ver</a>
                                    @endcan
                                </td>
                                <td>
                                    @can('update',[$user, ['user.edit','userown.edit']])
                                    <a href="{{ route('user.edit',$user->id) }}" class="btn btn-sm btn-info">Editar</a>
                                    @endcan
                                </td>
                                <td>
                                    @can('haveaccess','user.destroy')
                                    <form action="{{ route('user.destroy',$user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">Eliminar</button>

                                    </form>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection