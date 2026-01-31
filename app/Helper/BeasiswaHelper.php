<?php

namespace App\Helpers;

class BeasiswaHelper
{
    /**
     * Format angka ke Rupiah
     */
    public static function formatRupiah($angka, $inJuta = true)
    {
        if (!is_numeric($angka)) {
            return 'Rp 0';
        }
        if ($inJuta && $angka < 1000) {
            $angka = $angka * 1000000;
        }
        
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
    
public static function formatTelepon($telepon)
{
    // Hapus semua karakter non-digit
    $clean = preg_replace('/[^0-9]/', '', $telepon);
    
    $length = strlen($clean);
    
    // Jika terlalu pendek, kembalikan asli
    if ($length < 10 || $length > 13) {
        return $telepon;
    }
    
    // Tentukan format berdasarkan panjang
    if ($length == 10) {
        return substr($clean, 0, 4) . '-' . 
               substr($clean, 4, 3) . '-' . 
               substr($clean, 7, 3);
    }
    elseif ($length == 11) {
        // Format 11 digit
        return substr($clean, 0, 4) . '-' . 
               substr($clean, 4, 4) . '-' . 
               substr($clean, 8, 3);
    }
    elseif ($length == 12) {
        // Format 12 digit
        return substr($clean, 0, 4) . '-' . 
               substr($clean, 4, 4) . '-' . 
               substr($clean, 8, 4);
    }
    else { // 13 digit
        return substr($clean, 0, 4) . '-' . 
               substr($clean, 4, 4) . '-' . 
               substr($clean, 8, 5);
    }
}

/**
 * Validasi nomor telepon Indonesia
 */
public static function validateTeleponIndonesia($telepon)
{
    $clean = preg_replace('/[^0-9]/', '', $telepon);
    
    // Panjang harus 10-13 digit
    if (strlen($clean) < 10 || strlen($clean) > 13) {
        return false;
    }
    
    // Harus diawali dengan 0 atau +62
    if (!preg_match('/^(0|62)/', $clean)) {
        return false;
    }
    
    // Digit kedua biasanya 8
    if (strlen($clean) >= 2 && $clean[0] == '0' && $clean[1] != '8') {
    }
    
    return true;
}

/**
 * Normalisasi nomor telepon ke format standar
 */
public static function normalizeTelepon($telepon)
{
    $clean = preg_replace('/[^0-9]/', '', $telepon);
    
    // Jika diawali dengan 62, ubah jadi 0
    if (substr($clean, 0, 2) == '62') {
        $clean = '0' . substr($clean, 2);
    }
    
    // Jika diawali dengan +62, ubah jadi 0
    if (substr($clean, 0, 3) == '062') {
        $clean = '0' . substr($clean, 3);
    }
    
    return $clean;
}

/**
 * Cek apakah nomor telepon valid
 */
public static function validateTeleponWithMessage($telepon)
{
    $clean = preg_replace('/[^0-9]/', '', $telepon);
    
    if (empty($clean)) {
        return ['valid' => false, 'message' => 'Nomor telepon tidak boleh kosong'];
    }
    
    if (strlen($clean) < 10) {
        return ['valid' => false, 'message' => 'Nomor telepon minimal 10 digit'];
    }
    
    if (strlen($clean) > 13) {
        return ['valid' => false, 'message' => 'Nomor telepon maksimal 13 digit'];
    }
    
    // Validasi prefix Indonesia
    if (!preg_match('/^(0|62|8)/', $clean)) {
        return ['valid' => false, 'message' => 'Format nomor telepon tidak valid'];
    }
    
    return ['valid' => true, 'message' => 'Nomor telepon valid'];
}
    
    /**
     * Get color untuk status kelayakan
     */
    public static function getStatusColor($status)
    {
        $colors = [
            'SANGAT LAYAK' => 'success',
            'LAYAK' => 'info',
            'CUKUP LAYAK' => 'warning',
            'KURANG LAYAK' => 'secondary',
            'TIDAK LAYAK' => 'danger',
            'TIDAK TERDEFINISI' => 'dark'
        ];
        
        return $colors[$status] ?? 'dark';
    }
    
    /**
     * Get icon untuk status kelayakan
     */
    public static function getStatusIcon($status)
    {
        $icons = [
            'SANGAT LAYAK' => 'fas fa-star',
            'LAYAK' => 'fas fa-check-circle',
            'CUKUP LAYAK' => 'fas fa-exclamation-circle',
            'KURANG LAYAK' => 'fas fa-times-circle',
            'TIDAK LAYAK' => 'fas fa-ban',
            'TIDAK TERDEFINISI' => 'fas fa-question-circle'
        ];
        
        return $icons[$status] ?? 'fas fa-question-circle';
    }
    
    /**
     * Interpretasi skor numerik
     */
    public static function interpretScore($skor)
    {
        if ($skor >= 80) return 'Sangat Baik';
        if ($skor >= 70) return 'Baik';
        if ($skor >= 60) return 'Cukup';
        if ($skor >= 50) return 'Kurang';
        return 'Sangat Kurang';
    }
    
    /**
     * Get warna untuk skor
     */
    public static function getScoreColor($skor)
    {
        if ($skor >= 80) return 'success';
        if ($skor >= 70) return 'info';
        if ($skor >= 60) return 'warning';
        if ($skor >= 50) return 'secondary';
        return 'danger';
    }
    
    /**
     * Format IPK dengan 2 desimal
     */
    public static function formatIpk($ipk)
    {
        return number_format(floatval($ipk), 2);
    }
    
    /**
     * Format fakultas singkat
     */
    public static function formatFakultas($fakultas)
    {
        $singkatan = [
            'Ekonomi & Bisnis' => 'FEB',
            'Teknik' => 'FT',
            'Ilmu Komputer' => 'FIK',
            'Kedokteran' => 'FK',
            'Hukum' => 'FH',
            'Pertanian' => 'FP',
            'Keguruan' => 'FKIP'
        ];
        
        return $singkatan[$fakultas] ?? $fakultas;
    }
    
    /**
     * Validasi NIM (digunakan di frontend juga)
     */
    public static function validateNim($nim)
    {
        return preg_match('/^[0-9]{11}$/', $nim);
    }
    
    /**
     * Validasi Telepon (digunakan di frontend juga)
     */
    public static function validateTelepon($telepon)
    {
        $clean = preg_replace('/[^0-9]/', '', $telepon);
        return strlen($clean) >= 10 && strlen($clean) <= 12;
    }
    
    /**
     * Hitung rata-rata penghasilan per anggota
     */
    public static function hitungRataPenghasilan($penghasilan, $tanggungan)
    {
        if ($tanggungan <= 0) return 0;
        
        // Penghasilan dalam juta, konversi ke rupiah
        $penghasilanRupiah = $penghasilan * 1000000;
        $rata = $penghasilanRupiah / $tanggungan;
        
        return $rata / 1000000; // Kembali ke juta
    }
    
    /**
     * Get level ekonomi berdasarkan penghasilan per kapita
     */
    public static function getLevelEkonomi($penghasilanPerKapita)
    {
        if ($penghasilanPerKapita <= 1) return 'Sangat Rendah';
        if ($penghasilanPerKapita <= 2) return 'Rendah';
        if ($penghasilanPerKapita <= 4) return 'Menengah Bawah';
        if ($penghasilanPerKapita <= 6) return 'Menengah';
        if ($penghasilanPerKapita <= 10) return 'Menengah Atas';
        return 'Tinggi';
    }
    
    /**
     * Get level IPK
     */
    public static function getLevelIpk($ipk)
    {
        if ($ipk >= 3.5) return 'Sangat Baik';
        if ($ipk >= 3.0) return 'Baik';
        if ($ipk >= 2.5) return 'Cukup';
        return 'Kurang';
    }
    
    /**
     * Generate nomor registrasi beasiswa
     */
    public static function generateNoRegistrasi($nim)
    {
        $date = date('Ymd');
        $random = strtoupper(substr(md5($nim . time()), 0, 6));
        return 'REG-' . $date . '-' . $random;
    }
}