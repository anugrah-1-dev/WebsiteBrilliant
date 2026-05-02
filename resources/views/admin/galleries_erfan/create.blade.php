@extends('adminlte::page')

@section('title', 'Tambah Galeri Erfan')

@section('content_header')
    <h1><i class="fas fa-plus mr-2 text-warning"></i> Tambah Galeri Erfan Baru</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ups!</strong> Ada kesalahan:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@stop

@section('content')
    <form action="{{ route('admin.galleries-erfan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card card-outline card-warning">
            <div class="card-body">

                <div class="form-group">
                    <label>Judul Galeri</label>
                    <input type="text" name="title" class="form-control" required value="{{ old('title') }}"
                        placeholder="Contoh: Kelas Bersama Erfan - April 2026">
                </div>

                <div class="form-group">
                    <label>Deskripsi <small class="text-muted">(opsional)</small></label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="1" selected>Aktif (tampil di landing page)</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Upload Foto <small class="text-muted">(bisa lebih dari satu, maks 5MB/foto)</small></label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                </div>

                <div class="form-group">
                    <label>Upload Video Lokal <small class="text-muted">(opsional, maks 100MB/video, format: mp4, mov, avi, webm)</small></label>
                    <div id="video-pairs-container"></div>
                    <button type="button" class="btn btn-sm btn-outline-secondary mt-1" onclick="addVideoPair()">
                        <i class="fas fa-plus"></i> Tambah Video
                    </button>
                </div>

                <div class="form-group">
                    <label>Link Video YouTube <small class="text-muted">(opsional, satu link per baris)</small></label>
                    <textarea name="video_urls" class="form-control" rows="4"
                        placeholder="https://www.youtube.com/watch?v=xxxxx&#10;https://youtu.be/xxxxx">{{ old('video_urls') }}</textarea>
                </div>

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-warning"><i class="fas fa-save mr-1"></i> Simpan</button>
                <a href="{{ route('admin.galleries-erfan.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </form>
@stop

@section('js')
<script>
function addVideoPair() {
    const container = document.getElementById('video-pairs-container');
    const idx = container.querySelectorAll('.video-pair').length;
    const html = `
        <div class="video-pair border rounded p-2 mb-2 bg-light">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <strong class="small">Video ${idx + 1}</strong>
                <button type="button" class="btn btn-sm btn-outline-danger py-0" onclick="this.closest('.video-pair').remove()">
                    <i class="fas fa-times"></i> Hapus
                </button>
            </div>
            <div class="form-group mb-1">
                <label class="small mb-0">File Video <span class="text-danger">*</span></label>
                <input type="file" name="videos[]" class="form-control-file" accept="video/*" required>
            </div>
            <div class="form-group mb-0">
                <label class="small mb-0">Foto Cover <span class="text-muted">(opsional)</span></label>
                <input type="file" name="video_covers[]" class="form-control-file" accept="image/*">
                <small class="text-muted">Gambar yang tampil di galeri sebelum video diputar.</small>
            </div>
        </div>`;
    container.insertAdjacentHTML('beforeend', html);
}
</script>
@stop
