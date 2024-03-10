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
                    $('#result').html('<p>date must not greater than or equal tomorrow.</p>');
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
            if (isset($_POST['dob'])) {
                $dob = $_POST['dob'];
                $today = new DateTime(date('Y-m-d 00:00:00'));
                $bday = new DateTime($dob);
                $diff = $today->diff($bday);

                printf("$('#result').html(' Your age is %d years, %d months and %d days')", $diff->y, $diff->m, $diff->d);
            }
            ?>

        });
    </script>
</body>
</html>
