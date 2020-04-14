<?php 
session_start(); //Запускаем сессию
//Подключаем конфигурационные файлы и определяем ip,browser пользователя
include '../../system/config.php';
$ip = $_SERVER['REMOTE_ADDR'];
$browser = $_SERVER['HTTP_USER_AGENT'];
//Проверка на корректный вход в систему
if($_SESSION['login'] != '')
{
//Функия добавляет в БД историю каждого изменения данных БД администратором
function add_action($code,$ip,$browser)
	{
	$admin = $_SESSION['login'];
	que("INSERT into `actions` (admin,code,ip,browser) VALUES ('$admin','$code','$ip','$browser')",$ip,$browser);
	}
//Сортируем входящие данные
if($_POST['method']){
  switch($_POST['method']){
	case 'save':
		add_action($_POST['id'],$ip,$browser);
		switch($_POST['id'])
			{
			//Добавление в БД туристического направления
			case 'directions_add':
				$country = smart($_POST['country']);
				$city = smart($_POST['city']);
				if(que("INSERT INTO `directions` (country,city) VALUES('$country','$city')",$ip,$browser)=='no')
					exit('no');
				echo 'ok';
			break;
			//Редактирование в БД туристического направления
			case 'directions_edit':
				$ids = intval($_POST['ids']);
				$country = smart($_POST['country']);
				$city = smart($_POST['city']);
				if(que("UPDATE `directions` SET country='$country',city='$city' WHERE id='$ids'",$ip,$browser)=='no')
					exit('no');
				echo 'ok';
			break;
			//Добавление в БД отеля
			case 'hotels_add':
				$directions_id = intval($_POST['directions_id']);
				$stars = intval($_POST['stars']);
				$parking = intval($_POST['parking']);
				$title = smart($_POST['title']);
				$checkin = smart($_POST['checkin']);
				$departure = smart($_POST['departure']);
				$food = $_POST['food'];
				$rooms = $_POST['rooms'];
				$internet = smart($_POST['internet']);
				$animals = smart($_POST['animals']);
				$address = smart($_POST['address']);
				$outdoors = smart($_POST['outdoors']);
				$about = smart($_POST['about']);
				$services = smart($_POST['services']);
				if(que("INSERT INTO `hotels` (directions_id,title,stars,parking,about,outdoors,services,internet,animals,checkin,departure,address) VALUES('$directions_id','$title','$stars','$parking','$about','$outdoors','$services','$internet','$animals','$checkin','$departure','$address')",$ip,$browser)=='no')
					exit('no');
				$qq = mysql_query("SELECT MAX(id) FROM hotels");
				$q = mysql_fetch_array($qq);
				$current_hotel_id = $q[0];
				if(count($food)>0) 
					{
					for($i=0;$i<count($food);$i++)
						{
						if(que("INSERT INTO `food` (hotel_id,selected) VALUES('$current_hotel_id','$food[$i]')",$ip,$browser)=='no')
							exit('no');
						}
					}
				if(count($rooms)>0) 
					{
					for($i=0;$i<count($rooms);$i++)
						{
						if(que("INSERT INTO `rooms` (hotel_id,selected) VALUES('$current_hotel_id','$rooms[$i]')",$ip,$browser)=='no')
							exit('no');
						}
					}
				echo 'ok';
			break;
			//Редактирование в БД туристического направления
			case 'hotels_edit':
				$directions_id = intval($_POST['directions_id']);
				$ids = intval($_POST['ids']);
				$stars = intval($_POST['stars']);
				$parking = intval($_POST['parking']);
				$title = smart($_POST['title']);
				$checkin = smart($_POST['checkin']);
				$departure = smart($_POST['departure']);
				$food = $_POST['food'];
				$rooms = $_POST['rooms'];
				$internet = smart($_POST['internet']);
				$animals = smart($_POST['animals']);
				$address = smart($_POST['address']);
				$outdoors = smart($_POST['outdoors']);
				$about = smart($_POST['about']);
				$services = smart($_POST['services']);
				if(que("UPDATE `hotels` SET directions_id='$directions_id',title='$title',stars='$stars',parking='$parking',about='$about',outdoors='$outdoors',services='$services',internet='$internet',animals='$animals',checkin='$checkin',departure='$departure',address='$address' WHERE id='$ids'",$ip,$browser)=='no')
					exit('no');
				if(que("DELETE FROM `food` WHERE hotel_id='$ids'",$ip,$browser)=='no')
							exit('no');
				for($i=0;$i<count($food);$i++)
					{
					if(que("INSERT INTO `food` (hotel_id,selected) VALUES('$ids','$food[$i]')",$ip,$browser)=='no')
						exit('no');
					}
				if(que("DELETE FROM `rooms` WHERE hotel_id='$ids'",$ip,$browser)=='no')
					exit('no');
				for($i=0;$i<count($rooms);$i++)
					{
					if(que("INSERT INTO `rooms` (hotel_id,selected) VALUES('$ids','$rooms[$i]')",$ip,$browser)=='no')
						exit('no');
					}
				echo 'ok';
			break;
			//Добавление в БД изображений, привязанных к отелю
			case 'images_add':
				$hotel_id = intval($_POST['hotel_id']);
				$phots = $_POST['phots'];
				$len = count($phots);
				for($i=0;$i<$len;$i++)
					{
					$qq = mysql_query("SELECT MAX(position) FROM images WHERE hotel_id='$hotel_id'");
					if(mysql_num_rows($qq)<1)
						$position = 1;
					else
						{
						$arr = mysql_fetch_array($qq);
						$position = $arr[0]+1;
						}
					$image = trim($phots[$i]);
					if(que("INSERT INTO `images` (hotel_id,image,position) VALUES('$hotel_id','$image','$position')",$ip,$browser)=='no')
						exit('no');
					}
				echo 'ok';
			break;
			//Редактирование позиций изображений в БД
			case 'images_position':
			$pos = $_POST['pos'];
			$ids = $_POST['ids'];
			for($i=0;$i<count($pos);$i++)
				{
				if(que("UPDATE `images` SET position='$pos[$i]' WHERE id='$ids[$i]'",$ip,$browser)=='no')
					exit('no');
				}
				echo 'ok';
			break;
			//Добавление в БД цены, привязанных к отелю и дате
			case 'prices_add':
				$hotel_id = intval($_POST['hotel_id']);
				$ondate = smart($_POST['ondate']);
				$price = $_POST['price'];
				$price = str_replace(",",".",$price);
				$price = floatval($price);
				if($price<=0) exit ("no");
				$qq = mysql_query("SELECT id FROM prices WHERE ondate='$ondate' AND hotel_id='$hotel_id'");
				if(mysql_num_rows($qq)>0)
					{
					if(que("UPDATE `prices` SET price='$price' WHERE ondate='$ondate' AND hotel_id='$hotel_id'",$ip,$browser)=='no')
						exit('no');
					}
					else
						{
						if(que("INSERT INTO `prices` (hotel_id,ondate,price) VALUES('$hotel_id','$ondate','$price')",$ip,$browser)=='no')
							exit('no');
						}
				echo 'ok';
			break;
			//Получение цены отеля на определенную дату
			case 'prices_get':
				$hotel_id = intval($_POST['hotel_id']);
				$ondate = smart($_POST['ondate']);
				$res = resCreate("SELECT price FROM prices WHERE ondate='$ondate' AND hotel_id='$hotel_id'");
				echo json_safe_encode($res);
			break;
			//Удаление строки отеля/направления/изображения из БД
			case 'delete':
				$ids = intval($_POST['ids']);
				$act = smart($_POST['act']);
				if(que("DELETE FROM `$act` WHERE id='$ids'",$ip,$browser)=='no')
					exit('no');
				echo 'ok';
			break;
			}
	break;
}
}
}
?>