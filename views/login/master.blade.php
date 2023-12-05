<!DOCTYPE html>
<html lang="es">
<head>
    <title>Login</title>
    <meta charset="utf-8" />
    <meta name="description" content="{{$html_title}}" />
    <meta name="keywords" content="{{$html_keywords}}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="{{$html_locale}}" />
    <meta property="og:type" content="{{$html_type}}" />
    <meta property="og:title" content="{{$html_url}}" />
    <meta property="og:url" content="{{$html_url}}" />
    <meta property="og:site_name" content="{{$html_title}}" />
    <link rel="canonical" href="{{$html_canonical}}" />
    <link rel="shortcut icon" href="{{$url}}/public/media/logos/favicon.ico" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{$url}}/public/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="{{$url}}/public/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    @yield('header_css')
    @yield('header_scripts')
</head>

<body id="kt_body" class="app-blank app-blank bgi-size-cover bgi-position-center bgi-no-repeat">
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-theme-mode");
            } else {
                if (localStorage.getItem("data-theme") !== null) {
                    themeMode = localStorage.getItem("data-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-theme", themeMode);
        }
    </script>
    <!--end::Theme mode setup on page load-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Page bg image-->
        <style>
            body {
                background-image: url('{{$url}}/public/assets/media/auth/ciberseguridad.jpg');
            }
        </style>
        <!--end::Page bg image-->
        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-column-fluid flex-lg-row">
            <!--begin::Aside-->
            <!--begin::Aside-->
<div class="d-flex flex-center w-lg-50 pt-15 pt-lg-0 px-10 flex-lg-start"> <!-- Cambio de clase flex-lg-start -->
    <!--begin::Aside-->
    <div class="d-flex flex-center flex-lg-start flex-column text-left"> <!-- Añadido text-left -->
        <!--begin::Logo-->
        <a href="{{$url}}" class="mb-7">
            <img alt="Logo" src="{{$url}}/public/assets/media/logos/custom-3.png" style="width:500px;" />
        </a>
        <!--end::Logo-->
        <!--begin::Title-->
        <h2 class="text-white fw-normal m-0">Panel administración</h2>
        <!--end::Title-->
    </div>
    <!--begin::Aside-->
</div>
<!--begin::Body-->

            <!--begin::Aside-->
            <!--begin::Body-->
            @yield('content')
            <!--end::Body-->
        </div>
        <!--end::Authentication - Sign-in-->
    </div>
    <!--end::Root-->
    <!--begin::Javascript-->
    <script>
        var hostUrl = "{{$url}}/public/";
    </script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{$url}}/public/assets/plugins/global/plugins.bundle.js"></script>
    <script src="{{$url}}/public/assets/js/scripts.bundle.js"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Custom Javascript(used for this page only)-->
    <script src="{{$url}}/public/assets/js/custom/authentication/sign-in/general.js"></script>
    <!--end::Custom Javascript-->
    @yield('footer_scripts')
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>