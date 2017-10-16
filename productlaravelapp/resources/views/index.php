<!DOCTYPE html>
<html lang="en-US" ng-app="productRecords">
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>A Simple Laravel App with Angular JS</title>

		<!-- load bootstrap css via cdn -->		
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css"> 
		
		 <!-- Load Javascript Libraries (AngularJS, JQuery, Bootstrap) -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.8/angular.min.js"></script> 
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>	
		        
		<!-- AngularJS Application Scripts -->
        <script src="<?= asset('app/app.js') ?>"></script>
        <script src="<?= asset('app/controllers/products.js') ?>"></script>
       
    </head>
    <body>
        <h2>List of Products in the Warehouse</h2>
        <div  ng-controller="productsController">

            <!-- Table-to-load-the-data Part -->
            <table class="table">
                <thead>
                    <tr>
                        <!--<th>ID</th>-->
                        <th>Name</th>
                        <th>Description</th>
                        <th>Manufacturer</th>
                        <th>Number of items in Stock</th>
                        <th><button id="btn-add" class="btn btn-primary btn-xs" ng-click="toggle('add', 0)">Add New Product</button></th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="product in products">
                        <!--<td>{{  product.id }}</td>-->
                        <td>{{ product.name }}</td>
                        <td>{{ product.description }}</td>
                        <td>{{ product.manufacturer }}</td>
                        <td>{{ product.num_stock }}</td>
                        <td>
                            <button class="btn btn-default btn-xs btn-detail" ng-click="toggle('edit', product.id)">Edit</button>
                            <button class="btn btn-danger btn-xs btn-delete" ng-click="confirmDelete(product.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- End of Table-to-load-the-data Part -->
            <!-- Modal (Pop up when detail button clicked) -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="myModalLabel">{{form_title}}</h4>
                        </div>
                        <div class="modal-body">
                            <form name="frmProducts" class="form-horizontal" novalidate="">

                                <div class="form-group error">
                                    <label for="productName" class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control has-error" id="name" name="name" placeholder="Product Name" value="{{name}}" 
                                        ng-model="product.name" ng-required="true">
										<span class="help-inline" 
                                        ng-show="frmProducts.$dirty && frmProducts.name.$error.required">Product name is required</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="productDesc" class="col-sm-3 control-label">Description</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="description" name="description" placeholder="Product Description" value="{{description}}" 
                                        ng-model="product.description" ng-required="false">										
                                        <span class="help-inline" 
                                        ng-show="frmProducts.$dirty && frmProducts.description.$touched ">Description field is required</span>									
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="manufacturer" class="col-sm-3 control-label">Manufacturer</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="manufacturer" name="manufacturer" placeholder="Product Manufacturer" value="{{manufacturer}}" 
                                        ng-model="product.manufacturer" ng-required="true">
                                    <span class="help-inline" 
                                        ng-show="frmProducts.$dirty  && frmProducts.manufacturer.$error.required">Manufacturer name is required</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="num" class="col-sm-3 control-label">Number of Items in Stock</label>
                                    <div class="col-sm-9">
                                        <input type="integer" class="form-control" id="num_stock" name="num_stock" placeholder="Product Stock" value="{{num_stock}}" 
                                        ng-model="product.num_stock" ng-required="true">
                                    <span class="help-inline" 
                                        ng-show="frmProducts.$dirty && frmProducts.num_stock.$error.required">Number of items in stock is required</span>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="btn-save" ng-click="save(modalstate, id)"> 
								Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
		
    </body>
</html>