#Python Flask App with knockoutJS

##To run the application locally or through AWS, please follow the below instructions:

### Get code fron GitHub
*   git clone https://github.com/sarulse/SampleCode.git
	featurerequests directory contains project files for the feature requests app, ignore the other directories
### Create Virtual environment
*   $ cd featurerequestsapp
*	$ virtualenv flaskRequestApp-venv 
*   Activate the virtual environment
	$ source flaskRequestApp-venv/bin/activate
### Insall the required packages and dependencies
	$ pip install -r requirements.txt
###	Create a MySQL database using AWS RDS if using AWS or create a mysql database 
    At local, MYSQLWorkbench is helpful
    RDS settings are saved at settings.py
### To test at local: 	
	$ python application.py
	can see the app at: http://127.0.0.01:5000/
    
    
### To test the app at AWS
* Set up Elastic Beanstalk Environment
	$ pip install awsebcli
* With your AWS user account or through test user accounts at AWS IAM console
	$ eb init
* Select the settings for default region, application name, python version-2.7, SSH set up optional
* Create environment for AWS Elastic beanstalk
** Enter Envirorment name and DNS CNAME prefix
	DNS CNAME prefix is your app name For example: http://pythonflaskrequestapp.us-east-1.elasticbeanstalk.com/
	Now the app wil be deployed and you will see messages about app version creation. Once the deployment is successful:
	You will see the message: INFO: Successfully launched environment: pythonflaskrequestapp
* Check out the app at http://pythonflaskrequestapp.us-east-1.elasticbeanstalk.com/
* For any file changes, example: config setting updtes for settings.py
    ** Redeploy the app by
	    $ eb deploy
	
	  

	
	
	
	
	



•	Inside featurerequestsapp directory, create virtual env
	virtualenv flaskRequestApp-venv 
•	To activate the virtual env
source flaskRequestApp-venv/bin/activate
•	To install packages and dependencies for the app
pip install -r requirements.txt
•	Application name: ‘featurerequestsapp’
•	Instance name: pythonflaskrequestapp.us-east-1.elasticbeanstalk.com

