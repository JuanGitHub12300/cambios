@extends('tablar::auth.layout')

@section('content')
<div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; background-color: #e1ecf4;"> <!-- Fondo un poco más oscuro -->
    <div style="width: 100%; max-width: 400px; padding: 20px; background-color: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <div style="text-align: center;">
            <img src="{{ asset('assets/Ferro.png') }}" alt="Ferro Center Logo" style="height: 50px;">
            <h2 style="font-size: 24px; margin-top: 20px; color: #202124;">Recuperación de la cuenta</h2>
        </div>
        <p style="text-align: center; color: #5f6368; margin-top: 20px;">Ingresa tu dirección de correo electrónico para recuperar tu cuenta.</p>
        <form action="{{ route('password.email') }}" method="post">
            @csrf
            <input
                type="email"
                name="email"
                class="form-control"
                style="width: 100%; padding: 10px; margin-top: 20px; border: 1px solid #dfe1e5; border-radius: 4px;"
                placeholder="tu@correo.com"
                required
            >
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px; border-radius: 4px; margin-top: 20px; background-color: #1a73e8; border: none; color: white;">
                Enviar código de verificación
            </button>
        </form>
        <div style="text-align: center; margin-top: 20px;">
            ¿Olvidaste tu contraseña?
            <a href="{{ route('login') }}" style="color: #1a73e8;">Volver al inicio de sesión</a>
        </div>
    </div>
</div>
@endsection





