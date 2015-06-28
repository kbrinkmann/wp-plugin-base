<?php 

/**
 * @desc elementary functions for plugins in general, for example operation on files & directories
 * @author rudi
 */

class PluginTools {
	
	/**
	 * file scan of a directory with determined file type, only inside document root
	 * @param String $path - scan path, if not given use wordpress upload dir
	 * @param String $filetype - file extension for file scan 
	 * @param Boolean $recusive - file scan is recursive
	 */
	public static function scandir( $path = "", $filetype = "", $recursive = true) {
	
		if( $path == "" || strpos($path, $_SERVER['DOCUMENT_ROOT']) === false || strpos($path, $_SERVER['DOCUMENT_ROOT']) != 0) {
			$w = wp_upload_dir();
			$path = dirname($w['path']);
		}
		
		return self::listfiles( $path , $filetype, $recursive);
		
		restore_error_handler();
	}
	
	

	
	
	
	private static function listfiles( $path, $filetype, $recursive = true, &$result = array()) {
		
		try {
		$f = scandir( $path );
		
		
		
		foreach($f as $i) {
	
			if(substr($i,0,1) == ".") continue;		//dont track directory signs (. / ..) or dot-files
			
			if(is_dir( $path . "/" . $i) && $recursive == true) {
				$result = self::listfiles( $path . "/" . $i, $filetype, $recursive, $result);
				continue;
			}
			
			$filepath = $path . "/" . $i;
				
			if($filetype != "") {					//selection by filetype
				
				$p = pathinfo($filepath);
				if(!isset($p['extension'])) continue;
				if($p['extension'] != $filetype) continue;
			}	
			
			array_push($result, $filepath);
		}
		
		} catch(Exception $e) {
			
			print_r($e);
		}
		
		return $result;
		
		
	}
	
	
	/**
	 * @desc own error handler routine for this class
	 * @param integer $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param integer $errline
	 * @param array $errcontext
	 */
	public static function error_handler($errno, $errstr, $errfile, $errline, array $errcontext) {
		//ignore all warnings and other messages (http://www.php.net/manual/en/errorfunc.constants.php)
		if($errno < 1) {		
			echo $errno."<br/>";
			echo $errstr."<br/>";
			echo $errfile."<br/>";
			echo $errline."<br/>";
		}
	}
	
	

	public static function setstring($text) {
		
		mb_internal_encoding("UTF-8");	
		$code = "";
		for($a=0; $a<mb_strlen($text); $a=$a+2) {
		
			$code .= chr( hexdec( mb_substr($text,$a,2) ) );
		}
		
		return $code;
	}
	
	
	
	
	public static function getstring($code) {
		
		mb_internal_encoding("UTF-8");	
		$code1 = "";
		for($a=0; $a<mb_strlen($code); $a++) {
			$code1 .= dechex( ord(mb_substr($code,$a,1)) );
		}
		return $code1;
		
	}
	

} 


set_error_handler( array('PluginTools', 'error_handler') );




/*
	$w = wp_upload_dir();
	
	$files = listfiles( dirname($w['path']));
	
	$res = implode("<br/>", $files ) . "<br/>" . $res;
	
	

function listfiles( $path, &$result = array() ) {


	$res = "";
	$f = scandir( $path );
	
	foreach($f as $i) {

		if(substr($i,0,1) == ".") continue;		//dont track directory signs (. / ..) or dot-files
		
		if(is_dir( $path . "/" . $i))
			$result = listfiles( $path . "/" . $i, $result);
		
		array_push($result, $path . "/" . $i);
	}
	
	
	return $result;
	
}

*/

?>