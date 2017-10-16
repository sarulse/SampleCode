<?php
/* Show detailed search results when a record is selected from the list of matches */
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $('.results').each(function(index) {
            $(".showDetail"+index).hide(); 
            $("#showResult"+index).click(function(){                        
                 $(".showDetail"+index).toggle(); 
                  return false;                
            });
        });
    });
</script>
<link rel="stylesheet" type="text/css" href="formstyle.css">
</head>
<body>
<?php
	//Get Params from the URL
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
	//Create another xml request
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
   	//Get the xml data	
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
			//$count counts the no. of records in the xml file
			$count = count($xml_data->recordset->record);				
			if ($count>0)
			{
				//echo "Number of Records in the address book: ".$count."</br>";
				$match_found = 0;
				$resultArray =array();
				for ($i=0; $i<$count; $i++)
				{                   

						$firstname = $xml_data->recordset->record[$i]->firstname;
						$lastname = $xml_data->recordset->record[$i]->lastname;
						$stateName = $xml_data->recordset->record[$i]->state;
						$cityName = $xml_data->recordset->record[$i]->city;                
						$fNameCheck =strcasecmp($fname,$firstname);
						$lNameCheck= strcasecmp($lname,$lastname);
						$stateCheck= strcasecmp($state,$stateName);
						if(($fNameCheck ==0) && ($lNameCheck == 0)&& ($stateCheck == 0))
						{								
							$match_found++;
							//print_r($xml_data->recordset->record[$i]);
							$recordset = $xml_data->recordset;
							$match_record = $recordset->record[$i];
							array_push($resultArray,$match_record);
						}                                                       

				} 
				//call printRecord function() to print Results
				printRecord($resultArray);
			}	
		}		

	}
	//print Records
   	function printRecord($outputArray){		
		$num_match = count($outputArray);
		//echo "Number of Matches: ".$num_match;
		if ($num_match == 0)
		{
			exit( "\nNo person can be found with the given details\n");            
		}
		else if ($num_match == 1)
		{
			echo "Only one match found"."<br/>";
			for ($counter=0; $counter<$num_match; $counter++)
			{
				displayDetail($outputArray,$counter);
			}
		}
		else
		{
			echo "Number of matches found: ".$num_match."<br/>";
			echo "<br/>";
			for ($counter=0; $counter<$num_match; $counter++)
			{
				?>
				<div class="results">
				<div id="showResult<?php echo $counter?>">
				<a href="#showResult">
				<?php 
					echo $outputArray[$counter]->firstname." ".$outputArray[$counter]->lastname." City: ".$outputArray[$counter]->city." State: ".$outputArray[$counter]->state;
				?>
					</a>
					<a id="showResult"></a>
					</div> 
					<div class="showDetail<?php echo $counter?>">            
				<?php              
					displayDetail($outputArray,$counter);							 
				 ?>
				</div>
				</div>						
				<?php
			}  
		}
	} 
	//Display detail of each record
	 function displayDetail($match_array, $i){		   
		echo "<br/>";
       		echo "<h4>";
       		echo "\tFirst Name: ".$match_array[$i]->firstname."<br/>";
		echo "\tMiddle Initial: ".$match_array[$i]->middlename."<br/>";
		echo "\tLast Name: ".$match_array[$i]->lastname."<br/>";
		echo "\tDOB: ". $match_array[$i]->dob."<br/>";
		echo "\tAge: ". $match_array[$i]->age."<br/>";
		echo "\tAddress: ". $match_array[$i]->address."<br/>";
		echo "\tState: ". $match_array[$i]->state."<br/>";
		echo "\tCity: ". $match_array[$i]->city."<br/>";
		echo "\tZip: ". $match_array[$i]->zip."<br/>";
		echo "\tReport Date: ". $match_array[$i]->reportdate."<br/>";
		echo "\tPhone: ". $match_array[$i]->phone."<br/>";
		$num_rel =count($match_array[$i]->relatives->relative);
		$relatives=$match_array[$i]->relatives;
		echo "Number of relatives: ".$num_rel."<br/>";
		for ($j=0; $j<$num_rel; $j++){
			echo str_repeat("&nbsp;", 6);
			echo $relatives->relative[$j]->first_name." ".$relatives->relative[$j]->middle_name." ".$relatives->relative[$j]->last_name." Age: ".$relatives->relative[$j]->age;
			echo "<br/>";
		}
		$num_addr =count($match_array[$i]->previous_addresses->previous_address);
		$previous=$match_array[$i]->previous_addresses;
		echo "Number of previous addresses: ".$num_addr."<br/>";
		for ($k=0; $k<$num_addr; $k++){
			echo str_repeat("&nbsp;", 6);
			echo "City: ".$previous->previous_address[$k]->city." State: ".$previous->previous_address[$k]->state." Zip: ".$previous->previous_address[$k]->zip;
			echo "<br/>";
		}
		echo "\tHome Owner: ". $match_array[$i]->home_owner."<br/>";  
		echo "<br/>";
       		echo "</h4>";			 
	}
	getData();
?>
</body>
</html>
