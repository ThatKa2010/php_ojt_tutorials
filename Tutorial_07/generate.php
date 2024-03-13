<?php
require_once 'libs/phpqrcode/qrlib.php';

function qrCodeExists($qrname, $path)
{
    return file_exists($path . $qrname . ".png");
}

if (isset($_POST['submit'])) {
    if (empty($_POST['qrname'])) {
        header("Location:index.php?error=QR name is required");
        exit;
    } else {
        $qrname = $_POST['qrname'];
        $path = 'images/';

        // Check if the QR name already exists
        if (qrCodeExists($qrname, $path)) {
            header("Location:index.php?error-exists=QR name already exists");
        } else {
            // Generate QR code
            $qrcode = $path . $qrname . ".png";
            QRcode::png("Tech Area", $qrcode, 'H', 4, 4);

            header("Location: index.php?success&qrname=" . urlencode($qrname));
            exit;
        }
    }
}
?>
