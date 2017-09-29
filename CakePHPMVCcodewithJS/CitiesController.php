<?php
/*
**	
**	Subha Arulselvam: Controller file (CitiesController.php)
**	The file define actions through the functions that is called from view
*/

class CitiesController extends AppController 
{
     var $name = 'Cities'; // Controller name
     var $helpers = array ('Html','Form');
     var $components = array('Email','Session');	  
	
     //Function to display the input form: City Data.No actions are defined here. 
     function index()
     {
		
     }
	 
     // Function to process the data and email that to the recipient
     function send_email()
     {		  
	       if(!empty($this->data)) 
	       {			
				$this->City->set($this->data);
				if ($this->City->validates())
				{
					 // location of the city
					$location = $this->data['City']['location']; 
					$location = "The city lies in ".$location.".<br/>";	
					
					// major industries in the city
					$industries = implode(",",$this->data['City']['industries']); 
					$industries = "\nMajor Industries of the city are: ".$industries.".<br/>";	
					
					// statistics about the city
					$statistics = "Some statistics about the city are displayed below: <br/>";
					$statistics .=  "Population of the city: ".$this->data['City']['population']."<br/>";
					$statistics .= "Median Income of the city: ".$this->data['City']['median income']."<br/>";
					$statistics .= "Cost of Living in the city: ".$this->data['City']['Cost of living']."<br/>";
					$statistics .= "Unemployment rate in the city: ".$this->data['City']['Unemployment Rate']."<br/>";
					
					//Assigning values for mail function such as Recipients email address, Senders address, Subject of the message and the actual message
					$email_to    = $this->data['City']['email']; // recipient's email addres
					$to 		= $email_to;
					$from     = "sarulselv@gmail.com";
					$subject  = 'Testing Email capability';
					$message = "<h2><u>Information about San Diego, CA</h2><br/>";
					$message  .= $location."<br/>".$industries."<br/>".$statistics;	
					$headers  = 'From: sarulselv@gmail.com' . "\r\n" .
								'Reply-To: sarulselv@gmail.com' . "\r\n" .
								'MIME-Version: 1.0' . "\r\n" .
								'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
								'X-Mailer: PHP/' . phpversion();
								
						//To display notification about Email message: success or failure						
						if(mail($to, $subject, $message,$headers))
						{
							echo "Your email is sent successfully";
							$this->render('index');
						}
						else
						{
							echo "Sending the email failed";
						}			
					}
					else
					{							
						$validationErr =   $this->City->validationErrors;
						//flatten the validation Error array 
						$flatValidErr = Set::flatten($validationErr);													
						echo "<h3>There are ".  count($flatValidErr) . "error(s) in your submission:</h3>";
							echo "<ul>";
						foreach ($flatValidErr as $key=>$value)
						{ 
							echo "<li>".$value ."</li>";
						}
						echo"</ul>";	
						$this->render('index');
					}
	       }	
	       else
	       {
				$this->Session->setFlash(__('No data is entered on the form, Please enter/select the values for the form to be mailed', true));
				$this->render('index');
	       }
     }		
	
 }

?>