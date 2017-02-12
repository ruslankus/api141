<?php
namespace MyCompany\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserResetPasswordController extends AbstractActionController
{

    public function indexAction()
    {
        $view = new ViewModel();
        $view->setTerminal(true);

        $view->setVariables(
            [
                'requestedParams' => $this->params()->fromRoute()
            ]
        );


        return $view;

    }

}