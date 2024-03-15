<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Generate</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="ttl">
            <h1>Code Generator</h1>
        </div>
        <form action="generate.php" method="post">
            <label for="qr">QR Name</label>
            <input type="text" name="qrname" id="qr" placeholder="Enter QR name:">
            <?php if (isset ($_GET['error'])) {
                echo "<span>" . $_GET['error'] . "</span>";
            }
            if (isset ($_GET['error-exists'])) {
                echo "<span>" . $_GET['error-exists'] . "</span>";
            }
            ?>
            <button type="submit" name="submit">Generate</button>
        </form>
    </div>

    <div class="current-qr">
        <?php
        if (isset ($_GET['success'])) {
            $qrname = $_GET['qrname'];
            $qrImagePath = 'images/' . $qrname . '.png';
            echo '<h2>QR code is successfully generated.</h2>';
            echo '<img src="' . $qrImagePath . '" alt="QR Code" style="width:300px;">';
        }
        ?>
    </div>

    <div class="show-qr">
        <h3>QR Lists</h3>
        <div class="img-con">
            <?php
            $directory = 'images/';

            // Create RecursiveDirectoryIterator
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

            foreach ($iterator as $file) {
                if ($file->isFile() && in_array(strtolower($file->getExtension()), ['png'])) {
                    echo '<div class="image">';
                    echo '<img src="' . $file->getPathname() . '" alt="Uploaded Image"><br>';
                    echo '<p>' . basename($file->getPathname()) . '</p>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
