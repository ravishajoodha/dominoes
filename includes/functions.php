<?php
#-------------------------------
#	ravishajoodha@gmail.com
#	2020-04-21
#-------------------------------

//removes the array element that matches both values
function _remove_arr_elm($arr, $val0, $val1){
	foreach ($arr as $i => $array){
		if($array['L']==$val0 AND $array['R']==$val1){
			unset($arr[$i]);
		}
	}	
	return $arr;
}
?>