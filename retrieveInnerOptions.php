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
   $nextSenderIDNode= $root->childNodes->item(1); // senderID
   $nextRecipientIDNode= $root->childNodes->item(2); //recipientID
   $notes= $root->childNodes->item(3); //notes
   
	echo "You have chosen to search by: $searchOption<br>";
	switch ($searchOption) {
		case "Status": {	
			echo "<select id = 'innerSearch' name = 'innerSearch' onchange='displayNoteResult(this.value)'>
					<option value =''>-- Select Status --</option>
					<option value = 'New'>New</option>
					<option value = 'Current'>Current</option>
					<option value = 'Historic'>Historic</option>
					</select>";
		} break;
		case "ID": {
			echo "<select id = 'innerSearch' name = 'innerSearch' onchange='displayNoteResult(this.value)'><option value =''>--Select ID --</option>";

			foreach ($notes->childNodes as $note) { 
				$noteID =  $note->getAttribute('id'); 
				echo "<option value='$noteID'>ID: $noteID</option>";
			}
			echo "</select>";
		} break;
		case "Date": {
			//get all dates of notes
			echo "<select id = 'innerSearch' name = 'innerSearch' onchange='displayNoteResult(this.value)'><option value =''>--Select Date --</option>";

			foreach ($notes->childNodes as $note) { 
				 $sendingDate = $note -> childNodes -> item(3);
				echo "<option value='$sendingDate->nodeValue'>$sendingDate->nodeValue</option>";
			}
			echo "</select>";
		} break;
		case "Sender": {
			echo "<select id = 'innerSearch' name = 'innerSearch' onchange='displayNoteResult(this.value)'><option value =''>--Select Sender --</option>";		 
			foreach ($notes->childNodes as $note) { 
				$sender = $note -> childNodes -> item(1);
				$senderID = $sender -> getAttribute('id');
				$senderTitle = $sender -> childNodes -> item(0);
				$senderFirstName = $sender -> childNodes -> item(1);
				$senderSurname = $sender -> childNodes -> item(2);
				$senderFullName = $senderTitle -> nodeValue. " ".$senderFirstName -> nodeValue." ".$senderSurname -> nodeValue;
				echo "<option value='".$senderID."'>$senderFullName -- $senderID</option>";
			}
			echo "</select>";
		} break;
		case "Recipient": {
			echo "<select id = 'innerSearch' name = 'innerSearch' onchange='displayNoteResult(this.value)'><option value =''>--Select Recipient --</option>";
			foreach ($notes->childNodes as $note) {
				$recipients = $note->childNodes->item(2);  
				foreach ($recipients->childNodes as $recipient) { //retrieve all recipients 
					$recipientID = $recipient -> getAttribute('id');
					$recipientTitle = $recipient -> childNodes -> item(0);
					$recipientFirstName = $recipient -> childNodes -> item(1);
					$recipientSurname = $recipient -> childNodes -> item(2);
					$recipientFullName = $recipientTitle -> nodeValue." ".$recipientFirstName -> nodeValue." " .$recipientSurname -> nodeValue;
					echo "<option value='".$recipientID."'>$recipientFullName -- $recipientID</option>";
				}
			}
			echo "</select>";
		} break;
	}//switch
 ?>