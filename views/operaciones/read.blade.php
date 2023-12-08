@extends('layouts.master')
@section('header_css')
<style>
    .custom-table th {
        font-weight: bold; /* Hace que los encabezados sean negrita */
    }

    .custom-table td {
        padding: 0.3rem; /* Ajusta el relleno de las celdas para reducir la separación */
    }
    .th {
        font-weight: bold;
    }
</style>
@endsection
@section('header_scripts')
@endsection
@section('footer_scripts')
@endsection

@section('content')
@include('layouts.alerts')

<div class="container mt-5">
    <h1>Datos Operación {{ $operacion->nombres }}</h1>

    <!-- Datos de Operación -->
    
    <table class="table custom-table">
        <tbody>
            <tr>
                <th style="font-weight: bold;" >ID de Operación</th>
                <td>{{ $operacion->id }}</td>
            </tr>
            <tr>
                <th style="font-weight: bold;">Nombres</th>
                <td>{{ $operacion->nombres }}</td>
            </tr>
            <tr>
                <th style="font-weight: bold;">Apellidos</th>
                <td>{{ $operacion->apellidos }}</td>
            </tr>
            <tr>
                <th style="font-weight: bold;">Fecha de Creación</th>
                <td>{{ $operacion->created_at }}</td>
            </tr>
            <tr>
                <th style="font-weight: bold;">Fecha de Actualización</th>
                <td>{{ $operacion->updated_at }}</td>
            </tr>
            <tr>
                <th style="font-weight: bold;">Nacionalidad</th>
                <td>{{ $operacion->nacionalidad }}</td>
            </tr>
            <tr>
                <th style="font-weight: bold;">País</th>
                <td>{{ $operacion->pais }}</td>
            </tr>
            <tr>
                <th style="font-weight: bold;">Tipo de Documento</th>
                <td>{{ $operacion->tipo_doc }}</td>
            </tr>
            <tr>
                <th style="font-weight: bold;">Número de Documento</th>
                <td>{{ $operacion->numero_documento }}</td>
            </tr>
            <tr>
                <th style="font-weight: bold;">Sexo</th>
                <td>{{ $operacion->sexo }}</td>
            </tr>
            <tr>
                <th style="font-weight: bold;">Fecha de Nacimiento</th>
                <td>{{ $operacion->fecha_nacimiento }}</td>
            </tr>
            <tr>
                <th style="font-weight: bold;">Fecha de Caducidad</th>
                <td>{{ $operacion->fecha_caducidad }}</td>
            </tr>
            <tr>
                <th style="font-weight: bold;">Estado</th>
                <td>{{ $operacion->estado }}</td>
            </tr>
            <tr>
                <th style="font-weight: bold;">Reverso</th>
                <td>{{ $operacion->reverso }}</td>
            </tr>
            <tr>
                <th style="font-weight: bold;">Anverso</th>
                <td>{{ $operacion->anverso }}</td>
            </tr>
            <tr>
                <th style="font-weight: bold;">Selfie</th>
                <td>{{ $operacion->selfie }}</td>
            </tr>
            <tr>
                <th style="font-weight: bold;">UUID</th>
                <td>{{ $operacion->uuid }}</td>
            </tr>
            
            <!-- Agregar más filas según sea necesario para otros parámetros de Datos de Operación -->
        </tbody>
    </table>
    <form method="post" action="{{ $config->base_url }}/proyecto_daniela/v1/op/delete/{{ $operacion->id }}">
    @csrf
    <button type="submit" class="btn btn-danger">Eliminar Operación</button>
</form>

    <!-- Puedes agregar más contenido según sea necesario -->

</div>

@endsection
