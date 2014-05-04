<?php 
date_default_timezone_set('America/New_York');	

require_once('db.php');

if (!isset($_GET['page_num'])) {
   $page_num = 0;
}
else {
   $page_num = $_GET['page_num'];
}

if (!isset($_GET['s'])){
$sort = 0;
}
else{
$sort = $_GET['s'];
}

if($sort > 5){
$sort = 0;
}

$num_of_rows = 3;
$counts = $db->query('SELECT * FROM recipe');
$row_count = $counts->rowCount();
$last_page = ceil($row_count / $num_of_rows);

if ($page_num > $last_page) {
   $page_num = $last_page;
} 
if ($page_num < 1) {
   $page_num = 1;
} 

$page_num = (int)$page_num;
$page_total = ( ($page_num - 1) * $num_of_rows);

$prev_page = ($page_num - 1);
$next_page = ($page_num + 1);

switch ($sort) {
    case 0:
		$sort_val = 'ORDER BY name ASC';
		break;
    case 1:
		$sort_val = 'ORDER BY name DESC';
        break;
    case 2:
		$sort_val = 'ORDER BY category ASC';
        break;
    case 3:
		$sort_val = 'ORDER BY category DESC';
        break;
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

		<!--
			Table format taken from http://einstein.etsu.edu/~adam/hw8/agenda.php
		-->
        <div class="center">
            <?php echo "<h1>All Recipes</h1>" ?>
		<p>Click recipe name to go to its specific page.</p>
			<hr />
		</div>
        <table>
            <thead>
                <tr>
					<?php 		
echo '<th>Name<a href="' . $_SERVER['PHP_SELF'] . '?s=0&amp;page_num=' . htmlspecialchars($page_num) 
. '">^</a><a href="' . $_SERVER['PHP_SELF'] . '?s=1&amp;page_num=' . htmlspecialchars($page_num) . '">v</a></th>';
echo '<th>Category<a href="' . $_SERVER['PHP_SELF'] . '?s=2&amp;page_num=' . htmlspecialchars($page_num) 
. '">^</a><a href="' . $_SERVER['PHP_SELF'] . '?s=3&amp;page_num=' . htmlspecialchars($page_num) . '">v</a></th>';
echo '<th>Comments</th>';
echo '<th>Edit</th>';
echo '<th>Delete</th>';
				?>
                </tr>
            </thead>
            <tfoot>
<?php 				
if($page_num > 1){
echo '<tr><td><a href="' . $_SERVER['PHP_SELF'] . '?s=' . htmlspecialchars($sort) 
. '&amp;page_num=' . 1 . '">  &lt;-- First</a></td>';
echo '<td><a href="' . $_SERVER['PHP_SELF'] . '?s=' . htmlspecialchars($sort) 
. '&amp;page_num=' . htmlspecialchars($prev_page) . '">  &lt;- Previous</a></td>';
}
else{
echo '<tr><td>&lt;-- First</td>';
echo '<td>&lt;- Previous</td>';

}
echo '<td> </td>';

if($page_num < $last_page){
echo '<td><a href="' . $_SERVER['PHP_SELF'] . '?s=' . htmlspecialchars($sort) 
. '&amp;page_num=' . htmlspecialchars($next_page) . '">Next -&gt;  </a></td>';
echo '<td><a href="' . $_SERVER['PHP_SELF'] . '?s=' . htmlspecialchars($sort) 
. '&amp;page_num=' . htmlspecialchars($last_page) . '">Last --&gt; </a></td></tr>';
}
else{
echo '<td>Next -&gt;  </td>';
echo '<td>Last --&gt; </td></tr>';

}
?>
			</tfoot>
            <tbody>
<?php

$q = <<< EOQ
SELECT recipe.id, recipe.name, category.category AS category, recipe.comments 
FROM recipe JOIN category ON recipe.category_id=category.id 
$sort_val LIMIT $page_total, $num_of_rows 
EOQ;


$recipes = $db->query($q);

		foreach($recipes as $recipe){
		
		$view_url = array('r'=>$recipe['id']);
		$link = http_build_query($view_url);
		
		echo '<tr><td><a href="recipe.php?' . $link . '">' . htmlspecialchars($recipe['name']) . '</a> </td>';
		echo "<td>" . htmlspecialchars($recipe['category']) . "</td>";
		echo "<td>" . htmlspecialchars($recipe['comments']) . "</td>";
		echo '<td><a href="edit.php?' . $link . '">Edit</a> </td>';
		echo '<td><a href="delete.php?' . $link . '">Delete</a> </td></tr>';
		}
?>
            </tbody>
        </table>
<div class="center">
<p><a href="add-recipe.php">Add a New Recipe</a></p>
<p><a href="add-table.php">Add a New Ingredient to the Pantry</a></p>
</div>
    </body>
</html>
