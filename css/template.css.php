<?php 

// variables
$baseurl = $_GET['baseurl'];
$path = getcwd();

// initialize ob_gzhandler to send and compress data
ob_start ("ob_gzhandler");
// initialize compress function for whitespace removal
ob_start("compress");
// required header info and character set
header("Content-type:text/css; charset=UTF-8");
// cache control to process
header("Cache-Control:must-revalidate");
// duration of cached content (1 hour)
$offset = 60 * 60 ;
// expiration header format
$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s",time() + $offset) . " GMT";
// send cache expiration header to broswer
header($ExpStr);
// begin function compress
function compress($buffer) {
	// remove comments
	$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
	// remove tabs, spaces, new lines, etc.        
	$buffer = str_replace(array("\r\n","\r","\n","\t",'  ','    ','    '),'',$buffer);
	// remove unnecessary spaces        
	$buffer = str_replace('{ ', '{', $buffer);
	$buffer = str_replace(' }', '}', $buffer);
	$buffer = str_replace('; ', ';', $buffer);
	$buffer = str_replace(', ', ',', $buffer);
	$buffer = str_replace(' {', '{', $buffer);
	$buffer = str_replace('} ', '}', $buffer);
	$buffer = str_replace(': ', ':', $buffer);
	$buffer = str_replace(' ,', ',', $buffer);
	$buffer = str_replace(' ;', ';', $buffer);
	$buffer = str_replace(';}', '}', $buffer);
	
	return $buffer;
}

// less compiler
require_once 'less.php/Less.php';
$less_files = array( $path.'/template.less' => $baseurl.'/' );
$options = array( 'cache_dir' => $path.'/cache/' );
$css_file_name = Less_Cache::Get( $less_files, $options );
$compiled = file_get_contents( $baseurl.'/cache/'.$css_file_name );
require('cache/'.$css_file_name);

// load system stylesheets for system messages and default buttons
require("../../../media/system/css/system.css");
require("../../system/css/system.css");
require("../../system/css/general.css");

?>