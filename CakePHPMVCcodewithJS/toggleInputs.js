
	//To hide the section content using jquery
	$(document).ready(function () {
		// To hide the form input elements but not the title
		$("#cityLocation").hide();
		$("#cityIndustries").hide();
		$("#cityStat").hide();
	});
	//To show the section content when the title of the section is clicked
	function toggleCityData(section) {
		// To show or toggle form input elements when the section title is clicked
		$("." + section).show();
		//$("."+section).slideToggle();
	}
	//Function to show user inputs on the form before submission using alert
	function showFormValues() {
		var inputs = document.getElementsByTagName("input");
		var count = 0;
		var selected_inputs = [];
		//One for loop is sufficient to loop through the form inputs
		// But for pretty printing purpose three for loops are used for each input type.
		//To display user selection of the radio button
		selected_inputs += "Location selected: ";
		for (var i = 0; i < inputs.length; i++) {
			if (inputs[i].type == "radio" && inputs[i].checked == true) {
				// selected_inputs.push("Location selected is:");
				selected_inputs += inputs[i].value;		}
		}
		selected_inputs += "\n";
		//To display user input of the multiple checkboxes
		selected_inputs += "Major Industries selected: ";
		for (var i = 0; i < inputs.length; i++) {
			if (inputs[i].type == "checkbox" && inputs[i].checked == true) {
				selected_inputs += inputs[i].value + ','; //since multiple checkboxes could be selected, their values are concatenated using a comma
			}
		}
		selected_inputs += "\n";
		//To display the values of text input of the form
		selected_inputs += "Some Statistics about the City: ";
		selected_inputs += "\n";
		var txt;
		var txt_name;
		var pattern;
		for (var i = 0; i < inputs.length; i++) {
			if (inputs[i].type == "text" && inputs[i].name != "data[City][email]") {
				//Name of the input field is extracted. In cakephp, the format is data[Model][fieldName]
				//so field name is extracted using substring and pattern matching excluding the square brackets
				txt = inputs[i].name;
				txt_name = txt.substring(10); // gives [fieldName] including the brackets
				pattern = /^\[(.*?)\]$/;

				if (txt_name.match(/^\[(.*?)\]$/)) {
					var newName = txt_name.replace(pattern, "$1"); //gives fieldName excluding the brackets
					//alert(newName);
				} else {
					alert("No such field in the form");
				}
				selected_inputs += newName + ": ";
				selected_inputs += inputs[i].value;
				selected_inputs += "\n";
			}
		}
		alert("Selected inputs of the form are:\n" + selected_inputs + "\n" + "To Change any values;re-enter the values in the form" + "\n");
	}


