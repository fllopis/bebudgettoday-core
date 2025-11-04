//Variable para introducir las consultas ajax que requieran cancelar anteriores consultas
var consultasAjax = [];

//Cancela las consultas ajax que pertenecen al array consultasAjax[]
function cancelarAjax(){

	for(i=0;i<consultasAjax.length;i++){
		consultasAjax[i].abort();
	}
	consultasAjax = new Array();
}

/************************************************
 *												*
 *	   		FUNCTIONS ABOUT ME  				*
 * 												*
 ************************************************/

 //Function to manage update about me 
const ajaxModalManageAbout = (id) => {

	var formData = new FormData();
	formData.append('id', id);

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-get-modal-manage-about/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
		success: function(data){
			//Parsing json data.
			data = JSON.parse(data);

			if(data.type == 'success')
				$("#manageAboutData__content").html(data.html);
			else{
				console.log(data.error);
				$("#manageAboutData__content").html("Revisa la consola");
			}

		}
	});
}

//Function to manage the translation of about me
const ajaxManageAbout = (slug_lang) => {

	var formData = new FormData($("#form_manage_about_"+slug_lang)[0]);
	action 		= formData.get('action');
	slug 		= formData.get('slug');
	lang_name 	= formData.get('lang_name');

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-manage-about/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
 		success: function(data){

 			data = JSON.parse(data);

 			if(data.type == 'success'){

 				//Segun action mostramos mensaje
 				if(action == 'create'){
 					sw_message('New translation', 'Translation has been created for <strong>' + lang_name + '</strong> correctly. The popup will be updated.', 'success');

	 				ajaxModalManageAbout(data.id);
 				}
 				else
 					sw_message('Updated', 'Translation has been updated for <strong>' + lang_name + '</strong> correctly.', 'success');

 			}
 			else{
 				//Mostramos el error
 				sw_message('Oops', data.error, 'warning');
 			}
		}
	});
}

//Function to manage the about base
const ajaxManageAboutBase = () => {

	var formData = new FormData($("#form_manage_about_base")[0]);
	var action 		= formData.get('action');
	var id 			= formData.get('id');

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-manage-about-base/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
		success: function(data){

			data = JSON.parse(data);

			if(data.type == 'success'){

				//Showing message in base to action
				if(action == 'create')
					sw_message('New image', 'The image has been successfully created. The popup will be updated.', 'success');
				else
					sw_message('Image updated', 'The language data has been updated correctly. The popup will be updated.', 'success');

				//Refresh popup
				// ajaxModalManageAbout(data.id, comienzo, limite, pagina);

				//Updating images on HMTL
				$('.about-me img').attr("src", data.image);
			}
			else{
				sw_message('Oops', data.error, 'warning');
			}
		}
	});
}

/************************************************
 *												*
 *	   		FUNCTIONS ABOUT BLOCKS 				*
 * 												*
 ************************************************/

//Function to get the blocks filtered
const ajaxGetAboutBlocksFiltered = (comienzo, limite, pagina) =>{

    var formData = new FormData($("#form_filters")[0]);
    formData.append('comienzo', comienzo);
    formData.append('pagina', pagina);
    formData.append('limite', limite);

    $.ajax({
        type: "POST",
        url: dominio+"ajax/ajax-get-blocks-filtered/",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){
			//Mostramos loading
			$('#loadingContentBlocks').css('display', 'block');

			//Ocultamos tabla de tours
			$("#table_content_blocks").css('display', 'none');
			$("#table_content_blocks").html('');
		},
 		success: function(data){
 			
 			data = JSON.parse(data);

 			//Ocultamos loading
			$('#loadingContentBlocks').css('display', 'none');

 			//Si el resultado es correcto, devolvera el HTML
 			if(data.type == 'success')
				$("#table_content_blocks").html(data.html);
			else{
				console.log(data.error);
				$("#table_content_blocks").html("<div class='alert alert-warning'>An internal error has occurred, if the problem persists report it.</div>");
			}

			//Ocultamos tabla de tours
			$("#table_content_blocks").css('display', 'block');
		}
    });
}

//Function to manage new or update block
const ajaxModalManageAboutBlock = (id, comienzo, limite, pagina) => {

	var formData = new FormData();
	formData.append('id', id);
	formData.append('comienzo', comienzo);
	formData.append('limite', limite);
	formData.append('pagina', pagina);

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-get-modal-manage-about-block/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
		success: function(data){
			//Parsing json data.
			data = JSON.parse(data);

			if(data.type == 'success')
				$("#manageAboutData__content").html(data.html);
			else{
				console.log(data.error);
				$("#manageAboutData__content").html("Revisa la consola");
			}

		}
	});
}

//Function to manage the about block base
const ajaxManageAboutBlockBase = () => {

	var formData = new FormData($("#form_manage_about_block_base")[0]);
	var action 		= formData.get('action');
	var comienzo 	= formData.get('comienzo');
	var limite 		= formData.get('limite');
	var pagina 		= formData.get('pagina');
	var id 			= formData.get('id');

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-manage-about-block-base/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
		success: function(data){

			data = JSON.parse(data);

			if(data.type == 'success'){

				sw_message('New block', 'The block has been successfully created. The popup will be updated to add translations.', 'success');

				ajaxModalManageAboutBlock(data.id, comienzo, limite, pagina);

				//Updating the table
				ajaxGetAboutBlocksFiltered(comienzo, limite, pagina);

			}
			else{
				sw_message('Oops', data.error, 'warning');
			}
		}
	});
}

//Function to manage the translation or block
const ajaxManageAboutBlock = (slug_lang) => {

	var formData = new FormData($("#form_manage_about_block_"+slug_lang)[0]);
	action 		= formData.get('action');
	comienzo 	= formData.get('comienzo');
	limite 		= formData.get('limite');
	pagina 		= formData.get('pagina');
	slug 		= formData.get('slug');
	lang_name 	= formData.get('lang_name');

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-manage-about-block/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
 		success: function(data){

 			data = JSON.parse(data);

 			if(data.type == 'success'){

 				//Segun action mostramos mensaje
 				if(action == 'create'){
 					sw_message('New translation', 'Translation block has been created for <strong>' + lang_name + '</strong> correctly. The popup will be updated.', 'success');

	 				ajaxModalManageTranslation(data.id, comienzo, limite, pagina);
 				}
 				else
 					sw_message('Updated', 'Translation block has been updated for <strong>' + lang_name + '</strong> correctly.', 'success');

 				//Update table
 				ajaxGetAboutBlocksFiltered(comienzo, limite, pagina);

 			}
 			else{
 				//Mostramos el error
 				sw_message('Oops', data.error, 'warning');
 			}
		}
	});
}

//Function to delete a about block
const ajaxDeleteAboutBlock = (id, comienzo, limite, pagina) =>{
	swal({
		title: 'Delete block?',
		text: 'You are about to remove the block in all languages.',
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Yes, delete it!',
		cancelButtonText: 'Close',
		confirmButtonClass: 'btn btn-danger',
		cancelButtonClass: 'btn btn-default m-l-10',
		buttonsStyling: false
	}).then(function () {

		var formData = new FormData();
		formData.append("id", id);
		
		$.ajax({
			type: "POST",
			url: dominio+"ajax/ajax-delete-about-block/",
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function(){},
	 		success: function(data){
	 			
	 			//Show response
	 			sw_message('Block deleted', 'The block has been completely removed.', 'success')

	 			//Refresh table
 				ajaxGetAboutBlocksFiltered(comienzo, limite, pagina);
			}
		});

	});
}

/************************************************
 *												*
 *	   		FUNCTIONS ABOUT SOCIAL 				*
 * 												*
 ************************************************/

//Function to get the social links filtered
const ajaxGetAboutSocialLinksFiltered = (comienzo, limite, pagina) =>{

    var formData = new FormData($("#form_filters")[0]);
    formData.append('comienzo', comienzo);
    formData.append('pagina', pagina);
    formData.append('limite', limite);

    $.ajax({
        type: "POST",
        url: dominio+"ajax/ajax-get-about-social-links-filtered/",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){
			//Mostramos loading
			$('#loadingContentSocial').css('display', 'block');

			//Ocultamos tabla de tours
			$("#table_content_social").css('display', 'none');
			$("#table_content_social").html('');
		},
 		success: function(data){
 			
 			data = JSON.parse(data);

 			//Ocultamos loading
			$('#loadingContentSocial').css('display', 'none');

 			//Si el resultado es correcto, devolvera el HTML
 			if(data.type == 'success')
				$("#table_content_social").html(data.html);
			else{
				console.log(data.error);
				$("#table_content_social").html("<div class='alert alert-warning'>An internal error has occurred, if the problem persists report it.</div>");
			}

			//Ocultamos tabla de tours
			$("#table_content_social").css('display', 'block');
		}
    });
}

//Function to manage new or update social
const ajaxModalManageAboutSocial = (id, comienzo, limite, pagina) => {

	var formData = new FormData();
	formData.append('id', id);
	formData.append('comienzo', comienzo);
	formData.append('limite', limite);
	formData.append('pagina', pagina);

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-get-modal-manage-about-social/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
		success: function(data){
			//Parsing json data.
			data = JSON.parse(data);

			if(data.type == 'success')
				$("#manageAboutData__content").html(data.html);
			else{
				console.log(data.error);
				$("#manageAboutData__content").html("Revisa la consola");
			}

		}
	});
}

//Function to manage the social link
const ajaxManageSocialLink = () => {

	var formData = new FormData($("#form_manage_about_social_link")[0]);
	action 		= formData.get('action');
	comienzo 	= formData.get('comienzo');
	limite 		= formData.get('limite');
	pagina 		= formData.get('pagina');

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-manage-about-social-link/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
 		success: function(data){

 			data = JSON.parse(data);

			console.log(data);

 			if(data.type == 'success'){

				//Closing modal
				$('.manage-about-data').modal('hide');

 				//Segun action mostramos mensaje
 				if(action == 'create')
 					sw_message('Created', 'Social link has been created correctly.', 'success');
 				else
 					sw_message('Updated', 'Social link has been updated correctly.', 'success');

 				//Update table
 				ajaxGetAboutSocialLinksFiltered(comienzo, limite, pagina);

 			}
 			else{
 				//Mostramos el error
 				sw_message('Oops', data.error, 'warning');
 			}
		}
	});
}

//Function to delete about social link
const ajaxDeleteAboutSocial = (id, comienzo, limite, pagina) =>{
	swal({
		title: 'Delete social link?',
		text: 'You are about to remove the social link.',
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Yes, delete it!',
		cancelButtonText: 'Close',
		confirmButtonClass: 'btn btn-danger',
		cancelButtonClass: 'btn btn-default m-l-10',
		buttonsStyling: false
	}).then(function () {

		var formData = new FormData();
		formData.append("id", id);
		
		$.ajax({
			type: "POST",
			url: dominio+"ajax/ajax-delete-about-social-link/",
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function(){},
	 		success: function(data){
	 			
	 			//Show response
	 			sw_message('Social link deleted', 'The social link has been completely removed.', 'success')

	 			//Refresh table
 				ajaxGetAboutSocialLinksFiltered(comienzo, limite, pagina);
			}
		});

	});
}

/************************************************
 *												*
 *	   			FUNCTIONS  SKILLS 				*
 * 												*
 ************************************************/

//Function to get the project categories filtered
const ajaxGetSkills = () =>{

    var formData = new FormData($("#form_filters")[0]);

    $.ajax({
        type: "POST",
        url: dominio+"ajax/ajax-get-skills/",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){
			//Mostramos loading
			$('#loadingContent').css('display', 'block');

			//Ocultamos tabla de tours
			$("#table_content").css('display', 'none');
			$("#table_content").html('');
		},
 		success: function(data){
 			
 			data = JSON.parse(data);

 			//Ocultamos loading
			$('#loadingContent').css('display', 'none');

 			//Si el resultado es correcto, devolvera el HTML
 			if(data.type == 'success')
				$("#table_content").html(data.html);
			else{
				console.log(data.error);
				$("#table_content").html("<div class='alert alert-warning'>An internal error has occurred, if the problem persists report it.</div>");
			}

			//Ocultamos tabla de tours
			$("#table_content").css('display', 'block');
		}
    });
}

//Function to manage new or update category
const ajaxModalManageSkillInformation = () => {

	var formData = new FormData();

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-get-modal-manage-skill-information/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
		success: function(data){
			//Parsing json data.
			data = JSON.parse(data);

			if(data.type == 'success'){
				$("#manageSkillData__content").html(data.html);

				loadTinymce();
			}
			else{
				console.log(data.error);
				$("#manageSkillData__content").html("Revisa la consola");
			}

		}
	});
}

//Function to update translation of skill information
const ajaxManageSkillInformationTranslation = (slug_lang) => {

	let formData = new FormData($("#form_manage_skill_information_"+slug_lang)[0]);
	let action 		= formData.get('action');
	let slug 		= formData.get('slug');
	let lang_name 	= formData.get('lang_name');;

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-manage-skill-information-translations/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
		success: function(data){

			data = JSON.parse(data);

			if(data.type == 'success'){

				//Showging message in base to action
				if(action == 'create')
					sw_message('New translation', 'Skill information translation has been created for ' + lang_name + ' correctly.', 'success');
				else
					sw_message('Updated translation', 'Skill information translation has been updated for ' + lang_name + ' correctly.', 'success');

				//update content
				ajaxModalManageSkillInformation();
			}
			else{
				sw_message('Oops', data.error, 'warning');
			}
		}
	});
}

//Function to manage skill
const ajaxModalManageSkill = (id) => {

	var formData = new FormData();
	formData.append('id', id);

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-get-modal-manage-skill/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
		success: function(data){
			//Parsing json data.
			data = JSON.parse(data);

			if(data.type == 'success')
				$("#manageSkillData__content").html(data.html);
			else{
				console.log(data.error);
				$("#manageSkillData__content").html("Revisa la consola");
			}

		}
	});
}

//Function to update translation of language
const ajaxManageSkill = () => {

	var formData = new FormData($("#form_manage_skill")[0]);
	action 		= formData.get('action');

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-manage-skill/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
		success: function(data){

			data = JSON.parse(data);

			if(data.type == 'success'){

				//Showging message in base to action
				if(action == 'create')
					sw_message('New skill', 'Skill has been created correctly.', 'success');
				else
					sw_message('Skill updated', 'The skill has been updated correctly.', 'success');

				//Updating the table
				ajaxGetSkills();
				$('#closeSkill').click();

			}
			else{
				sw_message('Oops', data.error, 'warning');
			}
		}
	});
}

//Function to delete a project
const ajaxDeleteSkillById = (id) =>{
	swal({
		title: 'Delete skill?',
		text: 'You are about to remove the skill completely.',
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Yes, delete it!',
		cancelButtonText: 'Close',
		confirmButtonClass: 'btn btn-danger',
		cancelButtonClass: 'btn btn-default m-l-10',
		buttonsStyling: false
	}).then(function () {

		var formData = new FormData();
		formData.append("id", id);
		
		$.ajax({
			type: "POST",
			url: dominio+"ajax/ajax-delete-skill-by-id/",
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function(){},
	 		success: function(data){
	 			
	 			//Show response
	 			sw_message('Skill deleted', 'The skill has been completely removed.', 'success')

	 			//Refresh table
 				ajaxGetSkills();
			}
		});

	});
}

/****************************************************
 *													*
 *	   			FUNCTIONS PROJECTS 					*
 * 													*
 ****************************************************/

//Function to get the project filtered
const ajaxGetProjectsFiltered = (comienzo, limite, pagina) =>{

    var formData = new FormData($("#form_filters")[0]);
    formData.append('comienzo', comienzo);
    formData.append('pagina', pagina);
    formData.append('limite', limite);

    $.ajax({
        type: "POST",
        url: dominio+"ajax/ajax-get-projects-filtered/",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){
			//Mostramos loading
			$('#loadingContent').css('display', 'block');

			//Ocultamos tabla de tours
			$("#table_content").css('display', 'none');
			$("#table_content").html('');
		},
 		success: function(data){
 			
 			data = JSON.parse(data);

 			//Ocultamos loading
			$('#loadingContent').css('display', 'none');

 			//Si el resultado es correcto, devolvera el HTML
 			if(data.type == 'success')
				$("#table_content").html(data.html);
			else{
				console.log(data.error);
				$("#table_content").html("<div class='alert alert-warning'>An internal error has occurred, if the problem persists report it.</div>");
			}

			//Ocultamos tabla de tours
			$("#table_content").css('display', 'block');
		}
    });
}

//Function to get the project base data
const ajaxGetProjectBase = (id) =>{

    var formData = new FormData();
    formData.append('id', id);

    $.ajax({
        type: "POST",
        url: dominio+"ajax/ajax-get-project-base/",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){
			//Showing loading
			$('#loadingProjectContent').css('display', 'block');

			//Hide data
			$("#project_content").css('display', 'none');
			$("#project_content").html('');
		},
 		success: function(data){
 			
 			data = JSON.parse(data);

 			//hidde loading
			$('#loadingProjectContent').css('display', 'none');

 			//Si el resultado es correcto, devolvera el HTML
 			if(data.type == 'success'){
				$('#project-title').html(data.title);
				$("#project_content").html(data.html);
			}
			else{
				console.log(data.error);
				$("#project_content").html("<div class='alert alert-warning'>An internal error has occurred, if the problem persists report it.</div>");
			}

			$("#project_content").css('display', 'block');
		}
    });
}

//Function to manage the project base
const ajaxManageProjectsBase = () => {

	var formData = new FormData($("#form_manage_project_base")[0]);

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-manage-project-base/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
		success: function(data){

			data = JSON.parse(data);

			if(data.type == 'success'){

				//Redirect to project page.
				if(data.action == "create"){
					sw_message('New project', 'The project has been successfully created. You will be redirect to edit page.', 'success');

					window.location.replace(`${dominio}admin/project/${data.id}/`);
				} else {
					sw_message('Project updated', 'The project has been successfully updated.', 'success');

					ajaxGetProjectBase(data.id);
				}
			}
			else{
				sw_message('Oops', data.error, 'warning');
			}
		}
	});
}

//Function to get the project gallery
const ajaxGetProjectGallery = (id) =>{

    var formData = new FormData();
    formData.append('id', id);

    $.ajax({
        type: "POST",
        url: dominio+"ajax/ajax-get-project-gallery/",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){
			//Showing loading
			$('#loadingProjectGallery').css('display', 'block');

			//Hide data
			$("#project_gallery").css('display', 'none');
			$("#project_gallery").html('');
		},
 		success: function(data){
 			
 			data = JSON.parse(data);

 			//hidde loading
			$('#loadingProjectGallery').css('display', 'none');

 			//Si el resultado es correcto, devolvera el HTML
 			if(data.type == 'success')
				$("#project_gallery").html(data.html);
			else{
				console.log(data.error);
				$("#project_gallery").html("<div class='alert alert-warning'>An internal error has occurred, if the problem persists report it.</div>");
			}

			$("#project_gallery").css('display', 'block');
		}
    });
}

//Function to manage new project gallery
const ajaxManageNewProjectGallery = () => {

	let formData = new FormData($("#form_new_gallery")[0]);
	let id 		= formData.get('id');

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-manage-new-project-gallery/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
		success: function(data){

			data = JSON.parse(data);

			console.log("data", data);

			if(data.type == 'success'){
				$('#closeAddNewGalleryModal').click();
				sw_message('New image', 'The image gallery has been added.', 'success');

				ajaxGetProjectGallery(id);
			}
			else{
				sw_message('Oops', data.error, 'warning');
			}
		}
	});
}

//Function to delete an image of project's gallery
const ajaxDeleteProjectGalleryImage = (id, id_project) =>{
	swal({
		title: 'Delete image?',
		text: 'You are about to remove the image of gallery in the project.',
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Yes, delete it!',
		cancelButtonText: 'Close',
		confirmButtonClass: 'btn btn-danger',
		cancelButtonClass: 'btn btn-default m-l-10',
		buttonsStyling: false
	}).then(function () {

		var formData = new FormData();
		formData.append("id", id);
		formData.append("id_project", id_project);
		
		$.ajax({
			type: "POST",
			url: dominio+"ajax/ajax-delete-project-gallery-image/",
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function(){},
	 		success: function(data){
	 			
	 			//Show response
	 			sw_message('Image deleted', 'The image has been completely removed.', 'success')

	 			//Refresh table
 				ajaxGetProjectGallery(id_project);
			}
		});

	});
}

//Function to get the project translations
const ajaxGetProjectTranslations = (id_project) =>{

    var formData = new FormData();
    formData.append('id_project', id_project);

    $.ajax({
        type: "POST",
        url: dominio+"ajax/ajax-get-project-translations/",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){
			//Showing loading
			$('#loadingProjectTranslations').css('display', 'block');

			//Hide data
			$("#project_translations").css('display', 'none');
			$("#project_translations").html('');
		},
 		success: function(data){
 			
 			data = JSON.parse(data);

 			//hidde loading
			$('#loadingProjectTranslations').css('display', 'none');

 			//Si el resultado es correcto, devolvera el HTML
 			if(data.type == 'success'){
				$("#project_translations").html(data.html);

				loadTinymce();
			}
			else{
				console.log(data.error);
				$("#project_translations").html("<div class='alert alert-warning'>An internal error has occurred, if the problem persists report it.</div>");
			}

			$("#project_translations").css('display', 'block');
		}
    });
}

//Function to update translation of project
const ajaxManageProjectTranslation = (slug_lang) => {

	let formData = new FormData($("#form_manage_project_translation_"+slug_lang)[0]);
	let action 		= formData.get('action');
	let slug 		= formData.get('slug');
	let lang_name 	= formData.get('lang_name');
	let id_project 	= formData.get('id_project');

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-manage-project-translations/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
		success: function(data){

			data = JSON.parse(data);

			if(data.type == 'success'){

				//Showging message in base to action
				if(action == 'create')
					sw_message('New translation', 'Project translation has been created for ' + lang_name + ' correctly.', 'success');
				else
					sw_message('Updated translation', 'Project translation has been updated for ' + lang_name + ' correctly.', 'success');

				//Updating content of translation
				ajaxGetProjectTranslations(id_project);

			}
			else{
				sw_message('Oops', data.error, 'warning');
			}
		}
	});
}

//Function to delete a project
const ajaxDeleteProjectById = (id_project, comienzo, limite, pagina) =>{
	swal({
		title: 'Delete project?',
		text: 'You are about to remove the project completely.',
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Yes, delete it!',
		cancelButtonText: 'Close',
		confirmButtonClass: 'btn btn-danger',
		cancelButtonClass: 'btn btn-default m-l-10',
		buttonsStyling: false
	}).then(function () {

		var formData = new FormData();
		formData.append("id_project", id_project);
		
		$.ajax({
			type: "POST",
			url: dominio+"ajax/ajax-delete-project-by-id/",
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function(){},
	 		success: function(data){
	 			
	 			//Show response
	 			sw_message('Project deleted', 'The project has been completely removed.', 'success')

	 			//Refresh table
 				ajaxGetProjectsFiltered(comienzo, limite, pagina);
			}
		});

	});
}

/****************************************************
 *													*
 *	   		FUNCTIONS PROJECTS CATEGORIES 			*
 * 													*
 ****************************************************/

//Function to get the project categories filtered
const ajaxGetProjectsCategoriesFiltered = (comienzo, limite, pagina) =>{

    var formData = new FormData($("#form_filters")[0]);
    formData.append('comienzo', comienzo);
    formData.append('pagina', pagina);
    formData.append('limite', limite);

    $.ajax({
        type: "POST",
        url: dominio+"ajax/ajax-get-projects-categories-filtered/",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){
			//Mostramos loading
			$('#loadingContent').css('display', 'block');

			//Ocultamos tabla de tours
			$("#table_content").css('display', 'none');
			$("#table_content").html('');
		},
 		success: function(data){
 			
 			data = JSON.parse(data);

 			//Ocultamos loading
			$('#loadingContent').css('display', 'none');

 			//Si el resultado es correcto, devolvera el HTML
 			if(data.type == 'success')
				$("#table_content").html(data.html);
			else{
				console.log(data.error);
				$("#table_content").html("<div class='alert alert-warning'>An internal error has occurred, if the problem persists report it.</div>");
			}

			//Ocultamos tabla de tours
			$("#table_content").css('display', 'block');
		}
    });
}

//Function to manage new or update category
const ajaxModalManageProjectsCategory = (id, comienzo, limite, pagina) => {

	var formData = new FormData();
	formData.append('id', id);
	formData.append('comienzo', comienzo);
	formData.append('limite', limite);
	formData.append('pagina', pagina);

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-get-modal-manage-projects-category/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
		success: function(data){
			//Parsing json data.
			data = JSON.parse(data);

			if(data.type == 'success')
				$("#manageCategory__content").html(data.html);
			else{
				console.log(data.error);
				$("#manageCategory__content").html("Revisa la consola");
			}

		}
	});
}

//Function to manage the category base
const ajaxManageProjectsCategoryBase = () => {

	var formData = new FormData($("#form_manage_category_base")[0]);
	var action 		= formData.get('action');
	var comienzo 	= formData.get('comienzo');
	var limite 		= formData.get('limite');
	var pagina 		= formData.get('pagina');

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-manage-projects-category-base/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
		success: function(data){

			data = JSON.parse(data);

			if(data.type == 'success'){

				if(action == 'create'){
					sw_message('New category', 'The category has been successfully created. The popup will be updated to add translations.', 'success');

					ajaxModalManageProjectsCategory(data.id, comienzo, limite, pagina);
				} else {
					sw_message('Category updated', 'The category has been successfully updated.', 'success');
				}

				//Updating the table
				ajaxGetProjectsCategoriesFiltered(comienzo, limite, pagina);

			}
			else{
				sw_message('Oops', data.error, 'warning');
			}
		}
	});
}

//Function to manage the translation of category
const ajaxManageProjectsCategory = (slug_lang) => {

	var formData = new FormData($("#form_manage_category_"+slug_lang)[0]);
	action 		= formData.get('action');
	comienzo 	= formData.get('comienzo');
	limite 		= formData.get('limite');
	pagina 		= formData.get('pagina');
	slug 		= formData.get('slug');
	lang_name 	= formData.get('lang_name');

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-manage-projects-category/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
 		success: function(data){

 			data = JSON.parse(data);

 			if(data.type == 'success'){

 				//Segun action mostramos mensaje
 				if(action == 'create'){
 					sw_message('New translation', 'Translation has been created for <strong>' + lang_name + '</strong> correctly. The popup will be updated.', 'success');

	 				ajaxModalManageProjectsCategory(data.id, comienzo, limite, pagina);
 				}
 				else
 					sw_message('Updated', 'Translation has been updated for <strong>' + lang_name + '</strong> correctly.', 'success');

 				//Update table
 				ajaxGetProjectsCategoriesFiltered(comienzo, limite, pagina);

 			}
 			else{
 				//Mostramos el error
 				sw_message('Oops', data.error, 'warning');
 			}
		}
	});
}

//Function to delete a category
const ajaxDeleteProjectsCategory = (id, comienzo, limite, pagina) =>{
	swal({
		title: 'Delete category?',
		text: 'You are about to remove the category in all languages.',
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Yes, delete it!',
		cancelButtonText: 'Close',
		confirmButtonClass: 'btn btn-danger',
		cancelButtonClass: 'btn btn-default m-l-10',
		buttonsStyling: false
	}).then(function () {

		var formData = new FormData();
		formData.append("id", id);
		
		$.ajax({
			type: "POST",
			url: dominio+"ajax/ajax-delete-projects-category/",
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function(){},
	 		success: function(data){
	 			
	 			//Show response
	 			sw_message('Category deleted', 'The category has been completely removed.', 'success')

	 			//Refresh table
 				ajaxGetProjectsCategoriesFiltered(comienzo, limite, pagina);
			}
		});

	});
}

/****************************************************
 *													*
 *	   			FUNCTIONS CAREER 					*
 * 													*
 ****************************************************/

//Function to get the career type filtered
const ajaxGetCareerFiltered = (comienzo, limite, pagina, type) =>{

    var formData = new FormData($("#form_filters")[0]);
	formData.append('type', type);
    formData.append('comienzo', comienzo);
    formData.append('pagina', pagina);
    formData.append('limite', limite);

    $.ajax({
        type: "POST",
        url: dominio+"ajax/ajax-get-career-filtered/",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){
			//Mostramos loading
			$('#loadingContent').css('display', 'block');

			//Ocultamos tabla de tours
			$("#table_content").css('display', 'none');
			$("#table_content").html('');
		},
 		success: function(data){
 			
 			data = JSON.parse(data);

 			//Ocultamos loading
			$('#loadingContent').css('display', 'none');

 			//Si el resultado es correcto, devolvera el HTML
 			if(data.type == 'success')
				$("#table_content").html(data.html);
			else{
				console.log(data.error);
				$("#table_content").html("<div class='alert alert-warning'>An internal error has occurred, if the problem persists report it.</div>");
			}

			//Ocultamos tabla de tours
			$("#table_content").css('display', 'block');
		}
    });
}

//Function to manage new or update career
const ajaxModalManageCareer = (id, type, comienzo, limite, pagina) => {

	var formData = new FormData();
	formData.append('id', id);
	formData.append('type', type);
	formData.append('comienzo', comienzo);
	formData.append('limite', limite);
	formData.append('pagina', pagina);

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-get-modal-manage-career/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
		success: function(data){
			//Parsing json data.
			data = JSON.parse(data);

			if(data.type == 'success')
				$("#manageCareer__content").html(data.html);
			else{
				console.log(data.error);
				$("#manageCareer__content").html("Revisa la consola");
			}

		}
	});
}

//Function to manage the career base
const ajaxManageCareerBase = () => {

	var formData = new FormData($("#form_manage_career_base")[0]);
	var action 		= formData.get('action');
	var type 		= formData.get('type');
	var comienzo 	= formData.get('comienzo');
	var limite 		= formData.get('limite');
	var pagina 		= formData.get('pagina');

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-manage-career-base/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
		success: function(data){

			data = JSON.parse(data);

			console.log("data", data);

			if(data.type == 'success'){

				if(action == "create"){
					sw_message(`New ${type}`, `The ${type} has been successfully created. The popup will be updated to add translations.`, 'success');

					ajaxModalManageCareer(data.id, type, comienzo, limite, pagina);
				} else {
					sw_message(`${type} updated`, `The ${type} has been updated.`, 'success');
				}

				//Updating the table
				ajaxGetCareerFiltered(comienzo, limite, pagina, type);

			}
			else{
				sw_message('Oops', data.error, 'warning');
			}
		}
	});
}

//Function to manage the translation of career
const ajaxManageCareer = (slug_lang) => {

	var formData = new FormData($("#form_manage_career_"+slug_lang)[0]);
	let action 		= formData.get('action');
	let type 		= formData.get('type');
	let comienzo 	= formData.get('comienzo');
	let limite 		= formData.get('limite');
	let pagina 		= formData.get('pagina');
	let lang_name 	= formData.get('lang_name');

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-manage-career/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
 		success: function(data){

 			data = JSON.parse(data);

 			if(data.type == 'success'){

 				//Segun action mostramos mensaje
 				if(action == 'create'){
 					sw_message('New translation', 'Translation has been created for <strong>' + lang_name + '</strong> correctly. The popup will be updated.', 'success');

	 				ajaxModalManageCareer(data.id, type, comienzo, limite, pagina);
 				}
 				else
 					sw_message('Updated', 'Translation has been updated for <strong>' + lang_name + '</strong> correctly.', 'success');

 				//Update table
 				ajaxGetCareerFiltered(comienzo, limite, pagina, type);

 			}
 			else{
 				//Mostramos el error
 				sw_message('Oops', data.error, 'warning');
 			}
		}
	});
}

//Function to delete a career
const ajaxDeleteCareer = (id, type, comienzo, limite, pagina) =>{
	swal({
		title: `Delete ${type}?`,
		text: `You are about to remove the ${type} in all languages.`,
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Yes, delete it!',
		cancelButtonText: 'Close',
		confirmButtonClass: 'btn btn-danger',
		cancelButtonClass: 'btn btn-default m-l-10',
		buttonsStyling: false
	}).then(function () {

		var formData = new FormData();
		formData.append("id", id);
		formData.append("type", type);
		
		$.ajax({
			type: "POST",
			url: dominio+"ajax/ajax-delete-career/",
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function(){},
	 		success: function(data){
	 			
	 			//Show response
	 			sw_message(`${type} deleted`, `The ${type} has been completely removed.`, 'success')

	 			//Refresh table
 				ajaxGetCareerFiltered(comienzo, limite, pagina, type);
			}
		});

	});
}

/****************************************************
 *													*
 *	   			FUNCTIONS PAGES 					*
 * 													*
 ****************************************************/

//Function to get the pages filtered
const ajaxGetPagesFiltered = (comienzo, limite, pagina) =>{

    var formData = new FormData($("#form_filters")[0]);
    formData.append('comienzo', comienzo);
    formData.append('pagina', pagina);
    formData.append('limite', limite);

    $.ajax({
        type: "POST",
        url: dominio+"ajax/ajax-get-pages-filtered/",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){
			//Mostramos loading
			$('#loadingContent').css('display', 'block');

			//Ocultamos tabla de tours
			$("#table_content").css('display', 'none');
			$("#table_content").html('');
		},
 		success: function(data){
 			
 			data = JSON.parse(data);

 			//Ocultamos loading
			$('#loadingContent').css('display', 'none');

 			//Si el resultado es correcto, devolvera el HTML
 			if(data.type == 'success')
				$("#table_content").html(data.html);
			else{
				console.log(data.error);
				$("#table_content").html("<div class='alert alert-warning'>An internal error has occurred, if the problem persists report it.</div>");
			}

			//Ocultamos tabla de tours
			$("#table_content").css('display', 'block');
		}
    });
}

//Function to manage new or update page
const ajaxModalManagePage = (id, comienzo, limite, pagina) => {

	var formData = new FormData();
	formData.append('id', id);
	formData.append('comienzo', comienzo);
	formData.append('limite', limite);
	formData.append('pagina', pagina);

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-get-modal-manage-page/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
		success: function(data){
			//Parsing json data.
			data = JSON.parse(data);

			if(data.type == 'success'){
				$("#managePage__content").html(data.html);

				loadTinymce();
			}
			else{
				console.log(data.error);
				$("#managePage__content").html("Revisa la consola");
			}

		}
	});
}

//Function to manage the page base
const ajaxManagePageBase = () => {

	var formData = new FormData($("#form_manage_page_base")[0]);
	var action 		= formData.get('action');
	var comienzo 	= formData.get('comienzo');
	var limite 		= formData.get('limite');
	var pagina 		= formData.get('pagina');

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-manage-page-base/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
		success: function(data){

			data = JSON.parse(data);

			console.log("data", data);

			if(data.type == 'success'){

				if(action == "create"){
					sw_message(`New page`, `The page has been successfully created. The popup will be updated to add translations.`, 'success');

					ajaxModalManagePage(data.id, comienzo, limite, pagina);
				} else {
					sw_message(`Page updated`, `The page has been updated.`, 'success');
				}

				//Updating the table
				ajaxGetPagesFiltered(comienzo, limite, pagina);

			}
			else{
				sw_message('Oops', data.error, 'warning');
			}
		}
	});
}

//Function to manage the translation of page
const ajaxManagePage = (slug_lang) => {

	var formData = new FormData($("#form_manage_page_"+slug_lang)[0]);
	let action 		= formData.get('action');
	let comienzo 	= formData.get('comienzo');
	let limite 		= formData.get('limite');
	let pagina 		= formData.get('pagina');
	let lang_name 	= formData.get('lang_name');

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-manage-page/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
 		success: function(data){

 			data = JSON.parse(data);

 			if(data.type == 'success'){

 				//Segun action mostramos mensaje
 				if(action == 'create'){
 					sw_message('New translation', 'Translation has been created for <strong>' + lang_name + '</strong> correctly. The popup will be updated.', 'success');

	 				ajaxModalManagePage(data.id, comienzo, limite, pagina);
 				}
 				else
 					sw_message('Updated', 'Translation has been updated for <strong>' + lang_name + '</strong> correctly.', 'success');

 				//Update table
 				ajaxGetPagesFiltered(comienzo, limite, pagina);

 			}
 			else{
 				//Mostramos el error
 				sw_message('Oops', data.error, 'warning');
 			}
		}
	});
}

//Function to delete a page
const ajaxDeletePage = (id, comienzo, limite, pagina) =>{
	swal({
		title: `Delete page?`,
		text: `Remember that you can hide this one. If you delete it this will be remove completely.`,
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Yes, delete it!',
		cancelButtonText: 'Close',
		confirmButtonClass: 'btn btn-danger',
		cancelButtonClass: 'btn btn-default m-l-10',
		buttonsStyling: false
	}).then(function () {

		var formData = new FormData();
		formData.append("id", id);
		
		$.ajax({
			type: "POST",
			url: dominio+"ajax/ajax-delete-page/",
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function(){},
	 		success: function(data){
	 			
	 			//Show response
	 			sw_message(`Page deleted`, `The page has been completely removed.`, 'success')

	 			//Refresh table
 				ajaxGetPagesFiltered(comienzo, limite, pagina);
			}
		});

	});
}

/****************************************************
 *													*
 *	   			FUNCTIONS DOCUMENTS					*
 * 													*
 ****************************************************/

//Function to get the documents filtered
const ajaxGetDocumentsFiltered = (comienzo, limite, pagina) =>{

    var formData = new FormData($("#form_filters")[0]);
    formData.append('comienzo', comienzo);
    formData.append('pagina', pagina);
    formData.append('limite', limite);

    $.ajax({
        type: "POST",
        url: dominio+"ajax/ajax-get-documents-filtered/",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){
			//Mostramos loading
			$('#loadingContent').css('display', 'block');

			//Ocultamos tabla de tours
			$("#table_content").css('display', 'none');
			$("#table_content").html('');
		},
 		success: function(data){
 			
 			data = JSON.parse(data);

 			//Ocultamos loading
			$('#loadingContent').css('display', 'none');

 			//Si el resultado es correcto, devolvera el HTML
 			if(data.type == 'success')
				$("#table_content").html(data.html);
			else{
				console.log(data.error);
				$("#table_content").html("<div class='alert alert-warning'>An internal error has occurred, if the problem persists report it.</div>");
			}

			//Ocultamos tabla de tours
			$("#table_content").css('display', 'block');
		}
    });
}

//Function to manage new or update document
const ajaxModalManageDocument = (id, comienzo, limite, pagina) => {

	var formData = new FormData();
	formData.append('id', id);
	formData.append('comienzo', comienzo);
	formData.append('limite', limite);
	formData.append('pagina', pagina);

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-get-modal-manage-document/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
		success: function(data){
			//Parsing json data.
			data = JSON.parse(data);

			if(data.type == 'success'){
				$("#manageDocument__content").html(data.html);

				loadTinymce();
			}
			else{
				console.log(data.error);
				$("#manageDocument__content").html("Revisa la consola");
			}

		}
	});
}

//Function to manage the document base
const ajaxManageDocumentBase = () => {

	var formData = new FormData($("#form_manage_document_base")[0]);
	var action 		= formData.get('action');
	var comienzo 	= formData.get('comienzo');
	var limite 		= formData.get('limite');
	var pagina 		= formData.get('pagina');

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-manage-document-base/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
		success: function(data){

			data = JSON.parse(data);

			console.log("data", data);

			if(data.type == 'success'){

				if(action == "create"){
					sw_message(`New document`, `The document has been successfully created. The popup will be updated to add translations.`, 'success');

					ajaxModalManageDocument(data.id, comienzo, limite, pagina);
				} else {
					sw_message(`Document updated`, `The document has been updated.`, 'success');
				}

				//Updating the table
				ajaxGetDocumentsFiltered(comienzo, limite, pagina);

			}
			else{
				sw_message('Oops', data.error, 'warning');
			}
		}
	});
}

//Function to manage the translation of document
const ajaxManageDocument = (slug_lang) => {

	var formData = new FormData($("#form_manage_page_"+slug_lang)[0]);
	let action 		= formData.get('action');
	let comienzo 	= formData.get('comienzo');
	let limite 		= formData.get('limite');
	let pagina 		= formData.get('pagina');
	let lang_name 	= formData.get('lang_name');

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-manage-document/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
 		success: function(data){

 			data = JSON.parse(data);

 			if(data.type == 'success'){

 				//Segun action mostramos mensaje
 				if(action == 'create'){
 					sw_message('New translation', 'Translation has been created for document in <strong>' + lang_name + '</strong> correctly. The popup will be updated.', 'success');
 				}
 				else
 					sw_message('Updated', 'Translation has been updated for document in <strong>' + lang_name + '</strong> correctly. The popup will be updated.', 'success');

				ajaxModalManageDocument(data.id, comienzo, limite, pagina);

 				//Update table
 				ajaxGetDocumentsFiltered(comienzo, limite, pagina,);

 			}
 			else{
 				//Mostramos el error
 				sw_message('Oops', data.error, 'warning');
 			}
		}
	});
}

//Function to delete a page
const ajaxDeleteDocument = (id, comienzo, limite, pagina) =>{
	swal({
		title: `Delete document?`,
		text: `Remember that you can hide this one. If you delete it this will be remove completely.`,
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Yes, delete it!',
		cancelButtonText: 'Close',
		confirmButtonClass: 'btn btn-danger',
		cancelButtonClass: 'btn btn-default m-l-10',
		buttonsStyling: false
	}).then(function () {

		var formData = new FormData();
		formData.append("id", id);
		
		$.ajax({
			type: "POST",
			url: dominio+"ajax/ajax-delete-document/",
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function(){},
	 		success: function(data){
	 			
	 			//Show response
	 			sw_message(`Document deleted`, `The document has been completely removed in all languages.`, 'success')

	 			//Refresh table
 				ajaxGetDocumentsFiltered(comienzo, limite, pagina);
			}
		});

	});
}

/************************************************
 *												*
 *	   		FUNCTIONS  TRANSLATIONS 			*
 * 												*
 ************************************************/

//Function to get the translations filtered
const ajaxGetTraductionsFiltered = (comienzo, limite, pagina) =>{

    var formData = new FormData($("#form_filters")[0]);
    formData.append('comienzo', comienzo);
    formData.append('pagina', pagina);
    formData.append('limite', limite);

    cancelarAjax();
	consultaAjax = $.ajax({
        type: "POST",
        url: dominio+"ajax/ajax-get-traductions-filtered/",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(){
			//Mostramos loading
			$('#loadingContent').css('display', 'block');

			//Ocultamos tabla de tours
			$("#table_content").css('display', 'none');
			$("#table_content").html('');
		},
 		success: function(data){
 			
 			data = JSON.parse(data);

 			//Ocultamos loading
			$('#loadingContent').css('display', 'none');

 			//Si el resultado es correcto, devolvera el HTML
 			if(data.type == 'success')
				$("#table_content").html(data.html);
			else{
				console.log(data.error);
				$("#table_content").html("<div class='alert alert-warning'>An internal error has occurred, if the problem persists report it.</div>");
			}

			//Ocultamos tabla de tours
			$("#table_content").css('display', 'block');
		}
    });
    consultasAjax.push(consultaAjax);
}

//Function to manage new translation
const ajaxManageNewTranslation = (comienzo, limite, pagina) => {

	var formData = new FormData($("#form_new_traduction")[0]);
	var comienzo = formData.get('comienzo');
	var limite = formData.get('limite');
	var pagina = formData.get('pagina');

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-manage-new-translation/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
 		success: function(data){
 			
 			data = JSON.parse(data);

 			if(data.type == 'success'){

 				$('#closeAddTranslationModal').click();

 				sw_message('Translation created', 'The translation has been created correctly.', 'success');

 				//Actualizamos la tabla de bloques.
 				ajaxGetTraductionsFiltered(comienzo, limite, pagina);
 			}
 			else{
 				//Mostramos el error
 				sw_message('Oops', data.error, 'warning');
 			}

		}
	});
}

//Function  to manage translation
const ajaxModalManageTranslation = (id, comienzo, limite, pagina) =>{

	var formData = new FormData();
	formData.append('id', id);
	formData.append('comienzo', comienzo);
	formData.append('limite', limite);
	formData.append('pagina', pagina);

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-get-modal-manage-translation/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
 		success: function(data){
 			
 			data = JSON.parse(data);

 			//Si el resultado es correcto, devolvera el HTML
 			if(data.type == 'success')
				$("#manageTranslation__content").html(data.html);
			else{
				console.log(data.error);
				$("#manageTranslation__content").html("Revisa la consola");
			}

		}
	});
}

//Function to manage the translation
const ajaxManageTranslation = (slug_lang) => {

	var formData = new FormData($("#form_gestion_translation_"+slug_lang)[0]);
	action 		= formData.get('action');
	comienzo 	= formData.get('comienzo');
	limite 		= formData.get('limite');
	pagina 		= formData.get('pagina');
	slug 		= formData.get('slug');
	lang_name 	= formData.get('lang_name');

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-manage-translation/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
 		success: function(data){

 			data = JSON.parse(data);

 			if(data.type == 'success'){

 				//Segun action mostramos mensaje
 				if(action == 'create'){
 					sw_message('New translation', 'Translation has been created for <strong>' + lang_name + '</strong> correctly. The popup will be updated.', 'success');

	 				ajaxModalManageTranslation(data.id, comienzo, limite, pagina);
 				}
 				else
 					sw_message('Updated translation', 'Translation has been updated for <strong>' + lang_name + '</strong> correctly.', 'success');

 				//Update table
 				ajaxGetTraductionsFiltered(comienzo, limite, pagina);

 			}
 			else{
 				//Mostramos el error
 				sw_message('Oops', data.error, 'warning');
 			}
		}
	});
}

//Function to delete a translation
const ajaxDeleteTranslation = (id, comienzo, limite, pagina) =>{
	swal({
		title: 'Delete translation?',
		text: 'You are about to remove the translation text in all languages.',
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Yes, delete it!',
		cancelButtonText: 'Close',
		confirmButtonClass: 'btn btn-danger',
		cancelButtonClass: 'btn btn-default m-l-10',
		buttonsStyling: false
	}).then(function () {

		var formData = new FormData();
		formData.append("id", id);
		
		$.ajax({
			type: "POST",
			url: dominio+"ajax/ajax-delete-translation/",
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function(){},
	 		success: function(data){
	 			
	 			//Show response
	 			sw_message('Translation deleted', 'The translation has been completely removed.', 'success')

	 			//Refresh table
 				ajaxGetTraductionsFiltered(comienzo, limite, pagina);
			}
		});

	});
}

/************************************************
 *												*
 *	   		FUNCTIONS ABOUT LANGUAGES			*
 * 												*
 ************************************************/

//Function to get the languages filtered
const ajaxGetLanguagesFiltered = (comienzo, limite, pagina) => {

	var formData = new FormData($("#form_filters")[0]);
	formData.append("comienzo", comienzo);
	formData.append("limite", limite);
	formData.append("pagina", pagina);

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-get-languages-filtered/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){
			//Mostramos loading
			$('#loadingContent').css('display', 'block');

			//Ocultamos tabla de tours
			$("#table_content").css('display', 'none');
			$("#table_content").html('');
		},
		success: function(data){

			data = JSON.parse(data);

			//Ocultamos loading
			$('#loadingContent').css('display', 'none');

			//Si el resultado es correcto, devolvera el HTML
			if(data.type == 'success')
				$("#table_content").html(data.html);
			else{
				console.log(data.error);
				$("#table_content").html("<div class='alert alert-warning'>An internal error has occurred, if the problem persists report it.</div>");
			}

			//Ocultamos tabla de tours
			$("#table_content").css('display', 'block');

		}
	});
}

//Function to manage new language
const ajaxModalManageLanguage = (id, comienzo, limite, pagina) => {

	var formData = new FormData();
	formData.append('id', id);
	formData.append('comienzo', comienzo);
	formData.append('limite', limite);
	formData.append('pagina', pagina);

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-get-modal-manage-language/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
		success: function(data){
			//Parsing json data.
			data = JSON.parse(data);

			if(data.type == 'success')
				$("#manageLanguage__content").html(data.html);
			else{
				console.log(data.error);
				$("#manageLanguage__content").html("Revisa la consola");
			}

		}
	});
}

//Function to manage the language base
const ajaxManageLanguageBase = () => {

	var formData = new FormData($("#form_manage_language_base")[0]);
	var action 		= formData.get('action');
	var comienzo 	= formData.get('comienzo');
	var limite 		= formData.get('limite');
	var pagina 		= formData.get('pagina');
	var id 			= formData.get('id');

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-manage-language-base/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
		success: function(data){

			data = JSON.parse(data);

			if(data.type == 'success'){

				//Showing message in base to action
				if(action == 'create'){
					sw_message('New language', 'The language has been successfully created. The popup will be updated.', 'success');

					if(id == '0') { id = data.id; }

					ajaxModalManageLanguage(data.id, comienzo, limite, pagina);
				}
				else
					sw_message('Updated language', 'The language data has been updated correctly.', 'success');

				//Updating the table
				ajaxGetLanguagesFiltered(comienzo, limite, pagina);

			}
			else{
				sw_message('Oops', data.error, 'warning');
			}
		}
	});
}

//Function to update translation of language
const ajaxManageLanguage = (slug_lang) => {

	var formData = new FormData($("#form_manage_language_"+slug_lang)[0]);
	action 		= formData.get('action');
	comienzo 	= formData.get('comienzo');
	limite 		= formData.get('limite');
	pagina 		= formData.get('pagina');
	slug 		= formData.get('slug');
	lang_name 	= formData.get('lang_name');

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-manage-language/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
		success: function(data){

			data = JSON.parse(data);

			if(data.type == 'success'){

				//Showging message in base to action
				if(action == 'create'){
					sw_message('New translation', 'Language translation has been created for ' + lang_name + ' correctly. The popup will be updated.', 'success');

					ajaxModalManageLanguage(data.id, comienzo, limite, pagina);
				}
				else
					sw_message('Updated translation', 'Language translation has been updated for ' + lang_name + ' correctly.', 'success');

				//Updating the table
				ajaxGetLanguagesFiltered(comienzo, limite, pagina);

			}
			else{
				sw_message('Oops', data.error, 'warning');
			}
		}
	});
}

//Funcion de confirmacion y luego ejecuta un ajax (usada para eliminar cosas).
const ajaxEliminarLanguage = (id, comienzo, limite, pagina) => {
	swal({
		title: '¿Eliminar idioma?',
		text: 'Estás a punto de eliminar el idioma, y con ello la eliminación de todas las traducciones del mismo. La recomendación es desactivarlo por si en un futuro se vuelve a usar.',
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: '!Sí, eliminar!',
		cancelButtonText: 'Cerrar',
		confirmButtonClass: 'btn btn-danger',
		cancelButtonClass: 'btn btn-default m-l-10',
		buttonsStyling: false
	}).then(function () {

		var formData = new FormData();
		formData.append("id", id);

		$.ajax({
			type: "POST",
			url: dominio+"ajax/ajax-eliminar-language/",
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function(){},
			success: function(data){

				//Mostramos mensaje
				sw_message('Idioma eliminado', 'Se ha eliminado el idioma completamente.', 'success')

				//Refrescamos la tabla
				ajaxGetLanguagesFiltered(comienzo, limite, pagina);
			}
		});

	});
}

/********************************
 *								*
 *		FUNCIONES GENERALES 	*
 * 								*
 ********************************/

//Function to load tinymce
const loadTinymce = () => {
	//Loading wysihtml5
	setTimeout(function () {
		if($(".wysihtml5").length > 0){
			tinymce.remove();
			tinymce.init({
				selector: "textarea.wysihtml5",
				setup: function (editor) {
					editor.on('change', function () {
						editor.save();
					});
				},
				theme: "modern",
				height:200,
				plugins: [
					"advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
					"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
					"save table contextmenu directionality emoticons template paste textcolor"
				],
				toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l	  ink image | print preview media fullpage | forecolor backcolor emoticons",
				style_formats: [
					{title: 'Bold text', inline: 'b'},
					{title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
					{title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
					{title: 'Example 1', inline: 'span', classes: 'example1'},
					{title: 'Example 2', inline: 'span', classes: 'example2'},
					{title: 'Table styles'},
					{title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
				]
			});
		}
	}, 100);
}

//Funcion que hace una cadena auto-slug.
const autoSlug = (id) => {

 	//Cadena a traducir a slug
 	var cadena = $('#'+id).val();

 	var formData = new FormData();
	formData.append('cadena', cadena);

	$.ajax({
		type: "POST",
		url: dominio+"ajax/ajax-auto-slug/",
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		beforeSend: function(){},
 		success: function(data){

 			//Pasamos la cadena traducida al input
 			$('#'+id).val(data);
		}
	});
}

//Funcion que dibuja un sweet alert
const sw_message = (title, message, type) => {
	swal(
		{
			title: title,
			text: message,
			type: type,
			confirmButtonClass: 'btn btn-success',
			cancelButtonClass: 'btn btn-danger m-l-10'
		}
	)
}

  //Funcion que dibuja un sweet alert
const sw_message_custom_success_button = (title, message, type, button1) =>{
	swal(
		{
			title: title,
			text: message,
			type: type,
			showCloseButton: true,
			confirmButtonClass: 'btn btn-success',
			cancelButtonClass: 'btn btn-danger m-l-10',
			confirmButtonText: button1,
		}
	)
}

//Funcion que cuenta los caracteres de un elemento
const contarCaracteres = (idDndContar, idDndActualizar, limitePermitido) =>{

	//Obtenemos el elemento de donde hay que contar
	var contenido = $('#'+idDndContar).val().length;
	var restante = limitePermitido - contenido;

	$('#'+idDndActualizar).html(restante);

	if(restante <= 0){
		$('#'+idDndContar).attr("maxlength", limitePermitido);
	}
}