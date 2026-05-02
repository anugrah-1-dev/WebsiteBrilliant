@extends('adminlte::page')

@section('title', 'Tambah Galeri')

@section('content_header')
    <h1>Tambah Galeri Baru</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ups!</strong> Ada kesalahan dalam input:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@stop

@section('content')
    <form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">

                <div class="form-group">
                    <label for="title">Judul Galeri</label>
                    <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi (Opsional)</label>
                    <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" class="form-control">
                        <option value="1" selected>Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="images">Upload Foto <small class="text-muted">(bisa lebih dari satu, maks 5MB/foto)</small></label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                </div>

                <div class="form-group">
                    <label for="video_urls">Link Video YouTube <small class="text-muted">(opsional, satu link per baris)</small></label>
                    <textarea name="video_urls" class="form-control" rows="4"
                        placeholder="https://www.youtube.com/watch?v=xxxxx&#10;https://youtu.be/xxxxx">{{ old('video_urls') }}</textarea>
                    <small class="text-muted">Masukkan URL YouTube, satu link per baris.</small>
                </div>

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan Galeri</button>
                <a href="{{ route('admin.galleries.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </form>
@stop

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
@endif
