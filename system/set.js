//Запрос к серверной части для получения списка отелей, при смене города в поиске
function get_hotels(cities)
	{
	if(cities.length<1)
		{
		$("#hotels_box").html("");
		return;
		}
	$.post('./system/api.php',{act:'get_hotels',cities:cities},function(data){
		var json = $.parseJSON(data);
		if(json.response['error_code'] != '301')
			{
			$("#hotels_box").html("");
			var htm="";
			for(var i=0;i<json.response.length;i++)
				{
				htm+="<label><input type='checkbox' class='hotel' id='hotel_"+json.response[i].id+"'/> "+json.response[i].title+" "+json.response[i].stars+"*</label><br/>";
				cities.push(json.response[i].id);
				}
			$("#hotels_box").html(htm);
			}
			else
				{
				$("#hotels_box").html("");
				}
		});
	}
//Запрос к серверной части для получения списка отелей, при наборе символов в строке поиска отелей по названию
function search(str,cities)
	{
	if($.trim(str)!="" && $.trim(str).length>1 && cities.length>0)
		{
		$.post('./system/api.php',{act:'search',str:str,cities:cities},function(data){
		var json = $.parseJSON(data);
		if(json.response['error_code'] != '301')
			{
			$("#hotels_box").html("");
			var htm="";
			for(var i=0;i<json.response.length;i++)
				{
				htm+="<label><input type='checkbox' class='hotel' id='hotel_"+json.response[i].id+"'/> "+json.response[i].title+" "+json.response[i].stars+"*</label><br/>";
				}
			$("#hotels_box").html(htm);
			}
			else
				{
				$("#hotels_box").html("");
				}
			});
		}
		else
			{
			get_hotels(cities);
			}
	}
//Запрос к серверной части для получения списка городов, при смене страны в поиске
function get_city(country)
	{
	$.post('./system/api.php',{act:'get_city',country:country},function(data){
	var json = $.parseJSON(data);
	if(json.response['error_code'] != '301')
		{
		$("#all_cities").prop("checked",true);
		$("#city_box").html("");
		var htm="";
		var cities = [];
		for(var i=0;i<json.response.length;i++)
			{
			htm+="<label><input type='checkbox' class='city' id='city_"+json.response[i].id+"' checked/> "+json.response[i].city+"</label><br/>";
			cities.push(json.response[i].id);
			}
		$("#city_box").html(htm);
		get_hotels(cities);
		}
		else
			{
			alert("Неверный запрос");
			}
		});
	}
//Запрос к серверной части для получения результатов подбора тура по всем параметрам конфигуратора
function search_tour(hotels,cities,food,rooms,checkin,departure,price_from,price_to)
	{
	if(hotels.length<1 && cities.length<1)
		{
		alert("Нужно выбрать хотя-бы один город");
		return;
		}
	$.post('./system/api.php',{act:'search_tour',hotels:hotels,cities:cities,food:food,rooms:rooms,checkin:checkin,departure:departure,price_from:price_from,price_to:price_to},function(data){
		$("#results").html("<img src='/images/loading.gif' width='50px'/>");
		if(data=="no")
			{
			$("#results").html("Произошла ошика. Напоминаем, что дата поездки не может превышать 21 день. Так же возможно длительность поездки или сумма - отрицательное кол-во дней.<br/>Попробуйте подобрать параметры, соответствующие действительности");
			return;
			}
		var json = $.parseJSON(data);
		if(json.response['error_code'] != '301' && json.price.response['error_code'] != '301' && json.food.response['error_code'] != '301' && json.rooms.response['error_code'] != '301')
			{
			var htm="";
			var food_arr = ["Не выбрано","Без питания","Только завтрак","Завтрак и ужин","Завтрак, обед, ужин","Все включено","Ультра все включено"];
			var rooms_arr = ["Не выбрано","Для одного","Для двоих","Для двоих, разные кровати","Для двоих,с детьми"];
			for(var i=0;i<json.response.length;i++)
				{
				var f_food = 0;
				var f_rooms = 0;
				var f_price = 0;
				var f_img = "";
				for(var ii=0;ii<json.price.response.length;ii++)
					{
					if(json.price.response[ii].id==json.response[i].id)
						{
						f_price = json.price.response[ii].total;
						}
					}
				if(f_price==0) continue;
				for(var ii=0;ii<json.food.response.length;ii++)
					{
					if(json.food.response[ii].id==json.response[i].id && (food==0 || food==json.food.response[ii].food))
						{
						f_food = json.food.response[ii].food;
						}
					}
				if(f_food==0) continue;
				for(var ii=0;ii<json.rooms.response.length;ii++)
					{
					if(json.rooms.response[ii].id==json.response[i].id && (rooms==0 || rooms==json.rooms.response[ii].rooms))
						{
						f_rooms = json.rooms.response[ii].rooms;
						}
					}
				if(f_rooms==0) continue;
				for(var ii=0;ii<json.img.response.length;ii++)
					{
					if(json.img.response[ii].id==json.response[i].id)
						{
						f_img = json.img.response[ii].image;
						}
					}
				if(f_img=="") f_img="0.jpg";
				var ratio=1;
				switch (f_rooms)
					{
					case '1':
						ratio+=0;
					break;
					case '2':
						ratio*=2;
					break;
					case '3':
						ratio*=2;
					break;
					case '4':
						ratio*=2.5;
					break;
					default:
						ratio=0;
					break;
					}
				if(ratio!=0)
					{
					switch (f_food)
						{
						case '1':
							ratio+=0;
						break;
						case '2':
							ratio+=0.05;
						break;
						case '3':
							ratio+=0.07;
						break;
						case '4':
							ratio+=0.1;
						break;
						case '5':
							ratio+=0.15;
						break;
						case '6':
							ratio+=0.20;
						break;
						default:
							ratio=0;
						break;
						}
					}
				f_price = parseInt(f_price);
				f_price = f_price*ratio;
				if(f_price>0 && f_price>price_from && f_price<price_to)
					{
					htm+="<div class='result-box'>";
						htm+="<div class='result-left'>";
							htm+="<h3><a href='#' onclick='return false;' class='info' id='info_"+json.response[i].id+"'>"+json.response[i].title+" "+json.response[i].stars+"*</a> ("+json.response[i].city+") </h3>";
							htm+="<img src='/images/upload/"+f_img+"' width='200' height='150' align='top'/> ";
							htm+="<b>Дата поездки:</b> с <span class='checkin'>"+checkin+"</span> по <span class='departure'>"+departure+"</span><br/>";
							htm+="<b>Питание:</b> "+food_arr[f_food]+"<br/>";
							htm+="<b>Размещение:</b> "+rooms_arr[f_rooms]+"<br/>";
						htm+="</div>";
						htm+="<div class='result-right'>";
							htm+="<span class='price'>"+parseInt(f_price)+" руб.</span> <br/>";
							htm+="<a href='#' onclick='return false;' class='order' id='order_"+json.response[i].id+"'>Оформить</a><br/>";
						htm+="</div>";
					htm+="</div>";
					}
				}
			if(htm!="")
				{
				$("#results").html("<h2>Результат подбора:</h2>");
				$("#results").append(htm);
				$("html,body").stop().animate({scrollTop:670}, '500');
				}
			else
				{
				$("#results").html("По вашему запросу отсутствуют туры в данный момент, попробуйте изменить параметры поиска.");
				}
			}
			else
				{
				$("#results").html("По вашему запросу отсутствуют туры в данный момент, попробуйте изменить параметры поиска.");
				}
		});
	}
//Запрос к серверной части для получения полной информации об отеле
function hotel_info(id)
	{
	$.post('./system/api.php',{act:'hotel_info',id:id},function(data){
		var json = $.parseJSON(data);
		if(json.response['error_code'] != '301')
			{
			var parking =["Бесплатная, при отеле","Платная, при отеле","Бесплатная, общественная","Платная, общественная","Нет"];
			var food_arr = ["Не выбрано","Без питания","Только завтрак","Завтрак и ужин","Завтрак, обед, ужин","Все включено","Ультра все включено"];
			var rooms_arr = ["Не выбрано","Для одного","Для двоих","Для двоих, разные кровати","Для двоих,с детьми"];
			var htm="<div id='close'></div>";
			htm+="<div class='info-left'>";
				htm+="<div id='img-block'>";
					htm+="<img src='/images/upload/"+json.img.response[0].image+"'/>";
				htm+="</div><br class='cl'/>";
				for(var i=0;i<json.img.response.length;i++)
					{
					htm+="<div class='img-small-block'>";
					htm+="<img src='/images/upload/"+json.img.response[i].image+"'/>";
					htm+="</div>";
					}
				htm+="<br class='cl'/><div id='map'></div><br/>";
				htm+="<b>Адрес:</b> "+json.response[0].address;
			htm+="</div>";
			htm+="<div class='info-right'>";
				htm+="<h1>"+json.response[0].title+" "+json.response[0].stars+"*</h1>";
				htm+="<b>"+json.response[0].country+" - "+json.response[0].city+"</b><br/><br/>";
				htm+="<b>Заезд:</b> "+json.response[0].checkin+" / <b>Выезд:</b> "+json.response[0].departure+"<br/>";
				htm+="<div class='info-block'>";
					htm+="<div class='head'>Питание и номера</div>";
					htm+="<div class='body'>";
					htm+="<div class='column'><b>Питание</b><br/><ul>";
						for(var i=0;i<json.food.response.length;i++)
							{
							htm+="<li>"+food_arr[json.food.response[i].food]+"</li>";
							}
					htm+="</ul></div>";
					htm+="<div class='column'><b>Номера</b><br/><ul>";
						for(var i=0;i<json.rooms.response.length;i++)
							{
							htm+="<li>"+rooms_arr[json.rooms.response[i].rooms]+"</li>";
							}
					htm+="</ul></div>";
					htm+="</div>";
				htm+="</div>";
				htm+="<div class='info-block'>";
					htm+="<div class='head'>Услуги</div>";
					htm+="<div class='body'>";
					htm+="<div class='column'><b>Интернет</b><br/>";
						htm+=json.response[0].internet;
					htm+="</div>";
					htm+="<div class='column'><b>Животные</b><br/>";
						htm+=json.response[0].animals;
					htm+="</div>";
					htm+="<div class='column'><b>Парковка</b><br/>";
						htm+=parking[json.response[0].parking-1];
					htm+="</div>";
					htm+="</div>";
				htm+="</div>";
				htm+="<div class='info-block'>";
					htm+="<div class='head'>Описание</div>";
					htm+="<div class='body'>"+json.response[0].about+"</div>";
				htm+="</div>";
				htm+="<div class='info-block'>";
					htm+="<div class='head'>Вокруг отеля</div>";
					htm+="<div class='body'>"+json.response[0].outdoors+"</div>";
				htm+="</div>";
				htm+="<div class='info-block'>";
					htm+="<div class='head'>Сервис</div>";
					htm+="<div class='body'>"+json.response[0].services+"</div>";
				htm+="</div>";
			htm+="</div>";
			$("#modal").html(htm);
			$("#modal").css({"left":$(window).width()/2-500,"top":$(window).scrollTop()+50});
			$("#shadow").show();
			$("#modal").show();
			$("#map").html(" ");
			ymaps.ready(init);
			function init() {
				var myMap = new ymaps.Map('map', {
					center: [55.753994, 37.622093],
					zoom: 9,
					controls: ['smallMapDefaultSet']
				});
				ymaps.geocode(json.response[0].address, {
					results: 1
				}).then(function (res) {
						var firstGeoObject = res.geoObjects.get(0),
						coords = firstGeoObject.geometry.getCoordinates(),
						bounds = firstGeoObject.properties.get('boundedBy');
						myMap.geoObjects.add(firstGeoObject);
						myMap.setBounds(bounds, {е.
						checkZoomRange: true
						});
					});
			}
			}
			else alert("Такого отеля не найдено");
		});
	}
//Функция построения формы бронирования тура
function order_info(id,title,checkin,departure,image,price)
	{
	var htm="<div id='close'></div>";
	htm+="<div class='info-left'>";
		htm+="<div id='img-block'>";
			htm+="<img src='"+image+"'/>";
		htm+="</div><br/>";
		htm+="<b>Дата:</b> "+checkin+" - "+departure+"<br/>";
		htm+="<span class='price' style='margin-top:5px;'>"+price+" руб.</span>";
	htm+="</div>";
	htm+="<div class='info-right'>";
	htm+="<h1>"+title+"</h1><br/><h3>Бронирование тура</h3>";
	htm+="Имя: <input type='text' id='name'/><br/>";
	htm+="Телефон: <input type='text' id='phone'/><br/>";
	htm+="<a href='#' class='final' onclick='return false;'>Забронировать</a>";
	$("#modal").html(htm);
	$("#modal").css({"left":$(window).width()/2-500,"top":$(window).scrollTop()+50});
	$("#shadow").show();
	$("#modal").show();
	$("body").on("click", ".final", function()
		{
		var name=$("#name").val();
		var phone=$("#phone").val();
		$.post('./system/api.php',{act:'order',id:id,title:title,checkin:checkin,departure:departure,price:price,name:name,phone:phone},function(data){
			if(data=="ok")
				$("#modal").html("<div id='close'></div><h1>Ваш заказ успешно сформирован! Ожидайте звонка от нашего менеджера.</h1>");
			else
				alert("Что-то пошло не так, попробуйте повторить попытку позже.");
			});
		});
	}
//Функции использыемые после полной загрузки страницы
$(function()
	{
	//Инициальзация календаря при клике на поля ввода дат поездки
	$.datepicker.setDefaults( $.datepicker.regional[ 'ru' ] );
	$.datepicker.formatDate( "dd.mm.yy", new Date() );
	$('#checkin').datepicker({
		minDate: 3
	});
	$('#departure').datepicker({
		minDate: 7
	});
	//Вызов функции запроса к БД при смене страны
	$("#country").change(function()
		{
		var country = $("#country option:selected").text();
		get_city(country)
		});
	//Вызов функции запроса к БД при выборе города
	$("body").on("click", ".city", function()
		{
		var cities = [];
		$(".city").each(function()
			{
			if($(this).is(":checked"))
				cities.push($(this).attr("id").split("_")[1]);
			});
		get_hotels(cities)
		});
	//Вызов функции запроса к БД при выборе чекбокса "Все города"
	$("#all_cities").change(function()
		{
		var cities = [];
		if($(this).is(":checked"))
			{
			$(".city").prop("checked",true);
			$(".city").each(function()
				{
				if($(this).is(":checked"))
					cities.push($(this).attr("id").split("_")[1]);
				});
			get_hotels(cities)
			}
			else
				{
				$("#hotels_box").html("");
				$(".city").prop("checked",false);
				}
		});
	//Вызов функции запроса к БД при вводе символа в поле ввода "Название отеля"
	$("#hotel_search").keyup(function()
		{
		var str = $(this).val();
		var cities = [];
		$(".city").each(function()
			{
			if($(this).is(":checked"))
				cities.push($(this).attr("id").split("_")[1]);
			});
		search(str,cities);
		});
	//Вызов функции запроса к БД при клике на кнопку "Подобрать тур"
	$("#search_button").click(function()
		{
		var hotels=[];
		var cities=[];
		var food = $("#food :selected").val();
		var rooms = $("#rooms :selected").val();
		var checkin = $("#checkin").val();
		var departure = $("#departure").val();
		var price_from = $("#price_from").val();
		var price_to = $("#price_to").val();
		$(".hotel").each(function()
			{
			if($(this).is(":checked"))
				hotels.push($(this).attr("id").split("_")[1]);
			});
		$(".city").each(function()
			{
			if($(this).is(":checked"))
				cities.push($(this).attr("id").split("_")[1]);
			});
		search_tour(hotels,cities,food,rooms,checkin,departure,price_from,price_to);
		});
	//Вызов функции запроса к БД при клике на название отеля (для информации о нем) в развернутой области поиска, где туры уже подобранны.
	$("body").on("click", ".info", function()
		{
		var id = $(this).attr("id").split("_")[1];
		hotel_info(id);
		});
	//Клик на крестике всплывающего окна и тени за окном
	$("body").on("click", "#close,#shadow", function()
		{
		$("#modal").hide();
		$("#shadow").hide();
		});
	//Смена изображений в информации об отеле
	$("body").on("click", ".info-left .img-small-block img", function()
		{
		$(".info-left #img-block img").attr("src",$(this).attr("src"));
		});
	//Раскрытие информационных блоков в подробной информации об отеле
	$("body").on("click", ".info-right .info-block .head", function()
		{
		$(".info-right .info-block .body").slideUp("200");
		$(this).parent(".info-block").find(".body").stop().slideDown(200);
		});
	//Вызов функции запроса к БД при клике на конпку "Забронировать"
	$("body").on("click", ".order", function()
		{
		var id = $(this).attr("id").split("_")[1];
		var title = $(this).parent().parent().find(".info").text();
		var checkin = $(this).parent().parent().find(".checkin").text();
		var departure = $(this).parent().parent().find(".departure").text();
		var image = $(this).parent().parent().find("img").attr("src");
		var price = parseInt($(this).parent().find(".price").text());
		order_info(id,title,checkin,departure,image,price);
		});
	});