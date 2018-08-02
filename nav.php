
<?php	
$host = $_SERVER["HTTP_HOST"];
$host2= $_SERVER['REQUEST_URI'];
$url= $host."".$host2;
?>

<ul>
<li class="<?php if ($url == "" ) echo("active");  ?>"><a class="nav-link" href=""> I</a></li>
<li class="<?php if ($url == "" ) echo("active");  ?>"><a class="nav-link" href=""> P </a></li>
<li class="<?php if ($url == "" ) echo("active");  ?>"><a class="nav-link" href=""> T  </a></li>
<li class="<?php if ($url == "" ) echo("active");  ?>"><a class="nav-link" href=""> E  </a></li>
<li class="<?php if ($url == "" ) echo("active");  ?>"><a class="nav-link" href=""> C  </a></li>
</ul>

<br>
<br>
