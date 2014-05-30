<html>
<head>
    <title>File Upload</title>
</head>
<body>

<?php

var_dump($_FILES);

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