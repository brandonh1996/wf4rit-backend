<?php

// Need to change to PDO
$conn = mysqli_connect('localhost', 'root', 'Diamond1', 'wf4rit_dev');
$sql = "SELECT * FROM document";
$result = mysqli_query($conn, $sql);
$files = mysqli_fetch_all($result, MYSQLI_ASSOC);
// 3MB Limit on Filesize
$FileSizeLimit = 3000000;

// File Upload Module
if (isset($_POST['save'])) {
    $filename = $_FILES['myfile']['name'];
    $destination = 'uploads/' . $filename;
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $file = $_FILES['myfile']['tmp_name'];
    $size = $_FILES['myfile']['size'];

    if (!in_array($extension, ['zip', 'pdf', 'docx', 'doc'])) {
        //need to connect with front end socket
        echo "You file extension must be .zip, .pdf or .docx";

    } elseif ($_FILES['myfile']['size'] > $FileSizeLimit) {
        //need to connect with front end socket
        echo "File too large!";
    } else {
        // move the uploaded (temporary) file to the specified destination
        if (move_uploaded_file($file, $destination)) {

            //sets the document ID by getting the highest number
            $sql = "SELECT MAX( documentid ) AS max FROM `document`;";
            $rowSQL = mysqli_query($conn, $sql);
            $row = mysqli_fetch_array( $rowSQL, MYSQLI_ASSOC );
            $documentid = ($row['max'] + 1);

            //set current dates
            $creationDate = date('Y-d-m h:i:s', time());
            $lastModified = date('Y-d-m h:i:s', time());

            //WORK IN PROGRESS
            //Could get the author ID from PHP SESSION
            $isSigned = 'DefaultUser';
            //Need to figure out how to do task IDs
            $taskID = '1';
            ////

            $sql = "INSERT INTO document (documentID, creationDate, isSigned, lastModified, taskID, pdf_file) VALUES ($documentid, '$creationDate', '$isSigned', '$lastModified', $taskID, '$filename')";
            if (mysqli_query($conn, $sql)) {
                //need to connect with front end socket
                echo "File uploaded successfully";
            }
        } else {
            //need to connect with front end socket
            echo "Failed to upload file.";
        }
    }
}

// Downloads files
if (isset($_GET['file_id'])) {
    $id = $_GET['file_id'];

    // fetch file to download from database
    $sql = "SELECT * FROM document WHERE documentID=$id";
    $result = mysqli_query($conn, $sql);

    $file = mysqli_fetch_assoc($result);
    $filepath = 'uploads/' . $file['pdf_file'];

    if (file_exists($filepath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($filepath));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize('uploads/' . $file['pdf_file']));
        readfile('uploads/' . $file['pdf_file']);
        exit;
    }

}