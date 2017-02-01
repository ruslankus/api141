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
];
