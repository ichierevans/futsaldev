@extends('users.layout')

@section('styles')
<style>
    .profile-container {
        background-color: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        position: relative;
        z-index: 2;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .profile-welcome {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 30px;
        color: #333;
    }
    
    .profile-field {
        display: flex;
        margin-bottom: 20px;
    }
    
    .profile-label {
        background-color: #e9ecef;
        padding: 12px 20px;
        border-radius: 5px;
        width: 40%;
        text-align: center;
        font-weight: 500;
        color: #333;
    }
    
    .profile-value {
        background-color: #4caf50;
        padding: 12px 20px;
        border-radius: 5px;
        width: 60%;
        text-align: center;
        font-weight: 500;
        color: white;
        margin-left: 10px;
    }
</style>
@endsection

@section('content')
<div class="profile-container">
    <div class="profile-welcome">Hello, Selamat Datang..</div>
    
    <div class="profile-field">
        <div class="profile-label">Nama Lengkap</div>
        <div class="profile-value">{{ $user->name ?? 'Cristiano Ronaldo' }}</div>
    </div>
    
    <div class="profile-field">
        <div class="profile-label">Email</div>
        <div class="profile-value">{{ $user->email ?? 'cris@gmail.com' }}</div>
    </div>
    
    <div class="profile-field">
        <div class="profile-label">No.Hp/Whatsapp</div>
        <div class="profile-value">{{ $user->phone ?? '+12 929 3321 1122' }}</div>
    </div>
</div>
@endsection