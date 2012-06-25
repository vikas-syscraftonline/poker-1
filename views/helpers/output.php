<?php

class OutputHelper extends AppHelper {

	function summary($text, $length = 400) {
		$output = substr(strip_tags($text), 0, $length); 
		if(strlen(strip_tags($text)) > $length) 
			$output .= '...';
		return $output;
	}

}

?>
