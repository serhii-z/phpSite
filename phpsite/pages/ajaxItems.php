<?php
    include_once('classes.php');
    $cid=$_GET['cid'];
    $pdo=Tools::connect();
    $list=$pdo->query("SELECT * FROM items WHERE catid=".$cid);
    while ($row=$list->fetch())
    {
        echo '<option value="'.$row['id'].'">'.$row['itemname'].'</option>';
    }
?>