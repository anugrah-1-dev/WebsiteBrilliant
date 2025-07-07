@extends('adminlte::page')

@section('title', 'Edit Program Online')

@section('content_header')
    <h1>Edit Program Online: {{ $online->nama }}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.online.update', $online) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <x-adminlte-input name="nama" label="Nama Program" value="{{ old('nama', $online->nama) }}" required />

            <x-adminlte-input name="slug" label="Slug" value="{{ old('slug', $online->slug) }}" required />

            <x-adminlte-input name="lama_program" label="Durasi Program" value="{{ old('lama_program', $online->lama_program) }}" required />

            <x-adminlte-input name="kategori" label="Kategori" value="{{ old('kategori', $online->kategori) }}" required />

            <x-adminlte-input name="harga" label="Harga (Rp)" type="number" min="0" value="{{ old('harga', $online->harga) }}" required />

            <x-adminlte-textarea name="features_program" label="Fitur Program" rows="4">
                {{ old('features_program', is_array($online->features_program) ? implode("\n", $online->features_program) : '') }}
            </x-adminlte-textarea>

            <x-adminlte-select name="is_active" label="Status Program" required>
                <option value="1" {{ old('is_active', $online->is_active) == '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ old('is_active', $online->is_active) == '0' ? 'selected' : '' }}>Nonaktif</option>
            </x-adminlte-select>

            {{-- Gambar Thumbnail --}}
            <div class="form-group">
                <label for="thumbnail">Thumbnail</label>
                @if ($online->thumbnail)
                    <div class="mb-2">
                        <img src="{{ asset('uploads/thumbnails/' . $online->thumbnail) }}" width="150" class="img-thumbnail">
                        <div class="form-check mt-2">
                            <input type="checkbox" name="hapus_thumbnail" id="hapus_thumbnail" class="form-check-input">
                            <label for="hapus_thumbnail" class="form-check-label">Hapus thumbnail saat disimpan</label>
                        </div>
                    </div>
                @endif
                <input type="file" name="thumbnail" id="thumbnail" class="form-control-file @error('thumbnail') is-invalid @enderror">
                <small class="form-text text-muted">Format: jpg, jpeg, png, webp. Max: 5MB</small>
                @error('thumbnail')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            {{-- Tombol Aksi --}}
            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.online.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@stop

@section('css')
    <style>
        .img-thumbnail {
            max-height: 200px;
            object-fit: cover;
        }
    </style>
@stop
