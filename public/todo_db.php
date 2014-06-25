<?

require_once("classes/filestore.php");
//gets offset for displaying list
function getOffset() {
    // $page = isset($_GET['page']) ? $_GET['page'] : 1;
    if (isset($_GET['page'])) 
    {
    $page = $_GET['page'];
    }  else 
        {
        $page = 1;
        }

    return ($page - 1) * 4;
}

//>>>>>>CODE STARTS HERE<<<<<<<<<<<<<<<<<<

//1.establishes database connection  DONE
$dbc = new PDO('mysql:host=127.0.0.1;dbname=codeup_pdo_test_db', 'genaro', 'letmein');
// exceptions if errors
$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//2. checks if item was added    DONE
try
{       //checks if POST is empty
    if (isset($_POST['NewItem']) && empty($_POST['NewItem']))
    {   
        throw new InvalidInputException('<script type="text/javascript">alert("Todo item cannot be empty"); </script>'); 
    }   //checks if POST has more than X characters
    if (isset($_POST['NewItem']) && strlen($_POST['NewItem']) > 10) 
    {
       throw new InvalidInputException('<script type="text/javascript">alert("Todo item cannot be longer than 10 characters"); </script>'); 
    }

//2a. adds new item from submit   DONE
    if (!empty($_POST["NewItem"])) {
           
            //establishes database connection
            $dbc = new PDO('mysql:host=127.0.0.1;dbname=codeup_pdo_test_db', 'genaro', 'letmein');
            // exceptions if errors
            $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dbc->prepare("INSERT INTO todo_db (item) VALUES (:item)");
                 
            //$stmt->bindValue(':id', $_POST['id'], PDO::PARAM_STR);
            $stmt->bindValue(':item', $_POST['NewItem'], PDO::PARAM_STR);
            
            //posts to database
            $stmt->execute();
    }

} catch (InvalidInputException $e) 
    {
        echo $e->getMessage();
    }

//2b. checks if removing item 

if (isset($_POST['remove']))
{
    $idToRemove = $_POST['remove'];
    $stmt = $dbc->prepare('DELETE FROM todo_db WHERE id = ?');
    $stmt->execute(array($idToRemove));

}


// 2c. checks if file was uploaded and uploads file
if (count($_FILES) > 0 && $_FILES['file1']['error'] == 0) 
{
    // checks if uploaded file is text
    if ($_FILES['file1']['type'] == 'text/plain') 
    {
        // if file is text type

        // Set the destination directory for uploads
        $upload_dir = '/vagrant/sites/todo.dev/public/uploads/';

        // Grab the filename from the uploaded file by using basename
        $filename_up = basename($_FILES['file1']['name']);

        // Create the saved filename using the file's original name and our upload directory
        $saved_filename = $upload_dir . $filename_up;

        // Move the file from the temp location to our uploads directory
        move_uploaded_file($_FILES['file1']['tmp_name'], $saved_filename);

        // merges uploaded file with existing file
        $ups = new Filestore($saved_filename);
        $array_to_add = $ups->read();

        // prepare insert statement
            // posts to database
            $stmt = $dbc->prepare("INSERT INTO todo_db (item) VALUES (:item)");
            // loop over array to add
            foreach ($array_to_add as $item) {
                // bind items from array to add
                $stmt->bindValue(':item', $item, PDO::PARAM_STR);
                // execute statement
                $stmt->execute();
            }   // end loop
        
    } else {
        echo "File type must be TXT";
        echo '<script type="text/javascript">alert("type must be txt"); </script>';
    }
}



//  5. retrieves saved todo list items from DB
    // queries from database for displaying
    $query = 'SELECT * FROM todo_db LIMIT 4 OFFSET ' . getOffset();

    $items = $dbc->query($query)->fetchAll(PDO::FETCH_ASSOC);

//counts # of records from database
$count = $dbc->query('SELECT count(*) FROM todo_db')->fetchColumn();
//gets rounded up number of pages
$numPages = ceil($count / 4);

//gets current page $
if (isset($_GET['page'])) 
    {
    $page = $_GET['page'];
    }  else 
        {
        $page = 1;
        }



$nextPage = $page + 1;

$prevPage = $page - 1;

$limit = 10;
$offset = (($limit * $page) - $limit);




?>

<html>
<head>
	<title>My HTML todo list</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="/css/site.css">
</head>
<body>
<h1>TODO DB list</h1>
<div>
<ul class="cowboys">
    <!-- prints each item in array & adds remove link -->
    <? if (!empty($items)) : ?>
        <?   foreach ($items as $item): ?> 
            <li><?=htmlspecialchars(strip_tags($item['item'])); ?> <button class="btn btn-danger btn-sm pull-right btn-remove" data-todo="<?= $item['id']; ?>">Remove</button></li>
        <? endforeach; ?>
    <? else: ?>
        <p>
            <?= "Your list is empty! Add some stuff!"; ?>
        </p>
    <? endif; ?>

</ul>

<!-- NEW TODO ITEM FORM -->
<form method="POST" action="todo_db.php">
    <p>
        <label for="NewItem">New Todo item</label>
        <input id="NewItem" name="NewItem" type="text">
    </p>
    
    <p>
        <input type="submit">
    </p>
</form>


<!-- UPLOAD FILE FORM -->
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

<!-- HIDDEN SUBMIT FORM -->
<form id="remove-form" action="todo_db.php" method="post">
    <input id="remove-id" type="hidden" name="remove" value="">
</form>

</div>

<!-- PAGINATION -->
<ul class="pager">
    

<?php if ($page > 1): ?>
    <li class="previous"><a href="?page=<?= $prevPage ?>">&larr; Older</a></li>
<?php endif; ?>


<?php if ($page < $numPages): ?>
  <li class="next"><a href="?page=<?= $nextPage ?>">&rarr; Next</a></li>
<?php endif; ?>

  
</ul>


<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script>

$('.btn-remove').click(function () {
    var todoId = $(this).data('todo');
    if (confirm('Are you sure you want to remove item ' + todoId + '?')) {
        $('#remove-id').val(todoId);
        $('#remove-form').submit();
    }
});

</script>


</body>
</html>