<?php

/**
 * Simple script to extract files from a .tar archive
 *
 * @param string $file The .tar archive filepath
 * @param string $dest [optional] The extraction destination filepath, 
defaults to "./"
 * @return boolean Success or failure
 */
function untar($file, $dest = "./") {
	if (!is_readable($file)) return false;

	$filesize = filesize($file);
	if ($filesize <= 512*4) return false;	// Minimum 4 blocks
	
	if (!preg_match("/\/$/", $dest)) {
		$dest .= "/";						
// Force trailing slash
	}
	
	if (!file_exists($dest)) {
		if (!mkdir($dest, 0777, true)) {
			return false;
		}
	}
	
	$fh = fopen($file, 'rb');
	$total = 0;
	while (false !== ($block = fread($fh, 512))) {
		
		$total += 512;
		$meta = array();
		$meta['filename'] = trim(substr($block, 0, 99));
		$meta['mode'] = octdec((int)trim(substr($block, 100, 
8)));
		$meta['userid'] = octdec(substr($block, 108, 8));
		$meta['groupid'] = octdec(substr($block, 116, 8));
		$meta['filesize'] = octdec(substr($block, 124, 12));
		$meta['mtime'] = octdec(substr($block, 136, 12));
		$meta['header_checksum'] = octdec(substr($block, 148, 
8));
		$meta['link_flag'] = octdec(substr($block, 156, 1));
		$meta['linkname'] = trim(substr($block, 157, 99));
		$meta['databytes'] = ($meta['filesize'] + 511) & ~511;
		
		if ($meta['link_flag'] == 5) {
			mkdir($dest . $meta['filename'], 0777, true);
			chmod($dest . $meta['filename'], $meta['mode']);
		}
		
		if ($meta['databytes'] > 0) {
			$block = fread($fh, $meta['databytes']);
			$data = substr($block, 0, $meta['filesize']);

			if (false !== ($ftmp = fopen($dest . 
$meta['filename'], 'wb'))) {
				fwrite($ftmp, $data);
				fclose($ftmp);
				touch($dest . $meta['filename'], 
$meta['mtime'], $meta['mtime']);
				
				if ($meta['mode'] == 0744) { 
$meta['mode'] = 0644; }
				chmod($dest . $meta['filename'], 
$meta['mode']);
			}
			$total += $meta['databytes'];
		}
		
		if ($total >= $filesize-1024) {
			return true;
		}
	}
}


?>
