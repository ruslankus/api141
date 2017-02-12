<?php
return [
    'service_manager' => [
        'factories' => [
            \Identity\V1\Rest\User\UserResource::class => \Identity\V1\Rest\User\UserResourceFactory::class,
            \Identity\V1\Rest\BeginPasswordReset\BeginPasswordResetResource::class => \Identity\V1\Rest\BeginPasswordReset\BeginPasswordResetResourceFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'identity.rest.user' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/user[/:email]',
                    'defaults' => [
                        'controller' => 'Identity\\V1\\Rest\\User\\Controller',
                    ],
                ],
            ],
            'identity.rest.begin-password-reset' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/account/begin-password-reset[/:begin_password_reset_id]',
                    'defaults' => [
                        'controller' => 'Identity\\V1\\Rest\\BeginPasswordReset\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            0 => 'identity.rest.user',
            1 => 'identity.rest.begin-password-reset',
        ],
    ],
    'zf-rest' => [
        'Identity\\V1\\Rest\\User\\Controller' => [
            'listener' => \Identity\V1\Rest\User\UserResource::class,
            'route_name' => 'identity.rest.user',
            'route_identifier_name' => 'email',
            'collection_name' => 'user',
            'entity_http_methods' => [],
            'collection_http_methods' => [
                0 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \MyCompany\Entity\User::class,
            'collection_class' => \Identity\V1\Rest\User\UserCollection::class,
            'service_name' => 'User',
        ],
        'Identity\\V1\\Rest\\BeginPasswordReset\\Controller' => [
            'listener' => \Identity\V1\Rest\BeginPasswordReset\BeginPasswordResetResource::class,
            'route_name' => 'identity.rest.begin-password-reset',
            'route_identifier_name' => 'begin_password_reset_id',
            'collection_name' => 'begin_password_reset',
            'entity_http_methods' => [],
            'collection_http_methods' => [
                0 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Identity\V1\Rest\BeginPasswordReset\BeginPasswordResetEntity::class,
            'collection_class' => \Identity\V1\Rest\BeginPasswordReset\BeginPasswordResetCollection::class,
            'service_name' => 'BeginPasswordReset',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            'Identity\\V1\\Rest\\User\\Controller' => 'HalJson',
            'Identity\\V1\\Rest\\BeginPasswordReset\\Controller' => 'HalJson',
        ],
        'accept_whitelist' => [
            'Identity\\V1\\Rest\\User\\Controller' => [
                0 => 'application/vnd.identity.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Identity\\V1\\Rest\\BeginPasswordReset\\Controller' => [
                0 => 'application/vnd.identity.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'Identity\\V1\\Rest\\User\\Controller' => [
                0 => 'application/vnd.identity.v1+json',
                1 => 'application/json',
            ],
            'Identity\\V1\\Rest\\BeginPasswordReset\\Controller' => [
                0 => 'application/vnd.identity.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'zf-hal' => [
        'metadata_map' => [
            \Identity\V1\Rest\User\UserEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'identity.rest.user',
                'route_identifier_name' => 'user_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \Identity\V1\Rest\User\UserCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'identity.rest.user',
                'route_identifier_name' => 'user_id',
                'is_collection' => true,
            ],
            \MyCompany\Entity\User::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'identity.rest.user',
                'route_identifier_name' => 'email',
                'hydrator' => \DoctrineModule\Stdlib\Hydrator\DoctrineObject::class,
            ],
            \Identity\V1\Rest\BeginPasswordReset\BeginPasswordResetEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'identity.rest.begin-password-reset',
                'route_identifier_name' => 'begin_password_reset_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \Identity\V1\Rest\BeginPasswordReset\BeginPasswordResetCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'identity.rest.begin-password-reset',
                'route_identifier_name' => 'begin_password_reset_id',
                'is_collection' => true,
            ],
        ],
    ],
    'zf-content-validation' => [
        'Identity\\V1\\Rest\\User\\Controller' => [
            'input_filter' => 'Identity\\V1\\Rest\\User\\Validator',
        ],
        'Identity\\V1\\Rest\\BeginPasswordReset\\Controller' => [
            'input_filter' => 'Identity\\V1\\Rest\\BeginPasswordReset\\Validator',
        ],
    ],
    'input_filter_specs' => [
        'Identity\\V1\\Rest\\User\\Validator' => [
            0 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Zend\Validator\EmailAddress::class,
                        'options' => [],
                    ],
                ],
                'filters' => [],
                'name' => 'emailAddress',
                'description' => 'Email address',
                'error_message' => 'Please Enter email address',
            ],
            1 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Zend\Validator\StringLength::class,
                        'options' => [
                            'min' => '6',
                        ],
                    ],
                ],
                'filters' => [],
                'name' => 'password',
                'description' => 'Password',
                'error_message' => 'Please enter password',
            ],
        ],
        'Identity\\V1\\Rest\\BeginPasswordReset\\Validator' => [
            0 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Zend\Validator\EmailAddress::class,
                        'options' => [],
                    ],
                ],
                'filters' => [],
                'name' => 'emailAddress',
                'description' => 'A valid email address',
                'error_message' => 'Please provide a valid email address',
            ],
        ],
    ],
];
