<?php session_start(); //Запускаем сессию
//Подключаем конфигурационные файлы и файлы с функциями php
require_once '../system/config.php';
require_once './system/functions.php';
?>
<html><HEAD><META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<title>Административный интерфейс управления туристическим порталом</title>
<!-- Выбираем кодировку, подключаем библиотеки, файлы со скриптами и стилями -->
<link href='http://fonts.googleapis.com/css?family=Open+Sans&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
<link rel=Stylesheet href="./css/style_adm.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script src = "./system/set_adm.js" type = "text/javascript"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src = "./system/jquery-ui-rus.js" type = "text/javascript"></script>
</head>
<body>
<?php 
//Проверка ввода пароля/логина
if(isset($_POST['admLog']) && isset($_POST['admPass']))
	{
	$log = smart($_POST['admLog']);
	$pass = smart($_POST['admPass']);
	$qt = mysql_query("SELECT * FROM `admin` WHERE `login`='$log' and `pass`='$pass'");
	$q = mysql_fetch_array($qt);
	if($q['login']==$log && $q['pass']==$pass) 
		$_SESSION['login'] = $log; 
	?>
	<script type="text/javascript">
	window.location.href="./root.php?wrong";
	</script>
	<?
	}
?>
<!-- Форма ввода пароля -->
<div id="admin">
<?php if(!isset($_SESSION['login'])) { 
if(isset($_GET['wrong']))echo'<span class="red" style="font-size:14px;">Введен неверный логин/пароль</span><br/><br/>';
?>
<div id="wapmax">
<form name="enter" id="enter" action="" method="POST">
Логин: <br/><input class="enter" type="text" name="admLog" /><br/>
Пароль: <br/><input class="enter" type="password" name="admPass"/><br/><br/>
<input type="submit" value="Войти"/>
</form>
</div>
<?php }
else {
	if(isset($_GET['status']))
		{
		switch($_GET['status'])
			{
			case 'ok':
				?><script>status('ok');</script><?
			break;
			case 'no':
				?><script>status('no');</script><?
			break;
			}
		}
	?>
<!-- Верстка основной административной части-->
	<div id="admin_top">
		<a href="./root.php?act=directions" class="adm_btn" id="adm_btn_1">Направления</a>
		<a href="./root.php?act=hotels" class="adm_btn" id="adm_btn_2">Отели</a>
		<a href="./root.php?act=orders" class="adm_btn" id="adm_btn_3">Заказы</a>
		<div id="login_info">Вы зашли как <b><?echo $_SESSION['login'];?></b> <a href="./root.php?act=exit">Выход</a></div>
	</div>
	<div id="adm_monoblock">
	<div id="saved">Изменения успешно применены</div>
	<div id="error">Произошла ошибка. Попробуйте позже.</div>
		<div id="adm_center">
		
  <?if(!$_GET['act'])
		{
		echo "Добро пожаловать! Вы зашли в административный интерфейс управления туристическим порталом.<br/> Пожалуйста выберите интересующий пункт меню в верхней строке, для управления порталом.";
		}
		else
		{
		//Сортируем входящие данные для определения выбранного пункта меню
		switch($_GET['act'])
			{
			#directions
			case 'directions':
			if(!isset($_GET['mode']))
				{
				print_adm_directions();
				}
			elseif($_GET['mode']=='add')
				{
				print_adm_add_directions();
				}
			elseif($_GET['mode']=='edit')
				{
				print_adm_edit_directions(intval($_GET['id']));
				}
			break;
			#hotels
			case 'hotels':
			if(!isset($_GET['mode']))
				{
				print_adm_hotels();
				}
			elseif($_GET['mode']=='add')
				{
				print_adm_add_hotels();
				}
			elseif($_GET['mode']=='edit')
				{
				print_adm_edit_hotels(intval($_GET['id']));
				}
			elseif($_GET['mode']=='images')
				{
				print_adm_all_images(intval($_GET['hotels_id']));
				}
			elseif($_GET['mode']=='add_images')
				{
				print_adm_add_images(intval($_GET['hotels_id']));
				}
			elseif($_GET['mode']=='prices')
				{
				print_adm_prices(intval($_GET['hotels_id']));
				}
			break;
			#orders
			case 'orders':
			if(!isset($_GET['mode']))
				{
				print_adm_orders();
				}
			break;
			#exit
			case 'exit':
			unset($_SESSION['login']);
			?>
			<script type="text/javascript">
			window.location.href="./root.php";
			</script>
			<?
			break;
			}
		
		}?>
		<iframe name="iframe" src="" style="width: 1px; height: 1px; display: none;"></iframe>
		</div></div>
		
		
<? } ?>
</div>
<div id="adm_bot">
	Автор: Данилов М. В. (И-02з) 2016г. - ВКР СПБ ГУТ им. проф. М.А.Бонч-Бруевича
</div>
</body>
</html>