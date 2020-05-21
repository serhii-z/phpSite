<?php @session_start();
if(isset($_GET['page'])){
	$page=$_GET['page'];
}
include_once ("pages/classes.php"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Online Shopping</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<!-- <link rel="stylesheet" href="css/style.css">  -->
	<link rel="stylesheet/less" href="css/style.less">
</head>
<body>
    <div class="container">
    <div class="row">
    <header class="col-sm-12 col-md-12 col-lg-12">
<?php 
include_once('pages/menu.php');
?>
    </header>
    </div>
<main>
<?php
if(isset($_GET['page'])){
		if($page==1) include_once("pages/catalog.php");
		if($page==2) include_once("pages/cart.php");
		if($page==3) include_once("pages/registration.php");
		if($page==4) include_once("pages/admin.php");
        if($page==5 && isset($_SESSION['radmin'])) 
        include_once("pages/private.php"); 
	}
?>

</main>
<footer style="clear:both;">
	<p class="text-center">Step Academy &copy;</p>
</footer>
</div>
<script src="js/jquery-2.0.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/less.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>