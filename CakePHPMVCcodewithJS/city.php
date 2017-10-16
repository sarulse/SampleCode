<?php
/*	
**	Subha Arulselvam: Model file (app/Model/city.php)
**	The file holds model information and validates input fields of the model
*/
class City extends AppModel {
    var $name = 'City'; // Model[Table]  name	
	
	 var $validate = array(     
		'location' => array (				
				'required' => true, // input is required
				'rule'=>'notEmpty', // input cannot be empty
				 'message' => ' Please select a radio button option for location of the city' // error message
		),      
		'industries' => array (				
				'required' => true,
				'rule'=>array('multiple', array('min'=>1)),
				'message' => ' Please select atleast one of the checkboxes for major industries in the city'
		),		
		'population' => array (				
				'required' => true,
				'rule'=>'notEmpty', 
				'message' => ' Please enter the population of the city'
		),
		'median income' => array (				
				'required' => true,
				'rule'=>'notEmpty',
				'message' => ' Please enter the median income of the people in the city'
		),
		'Cost of living' => array (				
				'required' => true,
				'rule'=>'notEmpty',
				 'message' => ' Please enter the cost of living of the city'
		),
		'Unemployment Rate' => array (				
				'required' => true,
				'rule'=>'notEmpty',
				 'message' => ' Please enter the Unemployment rate of the city'
		),
        'email' => array(
               'required' => true,
			   'rule'=> 'email',
			   'message' => ' Valid Email Address is required'
        )       
    );
    
	
	
	
}



?>
