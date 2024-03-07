<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Age Calculator</title>
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container">
    <div id="result"></div>
    <h1>Age Calculator</h1>
    <label for="dob">Date of Birth:</label>
    <input type="date" id="dob" name="dob" placeholder="mm/dd/yyyy">
    <button id="calculate">Calculate</button>
  </div>

  <script src="libs/jquery-3.7.1.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#calculate').click(function () {
        $('#result').addClass('alert');
        var dob = $('#dob').val();
        var currentDate = new Date().toISOString().split('T')[0];

        if (dob > currentDate || dob === currentDate) {
          $('#result').html('<p>Your date of birth is must be less than Today!.</p>');
          return;
        }

        if ($('#dob').val() == "") {
          $('#result').html("<p>please fill you date of birth!</p>");
          return false;
        }
        var dob = $('#dob').val();

        $.ajax({
          url: 'index.php',
          type: 'POST',
          data: { dob: dob },
          success: function (response) {
            $('#result').html(response);
          }
        });
      });

      <?php
      if (isset($_POST['dob'])) 
      {
        $dob = $_POST['dob'];
        $currentDate = date('Y-m-d');
        if ((int) $dob > (int) $currentDate) {
          echo "$('#result').html('Your date of birth must be less than Today!')";
          return -1;
        }

        $age = floor((strtotime($currentDate) - strtotime($dob)) / (365 * 24 * 60 * 60));

        $remainingSeconds = floor((strtotime($currentDate) - strtotime($dob)) % (365 * 24 * 60 * 60));

        $months = floor($remainingSeconds / (30 * 24 * 60 * 60));

        $remainingSeconds = $remainingSeconds % (30 * 24 * 60 * 60);

        $days = floor($remainingSeconds / (24 * 60 * 60));
        echo "$('#result').html('You are $age years old, $months is month and $days days.');";
      }
      ?>
    });
  </script>
</body>
</html>
