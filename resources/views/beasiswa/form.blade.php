<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ELIGORA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen fade-in">
    <!-- Background Pattern -->
    <div class="fixed inset-0 z-0 opacity-5">
        <div class="absolute top-0 left-0 w-64 h-64 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-gradient-to-br from-blue-400 to-cyan-400 rounded-full translate-x-1/3 translate-y-1/3"></div>
    </div>
    
    <div class="relative z-10">
        <!-- Header -->
        <div class="container mx-auto px-4 pt-8 pb-6">
            <div class="flex flex-col md:flex-row justify-between items-center mb-8">
                <div class="text-center md:text-left mb-6 md:mb-0">
                    <div class="flex items-center justify-center md:justify-start space-x-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-lg">
                            <i class="fas fa-graduation-cap text-white text-lg"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">
                                ELIGORA
                            </h1>
                        </div>
                    </div>
                </div>
                </div>
            
            <div class="max-w-3xl mx-auto text-center mb-10">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 leading-tight">
                    Decision Support System
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Sistem Pendukung Keputusan Kelayakan Beasiswa Berbasis Rule-Based
                </p>
            </div>
        </div>
        
        <!-- Main Form Card -->
        <div class="container mx-auto px-4 pb-12">
            <div class="max-w-4xl mx-auto">
                <div class="glass-card rounded-2xl shadow-2xl overflow-hidden border border-white/30">
                    <!-- Form Header -->
                    <div class="gradient-bg px-8 py-6 text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="floating-icon">
                                    <i class="fas fa-robot text-3xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold">Analisis Beasiswa</h3>
                                    <p class="opacity-90">Isi formulir di bawah dengan data yang valid</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Content -->
                    <div class="p-8">
                        @if ($errors->any())
                            <div class="mb-8 p-4 rounded-xl bg-red-50 border border-red-200">
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-exclamation-circle text-red-500 text-xl mt-0.5"></i>
                                    <div>
                                        <h4 class="font-bold text-red-700 mb-2">Perbaiki data berikut:</h4>
                                        <ul class="space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li class="text-red-600 text-sm flex items-center">
                                                    <i class="fas fa-circle text-[6px] mr-2"></i>
                                                    {{ $error }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('beasiswa.check') }}" class="space-y-8" id="beasiswaForm">
                            @csrf
                            
                            <!-- Personal Info -->
                            <div class="space-y-6">
                                <div class="section-divider">
                                    <span><i class="fas fa-user mr-2"></i> Data Pribadi</span>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Nama -->
                                    <div class="relative">
                                        <div class="floating-label">Nama Lengkap</div>
                                        <div class="flex items-center border border-gray-300 rounded-xl px-4 py-3.5 input-focus group hover:border-purple-400 transition">
                                            <i class="fas fa-user text-gray-400 mr-3 group-hover:text-purple-500 transition"></i>
                                            <input type="text" name="nama" 
                                                   class="w-full bg-transparent outline-none text-gray-800 placeholder-gray-500"
                                                   placeholder="Nama sesuai KTP"
                                                   value="{{ old('nama') }}"
                                                   pattern="[A-Za-z\s]{3,100}"
                                                   title="Nama minimal 3 huruf, maksimal 100 karakter"
                                                   required>
                                        </div>
                                    </div>
                                    
                                    <!-- NIM -->
                                    <div class="relative">
                                        <div class="floating-label">NIM</div>
                                        <div class="flex items-center border border-gray-300 rounded-xl px-4 py-3.5 input-focus group hover:border-purple-400 transition">
                                            <i class="fas fa-id-card text-gray-400 mr-3 group-hover:text-purple-500 transition"></i>
                                            <input type="text" name="nim" 
                                                   class="w-full bg-transparent outline-none text-gray-800 placeholder-gray-500"
                                                   placeholder="11 digit angka"
                                                   value="{{ old('nim') }}"
                                                   pattern="[0-9]{11}"
                                                   title="NIM harus 11 digit angka"
                                                   maxlength="11"
                                                   minlength="11"
                                                   required>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Contoh: 12345678901
                                        </p>
                                    </div>
                                    
                                    <!-- Fakultas -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Fakultas
                                        </label>
                                        <select name="fakultas" id="fakultas" 
                                                class="w-full px-4 py-3.5 bg-white border-2 border-gray-300 rounded-xl 
                                                       focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none
                                                       transition-all duration-300 hover:border-blue-400 appearance-none"
                                                required>
                                            <option value="">-- Pilih Fakultas --</option>
                                            <option value="Ekonomi & Bisnis" {{ old('fakultas') == 'Ekonomi & Bisnis' ? 'selected' : '' }}>
                                                Fakultas Ekonomi & Bisnis
                                            </option>
                                            <option value="Ilmu Komputer" {{ old('fakultas') == 'Ilmu Komputer' ? 'selected' : '' }}>
                                                Fakultas Ilmu Komputer
                                            </option>
                                            <option value="Kehutanan & Lingkungan" {{ old('fakultas') == 'Kehutanan & Lingkungan' ? 'selected' : '' }}>
                                                Fakultas Kehutanan
                                            </option>
                                            <option value="Hukum" {{ old('fakultas') == 'Hukum' ? 'selected' : '' }}>
                                                Fakultas Hukum
                                            </option>
                                            <option value="Keguruan & Ilmu Pendidikan" {{ old('fakultas') == 'Keguruan & Ilmu Pendidikan' ? 'selected' : '' }}>
                                                Fakultas Keguruan & Ilmu Pendidikan
                                            </option>
                                        </select>
                                    </div>
                                    
                                    <!-- Program Studi -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Program Studi
                                        </label>
                                        <select name="prodi" id="prodi" 
                                                class="w-full px-4 py-3.5 bg-white border-2 border-gray-300 rounded-xl 
                                                       focus:border-green-500 focus:ring-2 focus:ring-green-200 focus:outline-none
                                                       transition-all duration-300 hover:border-green-400 appearance-none"
                                                required disabled>
                                            <option value="">-- Pilih fakultas terlebih dahulu --</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Semester -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Semester
                                        </label>
                                        <select name="semester" 
                                                class="w-full px-4 py-3.5 bg-white border-2 border-gray-300 rounded-xl 
                                                       focus:border-purple-500 focus:ring-2 focus:ring-purple-200 focus:outline-none
                                                       transition-all duration-300 hover:border-purple-400 appearance-none"
                                                required>
                                            <option value="">-- Pilih Semester --</option>
                                            @for($i = 1; $i <= 8; $i++)
                                                <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>
                                                    Semester {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    
                                    <!-- Tahun Angkatan -->
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Tahun Angkatan
                                        </label>
                                        <select name="angkatan" 
                                                class="w-full px-4 py-3.5 bg-white border-2 border-gray-300 rounded-xl 
                                                       focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:outline-none
                                                       transition-all duration-300 hover:border-orange-400 appearance-none"
                                                required>
                                            <option value="">-- Pilih Tahun --</option>
                                            @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                                                <option value="{{ $year }}" {{ old('angkatan') == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    
                                    <!-- Email -->
                                    <div class="relative">
                                        <div class="floating-label">Email</div>
                                        <div class="flex items-center border border-gray-300 rounded-xl px-4 py-3.5 input-focus group hover:border-purple-400 transition">
                                            <i class="fas fa-envelope text-gray-400 mr-3 group-hover:text-purple-500 transition"></i>
                                            <input type="email" name="email" 
                                                   class="w-full bg-transparent outline-none text-gray-800 placeholder-gray-500"
                                                   placeholder="email@example.com"
                                                   value="{{ old('email') }}"
                                                   required>
                                        </div>
                                    </div>
                                    
                                    <!-- Telepon -->
                                   
                                      <div class="relative">
                                          <div class="floating-label">Telepon/WhatsApp</div>
                                         <div class="flex items-center border border-gray-300 rounded-xl px-4 py-3.5 input-focus group hover:border-purple-400 transition">
                                              <i class="fas fa-phone text-gray-400 mr-3 group-hover:text-purple-500 transition"></i>
                                             <input type="tel" name="telepon" 
                                                 class="w-full bg-transparent outline-none text-gray-800 placeholder-gray-500"
                                                 placeholder="0812-3456-7890"
                                                 value="{{ old('telepon') }}"
                                                pattern="[0-9\s\-\.\+\(\)]{10,15}"
                                                title="Nomor telepon 10-13 digit angka (contoh: 0812-3456-7890)"
                                                 maxlength="15"
                                                 required>
                                            </div>
                                              <p class="text-xs text-gray-500 mt-1">
                                             <i class="fas fa-info-circle mr-1"></i>
                                            Contoh: 0812-3456-7890 (10-13 digit)
                                                </p>
                                            </div>
                                </div>
                                
                                <!-- Alamat -->
                                <div class="relative">
                                    <div class="floating-label">Alamat Lengkap</div>
                                    <div class="border border-gray-300 rounded-xl px-4 py-3.5 input-focus group hover:border-purple-400 transition">
                                        <div class="flex items-start">
                                            <i class="fas fa-map-marker-alt text-gray-400 mr-3 mt-1 group-hover:text-purple-500 transition"></i>
                                            <textarea name="alamat" rows="2"
                                                      class="w-full bg-transparent outline-none text-gray-800 placeholder-gray-500 resize-none"
                                                      placeholder="Tulis alamat lengkap (minimal 10 karakter)"
                                                      minlength="10"
                                                      required>{{ old('alamat') }}</textarea>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Contoh: Jl. Nusaherang No. 123, Kecamatan Nusaherang, Kabupaten Kuningan, Jawa Barat
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Family Info -->
                            <div class="space-y-6">
                                <div class="section-divider">
                                    <span><i class="fas fa-users mr-2"></i> Data Keluarga</span>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Ayah -->
                                    <div class="relative">
                                        <div class="floating-label">Nama Ayah</div>
                                        <div class="flex items-center border border-gray-300 rounded-xl px-4 py-3.5 input-focus group hover:border-purple-400 transition">
                                            <i class="fas fa-male text-gray-400 mr-3 group-hover:text-purple-500 transition"></i>
                                            <input type="text" name="ayah" 
                                                   class="w-full bg-transparent outline-none text-gray-800 placeholder-gray-500"
                                                   placeholder="Nama ayah kandung"
                                                   value="{{ old('ayah') }}"
                                                   pattern="[A-Za-z\s]{3,100}"
                                                   title="Nama minimal 3 huruf"
                                                   required>
                                        </div>
                                    </div>
                                    
                                    <!-- Ibu -->
                                    <div class="relative">
                                        <div class="floating-label">Nama Ibu</div>
                                        <div class="flex items-center border border-gray-300 rounded-xl px-4 py-3.5 input-focus group hover:border-purple-400 transition">
                                            <i class="fas fa-female text-gray-400 mr-3 group-hover:text-purple-500 transition"></i>
                                            <input type="text" name="ibu" 
                                                   class="w-full bg-transparent outline-none text-gray-800 placeholder-gray-500"
                                                   placeholder="Nama ibu kandung"
                                                   value="{{ old('ibu') }}"
                                                   pattern="[A-Za-z\s]{3,100}"
                                                   title="Nama minimal 3 huruf"
                                                   required>
                                        </div>
                                    </div>
                                    
                                    <!-- Penghasilan -->
                                    <div class="relative">
                                        <div class="floating-label">Penghasilan Keluarga</div>
                                        <div class="tooltip">
                                            <div class="flex items-center border border-gray-300 rounded-xl px-4 py-3.5 input-focus group hover:border-purple-400 transition">
                                                <i class="fas fa-money-bill-wave text-gray-400 mr-3 group-hover:text-purple-500 transition"></i>
                                                <input type="number" name="penghasilan" step="0.01" min="0" max="1000"
                                                       class="w-full bg-transparent outline-none text-gray-800 placeholder-gray-500"
                                                       placeholder="Dalam juta per bulan"
                                                       value="{{ old('penghasilan') }}"
                                                       required>
                                                <span class="text-gray-500">juta/bln</span>
                                            </div>
                                            <span class="tooltip-text">Total penghasilan orang tua per bulan</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Tanggungan -->
                                    <div class="relative">
                                        <div class="floating-label">Jumlah Tanggungan</div>
                                        <div class="tooltip">
                                            <div class="flex items-center border border-gray-300 rounded-xl px-4 py-3.5 input-focus group hover:border-purple-400 transition">
                                                <i class="fas fa-user-friends text-gray-400 mr-3 group-hover:text-purple-500 transition"></i>
                                                <input type="number" name="tanggungan" min="1" max="20"
                                                       class="w-full bg-transparent outline-none text-gray-800 placeholder-gray-500"
                                                       placeholder="Jumlah anggota keluarga"
                                                       value="{{ old('tanggungan') }}"
                                                       required>
                                                <span class="text-gray-500">orang</span>
                                            </div>
                                            <span class="tooltip-text">Total anggota keluarga yang masih menjadi tanggungan (termasuk diri sendiri)</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Termasuk orang tua, adik, kakak, dan diri sendiri
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Academic Info -->
                            <div class="space-y-6">
                                <div class="section-divider">
                                    <span><i class="fas fa-graduation-cap mr-2"></i> Data Akademik & Prestasi</span>
                                </div>
                                
                                <!-- IPK -->
                                <div class="relative">
                                    <div class="floating-label">IPK (Indeks Prestasi Kumulatif)</div>
                                    <div class="border border-gray-300 rounded-xl px-4 py-3.5 input-focus group hover:border-purple-400 transition">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fas fa-chart-line text-gray-400 mr-3 group-hover:text-purple-500 transition"></i>
                                                <input type="number" name="ipk" step="0.01" min="0" max="4"
                                                       id="ipk-input"
                                                       class="w-32 bg-transparent outline-none text-gray-800 placeholder-gray-500"
                                                       placeholder="0.00 - 4.00"
                                                       value="{{ old('ipk') }}"
                                                       required>
                                                <span class="text-gray-400 mx-3">/</span>
                                                <span class="text-gray-800 font-medium">4.00</span>
                                            </div>
                                            
                                            <!-- IPK -->
                                            <div id="ipk-indicator" class="flex items-center space-x-3">
                                                <div id="ipk-status" class="text-sm font-medium"></div>
                                                <div id="ipk-bar" class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                    <div id="ipk-progress" class="h-full rounded-full transition-all duration-500"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Masukkan IPK terakhir dengan format 2 desimal (contoh: 3.75)
                                    </p>
                                </div>
                                
                                <!-- Prestasi  -->
                                <div class="space-y-6">
                                    <!-- Prestasi Non-Akademik -->
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-4">
                                            <i class="fas fa-trophy mr-2"></i>
                                            Prestasi Non-Akademik
                                        </label>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <label class="radio-custom flex items-center space-x-3 p-4 border border-gray-300 rounded-xl hover:border-purple-400 hover:bg-purple-50 transition-all cursor-pointer group">
                                                <input type="radio" name="prestasi_non_akademik" value="ya" 
                                                       {{ old('prestasi_non_akademik') == 'ya' ? 'checked' : '' }}
                                                       required>
                                                <span class="radiomark"></span>
                                                <div class="flex-1">
                                                    <span class="font-medium text-gray-800 group-hover:text-purple-700">Ya, Saya Punya</span>
                                                    <p class="text-sm text-gray-600 mt-1">Memiliki prestasi non-akademik</p>
                                                </div>
                                                <i class="fas fa-award text-purple-400 text-xl"></i>
                                            </label>
                                            
                                            <label class="radio-custom flex items-center space-x-3 p-4 border border-gray-300 rounded-xl hover:border-purple-400 hover:bg-purple-50 transition-all cursor-pointer group">
                                                <input type="radio" name="prestasi_non_akademik" value="tidak"
                                                       {{ old('prestasi_non_akademik') == 'tidak' ? 'checked' : '' }}>
                                                <span class="radiomark"></span>
                                                <div class="flex-1">
                                                    <span class="font-medium text-gray-800 group-hover:text-purple-700">Tidak Punya</span>
                                                    <p class="text-sm text-gray-600 mt-1">Tidak ada prestasi non-akademik</p>
                                                </div>
                                                <i class="fas fa-times text-gray-400 text-xl"></i>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <!-- Prestasi Akademik  -->
                                    <div id="prestasi-akademik-container" class="{{ old('prestasi_non_akademik') == 'ya' ? '' : 'hidden' }}">
                                        <!-- Jenis Prestasi -->
                                        <div class="mb-6">
                                            <label class="block text-gray-700 font-medium mb-4">
                                                <i class="fas fa-medal mr-2"></i>
                                                Jenis Prestasi yang Dimiliki
                                            </label>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <!-- Academic Achievements -->
                                                <div class="space-y-3">
                                                    <h5 class="font-semibold text-gray-700 text-sm flex items-center">
                                                        <i class="fas fa-graduation-cap mr-2 text-blue-500"></i>
                                                        Akademik
                                                    </h5>
                                                    @php
                                                        $academicAchievements = [
                                                            'sertifikar' => 'Sertifikasi Kompetensi',
                                                            'lomba_akademik' => 'Lomba/Olimpiade Akademik',
                                                            'ipk_tinggi' => 'IPK ≥ 3.5',
                                                            'beasiswa_sebelumnya' => 'Pernah Dapat Beasiswa',
                                                        ];
                                                    @endphp
                                                    
                                                    @foreach($academicAchievements as $key => $title)
                                                        <label class="checkbox-custom flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition cursor-pointer group">
                                                            <input type="checkbox" name="prestasi_akademik[]" value="{{ $key }}"
                                                                   {{ in_array($key, old('prestasi_akademik', [])) ? 'checked' : '' }}>
                                                            <span class="checkmark"></span>
                                                            <span class="text-sm text-gray-700 group-hover:text-blue-700">{{ $title }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                                
                                                <!-- Non-Academic Achievements -->
                                                <div class="space-y-3">
                                                    <h5 class="font-semibold text-gray-700 text-sm flex items-center">
                                                        <i class="fas fa-trophy mr-2 text-green-500"></i>
                                                        Non-Akademik
                                                    </h5>
                                                    @php
                                                        $nonAcademicAchievements = [
                                                            'olahraga' => 'Prestasi Olahraga',
                                                            'seni_budaya' => 'Seni & Budaya',
                                                            'kepemimpinan' => 'Organisasi & Kepemimpinan',
                                                            'sosial' => 'Kegiatan Sosial',
                                                        ];
                                                    @endphp
                                                    
                                                    @foreach($nonAcademicAchievements as $key => $title)
                                                        <label class="checkbox-custom flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition cursor-pointer group">
                                                            <input type="checkbox" name="prestasi_non_akademik_detail[]" value="{{ $key }}"
                                                                   {{ in_array($key, old('prestasi_non_akademik_detail', [])) ? 'checked' : '' }}>
                                                            <span class="checkmark"></span>
                                                            <span class="text-sm text-gray-700 group-hover:text-green-700">{{ $title }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                                
                                                <!-- Research & Publications -->
                                                <div class="space-y-3">
                                                    <h5 class="font-semibold text-gray-700 text-sm flex items-center">
                                                        <i class="fas fa-flask mr-2 text-purple-500"></i>
                                                        Penelitian & Publikasi
                                                    </h5>
                                                    @php
                                                        $researchAchievements = [
                                                            'penelitian' => 'Penelitian/Karya Ilmiah',
                                                            'publikasi_jurnal' => 'Publikasi Jurnal',
                                                            'hak_cipta' => 'Hak Cipta/Paten',
                                                            'seminar' => 'Pemakalah Seminar',
                                                        ];
                                                    @endphp
                                                    
                                                    @foreach($researchAchievements as $key => $title)
                                                        <label class="checkbox-custom flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition cursor-pointer group">
                                                            <input type="checkbox" name="prestasi_penelitian[]" value="{{ $key }}"
                                                                   {{ in_array($key, old('prestasi_penelitian', [])) ? 'checked' : '' }}>
                                                            <span class="checkmark"></span>
                                                            <span class="text-sm text-gray-700 group-hover:text-purple-700">{{ $title }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Prestasi Lainnya -->
                                        <div class="relative">
                                            <div class="floating-label">Detail Prestasi Lainnya</div>
                                            <div class="border border-gray-300 rounded-xl px-4 py-3.5 input-focus group hover:border-purple-400 transition">
                                                <textarea name="prestasi_lainnya" rows="3"
                                                          class="w-full bg-transparent outline-none text-gray-800 placeholder-gray-500 resize-none"
                                                          placeholder="Jelaskan prestasi lainnya yang Anda miliki beserta tingkatnya (Lokal, Nasional, Internasional)...">{{ old('prestasi_lainnya') }}</textarea>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-2">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Contoh: Juara 1 Lomba Debat Nasional 2023, Finalis Olimpiade Matematika Internasional, dll.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Submit Section -->
                            <div class="pt-6 border-t border-gray-200 text-center">
                                    <button type="submit" class="btn-submit text-white font-bold px-10 py-4 rounded-xl text-lg flex items-center justify-center space-x-3 shadow-lg inline-flex">
                                        <i class="fas fa-bolt"></i>
                                        <span>Analisis Kelayakan</span>
                                        <i class="fas fa-arrow-right"></i>
                                     </button>  
                            </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="mt-12 text-center">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="text-gray-600 text-sm">
                            <p>© 2026 ELIGORA</p>
                        </div>
                        
                        <div class="flex items-center space-x-6">
                            <a href="https://github.com/loopkyy" target="_blank" class="text-gray-600 hover:text-purple-600 transition">
                                <i class="fab fa-github text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-600 hover:text-red-500 transition">
                                <i class="fab fa-laravel text-xl"></i>
                            </a>
                            <a href="#" class="text-gray-600 hover:text-blue-500 transition">
                                <i class="fab fa-php text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!--  JS -->
     <script src="{{ asset('js/form-script.js') }}"></script>
</body>
</html>