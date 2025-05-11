@extends('layouts.header')

@section('content')
<div class="admin-container">
    <div class="admin-layout">
        @include('components.admin-sidebar')

        <div class="admin-content">
            <h1>Создание нового тура</h1>

            <form action="{{ route('admin.tours.store') }}" method="POST" class="admin-form">
                @csrf
                <div class="form-group">
                    <label for="title">Название тура</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="description">Описание тура</label>
                    <textarea id="description" name="description" class="form-control" rows="5" required></textarea>
                </div>

                <div class="form-group">
                    <label for="image">URL изображения</label>
                    <input type="text" id="image" name="image" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Создать тур</button>
            </form>
        </div>
    </div>
</div>

<style>
.admin-form {
    max-width: 600px;
    margin: 0 auto;
}

.form-group {
    margin-bottom: 20px;
}

.form-control {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.btn-primary {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-primary:hover {
    background-color: #0056b3;
}
</style>
@endsection
