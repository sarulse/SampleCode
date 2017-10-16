<?php
/*
  Search for a person record using a valid U.S phone number and save requests and responses to a MYSQL table
  save xml request to "Person" table and 
  save the records with the matching phone number to "PersonSearchResults" table  
*/
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src = "js/validatePhone.js"></script>
<link rel="stylesheet" type="text/css" href="css/formstyle.css">
</head>
<body>
       <form name="personSearch"  method="POST"  accept-charset="UTF-8" target="_self">
           	<fieldset align="center">
                    <legend>Search Person Record </legend>
                    <br/>
                    Enter Phone Number to search a person:<br>
                    <input type="tel" name="phone" id="tel" required >
                    <br/><br/>
            	 </fieldset>
             	<br/>
             	<input type="submit" value="Submit" onclick=" return validatePhone()">
	</form>
<?php	
	//Get the entered Phone Number from the Form
	function getPhoneNo () {
		if(isset($_POST['phone']))
		{	
			$phone_num = $_POST['phone'];
			//Validate phone number
			//remove special characters from phone_number
			$ph_num = preg_replace('/[^A-Za-z0-9\$]/', '', $phone_num);
			if (!(is_numeric($ph_num))) 
				die ("<p align=\"CENTER\">Entered phone number is not a number</p>");							
		}
		else {
                       	die ("<p align=\"CENTER\">Please enter a phone number in the form to begin searching</p>");
      		}
        	return $ph_num;
	}
	// Create XML Request
	function xmlRequest() {		
		include_once("urlParams.php");
		try {
			$phone_number = getPhoneNo();
			// Check number of digits in the phone number
			$numberOfDigits = strlen($phone_number);	
			if ($numberOfDigits != 10) 
				throw new Exception ("Enter a valid US Phone Number Format:\n(123)-4567890\n (or) 1234567890\n (or) 123-456-7890\n (or) 123-4567890");
		}
		catch(Exception $e) {
			exit ("Error in the phone number entered:</br> ".$e->getMessage());
		}
		$area_code = substr($phone_number,0,3);
		$tel_number = substr($phone_number,3,7);
		// Get cURL resource
		$curl = curl_init();	
		$url = $urlParams1.'&areacode=';
		$url.= $area_code;
		$url.= '&phone=';
		$url.= $tel_number;		
		// Set some options - 
		curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => $url    
				));
		// Send the request & save response to $data
		$data = curl_exec($curl);
		if(!$data){
			//die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
			exit ("Could not connect to the XML server<br/>");
		}
		else
		{
			$data = simplexml_load_string($data);
		
		}	
		// Close request to clear up some resources
		curl_close($curl);
		return $data;
	}
  	//Print Search Results
	function printSearchResults() {
		$phone_number = getPhoneNo();
		$xml_data = xmlRequest();

		if ($xml_data === false) {
			echo "Failed loading XML: ";
			foreach(libxml_get_errors() as $error) {
				 echo "<br>", $error->message;
			 }
		}
		else
		{
			//save data from xml requests to a mysql table
			// To connect to the database include connection parameters
			include_once('connection.php');
			//Check if connected to database
			if ($connect)        {
				//create MYSQL table            
				$drop = "DROP TABLE IF EXISTS Person;";
				$create = "CREATE TABLE  IF NOT EXISTS Person
					(
						  id int(10) NOT NULL AUTO_INCREMENT,
						  fname varchar(255) NOT NULL,
						  minitial varchar(10)  NULL,
						  lname varchar(255) NOT NULL,
						  address varchar(255) NULL,
						  city varchar(255)  NOT NULL,
						  state varchar(255) NOT NULL,  
						  age int(10) NULL,  
						  dfrom varchar(255),
						  dto varchar(255),
						  phone varchar(255) NOT NULL,
						  timezone varchar(255)  NULL,
						  phoneCarrier varchar(255) NULL,
						  providerType varchar(255)  NULL,
						  PRIMARY KEY  (id)
					   ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
				
				if (!$connect->query($drop) || !$connect->query($create))
				{
						echo "Table creation failed: (" . $connect->errno . ") " . $connect->error;
				}
				else
				{
					//On table creation successful, insert the records
					foreach ($xml_data->record as $record)
					{                    
						$fname = $connect->real_escape_string($record->firstname);
						$mname= $connect->real_escape_string($record->middlename);
						$lname= $connect->real_escape_string($record->lastname);
						$address= $connect->real_escape_string($record->addressA);
						$city= $connect->real_escape_string($record->addressB);
						$state= $connect->real_escape_string($record->state);
						$age= $connect->real_escape_string($record->age); 
						$from= $connect->real_escape_string($record->from);
						$to= $connect->real_escape_string($record->to);
						$phone= $connect->real_escape_string($record->phone);
						$timeZone= $connect->real_escape_string($record->timezone);
						$phoneCarrier= $connect->real_escape_string($record->phone_carrier);
						$providerType= $connect->real_escape_string($record->provider_type);
						//Insert values into Person Table
						$insert = "INSERT INTO Person (fname,minitial,lname,address,city,state,age,dfrom,dto,phone,timezone,phoneCarrier,providerType)
						VALUES ('$fname','$mname','$lname','$address','$city','$state','$age','$from','$to','$phone','$timeZone','$phoneCarrier','$providerType')";    
	
						$query = $connect->query($insert);
					}
					if (!$query) {
						echo "Error: ".$insert. "<br>". $connect->error."Error #".$connect->errno." ".$connect->sqlstate;
					} else {
						echo "<h4>XML Request is saved to database</h4>";												 
					}
					
				}    
				//Saving search results to a mysql table									 
				//create PersonSearchResults Table
				$drop1 = "DROP TABLE IF EXISTS PersonSearchResults;";
				$create1 = "CREATE TABLE  IF NOT EXISTS PersonSearchResults
					(
						  id int(10) NOT NULL AUTO_INCREMENT,
						  fname varchar(255) NOT NULL,
						  minitial varchar(10)  NULL,
						  lname varchar(255) NOT NULL,
						  state varchar(255) NOT NULL,  
						  phone varchar(255) NOT NULL,
						  PRIMARY KEY  (id)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
				
				if (!$connect->query($drop1) || !$connect->query($create1))
				{
						echo "Table creation failed: (" . $connect->errno . ") " . $connect->error;
				}
				$pattern = '/^\(?(\d{3})\)?[-. ]?(\d{3})[-. ]?(\d{4})$/';
				$format = '($1) $2-$3';
				$phone_num = preg_replace($pattern,$format, $phone_number);
				//echo "Phone number is: ".$phone_num."<br/>";				
				$select = "SELECT fname,minitial,lname,state,phone FROM Person where phone = '$phone_num';"; 
				$result= $connect->query($select);
				$num_results = $result->num_rows;				
				echo "<h2>"."Search Results</h2>";
				echo "<h4>Number of matched records: ".$num_results."<br/></h4>";
              			if ($num_results == 0){
                    			echo "<h4>No records are saved to database<br/></h4>";
              			}
				echo "<h4>--------------------------------------------------------------------<br/></h4>";
			   
				echo "<h4>";
				while ($row = $result->fetch_assoc()) 
				{
					?>
				    	<a href="saveResultsToDB.php?firstname=<?php echo $row["fname"]; ?>&amp;lastname=<?php echo $row["lname"]; ?>&amp;state=<?php echo $row["state"]; ?>" >	
					<?php
					echo $row["fname"]." ".$row["minitial"]." ".$row["lname"]." State ".$row["state"]." Phone: ".$row["phone"];
					echo "<br/>";								 
					//Insert values 
					$insert1 = "INSERT INTO PersonSearchResults (fname,minitial,lname,state,phone)
						VALUES (?, ?, ?, ?, ?)";    
					 //Using prepare 
					 $query1 = $connect->prepare($insert1);    									
					 // bind parameters 
					 $query1->bind_param("sssss", $firstname, $middleInitial, $lastname,$state,$phone);	
					 //Set parameters
					 $firstname = $row["fname"];                
					 $middleInitial =$row["minitial"];                 
					 $lastname = $row["lname"];                
					 $state = $row["state"];                 
					 $phone = $row["phone"];                              
					 echo "<br/>";
					 //Execute
					 $query1->execute();                 
					 $query1->close();          									 
				}
				?>
				</a>
				<?php
				    if ($query1) 
					{
							echo "--------------------------------------------------------------------<br/>";
							echo "<h4>Person search results are saved to database</h4>"."<br/>";								 
					}
					echo "</h4>";
					mysqli_free_result($result);        	
					$connect->close();
			}
		}
	}
    	$ph_no = getPhoneNo();
    	if (!(empty($ph_no))) {
            printSearchResults();
	}
?>
</body>
</html>
