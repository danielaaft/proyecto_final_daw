@extends('layouts.master')
@section('header_css')
@endsection
@section('header_scripts')
@endsection
@section('footer_scripts')
@endsection

@section('content')
@include('layouts.alerts')
<!--toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Listado de usuarios</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="{{$url}}" class="text-muted text-hover-primary">Inicio</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">
                <a href="{{$url}}/usuarios" class="text-muted text-hover-primary">Usuarios</a> 
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">Listado</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <?php /*<a href="{{$url}}/usuarios/create" class="btn btn-sm fw-bold bg-body btn-active-color-primary">Nuevo usuario</a>*/ ?>

            <a href="{{$url}}/usuarios/create" class="btn btn-sm fw-bold btn-primary">Nuevo usuario</a>
        </div>
    </div>
</div>
<!--/toolbar-->
<div id="kt_app_content_container" class="app-container container-xxl">
    <div class="card mb-xl-9">
        <div class="card-body pt-9 pb-0">
            <div class="row">
                <div class="table-responsive">
                    <table class="table ">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Email</th>
                                <th>Empresa</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $usuario)
                            <tr>
                                <td>{{ $usuario->id }}</td>
                                <td>{{ $usuario->nombre }}</td>
                                <td>{{ $usuario->apellidos }}</td>
                                <td>{{ $usuario->email }}</td>
                                <td>{{ $usuario->empresa }}</td>
                                <td>
                                    <a href="{{$url}}/usuarios/update/{{ $usuario->id }}" class="btn btn-primary">Editar</a>
                                    <form action="{{$url}}/usuarios/delete/{{$usuario->id}}" method="post" style="display:inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection