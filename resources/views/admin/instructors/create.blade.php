@extends('layouts.hf')

@section('content')
    <div class="container">
        <h1>Создать инструктора</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.instructors.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Имя</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                @error('name')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="experience" class="form-label">Опыт</label>
                <textarea name="experience" id="experience" class="form-control" rows="4" required>{{ old('experience') }}</textarea>
                @error('experience')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="about" class="form-label">О себе</label>
                <textarea name="about" id="about" class="form-control" rows="6" required>{{ old('about') }}</textarea>
                @error('about')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="photo" class="form-label">Фотография</label>
                <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                @error('photo')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Создать</button>
        </form>
    </div>
@endsection
