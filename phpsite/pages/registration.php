<h3>Registration Form</h3>
<?php
    if(!isset($_POST['regbtn']))
    {
?>
<form action="index.php?page=3" method="post" enctype="multipart/form-data">   
<div class="col-sm-4 col-md-4 col-lg-4">
    <div>
    <label for="login">Login:</label>
    <input type="text" class="form-control" name="login">
    </div>
    <div>
        <label for="pass1">Password:</label>
        <input type="password" class="form-control" name="pass1">
    </div>
    <div>
        <label for="pass2">Confirm Password:</label>
        <input type="password" class="form-control" name="pass2">
    </div>
    <div>
        <label for="imagepath">Select image:</label>
        <input type="file" class="form-control" name="imagepath">
    </div>
    <button type="submit" class="btn btn-primary" name="regbtn">Register</button>
</div>      
</form>           
<?php
    }
    else
    {
        //upload processing
        if(is_uploaded_file($_FILES['imagepath']['tmp_name']))
        {
            $path="images/".$_FILES['imagepath']['name'];
            move_uploaded_file($_FILES['imagepath']['tmp_name'], $path);
        }
        //customer registration
        if(Tools::register($_POST['login'],$_POST['pass1'],$path))
        {
            echo "<h3/><span style='color:green;'>
            New User Added!</span><h3/>";
        }
    }
?>