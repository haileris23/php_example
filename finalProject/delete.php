<?php 

require_once('db.php');


if(!isset($_GET['r'])){
	header('Location: index.php');
	exit();
}

else{
	$recipe_id = $_GET['r'];
}

$object_id = $db->prepare('SELECT id FROM recipe WHERE id = :selected_id');	
$object_id->execute(array('selected_id' => $recipe_id));
$check_for_id = $object_id->rowCount();


	if($check_for_id !== 0){
		$delete_object = $db->prepare('DELETE FROM recipe WHERE id = :selected_id');	
		$delete_object->execute(array('selected_id' => $recipe_id));

		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	else{
		header('Location: agenda.php');
		exit();
	}

?>
