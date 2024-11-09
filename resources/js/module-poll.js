import Chart from 'chart.js/auto';

/**
 * DOM element containers and references for module statistics and charts.
 */
const statsContainer = document.querySelector('#statsChartContainer');
const moduleId = statsContainer.dataset.moduleId;

/**
 * Object containing references to DOM elements for displaying module information.
 * @type {Object}
 */
const refs = {
    status: document.querySelector('#refStatus'),
    operatingTime: document.querySelector('#refOperatingTime'),
    metricCount: document.querySelector('#refMetricCount'),
    failuresList: document.querySelector('#failuresList')
};

/**
 * Updates the page with new module data including status, metrics, charts, and failure logs.
 * @param {Object} module - Module data object containing status, sensors, and failure information
 * @param {string} module.status - Current status of the module
 * @param {string} module.status_class - CSS class for styling the status badge
 * @param {string} module.operating_time - Module's operating time
 * @param {number} module.metric_count - Count of metrics
 * @param {Array} module.sensors - Array of sensor objects
 * @param {Array} module.failures - Array of failure log objects
 */
const setPageData = async (module) => {
    // Update metrics with new data
    refs.status.textContent = module.status;
    refs.status.className = `badge text-uppercase ${module.status_class}`;
    refs.operatingTime.textContent = module.operating_time;
    refs.metricCount.textContent = module.metric_count;

    // Update sensor charts and current values
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

        // Clear and update chart data
        chart.data.labels.length = 0;
        chart.data.datasets[0].data.length = 0;
        chart.data.labels.push(...newLabels);
        chart.data.datasets[0].data.push(...newData);
        chart.update('none');
    });

    // Update failure logs list
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

        if (refs.failuresList.innerHTML !== newFailuresHTML) {
            refs.failuresList.innerHTML = newFailuresHTML;
        }
    }
};

/**
 * Initializes Chart.js line charts for each sensor.
 * @param {Array} sensors - Array of sensor objects to create charts for
 */
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

/**
 * Initializes the page when DOM content is loaded.
 * Fetches initial module data, sets up charts, and starts polling for updates
 * if the module is not deactivated.
 */
document.addEventListener('DOMContentLoaded', async () => {
    const res = await axios.get(`/api/modules/${moduleId}`);
    initCharts(res.data.data.sensors);
    setPageData(res.data.data);

    // Set up polling interval for active modules
    if (refs.status.textContent !== 'deactivated') {
        setInterval(async () => {
            const res = await axios.get(`/api/modules/${moduleId}`);
            setPageData(res.data.data);
        }, 1300);
    }
});
