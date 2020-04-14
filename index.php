<?php 
//подключаем заголовок
require 'header.php';
?>
<!-- Верстка -->
<div id="shadow"></div>
<div id="modal"></div>
<div id="container">
	<div class="box">
		<div class="header">Туристическое направление</div>
		<div class="body">
		<? print_country_city_hotels(); ?>
		</div>
	</div>
	<div class="box">
		<div class="header">Дополнительные параметры</div>
		<div class="body">
		<? print_food_rooms(); ?>
		Дата поездки:<br/>
		С <input type="text" id="checkin" maxlength="10" value="<?echo date("d.m.Y",strtotime("+3 days"));?>"/> 
		<span class='margin'>До <input type="text" id="departure" maxlength="10" value="<?echo date("d.m.Y",strtotime("+7 days"));?>"/> </span>
		<br/>
		Цена:<br/>
		От <input type="text" id="price_from" maxlength="7" value="20000"/> руб.
		<span class='margin'>До <input type="text" id="price_to" maxlength="7" value="150000"/> руб.</span><br/><br/>
		<a href="#" id="search_button" onclick="return false;">Подобрать тур</a>
		</div>
	</div>
	<br class="cl"/>
	<div id="results">
	
	</div>
	<br class="cl"/>
	<span class="copyright">Автор: Данилов М. В. (И-02з) 2016г. - ВКР СПБ ГУТ им. проф. М.А.Бонч-Бруевича</span>
</div>
</body></html>