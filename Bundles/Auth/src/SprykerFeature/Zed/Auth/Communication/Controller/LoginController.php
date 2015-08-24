<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Auth\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Auth\Business\AuthFacade;
use SprykerFeature\Zed\Auth\Communication\AuthDependencyContainer;
use SprykerFeature\Zed\Auth\Communication\Form\LoginForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method AuthDependencyContainer getDependencyContainer()
 * @method AuthFacade getFacade()
 */
class LoginController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $form = $this->getDependencyContainer()
            ->createLoginForm($request)
        ;
        $form->handleRequest();

        $message = null;

        if ($request->isMethod(Request::METHOD_POST) && $form->isValid()) {
            $formData = $form->getData();

            $isLogged = $this->getFacade()->login($formData[LoginForm::USERNAME], $formData[LoginForm::PASSWORD]);

            if (true === $isLogged) {
                return $this->redirectResponse('/');
            } else {
                $message = 'Authentication failed'; //@todo use flash Messenger
            }
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'message' => $message,
        ]);
    }

}
