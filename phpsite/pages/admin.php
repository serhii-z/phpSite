<?php
$category=new Category();
if (!isset($_SESSION['radmin']))
{
    echo "<h3><span style='color:red;'>For Administrators Only!
        </span><h3/>";
    exit();
}
?>
<table class="table table-striped">
   <tr>
   <td>
   <form action="index.php?page=4" method="post" enctype="multipart/form-data">
   <div class="col-sm-7 col-md-7 col-lg-7">
        <h3>Add Product</h3>
         <div> 
             <label for="catid">Category:</label>
         <select class="" name="catid">
         <?php
         $category->selectCategories();
         ?> 
         </select>
         </div>	
             <div>
             <label for="name">Name:</label>
             <input type="text" class="form-control" name="name">
         </div>
         <div>
             <label for="pricein">Incoming Price and Sale Price:</label>
             <div>
                 <input type="number" class="form-control" name="pricein">
                 <input type="number" class="form-control" name="pricesale">
             </div>
         </div>
         <div>
             <label for="info">Description:</label>
             <div><textarea class="form-control" name="info"></textarea></div>
         </div>
         <div>
             <label for="imagepath">Select image:</label>
             <input type="file" class="form-control" name="imagepath">
         </div>
         <div>
         <br/>
         <button type="submit" class="btn btn-primary" name="regbtn">Register</button>
         </div>
    </div>
    </form>
    </td>
    <td>
        <form action="index.php?page=4" method="post" enctype="multipart/form-data">
            <div class="col-sm-7 col-md-7 col-lg-7">
                <h3>Add Photo</h3>
                <div>
                <label for="catid">Category:</label>
                <select  name="catid" id="catid" onchange="showItems(this.value)">>>
                <?php
                $category->selectCategories();
                ?>
                </select> 
                </div>
                <div>
                <label for="itemid">Product:</label>
                <select  name='itemid' id="itemlist">>  
                </select> 
                </div>
                <div>
                <label for="impath">Select image:</label>
                <input type="file" class="form-control" name="impath">
                </div>
            <div>
            <br/>
            <button type="submit" class="btn btn-primary" name="addphotobtn">Add</button>
            </div>
            </div>
        </form>
        </td>
        </tr>
        <tr>
            <td>
               <form action="index.php?page=4" method="post" enctype="multipart/form-data">
               <div class="col-sm-7 col-md-7 col-lg-7">
                  <h3>Add/Delete Category</h3>   
                   <input type="text" class="form-control" name="catname">
                   <br/>
                  <button type="submit" class="btn btn-primary" name="addcatbtn">Add</button>  
                  <button type="submit" class="btn btn-primary" name="delcatbtn">Delete</button>
               </div>
                </form>
            </td>
        </tr>    
</table>   
<?php
if(!isset($_POST['regbtn']))
{
    if(is_uploaded_file($_FILES['imagepath']['tmp_name'])) 
    {
        $path="images/".$_FILES['imagepath']['name'];
        move_uploaded_file($_FILES['imagepath']['tmp_name'], $path);
    }
    $catid=$_POST['catid'];
    $pricein=$_POST['pricein'];
    $pricesale=$_POST['pricesale'];
    $name=trim(htmlspecialchars($_POST['name']));	
    $info=trim(htmlspecialchars($_POST['info']));
    $item=new Item($name,$catid,$pricein,$pricesale,$info,$path);
    $item->intoDb();    
}       
        
if(isset($_POST['addphotobtn']))
{
    if(isset($_POST['itemid']))
    {
    if(is_uploaded_file($_FILES['impath']['tmp_name'])) 
    {
        $path="images/".$_FILES['impath']['name'];
        move_uploaded_file($_FILES['impath']['tmp_name'], $path);
    }
    else
    {
        echo '<script type="text/javascript">alert("Select image")</script>';
    }
	$itemid=$_POST['itemid'];
	$im=new Image($itemid,$path);
	$im->intoDb();
    }
    else
    {
        echo '<script type="text/javascript">alert("Enter product")</script>';
    }
}

if(isset($_POST['addcatbtn']))
{
    $catname=$_POST['catname'];
    $category->addCategory($catname);
}

if(isset($_POST['delcatbtn']))
{
    $catname=$_POST['catname'];
    $category->deleteCategory($catname);  
}
?>

<script>
function showItems(catid)
{
    if(catid=="0")
    {
        document.getElementById('itemlist').innerHTML="";
    }
    //creating AJAX object
    if(window.XMLHttpRequest)
    {
        ao=new XMLHttpRequest();
    }
    else{
        ao=new ActiveXObject('Microsoft.XMLHTTP');
    }
    //creating callback function accepting result
    ao.onreadystatechange=function()
    {
        if(ao.readyState==4 && ao.status==200)
        {
            document.getElementById('itemlist').innerHTML=ao.responseText;
        }
    }
    //creating and sending AJAX request
    ao.open('GET',"pages/ajaxItems.php?cid="+catid,true);
    ao.send(null);
}
</script>


