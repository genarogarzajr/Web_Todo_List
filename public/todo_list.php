<html>

<head>
	<title>My HTML todo list</title>
</head>
<body>
<?php
        print_r($_GET);
        echo PHP_EOL;
        echo "....POST...." . PHP_EOL;
        echo PHP_EOL;
        print_r($_POST);
    ?>


<h1>TODO list</h1>
<ul>
	<li>Study PHP</li>
	<li>Study HTML</li>
	<li>Study CSS</li>
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