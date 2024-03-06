<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diamond Shape</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <h1>Diamond Shape</h1>
    <div class="diamond">
    <pre>   
<?php
/**
        * Summary of makeDiamondShape
        * @param mixed $row
        * @return void
        */
function makeDiamondShape($row)
{
    if (!is_numeric($row)) {
        echo '$row parameter must be a number';
        return;
    }

    if ($row % 2 == 0) {
        echo '$row parameter must be an odd number';
        return;
    }

    $midpoint = ($row - 1) / 2;

    // Upper half of the diamond
    for ($i = 0; $i <= $midpoint; $i++) {
        echo str_repeat(' ', $midpoint - $i);
        echo str_repeat('*', 2 * $i + 1);
        echo "<br>";
    }

    // Lower half of the diamond
    for ($i = $midpoint - 1; $i >= 0; $i--) {
        echo str_repeat(' ', $midpoint - $i);
        echo str_repeat('*', 2 * $i + 1);
        echo "<br>";
    }
}


makeDiamondShape(9);
?>
</pre>
    </div>
</body>

</html>