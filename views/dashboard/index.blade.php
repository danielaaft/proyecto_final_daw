@extends('layouts.master')
@section('header_css')
@endsection
@section('header_scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>


@endsection
@section('footer_scripts')

<script>
	// Configurar los datos para el gráfico
	var ctx = document.getElementById('operacionesChart').getContext('2d');
	var operacionesData = {
		labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
		datasets: [{
			label: 'Operaciones por Mes',
			data: <?php echo json_encode(array_values($operacionesPorMes)); ?>,
			backgroundColor: 'rgba(75, 192, 192, 0.2)', // Color de fondo
			borderColor: 'rgba(75, 192, 192, 1)', // Color del borde
			borderWidth: 1
		}]
	};

	// Configurar opciones del gráfico
	var operacionesOptions = {
		scales: {
			y: {
				beginAtZero: true
			}
		}
	};

	// Crear el gráfico
	var operacionesChart = new Chart(ctx, {
		type: 'bar',
		data: operacionesData,
		options: operacionesOptions
	});
</script>
@endsection

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
	<!--begin::Content wrapper-->
	<div class="d-flex flex-column flex-column-fluid">
		<!--begin::Toolbar-->
		<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
			<!--begin::Toolbar container-->
			<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
				<!--begin::Page title-->
				<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
					<!--begin::Title-->
					<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Hello {{$_SESSION['nombre']}}</h1>
					<!--end::Title-->
					<!--begin::Breadcrumb-->
					<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
						<!--begin::Item-->
						<li class="breadcrumb-item text-muted">
							<a href="{{$url}}" class="text-muted text-hover-primary">Inicio</a>
						</li>
						<!--end::Item-->
						<!--begin::Item-->
						<li class="breadcrumb-item">
							<span class="bullet bg-gray-400 w-5px h-2px"></span>
						</li>
						<!--end::Item-->
						<!--begin::Item-->
						<li class="breadcrumb-item text-muted">Estadísticas</li>
						<!--end::Item-->
					</ul>
					<!--end::Breadcrumb-->
				</div>
				<!--end::Page title-->
				<div class="d-flex align-items-center gap-2 gap-lg-3">
					<?php /*<a href="{{$url}}/usuarios/create" class="btn btn-sm fw-bold bg-body btn-active-color-primary">Nuevo usuario</a>*/ ?>

					<button onclick="$('#miModal').modal('show');" class="btn btn-sm fw-bold btn-primary">Lanzar operación de prueba</button>
					<!-- Modal -->
					<div class="modal" id="miModal">
						<div class="modal-dialog">
							<div class="modal-content">

								<!-- Cabecera del Modal -->
								<div class="modal-header">
									<h4 class="modal-title">Probar proceso</h4>
								</div>

								<!-- Contenido del Modal -->
								<div class="modal-body">
									<img id="codigo_qr" src="{{$qr_src}}" alt="QR" class="marco--qr"><br>
									<a href="{{$url_demo}}" target="_blank">Abrir en este navegador</a>
									<hr>
									Consultar estado #{{$uuid}}: <button class="btn btn-sm fw-bold btn-success" onclick="check()">Check</button>
									<textarea class="form-control" id="respuesta_info">
										Estado operación
									</textarea>
									<script>
										function check() {
											$.get('{{$url_estado_demo}}', function(data) {
												console.log(data);
													// Formatear la respuesta JSON y mostrarla en el textarea
													var formattedResponse = JSON.stringify(data, null, 2);
													$('#respuesta_info').val(formattedResponse);
												})
												.fail(function() {
													$('#respuesta_info').val('Error en la solicitud GET');
												});
										}
									</script>
								</div>

								<!-- Pie del Modal -->
								<div class="modal-footer">
									<button type="button" class="btn btn-danger" onclick="$('#miModal').modal('hide');">Cerrar</button>
								</div>

							</div>
						</div>
					</div>

				</div>
			</div>
			<!--end::Toolbar container-->
		</div>
		<!--end::Toolbar-->
		<!--begin::Content-->
		<div id="kt_app_content" class="app-content flex-column-fluid">
			<!--begin::Content container-->
			<div id="kt_app_content_container" class="app-container container-xxl">
				<canvas id="operacionesChart" width="400" height="200" style="max-height: 500px;"></canvas>
			</div>
			<!--end::Content container-->
			<div id="kt_app_content_container" class="app-container container-xxl">
				<h3>Últimas operaciones</h3>
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
									<td><a href="{{$url}}/operaciones/{{ $operacion->id }}" class="btn btn-primary">Ver Detalles</a></td>
								</tr>
								@endforeach
							</tbody>
						</table>
						<br>
						<br>
					</div>


				</div>
			</div>
		</div>
		<!--end::Content-->
	</div>
	<!--end::Content wrapper-->
</div>
@endsection