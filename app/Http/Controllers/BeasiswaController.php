<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\BeasiswaHelper;

class BeasiswaController extends Controller
{
    public function index()
    {
        return view('beasiswa.form');
    }

    public function check(Request $request)
    {
        // Bersihkan nomor telepon sebelum validasi
        $telepon = preg_replace('/[^\d]/', '', $request->telepon);
        $request->merge(['telepon_clean' => $telepon]);
        
        $request->validate([
            // Data Pribadi
            'nama' => 'required|string|max:100|regex:/^[A-Za-z\s]+$/',
            
            // NIM
            'nim' => [
                'required',
                'string',
                'size:11',
                'regex:/^[0-9]{11}$/'
            ],
            
            // Fakultas & Prodi
            'fakultas' => 'required|string|max:100',
            'prodi' => 'required|string|max:100',
            'semester' => 'required|integer|min:1|max:8',
            'angkatan' => 'required|integer|min:' . (date('Y') - 5) . '|max:' . date('Y'),
            
            // Kontak
            'telepon' => [
                'required',
                'string',
                'min:10',
                'regex:/^[\d\s\-\.\+\(\)]+$/'
            ],
            'telepon_clean' => [
                'required',
                'string',
                'min:10',
                'max:13',
                'regex:/^[0-9]{10,13}$/'
            ],
            'email' => 'required|email|max:100',
            
            // Alamat
            'alamat' => [
                'required',
                'string',
                'max:500',
                'min:10',
                'regex:/^(?!\d+$).+$/'
            ],
            
            // Data Keluarga
            'ayah' => 'required|string|max:100|regex:/^[A-Za-z\s]+$/',
            'ibu' => 'required|string|max:100|regex:/^[A-Za-z\s]+$/',
            
            // Ekonomi
            'penghasilan' => 'required|numeric|min:0|max:1000',
            'tanggungan' => 'required|integer|min:1|max:20',
            
            // Akademik
            'ipk' => 'required|numeric|min:0|max:4',
            
            // Prestasi
            'prestasi_non_akademik' => 'required|in:ya,tidak',
            'prestasi_akademik' => 'nullable|array',
            'prestasi_non_akademik_detail' => 'nullable|array',
            'prestasi_penelitian' => 'nullable|array',
            'prestasi_lainnya' => 'nullable|string|max:1000'
        ], [
            // Custom Error Messages
            'nama.regex' => 'Nama hanya boleh berisi huruf dan spasi',
            'nim.size' => 'NIM harus 11 digit angka',
            'nim.regex' => 'NIM hanya boleh berisi 11 digit angka',
            'telepon.min' => 'Nomor telepon minimal 10 karakter',
            'telepon.regex' => 'Format nomor telepon tidak valid (hanya angka, spasi, -, +, ., (, ) yang diperbolehkan)',
            'telepon_clean.min' => 'Nomor telepon minimal 10 digit angka',
            'telepon_clean.max' => 'Nomor telepon maksimal 13 digit angka',
            'telepon_clean.regex' => 'Nomor telepon hanya boleh berisi angka 0-9',
            'alamat.min' => 'Alamat minimal 10 karakter',
            'alamat.regex' => 'Alamat tidak boleh hanya berisi angka',
            'ayah.regex' => 'Nama ayah hanya boleh berisi huruf dan spasi',
            'ibu.regex' => 'Nama ibu hanya boleh berisi huruf dan spasi',
            'ipk.max' => 'IPK maksimal 4.00',
            'penghasilan.max' => 'Penghasilan maksimal 1000 juta',
            'tanggungan.max' => 'Maksimal 20 orang tanggungan',
            'angkatan.min' => 'Tahun angkatan minimal ' . (date('Y') - 5),
            'angkatan.max' => 'Tahun angkatan maksimal ' . date('Y'),
        ]);

        // Ambil semua data
        $data = $request->all();
        $data['telepon'] = $telepon;
        
        $ipk = floatval($data['ipk']);
        $penghasilan = floatval($data['penghasilan']);
        $tanggungan = intval($data['tanggungan']);
        $prestasi = $data['prestasi_non_akademik'];

        // Proses Prestasi
        $prestasiDetails = [
            'akademik' => $request->input('prestasi_akademik', []),
            'non_akademik' => $request->input('prestasi_non_akademik_detail', []),
            'penelitian' => $request->input('prestasi_penelitian', []),
            'lainnya' => $request->input('prestasi_lainnya', '')
        ];

        // Rule-Based System
        $kelayakan = $this->evaluateRules($ipk, $penghasilan, $tanggungan, $prestasi);
        
        // Hitung skor SIMPLE
        $skor = $this->calculateSimpleScore($ipk, $penghasilan, $tanggungan, $prestasi, $prestasiDetails);
        
        // Get applied rules
        $rules = $this->getAppliedRules($ipk, $penghasilan, $tanggungan, $prestasi);
        
        // Hitung penghasilan per kapita menggunakan Helper
        $penghasilanPerKapita = BeasiswaHelper::hitungRataPenghasilan($penghasilan, $tanggungan);
        $levelEkonomi = BeasiswaHelper::getLevelEkonomi($penghasilanPerKapita);
        $levelIpk = BeasiswaHelper::getLevelIpk($ipk);
        
        // Generate nomor registrasi
        $noRegistrasi = BeasiswaHelper::generateNoRegistrasi($data['nim']);

        // Return view dengan semua data
        return view('beasiswa.result', [
            'data' => $data,
            'prestasiDetails' => $prestasiDetails,
            'kelayakan' => $kelayakan,
            'skor' => $skor,
            'rules' => $rules,
            'penghasilanPerKapita' => $penghasilanPerKapita,
            'levelEkonomi' => $levelEkonomi,
            'levelIpk' => $levelIpk,
            'noRegistrasi' => $noRegistrasi,
            'scoreBreakdown' => $this->getSimpleScoreBreakdown($ipk, $penghasilan, $tanggungan, $prestasi, $prestasiDetails)
        ]);
    }

private function evaluateRules($ipk, $penghasilan, $tanggungan, $prestasi)
{
    // PRIORITAS 1: SANGAT LAYAK
    if ($ipk >= 3.5 && $penghasilan <= 3 && 
        ($tanggungan >= 3 || $prestasi == 'ya')) {
        return 'SANGAT LAYAK';
    }

    // PRIORITAS 2: LAYAK
    elseif ($ipk >= 3.5 && $penghasilan <= 5) {
        return 'LAYAK';
    }

    // PRIORITAS 3: LAYAK
    elseif ($ipk >= 3.0 && $ipk < 3.5 && 
            $penghasilan <= 5 && $tanggungan >= 3) {
        return 'LAYAK';
    }

    // PRIORITAS 4: CUKUP LAYAK
    elseif ($ipk >= 3.0 && $ipk < 3.5 && $penghasilan <= 5) {
        return 'CUKUP LAYAK';
    }

    // PRIORITAS 5: CUKUP LAYAK (Kompensasi Prestasi)
    elseif ($ipk < 3.0 && $prestasi == 'ya' && $penghasilan <= 3) {
        return 'CUKUP LAYAK';
    }

    return 'TIDAK LAYAK';
}

private function calculateSimpleScore($ipk, $penghasilan, $tanggungan, $prestasi, $prestasiDetails = [])
{
    $skor = 0;
    // SKOR IPK (Max 35)
    if ($ipk >= 3.5) $skor += 35;
    elseif ($ipk >= 3.0) $skor += 28;
    elseif ($ipk >= 2.5) $skor += 20;
    else $skor += 10;

    // SKOR EKONOMI (Max 35)
    if ($penghasilan <= 2) $skor += 35;
    elseif ($penghasilan <= 4) $skor += 28;
    elseif ($penghasilan <= 6) $skor += 18;
    else $skor += 8;

    // SKOR TANGGUNGAN (Max 15)
    if ($tanggungan >= 4) $skor += 15;
    elseif ($tanggungan == 3) $skor += 12;
    elseif ($tanggungan == 2) $skor += 8;
    else $skor += 5;

    // SKOR PRESTASI (Max 15)
    if ($prestasi == 'ya') {

        $prestasiScore = 5;

        $bonus = 0;

        if (!empty($prestasiDetails['akademik'])) {
            $bonus += count($prestasiDetails['akademik']) * 2;
        }

        if (!empty($prestasiDetails['non_akademik'])) {
            $bonus += count($prestasiDetails['non_akademik']) * 2;
        }

        if (!empty($prestasiDetails['penelitian'])) {
            $bonus += count($prestasiDetails['penelitian']) * 3;
        }

        if (!empty($prestasiDetails['lainnya'])) {
            $bonus += 3;
        }

        $prestasiScore += min($bonus, 10);

        $skor += $prestasiScore;
    }

    return $skor;
}

   private function getSimpleScoreBreakdown($ipk, $penghasilan, $tanggungan, $prestasi, $prestasiDetails)
{
    $breakdown = [];
    // SKOR IPK (Max 35)
    if ($ipk >= 3.5) $breakdown['ipk'] = 35;
    elseif ($ipk >= 3.0) $breakdown['ipk'] = 28;
    elseif ($ipk >= 2.5) $breakdown['ipk'] = 20;
    else $breakdown['ipk'] = 10;

    // SKOR EKONOMI (Max 35)
    if ($penghasilan <= 2) $breakdown['penghasilan'] = 35;
    elseif ($penghasilan <= 4) $breakdown['penghasilan'] = 28;
    elseif ($penghasilan <= 6) $breakdown['penghasilan'] = 18;
    else $breakdown['penghasilan'] = 8;

    // SKOR TANGGUNGAN (Max 15)
    if ($tanggungan >= 4) $breakdown['tanggungan'] = 15;
    elseif ($tanggungan == 3) $breakdown['tanggungan'] = 12;
    elseif ($tanggungan == 2) $breakdown['tanggungan'] = 8;
    else $breakdown['tanggungan'] = 5;

    // SKOR PRESTASI (Max 15)
    $prestasiScore = 0;

    if ($prestasi == 'ya') {
        $prestasiScore += 5;

        $bonus = 0;

        if (!empty($prestasiDetails['akademik'])) {
            $bonus += count($prestasiDetails['akademik']) * 2;
        }

        if (!empty($prestasiDetails['non_akademik'])) {
            $bonus += count($prestasiDetails['non_akademik']) * 2;
        }

        if (!empty($prestasiDetails['penelitian'])) {
            $bonus += count($prestasiDetails['penelitian']) * 3;
        }

        if (!empty($prestasiDetails['lainnya'])) {
            $bonus += 3;
        }

        $prestasiScore += min($bonus, 10);
    }

    $breakdown['prestasi'] = $prestasiScore;

    return $breakdown;
}


private function getAppliedRules($ipk, $penghasilan, $tanggungan, $prestasi)
{
    $appliedRules = [];

    if ($ipk >= 3.5 && $penghasilan <= 3 && 
        ($tanggungan >= 3 || $prestasi == 'ya')) {
        $appliedRules[] = [
            'rule' => 'Rule 1',
            'condition' => 'IPK ≥ 3.5 AND Penghasilan ≤ 3 AND (Tanggungan ≥ 3 OR Prestasi = ya)',
            'result' => 'SANGAT LAYAK',
            'priority' => 1
        ];
    }

    elseif ($ipk >= 3.5 && $penghasilan <= 5) {
        $appliedRules[] = [
            'rule' => 'Rule 2',
            'condition' => 'IPK ≥ 3.5 AND Penghasilan ≤ 5',
            'result' => 'LAYAK',
            'priority' => 2
        ];
    }

    elseif ($ipk >= 3.0 && $ipk < 3.5 && 
            $penghasilan <= 5 && $tanggungan >= 3) {
        $appliedRules[] = [
            'rule' => 'Rule 3',
            'condition' => '3.0 ≤ IPK < 3.5 AND Penghasilan ≤ 5 AND Tanggungan ≥ 3',
            'result' => 'LAYAK',
            'priority' => 3
        ];
    }

    elseif ($ipk >= 3.0 && $ipk < 3.5 && $penghasilan <= 5) {
        $appliedRules[] = [
            'rule' => 'Rule 4',
            'condition' => '3.0 ≤ IPK < 3.5 AND Penghasilan ≤ 5',
            'result' => 'CUKUP LAYAK',
            'priority' => 4
        ];
    }

    elseif ($ipk < 3.0 && $prestasi == 'ya' && $penghasilan <= 3) {
        $appliedRules[] = [
            'rule' => 'Rule 5',
            'condition' => 'IPK < 3.0 AND Prestasi = ya AND Penghasilan ≤ 3',
            'result' => 'CUKUP LAYAK',
            'priority' => 5
        ];
    }

    else {
        $appliedRules[] = [
            'rule' => 'Rule 6',
            'condition' => 'Tidak memenuhi kriteria',
            'result' => 'TIDAK LAYAK',
            'priority' => 6
        ];
    }

    return $appliedRules;
}

    }
