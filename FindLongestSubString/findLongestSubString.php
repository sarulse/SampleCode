<?php

class LongestString
{ 
    //Get all unique substrings from a string
    public static function getAllUniqueSubStrings($str)
    {
        $subs = [];        
        $length = mb_strlen($str);       
        for ($i = 0; $i < $length; $i++) {
           for ($j = 1; $j <= $length; $j++) {
                $subs[] = mb_substr($str, $i, $j);
            }
        }        
        $subs = array_unique($subs);       
        return ($subs); 
    }
    //Verify if unique characters from a string matches the given count
    public static function checkUniqueChars($str,$unique_chars_num )
    {        
        $count_unique = count(array_unique(str_split($str)));       
        if ($count_unique == $unique_chars_num) {            
            return true;            
        }       
        return null;
    }
    //Get Sub String Array with length of the strings
    public static function getSubStringWithLength($str,$unique_chars_num){
        $sub_array == [];
        $subStringsArr = LongestString::getAllUniqueSubStrings($str);
        for ($i=0; $i<count($subStringsArr); $i++){
            $unique_match = LongestString::checkUniqueChars($subStringsArr[$i], $unique_chars_num);
            if ( $unique_match){        
                 $sub_array[$subStringsArr[$i]] = strlen($subStringsArr[$i]);
            }    
        }
        return $sub_array;
    }
    //find the length of longest Sub string and the starting position
    public static function findLongestSubString($str,$unique_chars_num){             
        $arr_subs=LongestString::getSubStringWithLength($str, $unique_chars_num);        
        //Get the max value from the sub string array
        $max_length = max($arr_subs);
        //Get the longest string from the sub string array
        $longest_string = array_search($max_length, $arr_subs);
        $pos = strpos($str, $longest_string);
        echo "\nOutput: \n";  
        try{
            if (empty($str) || (empty($unique_chars_num) || empty($max_length))) 
                throw new Exception ("String/Max Length/Number of unique characters is invalid");
            else {               
                foreach ($arr_subs as $substr => $length) {
                   echo "{$substr} length {$length}\n"; 
                }   
                echo "\nLongest Substring: ". $longest_string .",length: ".$max_length ."(Max length),";
                echo  "starting position: ". $pos." matching $unique_chars_num unique characters in the input string: ". $str. "\n";    }
        }
        catch (Exception $e) {
           echo 'Caught exception: ',  $e->getMessage(), "\n";
        }     
        
    }
    
}
//Input Values for String and number of unique characters
$string = $argv[1]; //getting input string from the command line
//$string = 'dgfddsssdsfdffdfdfadfhheedsfdfcdfdxffdh';
//Required number of Unique characters in the substrings
$no_unique_chrs = $argv[2]; //getting num_unique characters from the command line
//$no_unique_chrs = 2;
echo "Input:\n";
echo "String: ".$string."\n";
echo "Number of Unique Characters: ".$no_unique_chrs."\n";

//To return longest Substring length, starting position with matching unique characters in the input string
LongestString::findLongestSubString($string, $no_unique_chrs);

