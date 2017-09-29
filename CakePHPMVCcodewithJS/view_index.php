<?php
/*
**	Subha Arulselvam: View file (app/view/cities/index.ctp) saved as view_index.php 
**	The file display the input form : City Data
**	There are three sections in the form: Location, Industries and Statistics.
**	Either by clicking the section icon or title, the section drops down or displayed.
**	If user did not enter the information on required fields, error message will be displayed to re-enter the values on the form
**	To send the email, user has to enter recipient's email address.
**	Before emailing the form, message will be displayed showing the user inputs on the form, to change any values user has to re-enter the inputs. 
**	If message is not sent, sender will receive notification in his/her email address.
*/
?>
<!DOCTYPE html>
<html>
	<head>
	<style>
		.title { color:blue; font-size: 2em; vertical-align:middle; }
		img {  width: 5em; height: 5em; float:left; padding: 1em;}
	</style>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js">
	</script>	
	</head>
	<body>
		<?php
		//To include external javaScript file
		echo $this->Html->script('toggleInputs.js');
		echo "<h2>City Data: San Diego, CA</h2>";
		//creating a Form
		echo $this->Form->create('City', array('type' => 'post','id'=>'myCityForm','action'=>'send_email'));
		//Radio Button section
		echo $this->Html->div(null,' ');
		//Inserting Icon/Image
		echo $this->Html->image('arrows.png', array('onclick'=>'toggleCityData("displayRadio");'));
		echo "&nbsp&nbsp&nbsp&nbsp&nbsp";
		// Title/label of the Radio Button Section
		echo $this->Form->label('Location of the City', 'Location', array('class' => 'title','onclick'=>'toggleCityData("displayRadio");')); 
		//Radio button values 
		$radio_values = array('northern part of the state'=>'northern part of the state','southern part of the State'=>'southern part of the State','eastern part of the state'=>'eastern part of the state','western part of the state'=>'western part of the state');
		echo $this->Form->input('The city lies in', array('type'=>'radio','name'=>'data[City][location]',  'div' =>array('id'=>'cityLocation','class'=>'displayRadio'),'options'=>$radio_values));
		//Check Box section
		echo $this->Html->div(null,' ');
		echo $this->Html->image('checkbox.png', array('onclick'=>'toggleCityData("displayCheckbox");'));
		echo $this->Form->label('Majority of Industries ', 'Industries', array('class' => 'title','onclick'=>'toggleCityData("displayCheckbox");')); 
		echo "&nbsp&nbsp&nbsp&nbsp&nbsp";
		//check box values 
		$check_values = array('Educational Institutions'=>'Education','Bio-Tech Industries'=>'Bio-tech','IT Industries'=>'IT','Farming'=>'Farming');
		echo $this->Form->input('Major industries of the city are:', array('type'=>'select', 'name'=>'data[City][industries]', 'div' =>array('id'=>'cityIndustries','class'=>'displayCheckbox'), 'multiple'=>'checkbox','options'=>$check_values));
		//Text Box section
		echo $this->Html->div(null,' ');
		echo $this->Html->image('statistics.png', array('onclick'=>'toggleCityData("cityStat");'));
		echo $this->Form->label('Statistics about the city ', 'Statistics', array('class' => 'title','onclick'=>'toggleCityData("cityStat");')); 
		//Text box values 
		$stat_values = array('Population'=>'Population','Income'=>'Median Income','Cost'=>'Cost of Living','Unemployment Rate'=>'Rate');
		echo $this->Html->div('cityStat',null, array('id' => 'cityStat'));
		echo $this->Form->inputs('Some statistics about the City', array('legend'=> false, 'fieldset'=>false, 'location','industries','email', 'statistics', 'type'=>'varchar','name'=>'data[City][statistics]', 'legend'=>false, 'options'=>$stat_values));
		// For Email Address
		echo $this->Html->div(null,' ');
		echo $this->Form->input('Enter Email Address to send the form', array('type'=>'varchar','name'=>'data[City][email]','legend'=>false));
		// Submit Button
		echo $this->Html->div(null,' ');
		$options = array(
			'label' => 'Send Email',
			'div' => array(
				'class' => 'submitButton',
				'onclick'=>'showFormValues()' //when sending Email button is clicked, the 'showFormValues' function will be executed
			)
		);
		echo $this->Form->end($options);		 
		?>
	</body>
</html>
