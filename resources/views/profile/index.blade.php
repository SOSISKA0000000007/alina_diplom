@extends('profile.layout')

@section('profile-content')
    <div class="profile-info-wrapper">
        <h2>Личные данные</h2>

        <form action="{{ route('profile.update') }}" method="POST" style="display: flex; flex-direction: column; gap: 30px">
            @csrf
            @method('PUT')

            <div class="profile-field">
                <label for="name" class="field-label">Имя:</label>
                <input type="text" name="name" id="name" value="{{ auth()->user()->name }}" class="field-input @error('name') is-invalid @enderror">
                @error('name')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="profile-field">
                <label for="email" class="field-label">Email:</label>
                <input type="email" name="email" id="email" value="{{ auth()->user()->email }}" class="field-input @error('email') is-invalid @enderror">
                @error('email')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="profile-field">
                <label for="phone" class="field-label">Телефон:</label>
                <input type="text" name="phone" id="phone" value="{{ auth()->user()->phone ?? '' }}" class="field-input @error('phone') is-invalid @enderror" placeholder="Не указан">
                @error('phone')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        </form>
    </div>
    <style>
        .profile-field input{
            font: 20px com-reg;
            margin: 0;
            min-width: 100px;
            color: var(--black);
            font-weight: 500;
        }
    </style>
@endsection
