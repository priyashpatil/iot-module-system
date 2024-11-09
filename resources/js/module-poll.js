import Chart from 'chart.js/auto';

const statsContainer = document.querySelector('#statsChartContainer');
const moduleId = statsContainer.dataset.moduleId;

const refs = {
    status: document.querySelector('#refStatus'),
    operatingTime: document.querySelector('#refOperatingTime'),
    metricCount: document.querySelector('#refMetricCount')
};

const updateData = async () => {
    try {
        const { data } = await axios.get(`/api/modules/${moduleId}`);
        const module = data.data;

        // Update metrics
        refs.status.textContent = module.status;
        refs.operatingTime.textContent = module.operating_time;
        refs.metricCount.textContent = module.metric_count;

        // Update charts
        module.sensors.forEach(sensor => {
            const chart = document.getElementById(`sensorChart-${sensor.id}`)?.chart;
            if (!chart) return;

            const labels = sensor.readings.map(r => r.timestamp);
            const data = sensor.readings.map(r => r.value);

            chart.data.labels = labels;
            chart.data.datasets[0].data = data;
            chart.update();
        });

    } catch (err) {
        console.error('Error fetching module data:', err);
    }
};

// Initialize charts
const initCharts = (sensors) => {
    sensors.forEach(sensor => {
        const canvas = document.getElementById(`sensorChart-${sensor.id}`);
        if (!canvas) return;

        canvas.chart = new Chart(canvas, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { x: { display: false } }
            }
        });
    });
};

(async () => {
    try {
        const { data } = await axios.get(`/api/modules/${moduleId}`);
        initCharts(data.data.sensors);
    } catch (err) {
        console.error('Error initializing charts:', err);
    }
})();

setInterval(updateData, 1300);
