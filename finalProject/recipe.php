<?php 
	date_default_timezone_set('America/New_York');	
	require_once('db.php');

	if(!isset($_GET['r'])){
		header('Location: index.php');
		exit();
	}
?>	
	

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<!--
	Name:  Carl and Landon
	Course:  CSCI-2910-001
	Assignment: Final Project
	Due Date: 11/29/2012
	Purpose: Recipe Database.
	Form format taken from http://einstein.etsu.edu/~adam/hw5/form.html
-->


        <title>Final Project</title>
        <style type="text/css">
            body { font-family: Consolas, Courier New, monospace; }
            table { margin-left: auto; margin-right: auto; border-collapse: collapse; text-align: left; }
            th { border-bottom: 2px solid black }
            tr.event td { border-top: 1px solid black }
            td,th { padding: 1em; }
            table tbody td a {display: block; text-decoration: none; color: black;}
            table tfoot td { border-top: 2px solid black; text-align: center; }

			dl { margin-left: 100px; }
			dl dt { margin: 3ex 0px 1ex 0px; }
			dl dd { margin: 1ex 0px 3ex 1em; }
			dl dd span.description { display:block; font-style: italic; font-size: 10pt; margin: 1em 1em 1em 1em;}
			.center { text-align: center }
			.list { margin: 1em 1em 1em 100px; }        
        </style>
    </head>

    <body>
<?php

$rec_query = <<<EOQ
SELECT recipe.id AS rec, recipe.name AS name, category.category AS cat, 
recipe.instructions AS instructions, recipe.comments AS comments FROM recipe 
JOIN category ON recipe.category_id=category.id 
WHERE recipe.id= :recipe_id
EOQ;

$recipes = $db->prepare($rec_query);
$recipes->execute(array('recipe_id' => $_GET['r']));
$row = $recipes->fetch();
echo '<div class="center"><h1>' . htmlspecialchars($row['name']) . '</h1>';
echo '<p>Ingredients and instructions for this ' . htmlspecialchars($row['cat']) . '</p><hr /></div>';

$q = <<<EOQ
SELECT ingredient.id, ingredient.ingredient AS ing, link.recipe_id FROM ingredient 
JOIN link ON ingredient.id=link.ingredient_id WHERE link.recipe_id= :recipe_id
EOQ;

	echo '<div class= "list"><p>Ingredients:</p>';
	echo '<ul>';
	
	
	$foods = $db->prepare($q);
	$foods->execute(array('recipe_id' => $_GET['r']));
	foreach($foods as $food){
	echo '<li>' . htmlspecialchars($food['ing']) . '</li>';
	}

	echo '</ul>';

echo '<p>Instructions: <br />' . htmlspecialchars($row['instructions']) . '</p>';
echo '<p>Comments: <br />' . htmlspecialchars($row['comments']) . '</p>';

		$view_url = array('r'=>$_GET['r']);
		$link = http_build_query($view_url);

echo '<p><a href="add-ingred.php?' . $link . '">Add New Ingredients</a></p>';

?>

<p><a href="index.php">Return to List</a></p>
</div>
</body>
</html>

