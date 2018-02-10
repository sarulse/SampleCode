<?php
    
    session_start();    
           
    class KVStore {
        private $keyName;
        private $valueName;
                
        public function setKey($keyName, $valueName){
            $_SESSION[$keyName] = $valueName;
            
        }
        
        public function getkey($keyName){
            return $_SESSION[$keyName];
        }      
        
    }
    //create KV store
    $kv_store = new KVStore();
        
    //Store the new/updated value of the key in the store and print the store content
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {                
                if ((empty($_POST['keyName'])) && (empty($_POST['valueName']))){
                    throw new Exception('Could not create Key Value Store');  
                }                                  
                $key_name = htmlentities(strtolower($_POST['keyName']));
                $value_name = htmlentities($_POST['valueName']);                
                $kv_store->setKey($key_name, $value_name);                
                                
        }catch(Exception $e) {
                echo 'Message: ' .$e->getMessage();
        }
        finally {
            echo "<h4>\tKey:\t\t\t\t\t\tValue<br/></h4>";
            ksort($_SESSION);            
            foreach ($_SESSION as $key=> $val){
                echo "\t".$key.":\t\t\t\t\t\t".$val."<br/>";
            }    
        }
    }
      
    //To handle autocomplete key name suggestions   
    if (!empty($_GET['term'])){        
            if (!empty(array_keys($_SESSION))){            
                $keys = array_values(array_keys($_SESSION));           
                echo json_encode($keys);                
            }             
    }     
    //To display value in the value field using the key entered
    if (!empty($_GET['keyName'])) {
        $val = $_SESSION[$_GET['keyName']];
        if (!empty($val)){                   
            echo json_encode($val);
        }           
    }    
    
    //session_destroy();
      
?>    

