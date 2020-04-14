<?php
//Функция вывода отелей из БД и формирование верстки отелей для поиска на портале
function print_hotels($cities)
	{
	echo "Отели:<br/>";
	echo "<input type='text' id='hotel_search' placeholder='Поиск по названию'/>";
	echo "<div class='change_box' id='hotels_box'>";
	$directions_id = implode(",",$cities);
	$qq = mysql_query("SELECT id,title,stars FROM hotels WHERE directions_id IN ($directions_id) ORDER BY title ASC");
	while($q = mysql_fetch_array($qq))
		{
		echo "<label><input type='checkbox' class='hotel' id='hotel_$q[id]'/> $q[title] $q[stars]*</label><br/>";
		}
	echo "</div>";
	}
//Функция вывода городов из БД и формирование верстки городов для поиска на портале
function print_city($country)
	{
	echo "Город:<br/>";
	echo "<label><input type='checkbox' id='all_cities' checked/> Все города</label><br/>";
	echo "<div class='change_box' id='city_box'>";
	$qq2 = mysql_query("SELECT city,id FROM directions WHERE country='$country' GROUP BY city ORDER BY city ASC");
	$cities=Array();
	while($q2 = mysql_fetch_array($qq2))
		{
		array_push($cities,$q2["id"]);
		echo "<label><input type='checkbox' class='city' id='city_$q2[id]' checked/> $q2[city]</label><br/>";
		}
	echo "</div>";
	print_hotels($cities);
	}
//Функция вывода страны из БД и формирование верстки страны для поиска на портале
function print_country_city_hotels()
	{
	echo "Страна:<br/>";
	echo "<select id='country'>";
	$qq = mysql_query("SELECT country,id FROM directions GROUP BY country ORDER BY country ASC");
	$i=0;
	while($q = mysql_fetch_array($qq))
		{
		if($i==0) $country=$q["country"];
		$i++;
		echo "<option value='$q[id]'>$q[country]</option>";
		}
	echo "</select>";
	print_city($country);
	}
//Функция вывода типа размещения и питания из БД и формирование верстки для поиска на портале
function print_food_rooms()
	{
	$food = Array("Без питания","Только завтрак","Завтрак и ужин","Завтрак, обед, ужин","Все включено","Ультра все включено");
	$rooms = Array("Для одного","Для двоих","Для двоих, разные кровати","Для двоих,с детьми");
	echo "Тип питания:<br/>";
	echo "<select id='food'>";
	for($i=0;$i<count($food);$i++)
		{
		echo "<option value='".($i+1)."'>$food[$i]</option>";
		}
	echo "</select><br/>";
	echo "Тип размещения:<br/>";
	echo "<select id='rooms'>";
	for($i=0;$i<count($rooms);$i++)
		{
		echo "<option value='".($i+1)."'>$rooms[$i]</option>";
		}
	echo "</select><br/>";
	}
?>