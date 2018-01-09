# Python Flask App with Sql-Alchemy and knockoutJS
App can be viewed at: http://pythonflaskrequestapp.us-east-1.elasticbeanstalk.com/showFeatureRequestForm

### Tech Stack:
*	OS: Ubuntu 
*	Languages: Python 2.7, jquery, KnockoutJS (3.4.2)
*	Framework - FLASK
*	ORM : Sql-Alchemy



## To run the application locally or through AWS, please follow the instructions:

### Get code fron GitHub
*   git clone https://github.com/sarulse/SampleCode.git
	* **featurerequests directory** contains project files for the feature requests app, ignore the other directories
### Create Virtual environment
*   `$ cd featurerequestsapp`
*	`$ virtualenv flaskRequestApp-venv`
*   Activate the virtual environment
	`$ source flaskRequestApp-venv/bin/activate`
### Insall the required packages and dependencies

	`$ pip install -r requirements.txt`
### Create a MySQL database using AWS RDS if using AWS or create a mysql database locally
	* At local, MYSQLWorkbench is helpful
	* RDS settings can be found at at settings.py
### To test at local: 	

	`$ python application.py`
	View the app at http://127.0.0.01:5000/ (5000 is the default port at local)
    
    
### To test the app at AWS
* Set up Elastic Beanstalk Environment through awsebcli

	`$ pip install awsebcli`
* Set up environment, app and python :

	`$ eb init`
* Select the settings for default region, application name, instance-name, python version-2.7, SSH set up optional.
	* Example: Application name: ‘featurerequestsapp’'
	* Instance name: pythonflaskrequestapp.us-east-1.elasticbeanstalk.com
* Create environment for AWS Elastic beanstalk:

	`$ eb create`
	
	* Enter Envirorment name and DNS CNAME prefix
	* DNS CNAME prefix is your app name For example: http://pythonflaskrequestapp.us-east-1.elasticbeanstalk.com/
	* Now the app wil be deployed and you will see messages about app version creation. Once the deployment is successful:
	(You will see the message: INFO: Successfully launched environment: pythonflaskrequestapp)
	
* Check out the app at http://pythonflaskrequestapp.us-east-1.elasticbeanstalk.com/
* For any file changes, example: config setting updates for settings.py
	* Re-deploy the app by the command:
	
	`$ eb deploy`
* To de-activate the virtual environment (flaskRequestApp-venv) :
	* `$ deactivate`
	
	  

	
	
	
	
	





