<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutorial_01</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        p {
            color: red;
            font-size: 40px;
        }
    </style>
</head>

<body>
    <h1>ChessBoard tutorial</h1>

    <?php
    /**
     * Summary of drawChessBoard
     * @param mixed $rows
     * @param mixed $cols
     * @return void
     */
    function drawChessBoard($rows, $cols)
    {
        if (!is_numeric($rows) && !is_numeric($cols)) {
            echo '<p>$rows and $cols parameter must be numbers</p>';
            return;
        } elseif (!is_numeric($rows) && $cols <= 0) {
            echo '<p>$rows parameter must be numbers and $cols must be greater than 0</p>';
            return;
        } elseif ($rows <= 0 && !is_numeric($cols)) {
            echo '<p>$rows parameter must be greater than 0 and $cols must be number</p>';
            return;
        } elseif (!is_numeric($rows)) {
            echo '<p>$rows parameter must be numbers</p>';
            return;
        } elseif (!is_numeric($cols)) {
            echo '<p>$cols parameter must be numbers</p>';
            return;
        }

        if ($rows <= 0 && $cols <= 0) {
            echo '<p>$rows and $cols parameter must be greater than 0</p>';
            return;
        } elseif ($rows <= 0) {
            echo '<p>$rows parameter must be greater than 0</p>';
            return;
        } elseif ($cols <= 0) {
            echo '<p>$cols parameter must be greater than 0</p>';
            return;
        }

        for ($i = 0; $i < $rows; $i++) {
            echo "<div class='chess-board'>";
            for ($j = 0; $j < $cols; $j++) {
                if (($i + $j) % 2 == 0) {
                    echo "<div class='white-div'></div>";
                } else {
                    echo "<div class='black-div'></div>";
                }
            }
            echo "</div>";
        }
        echo "</div>";
    }


    drawChessBoard(8, 8);
    ?>
</body>

</html>