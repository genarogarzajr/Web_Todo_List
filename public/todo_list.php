<?

function open_file($x_filename)
{
    $list_array = [];
    if (is_readable($x_filename) && filesize($x_filename) > 0) 
    {
        $handle = fopen($x_filename, "r");
        $contents = (fread($handle, filesize($x_filename)));
        $contents = trim($contents);
        fclose($handle);
        return $contents;
    }
    $contents = implode("", $list_array);
    return $contents;   
}
//--------------------------------------
function save_file($filename, $contents)
{
    $handle = fopen($filename, "w");
    fwrite($handle, $contents);
    fclose($handle);
}
//--------------------------------------

// >>>>>>>CODE STARTS HERE<<<<<<<<<<<<<<<<


//1. retrieves saved list txt file
$filename = "list.txt";
define('FILENAME', "list.txt");
//gets contents of file
$todo_string = open_file(FILENAME);
//converts file contents string to array
$list_array = explode("\n", $todo_string);

//2. checks if removing item from hyperlink via GET
if (isset($_GET['index']))
{
    //removes item from hyperlink
    unset($list_array[$_GET['index']]);
}

//3. checks if item was added via POST
if (!empty($_POST))
{
        //3a. adds new item from submit
    array_push($list_array, $_POST["NewItem"]);
}

// 4. checks if file was uploaded and uploads file
if (count($_FILES) > 0 && $_FILES['file1']['error'] == 0) 
{
    
        //4a. checks if uploaded file is text
    if ($_FILES['file1']['type'] == 'text/plain') 
        {
               //4b. if file is text type

            // Set the destination directory for uploads
            $upload_dir = '/vagrant/sites/todo.dev/public/uploads/';

            // Grab the filename from the uploaded file by using basename
            $filename_up = basename($_FILES['file1']['name']);

            // Create the saved filename using the file's original name and our upload directory
            $saved_filename = $upload_dir . $filename_up;

            // Move the file from the temp location to our uploads directory
            move_uploaded_file($_FILES['file1']['tmp_name'], $saved_filename);

            //4c. merges uploaded file with existing file
            $string_to_add = open_file($saved_filename);
            $array_to_add = explode("\n", $string_to_add);
            $list_array = array_merge($list_array, $array_to_add);
           
        } else 
            {
                echo "File type must be TXT";
                echo '<script type="text/javascript">alert("type must be txt"); </script>';
            }  
            
              
}

//6. converts array to string for saving to file   
$contents = implode("\n", $list_array);
save_file($filename, $contents);

?>
<html>
<head>
	<title>My HTML todo list</title>
</head>
<body>
<h1>TODO list</h1>

<ul>

<?php
    //prints each item in array & adds remove link
    foreach ($list_array as $key => $value) 
    {
        echo "<li>$value <a href = 'todo_list.php?action=remove&index=$key'>remove</a></li>";
    }
?>

</ul>


<form method="POST" action="todo_list.php">
    <p>
        <label for="NewItem">New Todo item</label>
        <input id="NewItem" name="NewItem" type="text">
    </p>
    
    <p>
        <input type="submit">
    </p>
</form>

<h2>Upload file</h2>

<form method="POST" enctype="multipart/form-data">
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