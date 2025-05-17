@extends('layouts.hf')

@section('content')
    <div class="container">
        <div class="admin-layout">
        @include('components.admin-sidebar')


        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.instructors.store') }}" method="POST" enctype="multipart/form-data" class="admin-form container">
            @csrf

            <div class="admin-form-container">
                <label for="name" class="admin-label">Имя</label>
                <input type="text" name="name" id="name" class="admin-input" value="{{ old('name') }}" required>
                @error('name')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="admin-form-container">
                <label for="experience" class="admin-label">Опыт</label>
                <textarea name="experience" id="experience" class="admin-input" rows="4" required>{{ old('experience') }}</textarea>
                @error('experience')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="admin-form-container">
                <label for="about" class="admin-label">О себе</label>
                <textarea name="about" id="about" class="admin-input" rows="6" required>{{ old('about') }}</textarea>
                @error('about')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="admin-form-container">
                <label for="photo" class="admin-label">Фотография</label>
                <input type="file" name="photo" id="photo" class="admin-input-photo" accept="image/*">
                @error('photo')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Создать</button>
        </form>
        </div>
    </div>
@endsection
