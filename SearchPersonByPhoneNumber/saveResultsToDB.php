<?php
/* Save Search requests and responses to a MYSQL table for XML Request :
  save xml requests to "PersonRecords" table
  save records with matching name and state to "PersonRecordSearchResults" table 
 */
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
</head>
<body>
<?php
	function getParams() {		
		$name = array();
		$fname = $_GET['firstname'];
		$lname =$_GET['lastname'];
		$state= $_GET['state'];		
		$name['fname']= $fname;
		$name['lname']= $lname;
		$name['state']= $state;
		return $name;		
	}
	//Send Request
	function xmlRequest() {		
		include_once("urlParams.php");
		$params = getParams();
		$fname = $params['fname'];
		$lname = $params['lname'];
		$state = $params['state'];	
		// Get cURL resource
		$curl = curl_init();
		$url = $urlParams2.'&firstname=';
		$url.=$fname;
		$url.='&middle_initial=&lastname=';
		$url.=$lname;
		$url.='&city=&state=';
		$url.=$state;
		$url.='&zip=&client_reference=test&phone=&housenumber=&streetname=.';
		// Set some options -
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			 CURLOPT_URL => $url
			
		));
		// Send the request & save response to $data
		$data = curl_exec($curl);
       		if(!$data){
			//die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
			exit ("Could not connect to the server<br/>");
		}
		else
		{
			$data = simplexml_load_string($data);	
		}	
		// Close request to clear up some resources
		curl_close($curl);
		return $data;
	}
	//Get xml data
	function getData() {		
		$xml_data = xmlRequest();	
		$params = getParams();
		$fname = $params['fname'];
		$lname = $params['lname'];
		$state = $params['state'];
		if ($xml_data === false) {
                echo "Failed loading XML: ";
    			foreach(libxml_get_errors() as $error) {
					 echo "<br>", $error->message;
				 }
		}
		else
		{
		    	//$count  the no. of records in the xml file
			$count = count($xml_data->recordset->record);
			if ($count>0)
			{
				$resultArray =array();
				for ($i=0; $i<$count; $i++)
				{                   

					$recordset = $xml_data->recordset;
					$found_record = $recordset->record[$i];
					array_push($resultArray,$found_record);                 

			   }         						
				//call saveRecords to save all Records to Database()
				saveRecords($resultArray);
			 }
		    }
	}  
	//save data from requests to a DB table
	function saveRecords($recordArray){		
		$num_records = count($recordArray);		
		//to make connection to the database
		include_once('connection.php');		
		if ($connect)     
		{
			//create MYSQL table    	
			$drop = "DROP TABLE IF EXISTS PersonRecords;";
			$create = "CREATE TABLE  IF NOT EXISTS PersonRecords
			(
				id int(10) NOT NULL AUTO_INCREMENT,
				fname varchar(255) NOT NULL,
				minitial varchar(10)NULL,
				lname varchar(255)NOT NULL,
				dob varchar(255) NULL,
				age varchar(255) NULL,            
				address varchar(255)NULL,
				city varchar(255) NOT NULL,
				state varchar(255) NOT NULL,  
				zip varchar(255) NOT NULL,  
				reportDate varchar(255),
				phone varchar(255) NOT NULL,
				relatives varchar(510) NULL,
				previousAddresses varchar(1020) NULL,
				homeOwner varchar(10)NULL,
				PRIMARY KEY  (id)                   
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";     
			
			//check if table creation is successful
			if (!$connect->query($drop) || !$connect->query($create))
			{
				echo "Table creation failed: (" . $connect->errno . ") " . $connect->error;
			}
			else
			{
				//On table creation successful insert the records
				for ($counter=0; $counter<$num_records; $counter++)
				{
					$fname = $connect->real_escape_string($recordArray[$counter]->firstname);
					$mname= $connect->real_escape_string($recordArray[$counter]->middlename);
					$lname= $connect->real_escape_string($recordArray[$counter]->lastname);
					$dob= $connect->real_escape_string($recordArray[$counter]->dob); 
					$age= $connect->real_escape_string($recordArray[$counter]->age); 
					$address= $connect->real_escape_string($recordArray[$counter]->address);
					$city= $connect->real_escape_string($recordArray[$counter]->city);
					$state= $connect->real_escape_string($recordArray[$counter]->state);
					$zip= $connect->real_escape_string($recordArray[$counter]->zip); 
					$reportDate= $connect->real_escape_string($recordArray[$counter]->reportdate); 
					$phone= $connect->real_escape_string($recordArray[$counter]->phone); 
								 
					$num_rel =count($recordArray[$counter]->relatives->relative);
					$relativeArray = array();
					for ($j=0; $j<$num_rel; $j++){
								
					
					  $relatives=$connect->real_escape_string($recordArray[$counter]->relatives->relative[$j]->first_name)." ";
					  $relatives.=$connect->real_escape_string($recordArray[$counter]->relatives->relative[$j]->middle_name)." "; 
					  $relatives.=$connect->real_escape_string($recordArray[$counter]->relatives->relative[$j]->last_name)." "; 
					  if (empty($recordArray[$counter]->relatives->relative[$j]->age)== FALSE)
					  {
						  $relatives.="Age: ";
						  $relatives.=$connect->real_escape_string($recordArray[$counter]->relatives->relative[$j]->age)." "; 
					  }
					  array_push($relativeArray,$relatives);
					  
					}
					$relativeValues = implode(",", $relativeArray);
					$num_addr =count($recordArray[$counter]->previous_addresses->previous_address);
					$previousAddrArray=array();
					//echo "Number of previous addresses: ".$num_addr."<br/>";
					for ($k=0; $k<$num_addr; $k++){                
					
									 
					  $previous=$connect->real_escape_string($recordArray[$counter]->previous_addresses->previous_address[$k]->city)." ";
					  $previous.=$connect->real_escape_string($recordArray[$counter]->previous_addresses->previous_address[$k]->state)." ";
					  $previous.=$connect->real_escape_string($recordArray[$counter]->previous_addresses->previous_address[$k]->zip);
					 
					  array_push($previousAddrArray,$previous);
					 
					}  
					$addressValues = implode(",", $previousAddrArray);
					$homeOwner= $connect->real_escape_string($recordArray[$counter]->home_owner); 
					
					
					//Insert without prepare                
					/*$insert = "INSERT INTO PersonRecords (fname,minitial,lname,dob,age,address,city,state,zip,reportDate,phone,relatives,previousAddresses,homeOwner)
							   VALUES ('$fname','$mname','$lname','$dob','$age','$address','$city','$state','$zip','$reportDate','$phone','$relativeValues','$addressValues','$homeOwner')";    

					//insert values into the table
					$query1 = $connect->query($insert);
					*/                    
                   			 //Insert using prepare 
    					$insert = "INSERT INTO PersonRecords (fname,minitial,lname,dob,age,address,city,state,zip,reportDate,phone,relatives,previousAddresses,homeOwner)
							   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
					
					 //Using prepare 
					 $query1 = $connect->prepare($insert);                                 
					 // bind parameters 
					 $query1->bind_param("ssssssssssssss",$fname,$mname,$lname,$dob,$age,$address,$city,$state,$zip,$reportDate,$phone,$relativeValues,$addressValues,$homeOwner);
				                        
					 //Execute
					 $query1->execute();                 
					 $query1->close();
															 
				}
				if ($query1) {
						echo "<h4>XML Request is saved to Database.</h4>";
						//echo "<h3>Number of Total Records: $num_records</h3>";
						retrieveRecords($connect);
				} else {
					echo "Error: " . $query1 . "<br>" . $connect->error;
				}
					
			}
		}    
	}
	// Retrieve matching records and save that to a DB table
	function retrieveRecords($conn){	        
        $params = getParams();
		$fname = $params['fname'];
		$lname = $params['lname'];
		$state = $params['state'];
		if ($conn)
		{ 
			//create search results table;
			$drop1 = "DROP TABLE IF EXISTS PersonRecordSearchResults;";
			$create1 = "CREATE TABLE IF NOT EXISTS PersonRecordSearchResults
			(
				id int(10) NOT NULL AUTO_INCREMENT,
				fname varchar(255) NOT NULL,
				minitial varchar(10)NULL,
				lname varchar(255)NOT NULL,
				dob varchar(255) NULL,
				age varchar(255) NULL,             
				address varchar(255)NULL,
				city varchar(255) NOT NULL,
				state varchar(255) NOT NULL,  
				zip varchar(255) NOT NULL,  
				reportDate varchar(255),
				phone varchar(255) NOT NULL,
				relatives varchar(510) NULL,
				previousAddresses varchar(1020) NULL,
				homeOwner varchar(10)NULL,
				PRIMARY KEY  (id)                   
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";  
			//check if table creation is successful
			if (!$conn->query($drop1) || !$conn->query($create1))
			{
						echo "Table creation failed: (" . $conn->errno . ") " . $conn->error;
			}
			else
			{
				//Query the database table to retrieve search results   
				$select = "SELECT * FROM PersonRecords where fname = '$fname' AND lname='$lname' AND state='$state';"; 
				$result= $conn->query($select);
				$num_results = $result->num_rows;            
				echo "<h3>Number of Matching Records: $num_results</h3>";
               			if ($num_results == 0) {
                   		 	echo "No Records are saved to the database<br/>";
               			}
				echo "--------------------------------------------------------------------<br/>";
				$personArray= array();			
				$counter = 0;
				while ($row = $result->fetch_assoc()) 
				{
					//echo $row["fname"]." ".$row["minitial"]." ".$row["lname"]." State ".$row["state"]." Phone: ".$row["phone"];
					$fname = $row["fname"];
					$personArray[$counter]->firstname = $fname;
					//echo $personArray[$counter]->firstname;                
					$mname = $row["minitial"];
					$personArray[$counter]->middlename = $mname;
					$lname = $row["lname"];
					$personArray[$counter]->lastname = $lname;
					$dob = $row["dob"];
					$personArray[$counter]->dob = $dob;
					$age = $row["age"];
					$personArray[$counter]->age = $age;
					$address =$row["address"];
					$personArray[$counter]->address = $address;
					$city = $row["city"];
					$personArray[$counter]->city = $city;
					$state= $row["state"];
					$personArray[$counter]->state = $state;
					$zip = $row["zip"];
					$personArray[$counter]->zip = $zip;
					$reportDate= $row["reportDate"];
					$personArray[$counter]->reportDate = $reportDate;
					$phone = $row["phone"];
					$personArray[$counter]->phone = $phone;
					$relatives = $row["relatives"];
					$personArray[$counter]->relatives = $relatives;
					$previousAddresses =$row["previousAddresses"];
					$personArray[$counter]->previousAddresses = $previousAddresses;
					$homeOwner = $row["homeOwner"];
					$personArray[$counter]->homeOwner = $homeOwner;
					$counter++;											   
					$insert2 = "INSERT INTO PersonRecordSearchResults (fname,minitial,lname,dob,age,address,city,state,zip,reportDate,phone,relatives,previousAddresses,homeOwner)
							   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
					 //Using prepare 
					 $query2 = $conn->prepare($insert2);                                 
					 // bind parameters 
					 $query2->bind_param("ssssssssssssss",$fname,$mname,$lname,$dob,$age,$address,$city,$state,$zip,$reportDate,$phone,$relatives,$previousAddresses,$homeOwner);
					 //Execute
					 $query2->execute();
					 $query2->close(); 											   
									 															
				}
				if ($query2){
    			        echo "Search Results are saved to database successfully<br/>";   
				} else {
						echo "Error: " . $query2 . "<br>" . $conn->error;				
					   
				} 					   
			  	mysqli_free_result($result);  			
			   	$conn->close();
			}
		} 
	}
	getData();
 ?>
</body>
</html>
