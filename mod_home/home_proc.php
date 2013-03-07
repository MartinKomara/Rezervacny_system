<?php
	
	
	 move_uploaded_file($_FILES["file"]["tmp_name"],"images/" . $_FILES["file"]["name"]);
	$tmp['meno'] = $_POST['meno'];
	$tmp['priezvisko'] = $_POST['priezvisko'];
	$tmp['skills'] = $_POST['skills'];
	$tmp['hobbies'] = $_POST['hobbies'];
	$tmp['email'] = $_POST['email'];
	$tmp['fotka'] = $_FILES["file"]["name"];
	$db->makeInsert('kontakty',$tmp);
	$_SESSION['zakaznik'] = 1;
	header ("Location:/?id=home&cmd=home");
	exit;


?>