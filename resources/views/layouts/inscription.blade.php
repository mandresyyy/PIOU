<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.partials.pwa')
    @include('layouts.partials.metadata')
    @vite('resources/css/app.scss')
    @vite('resources/css/inscription.css')
</head>

<body>

<div class="main">

    <!-- Sign up form -->
    <section class="signup">
        <div class="container">
            <div class="signup-content">
                <div class="signup-form">
                    <h2 class="form-title">Inscription</h2>
                    @yield('contenue')
                </div>
                <div class="signup-image">
                    <figure><img src="{{ asset('/images/signup-image.jpg') }}" alt="Sign up image"></figure>
                    <a href="{{ route('login')}}" class="signup-image-link">J'ai deja un compte</a>
                </div>
            </div>
        </div> 
    </section>

</div>

<!-- JS -->
<!-- <script src="vendor/jquery/jquery.min.js"></script>
<script src="js/main.js"></script> -->
</body>

<!-- This template was made by Colorlib (https://colorlib.com) -->

</html>