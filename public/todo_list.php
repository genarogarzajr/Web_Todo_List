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
// >>>>>>>CODE STARTS HERE<<<<<<<<<<<<<<<<
    $filename = "list.txt";
        //gets contents of file
    $todo_string = open_file($filename);
        //converts file contents string to array
    $list_array = explode("\n", $todo_string);


 if (isset($_GET['index']))
     {
            //removes item from hyperlink
        unset($list_array[$_GET['index']]);
     }

 if (!empty($_POST))
    
     {
            //adds new item from submit
        array_push($list_array, $_POST["NewItem"]);
     }

    //converts array to string for saving to file   
$contents = implode("\n", $list_array);
save_file($filename, $contents);

   //prints each item in array & adds remove link
foreach ($list_array as $key => $value) 
    {
    echo "<li>$value <a href = 'todo_list.php?action=remove&index=$key'>remove</a></li>";
    }
    var_dump($list_array);

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

</body>
</html>