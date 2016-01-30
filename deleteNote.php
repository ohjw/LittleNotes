<?php
    $noteID = $_GET["noteID"]; 
	
    // a function to open the XML file, read it and return the XML DOM Object
 	function getXML($file) {
	   $fp = fopen($file, "rb") or die("cannot open file");
	   $str = fread($fp, filesize($file));
	   $xml = new DOMDocument();
	   $xml->formatOutput = true;
	   $xml->preserveWhiteSpace = false;
	   $xml->loadXML($str) or die("Error");
	   return $xml;
	}
	$xml=getXML("notes.xml"); 
	
    // get document element
    $root= $xml->documentElement; 
    $notes= $root->childNodes->item(3); //notes

    // find note and delete it
    foreach($notes->childNodes as $note) {
        if ($note->getAttribute("id")==$noteID) { 
           $notes->removeChild($note);
        }
    }
    $xml->save("notes.xml");
?>