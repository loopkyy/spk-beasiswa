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
        // Rule 1: Sangat Layak - IPK tinggi, ekonomi rendah
        if ($ipk >= 3.5 && $penghasilan <= 3) {
            return 'SANGAT LAYAK';
        }
        
        // Rule 2: Layak - IPK tinggi, ekonomi menengah
        if ($ipk >= 3.5 && $penghasilan > 3 && $penghasilan <= 5) {
            return 'LAYAK';
        }
        
        // Rule 3: Layak - IPK baik, ekonomi rendah, tanggungan banyak
        if ($ipk >= 3.0 && $ipk < 3.5 && $penghasilan <= 5) {
            if ($tanggungan >= 3) {
                return 'LAYAK';
            } else {
                return 'CUKUP LAYAK';
            }
        }
        
        // Rule 4: Kurang Layak - IPK baik tapi ekonomi tinggi
        if ($ipk >= 3.0 && $ipk < 3.5 && $penghasilan > 5) {
            return 'KURANG LAYAK';
        }
        
        // Rule 5: Cukup Layak - IPK rendah tapi punya prestasi
        if ($ipk < 3.0) {
            if ($prestasi == 'ya') {
                return 'CUKUP LAYAK';
            } else {
                return 'TIDAK LAYAK';
            }
        }
        
        // Rule 6: Special Case - IPK rendah, ekonomi sangat rendah
        if ($ipk >= 2.5 && $ipk < 3.0 && $penghasilan <= 2) {
            return 'CUKUP LAYAK';
        }
        
        return 'TIDAK TERDEFINISI';
    }

    private function calculateSimpleScore($ipk, $penghasilan, $tanggungan, $prestasi, $prestasiDetails = [])
    {
        $skor = 0;
        
        // 1. SKOR AKADEMIK (Max 40)
        if ($ipk >= 3.5) $skor += 40;
        elseif ($ipk >= 3.0) $skor += 30;
        elseif ($ipk >= 2.5) $skor += 20;
        else $skor += 10;
        
        // Bonus prestasi akademik (Max 10)
        if (isset($prestasiDetails['akademik'])) {
            $academicBonus = count($prestasiDetails['akademik']) * 5;
            $skor += min($academicBonus, 10);
        }
        
        // 2. SKOR EKONOMI (Max 35)
        if ($penghasilan <= 2) $skor += 35;
        elseif ($penghasilan <= 4) $skor += 25;
        elseif ($penghasilan <= 6) $skor += 15;
        else $skor += 5;
        
        // 3. SKOR TANGGUNGAN (Max 15)
        if ($tanggungan >= 4) $skor += 15;
        elseif ($tanggungan >= 3) $skor += 12;
        elseif ($tanggungan >= 2) $skor += 8;
        else $skor += 5;
        
        // 4. SKOR PRESTASI (Max 20)
        if ($prestasi == 'ya') {
            $skor += 10; // Base score
            
            // Bonus detail prestasi
            $prestasiBonus = 0;
            if (isset($prestasiDetails['non_akademik'])) {
                $prestasiBonus += count($prestasiDetails['non_akademik']) * 3;
            }
            if (isset($prestasiDetails['penelitian'])) {
                $prestasiBonus += count($prestasiDetails['penelitian']) * 4;
            }
            if (!empty($prestasiDetails['lainnya'])) {
                $prestasiBonus += 5;
            }
            
            $skor += min($prestasiBonus, 10);
        }
        
        return min($skor, 100);
    }

    private function getSimpleScoreBreakdown($ipk, $penghasilan, $tanggungan, $prestasi, $prestasiDetails)
    {
        $breakdown = [];
        
        // IPK Score
        if ($ipk >= 3.5) $breakdown['ipk'] = 40;
        elseif ($ipk >= 3.0) $breakdown['ipk'] = 30;
        elseif ($ipk >= 2.5) $breakdown['ipk'] = 20;
        else $breakdown['ipk'] = 10;
        
        // Academic Bonus
        $academicBonus = 0;
        if (isset($prestasiDetails['akademik'])) {
            $academicBonus = min(count($prestasiDetails['akademik']) * 5, 10);
        }
        $breakdown['akademik_bonus'] = $academicBonus;
        
        // Income Score
        if ($penghasilan <= 2) $breakdown['penghasilan'] = 35;
        elseif ($penghasilan <= 4) $breakdown['penghasilan'] = 25;
        elseif ($penghasilan <= 6) $breakdown['penghasilan'] = 15;
        else $breakdown['penghasilan'] = 5;
        
        // Dependents Score
        if ($tanggungan >= 4) $breakdown['tanggungan'] = 15;
        elseif ($tanggungan >= 3) $breakdown['tanggungan'] = 12;
        elseif ($tanggungan >= 2) $breakdown['tanggungan'] = 8;
        else $breakdown['tanggungan'] = 5;
        
        // Prestasi Score
        $prestasiScore = 0;
        if ($prestasi == 'ya') {
            $prestasiScore = 10;
            
            $prestasiBonus = 0;
            if (isset($prestasiDetails['non_akademik'])) {
                $prestasiBonus += count($prestasiDetails['non_akademik']) * 3;
            }
            if (isset($prestasiDetails['penelitian'])) {
                $prestasiBonus += count($prestasiDetails['penelitian']) * 4;
            }
            if (!empty($prestasiDetails['lainnya'])) {
                $prestasiBonus += 5;
            }
            
            $prestasiScore += min($prestasiBonus, 10);
        }
        $breakdown['prestasi'] = $prestasiScore;
        
        return $breakdown;
    }

    private function getAppliedRules($ipk, $penghasilan, $tanggungan, $prestasi)
    {
        $appliedRules = [];
        
        // Rule 1
        if ($ipk >= 3.5 && $penghasilan <= 3) {
            $appliedRules[] = [
                'rule' => 'Rule 1',
                'condition' => 'IPK ≥ 3.5 AND Penghasilan ≤ 3 juta',
                'result' => 'SANGAT LAYAK',
                'priority' => 1
            ];
        }
        
        // Rule 2
        if ($ipk >= 3.5 && $penghasilan > 3 && $penghasilan <= 5) {
            $appliedRules[] = [
                'rule' => 'Rule 2',
                'condition' => 'IPK ≥ 3.5 AND Penghasilan > 3 juta AND Penghasilan ≤ 5 juta',
                'result' => 'LAYAK',
                'priority' => 2
            ];
        }
        
        // Rule 3
        if ($ipk >= 3.0 && $ipk < 3.5 && $penghasilan <= 5 && $tanggungan >= 3) {
            $appliedRules[] = [
                'rule' => 'Rule 3',
                'condition' => 'IPK ≥ 3.0 AND IPK < 3.5 AND Penghasilan ≤ 5 juta AND Tanggungan ≥ 3',
                'result' => 'LAYAK',
                'priority' => 3
            ];
        }
        
        // Rule 4
        if ($ipk >= 3.0 && $ipk < 3.5 && $penghasilan <= 5 && $tanggungan < 3) {
            $appliedRules[] = [
                'rule' => 'Rule 4',
                'condition' => 'IPK ≥ 3.0 AND IPK < 3.5 AND Penghasilan ≤ 5 juta AND Tanggungan < 3',
                'result' => 'CUKUP LAYAK',
                'priority' => 4
            ];
        }
        
        // Rule 5
        if ($ipk >= 3.0 && $ipk < 3.5 && $penghasilan > 5) {
            $appliedRules[] = [
                'rule' => 'Rule 5',
                'condition' => 'IPK ≥ 3.0 AND IPK < 3.5 AND Penghasilan > 5 juta',
                'result' => 'KURANG LAYAK',
                'priority' => 5
            ];
        }
        
        // Rule 6
        if ($ipk < 3.0 && $prestasi == 'ya') {
            $appliedRules[] = [
                'rule' => 'Rule 6',
                'condition' => 'IPK < 3.0 AND Prestasi = ya',
                'result' => 'CUKUP LAYAK',
                'priority' => 6
            ];
        }
        
        // Rule 7
        if ($ipk < 3.0 && $prestasi == 'tidak') {
            $appliedRules[] = [
                'rule' => 'Rule 7',
                'condition' => 'IPK < 3.0 AND Prestasi = tidak',
                'result' => 'TIDAK LAYAK',
                'priority' => 7
            ];
        }
        
        // Rule 8 - Special Case
        if ($ipk >= 2.5 && $ipk < 3.0 && $penghasilan <= 2) {
            $appliedRules[] = [
                'rule' => 'Rule 8',
                'condition' => 'IPK ≥ 2.5 AND IPK < 3.0 AND Penghasilan ≤ 2 juta',
                'result' => 'CUKUP LAYAK',
                'priority' => 8
            ];
        }
        
        // Sort by priority
        usort($appliedRules, function($a, $b) {
            return $a['priority'] <=> $b['priority'];
        });
        
        return $appliedRules;
    }
}