$(document).ready(function(){

    if($("#icon_image").length > 0){
        $image_crop = $('#icon_image').croppie({
            enableExif: true,
            viewport: {
                width: 200,
                height: 200,
                type:'square' //square
            },
            boundary:{
                width: 350,
                height: 350
            }
        });
    }

    //Si cambia la imagen mostraremos el modal
    $('#upload_image').on('change', function(){

        alert('change');
        
        var reader = new FileReader();
        
        reader.onload = function (event) {
            $image_crop.croppie('bind', {
                url: event.target.result
            }).then(function(){
            });
        }
        
        reader.readAsDataURL(this.files[0]);
        $('#uploadimageModal').modal('show');

    });

    $('.crop_image').click(function(event){

        //Obtenemos la url del ajax que guardara la imagen que hemos recortado.
        var uriAjax     = $(this).data('ajax');
        var idSuccess   = $(this).data('success');
        var custom      = $(this).data('custom');

        $image_crop.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function(response){

            //Guardamos la imagen por ajax
            $.ajax({
                url: dominio+'ajax/'+uriAjax+'/',
                type: "POST",
                data:{
                    "image": response,
                    "custom": custom
                },
                success: function(data){
                    $('#uploadimageModal').modal('hide');
                    $('#upload_image').val('');
                    $('#image_uploaded').val(data);

                    //Si se trata de la seccion de iconos, hay mas personalizacion. Quitaremos el icono seleccionado
                    if(custom == 'iconSection'){

                        //Vaciamos el input de icono
                        $('#icon_input').val('');

                        //Quitamos la clase active a todos
                        $('.icon-library').removeClass('icon-library-active');

                        sw_message_custom_success_button('¡Genial!', 'Hemos guardado tu icono, finaliza el proceso de creación o actualización.', 'success', '<i class="fa fa-thumbs-up"></i> Ok')
                    }
                }
            });
        })
    });

});