<?php
return [
    'service_manager' => [
        'factories' => [
            \Identity\V1\Rest\User\UserResource::class => \Identity\V1\Rest\User\UserResourceFactory::class,
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
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            0 => 'identity.rest.user',
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
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            'Identity\\V1\\Rest\\User\\Controller' => 'HalJson',
        ],
        'accept_whitelist' => [
            'Identity\\V1\\Rest\\User\\Controller' => [
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
        ],
    ],
    'zf-content-validation' => [
        'Identity\\V1\\Rest\\User\\Controller' => [
            'input_filter' => 'Identity\\V1\\Rest\\User\\Validator',
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
    ],
];
