# Search a Person Record using Phone number #
## Summary: ##
To implement a search feature using a valid U.S. phone number and then filter the results using full name and state.

* An user can enter a US phone number to look up a person
* When submitted, a back end XML request then display the results to the user. 
* If no records are found, that information is displayed to the user.
* If there is a problem with the XML server a notice is sent to the user. 
* If there are multiple results, clickable full name and state of each record is displayed. 
* When a record is clicked, a second xml request is created that will display a list of records with the matching name and state-[displaydetail.php](https://github.com/sarulse/SampleCode/blob/master/SearchPersonByPhoneNumber/displayDetail.php).
* When a record is selected from the list, it will display detailed information about the record.
* Requests and Responses are saved to a MYSQL table-[saveResultsToDB.php](https://github.com/sarulse/SampleCode/blob/master/SearchPersonRecords/saveResultsToDB.php).

# Files: #

* [SearchPersonPart2.php](https://github.com/sarulse/SampleCode/blob/master/SearchPersonByPhoneNumber/SearchPersonPart2.php)
* [displaydetail.php](https://github.com/sarulse/SampleCode/blob/master/SearchPersonByPhoneNumber/displayDetail.php)
* [saveResultsToDB.php](https://github.com/sarulse/SampleCode/blob/master/SearchPersonByPhoneNumber/saveResultsToDB.php)
* [urlparams.php](https://github.com/sarulse/SampleCode/blob/master/SearchPersonByPhoneNumber/urlparams.php)
* JS files : 
  * [showRecord.js](https://github.com/sarulse/SampleCode/blob/master/SearchPersonByPhoneNumber/js/showRecord.js)
  * [validatePhone.js](https://github.com/sarulse/SampleCode/blob/master/SearchPersonByPhoneNumber/js/svalidatePhone.js)


Note:
----
* Connection Parameters should not be exposed to the public folder (connectionSample.php)
* URL Paramerters with user credentials should not be exposed to the public folder (urlParams.php)
* User input (phone number) is validated both on the client side (JavaScript) and Server side (PHP)


Sample Test inputs:
-----------------
Phone numbers can be entered in the following format:
(123)-4567890 (or) 1234567890 (or) 123-456-7890 (or) 123-4567890

 For Example: 
 (i) 3867540455
(ii) (386)7540456
(iii) 386-7540457


