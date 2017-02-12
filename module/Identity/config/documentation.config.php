<?php
return [
    'Identity\\V1\\Rest\\User\\Controller' => [
        'collection' => [
            'POST' => [
                'request' => '{
   "emailAddress": "Email address",
   "password": "Password"
}',
                'response' => '{
   "_links": {
       "self": {
           "href": "/user[/:email]"
       }
   }
   "emailAddress": "Email address",
   "password": "Password"
}',
            ],
            'description' => 'Register a new user',
        ],
        'description' => 'User Service - handles basic user identity related functionality',
    ],
    'Identity\\V1\\Rest\\BeginPasswordReset\\Controller' => [
        'description' => 'Begin the password reset process by sending an activation email',
        'collection' => [
            'POST' => [
                'request' => '{
   "emailAddress": "A valid email address"
}',
                'response' => '{
   "_links": {
       "self": {
           "href": "/account/begin-password-reset[/:begin_password_reset_id]"
       }
   }
   "emailAddress": "A valid email address"
}',
                'description' => 'Reqiures a valid email address to send in a password reset',
            ],
        ],
    ],
];
