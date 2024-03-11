<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Phone Number</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Generate Phone Number</h1>
        <form action="#" method="get">
            <label for="phone">Enter phone Number(comma-separated):</label><br>
            <input type="text" id="phone" name="phone" placeholder="e.g., 0,0,3,1,2,3" required><br>
            <button type="submit" name="submit">Submit</button>
        </form>
        <div id="result">
            <?php
            if (isset($_GET['submit'])) {
                $numberArray = isset($_GET['phone']) ? explode(',', $_GET['phone']) : [];
                echo "<h2>" . createPhoneNumber($numberArray) . "</h2>";
            }        
            /**
             * Summary of validatePhoneNumber
             * @param mixed $phone
             * @return string
             */
            function validatePhoneNumber($phone) 
            {
                if (!preg_match('/^[0-9,]+$/', $phone)) {
                    return "Please fill numbers only.Comma-separate between numbers.";
                }

                $numbers = explode(',', $phone);
                foreach ($numbers as $num) {
                    if ($num < 0 || $num > 9) {
                        return "Input numbers must be between 0 and 9.";
                    }
                }

                return "";
            }
            /**
             * Summary of createPhoneNumber
             * @param mixed $numberArray
             * @return string
             */
            function createPhoneNumber($numberArray)
            {
                // Validate phone number
                $error = validatePhoneNumber(implode(',', $numberArray));
                if ($error !== "") {
                    return "<span style='color: red;'>Error: $error</span>";
                }

                if (count($numberArray) < 10) {
                    for ($i = count($numberArray); $i < 10; $i++) {
                        array_push($numberArray, 0);
                    }
                }

                // Format the phone number
                $formattedNumber = '(' . implode(array_slice($numberArray, 0, 3)) . ') ' .
                    implode(array_slice($numberArray, 3, 3)) . '-' .
                    implode(array_slice($numberArray, 6));

                return $formattedNumber;
            }

            ?>
        </div>
    </div>
</body>
</html>
