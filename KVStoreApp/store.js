$(document).ready(function(){
                
    $("#buttonToggle").hide();
    // To store key value Data
    $("#storeData").html('');                       
    //To autocomplete key names
    $(".auto").autocomplete({                    
            source: "kvStore.php",
            minLength: 1                                         
    });                 
    // Variable to hold ajaxRequest
    var ajaxRequest;
                                                
    //To display matching value of key in the value field              
    $('#keyName').change(function() {
        var key_name =  $('#keyName').val();
        console.log("Iam in change");
        console.log("key value entered: "+ key_name);
        ajaxRequest = $.ajax({
            url: "/kvStore.php",
            type: "get",
            datatype : "json",
            data: {keyName: key_name }
        });
        ajaxRequest.done(function (response, status, jqXHR){
            // Log a message to the console
            console.log("Value received: \n" + response);
            $(".valueReceived").val(JSON.parse(response));
        });                   
    });
    
    //To post the key value data to the store
    $("#kvform").submit(function(event){
        $("#storeData").hide();
        // Prevent default posting of form - put here to work in case of errors
        event.preventDefault();
        
        if (ajaxRequest) {
            ajaxRequest.abort();
        }                    
        var $form = $(this);                
        var $inputs = $form.find("input");
        var serializedData = $form.serialize();    
        // Disable the inputs for the duration of the Ajax ajaxRequest.
        $inputs.prop("disabled", true);    
        // Fire off the ajaxRequest to /kvStore.php
        ajaxRequest = $.ajax({
            url: "/kvStore.php",
            type: "post",
            datatype : "application/json",
            data: serializedData
        });                
        ajaxRequest.done(function (response, status, jqXHR){
            // Log a message to the console
            console.log("Hooray, it worked!");
            console.log("Key and value entered: \n" + response);
            $("#buttonToggle").show();
            $("#buttonToggle").click(function(){
                $("#storeData").html(response);
                $("input[type=text]").val('');
                $("#storeData").toggle();                            
            });                        
        });                
        ajaxRequest.fail(function (jqXHR, status, errorThrown){
            // Log the error to the console
            console.error(
                "The following error occurred: "+
                status, errorThrown
            );
            $("#storeData").html('Error');
        });                
        // Re-enable the inputs after ajax request
        ajaxRequest.always(function () {                        
            $inputs.prop("disabled", false);
        });
    });    
                         
});                   
                
        
