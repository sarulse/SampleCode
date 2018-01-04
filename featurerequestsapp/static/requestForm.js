ko.validation.rules.pattern.message = 'Invalid.';

ko.validation.init({
    registerExtenders: true,
    messagesOnModified: true,
    insertMessages: true,
    parseInputAttributes: true,    
    messageTemplate: null
}, true);

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
    self.title = ko.observable().extend({required: {message: 'Title is required'}, minLength: 2, maxLength: 40 });
    self.description = ko.observable().extend({required: {message: 'Description is required' } });
    self.clientList = ['Client A', 'Client B', 'Client C'];
    self.selectedClient = ko.observable().extend({required: {message: 'Client is required' } });
    minDate =  new Date().toISOString().slice(0, 10);
    self.client_priority = ko.observable().extend({required:{ message: 'Priority is required'}, min: 1 }, {digit: true});     
    self.target_date = ko.observable().extend({required: {message: 'Target Date is required'},  min: minDate});
    self.productAreas = ['Policies', 'Billing', 'Claims', 'Reports'];
    self.selectedProductArea = ko.observable().extend({required: {message: 'Product Area is required' } });;
      
    self.errors = ko.validation.group(self);
         
    self.addFeatureRequest = function() {	
	self.save();	
    };    
     
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
		console.log("Printing feature requests");
		console.log(data['target_date']);
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
		//console.log("self.errors.length in Error "+ self.errors().length)
		console.log("data");
		console.log("self.title"+ self.title);
		console.log("self.Description"+ self.description);
		console.log("self.clientName"+ self.selectedClient);
		console.log("self.priority"+ self.client_priority);
		console.log("self.targetDate"+ self.target_date);
		console.log("self.productArea"+ self.selectedProductArea);
		
		console.log("end");
		if (self.errors().length > 0) {
		    alert('Invalid inputs, please check your form values.');
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

