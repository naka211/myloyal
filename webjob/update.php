<?php
$number1 = rand(1, 999);
function PingController($number){
	$number2 = $number + 100;
	echo $number2;
}

PingController($number1);
?>