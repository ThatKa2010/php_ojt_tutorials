<?php

if (isset($_POST["submit"])) {
    if (empty($_POST["folder"]) && empty($_FILES["image"]["name"])) {
        header("Location: index.php?error=missing_inputs");
        exit;
    } elseif ($_POST['folder'] == "") {
        header("Location: index.php?empty-folder");
        exit;
    } elseif ($_FILES['image']['name'] == null || $_FILES['image']['name'] == "" || empty($_FILES['image']['name'])) {
        header("Location: index.php?empty-img");
        exit;
    }
    $targetDir = "images/";
    $folder = $_POST["folder"];

    if (!file_exists($targetDir . $folder)) {
        mkdir($targetDir . $folder);
    }

    $targetFilePath = $targetDir . $folder . '/' . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Check file extension
    $allowedTypes = array('jpg', 'jpeg', 'png');
    if (!in_array($imageFileType, $allowedTypes)) {
        header("Location: index.php?error=invalid_file_type");
        exit;
    }

    // Check file size
    if ($_FILES["image"]["size"] > 2000000) {
        header("Location: index.php?error=file_too_large");
        exit;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        header("Location: index.php?error=file_not_uploaded");
        exit;
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            header("Location: index.php?success=file_uploaded");
            exit;
        } else {
            header("Location: index.php?error=upload_failed");
            exit;
        }
    }

}

// Check if the delete form is submitted
if (isset($_POST["delete"])) {
    // Get the image path from the form
    $imagePath = $_POST["image_path"];

    // Check if the image file exists
    if (file_exists($imagePath)) {
        if (unlink($imagePath)) {
            header("Location: index.php?success=success_delete");
        } else {
            header("Location: index.php?error=fail_delete");
        }
    }
}
?>
