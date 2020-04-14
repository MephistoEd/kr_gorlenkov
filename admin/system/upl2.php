<?
session_start(); //подключаем сессию
//Проверка на корректный вход в систему
if($_SESSION['login'] != '')
{
//Информация о файле и права доступа
$valid_types  =  array("gif","jpg", "png", "jpeg","GIF","JPG","PNG","JPEG");
@chmod("../images/upload/",0775);
if(count($_FILES['userfile'])) {  
$uploaddir4 = '../images/upload/'; 
$x4 = ($_FILES['userfile']['name']); /
echo "<div id='j'>";
foreach ($x4 as $key => $value) {
	$ext = substr($_FILES['userfile']['name'][$key], 1 + strrpos($_FILES['userfile']['name'][$key], "."));
	if (filesize($_FILES['userfile']['tmp_name'][$key]) > (4096 * 1024))
		exit('<script type=text/javascript>alert("Ошибка: размер фотографий не должен превышать 4мб")</script>');
	if (!in_array($ext, $valid_types)) 
		exit('<script type=text/javascript>alert("Ошибка: Неверный формат изображения (допустимы - jpg,png,gif)")</script>');
				if (in_array($ext,array('jpeg','JPEG','jpg','JPG'))) $p = 'jpeg';
				if (in_array($ext,array('gif','GIF'))) $p = 'gif';
				if (in_array($ext,array('png','PNG'))) $p = 'png';

				$newname = 'pic__'.rand(10000000,99999999).'.'.$p;
 $uploadfile4 = $uploaddir4 . $newname;
//Загрузка изображения
 if (@move_uploaded_file($_FILES['userfile']['tmp_name'][$key], $uploadfile4))
	{
	echo $newname."||";
	}
 }
 echo'</div>';
 echo '<script type=text/javascript>alert("Файлы успешно загружены")</script>';
 }

}
?>