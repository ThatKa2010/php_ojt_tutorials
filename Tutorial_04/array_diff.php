<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Array Difference Calculator</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container">
        <h1>Array Difference Calculator</h1>
        <form method="GET">
            <label for="array1">Enter elements for Array 1 (comma-separated):</label><br>
            <input type="text" id="array1" name="array1" placeholder="e.g., 1,2,3"><br>
            <label for="array2">Enter elements for Array 2 (comma-separated):</label><br>
            <input type="text" id="array2" name="array2" placeholder="e.g., 3,4,5"><br>
            <button type="submit" name="submit">Submit</button>
        </form>
        <div id="result">
            <?php
            if (isset($_GET['submit'])) {
                $array1 = isset($_GET['array1']) ? explode(',', $_GET['array1']) : [];
                $array2 = isset($_GET['array2']) ? explode(',', $_GET['array2']) : [];

                $differences = arrayDiff($array1, $array2);

                echo "<p>Differences in Array: [" . implode(', ', $differences) . "]</p>";

                echo "<h2>Array1 value: " . json_encode($array1) . "</h2>";
                echo "<h2>Array2 value: " . json_encode($array2) . "</h2>";
            }

            function arrayDiff($array1, $array2)
            {
                $diff = [];

                foreach ($array1 as $value) {
                    if (!in_array($value, $array2)) {
                        $diff[] = $value;
                    }
                }

                return $diff;
            }
            ?>
        </div>
    </div>
</body>
</html>
