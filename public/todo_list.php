<html>
<head>
	<title>My HTML todo list</title>
</head>
<body>
<h1>TODO list</h1>

<ul>
<?php

function open_file($x_filename)
{
    $list_array = [];
    if (is_readable($x_filename) && filesize($x_filename) > 0) 
    {
        $handle = fopen($x_filename, "r");
        $contents = fread($handle, filesize($x_filename));
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
// >>>>>>>CODE STARTS HERE<<<<<<<<<<<<<<<<
    $filename = "list.txt";
        //gets contents of file
    $todo_string = open_file($filename);
        //converts string to array
    $list_array = explode("\n", $todo_string);
        // merges opened items list with existing list
    //$items = array_merge($items,$list_array);

     
array_push($list_array, $_POST["NewItem"]);
foreach ($list_array as $value) 
    {
    echo "<li>$value</li>";
    }

$contents = implode("\n", $list_array);
save_file($filename, $contents)




?>
</ul>


<form method="POST">
    <p>
        <label for="NewItem">New Todo item</label>
        <input id="NewItem" name="NewItem" type="text">
    </p>
    
    <p>
        <input type="submit">
    </p>
</form>

</body>
</html>