<?php
if (isset($_POST["submit"])) {
	header ('Location: index.html') ;

    $file = "notes.xml";
    $fp = fopen($file, "rb") or die("Error - cannot open XML file");
    $str = fread($fp, filesize($file));

    $xml = new DOMDocument();
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->loadXML($str) or die("Error - cannot load XML data");
 
    // get document element
    $root= $xml->documentElement; //collection
    $nextIDNode=$root->childNodes->item(0); //next note ID
    $nextSenderIDNode= $root->childNodes->item(1); //next sender ID
    $nextRecipientIDNode= $root->childNodes->item(2); //recipient ID
    $notes= $root->childNodes->item(3); //notes

    // find first note element
    $firstNote=$notes->childNodes->item(0);

    // get values for new note element
    $newID=(int)$root->childNodes->item(0)->nodeValue;
	$newSenderID =(int)$root->childNodes->item(1)->nodeValue;
	$newRecipientID =(int)$root->childNodes->item(2)->nodeValue;
	
    $newSubject = $_POST['subject'];
    $newSenderTitle = $_POST['sTitle'];
    $newSenderFirstName = $_POST['sFirstname'];
    $newSenderSurname = $_POST['sSurname'];
  
    $newMessage = $_POST['message'];
    $newLink = $_POST['site'];
    $newLinkDescription = $_POST['siteDescription'];
    $newStatus = $_POST['status'];
   
    $subjectNode=$xml->createElement("subject");
    $subjectTextNode=$xml->createTextNode("$newSubject");
    $subjectNode->appendChild($subjectTextNode);
   
    $senderNode = $xml -> createElement("sender");
    $senderTitleNode=$xml->createElement("title");
    $senderTitleTextNode=$xml->createTextNode("$newSenderTitle");
    $senderTitleNode->appendChild($senderTitleTextNode);
    $senderFirstnameNode=$xml->createElement("firstName");
    $senderFirstNameTextNode=$xml->createTextNode("$newSenderFirstName");
    $senderFirstnameNode->appendChild($senderFirstNameTextNode);
    $senderSurnameNode = $xml -> createElement("surname");
    $senderSurnameTextNode = $xml -> createTextNode("$newSenderSurname");
    $senderSurnameNode -> appendChild($senderSurnameTextNode); 
   
    $senderNode -> appendChild($senderTitleNode);   //adding all 3 of these elements under the parent sender element
    $senderNode -> appendChild($senderFirstnameNode);
    $senderNode -> appendChild($senderSurnameNode);
   
    $recipientsNode = $xml -> createElement("recipients"); // parent node of all recipients
   
    $newRecipientTitle = $_POST['rTitle'];
    $newRecipientFirstName = $_POST['rFirstname'];
    $newRecipientSurname = $_POST['rSurname'];
     	
	$numOfRecipients = count($newRecipientTitle);
	for ($i = 0; $i < $numOfRecipients; $i++) { // get all recipient details and add to a recipient node
	   $recipientNode = $xml -> createElement("recipient"); // child node of recipients. Created for each recipient
	   $newRecipientID+=1;
	   
	   $newRecipientIDTag = "recipient_".$newRecipientID;
	   
	   $recipientNode   -> setAttribute("id", $newRecipientIDTag);// New recipient so new ID 
	   $recipientsNode -> appendChild($recipientNode);   //adding recipient under parent recipients node

	   $recipientTitleNode = $xml->createElement("title");
	   $recipientTitleTextNode = $xml->createTextNode("$newRecipientTitle[$i]");
	   $recipientTitleNode ->appendChild($recipientTitleTextNode);
	   $recipientNode -> appendChild($recipientTitleNode); 
		
	   $recipientFirstnameNode = $xml->createElement("firstName");
	   $recipientFirstnameTextNode = $xml->createTextNode("$newRecipientFirstName[$i]");
	   $recipientFirstnameNode -> appendChild($recipientFirstnameTextNode);
	   $recipientNode -> appendChild($recipientFirstnameNode);
	
	   $recipientSurnameNode = $xml -> createElement("surname");
	   $recipientSurnameTextNode = $xml -> createTextNode("$newRecipientSurname[$i]");
	   $recipientSurnameNode -> appendChild($recipientSurnameTextNode); 
	   $recipientNode -> appendChild($recipientSurnameNode); 
	}        
   
    $timezone = date_default_timezone_get();
    date_default_timezone_set($timezone);
    $todaysDate = date("F j, Y");     // October 10, 2015           
    $dateNode = $xml -> createElement("date");
    $dateTextNode = $xml -> createTextNode("$todaysDate");
    $dateNode -> appendChild($dateTextNode);
   
    $messageNode = $xml -> createElement("message");
    $messageTextNode = $xml -> createTextNode("$newMessage");
    $messageNode -> appendChild($messageTextNode);
   
    $linkNode = $xml -> createElement("link"); // parent node of link info
    $linkSiteNode = $xml -> createElement("site"); //child 1 of link node
    $linkSiteTextNode = $xml -> createTextNode("$newLink");
    $linkSiteNode -> appendChild($linkSiteTextNode);
    $linkDescriptionNode = $xml -> createElement("text"); //child 2 of link node
    $linkDescriptionTextNode = $xml -> createTextNode("$newLinkDescription");
    $linkDescriptionNode -> appendChild($linkDescriptionTextNode);
   
    $linkNode -> appendChild($linkSiteNode); //adding 2 children to the link node
    $linkNode -> appendChild($linkDescriptionNode);
    
    $statusNode = $xml -> createElement("status");
    $statusTextNode = $xml ->createTextNode("$newStatus");
    $statusNode -> appendChild($statusTextNode);
	
   //New ID per recipient
   $newRecipientIDNode = $xml -> createElement("nextRecipientID");
   $newRecipientTextNode = $xml -> createTextNode("$newRecipientID");
   $newRecipientIDNode -> appendChild($newRecipientTextNode);
   $root -> replaceChild($newRecipientIDNode, $nextRecipientIDNode);  //replace nextRecipient with incremented ID value

	$newSenderID+=1;
	$newSenderIDTag = "sender_".$newSenderID;
	$newSenderIDNode = $xml -> createElement("nextSenderID");
	$newSenderTextNode = $xml -> createTextNode("$newSenderID");
    $newSenderIDNode -> appendChild($newSenderTextNode);
    $root -> replaceChild($newSenderIDNode, $nextSenderIDNode);  //replace nextSenderID with incremented ID value
   
    $newID+=1; //increment ID by one for each new note
    $newIDNode = $xml -> createElement("nextID");
    $newIDTextNode = $xml -> createTextNode("$newID");
    $newIDNode -> appendChild($newIDTextNode);
    $root -> replaceChild($newIDNode, $nextIDNode);  //replace nextID with incremented ID value

    // create the new note
    $newNoteNode =  $xml -> createElement("note");
    $newNoteNode -> setAttribute("id",$newID);
    $newNoteNode -> appendChild($subjectNode);
	$senderNode   -> setAttribute("id", $newSenderIDTag);// New sender so new ID
    $newNoteNode -> appendChild($senderNode);
    $newNoteNode -> appendChild($recipientsNode);
    $newNoteNode -> appendChild($dateNode);
    $newNoteNode -> appendChild($messageNode);
    $newNoteNode -> appendChild($linkNode);
    $newNoteNode -> appendChild($statusNode);

    // add new note to the data set
    $notes -> insertBefore($newNoteNode,$firstNote);

    // save new XML file
    $xml -> save("notes.xml");
} else {
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript">  
	$(document).ready(function(){	
		$('#removeRecipient').hide(); // hide remove recipient button until required
	});

	var counter = 1; //recipient count
	function addElement(divName){
		var newRecipientDiv = document.createElement('div');
			 newRecipientDiv.id = counter;
			 
		newRecipientDiv.innerHTML = "<h4>Recipient " + counter + ": </h4>"+ " Title: <select id = 'rTitle' name = 'rTitle[]'>"+
													 "<option value ''>-- Title --</option>"+
													 "<option value = 'Mr'>Mr</option>"+
													 "<option value = 'Mrs'>Mrs</option>"+
													 "<option value = 'Miss'>Miss</option>"+
													 "<option value = 'Master'>Master</option>"+
													 "<option value = 'Dr'>Dr</option></select>"+
													 " Forename: <input type='text' id = 'rFirstname' name='rFirstname[]'> "+
													 " Surname: <input type='text' id = 'rSurname' name='rSurname[]'>";
													 
		document.getElementById(divName).appendChild(newRecipientDiv);
		counter++;
		
		$('#removeRecipient').show(); // display delete button when a recipient is added
	}
 
	function removeElement() {
		counter--;	  	  
	    var parentElement = document.getElementById('recipientInput'); // parent element
	    var child = document.getElementById(counter);
	    parentElement.removeChild(child);
		
		if (counter == 1){
			$('#removeRecipient').hide(); 
	    }
	}
	
	function validate(){
		// get the form element
		var theForm = document.getElementById("noteForm");
		
		//checking to see that the form is not empty
 	 	if (theForm.subject.value == "" || theForm.sTitle.value == "" || theForm.sFirstname.value == "" || theForm.sSurname.value =="" || theForm.message.value=="") {
			alert("The required fields have not been filled in. Please complete them and try again.");
			return false;
		}//if	  
		
		if (theForm.website.value != ""){ // if a website has been provided then a description must also be provided.
			if (theForm.webDescription.value==""){
				alert("Please provide a description for your website");
				return false;
			}
		}
}//validate
</script>
<html>
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Create - Little Notes</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/business-casual.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Slab:100,300,400,600,700,100italic,300italic,400italic,600italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
	<div class="brand">Welcome to Little Notes</div>
    <div class="address-bar">Create, search, edit and sort your notes</div>
	
	   <!-- Navigation -->
    <nav class="navbar navbar-default" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- navbar-brand is hidden on larger screens, but visible when the menu is collapsed -->
                <a class="navbar-brand" href="index.html">Welcome</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="index.html">Home</a>
                    </li>
                    <li>
                        <a href="addNote.php">Create a Note</a>
                    </li>
                    <li>
                        <a href="searchNotes.php">Browse Notes</a>
                    </li><li>
                        <a href="sortNotes.html">Sort Notes</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <div class="container">
	 <div class="row">
            <div class="box">
                <div class="col-lg-12">
                    <hr>
                    <h2 class="intro-text text-center">
                        <strong>Create A Note </strong> 
                    </h2>
                    <hr>
                </div>

                <div class="col-md-6" style ="width: 800px; margin-left:130px">
                   <form id = "noteForm" name = "noteForm" method="post" action="addNote.php" onSubmit = "return validate();" style ="width: 800px; margin-left:130px" >
						Subject: <input type="text" id = "subject" name="subject"  style = "margin-bottom:20px"><br>
						<h4>Sender</h4>
						Title: <select id = 'sTitle' name = 'sTitle' style = "margin-bottom:20px">
							<option value "">-- Title --</option>
							<option value = "Mr">Mr</option>
							<option value = "Mrs">Mrs</option>
							<option value = "Miss">Miss</option>
							<option value = "Master">Master</option>
							<option value = "Dr">Dr</option>
					    </select>
					    Forename: <input type="text" id = "sFirstname" name="sFirstname" style = "margin-bottom:20px">
					    Surname: <input type = "text" id = "sSurname" name = "sSurname" style = "margin-bottom:20px">
					    <div id = "recipientInput"></div>
					    <input type="button" id = "addRecipient" value="Add Recipient" onClick="addElement('recipientInput');"><br>
						<input type="button" id = "removeRecipient" value="Remove Recipient" onClick="removeElement();"><br>
				   	    Message: <br><textarea cols="80" rows="4" id = "message" name="message"  style = "margin-bottom:20px"></textarea><br>
					    Website: <input type = "url" id = "website" name = "site" style = "margin-bottom:20px"><br>
					    <input type = "hidden" name = "status" value = "new"><br>
						<input type="submit" name="submit" value="Create Note" style = "margin-left: 220px">
					</form>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <!-- /.container -->

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p>Copyright &copy; Little Notes</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
</body>

</html>
<?php
}
?>
