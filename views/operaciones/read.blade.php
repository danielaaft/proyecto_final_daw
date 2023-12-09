@extends('layouts.master')
@section('header_css')
<style>
    .custom-table th {
        font-weight: bold;
        /* Hace que los encabezados sean negrita */
    }

    .custom-table td {
        padding: 0.3rem;
        /* Ajusta el relleno de las celdas para reducir la separación */
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
<!--toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Operación #{{$operacion->id}}</h1>
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
                <li class="breadcrumb-item text-muted">Operación individual</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">
        </div>
    </div>
</div>
<!--/toolbar-->
<div id="kt_app_content_container" class="app-container container-xxl">
    <!--test-->
    <div class="card">
        <!--begin::Body-->
        <div class="card-body p-lg-20 pb-lg-0">
            <!--begin::Layout-->
            <div class="d-flex flex-column flex-xl-row">
                <!--begin::Content-->
                <div class="flex-lg-row-fluid me-xl-15">
                    <!--begin::Post content-->
                    <div class="mb-17">
                        <!--begin::Wrapper-->
                        <div class="mb-8">
                            <!--begin::Info-->
                            <div class="d-flex flex-wrap mb-6">
                                <!--begin::Item-->
                                <div class="me-9 my-1">
                                    <!--begin::Icon-->
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
                                    <span class="svg-icon svg-icon-primary svg-icon-2 me-1">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="2" y="2" width="9" height="9" rx="2" fill="currentColor"></rect>
                                            <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="currentColor"></rect>
                                            <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2" fill="currentColor"></rect>
                                            <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2" fill="currentColor"></rect>
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                    <!--end::Icon-->
                                    <!--begin::Label-->
                                    <span class="fw-bold text-gray-400">{{$operacion->created_at}}</span>
                                    <!--end::Label-->
                                </div>

                                <!--end::Item-->
                            </div>
                            <!--end::Info-->
                            <!--begin::Title-->
                            <a href="javascript:;" class="text-dark text-hover-primary fs-2 fw-bold">Operación de reconocimiento #{{$operacion->id}}
                                <span class="fw-bold text-muted fs-5 ps-1">{{(new DateTime($operacion->updated_at))->diff(new DateTime($operacion->created_at))->format('%i')}} min</span></a>
                            <!--end::Title-->
                            <!--begin::Container-->
                            <?php 
                            //fix temporal
                            $tipos = [];
                            ?>
                            @foreach($operacion->imagenes()->get() as $img)
                            <?php if(in_array($img->tipo, $tipos)) continue; ?>
                            <div class="overlay mt-8">
                                <!--begin::Image-->
                                <div class="bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-350px" style="background-image:url('{{$img->url}}')"></div>
                                <!--end::Image-->
                            </div>
                            <?php $tipos[] = $img->tipo;?>
                            @endforeach
                            <!--end::Container-->
                        </div>
                        <!--end::Wrapper-->
                        
                    </div>
                    <!--end::Post content-->
                </div>
                <div class="flex-column flex-lg-row-auto w-100 w-xl-300px mb-10">
                    <div class="mb-16">
                        @if($operacion->estado == 'Verificacion OK')
                            <span class="badge badge-success">Verificacion OK</span>
                        @elseif($operacion->estado == 'Verificacion KO')
                            <span class="badge badge-danger">Verificacion KO</span>
                        @else
                            <span class="badge badge-warning">Pendiente</span>
                        @endif
                    <hr>
                        <h4 class="text-dark mb-7">Datos operación</h4>
                        <div class="d-flex flex-stack fw-semibold fs-5 text-muted mb-4">
                            <a title="Nombres" href="javascript:;" class="text-muted text-hover-primary pe-2"><i class="bi bi-person"></i></a>
                            <div class="m-0">{{$operacion->nombres}}</div>
                        </div>
                        <div class="d-flex flex-stack fw-semibold fs-5 text-muted mb-4">
                            <a title="Apellidos" href="javascript:;" class="text-muted text-hover-primary pe-2"><i class="bi bi-person"></i></a>
                            <div class="m-0">{{$operacion->apellidos}}</div>
                        </div>
                        <div class="d-flex flex-stack fw-semibold fs-5 text-muted mb-4">
                            <a title="Nacionalidad" href="javascript:;" class="text-muted text-hover-primary pe-2"><i class="bi bi-globe2"></i></a>
                            <div class="m-0">{{$operacion->nacionalidad}}</div>
                        </div>
                        <div class="d-flex flex-stack fw-semibold fs-5 text-muted mb-4">
                            <a title="País expedidor" href="javascript:;" class="text-muted text-hover-primary pe-2"><i class="bi bi-geo-fill"></i></a>
                            <div class="m-0">{{$operacion->pais}}</div>
                        </div>
                        <div class="d-flex flex-stack fw-semibold fs-5 text-muted mb-4">
                            <a title="Tipo documento" href="javascript:;" class="text-muted text-hover-primary pe-2"><i class="bi bi-file-text-fill"></i></a>
                            <div class="m-0">{{$operacion->tipo_doc}}</div>
                        </div>
                        <div class="d-flex flex-stack fw-semibold fs-5 text-muted mb-4">
                            <a title="Número de documento" href="javascript:;" class="text-muted text-hover-primary pe-2"><i class="bi bi-clipboard2-fill"></i></a>
                            <div class="m-0">{{$operacion->numero_documento}}</div>
                        </div>
                        <div class="d-flex flex-stack fw-semibold fs-5 text-muted mb-4">
                            <a title="Sexo" href="javascript:;" class="text-muted text-hover-primary pe-2"><i class="bi bi-gender-ambiguous"></i></a>
                            <div class="m-0">{{$operacion->sexo}}</div>
                        </div>
                        <div class="d-flex flex-stack fw-semibold fs-5 text-muted mb-4">
                            <a title="Fecha de nacimiento" href="javascript:;" class="text-muted text-hover-primary pe-2"><i class="bi bi-calendar"></i></a>
                            <div class="m-0">{{$operacion->fecha_nacimiento}}</div>
                        </div>
                        <div class="d-flex flex-stack fw-semibold fs-5 text-muted mb-4">
                            <a title="Fecha de caducidad documento" href="javascript:;" class="text-muted text-hover-primary pe-2"><i class="bi bi-calendar-range"></i></a>
                            <div class="m-0">{{$operacion->fecha_caducidad}}</div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection