<?php
namespace MyCompany\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use MyCompany\Service\UserService;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Doctrine\ORM\EntityManager;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplateMapResolver;

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

        //Setting smtp  transport
        $mailTransport = new Smtp();
        $mailOptions = new SmtpOptions([
            'name'              => 'smtp.yandex.ru',
            'host'              => 'smtp.yandex.ru',
            'port'              => 465, // Notice port change for TLS is 587
            'connection_class'  => 'plain',
            'connection_config' => array(
                'username' => 'ruslan@prophp.eu',
                'password' => 'mn867535144',
                'ssl'      => 'ssl')

        ]);

        $mailTransport->setOptions($mailOptions);


        //Setting renerer
        $mailRenderer = new PhpRenderer();
        $resolver = new TemplateMapResolver();
        $resolver->setMap($container->get('Config')['view_manager']['template_map']);
        $mailRenderer->setResolver($resolver);

        $service = new $requestedName($em, $mailTransport, $mailRenderer);

        return $service;
    }
}