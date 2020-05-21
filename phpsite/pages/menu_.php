<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php?page=1">Купи-Купи</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
      	<li <?php echo ($page==2)? "class='active'":"" ?>>
            <a href="index.php?page=2">
                <span class="glyphicon glyphicon-th-list"></span>
                Каталог
            </a>
      	</li>
        <li <?php echo ($page==3)? "class='active'":"" ?>>
            <a href="index.php?page=3">
                <span class="glyphicon glyphicon-shopping-cart"></span>
                Корзина
            </a>
        </li>
        <li <?php echo ($page==4)? "class='active'":"" ?>>
        <a href="index.php?page=4">
        <span class="glyphicon glyphicon-user"></span>
        Регистрация 
        </a>
        </li>	
        <li <?php echo ($page==5)? "class='active'":"" ?>>
            <a href="index.php?page=5">
                <span class="glyphicon glyphicon-wrench"></span>
                Админ
            </a>
        </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">      
       	<form class="navbar-form navbar-left">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="логин...">
                <input type="pass" class="form-control" placeholder="пароль...">
            </div>
            <button type="submit" class="btn btn-default">
                <span class="glyphicon glyphicon-log-in"></span>
                Войти
            </button>
        </form>     
      </ul> 	
    </div>
  </div>
</nav>