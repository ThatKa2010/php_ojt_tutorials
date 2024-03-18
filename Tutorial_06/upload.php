<?php
if (isset ($_POST["submit"])) {
    // Check if folder and image fields are empty
    if (empty ($_POST["folder"]) && empty ($_FILES["image"]["name"])) {
        header("Location: index.php?empty");
        exit;
    } elseif ($_POST['folder'] == "") {
        header("Location: index.php?empty-folder");
        exit;
    } elseif ($_FILES['image']['name'] == null || $_FILES['image']['name'] == "" || empty ($_FILES['image']['name'])) {
        header("Location: index.php?empty-img");
        exit;
    }

    // Define target directory and folder name
    $targetDir = "images/";
    $folder = $_POST["folder"];
    // Extract filename from the uploaded image
    $fileName = basename($_FILES["image"]["name"]);
    $fileNameWithoutExtension = pathinfo($fileName, PATHINFO_FILENAME);

    // Check if the file with the same name already exists in the directory
    if (file_exists($targetDir . $folder . '/' . $fileName)) {
        header("Location: index.php?duplicate-error=This file name is already exist!");
        exit;
    }

    // Check if folder name and file name without extension are the same
    if ($folder == $fileNameWithoutExtension) {
        header("Location: index.php?validation-error=Folder name and file name cannot be the same!");
        exit;
    }

    // Check if folder exists, if not, create it
    if (!file_exists($targetDir . $folder)) {
        mkdir($targetDir . $folder);
    }

    // Define the target file path
    $targetFilePath = $targetDir . $folder . '/' . $fileName;

    // Check file extension
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
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

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
        header("Location: index.php?success=file_uploaded");
        exit;
    } else {
        header("Location: index.php?error=upload_failed");
        exit;
    }
}

// Check if the delete form is submitted
if (isset ($_POST["delete"])) {
    // Get the image path from the form
    $imagePath = $_POST["image_path"];

    if (file_exists($imagePath)) {
        if (unlink($imagePath)) {
            header("Location: index.php?success=success_delete");
        } else {
            header("Location: index.php?error=fail_delete");
        }
    }
}
?>
