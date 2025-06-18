@extends('profile.layout')

@section('profile-content')
    <div class="profile-info-wrapper">
        <h2>Личные данные</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="profile-field">
                <label for="name" class="field-label">Имя:</label>
                <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" class="field-input @error('name') is-invalid @enderror">
                @error('name')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="profile-field">
                <label for="email" class="field-label">Email:</label>
                <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" class="field-input @error('email') is-invalid @enderror">
                @error('email')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="profile-field">
                <label for="phone" class="field-label">Телефон:</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" class="field-input @error('phone') is-invalid @enderror" placeholder="Не указан">
                @error('phone')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        </form>
    </div>
@endsection
