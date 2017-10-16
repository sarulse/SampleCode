function validatePhone(){  
            var num = document.forms["personSearch"]["phone"].value;    
            var pattern = /^\(?(\d{3})\)?[-. ]?(\d{3})[-. ]?(\d{4})$/;  
            var result = pattern.test(num); 
            var mesg = "Enter a valid US Phone Number Format:\n(123)-4567890\n (or) 1234567890\n (or) 123-456-7890\n (or) 123-4567890";
            if (result === false) {
                alert(mesg);
                return false;                   
            }              
} 
