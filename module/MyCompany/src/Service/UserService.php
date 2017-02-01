<?php
namespace MyCompany\Service;

use Doctrine\ORM\EntityManagerInterface;
use MyCompany\Entity\User;
use RuntimeException;
use Zend\Crypt\Password\Bcrypt;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mime\Part;
use Zend\Mvc\Console\View\ViewModel;
use Zend\View\Renderer\RendererInterface;


class UserService implements UserServiceInterface
{

    protected $entityManager;

    protected $mailTransport;

    protected $mailTemplateRenderer;

    const ERROR_USER_EXIST_CODE = 2;
    const ERROR_USER_EXIST_MSG = "User with such email is allready exist";

    const ERROR_USER_NOT_FOUND_CODE = 3;
    const ERROR_USER_NOT_FOUND_MSG = "Unuble to locate a user with the provided parameters";

    const ERROR_INVALD_RESET_TOKEN_CODE = 4;
    const ERROR_UINVALD_RESET_TOKEN_MSG = "Invalid reset token";

    public function __construct(EntityManagerInterface $em, SmtpTransport $mailTransport, RendererInterface $mailTemplateRenerer )
    {
        $this->entityManager = $em;
        $this->mailTransport = $mailTransport;
        $this->mailTemplateRenderer = $mailTemplateRenerer;
    }


    protected function _getActivationCode(User $useObj)
    {
        return hash('sha256', $useObj->getId().$useObj->getEmail().$useObj->getPassword().$useObj->getCreatedAt()->getTimestamp());
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
        $userObj = $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => $emailAddress]);

        if(! $userObj instanceof User){
            throw new \RuntimeException(self::ERROR_USER_NOT_FOUND_MSG,self::ERROR_USER_NOT_FOUND_CODE);
        }

        /**
         * SEND MAIL
         */

        $resetUrl = 'http://localhost/user-reset-password/'. urlencode($userObj->getEmail()) . "/"
            . $this->_getActivationCode($userObj);

        $viewContent = new ViewModel(compact('resetUrl'));
        $viewContent->setTemplate('MyCompany/mail/user/forgot-password');

        $mailContent = $this->mailTemplateRenderer->render($viewContent);

        $message = new Message();
        $message->setSubject('Welcome to My Company! Please activate your account');
        $message->setFrom('ruslan@prophp.eu');

        $htmlPart = new Part($mailContent);
        $body = new \Zend\Mime\Message();
        $body->setParts(array($htmlPart));
        $message->setTo($userObj->getEmail());
        $message->setBody($body);
        $this->mailTransport->send($message);

        return ['isMailSent' => true];
    }

    public function resetPassword($emailAddress, $resetToken, $newPassword)
    {
        $userObj = $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => $emailAddress]);

        if(! $userObj instanceof User){
            throw new \RuntimeException(self::ERROR_USER_NOT_FOUND_MSG,self::ERROR_USER_NOT_FOUND_CODE);
        }

        $expectedResetToken = $this->_getActivationCode($userObj);

        if($expectedResetToken !== $resetToken){
            throw new \RuntimeException(self::ERROR_UINVALD_RESET_TOKEN_MSG, self::ERROR_INVALD_RESET_TOKEN_CODE);
        }

        $bcrypt = new Bcrypt();
        $bcrypt->setCost(14);
        $userObj->setPassword($bcrypt->create($newPassword));

        $this->entityManager->persist($userObj);
        $this->entityManager->flush();

        return $userObj;

    }

    public function fetchUser($emailAddress)
    {
        $userObj = $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => $emailAddress]);

        if(! $userObj instanceof User){
            throw new \RuntimeException(self::ERROR_USER_NOT_FOUND_MSG,self::ERROR_USER_NOT_FOUND_CODE);
        }

        return $userObj;
    }
}
