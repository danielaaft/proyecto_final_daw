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
        <h1>Listado de Operaciones</h1>
        <table class="table">
            <thead>
                <tr>
                <th style="font-weight: bold;">ID</th>
                    <th style="font-weight: bold;">Nombres</th>
                    <th style="font-weight: bold;">Apellidos</th>
                    <th style="font-weight: bold;">Estado</th>
                    <th style="font-weight: bold;">Fecha de Creación</th>
                    <th style="font-weight: bold;">Fecha de Actualización</th>
                    <th style="font-weight: bold;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($operaciones as $operacion)
                    <tr>
                        <td>{{ $operacion->id }}</td>
                        <td>{{ $operacion->nombres }}</td>
                        <td>{{ $operacion->apellidos }}</td>
                        <td>{{ $operacion->estado }}</td>
                        <td>{{ $operacion->created_at }}</td>
                        <td>{{ $operacion->updated_at }}</td>
                        <td><a href="{{ $config->base_url }}/proyecto_daniela/v1/op/list/{{ $operacion->id }}" class="btn btn-primary">Ver Detalles</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection