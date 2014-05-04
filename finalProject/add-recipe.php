<?php 
	date_default_timezone_set('America/New_York');	
	require_once('db.php');


		$data = array(
	    'name' => array(
	            'value' => '',
	            'errors' => array()),
	    'category' => array(
	            'value' => '',
	            'errors' => array()),
	    'instructions' => array(
	            'value' => '',
	            'errors' => array()),
	    'comments' => array(
	            'value' => '',
	            'errors' => array()),
	);


$lastID = 0;


if($_SERVER['REQUEST_METHOD'] === 'POST')
{

	if(!$_POST['name']){
	    $data['name']['errors'] = "Recipe name is required.";
	}else{
			$data['name']['value'] = trim(htmlspecialchars($_POST['name']));
				if(strlen($data['name']['value']) == 0){
				$data['name']['errors'][] = 'This field is required.';
				}else{
					// data is valid
				}	
		}

    if(!isset($_POST['category'])){
        $data['category']['errors'][] = 'Unknown';
    }else{
        $data['category']['value'] = htmlspecialchars($_POST['category']);
		$statement = $db->prepare('select count(*) from category where id = :id');
		$statement->execute(array('id' => $data['category']['value']));
		$row = $statement->fetch();
		
		if($row[0] == 0){
			$data['category']['errors'][] = 'This field is required.';
		}else{
					// valid data
				}
		}
		
		

	if(!isset($_POST['instructions'])){
	    $data['instructions']['errors'] = "Instructions are required.";
	}else{
			$data['instructions']['value'] = trim(htmlspecialchars($_POST['instructions']));
				if(strlen($data['instructions']['value']) == 0){
				$data['instructions']['errors'][] = 'This field is required.';
				}else{
					// data is valid
				}	
		}

	if(!isset($_POST['comments'])){
	    $data['comments']['value'] = NULL;
	}else{
			$data['comments']['value'] = trim(htmlspecialchars($_POST['comments']));		
		}

	$total_errors = 0;
		foreach($data as $field){
			$total_errors += count($field['errors']);
		}

	if($total_errors == 0){
		$insert_stat = <<< EOQ
INSERT INTO recipe (name, category_id, instructions, comments) VALUES(:name, :category, :instructions, :comments)
EOQ;

	try{
		$statement = $db->prepare($insert_stat);
		$statement->execute(
			array('name' => $data['name']['value'],
					'category' => $data['category']['value'],
					'instructions' => $data['instructions']['value'],
					'comments' => $data['comments']['value'],)
				);

		$lastID = $db->lastInsertId();
		}catch(Exception $e){
		die("Could not execute query: {$e->getMessage()}");
		}
	}
}


// $lastID = $db->lastInsertId();

$ingred_page = array('r'=>$lastID);
$link = http_build_query($ingred_page);

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
        <h1>Add New Recipe</h1>
		<p>Add a new recipe to the database. Add ingredients to the last recipe by clicking Ingredients.</p>
		<p>NOTE: Fields marked with <span class="required"></span> are required for form processing.</p>
		<hr />
		</div>
		<form method="post" action="">
			<dl class="form">
				<dt class="required">Recipe Name</dt>
				<dd>
					<?php
//					<input type="text" name="name" />
echo '<input type="text" name="name" value="' . htmlspecialchars($data['name']['value']) . '"/>';
					?>
					<span class="description">Enter the name of your recipe. Must be unique.</span>
				</dd>
				<dt class="required">Recipe Category</dt>
				<dd>
					<select name="category">
						<?php $statement = $db->query('SELECT * FROM category ORDER BY category'); 
						foreach($statement as $row){ 
							echo '<option value="' . 
							htmlspecialchars($row['id']) . 
							'">' . 
							htmlspecialchars($row['category']) . 
							"</option>";
							} ?>
                    </select>
								<span class="description">Used to organize your recipes by meal time</span>
				
				</dd>
				<dt class="required">Recipe Instructions</dt>
				<dd>
					<?php
//					<textarea name="instructions" rows="7" cols="80"></textarea>
echo '<textarea name="instructions" rows="7" cols="80">' . htmlspecialchars($data['instructions']['value']) . '</textarea>';
					?>


					<span class="description">Enter the instructions here.</span>
				</dd>			
				<dt>Additional Comments</dt>
				<dd>
					<textarea name="comments" rows="4" cols="40"></textarea>
					<span class="description">Enter any additional notes about your 
					recipe that you might have</span>
				</dd>
				<dt>&nbsp;</dt>
				<dd>
					<input type="submit" value="Add" />
					<?php
					if($lastID !== 0){
					echo '<p><a href="add-ingred.php?' . $link . '">Ingredients</a><br />';
					echo '<a href="index.php">Return to main menu</a></p>';
					}else{
					echo '<br /><a href="index.php">Return to main menu</a></p>';
					}
					?>
				</dd>				
			</dl>
		</form>
    </body>
</html>

