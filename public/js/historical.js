$(document).ready(function () {
    // table
    let rowsToShow = 10;
    let rows = $('.data-row');
    rows.slice(0, rowsToShow).show();

    $('#showMoreBtn').click(function () {
        rowsToShow += 5;
        rows.slice(0, rowsToShow).show();

        if (rowsToShow >= rows.length) {
            $(this).hide();
        }
    });

    // chart
    let labels = [];
    let openPrices = [];
    let closePrices = [];

    let data = window.historicalData || [];
    data.forEach(price => {
        labels.push(price.date ? new Date(price.date * 1000).toISOString().slice(0,10) : 'N/A');
        openPrices.push(price.open || 'N/A');
        closePrices.push(price.close || 'N/A');
    });

    let ctx = document.getElementById('priceChart').getContext('2d');
    let chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Open',
                data: openPrices,
                borderColor: 'rgb(75, 192, 192)',
                fill: false
            }, {
                label: 'Close',
                data: closePrices,
                borderColor: 'rgb(255, 99, 132)',
                fill: false
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Open and Close Prices Over Time'
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Price'
                    }
                }
            }
        }
    });
})
