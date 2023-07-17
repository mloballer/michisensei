<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Michisensei Name Dictionary</title>
  <style>
    
    body, html {
      height: 100%;
      padding: 0 10px;
    }

    .d-flex.align-items-center {
      height: 100%;
    }
    
    .title {
      font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
      font-size: 56px;
      text-transform: uppercase;
      letter-spacing: 2px;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
      color: red;
    }

    #results {
      margin-top: 20px;
      overflow-y: auto;
      font-family: "Hiragino Sans W3", sans-serif;
      font-size: 20px;
    }

    .input-group {
      display: flex;
      margin-bottom: 1rem;
    }

    .input-group input[type="text"] {
      flex: 1;
      border: 1px solid #ccc;
      border-radius: 5px;
      padding: 5px;
      font-size: 16px;
    }

    .input-group button {
      background-color: white;
      border: none;
      padding: 8px 12px;
      font-size: 16px;
      color: red;
      cursor: pointer;
      border: 2px solid red;
      border-radius: 10px;
    }

    .input-group select {
      border: 1px solid #ccc;
      border-radius: 5px;
      padding: 5px;
      font-size: 16px;
    }

    @media (min-width: 768px) {
      .input-group {
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
      }
      .title {
        text-align: center;
      }

      #results, #results-count {
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
      }
    }

    @media (max-width: 767px) {
      .title {
        font-size: 36px;
        text-align: center;
      }
    }

    @media (max-width: 767px) and (min-device-width: 320px) and (max-device-width: 812px) and (-webkit-min-device-pixel-ratio: 2) {
      .input-group {
        flex-direction: column;
        align-items: center;
      }
    }

    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      margin: 0;
      padding: 0;
    }

    .content {
      flex: 1;
    }

    .footer {
      background-color: #f8f8f8;
      padding: 20px;
      text-align: center;
    }

  </style>
</head>
<body>
<div class="content">
  <div class="d-flex align-items-center justify-content-center h-50">
    <div>
      <h1 class="title">Michisensei Name Dictionary</h1>
    
      <div class="input-group">
        <select id="search-option">
          <option value="contain">Contain</option>
          <option value="start">Start</option>
          <option value="end">End</option>
          <option value="are">Are</option>
        </select> &nbsp;

        <input type="text" id="search-input" placeholder="">
        
        &nbsp; <button id="search-button" onclick="searchWord()">Search</button>
      </div>

      <p style="color: blue;" id="results-count"></p>
      <div id="results"></div>
    </div>

  </div>
</div>

<div class="footer">
  &copy; <?php echo date('Y') ;?> Michisensei Name Dictionary. All rights reserved.
</div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#search-input').on('keydown', function(event) {
        if (event.keyCode === 13) {
          searchWord();
        }
      });
    });

    function searchWord() {
      var word = $('#search-input').val();
      var option = $('#search-option').val();

      //clear last search
      $('#results').empty();
      $('#results-count').empty();

      $('#search-button, body').css('cursor', 'progress');

      $.ajax({
        url: "/dictionary/lookup",
        type: "GET",
        data: { word: word, option: option },
        success: function(response) {

          var resultsContainer = $('#results');
          var countContainer = $('#results-count');

          var reading = ''

          if (response.length > 0) {

            var resultHtml = '';
            $.each(response, function(index, entry) {
              if(entry.reading) reading =  ' [' +  entry.reading + ']';
              resultHtml += '<div><strong>' + entry.word + '</strong>' + reading + '  ' + entry.meaning + '</div>' + '<hr>';
            });
            resultsContainer.html(resultHtml);
            countContainer.html(response.length + ' result(s) found for <strong>' + word + '</strong>');

          } else {
            resultsContainer.html('<p>No results found.</p>');
            countContainer.text('');
          }

          $('#search-button, body').css('cursor', 'auto');
        },
        error: function(xhr, status, error) {
          $('#search-button, body').css('cursor', 'auto');
          console.error(error);
        }
      });
    }
  </script>
</body>
</html>