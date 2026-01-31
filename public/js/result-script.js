function initializeScoreChart() {
    const ctx = document.getElementById('scoreChart');
    if (!ctx || !window.scoreBreakdown) return;
    
    const scoreChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['IPK', 'Ekonomi', 'Tanggungan', 'Prestasi'],
            datasets: [{
                data: [
                    window.scoreBreakdown.ipk,
                    window.scoreBreakdown.penghasilan,
                    window.scoreBreakdown.tanggungan,
                    window.scoreBreakdown.prestasi
                ],
                backgroundColor: [
                    'rgba(59, 130, 246, 0.9)',
                    'rgba(16, 185, 129, 0.9)',
                    'rgba(139, 92, 246, 0.9)',
                    'rgba(245, 158, 11, 0.9)'
                ],
                borderColor: [
                    'rgb(59, 130, 246)',
                    'rgb(16, 185, 129)',
                    'rgb(139, 92, 246)',
                    'rgb(245, 158, 11)'
                ],
                borderWidth: 2,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.parsed + ' poin';
                            return label;
                        }
                    },
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: { size: 14 },
                    bodyFont: { size: 13 },
                    padding: 12
                }
            }
        }
    });
    
    return scoreChart;
}

// Animate progress bars
function animateProgressBars() {
    const progressBars = document.querySelectorAll('.progress-fill');
    
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0';
        
        setTimeout(() => {
            bar.style.width = width;
            bar.style.setProperty('--progress-width', width);
        }, 300);
    });
}
// Get status color based on kelayakan
function getStatusColor(kelayakan) {
    const colors = {
        'SANGAT LAYAK': 'from-green-500 to-emerald-600',
        'LAYAK': 'from-blue-500 to-cyan-600',
        'CUKUP LAYAK': 'from-yellow-500 to-amber-600',
        'KURANG LAYAK': 'from-orange-500 to-red-500',
        'TIDAK LAYAK': 'from-red-500 to-pink-600'
    };
    
    return colors[kelayakan] || 'from-gray-500 to-gray-600';
}

// Get status icon based on kelayakan
function getStatusIcon(kelayakan) {
    const icons = {
        'SANGAT LAYAK': 'fa-medal',
        'LAYAK': 'fa-thumbs-up',
        'CUKUP LAYAK': 'fa-check-circle',
        'KURANG LAYAK': 'fa-exclamation-triangle',
        'TIDAK LAYAK': 'fa-times-circle'
    };
    
    return icons[kelayakan] || 'fa-question-circle';
}

// Get score color based on value
function getScoreColor(score) {
    if (score >= 80) return { text: 'text-green-600', bg: 'bg-green-500' };
    if (score >= 60) return { text: 'text-blue-600', bg: 'bg-blue-500' };
    if (score >= 40) return { text: 'text-yellow-600', bg: 'bg-yellow-500' };
    return { text: 'text-red-600', bg: 'bg-red-500' };
}
document.addEventListener('DOMContentLoaded', function() {
    animateProgressBars();
    if (window.scoreBreakdown) {
        initializeScoreChart();
    }
    const printBtn = document.getElementById('printBtn');
    const downloadBtn = document.getElementById('downloadBtn');
    
    if (printBtn) {
        printBtn.addEventListener('click', printResult);
    }
    
    if (downloadBtn) {
        downloadBtn.addEventListener('click', downloadPDF);
    }
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
        });
    });
    const ruleCards = document.querySelectorAll('.rule-card');
    ruleCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateX(5px)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateX(0)';
        });
    });
    if (window.kelayakan && window.skor) {
        const stars = document.querySelectorAll('.fa-star');
        const starCount = Math.min(5, Math.floor(window.skor / 20));
        
        stars.forEach((star, index) => {
            if (index < starCount) {
                star.classList.add('text-yellow-400');
                star.classList.remove('text-gray-300');
            }
        });
    }
});