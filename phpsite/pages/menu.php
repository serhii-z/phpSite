<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
       <li <?php echo ($page==1)? "class='active'":"" ?>>
	      	<a href="index.php?page=1">Catalog</a>
       </li>
	     <li <?php echo ($page==2)? "class='active'":"" ?>>
		      <a href="index.php?page=2">Cart</a>
	     </li>
	     <li <?php echo ($page==3)? "class='active'":"" ?>>
		      <a href="index.php?page=3">Registration</a>
       </li>	
	     <li <?php echo ($page==4)? "class='active'":"" ?>>
		      <a href="index.php?page=4">Admin Forms</a>
	     </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        
<?php
    include_once("pages/login.php");
    if(isset($_SESSION['radmin']))
    {
        if($page==5)
            $c='active';
        else
            $c='';
        echo '<li class="'.$c.'">
        <a href="index.php?page=5">Private</a></li>';
    }
?>
      
      </ul>   	
    </div>
  </div>
</nav>