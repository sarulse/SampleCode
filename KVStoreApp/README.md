# Sample Key Value Store
Purpose: To build a Key Value Store that is transient.

Languages: PHP, HTML, jquery, Ajax, bootsrap
App can be viewed at http://www.sarulsel.us/KVStoreApp/



## Summary
    * Created a key-value store backend, with a 'get(key)' operation and a 'set(key, value)' operation.
    * Created a frontend webpage to interact with the backend.
    

## To run the application locally
*   git clone https://github.com/sarulse/SampleCode.git
    Required folder: "KStoreApp" hence other folders can be ignored by sparse checkout and shallow clone.
*   Once you have the folder KVStoreApp in your local
*   cd into the directory
    `cd KVSToreApp`
*   Then at command prompt: `$ php -S localhost:8080`

    View the app at http://localhost:8080 

    
### Use cases:
    * Enter data for key and value field through the frontend
    * The key field will show a list of existing key names if atleast one character is entered
    * The value field will auto populate with the key selected from the list
    * Update key value through the value field
    * To view the Key Value Store: Toggle the button: 'Click here to view/hide the Key Value Store'
    

## File Structure

* PHP Backend code at [kvStore.php] (https://github.com/sarulse/SampleCode/blob/master/KVStoreApp/kvStore.php)
* JS code at [store.js] (https://github.com/sarulse/SampleCode/blob/master/KVStoreApp/store.js)
* HTML code at [index.html] (https://github.com/sarulse/SampleCode/blob/master/KVStoreApp/index.html)


## Input Validation using HTML5


