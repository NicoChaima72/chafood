<?php

class File
{
    private static $path;

    public static function upload($file, $directory = "", $filename = "", $max_size_kB = -1)
    {
        if (empty($file)) return 0;

        $directory_copy = !empty($directory) ? "$directory/" : '';
        $directory = APP_DOCUMENT_ROOT . '/public/' . (!empty($directory) ? "$directory/" : null);
        $extension = "." . pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = !empty($filename) ? to_slug($filename) : uniqid();

        if (!is_dir($directory))
            mkdir($directory, 0777, true);

        if (file_exists($directory . $filename . $extension))
            $filename .= "-" . uniqid();

        $directory .= $filename . $extension;
        self::$path = $directory;

        if ($max_size_kB != -1)
            if ($file['size'] >= $max_size_kB * 1024)
                return 0;

        if (move_uploaded_file($file['tmp_name'], $directory))
            return $directory_copy . $filename . $extension;

        return 0;
    }

    public static function remove($path = "")
    {
        if (empty($path))
            return unlink(self::$path);

        $path = APP_DOCUMENT_ROOT . '/public/' . $path;
        return unlink($path);
    }
}




// $target_dir = "uploads/";
// $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
// $uploadOk = 1;
// $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// // Check if image file is a actual image or fake image
// if (isset($_POST["submit"])) {
//     $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
//     if ($check !== false) {
//         echo "File is an image - " . $check["mime"] . ".";
//         $uploadOk = 1;
//     } else {
//         echo "File is not an image.";
//         $uploadOk = 0;
//     }
// }

// // Check if file already exists
// if (file_exists($target_file)) {
//     echo "Sorry, file already exists.";
//     $uploadOk = 0;
// }

// // Check file size
// if ($_FILES["fileToUpload"]["size"] > 500000) {
//     echo "Sorry, your file is too large.";
//     $uploadOk = 0;
// }

// // Allow certain file formats
// if (
//     $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
//     && $imageFileType != "gif"
// ) {
//     echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
//     $uploadOk = 0;
// }

// // Check if $uploadOk is set to 0 by an error
// if ($uploadOk == 0) {
//     echo "Sorry, your file was not uploaded.";
//     // if everything is ok, try to upload file
// } else {
//     if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
//         echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
//     } else {
//         echo "Sorry, there was an error uploading your file.";
//     }
// }
