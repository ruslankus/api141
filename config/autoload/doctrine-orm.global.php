<?php

use DoctrineORMModule\Service\EntityManagerFactory;

return array(
    'doctrine' => array(
        'connection' => array(
            // default connection name
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => '172.18.0.1',
                    'port'     => '3306',
                    'user'     => 'root',
                    'password' => 'mysql',
                    'dbname'   => 'apigility',
                )
            )
        ),

        'driver' => array(
            // defines an annotation driver with two paths, and names it `my_annotation_driver`
            'my_annotation_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../../module/MyCompany/src/MyCompany/Entity'
                ),
            ),

            // default metadata driver, aggregates all other drivers into a single one.
            // Override `orm_default` only if you know what you're doing
            'orm_default' => array(
                'drivers' => array(
                    // register `my_annotation_driver` for any entity under namespace `My\Namespace`
                    'MyCompany' => 'my_annotation_driver'
                )
            )

        )

    ),


    'dependencies' => [
        'factories' => [
            'doctrine.entitymanager.orm_default' => EntityManagerFactory::class,
        ],
    ],

);
