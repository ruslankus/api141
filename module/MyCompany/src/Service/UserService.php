<?php
namespace MyCompany\Service;

use Doctrine\ORM\EntityManagerInterface;
use MyCompany\Entity\User;
use RuntimeException;
use Zend\Crypt\Password\Bcrypt;


class UserService implements UserServiceInterface
{

    protected $entityManager;

    const ERROR_USER_EXIST_CODE = 2;
    const ERROR_USER_EXIST_MSG = "User with such email is allready exist";

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    public function registerUser($emailAddress, $password)
    {
        $userObj = $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => $emailAddress]);

        if($userObj instanceof User){
            throw new RuntimeException(self::ERROR_USER_EXIST_MSG, self::ERROR_USER_EXIST_CODE);
        }

        $userObj = new User();
        $userObj->setEmail($emailAddress);

        $userObj->setPassword($password);

        $bcrypt = new Bcrypt();
        $bcrypt->setCost(14);
        $userObj->setPassword($bcrypt->create($password));

        $userObj->setRoles(array());
        $userObj->setCreatedAt(new \DateTime());
        $userObj->setIsEmailConfirmed(false);
        $userObj->setIsActivated(false);
        $this->entityManager->persist($userObj);

        $this->entityManager->flush();

        return $userObj;

    }

    public function forgotPassword($emailAddress)
    {
        // TODO: Implement forgotPassword() method.
    }

    public function resetPassword($emailAddress, $resetToken, $newPassword)
    {
        // TODO: Implement resetPassword() method.
    }

    public function fetchUser($email)
    {
        // TODO: Implement fetchUser() method.
    }
}
