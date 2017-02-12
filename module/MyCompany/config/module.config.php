<?php

namespace MyCompany;

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use MyCompany\Controller\UserResetPasswordController;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return array(



    'router' => [
        'routes' => [
            'reset-pass' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/user-reset-password/[:email]/[:token]',
                    'defaults' => [
                        'controller' => 'reset-pass',
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'aliases' => [
            'reset-pass' => UserResetPasswordController::class
        ],
        'factories' => [
            UserResetPasswordController::class => InvokableFactory::class,
        ],
    ],

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'template_map' => [
            'MyCompany/mail/user/signup' => __DIR__ . "/../view/my-company/mail/user/signup.phtml",
            'MyCompany/mail/user/forgot-password' => __DIR__ . "/../view/my-company/mail/user/forgot-password.phtml",
        ]
    ),


    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'class' => MappingDriverChain::class,
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],





);