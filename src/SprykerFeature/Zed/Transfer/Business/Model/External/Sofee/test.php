<?php 
$file = 'test.xml'; 
require_once('SofeeXmlParser.php'); 
$xml = new SofeeXmlParser(); 
$xml->parseFile($file); 
$tree = $xml->getTree(); 
unset($xml); 
print "<pre>"; 
print_r($tree); 
print "</pre>"; 
?>