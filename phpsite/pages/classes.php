<?php
class Tools
{
    static function connect(
    $host="phpsite",
    $user="root",
    $pass="root",
    $dbname="shop")
    {
        $cs='mysql:host='.$host.';dbname='.$dbname.';charset=utf8;';
        $options=array(
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::
        FETCH_ASSOC,PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8');
        try 
        {
            $pdo=new PDO($cs,$user,$pass,$options);
            return $pdo;
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
            return false;
        }
    }
    
    static function register($name,$pass,$imagepath)
    {
        $name=trim($name);
        $pass=trim($pass);
        $imagepath =trim($imagepath);
        if ($name=="" || $pass=="")
        {
            echo "<h3/><span style='color:red;'>
                Fill All Required Fields!</span><h3/>";
            return false;
        }
        if (strlen($name)<3 || strlen($name)>30 ||
        strlen($pass)<3 || strlen($pass)>30)
        {
            echo "<h3/><span style='color:red;'>
                Values Length Must Be Between 3 And 30!</span><h3/>";
            return false;
        }
        Tools::connect();
        $customer=new Customer($name,$pass,$imagepath);
        $err=$customer->intoDb();
        if ($err)
        {
            if($err==1062)
                echo "<h3/><span style='color:red;'>
                    This Login Is Already Taken!</span><h3/>";
            else
                echo "<h3/><span style='color:red;'>
                    Error code:".$err."!</span><h3/>";
            return false;
        }
        return true;
    }
    
    static function login($name,$pass)
    {
        $name=trim(htmlspecialchars($name));
        $pass=trim(htmlspecialchars($pass));
        if ($name=="" || $pass=="")
        {
            echo "<h3/><span style='color:red;'>
                Fill All Required Fields!</span><h3/>";
            return false;
        }
        if (strlen($name)<3 || strlen($name)>30 ||
        strlen($pass)<3 || strlen($pass)>30) 
        {
            echo "<h3/><span style='color:red;'>
                Value Length Must Be Between 3 And 30!</span><h3/>";
            return false;
        }
        $roleres=Customer::fromDb($name, $pass);      	
        $_SESSION['ruser']=$name;
        if($roleres==1){
            $_SESSION['radmin']=$name;       
        }
        else
        {
            echo "<h3/><span style='color:red;'>No Such User!</span><h3/>";
            return false;
        }
        return true;
    }

}

class Customer
{
    protected $id; //user id
    protected $login;
    protected $pass;
    public $roleid;
    protected $discount; //customer's personal discount
    protected $total; //total ammount of purchases
    protected $imagepath; //path to the image
    
    public function __construct($login,$pass,$imagepath,$id=0)
    {
        $this->id=$id;
        $this->login=$login;
        $this->pass=$pass;
        $this->roleid=2;
        $this->discount=0;
        $this->total=0;
        $this->imagepath=$imagepath;      
    }
    public function intoDb()
    {
        try
        {
            $pdo=Tools::connect();
            $ps=$pdo->prepare("INSERT INTO Customers
                (login,pass,roleid,discount,total,imagepath)          
                VALUES (:login,:pass,:roleid,:discount,:total,:imagepath)");
            $ar=array("login"=>$this->login, "pass"=>$this->pass, 
                      "roleid"=>$this->roleid, "discount"=>$this->discount, 
                      "total"=>$this->total, "imagepath"=>$this->imagepath);

            $ps->execute($ar);
        }
        catch(PDOException $e)
        {
            $err=$e->getMessage();
            if(substr($err,0,strrpos($err,":"))=='SQLSTATE[23000]:Integrity constraint violation')
                return 1062;
            else
                return $e->getMessage();
        }
    }
    
    static function fromDb($login, $pass)
    {
        try
        {
            $pdo=Tools::connect();
            $ps=$pdo->prepare('SELECT roleid FROM customers WHERE login="'.$login.'" AND pass="'.$pass.'"');
            $res=$ps->execute();
            return $res;
        }
        catch(PDOException $e) 
        {
            echo $e->getMessage();
            return false;
        }      
    }
}

class Admin{
      
    function addAdmin(){
        $pdo=Tools::connect();
        $id=$_POST['idadd'];
        $ps=$pdo->prepare('UPDATE customers SET  roleid=1 WHERE id='.$id);
        $ps->execute([$id]);
    }
    
    function deleteAdmin(){
        $pdo=Tools::connect();
        $id=$_POST['iddel'];
        $ps=$pdo->prepare('UPDATE customers SET  roleid=2 WHERE id='.$id);
        $ps->execute([$id]);
    }
    
    function selectRole($roleid){
        $pdo=Tools::connect();
        $sel=$pdo->query('SELECT * FROM customers WHERE roleid='.$roleid);
        while ($row=$sel->fetch(PDO::FETCH_ASSOC))
        {
            echo '<option value="'.$row['id'].'">'.$row['login'].'</option>';
        }
    }
    
    function showAdmin(){
        $pdo=Tools::connect(); 
        $sel=$pdo->query('SELECT * FROM customers WHERE roleid=1');
        echo '<table class="table table-striped">';
        while($row=$sel->fetch(PDO::FETCH_ASSOC))
        {
            echo '<tr>';
            echo '<td>'.$row['id'].'</td>';
            echo '<td>'.$row['login'].'</td>';
            $img=$row['imagepath'];
            echo '<td><img height="100px" src="'.$img.'"/></td>';
        }
        echo '</table>';
    }
}
                          
class Item
{
    public $id, $itemname, $catid, $pricein, $pricesale, $info, $rate,
        $imagepath, $action;
    
    public function __construct($itemname, $catid, $pricein, $pricesale, $info,
            $imagepath, $rate=0, $action=0, $id=0) {
            $this->id=$id;
            $this->itemname=$itemname;
            $this->catid=$catid;
            $this->pricein=$pricein;
            $this->pricesale=$pricesale;
            $this->info=$info;
            $this->rate=$rate;
            $this->imagepath=$imagepath;
            $this->action=$action;
    }
    
    function intoDb()
    {
        try
        {
            $pdo=Tools::connect();
            $ps=$pdo->prepare("INSERT INTO Items 
                (itemname,catid,pricein,pricesale,info,rate,imagepath,action) 
                VALUES (:itemname,:catid,:pricein,:pricesale,:info,:rate,:imagepath,:action)");

            $ar=array(":itemname"=>$this->itemname, ":catid"=>$this->catid, 
                      ":pricein"=>$this->pricein, ":pricesale"=>$this->pricesale, 
                      ":info"=>$this->info, ":rate"=>$this->rate, ":imagepath"=>$this->imagepath,
                      ":action"=>$this->action);
            $ps->execute($ar);
            
        }
        catch(PDOException $e) 
        {
            return $e->getMessage();
        }
    }

    static function fromDb($id)
    {
        $item=null;
        try
        {
            $pdo=Tools::connect();
            $ps=$pdo->prepare("SELECT * FROM Items WHERE id=?");
            $ps->execute(array($id));
            $row=$ps->fetch();
            $item=new Item($row['itemname'], $row['catid'], $row['pricein'],
            $row['pricesale'], $row['info'], $row['imagepath'], 
            $row['rate'], $row['action'],$row['id']);
            return $item;
        }   
        catch(PDOException $e) 
        {
            echo $e->getMessage();
            return false;
        }
    }
                              
    static function GetItems($catid=0)
    {
        $ps=null;
        $items=null;
        try
        {
            $pdo=Tools::connect();
            if($catid == 0)
            {
                $ps=$pdo->prepare('select * from items');
                $ps->execute();
            }
            else
            {
                $ps=$pdo->prepare
                    ('select * from items where categoryid=?');
                $ps->execute(array($catid));
            }
            while ($row=$ps->fetch())
            {
                $item=new Item($row['itemname'],
                $row['catid'], $row['pricein'],
                $row['pricesale'], $row['info'],
                $row['imagepath'], $row['rate'],
                $row['action'],$row['id']);
                $items[]=$item;
            }
            return $items;
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
            return false;
        }
    }
                              
    function Draw()
    {
        echo "<div class='col-sm-3 col-md-3 col-lg-3 container' style='height:350px;'>";
        echo "<div style='margin:5px'>";
            //itemInfo.php contains detailed info about product
            echo "<div class='row' style='margin-top:2px; background-color:#ffd2aa;'>";
                echo "<a href='pages/iteminfo.php?name=".$this->id."'class='pull-left' style='margin-left:10px;''target='_blank'>";
                    echo $this->itemname;
                echo "</a>";
                echo "<span class='pull-right' style='margin-right:10px;'>";
                    echo $this->rate."&nbsp;rate";
                echo "</span>";
            echo "</div>";
            echo "<div style='height:100px;margin-top:2px;'class='row'>";
                echo "<img src='".$this->imagepath."'height='100px' />";
                echo "<span class='pull-right' style='margin-left:10px;color:red;
                    font-size:16pt;'>";
                    echo "$&nbsp;".$this->pricesale;
                echo "</span>";
            echo "</div>";
            echo "<div class='row' style='margin-top:10px;'>";
                echo "<p class='text-left col-xs-12'style='background-color:lightblue;
                    overflow:auto;height:60px;'>";
                    echo $this->info;
                echo "</p>";
            echo "</div>";
            echo "<div class='row' style='margin-top:2px;'>";
            echo "</div>";
            echo "<div class='row' style='margin-top:2px;'>";
                //creating cookies for the cart
                //will be explained later
                $ruser='';
                if(!isset($_SESSION['reg']) || $_SESSION['reg']=="")
                {
                    $ruser="cart_".$this->id;
                }
                else
                {
                    $ruser=$_SESSION['reg']."_".$this->id;
                }
                echo "<button class='btn btn-success col-xs-offset-1 col-xs-10'
                    onclick=createCookie('".$ruser."','".$this->id."')>Add To My Cart</button>";
            echo "</div>";
        echo "</div>";
        echo "</div>";
    }
    
    function DrawForCart()
    {
        echo "<div class='row' style='margin:2px;'>";
        echo "<img src='".$this->imagepath."'width='70px'
            class='col-sm-1 col-md-1 col-lg-1'/>";
        echo "<span style='marginright:
            10px;background-color:#ddeeaa;
            color:blue;font-size:16pt' class='col-sm-3 colmd-3 col-lg-3'>";
        echo $this->itemname;
        echo "</span>";
        echo "<span style='marginleft:10px;color:red;font-size:16pt;
            background-color:#ddeeaa;'class='col-sm-2 col-md-2 col-lg-2' >";
        echo "$&nbsp;".$this->pricesale;
        echo "</span>";
        $ruser='';
        if(!isset($_SESSION['reg']) || $_SESSION['reg']=="")
        {
            $ruser="cart_".$this->id;
        }
        else
        {
            $ruser=$_SESSION['reg']."_".$this->id;
        }
        echo "<button class='btn btn-sm btn-danger'style='margin-left:10px;'
            onclick=eraseCookie('".$ruser."')>x</button>";
        echo "</div>";
    }
    
    function Sale()
    {
        try
        {
            $pdo=Tools::connect();
            $ruser='cart';
            if(isset($_SESSION['reg']) && $_SESSION['reg'] !="")
            {
                $ruser=$_SESSION['reg'];
            }
            //Incresing total field for Customer
            $sql = "UPDATE Customers SET total=total + ? WHERE login = ?";
            $ps = $pdo->prepare($sql);
            $ps->execute(array($this->pricesale,$ruser));
            //Inserting info about sold item into table Sales
            $ins = "insert into Sales
                (customername,itemname,pricein,pricesale,datesale)
                values(?,?,?,?,?)";
            $ps = $pdo->prepare($ins);
            $ps->execute(array($ruser,$this->itemname,
            $this->pricein,$this->pricesale,
            @date("Y/m/d H:i:s")));
            //deleting item from Items table
            $del = "DELETE FROM Items WHERE id = ?";
            $ps = $pdo->prepare($del);
            $ps->execute(array($this->id));
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
            return false;
        }
    }
}

class Category
{
    public $cat;
    
    function __construct()
    {
        $this->cat="";
    }
    
    function selectCategories()
    {
        $pdo=Tools::connect();
        $list=$pdo->query("SELECT * FROM Categories");
        while ($row=$list->fetch())
        {
            echo '<option value="'.$row['id'].'">'.$row['category'].'</option>';
        }
    }
    
    function isCategory($cat)
    {
        $pdo=Tools::connect();
        $list=$pdo->query("SELECT * FROM Categories");
        while ($row=$list->fetch())
        {
            if($cat==$row['category']) return true;
        }
    }
    
    function addCategory($cat)
    {
        $this->cat=$cat;
        if($this->isCategory($cat)) return "Error, dublicate category";
        try
        {
            $pdo=Tools::connect();
            $ps=$pdo->prepare("INSERT INTO categories (category) VALUES (:cat)");
            $ps->bindParam(':cat', $cat, PDO::PARAM_STR);
            $ps->execute();
            return "Category added";
        }
        catch(PDOException $e) 
        {
            return $e->getMessage();
        }     
    }
    
    function deleteCategory($cat)
    {
        try
        {
            $pdo=Tools::connect();
            $ps=$pdo->prepare("DELETE FROM categories WHERE category=:cat");
            $ps->bindParam(':cat', $cat, PDO::PARAM_STR); 
            $ps->execute();
            return "Category deleted";
        }
        catch(PDOException $e) 
        {
            return $e->getMessage();
        }      
    }
}

class Image
{
    public $id;
    public $itemid;
    public $imagepath;
    
    function __construct($itemid, $imagepath, $id=0)
    {
        $this->id=$id;
        $this->itemid=$itemid;
        $this->imagepath=$imagepath;
    }
    
    function intoDb(){
        try
        {
            $pdo=Tools::connect();
            $ps=$pdo->prepare("INSERT INTO images 
            (itemid,imagepath) VALUES (:itemid,:imagepath)");
            $ps->bindParam(':itemid', $this->itemid, PDO::PARAM_INT);
            $ps->bindParam(':imagepath', $this->imagepath, PDO::PARAM_STR);
            $ps->execute();
            return "Photo added";
        }
        catch(PDOException $e) 
        {
            return $e->getMessage();
        }       
    }
}

class ItemInfo
{
    function selectImages($id)
    {
        $pdo=Tools::connect();
	    $res=$pdo->query('SELECT imagepath FROM images WHERE itemid='.$id);
        return $res;
    }
    
    function selectInfo($id)
    {
        $pdo=Tools::connect();
        $sel=$pdo->query('SELECT info FROM items WHERE id='.$id);
        $row=$sel->fetch(PDO::FETCH_ASSOC);
        return $row['info'];
    }
}

?>