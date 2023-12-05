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
        <form method="post" action="{{$url_form_post}}">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresa tu nombre"
                    required>
            </div>
            <div class="mb-3">
                <label for="apellidos" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" placeholder="Ingresa tus apellidos"
                    required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                    placeholder="Ingresa tu correo electrónico" required>
            </div>
            <div class="mb-3">
                <label for="empresa" class="form-label">Nombre de la Empresa</label>
                <input type="text" class="form-control" id="empresa" name="empresa"
                    placeholder="Ingresa el nombre de tu empresa" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password"
                    placeholder="Ingresa tu contraseña" required>
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>
    @endsection
    <!-- Agrega el enlace al archivo JS de Bootstrap y Popper.js (necesario para algunos componentes de Bootstrap) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

