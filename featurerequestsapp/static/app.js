function FeatureRequest(data) {
    console.log("FeatureRequestData");
    this.id = ko.observable(data.id);
    this.title = ko.observable(data.title);
    this.description = ko.observable(data.description);
    this.clientList = ko.observableArray(data.clientList);
    this.client_name = ko.observable(data.selectedClient);
    this.client_priority = ko.observable(data.client_priority);
    this.target_date = ko.observable(data.target_date);
    this.productAreas = ko.observableArray(data.productAreas);
    this.product_area = ko.observable(data.selectedProductArea);
}

function RequestsViewModel() {
    var self = this;
    self.requests = ko.observableArray([]);
    self.title = ko.observable();
    self.description = ko.observable();
    self.clientList = ko.observableArray(['Client A', 'Client B', 'Client C']);
    self.selectedClient = ko.observable();
    self.client_priority = ko.observable();     
    self.target_date = ko.observable();
    console.log("Target Date:" + self.target_date);
    self.productAreas = ko.observableArray(['Policies', 'Billing', 'Claims', 'Reports']);
    self.selectedProductArea = ko.observable();
    
    self.addFeatureRequest = function() {
	self.save();
	self.title("");
	self.description("");
	self.clientList("");
	self.selectedClient("");
	self.client_priority("");
	self.target_date("");
	self.productAreas("");
	self.selectedProductArea("");	
    };
    
    /*
    self.showRequests = function() {
	$.getJSON("/showAllFeatureRequests", function(data) {
        ko.mapping.fromJS(data, RequestsViewModel);
      });
    }
    */

    self.save = function() {
	var data_to_send = JSON.stringify({
		'title': self.title(),
		'description': self.description(),
		'client_name': self.selectedClient(),
		'client_priority': self.client_priority(),
		'target_date': self.target_date(),
		'product_area': self.selectedProductArea()
	    });
        $.post("/addFeatureRequests", data_to_send,
	        function(data) {
		    console.log("Add to Requests list");		
		    self.requests.push(new FeatureRequest({
			title: data.title,
			description: data.description,
			client_name: data.client_name,
			client_priority: data.client_priority,
			target_date: data.target_date,		    
			product_area: data.product_area,
			id: data.id}));
		    alert("Your data has been posted to the server!");
		    return; 
		    })          
    }

}
ko.applyBindings(new RequestsViewModel());