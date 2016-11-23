<?
define(server,"localhost");
define(user,"root");
define(pass,"password");
//define(pass,"1234");
define(db,"asknutt");

$link=@mysql_connect(server,user,pass) or die ('Connection to database failed.');
//mysql_query("set names tis620");
mysql_query("set names utf8");
mysql_select_db(db);

//define('page_id', basename(dirname($_SERVER['PHP_SELF'])));
?>
