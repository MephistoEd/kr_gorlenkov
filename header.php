<?php 
//Подключаем конфигурационные файлы и файлы с функциями php
require_once('./system/config.php'); 
require_once('./system/functions.php'); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<!-- Выбираем кодировку, подключаем библиотеки, файлы со скриптами и стилями -->
<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="./system/set.js?<?echo rand(1000,9999);?>"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src = "./admin/system/jquery-ui-rus.js" type = "text/javascript"></script>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic&subset=latin,cyrillic,greek-ext,greek,vietnamese,cyrillic-ext' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="./css/style.css" />
<title>Туристический портал</title>
</head>
<body>
<!-- Yandex.Metrika counter --> <script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter37265595 = new Ya.Metrika({ id:37265595, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true, ut:"noindex" }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/37265595?ut=noindex" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->
<!-- шапка сайта -->
<div id="header">
	<a href="/"><img src="/images/logo1.png" title="Главная страница" alt="Главная страница" /></a>	
	<span>Подбери идеальный тур для себя,<br/> на нашем туристическом портале!</span>
</div>
