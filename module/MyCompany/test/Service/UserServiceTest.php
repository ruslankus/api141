<?php
namespace MyCompany\Service;

use MyCompany\Entity\User;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use MyCompany\Service\UserService;
use Doctrine\ORM\EntityManager;


class UserServiceTest extends AbstractHttpControllerTestCase
{

    protected $userService;

    protected $serviceManager;

    protected function getORM()
    {
        $orm = $this->serviceManager->get('doctrine.entitymanager.orm_default');
        return $orm;
    }


    protected function setUp()
    {

        // The module configuration should still be applicable for tests.
        // You can override configuration here with test case specific values,
        // such as sample view templates, path stacks, module_listener_options,
        // etc.
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();

        //$this->userService = new UserService();
        $this->serviceManager = $this->getApplicationServiceLocator();

    }



    protected function tearDown()
    {
        $orm = $this->getORM();
        $qb = $orm->createQueryBuilder();
        $exp = $qb->expr();
        $qb->select('u');
        $qb->from(User::class,'u');
        $qb->andWhere($exp->like('u.email', ':email'));
        $qb->setParameter('email','%ruslankus%');
        $iterateResult = $qb->getQuery();
        $res = $iterateResult->iterate();
        foreach ($res as $usAsArr){
            $orm->remove($usAsArr[0]);
        }
        $orm->flush();
        $orm->clear();


        parent::tearDown(); // TODO: Change the autogenerated stub
    }



    public function test__construct()
    {
        $userService = $this->serviceManager->get(UserService::class);
        $this->assertInstanceOf(UserService::class,$userService);
    }


    public function testRegisterUser()
    {
        $email = 'ruslankus@yahoo.com';
        $password = 'abc1234';
        $userService = $this->serviceManager->get(UserService::class);

        $userObj = $userService->registerUser($email,$password);
        $this->assertInstanceOf(User::class,$userObj);
    }


    public function testRegisterUserMailAlreadyExistsException()
    {
        $email = 'ruslankus@yahoo.com';
        $password = 'abc1234';
        $userService = $this->serviceManager->get(UserService::class);
        $userObj = $userService->registerUser($email,$password);

        $this->assertInstanceOf(User::class,$userObj);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(UserService::ERROR_USER_EXIST_MSG);
        $userObj = $userService->registerUser($email,$password);

    }


    public function testFetchUser()
    {
        $email = 'ruslankus@yahoo.com';
        $password = 'abc1234';
        $userService = $this->serviceManager->get(UserService::class);
        $userObj = $userService->registerUser($email,$password);

        $this->assertInstanceOf(User::class,$userObj);

        $expectedOutput = $userService->fetchUser($email);
        $this->assertInstanceOf(User::class, $expectedOutput);
    }


    public function testForgetPassword()
    {
        $email = 'ruslankus@yahoo.com';
        $password = 'abc1234';
        $userService = $this->serviceManager->get(UserService::class);
        $userObj = $userService->registerUser($email,$password);

        $this->assertInstanceOf(User::class,$userObj);
        //forget passwprd
        $responce = $userService->forgotPassword($email);

        $this->assertInternalType('array',$responce);
        $this->assertArrayHasKey('isMailSent', $responce);
        $this->assertTrue($responce['isMailSent']);

    }


    public function testForgotPassowrdWrongEmail()
    {
        $email = 'ruslankus@yahoo.com';
        $password = 'abc1234';
        $userService = $this->serviceManager->get(UserService::class);
        $userObj = $userService->registerUser($email,$password);

        $this->assertInstanceOf(User::class,$userObj);

        $wrongMail = "test@teest.com";
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(UserService::ERROR_USER_NOT_FOUND_MSG);
        $responce = $userService->forgotPassword($wrongMail);
    }


    public function testNotFoundUser()
    {
        $email = 'ruslankus@yahoo.com';

        $userService = $this->serviceManager->get(UserService::class);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(UserService::ERROR_USER_NOT_FOUND_MSG);

        $expectedOutput = $userService->fetchUser($email);
    }


    public function testResetPassword()
    {
        $email = 'ruslankus@yahoo.com';
        $password = 'abc1234';
        $userService = $this->serviceManager->get(UserService::class);
        $userObj = $userService->registerUser($email,$password);

        $this->assertInstanceOf(User::class,$userObj);

        $newPassword = "def5678";
        $stringToHash = $userObj->getId() .
            $userObj->getEmail() .
            $userObj->getPassword() .
            $userObj->getCreatedAt()->getTimestamp();
        $resetToken = hash('sha256',$stringToHash);
        $userResetObj = $userService->resetPassword($email,$resetToken,$newPassword);
        $this->assertInstanceOf(User::class, $userResetObj);

    }


    public function testResetPassowrdWrongEmail()
    {
        $email = 'ruslankus@yahoo.com';
        $password = 'abc1234';
        $userService = $this->serviceManager->get(UserService::class);
        $userObj = $userService->registerUser($email,$password);

        $this->assertInstanceOf(User::class,$userObj);

        $wrongMail = "test@teest.com";
        $newPass = "123456";
        $stringToHash = $userObj->getId() .
            $userObj->getEmail() .
            $userObj->getPassword() .
            $userObj->getCreatedAt()->getTimestamp();
        $resetToken = hash('sha256',$stringToHash);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(UserService::ERROR_USER_NOT_FOUND_MSG);
        $responce = $userService->resetPassword($wrongMail, $resetToken,$newPass);

    }


    public function testResetPassowrdWrongToken()
    {
        $email = 'ruslankus@yahoo.com';
        $password = 'abc1234';
        $userService = $this->serviceManager->get(UserService::class);
        $userObj = $userService->registerUser($email,$password);

        $this->assertInstanceOf(User::class,$userObj);

        $wrongMail = "test@teest.com";
        $newPass = "123456";
        $stringToHash = $userObj->getId() .
            $wrongMail .
            $userObj->getPassword() .
            $userObj->getCreatedAt()->getTimestamp();
        $wrongToken = hash('sha256',$stringToHash);


        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(UserService::ERROR_UINVALD_RESET_TOKEN_MSG);
        $responce = $userService->resetPassword($email, $wrongToken,$newPass);
    }










}