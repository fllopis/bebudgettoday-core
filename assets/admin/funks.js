var requestsAjax = [];

//Prevent ajax exceeded consulting
const cancelAjax = () => {
	for(i=0;i<requestsAjax.length;i++){
		requestsAjax[i].abort();
	}
	requestsAjax = new Array();
}

$(document).ready(function(){
    $('.btn-login').on('click', function(){
        getReCAPTCHA();
    });
});

//Generating or updating reCAPTCHA
const getReCAPTCHA = () => {
    grecaptcha.ready(function() {
        grecaptcha.execute(reCAPTCHA, {action: 'submit'}).then(function(token) {

            //Checking if exists
            const existingInput = document.querySelector('input[name="g-recaptcha-response"]');
            if (existingInput) {
                existingInput.value = token;
            } else {
                var input = document.createElement("input");
                input.type = "hidden";
                input.name = "g-recaptcha-response";
                input.value = token;
                document.getElementById("reCAPTCHA-G").appendChild(input);
            }
            
            //on finish, do login
            ajaxManageAdminLogin();
        });
    });
};

//Function to manage the admin login
const ajaxManageAdminLogin = () => {

    //Then take the form.
	var formData = new FormData($("#form_login")[0]);

    cancelAjax();
	requestAjax = $.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-manage-admin-login/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){
            $('.error').css('display', 'none');
            $('.error span').html("");
            $('.loading-form').css('display', 'inline-block');
            $('.btn-login').css('display', 'none');
        },
 		success: function(data){

 			data = JSON.parse(data);

            $('.loading-form').css('display', 'none');
            $('.btn-login').css('display', 'inline-block');

 			if(data.type == 'success'){

                //Redirect to enter in backend
                window.location.replace(dominio + 'admin/');
 			}
 			else{
                $('.error span').html(data.error);
                $('.error').slideDown('slow');
 			}
		}
	});
    requestsAjax.push(requestAjax);
}