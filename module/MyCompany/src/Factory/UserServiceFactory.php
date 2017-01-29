<?php
namespace MyCompany\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use MyCompany\Service\UserService;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Doctrine\ORM\EntityManager;

class UserServiceFactory implements FactoryInterface
{

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $em  = $container->get('doctrine.entitymanager.orm_default');


        $service = new $requestedName($em);

        return $service;
    }
}