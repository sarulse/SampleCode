ko.extenders.required = function(target, overrideMessage) {
    //add some sub-observables to our observable
    target.hasError = ko.observable();
    target.validationMessage = ko.observable();
 
    //define a function to do validation
    function validate(newValue) {
       target.hasError(newValue ? false : true);
       target.validationMessage(newValue ? "" : overrideMessage || "This field is required");
    }
 
    //initial validation
    validate(target());
 
    //validate whenever the value changes
    target.subscribe(validate);
 
    //return the original observable
    return target;
};

function FeatureRequest(data) {
    console.log("FeatureRequestData");
    this.id = ko.observable(data.id);
    this.title = ko.observable(data.title);
    this.description = ko.observable(data.description);
    //this.clientList = ko.observableArray(data.clientList);
    this.client_name = ko.observable(data.selectedClient);
    this.client_priority = ko.observable(data.client_priority);
    this.target_date = ko.observable(data.target_date);
    //this.productAreas = ko.observableArray(data.productAreas);
    this.product_area = ko.observable(data.selectedProductArea);
}

function RequestsViewModel() {
    var self = this;
    self.requests = ko.observableArray([]);
    self.title = ko.observable().extend({ required: "Please enter a title", minLength: 2, maxLength: 40 });
    self.description = ko.observable().extend({required: "Please enter a description" });
    self.clientList = ko.observableArray(['Client A', 'Client B', 'Client C']).extend({required: "Please select"});
    self.selectedClient = ko.observable();
    self.client_priority = ko.observable().extend({required: true, min: 1 });     
    self.target_date = ko.observable().extend({required: "Please choose a Date" });
    self.productAreas = ko.observableArray(['Policies', 'Billing', 'Claims', 'Reports']).extend({required: "Please select"});
    self.selectedProductArea = ko.observable();
        
    self.errors = ko.validation.group(self);  
    self.addFeatureRequest = function() {
	self.save();
	self.title("");
	self.description("");
	self.clientList("");
	//self.selectedClient("");
	self.client_priority("");
	self.target_date("");
	self.productAreas("");
	//self.selectedProductArea("");
	
    };
    
    /*  
    self.showRequests = function() {
	console.log("showing requests");
	$.getJSON("/showEnteredRequest", function(data) {
        ko.mapping.fromJS(data, RequestsViewModel);
	
      });
    }
    */    
    self.save = function() {
	return $.ajax({
	    url: '/addFeatureRequests',
	    contentType: 'application/json',
	    type: 'POST',
	    data: JSON.stringify({
		'title': self.title(),
		'description': self.description(),
		'client_name': self.selectedClient(),
		'client_priority': self.client_priority(),
		'target_date': self.target_date(),
		'product_area': self.selectedProductArea()
	    }),
	    success: function(data) {
		console.log("Add to Requests list");		
		self.requests.push(new FeatureRequest({
		    title: data.title,
		    description: data.description,
		    client_name: data.client_name,
		    client_priority: data.client_priority,
		    target_date: data.target_date,		    
		    product_area: data.product_area,
		    id: data.id}));
		window.location='/showEnteredRequest';
		return;	   
		
	    },  
	    error: function() {
		console.log("Error length "+ self.errors().length);
		alert(self.errors().length);
		if ( self.errors().length > 0) {
		    alert('Please check your submission.');
		    self.errors.showAllMessages();
		}
		return console.log("Failed");
	    }
	});
    };
    
    self.reset = function() {	
	Object.keys(self).forEach(function(name) {
	    if (ko.isWritableObservable(self[name])) {
		self[name](undefined);
	    }
	});		
	self.errors.showAllMessages(false);
    };
     
   
   
}

    
ko.applyBindings(new RequestsViewModel());

