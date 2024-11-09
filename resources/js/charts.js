import Chart from 'chart.js/auto';


const ctx = document.getElementById('myChart-1');
let data = [12, 19, 3, 5, 2, 3];
const MAX_LENGTH = 60;

let chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: data,
        datasets: [{
            data: data,
            borderWidth: 1
        }]
    },
    options: {
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                display: false
            }
        }

    }
});

setInterval(() => {
    if (data.length > MAX_LENGTH) {
        data.shift();
    }

    data.push(Math.floor(Math.random() * 100));

    chart.data.labels = data;
    chart.data.datasets[0].data = data;
    chart.update();
}, 1300);
