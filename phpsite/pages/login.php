<?php
if (isset($_SESSION['ruser']))
{  
    if (isset($_GET['page'])) echo '?page='.$_GET['page'];
?>      <form action="index.php" class="navbar-form navbar-right"         method="post">
        <div class="form-group">
            <h5 style="color:white;">Hello, <?php echo $_SESSION['ruser'];?></h5>
        </div>
        <button type="submit" id="ex" name="ex" 
      	class="btn btn-sm btn-default">Logout</button>
</form>
<?php
    if (isset($_POST['ex']))
    {
        unset($_SESSION['ruser']);
        unset($_SESSION['radmin']);
        echo '<script>window.location.reload()</script>';
    }
}
else
{
    if (isset($_POST['press']))
    {
        if(Tools::login($_POST['login'],$_POST['pass']))
        {
            echo '<script>window.location.reload()</script>';
        }
    }
    else
    {
        //echo '<form action="index.php';
        if (isset($_GET['page'])) echo '?page='.$_GET['page'];
        ?>
        <form action="index.php" class="navbar-form navbar-right" method="post">
      	<div class="form-group">
            <input type="text" name="login" class="input-sm" placeholder="login">
            <input type="pass" name="pass" class="input-sm" placeholder="password">
      	</div>
      	<button type="submit" id="press" name="press" 
      	class="btn btn-sm btn-default">Login</button>
        </form>
        <?php
    }
}
?>
