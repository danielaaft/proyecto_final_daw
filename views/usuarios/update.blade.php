@extends('layouts.master')
@section('header_css')
<!-- Agrega el enlace a los estilos de Bootstrap aquí si no lo has hecho ya -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
@endsection

@section('header_scripts')
@endsection

@section('footer_scripts')
<!-- Agrega el enlace a los scripts de Bootstrap aquí si no lo has hecho ya -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
@endsection

@section('content')
@include('layouts.alerts')
<!--toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Actualizar usuario</h1>
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
                <li class="breadcrumb-item text-muted">Actualizar</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">

        </div>
    </div>
</div>
<!--/toolbar-->
<div id="kt_app_content_container" class="app-container container-xxl">
    <div class="card mb-xl-9">
        <div class="card-body pt-2 pb-9">
            <div class="row">
                <div class="container mt-5">
                    <form method="post" action="{{$url}}/usuarios/update/{{ $usuario->id }}">
                        @csrf
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $usuario->nombre }}">
                        </div>
                        <div class="mb-3">
                            <label for="apellidos" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellidos" name="apellidos" value="{{ $usuario->apellidos }}">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $usuario->email }}">
                        </div>
                        <div class="mb-3">
                            <label for="empresa" class="form-label">Nombre de la Empresa</label>
                            <input type="text" class="form-control" id="empresa" name="empresa" value="{{ $usuario->empresa }}">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection