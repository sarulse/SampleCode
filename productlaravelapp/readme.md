# A Simple Laravel Rest API APP with Angular JS

## Summary
    * List of products with details is displayed 
    * User can view a list of products
    * Select a product and edit/delete a product
    * User can also add a new product
    * A product will not be added to the database unless the required fields are filled

## Location of Laravel Files

* Model at [app/Product.php](https://github.com/sarulse/SampleCode/blob/master/productlaravelapp/app/Product.php)
* Controller at [app/Http/Controllers/Products.php](https://github.com/sarulse/SampleCode/blob/master/productlaravelapp/app/Http/Controllers/Products.php)
* Database migrations at [/database/migrations](https://github.com/sarulse/SampleCode/blob/master/productlaravelapp/database/migrations)
* Routes at [app/Http/routes.php](https://github.com/sarulse/SampleCode/blob/master/productlaravelapp/app/Http/routes.php) 


## Location of Angular Files

* Controller at [public/app/controllers/products.js](https://github.com/sarulse/SampleCode/blob/master/productlaravelapp/public/app/controllers/products.js) 
* App file to define the application at [public/app/app.js](https://github.com/sarulse/SampleCode/blob/master/productlaravelapp/public/app/app.js) 
* Display data from the Rest API using Angular JS at [resources/views/index.php](https://github.com/sarulse/SampleCode/blob/master/productlaravelapp/resources/views/index.php) 

## Input Validation using Angular JS

At [index.php] (https://github.com/sarulse/SampleCode/blob/master/productlaravelapp/resources/views/index.php) : ng-show is used to display error messages if the required field is not filled 

## Location of the Snapshots

Snapshots of the app with each CRUD operation can be seen in the pdf : [SnaphotsOfLaravelApp.pdf](https://github.com/sarulse/SampleCode/blob/master/productlaravelapp/SnaphotsOfLaravelApp.pdf)


