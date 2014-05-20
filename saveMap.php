<?php
	$file = $_POST['fName'];

	if (file_put_contents($file, $_POST['data'])) {
		echo $file . " saved";
	}else{
		echo "error." . getcwd();
	}
?>
