<?php
   $newStatus=$_GET["newStatus"];
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

    // a function to replace a node in the XML tree, given the DOM Object, 
   // the tag name to use, the new text value, the parent node and the current node
   function replaceNode($xml, $tagName, $textValue, $parentNode, $nodeToReplace) {
      $newNode=$xml->createElement("$tagName");
      $newTextNode=$xml->createTextNode("$textValue");
      $newNode->appendChild($newTextNode);
      $parentNode->replaceChild($newNode,$nodeToReplace);
    }
    $xml=getXML("notes.xml");

    // get document element
    $root= $xml->documentElement; //collection
    $notes= $root->childNodes->item(3); //notes
      
	// find the note to edit   
	foreach ($notes->childNodes as $note) {
		if ($note->getAttribute('id') == $noteID) {   
			// replace the status node with the new value
			replaceNode($xml, "status", $newStatus, $note, $note->childNodes->item(6)); 
			$status = $note->childNodes->item(6);
			// save the changes
			$xml->save("notes.xml");
			echo "Note status has been updated to ".$status -> nodeValue;
		}
	}
 ?>