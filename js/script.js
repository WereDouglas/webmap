$(document).ready(function(){    
    
		$('#register').validate({
	    rules: {
	       username: {
	         required: true,
                 minlength: 3   
	      },
		  
		 description: {
	        minlength: 6,
	        required: true
	      },
		  
		  password: {
				required: true,
				minlength: 6
			},
			password2: {
				required: true,
				minlength: 6,
				equalTo: "#password"
			},
		  
	      email: {
	        required: true,
	        email: true
	      },
                number: {
	         required: true,
	         minlength: 7,
                number: true
	      },
               
            message: {
	        required: true,
                minlength: 6,
                maxlength: 130
	        
	      },
		  
	     
		   address: {
	      	minlength: 10,
	        required: true
	      },
		  
		  agree: "required"
		  
	    },
     
			highlight: function(element) {
				$(element).closest('.control-group').removeClass('success').addClass('error');
			},
			success: function(element) {
				element
				.text('OK!').addClass('valid')
				.closest('.control-group').removeClass('error').addClass('success');
			}
	  });

}
        
        
        ); // end document.ready