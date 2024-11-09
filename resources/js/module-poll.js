import Chart from 'chart.js/auto';

const statsContainer = document.querySelector('#statsChartContainer');
const moduleId = statsContainer.dataset.moduleId;

const refs = {
    status: document.querySelector('#refStatus'),
    operatingTime: document.querySelector('#refOperatingTime'),
    metricCount: document.querySelector('#refMetricCount'),
    failuresList: document.querySelector('#failuresList')
};

const updateData = async () => {
    try {
        const { data } = await axios.get(`/api/modules/${moduleId}`);
        const module = data.data;

        // Update metrics with new data
        refs.status.textContent = module.status;
        refs.status.className = `badge text-uppercase ${module.status_class}`;
        refs.operatingTime.textContent = module.operating_time;
        refs.metricCount.textContent = module.metric_count;

        // Clear and update charts with new data
        module.sensors.forEach(sensor => {
            const currentValueElement = document.getElementById(`sensorCurrentValue-${sensor.id}`);
            if (currentValueElement) {
                currentValueElement.textContent = `(${sensor.current_value} ${sensor.unit})`;
            }

            const chart = document.getElementById(`sensorChart-${sensor.id}`)?.chart;
            if (!chart) return;

            // Create new arrays for labels and data
            const newLabels = [...sensor.readings.map(r => r.timestamp)];
            const newData = [...sensor.readings.map(r => r.value)];

            // Clear existing data
            chart.data.labels.length = 0;
            chart.data.datasets[0].data.length = 0;

            // Push new data
            chart.data.labels.push(...newLabels);
            chart.data.datasets[0].data.push(...newData);

            chart.update('none'); // Update without animation for better performance
        });

        // Clear and update failure logs
        if (refs.failuresList) {
            const failures = module.failures || [];
            const newFailuresHTML = failures.length ? failures.map(failure => `
                <div class="list-group-item d-flex justify-content-between">
                    <div>
                        <h6 class="mb-0">${failure.description}</h6>
                        <small class="text-muted">Error Code: ${failure.error_code}</small>
                    </div>
                    <small>${failure.diff_for_humans}</small>
                </div>
            `).join('') : `
                <div class="list-group-item text-center">
                    <p class="mb-0 text-muted">No failure logs found</p>
                </div>
            `;

            // Only update DOM if content has changed
            if (refs.failuresList.innerHTML !== newFailuresHTML) {
                refs.failuresList.innerHTML = newFailuresHTML;
            }
        }

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
                    borderWidth: 1,
                    tension: 0.5,
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { x: { display: false, reverse: true } }
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
