//Angular controller file to interact with Rest API

app.controller('productsController', function($scope, $http, API_URL) {
    //retrieve products listing from API
    $http.get(API_URL + "products")
            .success(function(response) {
                $scope.products = response;
            });
    
    //show modal form
    $scope.toggle = function(modalstate, id) {
        $scope.modalstate = modalstate;

        switch (modalstate) {
            case 'add':
                $scope.form_title = "Add New Product";
                break;
            case 'edit':
                $scope.form_title = "Edit Product Detail";
                $scope.id = id;
                $http.get(API_URL + 'products/' + id)
                        .success(function(response) {
                            console.log(response);
                            $scope.product = response;
                        });
                break;
            default:
                break;
        }
        console.log(id);
        $('#myModal').modal('show');
    }

    //save new record / update existing record
    $scope.save = function(modalstate, id) {
        var url = API_URL + "products";
        
        //append product id to the URL if the form is in edit mode
        if (modalstate === 'edit'){
            url += "/" + id;
        }        
        $http({
            method: 'POST',
            url: url,
            data: $.param($scope.product),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
            console.log(response);
            location.reload();
        }).error(function(response) {
            console.log(response);
            alert('An error has occurred, please enter valid data');
        });
    }

    //delete record
    $scope.confirmDelete = function(id) {
        var isConfirmDelete = confirm('Are you sure you want to remove the selected product?');
		console.log(isConfirmDelete);
        if (isConfirmDelete) {
            $http({
                method: 'DELETE',
                url: API_URL + 'products/' + id
            }).
                    success(function(data) {
                        console.log(data);
                        location.reload();
                    }).
                    error(function(data) {
                        console.log(data);
                        alert('Unable to delete');
                    });
        } else {
            return false;
        }
    }
});