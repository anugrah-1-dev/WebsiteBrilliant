@extends('adminlte::page')

@section('title', 'Edit Program Offline')

@section('content_header')
    <h1 class="m-0 text-dark">Edit Program Offline</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-10">
            <x-adminlte-card theme="lightblue" title="Form Edit Program Offline">

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
                <form action="{{ route('admin.offline.update', $offline) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <x-adminlte-input name="nama" label="Nama Program" placeholder="Masukkan nama program"
                        value="{{ old('nama', $offline->nama) }}" required />

                    <x-adminlte-input name="slug" label="Slug (URL Friendly)"
                        placeholder="contoh-program-offline" value="{{ old('slug', $offline->slug) }}" required />

                    <x-adminlte-input name="lama_program" label="Durasi Program"
                        placeholder="Contoh: 2 bulan / 10 minggu" value="{{ old('lama_program', $offline->lama_program) }}" required />

                    <x-adminlte-input name="kategori" label="Kategori Program"
                        placeholder="Contoh: Intensif, Reguler, Khusus" value="{{ old('kategori', $offline->kategori) }}" required />

                    <x-adminlte-input name="harga" label="Harga (Rp)"
                        placeholder="Contoh: 1500000" type="number" value="{{ old('harga', $offline->harga) }}" required />

                    <x-adminlte-textarea name="features_program" label="Fitur Program (Pisahkan dengan enter)"
                        rows="4" placeholder="Contoh:\n✅ Sertifikat\n✅ Materi cetak\n✅ Tutor berpengalaman"
                        required>{{ old('features_program', $offline->features_program) }}</x-adminlte-textarea>

                    <x-adminlte-input name="lokasi" label="Lokasi Program" placeholder="Masukkan lokasi"
                        value="{{ old('lokasi', $offline->lokasi) }}" required />

                    <x-adminlte-input name="jadwal_mulai" label="Jadwal Mulai" type="date"
                        value="{{ old('jadwal_mulai', $offline->jadwal_mulai) }}" required />

                    <x-adminlte-input name="jadwal_selesai" label="Jadwal Selesai" type="date"
                        value="{{ old('jadwal_selesai', $offline->jadwal_selesai) }}" required />

                    <x-adminlte-input name="kuota" label="Kuota Peserta" type="number"
                        value="{{ old('kuota', $offline->kuota) }}" required />

                    <x-adminlte-select name="is_active" label="Status Program" required>
                        <option value="1" {{ old('is_active', $offline->is_active) == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('is_active', $offline->is_active) == '0' ? 'selected' : '' }}>Nonaktif</option>
                    </x-adminlte-select>

                    <x-adminlte-input name="thumbnail" label="Thumbnail Program (Gambar)" type="file"
                        accept="image/*" />

                    @if ($offline->thumbnail)
                        <div class="mt-2">
                            <p>Thumbnail Saat Ini:</p>
                            <img src="{{ asset('uploads/programs/' . $offline->thumbnail) }}" alt="Thumbnail"
                                 class="img-thumbnail" width="200">
                        </div>
                    @endif

                    <div class="mt-3">
                        <x-adminlte-button type="submit" theme="primary" icon="fas fa-save" label="Perbarui Program" />
                        <a href="{{ route('admin.offline.index') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-arrow-left"></i> Batal
                        </a>
                    </div>
                </form>

            </x-adminlte-card>
        </div>
    </div>
@stop
