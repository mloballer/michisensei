<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Percentage Increments Calculator</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.png') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px; /* Adjusted font size */
            margin: 20px;
        }
        input, button {
            margin: 5px;
        }
        .result {
            margin-top: 20px;
            display: flex;
            gap: 20px; /* Gap between the tables */
        }
        .table-container {
            display: flex;
            gap: 20px; /* Gap between the tables */
        }
        .table-container table {
            border-collapse: collapse;
            width: 100%; /* Adjusted table width */
        }
        .table-container th, .table-container td {
            border: 1px solid #ddd;
            padding: 3px; /* Reduced padding */
            text-align: center; /* Center align text */
            vertical-align: middle; /* Align vertically center */
        }
        .table-container th {
            background-color: #f2f2f2;
        }
        .percentage-table td {
            height: 18px; /* Reduced height for cells in percentage tables */
        }
        tr:nth-child(even) {
            background-color: #f9f9f9; /* Alternating row colors */
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Percentage Increments Calculator</h1>
    <label for="inputValue">Enter a number:</label>
    <input type="number" id="inputValue">
    <button id="calculateButton">Calculate</button>
    <br><strong><span id="currentNumber"></span></strong>

    <div class="result" id="result">
        <div class="table-container">
            <div id="percentageTableContainer"></div>
            <div id="contractsTableContainer"></div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function calculate() {
                const inputValue = $('#inputValue').val();
                const percentageTableContainer = $('#percentageTableContainer');
                const contractsTableContainer = $('#contractsTableContainer');
                const currentNumberSpan = $('#currentNumber');

                percentageTableContainer.empty(); // Clear previous percentage table
                contractsTableContainer.empty(); // Clear previous contracts table
                currentNumberSpan.empty(); // Clear previous current number display

                if (inputValue === '') {
                    percentageTableContainer.html('<p>Please enter a number.</p>');
                    return;
                }

                currentNumberSpan.html(`Current number: ${inputValue}`);

                const value = parseFloat(inputValue);

                // Table for stop loss and take profit (up to 20 entries)
                let tableHtml1 = '<table class="percentage-table"><tr><th>Take Profit</th><th>Value</th><th>Stop Loss</th><th>Value</th></tr>';

                for (let i = 0; i < 20; i++) {
                    const positivePercent = 5 * (i + 1);
                    const negativePercent = -5 * (i + 1);

                    const positiveValue = (value * (1 + (positivePercent / 100))).toFixed(2);
                    const negativeValue = (value * (1 + (negativePercent / 100))).toFixed(2);

                    tableHtml1 += `<tr>
                                    <td>+${positivePercent}%</td>
                                    <td>${positiveValue}</td>
                                    <td>${negativePercent}%</td>
                                    <td>${negativeValue}</td>
                                  </tr>`;
                }

                tableHtml1 += '</table>';

                // Table for contracts (up to 30 entries)
                let tableHtml2 = '<table><tr><th>Contracts</th><th>Contract Value</th></tr>';

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

                $('#inputValue').val(''); // Clear input field
            }

            $('#calculateButton').click(calculate);

            $('#inputValue').keypress(function(e) {
                if (e.which == 13) { // Enter key pressed
                    calculate();
                }
            });
        });
    </script>
</body>
</html>