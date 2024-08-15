<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Penumpang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Input Data Penumpang</h2>
        <!-- Menampilkan pesan sukses jika ada -->
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
        </div>
        @endif

        <!-- Menampilkan kesalahan jika ada -->
        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Perhatian!</strong> 
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form action="/simpan" method="POST">
            <!-- CSRF Token -->
            @csrf

            <!-- Input Penumpang -->
            <div class="mb-3">
                <label for="penumpang" class="form-label">Nama Penumpang</label>
                <input type="text" class="form-control" id="penumpang" name="penumpang" placeholder="Contoh: Arkatama 25 Malang" required>
            </div>

            <!-- Dropdown Travel -->
            <div class="mb-3">
                <label for="travel" class="form-label">Pilih Travel</label>
                <select class="form-select" id="travel" name="travel" required>
                    <option value="">Pilih Travel</option>
                    @foreach($travels as $travel)
                        <option value="{{ $travel->id }}">{{ $travel->tanggal_keberangkatan }} - Kuota Tersisa: {{ $travel->kuota }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
