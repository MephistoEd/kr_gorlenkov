<?php 
include 'config.php'; #подключаем файл конфигурации для доступа к БД и функциям защиты
//Сортируем входящие данные
switch($_POST['act']){
	case 'get_city':
		//выборка городов из базы (все выборки передаются в формате JSON-объекта)
		$country = smart($_POST['country']);
		$res = resCreate("SELECT city,id FROM directions WHERE country='$country' GROUP BY city ORDER BY city ASC");
		echo json_safe_encode($res);
	break;
	case 'get_hotels':
		//выборка городов из базы 
		$cities = $_POST['cities'];
		$cities = array_map('intval',$cities);
		$directions_id = implode(",",$cities);
		$res = resCreate("SELECT id,title,stars FROM hotels WHERE directions_id IN ($directions_id) ORDER BY title ASC");
		echo json_safe_encode($res);
	break;
	case 'search':
		//Поиск отелей по названию (выборка из базы)
		$str = smart(trim($_POST['str']));
		$cities = $_POST['cities'];
		$cities = array_map('intval',$cities);
		$directions_id = implode(",",$cities);
		$res = resCreate("SELECT id,title,stars FROM hotels WHERE directions_id IN ($directions_id) AND title LIKE '%$str%' ORDER BY title ASC");
		echo json_safe_encode($res);
	break;
	case 'search_tour':
		//Подбор тура по заданным параметрам
		$hotels = $_POST['hotels'];
		$cities = $_POST['cities'];
		if(count($hotels)<1 && count($cities)<1)
			exit("no");
		$cities = array_map('intval',$cities);
		$directions_id = implode(",",$cities);
		if(count($hotels)<1)
			$where = "h.directions_id IN ($directions_id)";
		else
			{
			$hotels = array_map('intval',$hotels);
			$hotels_id = implode(",",$hotels);
			$where = "h.id IN ($hotels_id)";
			}
		$food = intval($_POST['food']);
		$rooms = intval($_POST['rooms']);
		if(($food<0 || $food>6) || ($rooms<0 || $rooms>4))
			exit("no");
		$checkin = smart($_POST['checkin']);
		$departure = smart($_POST['departure']);
		$price_from = floatval($_POST['price_from']);
		$price_to = floatval($_POST['price_to']);
		if (preg_match("/([0-2]\d|3[01])\.(0\d|1[012])\.(\d{4})/",$checkin) && preg_match("/([0-2]\d|3[01])\.(0\d|1[012])\.(\d{4})/",$checkin))
			{
			$from = new DateTime($checkin);
			$to   = new DateTime($departure);
			$to->modify( '+1 day' ); 
			$period = new DatePeriod($from, new DateInterval('P1D'), $to);
			$arrayOfDates = array_map(function($item){return $item->format('d.m.Y');},iterator_to_array($period));
			$dates = "\"".implode('", "',$arrayOfDates)."\"";
			if(count($arrayOfDates)>21)
				exit("no");
			if($food!=0)
				{
				$food_app = "AND selected='$food'";
				}
			if($rooms!=0)
				{
				$rooms_app = "AND selected='$rooms'";
				}
			$res = resCreate("SELECT h.id,h.title,h.stars,d.city FROM hotels AS h,directions AS d WHERE $where AND d.id=h.directions_id ORDER BY h.title ASC");
			$res["price"] = resCreate("SELECT h.id,SUM(p.price) AS total FROM prices AS p, hotels AS h WHERE $where AND p.hotel_id=h.id AND p.ondate IN ($dates) GROUP BY p.hotel_id HAVING SUM(p.price)>$price_from AND SUM(p.price)<$price_to");
			$res["img"] = resCreate("SELECT h.id,i.image FROM hotels AS h,images AS i WHERE $where AND i.hotel_id=h.id GROUP BY i.hotel_id ORDER BY i.position");
			$res["food"] = resCreate("SELECT h.id,f.selected AS food FROM hotels AS h,food AS f WHERE $where AND h.id=f.hotel_id $food_app ORDER by f.selected DESC");
			$res["rooms"] = resCreate("SELECT h.id,r.selected AS rooms FROM hotels AS h,rooms AS r WHERE $where $rooms_app AND h.id=r.hotel_id ORDER by r.selected DESC");
			echo json_safe_encode($res);
			}
			else
				exit("no");
	break;
	case 'hotel_info':
		//выборка из базы полной информации об отеле
		$id = intval($_POST['id']);
		$res = resCreate("SELECT h.id,h.title,h.stars,d.city,d.country,h.parking,h.about,h.outdoors,h.services,h.internet,h.animals,h.checkin,h.departure,h.address FROM hotels AS h,directions AS d WHERE h.id='$id' AND d.id=h.directions_id");
		$res["img"] = resCreate("SELECT image FROM images WHERE hotel_id='$id' ORDER BY position LIMIT 0,4");
		$res["food"] = resCreate("SELECT selected AS food FROM food WHERE hotel_id='$id' ORDER by selected ASC");
		$res["rooms"] = resCreate("SELECT selected AS rooms FROM rooms WHERE hotel_id='$id' ORDER by selected ASC");
		echo json_safe_encode($res);
	break;
	case 'order':
		//занесение в базу данных сформированного заказа
		$hotel_id = intval($_POST['id']);
		$title = smart($_POST['title']);
		$checkin = smart($_POST['checkin']);
		$departure = smart($_POST['departure']);
		$price = intval($_POST['price']);
		$name = smart($_POST['name']);
		$phone = smart($_POST['phone']);
		if(que("INSERT INTO `orders` (hotel_id,name,phone,checkin,departure,price) VALUES('$hotel_id','$name','$phone','$checkin','$departure','$price')",$ip,$browser)=='no')
					exit('no');
		echo "ok";
	break;
}
?>