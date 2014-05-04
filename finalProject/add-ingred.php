<?php 
date_default_timezone_set('America/New_York');	
require_once('db.php');

function render_html_errors($node){
    if(count($node['errors']) > 0){
        $output = '<ul class="errors">';
        
        foreach($node['errors'] as $error){
            $output .= '<li>';
            $output .= htmlspecialchars($error);
            $output .= '</li>';
        }
        
        $output .= '</ul>';
    }else{
        $output = '<!-- no errors -->';
    }
    
    return $output;
}

		$data = array(
	    'r_id' => array(
	            'value' => '',
	            'errors' => array()),
	    'ingredient-id' => array(
	            'value' => '',
	            'errors' => array()),
	    'quant' => array(
	            'value' => '',
	            'errors' => array()),
	);

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if(!isset($_GET['r'])){
        $data['r_id']['errors'][] = 'Unknown';
    }else{
		$data['r_id']['value'] = $_GET['r'];		
		}

		
    if(!isset($_POST['ingredient-id'])){
        $data['ingredient-id']['errors'][] = 'Unknown';
    }else{
        $data['ingredient-id']['value'] = htmlspecialchars($_POST['ingredient-id']);
		$ingred_count = $db->prepare('select count(*) from ingredient where id = :id');
		$ingred_count->execute(array('id' => $data['ingredient-id']['value']));
		$number = $ingred_count->fetch();
		
		if($number[0] == 0){
			$data['ingredient-id']['errors'][] = 'This field is required.';
		}else{
					// valid data
				}
		}
		

$rec_num = array('r'=>$_GET['r']);
$link = http_build_query($rec_num);

	if(!isset($_POST['quant'])){
	    $data['quant']['value'] = NULL;
		    header('Location: add-ingred.php' . $link . '');
	}else{
			$data['quant']['value'] = trim(htmlspecialchars($_POST['quant']));
				if(strlen($data['quant']['value']) == 0){
				$data['quant']['errors'][] = 'This field is required.';
				}else{
					// data is valid
				}	
		}

$i = <<< EOQ
INSERT INTO link(recipe_id,ingredient_id,quantity) VALUES(:rec,:ing,:quant)
EOQ;

   $total_errors = 0;
    foreach($data as $field){
        $total_errors += count($field['errors']);
    }

    if($total_errors == 0){ 
				try{

					$final = $db->prepare($i); 
					$final->execute(array('rec' => $data['r_id']['value'], 
						'ing' => $data['ingredient-id']['value'], 
						'quant' => $data['quant']['value']));
					}catch(Exception $e){
						echo $e;
		}
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
        <h1>Add New Ingredient</h1>
		<p>Add an ingredient to a recipe.</p>
		<hr />
		</div>
	<?php echo '<form method="post" action="">' ?>
			<dl class="form">
				<dt>Ingredient List</dt>
				<dd>
					<select name="ingredient-id">
						<?php 
						
$q = <<<EOQ
SELECT ingredient.id AS id, ingredient.ingredient AS ingredient, link.recipe_id, link.quantity  
FROM ingredient LEFT OUTER JOIN link ON ingredient.id=link.ingredient_id 
WHERE link.recipe_id!= :id_url OR link.recipe_id IS NULL ORDER BY ingredient;
EOQ;

						$statement = $db->prepare($q); 
						$statement->execute(array('id_url' => $_GET['r']));

						foreach($statement as $row){ 
							echo '<option value="' . 
							htmlspecialchars($row['id']) . 
							'">' . 
							htmlspecialchars($row['ingredient']) . 
							"</option>";
							} 
							?>
                    </select>
					<span class="description">Add an ingredient</span>
				</dd>				

				<dt class="list">Quantity</dt>
				<dd>
					<input type="text" name="quant" />
					<span class="description">The amount of the ingredient needed.</span>
				</dd>

				<dd>
					<input type="submit" value="Add" />
				</dd>
			</dl>
		</form>

<?php
echo '<div class="list">';
echo '<p>Current list of ingredients</p>';
echo '<ul>';

$list = <<<EOQ
SELECT ingredient.id AS id, ingredient.ingredient AS ingredient, link.recipe_id, 
link.quantity AS quant FROM ingredient JOIN link ON ingredient.id=link.ingredient_id 
WHERE link.recipe_id= :id_url ORDER BY ingredient
EOQ;


$items = $db->prepare($list); 
$items->execute(array('id_url' => $_GET['r']));

foreach($items as $item){ 
echo '<li>' . $item['quant'] . ' '  . $item['ingredient'] . '</li>';
} 

echo '</ul>';
?>

	<p><a href="index.php">Return to Index</a></p>
	</div>
    </body>
</html>

