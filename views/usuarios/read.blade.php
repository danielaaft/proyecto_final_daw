@extends('layouts.master')
@section('header_css')
@endsection
@section('header_scripts')
@endsection
@section('footer_scripts')
@endsection

@section('content')
@include('layouts.alerts')
<div class="container mt-5">
    <h2>Listado de Usuarios</h2>
    <table class="table">
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
                        <form action="{{ $config->base_url }}/proyecto_daniela/v1/update" method="post" style="display: inline;">
                            @csrf
                            <input type="hidden" name="id" value="{{ $usuario->id }}">
                        </form>
                        
                        <a href="{{ $config->base_url }}/proyecto_daniela/v1/update/{{ $usuario->id }}" class="btn btn-primary">Actualizar</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
