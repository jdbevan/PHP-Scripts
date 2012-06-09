<?php
/**
 * Returns index of $haystack whereby $needle is found at $key
 *
 * @param string/boolean/null/float/int $needle The search value
 * @param array $haystack The 2D array in which to search
 * @param string/int $key The index key to search in the 2D array for $needle
 * @param boolean $strict [optional]<br>Enable strict comparison, false by default
 * @param boolean $case_sensitive [optional]<br>Enabled case-sensitive comparison, true by default
 * @return mixed False on failure, otherwise the index of $haystack in which $needle is found at $key
 */
function array_2d_search($needle, $haystack, $key, $strict = false, $case_sensitive = true) {
	if (is_resource($needle) and $needle !== null) return false;
	if (is_array($needle) or is_object($needle)) return false;
	if (!is_array($haystack)) return false;
	if (!is_string($key) and !is_int($key)) return false;

	foreach ($haystack as $index=>$array) {
		if (!is_array($array)) continue;
		
		if ($strict) {
			if ($case_sensitive and $array[$key] === $needle) {
				return $index;
			} else if (!$case_sensitive and strtoupper($array[$key]) === strtoupper($needle)) {
				return $index;
			}
		} else {
			echo "::$index " . var_export($array[$key], true), " ", var_export($needle,true), "\n";
			if ($case_sensitive and $array[$key] == $needle) {
				return $index;
			} else if (!$case_sensitive and strtoupper($array[$key]) == strtoupper($needle)) {
				return $index;
			}
		}
	}
	return false;
}

/**
 * Return all indexes of $haystack whereby $needle is found at $key
 *
 * @param string/boolean/null/float/int $needle The search value
 * @param array $haystack The 2D array in which to search
 * @param string/int $key The index key to search in the 2D array for $needle
 * @param boolean $strict [optional]<br>Enable strict comparison, false by default
 * @param boolean $case_sensitive [optional]<br>Enabled case-sensitive comparison, true by default
 * @return mixed False on failure, otherwise an array of indexes of $haystack in which $needle is found at $key
 */
function array_2d_keys($needle, $haystack, $key, $strict = false, $case_sensitive = true) {
	if (is_resource($needle) and $needle !== null) return false;
	if (is_array($needle) or is_object($needle)) return false;
	if (!is_array($haystack)) return false;
	if (!is_string($key) and !is_int($key)) return false;

	$keys = array();
	foreach ($haystack as $index=>$array) {
		if (!is_array($array)) continue;
		
		if ($strict) {
			if ($case_sensitive and $array[$key] === $needle) {
				$keys[] = $index;
			} else if (!$case_sensitive and strtoupper($array[$key]) === strtoupper($needle)) {
				$keys[] = $index;
			}
		} else {
			if ($case_sensitive and $array[$key] == $needle) {
				$keys[] = $index;
			} else if (!$case_sensitive and strtoupper($array[$key]) == strtoupper($needle)) {
				$keys[] = $index;
			}
		}
	}
	return (count($keys)>0) ? $keys : false;
}

?>
