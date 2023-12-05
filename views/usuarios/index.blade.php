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
                            <a href="{{ $config->base_url }}/proyecto_daniela/v1/update/{{ $usuario->id }}" class="btn btn-primary">Editar</a>
                            <form action="{{ $config->base_url }}/proyecto_daniela/v1/delete/{{$usuario->id}}" method="post" style="display:inline">
                                @csrf
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection