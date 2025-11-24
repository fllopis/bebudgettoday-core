<?php
    
    return [

        /****************************
         *                          *
         *      AUTHENTICATION      *
         *                          *
         ****************************/

        //LOGIN
        'AUTH_LOGIN_EMAIL_WRONG_FORMAT' => 'El formato del correo electrónico no es válido. Inténtalo de nuevo 😃.',
        'AUTH_LOGIN_EMAIL_NOT_FOUND' => 'No se ha encontrado ninguna cuenta con este correo electrónico. Por favor, regístrese primero 😥.',
        'AUTH_LOGIN_PASSWORD_EMPTY' => 'La contraseña no puede estar vacía.',
        'AUTH_LOGIN_PASSWORD_WRONG' => 'La contraseña es incorrecta. Por favor, inténtalo de nuevo.',

        //PROVIDER LOGIN
        'AUTH_PROVIDER_LOGIN_FAILED' => 'Error al iniciar sesión, inténtalo de nuevo.',
        'AUTH_PROVIDER_LOGIN_NOT_FOUND' => 'No se ha encontrado ninguna cuenta con las credenciales del proveedor %provider%. Por favor, regístrese primero 😥.',

        //REGISTRATION
        'AUTH_REGISTRATION_EMAIL_WRONG_FORMAT' => 'El formato del correo electrónico no es válido. Inténtalo de nuevo 😃.',
        'AUTH_REGISTRATION_EMAIL_USED' => 'Este correo electrónico ya está en uso 😥. Intenta con otro.',
        'AUTH_REGISTRATION_PASSWORD_SHORT' => 'La contraseña debe tener al menos 6 caracteres.',
        'AUTH_REGISTRATION_NAME_EMPTY' => 'El nombre no puede estar vacío. Puedes inventártelo 😉.',
        'AUTH_REGISTRATION_PROVIDER_TOKEN_WRONG' => 'Token incorrecto para el registro. Por favor, inténtalo de nuevo 😃.',
        'AUTH_REGISTRATION_UNKNOW_ERROR' => 'Se ha producido un error durante el registro. Por favor, inténtalo de nuevo.',

        'AUTH_VALIDATION_USER_NOT_FOUND' => 'Usuario no encontrado, inténtalo de nuevo o inicia sesión.',

        /****************************
         *                          *
         *        DASHBOARD         *
         *                          *
         ****************************/

        'DASHBOARD_POSITIVE_SALARY' => '¡Excelente! Este mes has conseguido un saldo positivo de %currency_init%%amount%%currency_end%.',
        'DASHBOARD_NOT_DATA' => 'No hay datos suficientes del mes anterior para realizar una comparación significativa del saldo.',
        'DASHBOARD_POSITIVE_VARIATION' => '¡Excelente! Has logrado un saldo mensual del %variation%%, lo cual es mejor que el mes pasado. ¡Sigue así!',
        'DASHBOARD_NEGATIVE_VARIATION' => 'Tu saldo fue un %variation%% más bajo que el mes pasado. ¡Revisa tus gastos!',
        'DASHBOARD_EQUAL_VARIATION' => 'Has mantenido exactamente el mismo saldo que el mes pasado. ¡Estabilidad financiera!',

        /****************************
         *                          *
         *        CATEGORIES        *
         *                          *
         ****************************/

        //VALIDATIONS
        'CATEGORY_VALIDATION_NAME_EMPTY' => 'El nombre de la categoría no puede estar vacío.',
        'CATEGORY_VALIDATION_NAME_USED' => 'Ya tienes una categoría con este nombre.',
        'CATEGORY_VALIDATION_CREATION_LIMIT' => 'Has alcanzado el número máximo de categorías que se pueden crear 😥.',
        'CATEGORY_VALIDATION_CREATION_UNKNOW_ERROR' => 'Se ha producido un error durante la creación de la categoría. Por favor, inténtalo de nuevo.',
        'CATEGORY_VALIDATION_UPDATE_UNKNOW_ERROR' => 'Se ha producido un error durante la actualización de la categoría. Por favor, inténtalo de nuevo.',
        'CATEGORY_VALIDATION_UNKNOW_ERROR' => 'Se ha producido un error durante la gestión de categorías. Por favor, inténtalo de nuevo.',
        'CATEGORY_VALIDATION_ID_REQUIRED' => 'Se requiere el ID de categoría para esta acción.',
        'CATEGORY_VALIDATION_DELETING_ERROR' => 'Se ha producido un error al eliminar la categoría. Inténtalo de nuevo.',
        'CATEGORY_VALIDATION_DELETING_SUCCESS' => 'Categoría eliminada correctamente.',
        'CATEGORY_VALIDATION_DELETING_NOT_FOUND' => 'Categoría no encontrada o ya eliminada.',
        'CATEGORY_VALIDATION_NOT_FOUND_FOR_USER' => 'No se ha encontrado ninguna categoría para este usuario.',

        /****************************
         *                          *
         *       TRANSACTIONS       *
         *                          *
         ****************************/

        'TRANSACTION_VALIDATION_USER_NOT_FOUND' => 'Usuario no encontrado, inténtalo de nuevo o inicia sesión.',
        'TRANSACTION_VALIDATION_ID_REQUIRED' => 'Se requiere el ID de transacción para esta acción.',
        'TRANSACTION_VALIDATION_DELETING_ERROR' => 'Se ha producido un error durante la eliminación de la transacción. Por favor, inténtelo de nuevo.',
        'TRANSACTION_VALIDATION_DELETING_SUCCESS' => 'Transacción eliminada correctamente.',
        'TRANSACTION_VALIDATION_DELETING_NOT_FOUND' => 'Transacción no encontrada o ya eliminada.',
        'TRANSACTION_VALIDATION_NOT_FOUND_FOR_USER' => 'No se ha encontrado ninguna transacción para este usuario.',
        'TRANSACTION_VALIDATIONS_DATES_ERROR' => 'Se requieren ambos filtros de fecha.',
        'TRANSACTION_VALIDATIONS_DATE_START_ERROR' => 'La fecha de inicio tiene un formato no válido. El formato debe ser AAAA-MM-DD.',
        'TRANSACTION_VALIDATIONS_DATE_END_ERROR' => 'La fecha de finalización tiene un formato no válido. El formato debe ser AAAA-MM-DD.',
        'TRANSACTION_VALIDATIONS_DATE_START_LATER_END' => 'La fecha de inicio no puede ser posterior a la fecha de finalización.',
        'TRANSACTION_VALIDATION_ID_REQUIRED' => 'Se requiere el ID de transacción para esta acción.',
        'TRANSACTION_VALIDATION_CREATION_UNKNOW_ERROR' => 'Se ha producido un error durante la creación de la transacción. Por favor, inténtelo de nuevo.',
        'TRANSACTION_VALIDATION_UPDATE_UNKNOW_ERROR' => 'Se ha producido un error durante la actualización de la transacción. Por favor, inténtelo de nuevo.',
        'TRANSACTION_VALIDATION_UNKNOW_ERROR' => 'Se ha producido un error durante la gestión de la transacción. Por favor, inténtelo de nuevo.',
        'TRANSACTION_VALIDATION_AMOUNT_EMPTY' => 'El importe introducido no es válido; no puede estar vacío y debe ser superior a 0.',
        'TRANSACTION_VALIDATION_DESCRIPTION_EMPTY' => 'La descripción de la transacción no puede estar vacía. ¿A qué se refiere? 🤗',
        'TRANSACTION_VALIDATION_CATEGORY_EMPTY' => 'Selecciona una categoría para este %tipo% 😐',
        'TRANSACTION_VALIDATION_TRANSACTION_DATE_EMPTY' => '¿Cuándo se realizó este %tipo%? Indique la fecha.',
        'TRANSACTION_VALIDATION_CATEGORY_NOT_OWNER' => 'Ha habido un problema al asociar la transacción con la categoría 😥. Elige otra y vuelve a intentarlo.'
    ];

?>