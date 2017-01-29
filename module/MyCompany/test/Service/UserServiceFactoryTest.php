<?php
namespace MyCompany\Service;

use MyCompany\Factory\UserServiceFactory;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\ServiceManager\ServiceManager;

class UserServiceFactoryTest extends AbstractHttpControllerTestCase
{

   public function testCanCreateUserRepositoryFactory()
   {
       //$sm = new ServiceManager();

       //$factory = new UserServiceFactory();
       //$userService = $factory($sm,UserService::class);

       //$this->assertInstanceOf(UserService::class, $userService);
   }

}