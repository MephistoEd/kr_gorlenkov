<?php
//Вывод в админ-панель всех направлений
function print_adm_directions()
	{
	echo "<input type='button' class='add' onClick='window.location.href=\"?act=directions&mode=add\"' value='Добавить направление' /><br/><br/>";
	$qq = mysql_query("SELECT id,country,city FROM directions ORDER BY country ASC");
	echo "<h1>Направления</h1>";
	if(mysql_num_rows($qq)<1)
		{
		echo "<h4>Направления еще не добавлены.</h4>";
		}
	else
		{
		echo "<table class='adm_big_table'><tr><td>Направление</td><td>Действия</td></tr>";
		while($q = mysql_fetch_array($qq))
			{
			echo "<tr>";
			echo "<td><b>$q[country]</b> - $q[city]</td>";
			
			echo "<td><input type='button' class='edit_btn' id='edit_directions_$q[id]' />
					<input type='button' class='del_btn' id='del_directions_$q[id]' />";
			echo "</td>";
			echo "</tr>";
			}
		echo "</table>";
		}
	}
//Вывод в админ-панель формы добавления направления
function print_adm_add_directions()
	{
	echo "<h1>Добавить направление</h1>";
	echo "
	Страна: <input type='text' id='country' maxlength='255'/><br/><br/>
	Город: <input type='text' id='city' maxlength='255'/><br/><br/>
	<br/><br/>
	<input type='button' class='add save' id='directions_add' value='Добавить направление' />
	";
	}
//Вывод в админ-панель формы редактирования направления
function print_adm_edit_directions($id)
	{
	echo "<h1>Редактировать направление</h1>";
	$qq = mysql_query("SELECT country,city FROM directions WHERE id='$id'");
	$q = mysql_fetch_array($qq);
	echo "
	Страна: <input type='text' class='country_$id' id='country' maxlength='255' value='$q[country]'/><br/><br/>
	Город: <input type='text' id='city' maxlength='255' value='$q[city]'/><br/><br/>
	<input type='button' class='edit save' id='pages_edit' value='Редактировать' />
	";
	}
//Вывод в админ-панель всех отелей
function print_adm_hotels()
	{
	echo "<input type='button' class='add' onClick='window.location.href=\"?act=hotels&mode=add\"' value='Добавить отель' /><br/><br/>";
	$qq = mysql_query("SELECT h.id,h.title,d.country,d.city FROM hotels AS h,directions AS d WHERE h.directions_id=d.id ORDER BY h.title ASC");
	echo "<h1>Отели</h1>";
	if(mysql_num_rows($qq)<1)
		{
		echo "<h4>Отели еще не добавлены.</h4>";
		}
	else
		{
		echo "<table class='adm_big_table'><tr><td>Отель</td><td>Действия</td></tr>";
		while($q = mysql_fetch_array($qq))
			{
			echo "<tr>";
			echo "<td><b>$q[title]</b> ($q[country] - $q[city])</td>";
			
			echo "<td>
					<a href='?act=hotels&mode=images&hotels_id=$q[id]'>Изображения</a> 
					<a href='?act=hotels&mode=prices&hotels_id=$q[id]'>Цены</a> 
					<input type='button' class='edit_btn' id='edit_hotels_$q[id]' />
					<input type='button' class='del_btn' id='del_hotels_$q[id]' />";
			echo "</td>";
			echo "</tr>";
			}
		echo "</table>";
		}
	}
//Вывод в админ-панель формы добавления отеля
function print_adm_add_hotels()
	{
	$stars = Array("1","2","3","4","5");
	$food = Array("Без питания","Только завтрак","Завтрак и ужин","Завтрак, обед, ужин","Все включено","Ультра все включено");
	$rooms = Array("Для одного","Для двоих","Для двоих, разные кровати","Для двоих,с детьми");
	$parking = Array("Бесплатная, при отеле","Платная, при отеле","Бесплатная, общественная","Платная, общественная","Нет");
	echo "<h1>Добавить отель</h1>";
	echo "<div class='block'>";
	echo "Направление: ";
	$qq = mysql_query("SELECT id,country,city FROM directions ORDER BY country ASC");
	if(mysql_num_rows($qq)>0)
		{
		echo "<select id='directions_id'>";
		while($q = mysql_fetch_array($qq))
			{
			echo "<option value='$q[id]'>$q[country] - $q[city]</option>";
			}
		echo "</select><br/><br/>";
		}
		else echo "<b>Добавьте направление, прежде чем добавлять отель.</b><br/><br/>";
	echo "
	Наименование: <input type='text' id='title' maxlength='255'/><br/><br/>
	Звездность отеля: 
	";
	for($i=0;$i<count($stars);$i++)
		{
		echo "<input type='radio' name='stars' value='$stars[$i]' ";
		if($i==4)
			echo "checked";
		echo "/> $stars[$i]";
		}
	echo "
	<br/><br/>
	Время заезда/выезда:
	<input type='text' id='checkin' maxlength='5' placeholder='14:00'/> / 
	<input type='text' id='departure' maxlength='5' placeholder='12:00'/>
	</div>
	<div class='block'>
	Питание:<br/>
	";
	for($i=0;$i<count($food);$i++)
		{
		echo "<input type='checkbox' class='food'  id='food_".($i+1)."'/> $food[$i] <br/>";
		}
	echo "
	</div>
	<div class='block'>
	Типы номеров:<br/>
	";
	for($i=0;$i<count($rooms);$i++)
		{
		echo "<input type='checkbox' class='rooms'  id='room_".($i+1)."'/> $rooms[$i] <br/>";
		}
	echo "
	</div>
	<div class='block'>
	Парковка: <br/>
	";
	for($i=0;$i<count($parking);$i++)
		{
		echo "<input type='radio' name='parking' value='".($i+1)."' ";
		if($i==4)
			echo "checked";
		echo "/> $parking[$i]<br/>";
		}
	echo "
	</div>
	<br class='cl'/>
	<div class='block'>
	Интернет:<br/> <input type='text' id='internet' maxlength='255'/><br/><br/>
	Проживание с животными:<br/> <input type='text' id='animals' maxlength='255'/><br/><br/>
	Адрес:<br/> 
	<input type='text' id='address' maxlength='255'/> <a href='#' id='check_address' onclick='return false;'>Проверить адрес ></a>
	</div>
	<div class='block'>
	<div id='map'></div>
	</div>
	<br class='cl'/>
	Описание: <br/>
	<textarea id='about' cols='100' rows='8'></textarea><br/><br/>
	Вокруг отеля: <br/>
	<textarea id='outdoors' cols='100' rows='8'></textarea><br/><br/>
	Предоставляемый сервис: <br/>
	<textarea id='services' cols='100' rows='8'></textarea><br/><br/>
	<br/><br/>
	<input type='button' class='add save' id='hotels_add' value='Добавить отель' /><br/><br/>
	* После добавления отеля, не забудте поставить ценовые диапазоны для отеля, для отображения отеля клиентам.
	";
	}
//Вывод в админ-панель формы редактирования отеля
function print_adm_edit_hotels($id)
	{
	$qq2 = mysql_query("SELECT * FROM hotels WHERE id='$id'");
	if(mysql_num_rows($qq2)>0)
		{
		$q2 = mysql_fetch_array($qq2);
		$stars = Array("1","2","3","4","5");
		$food = Array("Без питания","Только завтрак","Завтрак и ужин","Завтрак, обед, ужин","Все включено","Ультра все включено");
		$rooms = Array("Для одного","Для двоих","Для двоих, разные кровати","Для двоих,с детьми");
		$parking = Array("Бесплатная, при отеле","Платная, при отеле","Бесплатная, общественная","Платная, общественная","Нет");
		echo "<h1>Редактирование отеля</h1>";
		echo "<div class='block'>";
		echo "Направление: ";
		$qq = mysql_query("SELECT id,country,city FROM directions ORDER BY country ASC");
		if(mysql_num_rows($qq)>0)
			{
			echo "<select id='directions_id'>";
			while($q = mysql_fetch_array($qq))
				{
				echo "<option value='$q[id]' ";
				if($q2["directions_id"]==$q["id"]) echo "selected";
				echo ">$q[country] - $q[city]</option>";
				}
			echo "</select><br/><br/>";
			}
			else echo "<b>Добавьте направление, прежде чем редактировать отель.</b><br/><br/>";
		echo "
		Наименование: <input type='text' class='hotel_$q2[id]' id='title' maxlength='255' value='$q2[title]'/><br/><br/>
		Звездность отеля: 
		";
		for($i=0;$i<count($stars);$i++)
			{
			echo "<input type='radio' name='stars' value='$stars[$i]' ";
			if($stars[$i]==$q2["stars"])
				echo "checked";
			echo "/> $stars[$i]";
			}
		echo "
		<br/><br/>
		Время заезда/выезда: 
		<input type='text' id='checkin' maxlength='5' placeholder='14:00' value='$q2[checkin]'/> / 
		<input type='text' id='departure' maxlength='5' placeholder='12:00' value='$q2[departure]'/>
		</div>
		<div class='block'>
		Питание:<br/>
		";
		$food_selected = Array();
		$qq3 = mysql_query("SELECT selected FROM food WHERE hotel_id=$id");
		while($q3 = mysql_fetch_array($qq3)) { array_push($food_selected,$q3["selected"]); }
		for($i=0;$i<count($food);$i++)
			{
			echo "<input type='checkbox' class='food'  id='food_".($i+1)."' ";
			if(in_array(($i+1),$food_selected)) echo "checked";
			echo "/> $food[$i] <br/>";
			}
		echo "
		</div>
		<div class='block'>
		Типы номеров:<br/>
		";
		$room_selected = Array();
		$qq4 = mysql_query("SELECT selected FROM rooms WHERE hotel_id=$id");
		while($q4 = mysql_fetch_array($qq4)) { array_push($room_selected,$q4["selected"]); }
		for($i=0;$i<count($rooms);$i++)
			{
			echo "<input type='checkbox' class='rooms'  id='room_".($i+1)."' ";
			if(in_array(($i+1),$room_selected)) echo "checked";
			echo "/> $rooms[$i] <br/>";
			}
		echo "
		</div>
		<div class='block'>
		Парковка: <br/>
		";
		for($i=0;$i<count($parking);$i++)
			{
			echo "<input type='radio' name='parking' value='".($i+1)."' ";
			if(($i+1)==$q2["parking"])
				echo "checked";
			echo "/> $parking[$i]<br/>";
			}
		echo "
		</div>
		<br class='cl'/>
		<div class='block'>
		Интернет:<br/> <input type='text' id='internet' maxlength='255' value='$q2[internet]'/><br/><br/>
		Проживание с животными:<br/> <input type='text' id='animals' maxlength='255' value='$q2[animals]'/><br/><br/>
		Адрес:<br/> 
		<input type='text' id='address' maxlength='255' value='$q2[address]'/> <a href='#' id='check_address' onclick='return false;'>Проверить адрес ></a>
		</div>
		<div class='block'>
		<div id='map'></div>
		</div>
		<br class='cl'/>
		Описание: <br/>
		<textarea id='about' cols='100' rows='8'>$q2[about]</textarea><br/><br/>
		Вокруг отеля: <br/>
		<textarea id='outdoors' cols='100' rows='8'>$q2[outdoors]</textarea><br/><br/>
		Предоставляемый сервис: <br/>
		<textarea id='services' cols='100' rows='8'>$q2[services]</textarea><br/><br/>
		<br/><br/>
		<input type='button' class='edit save' id='hotels_edit' value='Редактировать' /><br/><br/>
		* После добавления отеля, не забудте поставить ценовые диапазоны для отеля, для отображения отеля клиентам.
		";
		}
	}
//Вывод в админ-панель всех изображений определенного отеля
function print_adm_all_images($hotels_id)
	{
	echo "<input type='button' class='add' onClick='window.location.href=\"?act=hotels&mode=add_images&hotels_id=$hotels_id\"' value='Добавить изображения' /><br/><br/>";
	$qq2 = mysql_query("SELECT title FROM hotels WHERE id='$hotels_id'");
	$q2 = mysql_fetch_array($qq2);
	$qq = mysql_query("SELECT * FROM images WHERE hotel_id='$hotels_id' ORDER BY position ASC");
	if(mysql_num_rows($qq)<1)
		{
		echo "<h1>Изображения еще не добавлены.</h1>";
		return;
		}
	echo "<h1>Изображения отеля \"$q2[title]\"</h1><br/>";
	echo "<table class='adm_big_table'><tr style='text-align:center;'><td>Фото</td><td>Позиция</td><td>Действия</td></tr>";
	while($q = mysql_fetch_array($qq))
		{
		echo "<td style='text-align:center;'><img src='../images/upload/$q[image]' width='200px'/></td>";
		echo "<td style='text-align:center;'><b><input type='text' class='img_position' id='img_$q[id]' style='text-align:center; width:30px;' value='$q[position]'/></b></td>";
		echo "<td>";
		echo "<input type='button' class='del_btn' id='del_images_$q[id]' />";
		echo "</td>";
		echo "</tr>";
		}
		echo "</table><br/><br/>";
		echo " <input type='button' class='save' id='images_position' value='Сохранить позиции' /></div>";
	}
//Вывод в админ-панель формы добавления изображения к отелю
function print_adm_add_images($hotels_id)
	{
	$qq2 = mysql_query("SELECT title FROM hotels WHERE id='$hotels_id'");
	$q2 = mysql_fetch_array($qq2);
	echo "<h1>Добавить изображения к отелю \"$q2[title]\"</h1><br/>";
	echo "Выберите одно или несколько изображений и нажмите кнопку \"Загрузить\"<br/>
	<form id='images' name='form' action='./system/upl2.php' target='iframe' method='post' enctype='multipart/form-data'>
					<input type='file' name='userfile[]' multiple='true' /><input type='submit' value='Загрузить' />
					</form>
	<span id='hotel' class='hotel_".$hotels_id."' style='font-size:14px; color:red;'>После надписи \"Файлы успешно загружены, нажмите кнопку 'Добавить' \"</span><br/>
	";
	echo "
	<br/><br/>
	<input type='button' class='save' id='images_add' value='Добавить' />
	";
	}
//Вывод в админ-панель "календаря цен" определенного отеля
function print_adm_prices($hotels_id)
	{
	$qq2 = mysql_query("SELECT title FROM hotels WHERE id='$hotels_id'");
	$q2 = mysql_fetch_array($qq2);
	echo "<h1>Цены к отелю \"$q2[title]\"</h1><br/>";
	echo "<div id='datepicker'></div>";
	echo "<script>
	$(function() {
	picker();
	});
	</script>";
	echo "<div class='block'>";
	echo "Дата: <input type='text' class='hotel_".$hotels_id."' id='ondate' maxlength='10'/><br/><br/>";
	echo "Цена: <input type='text' id='price' placeholder='1000.00'/> руб.<br/><br/>";
	echo "<span id='mess'>Сохранено</span>";
	echo "</div>";
	}
//Вывод в админ-панель всех заказов
function print_adm_orders()
	{
	$qq = mysql_query("SELECT h.title,o.* FROM hotels AS h,orders AS o WHERE o.hotel_id=h.id ORDER BY o.id DESC");
	echo "<h1>Заказы</h1>";
	if(mysql_num_rows($qq)<1)
		{
		echo "<h4>Заказы еще не сделанны</h4>";
		}
	else
		{
		echo "<table class='adm_big_table'><tr><td>Отель и даты проживания</td><td>Дата заказа</td><td>ФИО заказчика и телефон</td><td>Рассчитаная цена</td></tr>";
		while($q = mysql_fetch_array($qq))
			{
			echo "<tr>";
			echo "<td><b>$q[title]</b> ($q[checkin] - $q[departure])</td>";
			echo "<td>".date("d.m.Y H:i",strtotime($q["order_date"]))."</td>";
			echo "<td><b>$q[name]</b> ($q[phone])</td>";
			echo "<td>$q[price]</td>";
			echo "</tr>";
			}
		echo "</table>";
		}
	}
?>