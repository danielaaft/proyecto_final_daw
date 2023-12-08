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
<div class="container mt-5">
    <h2>Actualizar Usuario</h2>
    <form method="post" action="{{ $config->base_url }}/proyecto_daniela/v1/update/{{ $usuario->id }}">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $usuario->nombre }}" >
        </div>
        <div class="mb-3">
            <label for="apellidos" class="form-label">Apellido</label>
            <input type="text" class="form-control" id="apellidos" name="apellidos" value="{{ $usuario->apellidos }}" >
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $usuario->email }}" >
        </div>
        <div class="mb-3">
            <label for="empresa" class="form-label">Nombre de la Empresa</label>
            <input type="text" class="form-control" id="empresa" name="empresa" value="{{ $usuario->empresa }}" >
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" >
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
</div>
@endsection
