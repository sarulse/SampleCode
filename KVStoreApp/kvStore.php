<?php
    
    session_start();    
           
    class KVStore {
        private $keyName;
        private $valueName;
        private $dataStore = array();        
        
        public function setKey($keyName, $valueName){
            $this->dataStore[$keyName] = $valueName;            
        }
        
        public function getkey($keyName){
            return $this->dataStore[$keyName];
        }
        
    }  
      
    //To handle autocomplete key name suggestions   
    if (!empty($_GET['term'])){        
            if (!empty(array_keys($_SESSION))){            
                $keySuggestions = array_keys($_SESSION);
                $keys = array_values($keySuggestions);           
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
    
    //Store the new/updated value of the key in the store and print the store content
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {                
                if ((empty($_POST['keyName'])) && (empty($_POST['valueName']))){
                    throw new Exception('Could not create Key Value Store');  
                }    
                $kvStore = new KVStore();
                $key_name = htmlentities(strtolower($_POST['keyName']));
                $value_name = htmlentities($_POST['valueName']);
                $kvStore->setKey($key_name, $value_name);
                $value = $kvStore->getkey($key_name);
                //echo "Value: ",$value."\n";       
                $_SESSION[$key_name]= $value;                
                                
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
    //session_destroy();
      
?>    

