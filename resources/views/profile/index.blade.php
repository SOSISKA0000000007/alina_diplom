@extends('profile.layout')

@section('profile-content')
<div class="profile-info-wrapper">
    <h2>Личные данные</h2>

    <div class="profile-info">
        <div class="profile-field">
            <span class="field-label">Имя:</span>
            <span class="field-value">{{ auth()->user()->name }}</span>
        </div>

        <div class="profile-field">
            <span class="field-label">Email:</span>
            <span class="field-value">{{ auth()->user()->email }}</span>
        </div>

        <div class="profile-field">
            <span class="field-label">Телефон:</span>
            <span class="field-value">{{ auth()->user()->phone ?? 'Не указан' }}</span>
        </div>
    </div>
</div>
@endsection
