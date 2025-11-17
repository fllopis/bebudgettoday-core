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
         *        DASHBOARD         *
         *                          *
         ****************************/

        'DASHBOARD_POSITIVE_SALARY' => 'Excellent! This month you achieved a positive balance of %currency_init%%amount%%currency_end%',
        'DASHBOARD_NOT_DATA' => 'There is insufficient data from the previous month to make a meaningful comparison of the balance.',
        'DASHBOARD_POSITIVE_VARIATION' => 'Excellent! You achieved a monthly balance of %variation%%, which is better than last month. Keep up the good work!',
        'DASHBOARD_NEGATIVE_VARIATION' => 'Your balance was %variation%% lower than last month. Review your expenses!',
        'DASHBOARD_EQUAL_VARIATION' => 'You kept exactly the same balance as last month. Financial stability!',

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
        'CATEGORY_VALIDATION_NOT_FOUND_FOR_USER' => 'Category not found for this user.',

        /****************************
         *                          *
         *       TRANSACTIONS       *
         *                          *
         ****************************/

        'TRANSACTION_VALIDATION_USER_NOT_FOUND' => 'User not found, please try again or do login.',
        'TRANSACTION_VALIDATION_ID_REQUIRED' => 'Transaction ID is required for this action.',
        'TRANSACTION_VALIDATION_DELETING_ERROR' => 'There was an error during transaction deletion. Please, try again.',
        'TRANSACTION_VALIDATION_DELETING_SUCCESS' => 'Transaction deleted successfully.',
        'TRANSACTION_VALIDATION_DELETING_NOT_FOUND' => 'Transaction not found or already deleted.',
        'TRANSACTION_VALIDATION_NOT_FOUND_FOR_USER' => 'Transaction not found for this user.',
        'TRANSACTION_VALIDATIONS_DATES_ERROR' => 'Both dates filters are required',
        'TRANSACTION_VALIDATIONS_DATE_START_ERROR' => 'Start date has invalid format. Format must be YYYY-MM-DD',
        'TRANSACTION_VALIDATIONS_DATE_END_ERROR' => 'End date has invalid format. Format must be YYYY-MM-DD',
        'TRANSACTION_VALIDATIONS_DATE_START_LATER_END' => 'The start date cannot be later than the end date',
        'TRANSACTION_VALIDATION_ID_REQUIRED' => 'Transaction ID is required for this action.'
    ];

?>