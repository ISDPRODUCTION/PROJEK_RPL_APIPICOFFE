/**
 * reportModule.js
 * Manages Sales Report page: Chart.js bar chart, filter switching, export.
 * Sesuai desain referensi: bar oranye untuk nilai tertinggi, abu-abu untuk lainnya,
 * tooltip dark dengan info Total Transaksi + Penghasilan.
 */

const reportModule = (() => {

    let chartInstance = null;
    let currentType   = 'daily';
    let rawData       = [];

    // ── Helpers ───────────────────────────────────────────────────────────────
    function formatRupiah(val) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
    }

    function getBarColors(data) {
        if (!data.length) return [];
        const revenues = data.map(d => d.revenue);
        const max      = Math.max(...revenues);
        return revenues.map(v => v === max && max > 0 ? '#F97316' : '#E2E8F0');
    }

    function formatLabel(d) {
        if (d.date) {
            const dt = new Date(d.date + 'T00:00:00');
            return dt.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }).toUpperCase();
        }
        if (d.month) return d.month;
        if (d.year)  return String(d.year);
        return '';
    }

    // ── Chart initialisation ──────────────────────────────────────────────────
    function initChart() {
        const canvas = document.getElementById('revenue-chart');
        if (!canvas) return;

        if (typeof Chart === 'undefined') {
            console.error('Chart.js belum dimuat');
            return;
        }

        rawData = window.reportChartData || [];

        if (chartInstance) {
            chartInstance.destroy();
            chartInstance = null;
        }

        // Jika tidak ada data, tampilkan empty state
        if (rawData.length === 0) {
            renderEmptyChart(canvas);
            return;
        }

        const labels = rawData.map(d => formatLabel(d));
        const revenues = rawData.map(d => d.revenue);

        chartInstance = new Chart(canvas, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label:           'Pendapatan',
                    data:            revenues,
                    backgroundColor: getBarColors(rawData),
                    hoverBackgroundColor: rawData.map((_, i) => {
                        const revenues2 = rawData.map(d => d.revenue);
                        const max = Math.max(...revenues2);
                        return rawData[i].revenue === max && max > 0 ? '#EA580C' : '#CBD5E1';
                    }),
                    borderRadius:    { topLeft: 8, topRight: 8 },
                    borderSkipped:   false,
                    barPercentage:   0.55,
                    categoryPercentage: 0.8,
                }],
            },
            options: {
                responsive:          true,
                maintainAspectRatio: false,
                animation: {
                    duration: 600,
                    easing: 'easeInOutQuart',
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: true,
                        backgroundColor: '#1C1917',
                        titleColor:      '#D6D3D1',
                        bodyColor:       '#FFFFFF',
                        padding:         { top: 10, bottom: 10, left: 14, right: 14 },
                        cornerRadius:    12,
                        displayColors:   false,
                        titleFont:       { size: 11, weight: '600' },
                        bodyFont:        { size: 12 },
                        callbacks: {
                            title(items) {
                                const idx = items[0].dataIndex;
                                const d   = rawData[idx];
                                if (d.date) {
                                    const dt = new Date(d.date + 'T00:00:00');
                                    return dt.toLocaleDateString('id-ID', {
                                        day: 'numeric', month: 'long', year: 'numeric'
                                    }).toUpperCase();
                                }
                                return d.month || d.year || '';
                            },
                            label(item) {
                                const idx = item.dataIndex;
                                const d   = rawData[idx];
                                return [
                                    `Total Transaksi   ${d.transaction_count ?? 0}`,
                                    `Penghasilan  ${formatRupiah(d.revenue)}`,
                                ];
                            },
                            labelColor(item) {
                                const idx = item.dataIndex;
                                const d   = rawData[idx];
                                const c   = rawData.map(x => x.revenue);
                                const max = Math.max(...c);
                                const col = d.revenue === max && max > 0 ? '#F97316' : '#94A3B8';
                                return { borderColor: col, backgroundColor: col, borderRadius: 3 };
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        grid:   { display: false },
                        ticks:  { color: '#A8A29E', font: { size: 11, weight: '500' } },
                        border: { display: false },
                    },
                    y: {
                        display: false,
                        grid:    { display: false },
                        border:  { display: false },
                    },
                },
            },
        });
    }

    function renderEmptyChart(canvas) {
        // Buat placeholder dengan data dummy 0 agar sumbu X tetap terlihat
        const today = new Date();
        const emptyLabels = [];
        const emptyData   = [];
        for (let i = 6; i >= 0; i--) {
            const d = new Date(today);
            d.setDate(today.getDate() - i);
            emptyLabels.push(d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }).toUpperCase());
            emptyData.push(0);
        }

        chartInstance = new Chart(canvas, {
            type: 'bar',
            data: {
                labels: emptyLabels,
                datasets: [{
                    data:            emptyData,
                    backgroundColor: '#F1F5F9',
                    borderRadius:    { topLeft: 8, topRight: 8 },
                    borderSkipped:   false,
                    barPercentage:   0.55,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend:  { display: false },
                    tooltip: { enabled: false },
                },
                scales: {
                    x: {
                        grid:   { display: false },
                        ticks:  { color: '#A8A29E', font: { size: 11 } },
                        border: { display: false },
                    },
                    y: { display: false },
                },
            },
        });
    }

    function updateChart(data) {
        rawData = data;

        if (!chartInstance) {
            initChart();
            return;
        }

        if (!rawData.length) {
            chartInstance.destroy();
            chartInstance = null;
            renderEmptyChart(document.getElementById('revenue-chart'));
            return;
        }

        const labels   = rawData.map(d => formatLabel(d));
        const revenues = rawData.map(d => d.revenue);

        chartInstance.data.labels                        = labels;
        chartInstance.data.datasets[0].data             = revenues;
        chartInstance.data.datasets[0].backgroundColor  = getBarColors(rawData);
        chartInstance.options.animation.duration        = 400;
        chartInstance.update();
    }

    // ── Filter switch Daily / Weekly ──────────────────────────────────────────
    async function handleFilterChange(type) {
        currentType = type;

        // Toggle button styles
        document.getElementById('btn-daily')?.classList.toggle('bg-[#1C1917]',  type === 'daily');
        document.getElementById('btn-daily')?.classList.toggle('text-white',     type === 'daily');
        document.getElementById('btn-daily')?.classList.toggle('bg-stone-100',   type !== 'daily');
        document.getElementById('btn-daily')?.classList.toggle('text-[#78716C]', type !== 'daily');
        document.getElementById('btn-weekly')?.classList.toggle('bg-[#1C1917]',  type === 'weekly');
        document.getElementById('btn-weekly')?.classList.toggle('text-white',    type === 'weekly');
        document.getElementById('btn-weekly')?.classList.toggle('bg-stone-100',  type !== 'weekly');
        document.getElementById('btn-weekly')?.classList.toggle('text-[#78716C]',type !== 'weekly');

        try {
            const month = window.reportMonth;
            const year  = window.reportYear;
            const res   = await fetch(`/reports/chart-data?type=${type}&month=${month}&year=${year}`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content }
            });
            const json  = await res.json();
            updateChart(json.data || []);
        } catch (err) {
            console.error('Gagal memuat data chart', err);
        }
    }

    // ── Export ─────────────────────────────────────────────────────────────────
    function triggerExport() {
        const month = window.reportMonth;
        const year  = window.reportYear;
        window.open(`/reports/export?type=daily&month=${month}&year=${year}&format=xlsx`, '_blank');
    }

    function openFilter() {
        document.getElementById('filter-modal')?.classList.remove('hidden');
    }

    // ── Init ───────────────────────────────────────────────────────────────────
    function init() {
        if (document.getElementById('revenue-chart')) {
            initChart();
        }
    }

    return { init, initChart, updateChart, handleFilterChange, triggerExport, openFilter };
})();

window.reportModule = reportModule;

document.addEventListener('DOMContentLoaded', () => reportModule.init());
