<?php 
date_default_timezone_set('America/New_York');	
// require_once('db.php');

$data = array('name' => 
			array('value' => '', 'errors' => 
				array()),);

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(!isset($_POST['ingredient'])){
	    $data['ingredient']['value'] = NULL;
			}else{
				$data['ingredient']['value'] = trim(htmlspecialchars($_POST['ingredient']));		
				}

try{
$food = $db->prepare(
"INSERT INTO ingredient(ingredient) VALUES(:ingredient)"
);

// 	convert to all lowercase.
//	move to uppercase first latter
//	update ONLY IF unique.


$food->execute(array('ingredient' => $data['ingredient']['value']));
}
catch(Exception $e){
echo $e;
}

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
			.required:after { content: " * "; color: red; font-weight: bold;}
			dl { margin-left: 100px; }
			dl dt { margin: 3ex 0px 1ex 0px; }
			dl dd { margin: 1ex 0px 3ex 1em; }
			dl dd span.description { display:block; font-style: italic; font-size: 10pt; margin: 1em 1em 1em 1em;}
			.center { text-align: center }
			.list { margin: 1em 1em 1em 100px; }        
        </style>
    </head>

    <body>
		<div class="center">
        <h1>Add New Ingredient to Ingredient List</h1>
		<p>Add an ingredient to the pantry.</p>
		<p>NOTE: Fields marked with <span class="required"></span> are required for form processing.</p>
		<hr />
		</div>

		<div class="list">
		<form method="post" action="">
		<!-- <form method="post" action="add-table.php"> -->
			<dl class="form">
				<dt class="required">Ingredient</dt>
				<dd>
					<input type="text" name="ingredient"/>
					<span class="description">Add an ingredient</span>
				</dd>
				
				<dd>
					<input type="submit" value="Add" />
				</dd>

				<dd>
					<select name="category">
						<?php $statement = $db->query('SELECT * FROM ingredient ORDER BY ingredient'); 
						foreach($statement as $row){ 
							echo '<option value="' . 
							htmlspecialchars($row['id']) . 
							'">' . 
							htmlspecialchars($row['ingredient']) . 
							"</option>";
							} ?>
                    </select>
					<span class="description">See what ingredients already exist.</span>
</dd>				
		<p><a href="index.php">Return to Main Menu</a></p>
			</dl>
		</form>
</div>

    </body>
</html>

