<html>
<head>
    <title>File Upload</title>
</head>
<body>

<?php

var_dump($_FILES);

		// Verify there were uploaded files and no errors
if (count($_FILES) > 0 && $_FILES['file1']['error'] == 0) {
    	// Set the destination directory for uploads
    $upload_dir = '/vagrant/sites/codeup.dev/public/uploads/';
    	// Grab the filename from the uploaded file by using basename
    $filename = basename($_FILES['file1']['name']);
    	// Create the saved filename using the file's original name and our upload directory
    $saved_filename = $upload_dir . $filename;
    	// Move the file from the temp location to our uploads directory
    move_uploaded_file($_FILES['file1']['tmp_name'], $saved_filename);
}

		// Check if we saved a file
if (isset($saved_filename)) {
    	// If we did, show a link to the uploaded file
    echo "<p>You can download your file <a href='/uploads/{$filename}'>here</a>.</p>";
}









?>

<h1>Upload File</h1>

<form method="POST" enctype="multipart/form-data" action="/file_upload.php">
    <p>
        <label for="file1">File to upload: </label>
        <input type="file" id="file1" name="file1">
    </p>
    <p>
        <input type="submit" value="Upload">
    </p>
</form>

</body>
</html>


