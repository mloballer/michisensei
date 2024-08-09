<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Options Calculator</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .container {
            max-width: 1200px;
        }
        .table {
            font-size: 14px;
        }
        .table th, .table td {
            padding: 0.5rem;
        }
        .results-col {
            padding-left: 15px;
        }
        #results {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3">
                <h3>Options Calculator</h3>
                <form id="calc-form">
                    <div class="form-group">
                        <label for="currentContracts">Contracts:</label>
                        <input type="number" class="form-control" id="currentContracts" value="2" required>
                    </div>
                    <div class="form-group">
                        <label for="currentAverage">Current Average :</label>
                        <input type="number" step="0.01" class="form-control" id="currentAverage" required>
                    </div>
                    <div class="form-group">
                        <label for="desiredAverage">Desired Average (optional):</label>
                        <input type="number" step="0.01" class="form-control" id="desiredAverage" >
                    </div>
                    <button type="submit" class="btn btn-primary">Calculate</button>
                </form>
            </div>
            <div class="col-md-5 profit-results-col">
                <div id="profit-results-header" style="display: none;">
                    <h3>Profit<span id="currentNumber"></span></h3>
                    
                    <div class="table-container">
                    <div id="percentageTableContainer"></div>
                    <div id="contractsTableContainer"></div>
                </div>
                </div>
                
            </div>
            <div class="col-md-4 results-col">
                <div id="results-header" style="display: none;">
                    <h3>Average Price Target</h3>
                </div>
                <div id="results"></div>
            </div>
        </div>
    </div>

</body>
</html>




<script>

$(document).ready(function() {
    $('#calc-form').submit(function(event) {
        event.preventDefault();

        const currentContracts = parseInt($('#currentContracts').val());
        const currentAverage = parseFloat($('#currentAverage').val());
        const desiredAverage = parseFloat($('#desiredAverage').val());

        

        let resultsHtml = '<table class="table table-bordered"><thead><tr><th># of Contracts</th><th>Price</th><th>New Average</th></tr></thead><tbody>';

        for (let i = 1; i <= 20; i++) {
            // Calculate the required total cost to achieve the desired average
            const totalContracts = currentContracts + i;
            const totalCost = (totalContracts * desiredAverage) - (currentContracts * currentAverage);

            // Calculate the price per additional contract
            const requiredPrice = totalCost / i;

            // Calculate the new average
            const newTotalCost = (currentContracts * currentAverage) + (i * requiredPrice);
            const newAverage = newTotalCost / totalContracts;

            resultsHtml += `<tr><td>${i}</td><td>${requiredPrice.toFixed(2)}</td><td>${newAverage.toFixed(2)}</td></tr>`;
        }

        resultsHtml += '</tbody></table>';
        $('#results').html(resultsHtml);
        $('#results-header').show();
        $('#results').show();
        if (isNaN(desiredAverage))
        {
            $('#results-header').hide();
            $('#results').hide();
        }

        const inputValue = $('#currentAverage').val();
        const percentageTableContainer = $('#percentageTableContainer');
        const contractsTableContainer = $('#contractsTableContainer');

        percentageTableContainer.empty(); // Clear previous percentage table
        contractsTableContainer.empty(); // Clear previous contracts table

        if (inputValue === '') {
            percentageTableContainer.html('<p>Please enter a number.</p>');
            return;
        }


        const value = parseFloat(inputValue);

        // Table for stop loss and take profit (up to 20 entries)
        let tableHtml1 = '<table class="table table-bordered percentage-table"><tr><th>Take Profit</th><th>Value</th><th>Stop Loss</th><th>Value</th></tr>';

        for (let i = 0; i < 30; i++) {
                positivePercent = 5 * (i + 1);
                negativePercent = -5 * (i + 1);

                negativePercentText = negativePercent + "%"

                positiveValue = (value * (1 + (positivePercent / 100))).toFixed(2);
                negativeValue = (value * (1 + (negativePercent / 100))).toFixed(2);

            if(negativePercent < -100) negativePercentText = '-';
            if(negativeValue < 0)     negativeValue = '-';

            tableHtml1 += `<tr>
                            <td>+${positivePercent}%</td>
                            <td>${positiveValue}</td>
                            <td>${negativePercentText}</td>
                            <td>${negativeValue}</td>
                            </tr>`;
        }

        tableHtml1 += '</table>';

        // Table for contracts (up to 30 entries)
        let tableHtml2 = '<table class="table table-bordered"><tr><th>Contracts</th><th>Contract Value</th></tr>';

        for (let i = 0; i < 30; i++) {
            const contracts = i + 1;
            const contractValue = Math.round(contracts * value * 100);

            tableHtml2 += `<tr>
                            <td>${contracts}</td>
                            <td>${contractValue}</td>
                            </tr>`;
        }

        tableHtml2 += '</table>';

        percentageTableContainer.html(tableHtml1);
        contractsTableContainer.html(tableHtml2);

        $('#profit-results-header').show();
        
        const currentNumberSpan = $('#currentNumber');
        currentNumberSpan.empty();

        currentNumberSpan.html(` for average: ${inputValue}`);

    });
});





</script>