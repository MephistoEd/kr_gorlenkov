//Ввод пароля через клавишу "Enter"
$('.enter').keypress(function(e){
      if(e.which == 13){
       $('form#enter').submit();
       }
      });
//Вывод таблички со статусом запроса к БД
function status(stat)
	{
	$(document).ready(function()
		{
		if(stat=='ok')
			{
			$("#saved").fadeIn(350);
			$("body,html").scrollTop(0);
			}
		if(stat=='no')
			{
			$("#error").fadeIn(350);
			$("body,html").scrollTop(0);
			}
		});
	}
//Проверка введеного адреса отеля на яндекс картах
function check_address(address)
	{
	$("#map").html(" ");
	ymaps.ready(init);
	function init() {
		var myMap = new ymaps.Map('map', {
			center: [55.753994, 37.622093],
			zoom: 9,
			controls: ['smallMapDefaultSet']
		});
		ymaps.geocode(address, {
			results: 1
		}).then(function (res) {
				var firstGeoObject = res.geoObjects.get(0),
				coords = firstGeoObject.geometry.getCoordinates(),
				bounds = firstGeoObject.properties.get('boundedBy');
				myMap.geoObjects.add(firstGeoObject);
				myMap.setBounds(bounds, {
				checkZoomRange: true
				});
			});
	}
	}
//Функция запроса к БД для получения цена на определенную дату отеля в "календаре цен"
function getDate(date) {
var hotel_id = $("#ondate").attr("class").split("_")[1];
$.post('./system/api.php',{method:'save',id:'prices_get',hotel_id:hotel_id,ondate:date.toString()},function(data){
	var json = $.parseJSON(data);
	if(json.response['error_code'] != '301')
		$("#price").val(json.response[0].price);
	else
		$("#price").val("");	
	});
$("#ondate").val(date.toString());
}
//Инициализация календаря в редактировании цен отеля
function picker() {
$.datepicker.setDefaults( $.datepicker.regional[ 'ru' ] );
$.datepicker.formatDate( "dd.mm.yy", new Date() );
$('#datepicker').datepicker({
onSelect: function(dateText, inst) {
        var date = $(this).val();
		getDate(date);
		}
});
getDate($("#datepicker").datepicker({ dateFormat: 'dd.mm.yy' }).val());
//Функция сохранения цены на определенную дату отеля, при потере фокуса с поля "цена" в "календаре цен"
$("#price").blur(function()
	{
	var id = "prices_add";
	var hotel_id = $("#ondate").attr("class").split("_")[1];
	var ondate = $("#ondate").val();
	var price = $.trim($("#price").val());
	if(price!="")
		{
		$.post('./system/api.php',{method:'save',id:id,hotel_id:hotel_id,ondate:ondate,price:price},function(data){
							if(data == "ok")
								{
								$("#mess").show();
								setTimeout(function() {$("#mess").fadeOut(500);},2000);
								}
							else
								alert("Что-то пошло не так/\nИли цена указана неверно.");
							});
		}	
	});
}
//Функции при полной загрузке страницы
$(document).ready(function()
{
	$("#check_address").click(function(){check_address($("#address").val());}); //Отправляем введеный адрес на проверку
	//Переадресация при нажатии на кнопку редактирования
	$('.edit_btn').live("click",function()
		{
		var act = $(this).attr('id').split('_')[1];
		var id = $(this).attr('id').split('_')[2];
		window.location.href='./root.php?act='+act+'&mode=edit&id='+id;
		});
	//Запрос к БД на удаление
	$('.del_btn').live("click",function()
		{
		var act = $(this).attr('id').split('_')[1];
		var id = $(this).attr('id').split('_')[2];
		if(!confirm('Вы действительно хотите удалить?'))
				return false;
		$.post('./system/api.php',{method:'save',id:'delete',act:act,ids:id},function(data){
					if(data == "ok")
						{
						$('#del_'+act+'_'+id).parent('td').parent('tr').remove();
						}
					else
						alert('Ошибка');
					});
		});
	//Сортировка входящих данных
	$('.save').click(function()
		{
		var id=$(this).attr('id');
		switch(id)
			{
			//Отправка запроса на добавление туристического направления
			case 'directions_add':
				var country = $('#country').val();
				var city = $('#city').val();
				$.post('./system/api.php',{method:'save',id:id,country:country,city:city},function(data){
					if(data == "ok")
						window.location.href="./root.php?act=directions&status=ok";
					else
						window.location.href="./root.php?act=directions&status=no";
					});
			break;
			//Отправка запроса на редактирование туристического направления
			case 'directions_edit':
				var ids = $('#country').attr('class').split('_')[1];
				var country = $('#country').val();
				var city = $('#city').val();
				$.post('./system/api.php',{method:'save',id:id,ids:ids,country:country,city:city},function(data){
					if(data == "ok")
						window.location.href="./root.php?act=directions&status=ok";
					else
						window.location.href="./root.php?act=directions&status=no";
					});
			break;
			//Отправка запроса на добавление отеля
			case 'hotels_add':
				var directions_id = $("#directions_id option:selected").val();
				var title = $('#title').val();
				var stars = $("input[name=stars]:checked").val();
				var checkin = $('#checkin').val();
				var departure = $('#departure').val();
				var food = [];
				var rooms = [];
				var parking = $("input[name=parking]:checked").val();
				var internet = $('#internet').val();
				var animals = $('#animals').val();
				var address = $('#address').val();
				var about = $('#about').val();
				var outdoors = $('#outdoors').val();
				var services = $('#services').val();
				$(".food").each(function()
					{
					if($(this).is(':checked'))
						food.push($(this).attr("id").split("_")[1]);
					});
				$(".rooms").each(function()
					{
					if($(this).is(':checked'))
						rooms.push($(this).attr("id").split("_")[1]);
					});
				$.post('./system/api.php',{method:'save',
										   id:id,
										   directions_id:directions_id,
										   title:title,
										   stars:stars,
										   checkin:checkin,
										   departure:departure,
										   food:food,
										   rooms:rooms,
										   parking:parking,
										   internet:internet,
										   animals:animals,
										   address:address,
										   about:about,
										   outdoors:outdoors,
										   services:services,
										   },function(data){		   
					if(data == "ok")
						window.location.href="./root.php?act=hotels&status=ok";
					else
						window.location.href="./root.php?act=hotels&status=no";
					});
			break;
			//Отправка запроса на редактирование отеля
			case 'hotels_edit':
				var directions_id = $("#directions_id option:selected").val();
				var ids = $('#title').attr("class").split("_")[1];
				var title = $('#title').val();
				var stars = $("input[name=stars]:checked").val();
				var checkin = $('#checkin').val();
				var departure = $('#departure').val();
				var food = [];
				var rooms = [];
				var parking = $("input[name=parking]:checked").val();
				var internet = $('#internet').val();
				var animals = $('#animals').val();
				var address = $('#address').val();
				var about = $('#about').val();
				var outdoors = $('#outdoors').val();
				var services = $('#services').val();
				$(".food").each(function()
					{
					if($(this).is(':checked'))
						food.push($(this).attr("id").split("_")[1]);
					});
				$(".rooms").each(function()
					{
					if($(this).is(':checked'))
						rooms.push($(this).attr("id").split("_")[1]);
					});
				$.post('./system/api.php',{method:'save',
										   id:id,
										   ids:ids,
										   directions_id:directions_id,
										   title:title,
										   stars:stars,
										   checkin:checkin,
										   departure:departure,
										   food:food,
										   rooms:rooms,
										   parking:parking,
										   internet:internet,
										   animals:animals,
										   address:address,
										   about:about,
										   outdoors:outdoors,
										   services:services,
										   },function(data){		   
					if(data == "ok")
						window.location.href="./root.php?act=hotels&status=ok";
					else
						window.location.href="./root.php?act=hotels&status=no";
					});
			break;
			//Отправка запроса на добавление изображения для отеля
			case 'images_add':
				var hotel_id = $('#hotel').attr('class').split('_')[1];
				var images = $('iframe[name=iframe]').contents().find('#j').html();
				var len = images.length;
				var ph = images.substr(0,len-2);
				var phots = ph.split('||');
				$.post('./system/api.php',{method:'save',id:id,hotel_id:hotel_id,phots:phots},function(data){
				if(data == "ok")
						window.location.href="./root.php?act=hotels&mode=images&hotels_id="+hotel_id+"&status=ok";
					else
						window.location.href="./root.php?act=hotels&mode=images&hotels_id="+hotel_id+"&status=no";
					});
			break;
			//Отправка запроса на изменение позиции изображения для отеля
			case 'images_position':
				var pos=[];
				var ids=[];
				$('.img_position').each(function()
					{
					pos.push($(this).val());
					ids.push($(this).attr('id').split('_')[1]);
					});
				$.post('./system/api.php',{method:'save',id:id,pos:pos,ids:ids},function(data){
						if(data == "ok")
							{
							setTimeout(function(){window.location.reload();}, 1000);
							}
						else
							alert("Что-то пошло не так");
					});
			break;
			}
		});
});