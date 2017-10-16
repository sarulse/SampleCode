$(document).ready(function(){
        $('.results').each(function(index) {
            $(".showDetail"+index).hide(); 
            $("#showResult"+index).click(function(){                        
                 $(".showDetail"+index).toggle(); 
                  return false;                
            });
        });
});