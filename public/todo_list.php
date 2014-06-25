<?

require_once("classes/filestore.php");
class InvalidInputException extends Exception { }


// >>>>>>>CODE STARTS HERE<<<<<<<<<<<<<<<<
$filename = "list.txt";
$todoObject = new Filestore($filename);
// 1. retrieves saved list txt file


//gets contents of file.  read() located in filestore.php
$list_array = $todoObject->read();

//2. checks if removing item from hyperlink via GET
if (isset($_GET['index']))
{
    //removes item from hyperlink
    unset($list_array[$_GET['index']]);
    $todoObject->write($list_array);
}

//3. checks if item was added via POST
try
{       //checks if POST is empty
    if (isset($_POST['NewItem']) && empty($_POST['NewItem']))
    {   
        throw new InvalidInputException('<script type="text/javascript">alert("Todo item cannot be empty"); </script>'); 
    }   //checks if POST has more than X characters
    if (isset($_POST['NewItem']) && strlen($_POST['NewItem']) > 5) 
    {
       throw new InvalidInputException('<script type="text/javascript">alert("Todo item cannot be longer than 5 characters"); </script>'); 
    }

    //3a. adds new item from submit
    if (!empty($_POST["NewItem"])) {
        array_push($list_array, $_POST["NewItem"]);
        $todoObject->write($list_array);
    }

} catch (InvalidInputException $e) 
{
    echo $e->getMessage();
}

// 4. checks if file was uploaded and uploads file
if (count($_FILES) > 0 && $_FILES['file1']['error'] == 0) 
{
    //4a. checks if uploaded file is text
    if ($_FILES['file1']['type'] == 'text/plain') {
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
        $ups = new Filestore($saved_filename);
        $array_to_add = $ups->read();
        $list_array = array_merge($list_array, $array_to_add);
        $todoObject->write($list_array);
    } else {
        echo "File type must be TXT";
        echo '<script type="text/javascript">alert("type must be txt"); </script>';
    }
}

?>

<html>
<head>
	<title>My HTML todo list</title>
    <link rel="stylesheet" href="/css/site.css">
</head>
<body>
<h1>TODO list</h1>

<ul>
    <!-- prints each item in array & adds remove link -->
<? if (!empty($list_array)) : ?>
    <?   foreach ($list_array as $key => $value): ?>    
        <li><?=htmlspecialchars(strip_tags($value)); ?> <a href = 'todo_list.php?action=remove&amp;index=<?=$key?>'>remove</a></li>
    <? endforeach; ?>
<? else: ?>
    <p>
        <?= "Your list is empty! Add some stuff!"; ?>
    </p>
<? endif; ?>


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