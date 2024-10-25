@extends('layouts.inscription')

@section('contenue')
    <form action="{{ route('newUser') }}" autocomplete="off" method="POST"class="register-form" id="register-form">
        @csrf
        <div class="form-group">
            <label for="name"><i class="zmdi zmdi-account material-icons-name"></i></label>
            <input type="text" name="nom" id="name" : value="{{ old('nom') }}" placeholder="Votre nom" required/>
        </div>
        <div class="form-group">
            <label for="name"><i class="zmdi zmdi-account material-icons-name"></i></label>
            <input type="text" name="prenom" id="name" value="{{ old('prenom') }}" placeholder="Votre prénom" required/>
        </div>
        <div class="form-group">
            <label for="email"><i class="zmdi zmdi-email"></i></label>
            <input type="email" name="email" id="email" value="{{ old('email') }}"placeholder="Votre Email" required/>
        </div>
        <div class="form-group">
            <label for="pass"><i class="zmdi zmdi-lock"></i></label>
            <input type="password" name="pass" id="pass"  placeholder="Votre de mot de passe" required/>
        </div>
        <div class="form-group">
            <label for="re_pass"><i class="zmdi zmdi-lock-outline"></i></label>
            <input type="password" name="re_pass" id="re_pass"  placeholder="Repéter votre de mot de passe" required/>
        </div>
    
        <div class="form-group form-button">
            <input type="submit" name="signup" id="signup" class="form-submit" value="S'inscrire"/>
        </div>
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </form> 
@endsection