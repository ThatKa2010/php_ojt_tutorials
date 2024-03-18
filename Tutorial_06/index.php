<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php
    if (isset($_GET['error'])) {
        $error = $_GET['error'];
        switch ($error) {
            case 'file_too_large':
                echo "<div class='alert red'>File is too large.File size must be less than or equal 2MB.</div>";
                break;
            case 'invalid_file_type':
                echo "<div class='alert red'>Invalid file type. Only JPG, JPEG, PNG files are allowed.</div>";
                break;
            case 'file_not_uploaded':
                echo "<div class='alert red'>File was not uploaded.</div>";
                break;
            case 'upload_failed':
                echo "<div class='alert red'>File upload failed.</div>";
                break;
            default:
                echo "<div class='alert red'>Unknown error.</div>";
        }
    }

    if (isset($_GET['success'])) {
        $success = $_GET['success'];
        switch ($success) {
            case 'file_uploaded':
                echo "<div class='alert success'>Success: File was uploaded successfully.</div>";
                break;
            case 'success_delete':
                echo "<div class='alert success'>Image file deleted successfully.</div>";
                break;
            default:
                echo "<div class='alert success'>Unknown success.</div>";
        }
    }
    ?>

    <div class="upload">
        <div class="ttl">
            <h1>Upload Image</h1>
        </div>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <label for="folder">Folder Name:</label>
            <input type="text" name="folder" id="folder" placeholder="Enter folder name:">
            <p class="red">
                <?php if (isset($_GET['empty-folder'])||isset($_GET['empty'])) {
                    echo "please fill folder name!";
                } ?>
            </p>
            <label for="image">Choose Image</label>
            <input type="file" name="image" id="image">
            <p class="red">
                <?php if (isset($_GET['empty-img'])||isset($_GET['empty'])) {
                    echo "please choose image file!";
                } ?>
            </p>
            <p class="red">
                <?php if (isset($_GET['duplicate-error'])){
                    echo $_GET['duplicate-error'];
                } ?>
            </p>
            <p class="red">
                <?php if (isset($_GET['validation-error'])){
                    echo $_GET['validation-error'];
                } ?>
            </p>
            <input type="submit" value="Upload Image" name="submit">
        </form>
    </div>

    <div class="img-con">
        <?php
        $directory = 'images/';
        // Create RecursiveDirectoryIterator
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

        foreach ($iterator as $file) {
            if ($file->isFile() && in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png'])) {
                echo '<div class="image">';
                echo '<img src="' . $file->getPathname() . '" alt="Uploaded Image"><br>';
                echo '<h6>' . basename($file->getPathname()) . '</h6>';
                $filePath = str_replace('\\', '/', $file->getPathname());
                $urlLink =$_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/' . $filePath;

                echo '<h6>' . $urlLink . '</h6>';
                echo '<form action="upload.php" method="post">';
                echo '<input type="hidden" name="image_path" value="' . $file->getPathname() . '" readonly>';
                echo '<button type="submit" name="delete" class="delete-btn">Delete</button>';
                echo '</form>';
                echo '</div>';
            }
        }
        ?>
    </div>
</body>
</html>
