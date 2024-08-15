<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penumpangs;
use App\Models\Travels;
use Carbon\Carbon;

class PenumpangController extends Controller
{
    public function index()
    {
        // Ambil data travel yang belum berangkat dan kuota masih tersisa
        $travels = Travels::where('tanggal_keberangkatan', '>', now())  // Cek keberangkatan di masa depan
            ->where('kuota', '>', 0)                      // Kuota lebih dari 0
            ->get();


        // Kirim data travel ke view
        return view('index', ['travels' => $travels]);
    }

    public function simpan(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'penumpang' => 'required|string',
            'travel' => 'required|exists:travels,id'
        ]);
        // dd($request);
        // Memecah data penumpang menjadi bagian-bagian
        $penumpang = $validated['penumpang'];
        // list($nama, $usia, $kota) = explode(' ', $penumpang, 3);
        // dd($penumpang);
        $input = strtolower($penumpang);

        // Memecah string berdasarkan spasi
        $parts = explode(' ', $input);


        foreach ($parts as &$element) {
            // Jika elemen mengandung angka
            if (preg_match('/\d/', $element)) {
                // Menghapus semua karakter non-numerik, menyisakan hanya angka
                $element = preg_replace('/\D/', '', $element);
            }
        }
        // Menghapus referensi
        unset($element);

        $usiaIndex = array_search(true, array_map('is_numeric', $parts));
        if ($parts[$usiaIndex + 1] == 'th' || $parts[$usiaIndex + 1] == 'tahun' || $parts[$usiaIndex + 1] == 'thn') {
            $hapus = $usiaIndex + 1;
            unset($parts[$hapus]);
            // dd($parts);
        }

        // Mengambil dan membersihkan usia
        $usiaText = $parts[$usiaIndex];

        $usiaText = preg_replace('/[^0-9]/', '', strtolower($usiaText)); // Hapus semua karakter non-numerik dan konversi ke lowercase
        $usia = (int) $usiaText;

        // Mengambil nama
        $nama = implode(' ', array_slice($parts, 0, $usiaIndex));

        // Mengambil kota
        $kota = implode(' ', array_slice($parts, $usiaIndex + 1));
        $nama = strtoupper(trim($nama)); // Ubah nama menjadi UPPERCASE
        $kota = strtoupper(trim($kota)); // Ubah kota menjadi UPPERCASE

        // dd('nama: ' . $nama . ' Usia: ' . $usia . ' Kota: ' . $kota);

        // Menghitung tahun lahir dan memproses usia
        $usia = preg_replace('/[^0-9]/', '', $usia); // Menghapus karakter non-numerik
        $tahun_lahir = Carbon::now()->year - $usia;

        // Handle cases for age input
        $usia = strtolower($usia);
        if (str_contains($usia, 'tahun') || str_contains($usia, 'thn') || str_contains($usia, 'th')) {
            $usia = preg_replace('/[^0-9]/', '', $usia); // Hanya ambil angka
            $tahun_lahir = Carbon::now()->year - $usia;
        }

        // Generate kode booking
        $travelId = $validated['travel'];
        $travel = Travels::findOrFail($travelId);
        $nomorUrut = Penumpangs::where('travel_id', $travelId)->count() + 1; // Menentukan nomor urut
        $kodeBooking = sprintf(
            '%02d%02d%04d%04d',
            Carbon::now()->year % 100,  // 2 digit tahun
            Carbon::now()->month,       // 2 digit bulan
            $travelId,                  // 4 digit ID travel
            $nomorUrut                  // 4 digit nomor urut
        );

        // Periksa apakah kode booking sudah ada
        if (Penumpangs::where('kode_booking', $kodeBooking)->exists()) {
            return redirect()->back()->withErrors(['Kode booking sudah ada.']);
        }

        // Periksa apakah penumpang sudah ada untuk travel yang sama
        if (Penumpangs::where('nama', $nama)
            ->where('travel_id', $travelId)
            ->exists()
        ) {
            return redirect()->back()->withErrors(['Penumpang sudah ada untuk travel yang sama.']);
        }

        // Simpan data penumpang ke database
        Penumpangs::create([
            'nama' => $nama,
            'usia' => $usia,
            'kota' => $kota,
            'tahun_lahir' => $tahun_lahir,
            'kode_booking' => $kodeBooking,
            'travel_id' => $travelId
        ]);

        // Redirect atau berikan respons
        return redirect()->back()->with('success', 'Data penumpang berhasil disimpan!');
    }
}
