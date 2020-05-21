<?php
$adm=new Admin();

if(isset($_POST['addadmin']))
{  
    $adm->addAdmin();
}

if(isset($_POST['deladmin']))
{
    $adm->deleteAdmin();
}

echo '<form action="index.php?page=5" method="post"
    enctype="multipart/form-data" class="input-group">';

echo '<table class="table table-striped">';
echo '<tr>';
echo '<td>Customers: <select name="idadd" size="1">';
$adm->selectRole(2);
echo '</select>';
echo ' <input type="submit" name="addadmin" value="Add Admin" class="btn btn-sm btn-info"></td>';

echo '<td>Admins: <select name="iddel" size="1">';
$adm->selectRole(1);
echo '</select>';
echo ' <input type="submit" name="deladmin" value="Delete Admin" class="btn btn-sm btn-info"></td>';
echo '</tr>';
echo '</table>';

echo '</form></br></br>';

$adm->showAdmin();
?>