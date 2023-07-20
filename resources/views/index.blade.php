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

  #floating-legend {
    position: fixed;
    bottom: 60px;
    right: 20px;
    z-index: 9999;
  }

  #legend-button {
    background-color: red;
    color: white;
    border: none;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    font-size: 18px;
    cursor: pointer;

    display: flex;
    justify-content: center;
    align-items: center;
  }

  #legend-box {
    display: none;
    position: absolute;
    background-color: white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 10px;
    border-radius: 5px;
    right: 0px;
    bottom: 80px;
    width: 365px;
    border: 2px solid red;

    /* Updated styles for mobile */
      @media (max-width: 767px) {
        right: 20px;
        bottom: 63px;
        width: 300px;
        font-size: 11px;
      }
  }

  #legend-box p {
    margin: 0;
  }

  span {
    font-family: monospace;
    margin: 0.1em 0px;
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

      <p style="color: red;" id="results-count"></p>
      <div id="results"></div>
    </div>

  <div id="floating-legend">
    <button id="legend-button" onclick="toggleLegend()">Key</button>
    <div id="legend-box">

      <span>
        s - surname<br>
        p - place-name<br>
        u - person name, either given or surname,<br> 
            &nbsp;&nbsp;&nbsp;&nbsp;as-yet unclassified<br>
        g - given name, as-yet not classified by sex<br>
        f - female given name<br>
        m - male given name<br>
        h - full name of a particular person<br>
            &nbsp;&nbsp;&nbsp;&nbsp;(usually family plus given)<br>
        pr - product name<br>
        c - company name<br>
        o - organization name<br>
        st - stations<br>
        wk - work of literature, art, film, etc.<br>
      </span>

    </div>
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

    function toggleLegend() {
      var legendBox = document.getElementById("legend-box");
      if (legendBox.style.display === "block") {
        legendBox.style.display = "none";
      } else {
        legendBox.style.display = "block";
      }
    }

  </script>
</body>
</html>