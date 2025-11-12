<?php
    
    return [

        /****************************
         *                          *
         *      AUTHENTICATION      *
         *                          *
         ****************************/

        //LOGIN
        'AUTH_LOGIN_EMAIL_WRONG_FORMAT' => 'The email format is not valid. Try again 😃.',
        'AUTH_LOGIN_EMAIL_NOT_FOUND' => 'No account found with this email. Please, register first 😥.',
        'AUTH_LOGIN_PASSWORD_EMPTY' => 'Password can not be empty.',
        'AUTH_LOGIN_PASSWORD_WRONG' => 'The password is incorrect. Please, try again.',

        //PROVIDER LOGIN
        'AUTH_PROVIDER_LOGIN_FAILED' => 'Login failed, please try again.',
        'AUTH_PROVIDER_LOGIN_NOT_FOUND' => 'No account found with %provider% provider credentials. Please, register first 😥.',

        //REGISTRATION
        'AUTH_REGISTRATION_EMAIL_WRONG_FORMAT' => 'The email format is not valid. Try again 😃.',
        'AUTH_REGISTRATION_EMAIL_USED' => 'This email is already used 😥. Try another one.',
        'AUTH_REGISTRATION_PASSWORD_SHORT' => 'Password must be at least 6 characters long.',
        'AUTH_REGISTRATION_NAME_EMPTY' => 'The name cannot be empty. You can make it up 😉.',
        'AUTH_REGISTRATION_PROVIDER_TOKEN_WRONG' => 'Wrong token for registration. Please, try again 😃.',
        'AUTH_REGISTRATION_UNKNOW_ERROR' => 'There was an error during the registration. Please, try again.',

        /****************************
         *                          *
         *        CATEGORIES        *
         *                          *
         ****************************/

        //VALIDATIONS
        'CATEGORY_VALIDATION_NAME_EMPTY' => 'The name of the category cannot be empty.',
        'CATEGORY_VALIDATION_NAME_USED' => 'You already have a category with this name.',
        'CATEGORY_VALIDATION_CREATION_LIMIT' => 'You have reached the maximum number of categories that can be created 😥.',
        'CATEGORY_VALIDATION_CREATION_UNKNOW_ERROR' => 'There was an error during the category creation. Please, try again.',
        'CATEGORY_VALIDATION_UPDATE_UNKNOW_ERROR' => 'There was an error during the category update. Please, try again.',
        'CATEGORY_VALIDATION_UNKNOW_ERROR' => 'There was an error during category management. Please, try again.',
        'CATEGORY_VALIDATION_USER_NOT_FOUND' => 'User not found, please try again or do login.',
        'CATEGORY_VALIDATION_ID_REQUIRED' => 'Category ID is required for this action.',
        'CATEGORY_VALIDATION_DELETING_ERROR' => 'There was an error during category deletion. Please, try again.',
        'CATEGORY_VALIDATION_DELETING_SUCCESS' => 'Category deleted successfully.',
        'CATEGORY_VALIDATION_DELETING_NOT_FOUND' => 'Category not found or already deleted.',
        'CATEGORY_VALIDATION_NOT_FOUND_FOR_USER' => 'Category not found for this user.'
    ];

?>