<?php
   $searchOption=$_GET["searchOption"];
   $file = "notes.xml";
   $fp = fopen($file, "rb") or die("cannot open file");
   $str = fread($fp, filesize($file));

   $xml = new DOMDocument();
   $xml->formatOutput = true;
   $xml->preserveWhiteSpace = false;
   $xml->loadXML($str) or die("Error");

    // get document element
   $root= $xml->documentElement; //collection
   $nextIDNode=$root->childNodes->item(0); //next note ID
   $nextSenderIDNode= $root->childNodes->item(1); //next sender ID
   $nextRecipientIDNode= $root->childNodes->item(2); //next recipient ID
   $notes= $root->childNodes->item(3); //notes
	
   $notesFound = 0;
   foreach ($notes->childNodes as $note) { // get individual notes
	   $notesFound++;
	   $noteID =  $note->getAttribute('id'); 
	   $noteTitle = $note -> childNodes -> item(0); 
	   $sender = $note -> childNodes -> item(1);
	   $senderID = $sender ->getAttribute('id');
		  $senderTitle = $sender -> childNodes -> item(0);
		  $senderFirstName = $sender -> childNodes -> item(1);
		  $senderSurname = $sender -> childNodes -> item(2);
       $recipients = $note->childNodes->item(2);  
	   $sendingDate = $note -> childNodes -> item(3);
	   $message = $note -> childNodes -> item(4);
	   $link = $note -> childNodes -> item(5);
		  $site = $link -> childNodes -> item(0);
	   $status = $note -> childNodes -> item(6);
	   $url = $site -> nodeValue;
	   
	   	foreach ($recipients->childNodes as $recipient) { //retrieve all recipients 
		   $recipient = $recipients -> childNodes -> item(0);
		   $recipientID = $recipient -> getAttribute('id');
		   $recipientTitle = $recipient -> childNodes -> item(0);
		   $recipientFirstName = $recipient -> childNodes -> item(1);
		   $recipientSurname = $recipient -> childNodes -> item(2);
		   $recipientFullName = $recipientTitle->nodeValue." ".$recipientFirstName -> nodeValue." " .$recipientSurname -> nodeValue;
		}
	   
	   $senderFullName = $senderTitle -> nodeValue. " ".$senderFirstName -> nodeValue." ".$senderSurname -> nodeValue;
	  
		if($searchOption == $noteID || $searchOption == $status -> nodeValue || $searchOption == $sendingDate -> nodeValue || $searchOption == $senderID || $searchOption == $recipientID){
			echo "<br><div id = '$noteID'>Subject: ".$noteTitle -> nodeValue. "<br>";
			foreach ($recipients->childNodes as $recipient) { //retrieve all recipients 		
				 $recipientTitle = $recipient -> childNodes -> item(0);
			     $recipientFirstName = $recipient -> childNodes -> item(1);
			     $recipientSurname = $recipient -> childNodes -> item(2);
			     $recipientName = $recipientTitle->nodeValue." ".$recipientFirstName -> nodeValue." " .$recipientSurname -> nodeValue;
				echo "<div id = 'recipientFullname'>To: ".$recipientName."</div>";
			}
			echo "<div id = 'senderFullname'>From: ".$senderFullName."</div>";
			echo "<div id = 'date'>".$sendingDate->nodeValue."</div>";
			echo "<div id = 'message'>".$message -> nodeValue. "</div>";
			echo "<div id = 'link'><a href = '$url'>$url</a></div>";
			echo "<div id = 'status'>Note Status: ".$status -> nodeValue. "</div>";
			echo "<input type='button' id ='deleteNote' value = 'Delete Note'  onclick ='deleteNote($noteID)'>";
			echo "<select id = 'editStatus' name = 'editStatus' onchange='editStatus(this.value,$noteID)'>
						<option value =''>--Mark note as: --</option>
						<option value ='Historic'>Historic</option>
						<option value ='Current'>Current</option>
						<option value ='New'>New</option>
					 </select><br></div>";

		}//if			
	} if ($notesFound==0) echo "No matching notes found";
   
 ?>