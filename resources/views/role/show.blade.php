@extends('LuisPermisos::layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Ver Rol</h2>
                </div>

                <div class="card-body">
                    @include('LuisPermisos::common.message')

                    <form action="{{ route('role.update',$role->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="container">
                            <div class="form-group">
                                <input type="text" class="form-control" name="name" placeholder="Nombre"
                                    autocomplete="off" value="{{ old('name',$role->name) }}" readonly>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="slug" placeholder="Slug"
                                    autocomplete="off" value="{{ old('slug',$role->slug) }}" readonly>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" placeholder="Descripcion" name="description" rows="2"
                                    readonly>{{old('description',$role->description)}}</textarea>
                            </div>

                            <hr>
                            <h3>Acceso Completo</h3>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="customRadioInline1" name="full-access" value="yes"
                                    class="custom-control-input" @if ($role['full-access']=="yes" ) checked
                                    @elseif(old('full-access')=="yes" ) checked @endif disabled>
                                <label class="custom-control-label" for="customRadioInline1">Si</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="customRadioInline2" name="full-access" value="no"
                                    class="custom-control-input" @if($role['full-access']=="no" ) checked
                                    @elseif(old('full-access')=="no" ) checked @endif disabled>
                                <label class="custom-control-label" for="customRadioInline2">No</label>
                            </div>
                            <hr>
                            <h3>Lista de Permisos</h3>

                            @foreach ($permissions as $permission)
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="permission_{{$permission->id}}"
                                    value="{{ $permission->id }}" name="permission[]" @if (is_array(old('permission'))
                                    && in_array("$permission->id",old('permission')))
                                checked
                                @elseif(is_array($permission_role) && in_array("$permission->id",$permission_role))
                                checked
                                @endif
                                disabled>
                                <label class="custom-control-label" for="permission_{{$permission->id}}">
                                    {{$permission->id}}
                                    -
                                    {{$permission->name}}
                                    <em>({{$permission->description}})</em>

                                </label>
                            </div>
                            @endforeach

                            <br>
                            <a href="{{ route('role.edit',$role->id) }}" class="btn btn-info">Editar</a>
                            <a href="{{ route('role.index') }}" class="btn btn-danger">Atras</a>


                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection