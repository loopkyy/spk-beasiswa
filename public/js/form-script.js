// IPK Input 
const ipkInput = document.getElementById('ipk-input');
const ipkProgress = document.getElementById('ipk-progress');
const ipkStatus = document.getElementById('ipk-status');

if (ipkInput) {
    ipkInput.addEventListener('input', function(e) {
        const value = parseFloat(e.target.value) || 0;
        const percentage = (value / 4) * 100;
        if (value > 4) {
            this.value = 4;
            this.dispatchEvent(new Event('input'));
            return;
        }
        
        // Update progress bar
        ipkProgress.style.width = `${Math.min(percentage, 100)}%`;
        if (value >= 3.5) {
            ipkProgress.style.background = 'linear-gradient(135deg, #10b981, #34d399)';
            ipkStatus.textContent = 'Sangat Baik';
            ipkStatus.className = 'text-sm font-medium text-green-600';
        } else if (value >= 3.0) {
            ipkProgress.style.background = 'linear-gradient(135deg, #3b82f6, #60a5fa)';
            ipkStatus.textContent = 'Baik';
            ipkStatus.className = 'text-sm font-medium text-blue-600';
        } else if (value >= 2.0) {
            ipkProgress.style.background = 'linear-gradient(135deg, #f59e0b, #fbbf24)';
            ipkStatus.textContent = 'Cukup';
            ipkStatus.className = 'text-sm font-medium text-yellow-600';
        } else if (value > 0) {
            ipkProgress.style.background = 'linear-gradient(135deg, #ef4444, #f87171)';
            ipkStatus.textContent = 'Perlu Ditingkatkan';
            ipkStatus.className = 'text-sm font-medium text-red-600';
        } else {
            ipkProgress.style.background = '#d1d5db';
            ipkStatus.textContent = '';
        }
    });
    
    // Trigger initial input event jika ada nilai awal
    if (ipkInput.value) {
        ipkInput.dispatchEvent(new Event('input'));
    }
}

// Prestasi Non-Akademik Handler
document.querySelectorAll('input[name="prestasi_non_akademik"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const container = document.getElementById('prestasi-akademik-container');
        if (this.value === 'ya') {
            container.classList.remove('hidden');
            container.classList.add('fade-in');
        } else {
            container.classList.add('hidden');
            // Reset semua checkbox jika "Tidak" dipilih
            document.querySelectorAll('#prestasi-akademik-container input[type="checkbox"]').forEach(cb => {
                cb.checked = false;
            });
        }
    });
});

// Validasi NIM
const nimInput = document.querySelector('input[name="nim"]');
if (nimInput) {
    nimInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 11) {
            value = value.slice(0, 11);
        }
        e.target.value = value;
        updateNimValidation(value);
    });
    
    // Validasi saat blur
    nimInput.addEventListener('blur', function() {
        updateNimValidation(this.value);
    });
}

function updateNimValidation(value) {
    const nimMessage = document.getElementById('nim-validation') || createValidationMessage(nimInput, 'nim-validation');
    
    if (value.length === 0) {
        nimMessage.textContent = '⚠ Masukkan 11 digit NIM';
        nimMessage.className = 'text-xs text-yellow-600 mt-1';
    } else if (value.length === 11) {
        nimMessage.textContent = '✓ NIM valid (11 digit)';
        nimMessage.className = 'text-xs text-green-600 mt-1';
    } else {
        nimMessage.textContent = `✗ NIM harus 11 digit (${value.length}/11)`;
        nimMessage.className = 'text-xs text-red-600 mt-1';
    }
}

// Validasi Telepon
const telInput = document.querySelector('input[name="telepon"]');
if (telInput) {
    telInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^\d\s\-\.\+\(\)]/g, '');
        if (value.length > 15) {
            value = value.slice(0, 15);
        }
        
        e.target.value = value;
        const cleanValue = value.replace(/\D/g, '');
        updatePhoneValidation(cleanValue);
    });
    
    // Validasi saat blur
    telInput.addEventListener('blur', function() {
        const cleanValue = this.value.replace(/\D/g, '');
        updatePhoneValidation(cleanValue);
        if (cleanValue.length >= 10 && cleanValue.length <= 13) {
            this.value = formatPhoneNumber(cleanValue);
        }
    });
    
    // Format nomor telepon
    function formatPhoneNumber(phone) {
        const clean = phone.replace(/\D/g, '');
        
        if (clean.length === 10) {
            return clean.replace(/(\d{4})(\d{3})(\d{3})/, '$1-$2-$3');
        } else if (clean.length === 11) {
            return clean.replace(/(\d{4})(\d{4})(\d{3})/, '$1-$2-$3');
        } else if (clean.length === 12) {
            return clean.replace(/(\d{4})(\d{4})(\d{4})/, '$1-$2-$3');
        } else if (clean.length === 13) {
            return clean.replace(/(\d{4})(\d{4})(\d{5})/, '$1-$2-$3');
        }
        
        return phone;
    }
}

function updatePhoneValidation(value) {
    const telMessage = document.getElementById('tel-validation') || createValidationMessage(telInput, 'tel-validation');
    const cleanValue = value.replace(/\D/g, '');
    
    if (cleanValue.length === 0) {
        telMessage.textContent = '⚠ Masukkan 10-13 digit nomor telepon';
        telMessage.className = 'text-xs text-yellow-600 mt-1';
    } else if (cleanValue.length >= 10 && cleanValue.length <= 13) {
        telMessage.textContent = `✓ Nomor telepon valid (${cleanValue.length} digit)`;
        telMessage.className = 'text-xs text-green-600 mt-1';
    } else if (cleanValue.length < 10) {
        telMessage.textContent = `✗ Minimal 10 digit (${cleanValue.length}/10)`;
        telMessage.className = 'text-xs text-red-600 mt-1';
    } else {
        telMessage.textContent = '✗ Maksimal 13 digit';
        telMessage.className = 'text-xs text-red-600 mt-1';
    }
}

// Validasi Alamat
const alamatTextarea = document.querySelector('textarea[name="alamat"]');
if (alamatTextarea) {
    alamatTextarea.addEventListener('input', function(e) {
        const value = e.target.value.trim();
        updateAlamatValidation(value);
    });
    
    alamatTextarea.addEventListener('blur', function() {
        updateAlamatValidation(this.value.trim());
    });
}

function updateAlamatValidation(value) {
    const alamatMessage = document.getElementById('alamat-validation') || createValidationMessage(alamatTextarea, 'alamat-validation');
    
    if (value.length === 0) {
        alamatMessage.textContent = '⚠ Masukkan alamat lengkap (minimal 10 karakter)';
        alamatMessage.className = 'text-xs text-yellow-600 mt-1';
    } else if (value.length < 10) {
        alamatMessage.textContent = `✗ Alamat terlalu pendek (${value.length}/10 karakter)`;
        alamatMessage.className = 'text-xs text-red-600 mt-1';
    } else if (/^\d+$/.test(value)) {
        alamatMessage.textContent = '✗ Alamat tidak boleh hanya angka';
        alamatMessage.className = 'text-xs text-red-600 mt-1';
    } else {
        alamatMessage.textContent = '✓ Format alamat valid';
        alamatMessage.className = 'text-xs text-green-600 mt-1';
    }
}

// Helper untuk membuat elemen pesan validasi
function createValidationMessage(inputElement, id) {
    let messageElement = document.getElementById(id);
    
    if (!messageElement) {
        messageElement = document.createElement('p');
        messageElement.id = id;
        messageElement.className = 'text-xs mt-1';
        inputElement.parentNode.parentNode.appendChild(messageElement);
    }
    
    return messageElement;
}
const fakultasSelect = document.getElementById('fakultas');
const prodiSelect = document.getElementById('prodi');

// Data Prodi berdasarkan Fakultas
const prodiData = {
    'Ekonomi & Bisnis': [
        'Akuntansi',
        'Manajemen', 
        'Bisnis Digital'
    ],
    'Ilmu Komputer': [
        'Teknik Informatika',
        'Sistem Informasi',
        'Desain Komunikasi Visual',
        'Teknik Sipil'
    ],
    'Kehutanan & Lingkungan': [
        'Kehutanan',
        'Ilmu Lingkungan'
    ],
    'Hukum': [
        'Ilmu Hukum'
    ],
    'Keguruan & Ilmu Pendidikan': [
        'Bahasa dan Sastra Indonesia',
        'Pendidikan Biologi',
        'Pendidikan Ekonomi',
        'Pendidikan Bahasa Inggris',
        'Pendidikan Matematika',
        'Pendidikan Guru Sekolah Dasar'
    ]
};

// Update Prodi saat Fakultas berubah
if (fakultasSelect) {
    fakultasSelect.addEventListener('change', function() {
        const selectedFakultas = this.value;
        
        // Reset Prodi options
        prodiSelect.innerHTML = '<option value="">Pilih Program Studi</option>';
        
        if (selectedFakultas && prodiData[selectedFakultas]) {
            prodiSelect.disabled = false;
            prodiSelect.classList.remove('opacity-50');
            prodiSelect.classList.add('opacity-100');
            
            // Tambah opsi Prodi sesuai Fakultas
            prodiData[selectedFakultas].forEach(prodi => {
                const option = document.createElement('option');
                option.value = prodi;
                option.textContent = prodi;
                if (prodi === "{{ old('prodi') }}") {
                    option.selected = true;
                }
                
                prodiSelect.appendChild(option);
            });
        } else {
            prodiSelect.disabled = true;
            prodiSelect.classList.add('opacity-50');
            prodiSelect.classList.remove('opacity-100');
        }
    });
}

// Form validation
const form = document.querySelector('form');
if (form) {
    form.addEventListener('submit', function(e) {
        let isValid = true;
        let errorMessages = [];
        
        // Validasi NIM
        const nimValue = nimInput?.value || '';
        if (nimValue.length !== 11) {
            isValid = false;
            errorMessages.push('NIM harus 11 digit angka');
            nimInput?.classList.add('border-red-500');
        } else {
            nimInput?.classList.remove('border-red-500');
        }
        
        // Validasi Telepon
        const telValue = telInput?.value || '';
        const telClean = telValue.replace(/\D/g, '');
        
        if (telClean.length < 10) {
            isValid = false;
            errorMessages.push('Nomor telepon minimal 10 digit angka');
            telInput?.classList.add('border-red-500');
        } else if (telClean.length > 13) {
            isValid = false;
            errorMessages.push('Nomor telepon maksimal 13 digit angka');
            telInput?.classList.add('border-red-500');
        } else {
            telInput?.classList.remove('border-red-500');
        }
        
        // Validasi Alamat
        const alamatValue = alamatTextarea?.value.trim() || '';
        if (alamatValue.length < 10) {
            isValid = false;
            errorMessages.push('Alamat minimal 10 karakter');
            alamatTextarea?.classList.add('border-red-500');
        } else if (/^\d+$/.test(alamatValue)) {
            isValid = false;
            errorMessages.push('Alamat tidak boleh hanya angka');
            alamatTextarea?.classList.add('border-red-500');
        } else {
            alamatTextarea?.classList.remove('border-red-500');
        }
        
        // Validasi IPK
        const ipkValue = parseFloat(ipkInput?.value || 0);
        if (ipkValue < 0 || ipkValue > 4) {
            isValid = false;
            errorMessages.push('IPK harus antara 0.00 dan 4.00');
            ipkInput?.classList.add('border-red-500');
        } else {
            ipkInput?.classList.remove('border-red-500');
        }
        
        // Jika ada error, tampilkan dan cegah submit
        if (!isValid) {
            e.preventDefault();
            const errorSummary = errorMessages.join('\n• ');
            alert('Mohon perbaiki data berikut:\n• ' + errorSummary);
            
            return false;
        }
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menganalisis...';
            submitBtn.disabled = true;
        }
    });
}

// input mask untuk telepon
if (telInput) {
    telInput.addEventListener('keyup', function(e) {
        let value = this.value.replace(/[^\d\s\-\.\+\(\)]/g, '');
        const clean = value.replace(/\D/g, '');
        if (clean.length === 10) {
            value = clean.replace(/(\d{4})(\d{3})(\d{3})/, '$1-$2-$3');
        } else if (clean.length === 11) {
            value = clean.replace(/(\d{4})(\d{4})(\d{3})/, '$1-$2-$3');
        } else if (clean.length === 12) {
            value = clean.replace(/(\d{4})(\d{4})(\d{4})/, '$1-$2-$3');
        } else if (clean.length === 13) {
            value = clean.replace(/(\d{4})(\d{4})(\d{5})/, '$1-$2-$3');
        }
        
        this.value = value;
    });
}

// Animasi fade in
function fadeIn(element) {
    element.style.opacity = 0;
    element.style.display = 'block';
    
    let opacity = 0;
    const timer = setInterval(function() {
        if (opacity >= 1) {
            clearInterval(timer);
        }
        element.style.opacity = opacity;
        opacity += 0.1;
    }, 20);
}
document.addEventListener('DOMContentLoaded', function() {
    // Trigger fakultas
    if (fakultasSelect && fakultasSelect.value) {
        fakultasSelect.dispatchEvent(new Event('change'));
    }
    
    // Trigger validasi
    if (nimInput && nimInput.value) {
        updateNimValidation(nimInput.value);
    }
    
    if (telInput && telInput.value) {
        const cleanValue = telInput.value.replace(/\D/g, '');
        updatePhoneValidation(cleanValue);
    }
    
    if (alamatTextarea && alamatTextarea.value) {
        updateAlamatValidation(alamatTextarea.value.trim());
    }
    
    // Trigger prestasi akademik jika sudah dipilih
    const prestasiYa = document.querySelector('input[name="prestasi_non_akademik"][value="ya"]');
    if (prestasiYa && prestasiYa.checked) {
        const container = document.getElementById('prestasi-akademik-container');
        if (container) {
            container.classList.remove('hidden');
        }
    }
    
});
