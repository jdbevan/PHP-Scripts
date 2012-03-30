<?php
/**
 * Return last element from array without removing that element from array.
 *
 * @param array $array The array to get the last element from
 * @return mixed False if $array is not an array or an empty array, else the key of the last element of the array.
 */ 
function array_peek($array) {
	if (!is_array($array)) return false;
	if (count($array)<1) return false;
	
	$last_key = array_pop(array_keys($array));
	return $array[$last_key];
}
?>
