<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Item Info</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/info.css">
</head>
<body>
<?php
include_once ("classes.php");
if(isset($_GET['name']))
{
    $id=$_GET['name'];

    echo '<main><h2 class="text-uppercase text-center">Images</h2>';
    echo '<div class="row"><div class="col-md-6 text-center">';
    
    $iteminf=new ItemInfo();
    $res=$iteminf->selectImages($id);
    
    echo '<span class="label label-info">Watch our pictures</span>';
    echo'<ul id="gallery">';
    while($row=$res->fetch(PDO::FETCH_NUM))
    {
        echo ' <li><img src="../'.$row[0].'"></li>';
    }
    echo ' </ul>';
    
    $info=$iteminf->selectInfo($id);
    
    echo '</div><div class="col-md-6"><p class="well">'.$info.'</p></div>';
    echo '</div></main>';
    echo '<div>';
}

 ?>

<script src="../js/jquery-3.1.0.min.js"></script>
<script src="../js/gallery.js"></script>
<script src="../js/info2.js"></script>
</body>
</html>