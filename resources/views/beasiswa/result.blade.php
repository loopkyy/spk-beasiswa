<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Evaluasi Beasiswa</title>
    
    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/beasiswa-result.css') }}">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Pass data to JS -->
    @if(isset($scoreBreakdown))
    <script>
        window.scoreBreakdown = @json($scoreBreakdown);
        window.skor = {{ $skor }};
        window.kelayakan = "{{ $kelayakan }}";
        window.noRegistrasi = "{{ $noRegistrasi ?? '' }}";
    </script>
    @endif
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <!-- Animated Background -->
    <div class="fixed inset-0 z-0 overflow-hidden">
        <div class="absolute top-0 left-0 w-72 h-72 bg-gradient-to-br from-purple-200 to-pink-200 rounded-full -translate-x-1/2 -translate-y-1/2 opacity-20 floating" style="animation-delay: 0s;"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-gradient-to-br from-blue-200 to-cyan-200 rounded-full translate-x-1/3 translate-y-1/3 opacity-20 floating" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-gradient-to-br from-green-200 to-emerald-200 rounded-full -translate-x-1/2 -translate-y-1/2 opacity-20 floating" style="animation-delay: 4s;"></div>
    </div>
    
    <div class="relative z-10">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-6xl mx-auto">
                <!-- Header -->
                <div class="text-center mb-10 fade-in-up">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 mb-6 shadow-lg">
                        <i class="fas fa-file-alt text-white text-3xl"></i>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-3">
                        Hasil Analisis
                        <span class="gradient-text">Beasiswa</span>
                    </h1>
                    <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                        Sistem telah menganalisis data Anda menggunakan <span class="font-semibold text-blue-600">Rule-Based Expert System</span>
                    </p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-8 fade-in-up delay-1">
                    <a href="{{ route('beasiswa.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Form
                    </a>
                </div>
                
                <!-- Main Container -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Student Info Card -->
                        <div class="glass-card rounded-2xl shadow-xl overflow-hidden fade-in-up delay-2">
                            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h2 class="text-2xl font-bold flex items-center">
                                            <i class="fas fa-user-graduate mr-3"></i>
                                            Data Mahasiswa
                                        </h2>
                                        <p class="opacity-90">Evaluasi untuk {{ $data['nama'] }}</p>
                                    </div>
                                    <div class="bg-white/20 p-3 rounded-xl">
                                        <i class="fas fa-id-card text-2xl"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <!-- Stats Grid -->
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                    <div class="stat-card bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="bg-blue-100 p-2 rounded-lg">
                                                <i class="fas fa-brain text-blue-600"></i>
                                            </div>
                                            <span class="text-2xl font-bold {{ $data['ipk'] >= 3.5 ? 'text-green-600' : ($data['ipk'] >= 3.0 ? 'text-blue-600' : 'text-yellow-600') }}">
                                                {{ number_format($data['ipk'], 2) }}
                                            </span>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">IPK</p>
                                        <p class="text-xs text-gray-500">Indeks Prestasi</p>
                                    </div>
                                    
                                    <div class="stat-card bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl border border-green-200">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="bg-green-100 p-2 rounded-lg">
                                                <i class="fas fa-money-bill-wave text-green-600"></i>
                                            </div>
                                            <span class="text-xl font-bold text-gray-800">
                                                {{ \App\Helpers\BeasiswaHelper::formatRupiah($data['penghasilan']) }}
                                            </span>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">Penghasilan</p>
                                        <p class="text-xs text-gray-500">Per bulan</p>
                                    </div>
                                    
                                    <div class="stat-card bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-xl border border-purple-200">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="bg-purple-100 p-2 rounded-lg">
                                                <i class="fas fa-users text-purple-600"></i>
                                            </div>
                                            <span class="text-2xl font-bold text-gray-800">{{ $data['tanggungan'] }}</span>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">Tanggungan</p>
                                        <p class="text-xs text-gray-500">Anggota keluarga</p>
                                    </div>
                                    
                                    <div class="stat-card bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-xl border border-yellow-200">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="bg-yellow-100 p-2 rounded-lg">
                                                <i class="fas fa-trophy text-yellow-600"></i>
                                            </div>
                                            <span class="text-xl font-bold text-gray-800">{{ $data['prestasi_non_akademik'] == 'ya' ? 'Ya' : 'Tidak' }}</span>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">Prestasi</p>
                                        <p class="text-xs text-gray-500">Non-akademik</p>
                                    </div>
                                </div>
                                
                                <!-- Detailed Info -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-3">
                                        <h4 class="font-bold text-gray-800 flex items-center">
                                            <i class="fas fa-graduation-cap text-blue-500 mr-2"></i>
                                            Akademik
                                        </h4>
                                        <div class="space-y-2">
                                            <p><span class="font-medium text-gray-600">Fakultas:</span> {{ $data['fakultas'] }}</p>
                                            <p><span class="font-medium text-gray-600">Program Studi:</span> {{ $data['prodi'] }}</p>
                                            <p><span class="font-medium text-gray-600">Semester:</span> {{ $data['semester'] }}</p>
                                            <p><span class="font-medium text-gray-600">Angkatan:</span> {{ $data['angkatan'] }}</p>
                                        </div>
                                    </div>
                                    <div class="space-y-3">
                                        <h4 class="font-bold text-gray-800 flex items-center">
                                            <i class="fas fa-address-card text-purple-500 mr-2"></i>
                                            Kontak
                                        </h4>
                                        <div class="space-y-2">
                                            <p><span class="font-medium text-gray-600">Email:</span> {{ $data['email'] }}</p>
                                            <p><span class="font-medium text-gray-600">Telepon:</span> {{ \App\Helpers\BeasiswaHelper::formatTelepon($data['telepon']) }}</p>
                                            <p><span class="font-medium text-gray-600">Alamat:</span> 
                                                <span class="text-sm">{{ Str::limit($data['alamat'], 50) }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Score Visualization -->
                        @if(isset($scoreBreakdown))
                        <div class="glass-card rounded-2xl shadow-xl overflow-hidden fade-in-up delay-3">
                            <div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-xl font-bold flex items-center">
                                            <i class="fas fa-chart-pie mr-3"></i>
                                            Visualisasi Skor
                                        </h3>
                                        <p class="text-sm opacity-90">Total Skor: {{ $skor }}/100</p>
                                    </div>
                                    <div class="bg-white/20 p-2 rounded-lg">
                                        <span class="text-2xl font-bold">{{ $skor }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <!-- Breakdown -->
                                    <div class="space-y-4">
                                        <div class="space-y-3">
                                            <div class="flex items-center justify-between">
                                                <span class="font-medium text-gray-700 flex items-center">
                                                    <div class="w-3 h-3 rounded-full bg-blue-500 mr-2"></div>
                                                    IPK ({{ \App\Helpers\BeasiswaHelper::getLevelIpk($data['ipk']) }})
                                                </span>
                                                <span class="font-bold text-blue-600">{{ $scoreBreakdown['ipk'] }}/40</span>
                                            </div>
                                            <div class="progress-bar">
                                                <div class="progress-fill bg-blue-500" style="width: {{ ($scoreBreakdown['ipk']/40)*100 }}%"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-3">
                                            <div class="flex items-center justify-between">
                                                <span class="font-medium text-gray-700 flex items-center">
                                                    <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                                                    Ekonomi ({{ \App\Helpers\BeasiswaHelper::getLevelEkonomi($penghasilanPerKapita) }})
                                                </span>
                                                <span class="font-bold text-green-600">{{ $scoreBreakdown['penghasilan'] }}/35</span>
                                            </div>
                                            <div class="progress-bar">
                                                <div class="progress-fill bg-green-500" style="width: {{ ($scoreBreakdown['penghasilan']/35)*100 }}%"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-3">
                                            <div class="flex items-center justify-between">
                                                <span class="font-medium text-gray-700 flex items-center">
                                                    <div class="w-3 h-3 rounded-full bg-purple-500 mr-2"></div>
                                                    Tanggungan
                                                </span>
                                                <span class="font-bold text-purple-600">{{ $scoreBreakdown['tanggungan'] }}/15</span>
                                            </div>
                                            <div class="progress-bar">
                                                <div class="progress-fill bg-purple-500" style="width: {{ ($scoreBreakdown['tanggungan']/15)*100 }}%"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-3">
                                            <div class="flex items-center justify-between">
                                                <span class="font-medium text-gray-700 flex items-center">
                                                    <div class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></div>
                                                    Prestasi
                                                </span>
                                                <span class="font-bold text-yellow-600">{{ $scoreBreakdown['prestasi'] }}/10</span>
                                            </div>
                                            <div class="progress-bar">
                                                <div class="progress-fill bg-yellow-500" style="width: {{ ($scoreBreakdown['prestasi']/10)*100 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Chart -->
                                    <div>
                                        <canvas id="scoreChart" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Applied Rules -->
                        @if(isset($rules) && count($rules) > 0)
                        <div class="glass-card rounded-2xl shadow-xl overflow-hidden fade-in-up delay-4">
                            <div class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white p-6">
                                <h3 class="text-xl font-bold flex items-center">
                                    <i class="fas fa-code-branch mr-3"></i>
                                    Aturan yang Diterapkan
                                </h3>
                                <p class="text-sm opacity-90">{{ count($rules) }} aturan sistem pakar</p>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    @foreach($rules as $rule)
                                    <div class="rule-card bg-white p-4 rounded-xl border-l-4 {{ 
                                        str_contains($rule['result'], 'SANGAT') ? 'border-green-500' : 
                                        (str_contains($rule['result'], 'LAYAK') && !str_contains($rule['result'], 'KURANG') ? 'border-blue-500' : 
                                        (str_contains($rule['result'], 'CUKUP') ? 'border-yellow-500' : 
                                        (str_contains($rule['result'], 'KURANG') ? 'border-orange-500' : 'border-red-500'))) 
                                    }}">
                                        <div class="flex items-start">
                                            <div class="mr-3 mt-1">
                                                <i class="fas fa-arrow-right text-sm {{ 
                                                    str_contains($rule['result'], 'SANGAT') ? 'text-green-500' : 
                                                    (str_contains($rule['result'], 'LAYAK') && !str_contains($rule['result'], 'KURANG') ? 'text-blue-500' : 
                                                    (str_contains($rule['result'], 'CUKUP') ? 'text-yellow-500' : 
                                                    (str_contains($rule['result'], 'KURANG') ? 'text-orange-500' : 'text-red-500'))) 
                                                }}"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-800">{{ $rule['condition'] }}</p>
                                                <div class="mt-2 flex items-center justify-between">
                                                    <span class="text-sm text-gray-600">
                                                        <span class="font-semibold">Hasil:</span> 
                                                        <span class="px-2 py-1 rounded text-xs font-medium ml-1 {{ 
                                                            str_contains($rule['result'], 'SANGAT') ? 'bg-green-100 text-green-800' : 
                                                            (str_contains($rule['result'], 'LAYAK') && !str_contains($rule['result'], 'KURANG') ? 'bg-blue-100 text-blue-800' : 
                                                            (str_contains($rule['result'], 'CUKUP') ? 'bg-yellow-100 text-yellow-800' : 
                                                            (str_contains($rule['result'], 'KURANG') ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800'))) 
                                                        }}">
                                                            {{ $rule['result'] }}
                                                        </span>
                                                    </span>
                                                    <span class="text-xs text-gray-500">{{ $rule['rule'] }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Right Column -->
                    <div class="space-y-8">
                        <!-- Result Status -->
                        <div class="glass-card rounded-2xl shadow-xl overflow-hidden fade-in-up delay-2 slide-in-left">
                            <div class="p-1 bg-gradient-to-r {{ 
                                $kelayakan == 'SANGAT LAYAK' ? 'from-green-500 to-emerald-600' : 
                                ($kelayakan == 'LAYAK' ? 'from-blue-500 to-cyan-600' : 
                                ($kelayakan == 'CUKUP LAYAK' ? 'from-yellow-500 to-amber-600' : 
                                ($kelayakan == 'KURANG LAYAK' ? 'from-orange-500 to-red-500' : 
                                'from-red-500 to-pink-600'))) 
                            }}">
                                <div class="bg-white p-8 rounded-xl text-center">
                                    <!-- Icon -->
                                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4 bg-gradient-to-r {{ 
                                        $kelayakan == 'SANGAT LAYAK' ? 'from-green-500 to-emerald-600' : 
                                        ($kelayakan == 'LAYAK' ? 'from-blue-500 to-cyan-600' : 
                                        ($kelayakan == 'CUKUP LAYAK' ? 'from-yellow-500 to-amber-600' : 
                                        ($kelayakan == 'KURANG LAYAK' ? 'from-orange-500 to-red-500' : 
                                        'from-red-500 to-pink-600'))) 
                                    }}">
                                        <i class="fas {{ 
                                            $kelayakan == 'SANGAT LAYAK' ? 'fa-medal' : 
                                            ($kelayakan == 'LAYAK' ? 'fa-thumbs-up' : 
                                            ($kelayakan == 'CUKUP LAYAK' ? 'fa-check-circle' : 
                                            ($kelayakan == 'KURANG LAYAK' ? 'fa-exclamation-triangle' : 
                                            'fa-times-circle'))) 
                                        }} text-white text-3xl"></i>
                                    </div>
                                    
                                    <!-- Status -->
                                    <div class="mb-4">
                                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">STATUS KELAYAKAN</p>
                                        <h3 class="text-3xl font-bold text-gradient bg-gradient-to-r {{ 
                                            $kelayakan == 'SANGAT LAYAK' ? 'from-green-500 to-emerald-600' : 
                                            ($kelayakan == 'LAYAK' ? 'from-blue-500 to-cyan-600' : 
                                            ($kelayakan == 'CUKUP LAYAK' ? 'from-yellow-500 to-amber-600' : 
                                            ($kelayakan == 'KURANG LAYAK' ? 'from-orange-500 to-red-500' : 
                                            'from-red-500 to-pink-600'))) 
                                        }}">
                                            {{ $kelayakan }}
                                        </h3>
                                    </div>
                                    
                                    <!-- Score -->
                                    <div class="mb-6">
                                        <div class="flex justify-between mb-2">
                                            <span class="text-sm font-medium text-gray-700">Skor Evaluasi</span>
                                            <span class="text-sm font-bold {{ 
                                                $skor >= 80 ? 'text-green-600' : 
                                                ($skor >= 60 ? 'text-blue-600' : 
                                                ($skor >= 40 ? 'text-yellow-600' : 'text-red-600')) 
                                            }}">
                                                {{ $skor }}/100
                                            </span>
                                        </div>
                                        <div class="progress-bar">
                                            <div class="progress-fill {{ 
                                                $skor >= 80 ? 'bg-green-500' : 
                                                ($skor >= 60 ? 'bg-blue-500' : 
                                                ($skor >= 40 ? 'bg-yellow-500' : 'bg-red-500')) 
                                            }}" style="width: {{ $skor }}%"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Rating -->
                                    <div class="flex items-center justify-center space-x-1 mb-6">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ 
                                                $i <= ($skor/20) ? 'text-yellow-400' : 'text-gray-300'
                                            }}"></i>
                                        @endfor
                                        <span class="ml-2 text-sm text-gray-600">
                                            {{ \App\Helpers\BeasiswaHelper::interpretScore($skor) }}
                                        </span>
                                    </div>
                            
                                   <!-- Registration Number -->
                                    @if(isset($noRegistrasi))
                                    <div class="mt-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                        <p class="text-xs font-medium text-gray-700 mb-1">Nomor Registrasi</p>
                                        <p class="text-sm font-bold text-blue-600 font-mono">{{     $noRegistrasi }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-calendar mr-1"></i>
                                     {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Recommendations -->
                        <div class="glass-card rounded-2xl shadow-xl overflow-hidden fade-in-up delay-3 slide-in-left">
                            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6">
                                <h3 class="text-xl font-bold flex items-center">
                                    <i class="fas fa-lightbulb mr-3"></i>
                                    Rekomendasi
                                </h3>
                                <p class="text-sm opacity-90">Saran sistem pakar</p>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    @if($kelayakan == 'SANGAT LAYAK')
                                        <div class="flex items-start">
                                            <div class="mr-3 mt-1 text-green-500">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800 mb-1">Prioritas Tinggi</p>
                                                <p class="text-sm text-gray-600">Direkomendasikan untuk beasiswa penuh berdasarkan IPK tinggi dan kondisi ekonomi.</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="mr-3 mt-1 text-blue-500">
                                                <i class="fas fa-graduation-cap"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800 mb-1">Beasiswa Prestasi</p>
                                                <p class="text-sm text-gray-600">Sangat cocok untuk beasiswa prestasi akademik.</p>
                                            </div>
                                        </div>
                                    @elseif($kelayakan == 'LAYAK')
                                        <div class="flex items-start">
                                            <div class="mr-3 mt-1 text-blue-500">
                                                <i class="fas fa-thumbs-up"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800 mb-1">Layak Diterima</p>
                                                <p class="text-sm text-gray-600">Memenuhi syarat untuk beasiswa parsial atau bantuan pendidikan.</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="mr-3 mt-1 text-purple-500">
                                                <i class="fas fa-handshake"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800 mb-1">Wawancara</p>
                                                <p class="text-sm text-gray-600">Disarankan untuk tahap wawancara.</p>
                                            </div>
                                        </div>
                                    @elseif($kelayakan == 'CUKUP LAYAK')
                                        <div class="flex items-start">
                                            <div class="mr-3 mt-1 text-yellow-500">
                                                <i class="fas fa-info-circle"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800 mb-1">Perlu Evaluasi</p>
                                                <p class="text-sm text-gray-600">Dipertimbangkan untuk program beasiswa khusus.</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="mr-3 mt-1 text-orange-500">
                                                <i class="fas fa-chart-line"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800 mb-1">Tingkatkan IPK</p>
                                                <p class="text-sm text-gray-600">Disarankan meningkatkan IPK pada semester berikutnya.</p>
                                            </div>
                                        </div>
                                    @elseif($kelayakan == 'KURANG LAYAK')
                                        <div class="flex items-start">
                                            <div class="mr-3 mt-1 text-orange-500">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800 mb-1">Perbaikan Dibutuhkan</p>
                                                <p class="text-sm text-gray-600">Fokus pada peningkatan IPK dan prestasi non-akademik.</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="mr-3 mt-1 text-red-500">
                                                <i class="fas fa-redo"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800 mb-1">Coba Semester Depan</p>
                                                <p class="text-sm text-gray-600">Ajukan kembali setelah meningkatkan prestasi akademik.</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-start">
                                            <div class="mr-3 mt-1 text-red-500">
                                                <i class="fas fa-times-circle"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800 mb-1">Tidak Direkomendasikan</p>
                                                <p class="text-sm text-gray-600">IPK di bawah standar minimum yang ditetapkan.</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <div class="mr-3 mt-1 text-gray-500">
                                                <i class="fas fa-book"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800 mb-1">Fokus Akademik</p>
                                                <p class="text-sm text-gray-600">Disarankan untuk fokus pada peningkatan prestasi akademik terlebih dahulu.</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Next Steps -->
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <h5 class="font-bold text-gray-800 mb-4 flex items-center">
                                        <i class="fas fa-list-check mr-2"></i>
                                        Langkah Selanjutnya
                                    </h5>
                                    <div class="space-y-3">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 text-blue-800 flex items-center justify-center mr-3">
                                                1
                                            </div>
                                            <span class="text-sm">Verifikasi dokumen pendukung</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-8 h-8 rounded-full {{ 
                                                $kelayakan == 'SANGAT LAYAK' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' 
                                            }} flex items-center justify-center mr-3">
                                                2
                                            </div>
                                            <span class="text-sm">
                                                Wawancara {{ $kelayakan == 'SANGAT LAYAK' ? '(Opsional)' : '(Wajib)' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-purple-100 text-purple-800 flex items-center justify-center mr-3">
                                                3
                                            </div>
                                            <span class="text-sm">Pengumuman hasil final</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- System Info -->
                        <div class="glass-card rounded-2xl shadow-xl overflow-hidden fade-in-up delay-4 slide-in-left">
                            <div class="bg-gradient-to-r from-gray-700 to-gray-900 text-white p-6">
                                <h3 class="text-xl font-bold flex items-center">
                                    <i class="fas fa-robot mr-3"></i>
                                    Sistem Pakar
                                </h3>
                                <p class="text-sm opacity-90">Rule-Based Expert System</p>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <div class="mr-3 text-green-500">
                                            <i class="fas fa-shield-alt"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">Data Aman</p>
                                            <p class="text-xs text-gray-600">Semua data terenkripsi</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="mr-3 text-blue-500">
                                            <i class="fas fa-bolt"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">Real-time Analysis</p>
                                            <p class="text-xs text-gray-600">Proses analisis instan</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="mr-3 text-purple-500">
                                            <i class="fas fa-cogs"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ count($rules ?? []) }} Rules Applied</p>
                                            <p class="text-xs text-gray-600">Berdasarkan logika IF-THEN</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                                    <p class="text-xs text-gray-500">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Hasil ini berdasarkan sistem pakar dan dapat berubah sesuai kebijakan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
<div class="mt-10 w-full border-t border-gray-100 pt-6 fade-in-up">
    <div class="container mx-auto px-4"> <div class="flex flex-row items-center justify-between">
            
            <div class="text-gray-500 text-sm">
                <p>© 2026 ELIGORA</p>
            </div>
            
            <div class="flex items-center space-x-6">
                <a href="https://github.com/loopkyy" class="text-gray-400 hover:text-gray-900 transition">
                    <i class="fab fa-github text-xl"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-red-500 transition">
                    <i class="fab fa-laravel text-xl"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-blue-500 transition">
                    <i class="fab fa-php text-xl"></i>
                </a>
            </div>

        </div>
    </div>
</div>
        </div>
    </div>
    
    <!-- JS -->
    <script src="{{ asset('js/result-script.js') }}"></script>
</body>
</html>