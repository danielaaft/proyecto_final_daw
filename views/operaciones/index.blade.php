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
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Listado de operaciones</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="{{$url}}" class="text-muted text-hover-primary">Inicio</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">
                    <a href="{{$url}}/operaciones" class="text-muted text-hover-primary">Operaciones</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">Listado</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">
        </div>
    </div>
</div>
<!--/toolbar-->
<div id="kt_app_content_container" class="app-container container-xxl">
    <div class="card">
        <div class="card-body">
        <form action="{{$url}}/operaciones" method="post">
            <div class="panel-body" id="panel_busqueda">
                <div class="row">
                    <div class="col-xs-4">
                        <input type="text" class="form-control" id="numero_documento" name="numero_documento"  placeholder="NIF..." value="{{Input::old('numero_documento')}}">
                    </div>

                    <div class="col-xs-4">
                        <select class="form-control" id="estado" name="estado">
                            <option value="">Filtro estado (Ver todos)</option>
                            @foreach($estados as $estado)
						    <option {{Input::old_selected('estado', $estado)}} value="{{$estado}}">{{$estado}}</option>
						    @endforeach
                        </select>
                    </div>
                    <div class="col-xs-4">
                        <input type="text" class="form-control" id="nombres" name="nombres"  placeholder="Nombre..." value="{{Input::old('nombres')}}">
                    </div>
                    <div class="col-xs-4">
                        <input type="text" class="form-control" id="apellidos" name="apellidos"  placeholder="Apellidos..." value="{{Input::old('apellidos')}}">
                    </div>
                </div>
                
                <div class="row">

                    <div class="col-xs-4">
                        <input type="date" class="form-control" title="Fecha desde..."
                               id="fecha_desde" name="fecha_desde" placeholder="Fecha desde"
                               value="{{Input::old('fecha_desde')}}"
                        />
                    </div>
                    <div class="col-xs-4">
                        <input type="date" class="form-control" title="Fecha hasta..."
                               id="fecha_hasta" name="fecha_hasta" placeholder="Fecha hasta"
                               vvalue="{{Input::old('fecha_hasta')}}"
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <input type="submit" value="Buscar" class="btn btn-success"/>
                    </div>
                </div>
            </div>
        </form>
        </div>
    </div>
    <div class="card mb-xl-9">
        <div class="card-body pt-9 pb-0">
            <div class="row">
                <div class="table-responsive">
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
                                <td><a href="{{$url}}/operaciones/op/{{ $operacion->id }}" class="btn btn-primary">Ver Detalles</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <?php echo Paginacion::render($total, $pagina, $limit, $url.'/operaciones/'.$search_data.'/'.$limit.'/'.str_replace('.','-',$key).'/'.$order.'/'); ?>
                    <br>
                </div>
                

            </div>
        </div>
    </div>
</div>
@endsection